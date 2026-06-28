<?php $_b = APP_BASE; ?>
<footer class="site-footer">
  <div class="container footer-grid">
    <div class="footer-brand">
      <img src="<?= $_b ?>/PHOTO-2026-04-24-12-40-21.jpg" alt="Braids by Portia" class="footer-logo">
      <p>Premium African hair braiding crafted with artistry and care.</p>
    </div>
    <div class="footer-links">
      <h4>Navigate</h4>
      <ul>
        <li><a href="<?= $_b ?>/services">Services</a></li>
        <li><a href="<?= $_b ?>/gallery">Gallery</a></li>
        <li><a href="<?= $_b ?>/about">About</a></li>
        <li><a href="<?= $_b ?>/contact">Contact</a></li>
      </ul>
    </div>
    <div class="footer-book">
      <h4>Ready to Book?</h4>
      <a href="<?= $_b ?>/booking" class="btn-gold-sm">Book Appointment</a>
    </div>
    <div class="footer-newsletter">
      <h4>Stay Connected</h4>
      <p>Get care tips &amp; appointment reminders</p>
      <form class="nl-form" action="<?= $_b ?>/contact">
        <input type="email" name="email" placeholder="your@email.com" class="nl-input" aria-label="Email address">
        <button type="submit" class="nl-btn" aria-label="Subscribe">→</button>
      </form>
    </div>
  </div>
  <div class="footer-bottom">
    <p>&copy; <?= date('Y') ?> Braids by Portia. All rights reserved.</p>
  </div>
</footer>
<script src="<?= $_b ?>/js/main.js"></script>
<?= $extra_js ?? '' ?>
</body>
</html>
