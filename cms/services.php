<?php
require_once 'includes/auth.php';
cms_check_auth();
$active_page = 'services';
$page_title  = 'Services & Pricing';

$services = require CMS_ROOT . '/config/services.php';

ob_start(); ?>

<div style="margin-bottom:1rem" class="flex-between">
  <p class="text-faint">Edit your service names, descriptions, and pricing. Changes go live immediately.</p>
  <button class="btn btn-gold" id="save-all-btn">Save All Changes</button>
</div>
<div id="save-alert" style="display:none" class="alert alert-ok">Changes saved successfully.</div>
<div id="save-err"   style="display:none" class="alert alert-err"></div>

<?php foreach ($services as $cat_key => $cat): ?>
<div class="svc-cat open" id="cat-<?= htmlspecialchars($cat_key) ?>">
  <div class="svc-cat-head" onclick="this.parentElement.classList.toggle('open')">
    <h3><?= htmlspecialchars($cat['name']) ?> <span style="font-weight:400;color:var(--text-faint);font-size:.75rem">(<?= $cat['type'] ?>)</span></h3>
    <span class="svc-cat-toggle">▾</span>
  </div>
  <div class="svc-cat-body">

    <!-- Category meta -->
    <div class="cms-form-row cols-2" style="margin-bottom:1rem">
      <div class="cms-field">
        <label class="cms-label">Category Name</label>
        <input type="text" class="cms-input" data-cat="<?= $cat_key ?>" data-field="name" value="<?= htmlspecialchars($cat['name']) ?>">
      </div>
      <div class="cms-field">
        <label class="cms-label">Description</label>
        <input type="text" class="cms-input" data-cat="<?= $cat_key ?>" data-field="desc" value="<?= htmlspecialchars($cat['desc'] ?? '') ?>">
      </div>
    </div>

    <?php if ($cat['type'] === 'list'): ?>
    <!-- List-type services -->
    <table class="svc-list-tbl" data-cat="<?= $cat_key ?>">
      <thead><tr><th>Service Name</th><th style="width:90px">Price ($)</th><th style="width:100px">Duration (min)</th><th style="width:50px"></th></tr></thead>
      <tbody>
        <?php $all_items = array_merge($cat['services'] ?? [], $cat['extras'] ?? []);
        foreach ($all_items as $i => $svc): ?>
        <tr data-idx="<?= $i ?>">
          <td><input type="text" value="<?= htmlspecialchars($svc['name']) ?>" data-field="name"></td>
          <td><input type="number" value="<?= $svc['price'] ?>" data-field="price" min="0"></td>
          <td><input type="number" value="<?= $svc['duration'] ?>" data-field="duration" min="0"></td>
          <td><button type="button" class="btn btn-danger btn-xs del-row-btn" title="Remove">✕</button></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <button type="button" class="btn btn-outline btn-sm add-row-btn mt-1" data-cat="<?= $cat_key ?>" style="margin-top:.6rem">+ Add Service</button>

    <?php elseif ($cat['type'] === 'matrix'): ?>
    <!-- Matrix-type: price grid -->
    <p class="text-faint" style="margin-bottom:.6rem;font-size:.72rem">Prices — rows = sizes, columns = lengths</p>
    <div style="overflow-x:auto;margin-bottom:1rem">
      <table class="matrix-tbl" data-cat="<?= $cat_key ?>" data-mtype="prices">
        <thead><tr>
          <th>Size \ Length</th>
          <?php foreach ($cat['lengths'] as $l): ?><th><?= htmlspecialchars($l) ?></th><?php endforeach; ?>
        </tr></thead>
        <tbody>
        <?php foreach ($cat['sizes'] as $size): ?>
          <tr>
            <th style="text-align:left"><?= htmlspecialchars($size) ?></th>
            <?php foreach ($cat['lengths'] as $len): ?>
            <td><input type="number" value="<?= $cat['prices'][$size][$len] ?? '' ?>" data-size="<?= $size ?>" data-len="<?= $len ?>" min="0"></td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <p class="text-faint" style="margin-bottom:.6rem;font-size:.72rem">Durations (minutes)</p>
    <div style="overflow-x:auto;margin-bottom:.75rem">
      <table class="matrix-tbl" data-cat="<?= $cat_key ?>" data-mtype="durations">
        <thead><tr>
          <th>Size \ Length</th>
          <?php foreach ($cat['lengths'] as $l): ?><th><?= htmlspecialchars($l) ?></th><?php endforeach; ?>
        </tr></thead>
        <tbody>
        <?php foreach ($cat['sizes'] as $size): ?>
          <tr>
            <th style="text-align:left"><?= htmlspecialchars($size) ?></th>
            <?php foreach ($cat['lengths'] as $len): ?>
            <td><input type="number" value="<?= $cat['durations'][$size][$len] ?? '' ?>" data-size="<?= $size ?>" data-len="<?= $len ?>" min="0"></td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php if (!empty($cat['extras'])): ?>
    <p class="text-faint" style="margin-bottom:.4rem;font-size:.72rem">Extras / Add-ons</p>
    <table class="svc-list-tbl" data-cat="<?= $cat_key ?>" data-extras="1">
      <thead><tr><th>Name</th><th style="width:90px">Price ($)</th><th style="width:100px">Duration (min)</th><th style="width:50px"></th></tr></thead>
      <tbody>
        <?php foreach ($cat['extras'] as $i => $e): ?>
        <tr data-idx="<?= $i ?>">
          <td><input type="text" value="<?= htmlspecialchars($e['name']) ?>" data-field="name"></td>
          <td><input type="number" value="<?= $e['price'] ?>" data-field="price" min="0"></td>
          <td><input type="number" value="<?= $e['duration'] ?>" data-field="duration" min="0"></td>
          <td><button type="button" class="btn btn-danger btn-xs del-row-btn">✕</button></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
    <?php endif; ?>

  </div>
</div>
<?php endforeach; ?>

<?php
$content = ob_get_clean();
$svc_json = json_encode($services, JSON_HEX_TAG | JSON_HEX_AMP);
$extra_js = <<<JS
<script>
let services = {$svc_json};

// Delete list row
document.querySelectorAll('.del-row-btn').forEach(btn => {
  btn.addEventListener('click', () => btn.closest('tr').remove());
});

// Add list row
document.querySelectorAll('.add-row-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const cat = btn.dataset.cat;
    const tbody = document.querySelector('.svc-list-tbl[data-cat="'+cat+'"] tbody');
    const idx = tbody.querySelectorAll('tr').length;
    const tr = document.createElement('tr');
    tr.dataset.idx = idx;
    tr.innerHTML = '<td><input type="text" value="New Service" data-field="name"></td>' +
      '<td><input type="number" value="0" data-field="price" min="0"></td>' +
      '<td><input type="number" value="60" data-field="duration" min="0"></td>' +
      '<td><button type="button" class="btn btn-danger btn-xs del-row-btn">✕</button></td>';
    tr.querySelector('.del-row-btn').addEventListener('click', () => tr.remove());
    tbody.appendChild(tr);
  });
});

// Collect and save
document.getElementById('save-all-btn').addEventListener('click', async () => {
  const btn = document.getElementById('save-all-btn');
  btn.disabled = true; btn.textContent = 'Saving…';
  document.getElementById('save-alert').style.display = 'none';
  document.getElementById('save-err').style.display = 'none';

  const updated = JSON.parse(JSON.stringify(services)); // clone

  // Update meta fields
  document.querySelectorAll('[data-cat][data-field]').forEach(el => {
    const cat = el.dataset.cat, field = el.dataset.field;
    if (updated[cat]) updated[cat][field] = el.value;
  });

  // Update list services
  document.querySelectorAll('.svc-list-tbl[data-cat]').forEach(tbl => {
    const cat = tbl.dataset.cat;
    const isExtras = tbl.dataset.extras === '1';
    const rows = tbl.querySelectorAll('tbody tr');
    const list = [];
    rows.forEach((row, i) => {
      const nameEl = row.querySelector('[data-field="name"]');
      const priceEl = row.querySelector('[data-field="price"]');
      const durEl   = row.querySelector('[data-field="duration"]');
      if (!nameEl) return;
      const id = (nameEl.value||'').toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-|-\$/g,'') + '-' + i;
      list.push({ id, name: nameEl.value, price: parseInt(priceEl?.value)||0, duration: parseInt(durEl?.value)||0 });
    });
    if (isExtras) updated[cat].extras = list;
    else          updated[cat].services = list;
  });

  // Update matrix prices/durations
  document.querySelectorAll('.matrix-tbl[data-cat]').forEach(tbl => {
    const cat = tbl.dataset.cat, mtype = tbl.dataset.mtype;
    if (!updated[cat]) return;
    tbl.querySelectorAll('tbody tr').forEach(row => {
      row.querySelectorAll('td input').forEach(inp => {
        const size = inp.dataset.size, len = inp.dataset.len;
        if (!updated[cat][mtype]) updated[cat][mtype] = {};
        if (!updated[cat][mtype][size]) updated[cat][mtype][size] = {};
        updated[cat][mtype][size][len] = parseInt(inp.value)||0;
      });
    });
  });

  const res = await fetch('api/save-services.php', {
    method:'POST', headers:{'Content-Type':'application/json'},
    body: JSON.stringify(updated)
  });
  const data = await res.json();
  btn.disabled = false; btn.textContent = 'Save All Changes';
  if (data.success) {
    services = updated;
    document.getElementById('save-alert').style.display = 'block';
    setTimeout(() => document.getElementById('save-alert').style.display='none', 3000);
  } else {
    document.getElementById('save-err').textContent = data.error || 'Save failed.';
    document.getElementById('save-err').style.display = 'block';
  }
});
</script>
JS;
require 'includes/layout.php';
