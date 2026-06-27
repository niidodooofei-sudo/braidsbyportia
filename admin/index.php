<?php
// Basic auth — change these credentials before deploying
$admin_user = 'portia';
$admin_pass = 'change-this-password';

if (!isset($_SERVER['PHP_AUTH_USER']) ||
    $_SERVER['PHP_AUTH_USER'] !== $admin_user ||
    $_SERVER['PHP_AUTH_PW']   !== $admin_pass) {
    header('WWW-Authenticate: Basic realm="Braids by Portia Admin"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Access denied.'; exit;
}

$all = json_decode(file_get_contents(dirname(__DIR__) . '/data/bookings.json'), true) ?? [];
usort($all, fn($a,$b) => strcmp($b['date'].$b['time'], $a['date'].$a['time']));

$filter = $_GET['filter'] ?? 'upcoming';
$today  = date('Y-m-d');
$bookings = array_filter($all, function($b) use ($filter, $today) {
    if ($filter === 'upcoming') return $b['date'] >= $today;
    if ($filter === 'past')     return $b['date'] <  $today;
    return true;
});
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin | Braids by Portia</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:'Poppins',sans-serif;background:#0a0a0a;color:#f8f3e7;min-height:100vh}
    .admin-header{background:#131008;border-bottom:1px solid rgba(212,175,55,.2);padding:1rem 2rem;display:flex;align-items:center;justify-content:space-between}
    .admin-header h1{font-size:1.2rem;color:#d4af37}
    .admin-header span{font-size:.8rem;color:rgba(248,243,231,.5)}
    .admin-body{padding:2rem}
    .filters{display:flex;gap:.75rem;margin-bottom:1.5rem}
    .filters a{padding:.45rem 1.1rem;border-radius:50px;border:1px solid rgba(212,175,55,.3);color:rgba(248,243,231,.7);font-size:.8rem;text-decoration:none;transition:.2s}
    .filters a.active,.filters a:hover{background:#d4af37;color:#000;border-color:#d4af37}
    .count{font-size:.85rem;color:rgba(248,243,231,.5);margin-bottom:1rem}
    table{width:100%;border-collapse:collapse;font-size:.85rem}
    th{text-align:left;padding:.6rem 1rem;font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;color:rgba(212,175,55,.7);border-bottom:1px solid rgba(212,175,55,.15)}
    td{padding:.75rem 1rem;border-bottom:1px solid rgba(248,243,231,.06);vertical-align:top}
    tr:hover td{background:rgba(212,175,55,.04)}
    .badge{display:inline-block;padding:.2rem .6rem;border-radius:50px;font-size:.7rem;font-weight:600}
    .badge-confirmed{background:rgba(39,174,96,.15);color:#6ee09b}
    .tag-service{font-size:.78rem;color:#d4af37}
    .tag-notes{font-size:.78rem;color:rgba(248,243,231,.5);font-style:italic}
    .no-bookings{text-align:center;padding:3rem;color:rgba(248,243,231,.4)}
    @media(max-width:700px){table,thead,tbody,th,td,tr{display:block}th{display:none}td{padding:.4rem .6rem}td::before{content:attr(data-label);font-size:.65rem;color:#d4af37;display:block;margin-bottom:.2rem}}
  </style>
</head>
<body>
<div class="admin-header">
  <h1>Braids by Portia — Bookings</h1>
  <span><?= date('l, F j, Y') ?></span>
</div>
<div class="admin-body">
  <div class="filters">
    <a href="?filter=upcoming" class="<?= $filter==='upcoming'?'active':'' ?>">Upcoming</a>
    <a href="?filter=past"     class="<?= $filter==='past'    ?'active':'' ?>">Past</a>
    <a href="?filter=all"      class="<?= $filter==='all'     ?'active':'' ?>">All</a>
  </div>
  <p class="count"><?= count($bookings) ?> booking<?= count($bookings)!==1?'s':'' ?></p>

  <?php if (empty($bookings)): ?>
  <div class="no-bookings">No bookings found.</div>
  <?php else: ?>
  <table>
    <thead>
      <tr><th>Date & Time</th><th>Service</th><th>Client</th><th>Contact</th><th>Deposit</th><th>Status</th></tr>
    </thead>
    <tbody>
      <?php foreach ($bookings as $b): ?>
      <tr>
        <td data-label="Date & Time">
          <strong><?= date('D, M j', strtotime($b['date'])) ?></strong><br>
          <?= date('g:i A', strtotime($b['time'])) ?>
        </td>
        <td data-label="Service">
          <span class="tag-service"><?= htmlspecialchars($b['service_name']) ?></span>
          <?php if (!empty($b['customer']['notes'])): ?>
          <br><span class="tag-notes">"<?= htmlspecialchars(substr($b['customer']['notes'],0,60)) ?>…"</span>
          <?php endif; ?>
        </td>
        <td data-label="Client"><?= htmlspecialchars($b['customer']['name']) ?></td>
        <td data-label="Contact">
          <?= htmlspecialchars($b['customer']['email']) ?><br>
          <?= htmlspecialchars($b['customer']['phone']) ?>
        </td>
        <td data-label="Deposit">$<?= $b['deposit'] ?></td>
        <td data-label="Status"><span class="badge badge-confirmed"><?= htmlspecialchars($b['status']) ?></span></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>
</body>
</html>
