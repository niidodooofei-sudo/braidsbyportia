<?php
require_once 'includes/auth.php';
cms_check_auth();
$active_page = 'dashboard';
$page_title  = 'Dashboard';

$all   = cms_bookings();
$today = date('Y-m-d');
$month = date('Y-m');

$upcoming  = array_filter($all, fn($b) => ($b['date'] ?? '') >= $today && ($b['status'] ?? '') === 'confirmed');
$today_bks = array_filter($all, fn($b) => ($b['date'] ?? '') === $today && ($b['status'] ?? '') !== 'cancelled');
$month_bks = array_filter($all, fn($b) => str_starts_with($b['date'] ?? '', $month) && ($b['status'] ?? '') !== 'cancelled');
$confirmed = array_filter($all, fn($b) => ($b['status'] ?? '') === 'confirmed');

$month_revenue = array_sum(array_column(array_values($month_bks), 'deposit'));

// Next 5 upcoming sorted soonest first
usort($upcoming, fn($a,$b) => strcmp($a['date'].$a['time'], $b['date'].$b['time']));
$next5 = array_slice(array_values($upcoming), 0, 5);

// Recent 5 (all time, newest first)
$recent = $all;
usort($recent, fn($a,$b) => strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''));
$recent5 = array_slice($recent, 0, 5);

ob_start(); ?>

<div class="cms-stats">
  <div class="stat-card stat-card-gold">
    <div class="stat-label">Upcoming</div>
    <div class="stat-value"><?= count($upcoming) ?></div>
    <div class="stat-sub">confirmed ahead</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Today</div>
    <div class="stat-value"><?= count($today_bks) ?></div>
    <div class="stat-sub"><?= date('l, M j') ?></div>
  </div>
  <div class="stat-card stat-card-green">
    <div class="stat-label">This Month's Deposits</div>
    <div class="stat-value">$<?= number_format($month_revenue) ?></div>
    <div class="stat-sub"><?= count($month_bks) ?> booking<?= count($month_bks) !== 1 ? 's' : '' ?> in <?= date('F') ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-label">All Time</div>
    <div class="stat-value"><?= count($confirmed) ?></div>
    <div class="stat-sub">total confirmed bookings</div>
  </div>
</div>

<div class="cms-grid-2" style="gap:1.25rem">

  <!-- Upcoming appointments -->
  <div class="cms-card">
    <div class="cms-card-head">
      <h2>Upcoming Appointments</h2>
      <a href="bookings.php?filter=upcoming" class="btn btn-outline btn-sm">View all</a>
    </div>
    <?php if (empty($next5)): ?>
    <div class="cms-card-body" style="text-align:center;color:var(--text-faint);padding:2.5rem 1.4rem">
      <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" style="opacity:.3;margin-bottom:.75rem"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
      <p style="font-size:.8rem">No upcoming bookings yet.</p>
    </div>
    <?php else: ?>
    <div>
      <?php foreach ($next5 as $i => $b): ?>
      <div style="display:flex;align-items:center;gap:1rem;padding:.85rem 1.4rem;<?= $i < count($next5)-1 ? 'border-bottom:1px solid var(--border2)' : '' ?>;transition:background .15s" onmouseover="this.style.background='rgba(255,255,255,.02)'" onmouseout="this.style.background=''">
        <!-- Date pill -->
        <div style="min-width:46px;text-align:center;background:var(--s2);border:1px solid var(--border);border-radius:8px;padding:.4rem .3rem;flex-shrink:0">
          <div style="font-size:.58rem;text-transform:uppercase;letter-spacing:.08em;color:var(--text-faint)"><?= date('M', strtotime($b['date'])) ?></div>
          <div style="font-size:1.15rem;font-weight:700;line-height:1;color:var(--gold)"><?= date('j', strtotime($b['date'])) ?></div>
        </div>
        <!-- Info -->
        <div style="flex:1;min-width:0">
          <div style="font-size:.82rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= htmlspecialchars($b['customer']['name'] ?? '') ?></div>
          <div style="font-size:.72rem;color:var(--text-dim);white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= htmlspecialchars($b['service_name']) ?></div>
        </div>
        <!-- Time + deposit -->
        <div style="text-align:right;flex-shrink:0">
          <div style="font-size:.78rem;color:var(--text-dim)"><?= date('g:i A', strtotime($b['time'])) ?></div>
          <div style="font-size:.72rem;color:var(--gold)">$<?= $b['deposit'] ?> dep.</div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>

  <!-- Recent activity -->
  <div class="cms-card">
    <div class="cms-card-head">
      <h2>Recent Bookings</h2>
      <a href="bookings.php?filter=all" class="btn btn-outline btn-sm">View all</a>
    </div>
    <?php if (empty($recent5)): ?>
    <div class="cms-card-body" style="text-align:center;color:var(--text-faint);padding:2.5rem 1.4rem">
      <p style="font-size:.8rem">No bookings yet.</p>
    </div>
    <?php else: ?>
    <div>
      <?php foreach ($recent5 as $i => $b):
        $status = $b['status'] ?? 'confirmed';
      ?>
      <div style="display:flex;align-items:center;gap:.85rem;padding:.8rem 1.4rem;<?= $i < count($recent5)-1 ? 'border-bottom:1px solid var(--border2)' : '' ?>">
        <!-- Avatar circle -->
        <div style="width:34px;height:34px;border-radius:50%;background:var(--gold-soft);border:1px solid var(--gold-border);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.8rem;font-weight:700;color:var(--gold)">
          <?= mb_strtoupper(mb_substr($b['customer']['name'] ?? '?', 0, 1)) ?>
        </div>
        <div style="flex:1;min-width:0">
          <div style="font-size:.82rem;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= htmlspecialchars($b['customer']['name'] ?? '') ?></div>
          <div style="font-size:.7rem;color:var(--text-faint)"><?= date('M j', strtotime($b['date'])) ?> · <?= htmlspecialchars(substr($b['service_name'], 0, 28)) ?></div>
        </div>
        <span class="badge badge-<?= htmlspecialchars($status) ?>"><?= ucfirst($status) ?></span>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>

</div>

<?php
$content = ob_get_clean();
require 'includes/layout.php';
