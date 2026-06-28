<?php
$page_title = 'Braids by Portia | African Hair Braiding';
$page_desc  = 'Premium African hair braiding — box braids, knotless, French curls, twists, locs and more. Book your appointment online today.';
$extra_js   = '<script src="js/home.js"></script>';
require 'config/stripe.php';
require 'includes/header.php';
$services = require 'config/services.php';
?>

<main>
  <!-- HERO -->
  <section id="hero" class="hero-section">
    <div class="collage-bg" aria-hidden="true">
      <?php for($i=0;$i<24;$i++) echo '<div class="tile"></div>'; ?>
    </div>
    <div class="braid-pattern" aria-hidden="true"></div>
    <div class="overlay" aria-hidden="true"></div>
    <div class="hero-content">
      <img src="PHOTO-2026-04-24-12-40-21.jpg" alt="Braids by Portia logo" class="hero-logo">
      <h1>Where Every Braid<br><em>Tells a Story</em></h1>
      <p class="hero-sub">Expert braiding styles crafted with precision, care, and quality extensions. Serving you with artistry rooted in African tradition.</p>
      <div class="hero-ctas">
        <a href="booking.php" class="btn-gold">Book Appointment</a>
        <a href="services.php" class="btn-outline">Explore Services</a>
      </div>
    </div>
    <a href="#intro" class="scroll-hint" aria-label="Scroll down"><span class="scroll-line"></span></a>
  </section>

  <!-- TRUST STRIP -->
  <div class="trust-strip" id="intro">
    <div class="container trust-grid">
      <div class="trust-item">
        <div class="trust-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
        <div class="trust-text"><span class="trust-label">By Appointment Only</span><span class="trust-sub">Tue – Sat, flexible hours</span></div>
      </div>
      <div class="trust-item">
        <div class="trust-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></div>
        <div class="trust-text"><span class="trust-label">Premium Extensions</span><span class="trust-sub">Quality hair provided</span></div>
      </div>
      <div class="trust-item">
        <div class="trust-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
        <div class="trust-text"><span class="trust-label">Hair-Safe Technique</span><span class="trust-sub">Gentle on natural hair</span></div>
      </div>
      <div class="trust-item">
        <div class="trust-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
        <div class="trust-text"><span class="trust-label">5-Star Rated</span><span class="trust-sub">Loved by our community</span></div>
      </div>
    </div>
  </div>

  <!-- SERVICE HIGHLIGHTS -->
  <section class="home-services">
    <div class="container">
      <div class="section-header reveal">
        <span class="eyebrow">What We Offer</span>
        <h2>Our Signature Styles</h2>
        <p class="section-sub">From quick protective looks to intricate full installations — each style crafted with precision and premium extensions.</p>
      </div>
      <div class="service-highlight-grid">
        <?php foreach ($services as $key => $cat):
          if ($cat['type'] === 'matrix') {
            $all_prices = [];
            foreach ($cat['prices'] as $row) foreach ($row as $p) $all_prices[] = $p;
            $from = min($all_prices);
          } else {
            $from = min(array_column($cat['services'], 'price'));
          }
        ?>
        <div class="sh-card reveal" data-cat="<?= htmlspecialchars($key) ?>" role="button" tabindex="0" aria-haspopup="dialog">
          <div class="sh-card-img sh-img-<?= htmlspecialchars($key) ?>"></div>
          <div class="sh-card-body">
            <h3><?= htmlspecialchars($cat['name']) ?></h3>
            <p class="sh-card-desc"><?= htmlspecialchars($cat['desc']) ?></p>
            <span class="sh-from">From $<?= $from ?></span>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="home-services-cta reveal">
        <a href="services.php" class="btn-outline-gold">View All Services &amp; Pricing</a>
      </div>
    </div>
  </section>

  <!-- SERVICE DETAIL MODAL -->
  <div id="svc-modal" class="svc-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <div class="svc-modal-backdrop" id="modal-backdrop"></div>
    <div class="svc-modal-panel">
      <button class="svc-modal-close" id="modal-close" aria-label="Close">✕</button>
      <div class="svc-modal-hero" id="modal-hero"></div>
      <div class="svc-modal-body">
        <h3 id="modal-title"></h3>
        <p class="modal-desc" id="modal-desc"></p>
        <div class="modal-pricing" id="modal-pricing"></div>
        <div class="svc-modal-cta">
          <a href="#" id="modal-book-btn" class="btn-gold">Book This Style</a>
        </div>
      </div>
    </div>
  </div>
  <script>window.HOME_SERVICES = <?= json_encode($services, JSON_HEX_TAG | JSON_HEX_AMP) ?>;</script>

  <!-- WHY CHOOSE US -->
  <section class="why-us">
    <div class="container">
      <div class="section-header reveal">
        <span class="eyebrow">Why Choose Us</span>
        <h2>Artistry You Can Trust</h2>
      </div>
      <div class="why-grid">
        <div class="why-item reveal">
          <div class="why-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
          <h4>Premium Quality</h4>
          <p>Top-grade extensions for lasting, beautiful results</p>
        </div>
        <div class="why-item reveal">
          <div class="why-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></div>
          <h4>Client Loved</h4>
          <p>5-star rated and trusted by hundreds of clients</p>
        </div>
        <div class="why-item reveal">
          <div class="why-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg></div>
          <h4>Secure Booking</h4>
          <p>Simple online booking with Stripe-powered deposits</p>
        </div>
        <div class="why-item reveal">
          <div class="why-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg></div>
          <h4>Your Vision</h4>
          <p>We listen, consult, and deliver your perfect look</p>
        </div>
      </div>
    </div>
  </section>

  <!-- TESTIMONIALS -->
  <section class="testimonials">
    <div class="container">
      <div class="section-header reveal">
        <span class="eyebrow">Client Love</span>
        <h2>What Our Clients Say</h2>
      </div>
    </div>
    <div class="testi-track-wrap" aria-label="Customer reviews">
      <div class="testi-track">
        <?php
        $reviews = [
          ['author'=>'Tanitia',  'text'=>'Portia\'s customer service is great! Her braids are not overly tight and I am pleased every time I book with her. She will let you know if she can\'t do a style — very communicative. Book with her, you will not be disappointed.'],
          ['author'=>'Daniella', 'text'=>'My experience with Portia was amazing! This is my second time here and I have never been disappointed. She was very friendly and understanding — I asked for a certain style and she always delivered with greatness.'],
          ['author'=>'Tarra',    'text'=>'Professional, friendly, and talented! Great salon and great hair braiding — her hands are like a gift from God! I will be back! Thanks Portia!'],
          ['author'=>'Ruthie',   'text'=>'She was very professional — I call her lovely Portia. She is kind and definitely has those hands. I highly recommend her.'],
          ['author'=>'Kehinde',  'text'=>'I got island twists and it went by quicker than I thought it would. The braids are very nice and the parts are very neat.'],
          ['author'=>'Debbie',   'text'=>'Braids turned out very well and I will definitely return!'],
          ['author'=>'Yvette',   'text'=>'Was in and out before I knew it — thanks a lot Portia, see you soon!'],
          ['author'=>'Angel',    'text'=>'Very professional. Absolutely love my French curls — will be back!'],
          ['author'=>'Kezia',    'text'=>'My hair is beautiful and she makes every braid the same size and very neat. Absolutely excellent work!'],
        ];
        // Render twice for seamless infinite loop
        foreach ([0, 1] as $_) {
          foreach ($reviews as $r) {
            echo '<div class="testi-card" aria-hidden="' . ($_ === 1 ? 'true' : 'false') . '">';
            echo '<div class="testi-stars">★★★★★</div>';
            echo '<p class="testi-quote">"' . htmlspecialchars($r['text']) . '"</p>';
            echo '<span class="testi-author">— ' . htmlspecialchars($r['author']) . ' &middot; <span class="testi-source">Verified Booking</span></span>';
            echo '</div>';
          }
        }
        ?>
      </div>
    </div>
  </section>

  <!-- GALLERY TEASER -->
  <section class="home-gallery">
    <div class="container">
      <div class="section-header reveal">
        <span class="eyebrow">Our Work</span>
        <h2>The Portfolio</h2>
        <p class="section-sub">A glimpse of the artistry we bring to every appointment.</p>
      </div>
      <div class="gallery-grid reveal">
        <?php for($i=1;$i<=9;$i++) echo "<div class=\"g-tile g{$i}\"></div>"; ?>
      </div>
      <div class="gallery-cta reveal">
        <a href="gallery.php" class="btn-outline-gold">View Full Gallery</a>
      </div>
    </div>
  </section>

  <!-- BOOKING CTA BANNER -->
  <section class="cta-banner">
    <div class="container cta-banner-inner reveal">
      <div>
        <h2>Ready for Your Next Look?</h2>
        <p>Book your appointment online — choose your style, pick your time, and secure your spot with a $<?= DEPOSIT_AMOUNT_DOLLARS ?? 50 ?> deposit.</p>
      </div>
      <a href="booking.php" class="btn-gold btn-large">Book Now</a>
    </div>
  </section>
</main>

<?php require 'includes/footer.php'; ?>
