<?php
$page_title = 'Booking Confirmed | Braids by Portia';
require 'includes/header.php';

$ref = preg_replace('/[^a-z0-9\-]/', '', strtolower($_GET['ref'] ?? ''));
$booking = null;

if ($ref) {
    $path = __DIR__ . '/data/bookings.json';
    $all  = json_decode(file_get_contents($path), true) ?? [];
    foreach ($all as $b) {
        if (($b['id'] ?? '') === $ref) { $booking = $b; break; }
    }
}
?>

<main>
  <div class="success-page">
    <div class="container">
      <?php if ($booking): ?>
      <div class="success-card">
        <div class="success-icon">✓</div>
        <h1>You're all booked!</h1>
        <p class="success-sub">A confirmation has been sent to <strong><?= htmlspecialchars($booking['customer']['email']) ?></strong>.</p>

        <div class="success-details">
          <div class="sd-row"><span>Service</span><strong><?= htmlspecialchars($booking['service_name']) ?></strong></div>
          <div class="sd-row"><span>Date</span><strong><?= date('l, F j, Y', strtotime($booking['date'])) ?></strong></div>
          <div class="sd-row"><span>Time</span><strong><?= date('g:i A', strtotime($booking['time'])) ?></strong></div>
          <div class="sd-row"><span>Name</span><strong><?= htmlspecialchars($booking['customer']['name']) ?></strong></div>
          <div class="sd-row sd-deposit"><span>Deposit Paid</span><strong>$<?= $booking['deposit'] ?></strong></div>
          <div class="sd-row"><span>Balance Due</span><strong>$<?= $booking['price'] - $booking['deposit'] ?> at appointment</strong></div>
        </div>

        <p class="success-note">Please arrive 5–10 minutes before your appointment. If you need to reschedule, contact us at least 24 hours in advance.</p>

        <div class="success-actions">
          <a href="services.php" class="btn-outline-gold">Browse Services</a>
          <a href="index.php" class="btn-gold">Back to Home</a>
        </div>
      </div>
      <?php else: ?>
      <div class="success-card">
        <div class="success-icon">✓</div>
        <h1>Booking Confirmed!</h1>
        <p class="success-sub">Thank you for booking with Braids by Portia. A confirmation email is on its way.</p>
        <div class="success-actions">
          <a href="index.php" class="btn-gold">Back to Home</a>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</main>

<?php require 'includes/footer.php'; ?>
