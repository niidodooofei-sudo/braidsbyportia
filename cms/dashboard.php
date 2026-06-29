<?php
require_once 'includes/auth.php';
cms_check_auth();
$active_page = 'dashboard';
$page_title  = 'Dashboard';

$all     = cms_bookings();
$today   = date('Y-m-d');
$month   = date('Y-m');

$upcoming   = array_filter($all, fn($b) => ($b['date'] ?? '') >= $today && ($b['status'] ?? '') === 'confirmed');
$today_bks  = array_filter($all, fn($b) => ($b['date'] ?? '') === $today  && ($b['status'] ?? '') !== 'cancelled');
$month_bks  = array_filter($all, fn($b) => str_starts_with($b['date'] ?? '', $month) && ($b['status'] ?? '') !== 'cancelled');
$confirmed  = array_filter($all, fn($b) => ($b['status'] ?? '') === 'confirmed');

$month_revenue = array_sum(array_column(array_values($month_bks), 'deposit'));

// Next 5 upcoming
usort($upcoming, fn($a,$b) => strcmp($a['date'].$a['time'], $b['date'].$b['time']));
$next5 = array_slice(array_values($upcoming), 0, 5);

ob_start(); ?>

<div class="cms-stats">
  <div class="stat-card stat-gold">
    <div class="stat-label">Upcoming Bookings</div>
    <div class="stat-value"><?= count($upcoming) ?></div>
    <div class="stat-sub">confirmed appointments ahead</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Today</div>
    <div class="stat-value"><?= count($today_bks) ?></div>
    <div class="stat-sub"><?= date('l, M j') ?></div>
  </div>
  <div class="stat-card stat-green">
    <div class="stat-label">This Month's Deposits</div>
    <div class="stat-value">$<?= $month_revenue ?></div>
    <div class="stat-sub"><?= count($month_bks) ?> booking<?= count($month_bks) !== 1 ? 's' : '' ?> in <?= date('F') ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Total Bookings</div>
    <div class="stat-value"><?= count($confirmed) ?></div>
    <div class="stat-sub">all time confirmed</div>
  </div>
</div>

<div class="cms-card">
  <div class="cms-card-head">
    <h2>Upcoming Appointments</h2>
    <a href="bookings.php" class="btn btn-outline btn-sm">View all →</a>
  </div>
  <?php if (empty($next5)): ?>
  <div class="cms-card-body" style="text-align:center;color:var(--text-faint);padding:2.5rem">
    No upcoming bookings yet.
  </div>
  <?php else: ?>
  <div class="tbl-wrap">
    <table class="cms-tbl">
      <thead><tr>
        <th>Date &amp; Time</th><th>Service</th><th>Client</th><th>Phone</th><th>Deposit</th>
      </tr></thead>
      <tbody>
      <?php foreach ($next5 as $b): ?>
        <tr>
          <td class="nowrap">
            <strong><?= date('D, M j', strtotime($b['date'])) ?></strong><br>
            <span class="text-dim text-sm"><?= date('g:i A', strtotime($b['time'])) ?></span>
          </td>
          <td><?= htmlspecialchars($b['service_name']) ?></td>
          <td><?= htmlspecialchars($b['customer']['name'] ?? '') ?></td>
          <td class="text-dim text-sm"><?= htmlspecialchars($b['customer']['phone'] ?? '') ?></td>
          <td class="text-gold">$<?= $b['deposit'] ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require 'includes/layout.php';
