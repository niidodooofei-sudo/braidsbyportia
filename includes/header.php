<?php
require_once dirname(__DIR__) . '/config/app.php';
$_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Strip subdirectory prefix so $current is always the page name, not the folder
if (APP_BASE !== '' && strpos($_uri, APP_BASE) === 0) {
    $_uri = substr($_uri, strlen(APP_BASE));
}
$current = basename($_uri, '.php') ?: 'index';
$_b = APP_BASE; // shorthand for use in HTML below
function nav_class($page, $current) {
    return 'nav-link' . ($page === $current ? ' active' : '');
}
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($page_title ?? 'Braids by Portia | African Hair Braiding') ?></title>
  <meta name="description" content="<?= htmlspecialchars($page_desc ?? 'Premium African hair braiding — box braids, knotless, French curls, twists, locs and more. Book online today.') ?>">
  <meta property="og:title" content="<?= htmlspecialchars($page_title ?? 'Braids by Portia') ?>">
  <meta property="og:image" content="<?= $_b ?>/PHOTO-2026-04-24-12-40-21.jpg">
  <meta property="og:type" content="website">
  <link rel="icon" href="<?= $_b ?>/favicon.ico" sizes="any">
  <link rel="icon" type="image/png" sizes="32x32" href="<?= $_b ?>/favicon-32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= $_b ?>/favicon-16.png">
  <link rel="apple-touch-icon" sizes="180x180" href="<?= $_b ?>/favicon-180.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;1,400&family=Playfair+Display:wght@700;800&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= $_b ?>/styles.css">
  <script>window.APP_BASE="<?= $_b ?>";</script>
  <?= $extra_head ?? '' ?>
</head>
<body class="page-<?= htmlspecialchars($current) ?>">

<header class="site-header <?= $current !== 'index' ? 'scrolled always-solid' : '' ?>" id="site-header">
  <div class="container nav-container">
    <a href="<?= $_b ?>/" class="nav-logo">
      <img src="<?= $_b ?>/PHOTO-2026-04-24-12-40-21.jpg" alt="Braids by Portia">
    </a>
    <nav class="main-nav" id="main-nav" aria-label="Main navigation">
      <ul>
        <li><a href="<?= $_b ?>/services"  class="<?= nav_class('services', $current) ?>">Services</a></li>
        <li><a href="<?= $_b ?>/gallery"   class="<?= nav_class('gallery',  $current) ?>">Gallery</a></li>
        <li><a href="<?= $_b ?>/about"     class="<?= nav_class('about',    $current) ?>">About</a></li>
        <li><a href="<?= $_b ?>/contact"   class="<?= nav_class('contact',  $current) ?>">Contact</a></li>
      </ul>
      <a href="<?= $_b ?>/booking" class="btn-gold nav-book-mobile">Book Now</a>
    </nav>
    <a href="<?= $_b ?>/booking" class="btn-book-nav <?= $current === 'booking' ? 'active' : '' ?>">Book Now</a>
    <button class="nav-toggle" id="nav-toggle" aria-label="Toggle menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </div>
</header>
