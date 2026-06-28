<?php
$page_title = 'Services & Pricing | Braids by Portia';
$page_desc  = 'Full service menu and pricing for Braids by Portia — box braids, knotless, French curls, twists, locs, stitch braids and more.';
require 'includes/header.php';
$services = require 'config/services.php';
?>

<main>
  <div class="page-banner">
    <div class="container">
      <span class="eyebrow">What We Offer</span>
      <h1>Services &amp; Pricing</h1>
      <p>Every style is crafted with precision, care, and premium quality extensions.</p>
    </div>
  </div>

  <section class="services-section">
    <div class="container">

      <?php foreach ($services as $key => $cat): ?>
      <div class="service-block reveal" id="<?= htmlspecialchars($key) ?>">
        <div class="svc-block-img sh-img-<?= htmlspecialchars($key) ?>"></div>
        <div class="service-block-head">
          <div>
            <h3 class="service-cat-name"><?= htmlspecialchars($cat['name']) ?></h3>
            <p class="service-cat-desc"><?= htmlspecialchars($cat['desc']) ?></p>
          </div>
          <a href="booking.php?cat=<?= urlencode($key) ?>" class="btn-gold-sm">Book Now</a>
        </div>

        <?php if ($cat['type'] === 'list'): ?>
        <div class="simple-table">
          <div class="st-row st-head"><span>Style</span><span>Duration</span><span>Price</span></div>
          <?php foreach ($cat['services'] as $svc): ?>
          <div class="st-row">
            <span><?= htmlspecialchars($svc['name']) ?></span>
            <span><?= floor($svc['duration']/60) > 0 ? floor($svc['duration']/60).'h ' : '' ?><?= $svc['duration']%60 > 0 ? ($svc['duration']%60).'m' : '' ?></span>
            <span class="st-price">$<?= $svc['price'] ?></span>
          </div>
          <?php endforeach; ?>
        </div>

        <?php else: ?>
        <div class="matrix-wrap">
          <table class="matrix-table">
            <thead>
              <tr>
                <th>Size</th>
                <?php foreach ($cat['lengths'] as $len): ?><th><?= $len ?></th><?php endforeach; ?>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($cat['sizes'] as $size): ?>
              <tr>
                <td class="size-label"><?= $size ?></td>
                <?php foreach ($cat['lengths'] as $len): ?>
                <td>$<?= $cat['prices'][$size][$len] ?></td>
                <?php endforeach; ?>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php if (!empty($cat['extras'])): ?>
        <div class="simple-table" style="margin-top:0.75rem">
          <?php foreach ($cat['extras'] as $ex): ?>
          <div class="st-row">
            <span><?= htmlspecialchars($ex['name']) ?></span>
            <span><?= floor($ex['duration']/60) ?>h <?= $ex['duration']%60 > 0 ? ($ex['duration']%60).'m' : '' ?></span>
            <span class="st-price">$<?= $ex['price'] ?></span>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>

      <div class="services-cta reveal">
        <p>Not sure which style is right for you? Book a consultation and we'll help you choose.</p>
        <a href="booking.php" class="btn-gold">Book Your Appointment</a>
      </div>
    </div>
  </section>
</main>

<?php require 'includes/footer.php'; ?>
