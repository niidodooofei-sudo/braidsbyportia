<?php
$page_title = 'Gallery | Braids by Portia';
$page_desc  = 'Browse the Braids by Portia portfolio — box braids, knotless, French curls, twists, locs and more.';
require 'includes/header.php';
?>

<main>
  <div class="page-banner">
    <div class="container">
      <span class="eyebrow">Our Work</span>
      <h1>The Portfolio</h1>
      <p>A glimpse of the artistry we bring to every appointment.</p>
    </div>
  </div>

  <section class="gallery-section" style="padding-top:3rem">
    <div class="container">
      <div class="gallery-grid">
        <?php for($i=1;$i<=9;$i++) echo "<div class=\"g-tile g{$i}\"></div>"; ?>
      </div>
      <div class="gallery-cta" style="margin-top:3rem">
        <a href="booking.php" class="btn-gold">Book Your Style</a>
      </div>
    </div>
  </section>
</main>

<?php require 'includes/footer.php'; ?>
