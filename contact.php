<?php
$page_title = 'Contact | Braids by Portia';
$page_desc  = 'Get in touch with Braids by Portia — ask a question, check availability, or learn more about our styles.';

$success = false;
$errors  = [];
$v       = ['name' => '', 'email' => '', 'phone' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $v['name']    = trim($_POST['name']    ?? '');
    $v['email']   = trim($_POST['email']   ?? '');
    $v['phone']   = trim($_POST['phone']   ?? '');
    $v['message'] = trim($_POST['message'] ?? '');

    if (!$v['name'])                                    $errors[] = 'name';
    if (!filter_var($v['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'email';
    if (!$v['message'])                                 $errors[] = 'message';

    if (!$errors) {
        $to      = 'YOUR_EMAIL@example.com'; // ← update before going live
        $subject = 'New message from Braids by Portia website';
        $body    = "Name:    {$v['name']}\n"
                 . "Email:   {$v['email']}\n"
                 . "Phone:   {$v['phone']}\n\n"
                 . "Message:\n{$v['message']}";
        $headers = "From: noreply@braidsbyportia.com\r\n"
                 . "Reply-To: {$v['email']}\r\n";
        mail($to, $subject, $body, $headers);
        $success = true;
    }
}

require 'includes/header.php';
?>

<main>
  <div class="page-banner">
    <div class="container">
      <span class="eyebrow">Get In Touch</span>
      <h1>We'd Love to Hear from You</h1>
      <p>Have a question about styles, availability, or care? Send us a message and we'll get back to you within 24 hours.</p>
    </div>
  </div>

  <section class="contact-section" style="padding-top:3rem">
    <div class="container contact-grid">

      <!-- Contact form -->
      <div class="contact-form-card reveal">
        <h3>Send a Message</h3>
        <p class="cf-intro">Fill in the form below and we'll reply as soon as possible.</p>

        <?php if ($success): ?>
        <div class="cf-success">
          <div class="cf-success-icon">✓</div>
          <h4>Message sent!</h4>
          <p>Thank you, <?= htmlspecialchars($v['name']) ?>. We'll be in touch within 24 hours.</p>
          <a href="/contact.php" class="btn-outline-gold" style="margin-top:1.25rem;display:inline-block">Send another</a>
        </div>
        <?php else: ?>
        <form method="POST" class="cf-form" novalidate>
          <div class="cf-2col">
            <div class="cf-row">
              <label for="cf-name">Your Name <span class="cf-req">*</span></label>
              <input type="text" id="cf-name" name="name"
                     value="<?= htmlspecialchars($v['name']) ?>"
                     placeholder="e.g. Amara Johnson"
                     class="<?= in_array('name', $errors) ? 'cf-error' : '' ?>"
                     required>
              <?php if (in_array('name', $errors)): ?><span class="cf-err-msg">Please enter your name</span><?php endif; ?>
            </div>
            <div class="cf-row">
              <label for="cf-email">Email Address <span class="cf-req">*</span></label>
              <input type="email" id="cf-email" name="email"
                     value="<?= htmlspecialchars($v['email']) ?>"
                     placeholder="you@example.com"
                     class="<?= in_array('email', $errors) ? 'cf-error' : '' ?>"
                     required>
              <?php if (in_array('email', $errors)): ?><span class="cf-err-msg">Please enter a valid email</span><?php endif; ?>
            </div>
          </div>

          <div class="cf-row">
            <label for="cf-phone">Phone Number <span class="cf-optional">(optional)</span></label>
            <input type="tel" id="cf-phone" name="phone"
                   value="<?= htmlspecialchars($v['phone']) ?>"
                   placeholder="(555) 000-0000">
          </div>

          <div class="cf-row">
            <label for="cf-message">Your Message <span class="cf-req">*</span></label>
            <textarea id="cf-message" name="message" rows="5"
                      placeholder="Tell us what you have in mind — style questions, consultation requests, anything at all…"
                      class="<?= in_array('message', $errors) ? 'cf-error' : '' ?>"
                      required><?= htmlspecialchars($v['message']) ?></textarea>
            <?php if (in_array('message', $errors)): ?><span class="cf-err-msg">Please enter a message</span><?php endif; ?>
          </div>

          <button type="submit" class="btn-gold cf-submit">Send Message</button>
        </form>
        <?php endif; ?>
      </div>

      <!-- Sidebar -->
      <div class="contact-sidebar">
        <div class="contact-info-card reveal">
          <h3>Details</h3>
          <div class="contact-info-list">
            <div class="ci-item">
              <span class="ci-label">Availability</span>
              <span class="ci-value">By appointment only (Tue – Sat)</span>
            </div>
            <div class="ci-item">
              <span class="ci-label">Hair Provided</span>
              <span class="ci-value">Premium extensions included</span>
            </div>
            <div class="ci-item">
              <span class="ci-label">Deposit</span>
              <span class="ci-value">$50 secures your slot — balance due at appointment</span>
            </div>
            <div class="ci-item">
              <span class="ci-label">Cancellations</span>
              <span class="ci-value">Please cancel at least 24 hrs in advance</span>
            </div>
            <div class="ci-item">
              <span class="ci-label">Response Time</span>
              <span class="ci-value">We aim to reply within 24 hours</span>
            </div>
          </div>
        </div>

        <div class="contact-book-strip reveal">
          <p>Ready to secure your spot?</p>
          <a href="/booking.php" class="btn-gold">Book Your Appointment</a>
          <span class="booking-note">Secure payments powered by Stripe</span>
        </div>
      </div>

    </div>
  </section>
</main>

<?php require 'includes/footer.php'; ?>
