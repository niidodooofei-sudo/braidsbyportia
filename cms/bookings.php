<?php
require_once 'includes/auth.php';
cms_check_auth();
$active_page = 'bookings';
$page_title  = 'Bookings';

$all   = cms_bookings();
$today = date('Y-m-d');

// Sort: newest date first
usort($all, fn($a,$b) => strcmp($b['date'].$b['time'], $a['date'].$a['time']));

$filter = $_GET['filter'] ?? 'upcoming';
$search = trim($_GET['q'] ?? '');

$filtered = array_filter($all, function($b) use ($filter, $today, $search) {
    $d = $b['date'] ?? '';
    $s = $b['status'] ?? 'confirmed';
    $match = true;
    if ($filter === 'upcoming')   $match = $d >= $today && $s !== 'cancelled';
    elseif ($filter === 'today')  $match = $d === $today;
    elseif ($filter === 'past')   $match = $d < $today && $s !== 'cancelled';
    elseif ($filter === 'cancelled') $match = $s === 'cancelled';
    // 'all' = no filter

    if ($match && $search) {
        $name  = strtolower($b['customer']['name']  ?? '');
        $email = strtolower($b['customer']['email'] ?? '');
        $svc   = strtolower($b['service_name']      ?? '');
        $match = str_contains($name, strtolower($search))
              || str_contains($email, strtolower($search))
              || str_contains($svc,  strtolower($search));
    }
    return $match;
});
$filtered = array_values($filtered);

ob_start(); ?>

<div class="cms-card">
  <div class="cms-card-head flex-between">
    <div class="cms-filters">
      <?php foreach (['upcoming'=>'Upcoming','today'=>'Today','past'=>'Past','cancelled'=>'Cancelled','all'=>'All'] as $k=>$l): ?>
      <a href="?filter=<?= $k ?><?= $search ? '&q='.urlencode($search) : '' ?>" class="filter-btn <?= $filter===$k?'active':'' ?>"><?= $l ?></a>
      <?php endforeach; ?>
    </div>
    <form method="GET" style="display:flex;gap:.4rem">
      <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
      <input type="text" name="q" class="cms-input cms-input-md" placeholder="Search name, email, service…" value="<?= htmlspecialchars($search) ?>" style="padding:.35rem .7rem;font-size:.75rem">
      <button type="submit" class="btn btn-outline btn-sm">Search</button>
      <?php if ($search): ?><a href="?filter=<?= $filter ?>" class="btn btn-outline btn-sm">✕</a><?php endif; ?>
    </form>
  </div>

  <div style="padding:.6rem 1.4rem;border-bottom:1px solid var(--border2);font-size:.72rem;color:var(--text-faint)">
    <?= count($filtered) ?> booking<?= count($filtered) !== 1 ? 's' : '' ?>
  </div>

  <?php if (empty($filtered)): ?>
  <div class="cms-card-body" style="text-align:center;color:var(--text-faint);padding:2.5rem">No bookings found.</div>
  <?php else: ?>
  <div class="tbl-wrap">
    <table class="cms-tbl">
      <thead><tr>
        <th>Ref</th><th>Date &amp; Time</th><th>Service</th><th>Client</th>
        <th>Contact</th><th>Deposit</th><th>Balance</th><th>Status</th><th></th>
      </tr></thead>
      <tbody>
      <?php foreach ($filtered as $b):
        $status  = $b['status'] ?? 'confirmed';
        $balance = max(0, ($b['price'] ?? 0) - ($b['deposit'] ?? 0));
      ?>
        <tr>
          <td class="text-faint text-sm nowrap"><?= htmlspecialchars($b['id'] ?? '') ?></td>
          <td class="nowrap">
            <strong><?= date('D, M j', strtotime($b['date'])) ?></strong><br>
            <span class="text-dim text-sm"><?= date('g:i A', strtotime($b['time'])) ?></span>
          </td>
          <td style="max-width:160px"><?= htmlspecialchars($b['service_name']) ?></td>
          <td class="nowrap"><?= htmlspecialchars($b['customer']['name'] ?? '') ?></td>
          <td style="font-size:.72rem;color:var(--text-dim)">
            <?= htmlspecialchars($b['customer']['email'] ?? '') ?><br>
            <?= htmlspecialchars($b['customer']['phone'] ?? '') ?>
          </td>
          <td class="text-gold nowrap">$<?= $b['deposit'] ?? 0 ?></td>
          <td class="text-dim nowrap">$<?= $balance ?></td>
          <td><span class="badge badge-<?= htmlspecialchars($status) ?>"><?= ucfirst($status) ?></span></td>
          <td class="nowrap" style="display:flex;gap:.3rem;align-items:center">
            <button class="btn btn-outline btn-xs detail-btn"
              data-booking="<?= htmlspecialchars(json_encode($b)) ?>">Detail</button>
            <?php if ($status === 'confirmed'): ?>
            <button class="btn btn-danger btn-xs cancel-btn"
              data-id="<?= htmlspecialchars($b['id']) ?>"
              data-name="<?= htmlspecialchars($b['customer']['name'] ?? '') ?>"
              data-pi="<?= htmlspecialchars($b['payment_intent_id'] ?? '') ?>">Cancel</button>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

<!-- Detail modal -->
<div id="detail-modal" style="display:none;position:fixed;inset:0;z-index:500;background:rgba(0,0,0,.75);padding:1rem;overflow-y:auto">
  <div style="max-width:480px;margin:2rem auto;background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:1.5rem;position:relative">
    <button onclick="document.getElementById('detail-modal').style.display='none'" style="position:absolute;top:1rem;right:1rem;background:none;border:none;color:var(--text-dim);cursor:pointer;font-size:1.2rem">✕</button>
    <h3 style="font-size:.9rem;font-weight:600;margin-bottom:1.25rem;color:var(--gold)">Booking Detail</h3>
    <div id="detail-body"></div>
  </div>
</div>

<!-- Cancel modal -->
<div id="cancel-modal" style="display:none;position:fixed;inset:0;z-index:500;background:rgba(0,0,0,.75);padding:1rem;overflow-y:auto">
  <div style="max-width:420px;margin:2rem auto;background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:1.5rem">
    <h3 style="font-size:.9rem;font-weight:600;margin-bottom:.75rem">Cancel Booking</h3>
    <p style="font-size:.8rem;color:var(--text-dim);margin-bottom:1rem" id="cancel-msg"></p>
    <div class="cms-field" style="margin-bottom:1rem">
      <label class="cms-label">Issue Stripe Refund?</label>
      <label style="display:flex;align-items:center;gap:.5rem;font-size:.8rem;cursor:pointer">
        <input type="checkbox" id="refund-check" checked style="accent-color:var(--gold)">
        Refund the $<span id="refund-amt">50</span> deposit to the client's card
      </label>
    </div>
    <div style="display:flex;gap:.5rem">
      <button class="btn btn-danger" id="confirm-cancel-btn">Confirm Cancellation</button>
      <button class="btn btn-outline" onclick="document.getElementById('cancel-modal').style.display='none'">Keep Booking</button>
    </div>
    <div id="cancel-result" style="margin-top:.75rem;font-size:.78rem"></div>
  </div>
</div>

<?php
$content = ob_get_clean();
$extra_js = <<<'JS'
<script>
// Detail modal
document.querySelectorAll('.detail-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const b = JSON.parse(btn.dataset.booking);
    const bal = Math.max(0, (b.price||0) - (b.deposit||0));
    document.getElementById('detail-body').innerHTML = `
      <div style="display:grid;gap:.6rem;font-size:.8rem">
        <div style="display:flex;justify-content:space-between"><span style="color:var(--text-dim)">Ref</span><span>${b.id||''}</span></div>
        <div style="display:flex;justify-content:space-between"><span style="color:var(--text-dim)">Service</span><span>${b.service_name||''}</span></div>
        <div style="display:flex;justify-content:space-between"><span style="color:var(--text-dim)">Date</span><span>${b.date||''} at ${b.time||''}</span></div>
        <div style="display:flex;justify-content:space-between"><span style="color:var(--text-dim)">Duration</span><span>${b.duration||0} min</span></div>
        <hr style="border:none;border-top:1px solid var(--border2);margin:.25rem 0">
        <div style="display:flex;justify-content:space-between"><span style="color:var(--text-dim)">Client</span><span>${b.customer?.name||''}</span></div>
        <div style="display:flex;justify-content:space-between"><span style="color:var(--text-dim)">Email</span><span>${b.customer?.email||''}</span></div>
        <div style="display:flex;justify-content:space-between"><span style="color:var(--text-dim)">Phone</span><span>${b.customer?.phone||''}</span></div>
        ${b.customer?.notes ? `<div style="display:flex;justify-content:space-between"><span style="color:var(--text-dim)">Notes</span><span style="max-width:60%;text-align:right">${b.customer.notes}</span></div>` : ''}
        <hr style="border:none;border-top:1px solid var(--border2);margin:.25rem 0">
        <div style="display:flex;justify-content:space-between"><span style="color:var(--text-dim)">Full Price</span><span>$${b.price||0}</span></div>
        <div style="display:flex;justify-content:space-between"><span style="color:var(--text-dim)">Deposit Paid</span><span style="color:#d4af37">$${b.deposit||0}</span></div>
        <div style="display:flex;justify-content:space-between"><span style="color:var(--text-dim)">Balance Due</span><span>$${bal}</span></div>
        <div style="display:flex;justify-content:space-between"><span style="color:var(--text-dim)">Payment ID</span><span style="font-size:.7rem;color:var(--text-faint)">${b.payment_intent_id||'—'}</span></div>
        <div style="display:flex;justify-content:space-between"><span style="color:var(--text-dim)">Status</span><span>${b.status||''}</span></div>
      </div>`;
    document.getElementById('detail-modal').style.display = 'block';
  });
});

// Cancel modal
let cancelId = null, cancelPi = null;
document.querySelectorAll('.cancel-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    cancelId = btn.dataset.id;
    cancelPi = btn.dataset.pi;
    document.getElementById('cancel-msg').textContent =
      `Cancel appointment for ${btn.dataset.name}?`;
    document.getElementById('cancel-result').textContent = '';
    document.getElementById('cancel-modal').style.display = 'block';
  });
});

document.getElementById('confirm-cancel-btn')?.addEventListener('click', async () => {
  const doRefund = document.getElementById('refund-check').checked;
  const btn = document.getElementById('confirm-cancel-btn');
  btn.disabled = true; btn.textContent = 'Cancelling…';
  const res = await fetch('api/cancel-booking.php', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({id: cancelId, refund: doRefund, payment_intent_id: cancelPi})
  });
  const data = await res.json();
  const resultEl = document.getElementById('cancel-result');
  if (data.success) {
    resultEl.style.color = '#4caf78';
    resultEl.textContent = data.message || 'Booking cancelled.';
    setTimeout(() => location.reload(), 1200);
  } else {
    resultEl.style.color = '#e05252';
    resultEl.textContent = data.error || 'Failed. Try again.';
    btn.disabled = false; btn.textContent = 'Confirm Cancellation';
  }
});
</script>
JS;
require 'includes/layout.php';
