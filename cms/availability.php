<?php
require_once 'includes/auth.php';
cms_check_auth();
$active_page = 'availability';
$page_title  = 'Availability';

$avail = require CMS_ROOT . '/config/availability.php';
$days  = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];

ob_start(); ?>

<div id="av-alert" style="display:none" class="alert alert-ok">Availability saved successfully.</div>
<div id="av-err"   style="display:none" class="alert alert-err"></div>

<div class="cms-grid-2" style="gap:1.25rem">

  <!-- Working Hours -->
  <div class="cms-card">
    <div class="cms-card-head"><h2>Working Hours</h2></div>
    <div class="cms-card-body">

      <div class="cms-field" style="margin-bottom:1.1rem">
        <label class="cms-label">Working Days</label>
        <div style="display:flex;flex-wrap:wrap;gap:.4rem;margin-top:.3rem">
          <?php foreach ($days as $i => $d): ?>
          <label style="display:flex;align-items:center;gap:.3rem;font-size:.78rem;cursor:pointer;padding:.3rem .65rem;border:1px solid var(--border2);border-radius:5px;background:var(--surface2)">
            <input type="checkbox" id="day-<?= $i ?>" value="<?= $i ?>"
              <?= in_array($i, $avail['working_days']) ? 'checked' : '' ?>
              style="accent-color:var(--gold)">
            <?= $d ?>
          </label>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="cms-form-row cols-2">
        <div class="cms-field">
          <label class="cms-label">Open Time</label>
          <input type="time" id="start-time" class="cms-input" value="<?= htmlspecialchars($avail['start_time']) ?>">
        </div>
        <div class="cms-field">
          <label class="cms-label">Close Time</label>
          <input type="time" id="end-time" class="cms-input" value="<?= htmlspecialchars($avail['end_time']) ?>">
        </div>
      </div>

      <div class="cms-form-row cols-2" style="margin-top:1rem">
        <div class="cms-field">
          <label class="cms-label">Slot Interval (minutes)</label>
          <select id="slot-interval" class="cms-select">
            <?php foreach ([15,30,45,60] as $m): ?>
            <option value="<?= $m ?>" <?= $avail['slot_interval'] == $m ? 'selected' : '' ?>><?= $m ?> min</option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="cms-field">
          <label class="cms-label">Book-Ahead Limit (weeks)</label>
          <input type="number" id="max-weeks" class="cms-input" min="1" max="52" value="<?= (int)$avail['max_weeks_ahead'] ?>">
        </div>
      </div>

    </div>
  </div>

  <!-- Blocked Dates -->
  <div class="cms-card">
    <div class="cms-card-head"><h2>Blocked / Closed Dates</h2></div>
    <div class="cms-card-body">
      <p class="text-faint" style="margin-bottom:1rem">Clients cannot book on these dates (holidays, days off, etc.).</p>

      <div class="cms-field" style="margin-bottom:.75rem">
        <label class="cms-label">Add Date</label>
        <div style="display:flex;gap:.4rem">
          <input type="date" id="block-date-input" class="cms-input" min="<?= date('Y-m-d') ?>">
          <button class="btn btn-outline btn-sm" id="add-block-btn" style="flex-shrink:0">Add</button>
        </div>
      </div>

      <div class="blocked-list" id="blocked-list">
        <?php foreach ($avail['blocked_dates'] as $bd): ?>
        <span class="blocked-chip" data-date="<?= htmlspecialchars($bd) ?>">
          <?= date('M j, Y', strtotime($bd)) ?>
          <button onclick="removeBlocked('<?= htmlspecialchars($bd) ?>')" title="Remove">✕</button>
        </span>
        <?php endforeach; ?>
      </div>
      <?php if (empty($avail['blocked_dates'])): ?>
      <p class="text-faint" id="no-blocked" style="font-size:.75rem;margin-top:.5rem">No blocked dates.</p>
      <?php else: ?>
      <p class="text-faint" id="no-blocked" style="display:none;font-size:.75rem;margin-top:.5rem">No blocked dates.</p>
      <?php endif; ?>

    </div>
  </div>

</div>

<div style="margin-top:1.25rem;text-align:right">
  <button class="btn btn-gold" id="save-av-btn">Save Availability</button>
</div>

<?php
$content = ob_get_clean();
$blocked_json = json_encode($avail['blocked_dates']);
$extra_js = <<<JS
<script>
let blockedDates = {$blocked_json};

function renderBlocked() {
  const list = document.getElementById('blocked-list');
  const none = document.getElementById('no-blocked');
  list.innerHTML = '';
  blockedDates.sort().forEach(d => {
    const chip = document.createElement('span');
    chip.className = 'blocked-chip';
    chip.dataset.date = d;
    const dt = new Date(d + 'T12:00:00');
    chip.innerHTML = dt.toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'}) +
      ' <button onclick="removeBlocked(\\''+d+'\\')">✕</button>';
    list.appendChild(chip);
  });
  none.style.display = blockedDates.length ? 'none' : 'block';
}

function removeBlocked(date) {
  blockedDates = blockedDates.filter(d => d !== date);
  renderBlocked();
}

document.getElementById('add-block-btn').addEventListener('click', () => {
  const val = document.getElementById('block-date-input').value;
  if (!val || blockedDates.includes(val)) return;
  blockedDates.push(val);
  renderBlocked();
  document.getElementById('block-date-input').value = '';
});

document.getElementById('save-av-btn').addEventListener('click', async () => {
  const btn = document.getElementById('save-av-btn');
  btn.disabled = true; btn.textContent = 'Saving…';
  document.getElementById('av-alert').style.display = 'none';
  document.getElementById('av-err').style.display = 'none';

  const working_days = [];
  document.querySelectorAll('input[id^="day-"]:checked').forEach(cb => working_days.push(parseInt(cb.value)));

  const payload = {
    working_days,
    start_time:     document.getElementById('start-time').value,
    end_time:       document.getElementById('end-time').value,
    slot_interval:  parseInt(document.getElementById('slot-interval').value),
    max_weeks_ahead:parseInt(document.getElementById('max-weeks').value),
    blocked_dates:  blockedDates.sort()
  };

  const res = await fetch('api/save-availability.php', {
    method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(payload)
  });
  const data = await res.json();
  btn.disabled = false; btn.textContent = 'Save Availability';
  if (data.success) {
    document.getElementById('av-alert').style.display = 'block';
    setTimeout(() => document.getElementById('av-alert').style.display='none', 3000);
  } else {
    document.getElementById('av-err').textContent = data.error || 'Save failed.';
    document.getElementById('av-err').style.display = 'block';
  }
});
</script>
JS;
require 'includes/layout.php';
