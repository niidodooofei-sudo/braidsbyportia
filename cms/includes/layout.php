<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($page_title ?? 'CMS') ?> — Braids by Portia</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --gold:#d4af37;--gold-soft:rgba(212,175,55,.11);--gold-border:rgba(212,175,55,.22);
  --bg:#080808;
  --s1:#0f0f0f;   /* cards */
  --s2:#151515;   /* inputs, nav bg */
  --s3:#1c1c1c;   /* hover, elevated */
  --s4:#222;      /* deepest surface */
  --text:#ede8dc;
  --text-dim:rgba(237,232,220,.5);
  --text-faint:rgba(237,232,220,.22);
  --border:rgba(255,255,255,.07);
  --border2:rgba(255,255,255,.04);
  --red:#e05c5c;--green:#4fc57a;--blue:#5da8d6;
  --nav-h:52px;--nav-top:1rem;
  --radius:12px;--radius-sm:8px;
}
html,body{height:100%;-webkit-font-smoothing:antialiased}
body{font-family:'Poppins',sans-serif;background:var(--bg);color:var(--text);font-size:14px;line-height:1.55}

/* ── Floating top nav ── */
.cms-topnav{
  position:fixed;top:var(--nav-top);left:1rem;right:1rem;z-index:300;
  height:var(--nav-h);
  background:rgba(14,14,14,.88);
  backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);
  border:1px solid var(--border);
  border-radius:var(--radius);
  box-shadow:0 8px 32px rgba(0,0,0,.55),0 1px 0 rgba(255,255,255,.04) inset;
  display:flex;align-items:center;padding:0 .85rem;gap:.5rem;
}

/* Logo */
.cms-nav-logo{display:flex;align-items:center;gap:.6rem;text-decoration:none;flex-shrink:0;margin-right:.75rem}
.cms-logo-img{width:32px;height:32px;border-radius:50%;object-fit:cover;border:1.5px solid var(--gold-border);flex-shrink:0}
.cms-logo-name{font-size:.78rem;font-weight:700;color:var(--gold);white-space:nowrap;letter-spacing:.01em}

/* Nav links (desktop) */
.cms-nav-links{display:flex;align-items:center;gap:.2rem;flex:1}
.cms-nav-link{
  display:flex;align-items:center;gap:.4rem;padding:.38rem .7rem;
  color:var(--text-dim);font-size:.76rem;font-weight:500;text-decoration:none;
  border-radius:7px;transition:all .15s;white-space:nowrap;border:1px solid transparent;
}
.cms-nav-link:hover{color:var(--text);background:rgba(255,255,255,.05)}
.cms-nav-link.active{color:var(--gold);font-weight:600;background:var(--gold-soft);border-color:var(--gold-border)}
.cms-nav-link svg{width:13px;height:13px;flex-shrink:0;opacity:.6}
.cms-nav-link.active svg,.cms-nav-link:hover svg{opacity:1}

/* Right side */
.cms-nav-right{display:flex;align-items:center;gap:.55rem;margin-left:auto;flex-shrink:0}
.cms-nav-date{font-size:.68rem;color:var(--text-faint);white-space:nowrap}
.cms-nav-viewsite{
  font-size:.7rem;color:var(--text-dim);text-decoration:none;
  padding:.3rem .6rem;border:1px solid var(--border);border-radius:6px;
  display:flex;align-items:center;gap:.3rem;transition:all .15s;
}
.cms-nav-viewsite:hover{border-color:var(--gold-border);color:var(--gold)}
.cms-nav-signout{
  font-size:.7rem;color:var(--text-faint);text-decoration:none;
  padding:.3rem .6rem;border-radius:6px;transition:all .15s;
  display:flex;align-items:center;gap:.3rem;
}
.cms-nav-signout:hover{color:var(--red);background:rgba(224,92,92,.07)}

/* Hamburger (mobile) */
.cms-hamburger{display:none;background:none;border:none;color:var(--text-dim);cursor:pointer;padding:.3rem;border-radius:6px;margin-left:auto}
.cms-hamburger:hover{background:rgba(255,255,255,.05)}
.cms-hamburger svg{width:18px;height:18px;display:block}

/* Mobile dropdown */
.cms-mobile-menu{
  display:none;position:fixed;
  top:calc(var(--nav-top) + var(--nav-h) + .4rem);
  left:1rem;right:1rem;z-index:299;
  background:rgba(14,14,14,.97);
  border:1px solid var(--border);border-radius:var(--radius);
  padding:.5rem;
  box-shadow:0 16px 40px rgba(0,0,0,.6);
}
.cms-mobile-menu.open{display:block}
.cms-mobile-link{
  display:flex;align-items:center;gap:.6rem;padding:.6rem .75rem;
  color:var(--text-dim);font-size:.82rem;text-decoration:none;
  border-radius:7px;transition:all .15s;
}
.cms-mobile-link:hover{color:var(--text);background:rgba(255,255,255,.04)}
.cms-mobile-link.active{color:var(--gold);background:var(--gold-soft);font-weight:600}
.cms-mobile-link svg{width:14px;height:14px;flex-shrink:0;opacity:.65}
.cms-mobile-link.active svg{opacity:1}
.cms-mobile-sep{height:1px;background:var(--border);margin:.4rem .75rem}

/* ── Main ── */
.cms-main{
  padding-top:calc(var(--nav-top) + var(--nav-h) + 1.75rem);
  padding-left:1.75rem;padding-right:1.75rem;padding-bottom:2.5rem;
  max-width:1440px;margin:0 auto;
}

.cms-page-head{margin-bottom:1.5rem}
.cms-page-head h1{font-size:1.5rem;font-weight:700;color:var(--text);letter-spacing:-.025em;line-height:1.2}
.cms-page-head p{font-size:.78rem;color:var(--text-dim);margin-top:.3rem}

/* ── Stat cards ── */
.cms-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem}
.stat-card{
  background:var(--s1);border:1px solid var(--border);border-radius:var(--radius);
  padding:1.3rem 1.5rem;position:relative;overflow:hidden;
  transition:border-color .2s,transform .15s;
}
.stat-card:hover{border-color:rgba(255,255,255,.12);transform:translateY(-1px)}
.stat-card::after{
  content:'';position:absolute;top:0;left:1.5rem;right:1.5rem;height:1px;
  background:linear-gradient(90deg,transparent,rgba(255,255,255,.06),transparent);
}
.stat-card-gold{border-color:var(--gold-border);background:linear-gradient(145deg,var(--s1),rgba(212,175,55,.04))}
.stat-card-gold::after{background:linear-gradient(90deg,transparent,rgba(212,175,55,.25),transparent)}
.stat-card-green{border-color:rgba(79,197,122,.15)}
.stat-label{font-size:.62rem;letter-spacing:.1em;text-transform:uppercase;color:var(--text-faint);margin-bottom:.55rem;font-weight:600}
.stat-value{font-size:2.25rem;font-weight:700;color:var(--text);line-height:1;letter-spacing:-.03em}
.stat-card-gold .stat-value{color:var(--gold)}
.stat-card-green .stat-value{color:var(--green)}
.stat-sub{font-size:.67rem;color:var(--text-faint);margin-top:.4rem}

/* ── Generic card ── */
.cms-card{background:var(--s1);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden}
.cms-card+.cms-card,.cms-card+.cms-grid-2{margin-top:1.25rem}
.cms-card-head{
  padding:.95rem 1.4rem;border-bottom:1px solid var(--border);
  display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;
}
.cms-card-head h2{font-size:.87rem;font-weight:600}
.cms-card-body{padding:1.4rem}

/* ── Table ── */
.tbl-wrap{overflow-x:auto}
.cms-tbl{width:100%;border-collapse:collapse;font-size:.8rem;white-space:nowrap}
.cms-tbl th{
  text-align:left;padding:.6rem 1.1rem;font-size:.61rem;letter-spacing:.1em;
  text-transform:uppercase;color:rgba(212,175,55,.5);
  border-bottom:1px solid var(--border);font-weight:600;background:rgba(255,255,255,.01);
}
.cms-tbl td{padding:.8rem 1.1rem;border-bottom:1px solid var(--border2);vertical-align:middle;white-space:normal}
.cms-tbl tbody tr:last-child td{border-bottom:none}
.cms-tbl tbody tr:hover td{background:rgba(255,255,255,.02)}

/* ── Badges ── */
.badge{display:inline-flex;align-items:center;padding:.2rem .65rem;border-radius:50px;font-size:.65rem;font-weight:600}
.badge-confirmed{background:rgba(79,197,122,.11);color:#4fc57a;border:1px solid rgba(79,197,122,.18)}
.badge-cancelled{background:rgba(224,92,92,.1);color:#e05c5c;border:1px solid rgba(224,92,92,.16)}
.badge-completed{background:rgba(93,168,214,.1);color:#5da8d6;border:1px solid rgba(93,168,214,.16)}

/* ── Buttons ── */
.btn{display:inline-flex;align-items:center;gap:.35rem;padding:.46rem 1rem;border-radius:var(--radius-sm);font-family:inherit;font-size:.77rem;font-weight:600;cursor:pointer;border:none;text-decoration:none;transition:all .15s;line-height:1}
.btn-gold{background:var(--gold);color:#080808}
.btn-gold:hover{background:#c9a52e;box-shadow:0 0 0 3px rgba(212,175,55,.14)}
.btn-outline{background:transparent;color:var(--text-dim);border:1px solid var(--border)}
.btn-outline:hover{border-color:rgba(255,255,255,.14);color:var(--text);background:rgba(255,255,255,.035)}
.btn-danger{background:rgba(224,92,92,.09);color:var(--red);border:1px solid rgba(224,92,92,.16)}
.btn-danger:hover{background:rgba(224,92,92,.16)}
.btn-sm{padding:.3rem .7rem;font-size:.72rem}
.btn-xs{padding:.2rem .55rem;font-size:.67rem}

/* ── Filters ── */
.cms-filters{display:flex;gap:.3rem;flex-wrap:wrap}
.filter-btn{padding:.3rem .82rem;border-radius:50px;border:1px solid var(--border);background:transparent;color:var(--text-faint);font-size:.71rem;cursor:pointer;font-family:inherit;transition:all .15s}
.filter-btn.active{background:var(--gold-soft);border-color:var(--gold-border);color:var(--gold);font-weight:600}
.filter-btn:not(.active):hover{border-color:rgba(255,255,255,.11);color:var(--text-dim);background:rgba(255,255,255,.025)}

/* ── Forms ── */
.cms-form-row{display:grid;gap:1rem;margin-bottom:1rem}
.cms-form-row.cols-2{grid-template-columns:1fr 1fr}
.cms-form-row.cols-3{grid-template-columns:1fr 1fr 1fr}
.cms-field{display:flex;flex-direction:column;gap:.3rem}
.cms-label{font-size:.64rem;letter-spacing:.07em;text-transform:uppercase;color:var(--text-faint);font-weight:600}
.cms-input,.cms-select,.cms-textarea{
  background:var(--s2);border:1px solid var(--border);color:var(--text);
  border-radius:var(--radius-sm);padding:.56rem .85rem;font-family:inherit;font-size:.82rem;
  transition:border-color .15s,box-shadow .15s;outline:none;width:100%;
}
.cms-input:focus,.cms-select:focus,.cms-textarea:focus{border-color:var(--gold-border);box-shadow:0 0 0 3px rgba(212,175,55,.07)}
.cms-select option{background:var(--s2)}
.cms-textarea{resize:vertical;min-height:80px}
.cms-input-sm{max-width:120px}
.cms-input-md{max-width:220px}

/* ── Alerts ── */
.alert{padding:.68rem 1rem;border-radius:var(--radius-sm);font-size:.78rem;margin-bottom:1rem}
.alert-ok{background:rgba(79,197,122,.07);border:1px solid rgba(79,197,122,.18);color:#4fc57a}
.alert-err{background:rgba(224,92,92,.07);border:1px solid rgba(224,92,92,.16);color:#e05c5c}
.alert-info{background:var(--gold-soft);border:1px solid var(--gold-border);color:var(--gold)}

/* ── Grid helpers ── */
.cms-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1.25rem}
.flex-between{display:flex;align-items:center;justify-content:space-between;gap:.75rem;flex-wrap:wrap}
.mt-1{margin-top:.75rem}
.text-dim{color:var(--text-dim)}.text-faint{color:var(--text-faint)}.text-gold{color:var(--gold)}
.text-sm{font-size:.75rem}.text-xs{font-size:.68rem}.nowrap{white-space:nowrap}

/* ── Service editor ── */
.svc-cat{border:1px solid var(--border);border-radius:var(--radius-sm);overflow:hidden;margin-bottom:.85rem;background:var(--s1)}
.svc-cat-head{background:var(--s2);padding:.78rem 1.1rem;cursor:pointer;display:flex;align-items:center;justify-content:space-between;user-select:none;border-bottom:1px solid transparent;transition:border-color .15s}
.svc-cat.open .svc-cat-head{border-bottom-color:var(--border)}
.svc-cat-head h3{font-size:.82rem;font-weight:600}
.svc-cat-head .svc-cat-toggle{color:var(--text-faint);font-size:.7rem;transition:transform .2s}
.svc-cat.open .svc-cat-toggle{transform:rotate(180deg)}
.svc-cat-body{display:none;padding:1.1rem}
.svc-cat.open .svc-cat-body{display:block}
.svc-list-tbl{width:100%;border-collapse:collapse;font-size:.78rem}
.svc-list-tbl th{text-align:left;padding:.4rem .5rem;font-size:.61rem;letter-spacing:.08em;text-transform:uppercase;color:var(--text-faint);font-weight:600;border-bottom:1px solid var(--border)}
.svc-list-tbl td{padding:.42rem .5rem;border-bottom:1px solid var(--border2)}
.svc-list-tbl td input{background:var(--s2);border:1px solid var(--border);color:var(--text);border-radius:5px;padding:.28rem .5rem;font-family:inherit;font-size:.78rem;width:100%;outline:none;transition:border-color .15s}
.svc-list-tbl td input:focus{border-color:var(--gold-border)}
.matrix-tbl{border-collapse:collapse;font-size:.75rem;width:100%}
.matrix-tbl th,.matrix-tbl td{padding:.38rem .6rem;border:1px solid var(--border);text-align:center}
.matrix-tbl th{background:var(--s2);color:var(--text-dim);font-weight:600;font-size:.62rem;letter-spacing:.06em;text-transform:uppercase}
.matrix-tbl td input{background:var(--s1);border:none;color:var(--text);border-radius:4px;padding:.22rem .38rem;font-family:inherit;font-size:.75rem;width:72px;text-align:center;outline:none}
.matrix-tbl td input:focus{outline:2px solid var(--gold-border)}

/* ── Blocked dates ── */
.blocked-list{display:flex;flex-wrap:wrap;gap:.4rem;margin-top:.65rem}
.blocked-chip{display:inline-flex;align-items:center;gap:.35rem;padding:.28rem .65rem;background:rgba(224,92,92,.07);border:1px solid rgba(224,92,92,.16);border-radius:50px;font-size:.7rem;color:var(--red)}
.blocked-chip button{background:none;border:none;cursor:pointer;color:var(--red);padding:0;line-height:1;font-size:.85rem;opacity:.6}
.blocked-chip button:hover{opacity:1}

/* ── Responsive ── */
@media(max-width:1100px){.cms-stats{grid-template-columns:repeat(2,1fr)}}
@media(max-width:900px){
  .cms-grid-2{grid-template-columns:1fr}
  .cms-form-row.cols-3{grid-template-columns:1fr 1fr}
  .cms-nav-links,.cms-nav-date,.cms-nav-viewsite{display:none}
  .cms-hamburger{display:flex}
  .cms-nav-signout{display:none}
}
@media(max-width:600px){
  .cms-stats{grid-template-columns:1fr 1fr}
  .cms-form-row.cols-2{grid-template-columns:1fr}
  .cms-main{padding-left:1rem;padding-right:1rem}
  .cms-topnav{left:.6rem;right:.6rem}
  .cms-mobile-menu{left:.6rem;right:.6rem}
}
@media(max-width:380px){.cms-stats{grid-template-columns:1fr}}
</style>
</head>
<body>

<!-- Floating top nav -->
<nav class="cms-topnav" id="cms-topnav">
  <a href="dashboard.php" class="cms-nav-logo">
    <img src="../PHOTO-2026-04-24-12-40-21.jpg" alt="Braids by Portia" class="cms-logo-img">
    <span class="cms-logo-name">Braids by Portia</span>
  </a>

  <div class="cms-nav-links">
    <a href="dashboard.php"    class="cms-nav-link <?= ($active_page??'')==='dashboard'   ?'active':'' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
      Dashboard
    </a>
    <a href="bookings.php"     class="cms-nav-link <?= ($active_page??'')==='bookings'    ?'active':'' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
      Bookings
    </a>
    <a href="availability.php" class="cms-nav-link <?= ($active_page??'')==='availability'?'active':'' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      Availability
    </a>
    <a href="services.php"     class="cms-nav-link <?= ($active_page??'')==='services'    ?'active':'' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/></svg>
      Services &amp; Pricing
    </a>
    <a href="settings.php"     class="cms-nav-link <?= ($active_page??'')==='settings'    ?'active':'' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
      Settings
    </a>
  </div>

  <div class="cms-nav-right">
    <span class="cms-nav-date"><?= date('D, M j') ?></span>
    <a href="../index.php" target="_blank" class="cms-nav-viewsite">
      <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
      View site
    </a>
    <a href="logout.php" class="cms-nav-signout">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      Sign out
    </a>
  </div>

  <button class="cms-hamburger" id="cms-hamburger" aria-label="Menu" aria-expanded="false">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
      <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
    </svg>
  </button>
</nav>

<!-- Mobile dropdown -->
<div class="cms-mobile-menu" id="cms-mobile-menu">
  <?php
  $mobile_links = [
    'dashboard'    => ['href'=>'dashboard.php',    'label'=>'Dashboard',         'icon'=>'<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/>'],
    'bookings'     => ['href'=>'bookings.php',     'label'=>'Bookings',          'icon'=>'<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>'],
    'availability' => ['href'=>'availability.php', 'label'=>'Availability',      'icon'=>'<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
    'services'     => ['href'=>'services.php',     'label'=>'Services & Pricing','icon'=>'<line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/>'],
    'settings'     => ['href'=>'settings.php',     'label'=>'Settings',          'icon'=>'<circle cx="12" cy="12" r="3"/>'],
  ];
  foreach ($mobile_links as $key => $link):
    $active = ($active_page??'')===$key ? 'active' : ''; ?>
    <a href="<?= $link['href'] ?>" class="cms-mobile-link <?= $active ?>">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><?= $link['icon'] ?></svg>
      <?= htmlspecialchars($link['label']) ?>
    </a>
  <?php endforeach; ?>
  <div class="cms-mobile-sep"></div>
  <a href="../index.php" target="_blank" class="cms-mobile-link">↗ View site</a>
  <a href="logout.php" class="cms-mobile-link" style="color:var(--red)">Sign out</a>
</div>

<main class="cms-main">
  <div class="cms-page-head">
    <h1><?= htmlspecialchars($page_title ?? '') ?></h1>
    <?php if (!empty($page_sub)): ?><p><?= htmlspecialchars($page_sub) ?></p><?php endif; ?>
  </div>
<?= $content ?? '' ?>
</main>

<script>
const hamburger = document.getElementById('cms-hamburger');
const mobileMenu = document.getElementById('cms-mobile-menu');
hamburger?.addEventListener('click', () => {
  const open = mobileMenu.classList.toggle('open');
  hamburger.setAttribute('aria-expanded', open);
});
document.addEventListener('click', e => {
  if (mobileMenu.classList.contains('open') &&
      !mobileMenu.contains(e.target) &&
      !hamburger.contains(e.target)) {
    mobileMenu.classList.remove('open');
    hamburger.setAttribute('aria-expanded', 'false');
  }
});
</script>
<?= $extra_js ?? '' ?>
</body>
</html>
