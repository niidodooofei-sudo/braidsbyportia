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
  --gold:#d4af37;--gold-soft:rgba(212,175,55,.12);--gold-border:rgba(212,175,55,.2);
  --gold-glow:rgba(212,175,55,.06);
  --bg:#0c0e14;--s1:#131620;--s2:#181b26;--s3:#1e2133;--s4:#232640;
  --text:#e8e4d9;--text-dim:rgba(232,228,217,.5);--text-faint:rgba(232,228,217,.22);
  --border:rgba(255,255,255,.055);--border2:rgba(255,255,255,.035);
  --red:#e05c5c;--green:#4fc57a;--blue:#5da8d6;
  --sidebar-w:232px;--header-h:52px;
  --radius:12px;--radius-sm:8px;
}
html,body{height:100%}
body{font-family:'Poppins',sans-serif;background:var(--bg);color:var(--text);font-size:14px;line-height:1.55;-webkit-font-smoothing:antialiased}

/* ── Sidebar ── */
.cms-sidebar{
  position:fixed;top:0;left:0;width:var(--sidebar-w);height:100vh;
  background:var(--s1);border-right:1px solid var(--border);
  display:flex;flex-direction:column;z-index:200;overflow-y:auto;overflow-x:hidden;
  transition:transform .25s ease;
}
.cms-sb-logo{
  padding:1.3rem 1.2rem 1.1rem;border-bottom:1px solid var(--border);
  display:flex;align-items:center;gap:.75rem;
}
.cms-sb-logo-img{
  width:38px;height:38px;border-radius:50%;object-fit:cover;
  border:2px solid var(--gold-border);flex-shrink:0;
}
.cms-sb-logo-text{min-width:0}
.cms-sb-logo-name{font-size:.82rem;font-weight:700;color:var(--gold);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.cms-sb-logo-sub{font-size:.62rem;font-weight:400;color:var(--text-faint);margin-top:.05rem}

.cms-nav{flex:1;padding:.75rem .65rem}
.cms-nav-section{
  padding:.6rem .55rem .2rem;font-size:.6rem;letter-spacing:.12em;
  text-transform:uppercase;color:var(--text-faint);margin-top:.3rem;font-weight:600;
}
.cms-nav-link{
  display:flex;align-items:center;gap:.65rem;padding:.58rem .75rem;
  color:var(--text-dim);font-size:.8rem;text-decoration:none;
  border-radius:var(--radius-sm);transition:all .15s;margin-bottom:.15rem;
}
.cms-nav-link:hover{color:var(--text);background:rgba(255,255,255,.04)}
.cms-nav-link.active{
  color:var(--gold);font-weight:600;
  background:var(--gold-soft);
  box-shadow:inset 0 0 0 1px var(--gold-border);
}
.cms-nav-link svg{width:15px;height:15px;flex-shrink:0;opacity:.65}
.cms-nav-link:hover svg{opacity:.85}
.cms-nav-link.active svg{opacity:1}

.cms-sb-foot{padding:.9rem 1.2rem;border-top:1px solid var(--border)}
.cms-sb-foot a{
  font-size:.74rem;color:var(--text-faint);text-decoration:none;
  display:flex;align-items:center;gap:.45rem;padding:.4rem .55rem;border-radius:6px;transition:all .15s;
}
.cms-sb-foot a:hover{color:var(--red);background:rgba(224,92,92,.06)}

/* ── Main ── */
.cms-main{margin-left:var(--sidebar-w);min-height:100vh;display:flex;flex-direction:column}

.cms-header{
  position:sticky;top:0;z-index:100;height:var(--header-h);
  background:rgba(12,14,20,.9);backdrop-filter:blur(12px);
  border-bottom:1px solid var(--border);
  display:flex;align-items:center;justify-content:space-between;padding:0 1.75rem;
}
.cms-header-left{display:flex;align-items:center;gap:.75rem}
.cms-hamburger{display:none;background:none;border:none;color:var(--text-dim);cursor:pointer;padding:.25rem}
.cms-hamburger svg{width:20px;height:20px}
.cms-header-breadcrumb{font-size:.72rem;color:var(--text-faint)}
.cms-header-breadcrumb span{color:var(--text-dim);margin:0 .3rem}

.cms-header-right{display:flex;align-items:center;gap:.85rem}
.cms-header-date{font-size:.7rem;color:var(--text-faint)}
.cms-header-viewsite{
  font-size:.7rem;color:var(--text-dim);text-decoration:none;
  padding:.3rem .65rem;border:1px solid var(--border);border-radius:6px;transition:all .15s;display:flex;align-items:center;gap:.3rem;
}
.cms-header-viewsite:hover{border-color:var(--gold-border);color:var(--gold)}

.cms-body{flex:1;padding:1.75rem}
.cms-page-head{margin-bottom:1.5rem}
.cms-page-head h1{font-size:1.55rem;font-weight:700;color:var(--text);letter-spacing:-.02em}
.cms-page-head p{font-size:.78rem;color:var(--text-dim);margin-top:.25rem}

/* ── Stat cards ── */
.cms-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem}
.stat-card{
  background:var(--s1);border:1px solid var(--border);border-radius:var(--radius);
  padding:1.3rem 1.5rem;position:relative;overflow:hidden;
  transition:border-color .2s,transform .15s;
}
.stat-card:hover{border-color:rgba(255,255,255,.1);transform:translateY(-1px)}
.stat-card::before{
  content:'';position:absolute;top:0;left:0;right:0;height:2px;
  background:linear-gradient(90deg,transparent,rgba(212,175,55,.3),transparent);opacity:0;transition:opacity .2s;
}
.stat-card:hover::before{opacity:1}
.stat-card-gold{border-color:var(--gold-border);background:linear-gradient(135deg,var(--s1),rgba(212,175,55,.05))}
.stat-card-gold::before{opacity:1;background:linear-gradient(90deg,transparent,var(--gold-border),transparent)}
.stat-card-green{border-color:rgba(79,197,122,.18)}
.stat-label{font-size:.63rem;letter-spacing:.1em;text-transform:uppercase;color:var(--text-faint);margin-bottom:.5rem;font-weight:600}
.stat-value{font-size:2.2rem;font-weight:700;color:var(--text);line-height:1;letter-spacing:-.03em}
.stat-card-gold .stat-value{color:var(--gold)}
.stat-card-green .stat-value{color:var(--green)}
.stat-sub{font-size:.68rem;color:var(--text-faint);margin-top:.4rem}

/* ── Generic card ── */
.cms-card{
  background:var(--s1);border:1px solid var(--border);
  border-radius:var(--radius);overflow:hidden;
}
.cms-card+.cms-card,.cms-card+.cms-grid-2{margin-top:1.25rem}
.cms-card-head{
  padding:.95rem 1.4rem;border-bottom:1px solid var(--border);
  display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;
}
.cms-card-head h2{font-size:.88rem;font-weight:600;color:var(--text)}
.cms-card-body{padding:1.4rem}

/* ── Table ── */
.tbl-wrap{overflow-x:auto}
.cms-tbl{width:100%;border-collapse:collapse;font-size:.8rem;white-space:nowrap}
.cms-tbl th{
  text-align:left;padding:.6rem 1.1rem;font-size:.62rem;letter-spacing:.1em;
  text-transform:uppercase;color:rgba(212,175,55,.55);
  border-bottom:1px solid var(--border);font-weight:600;background:rgba(255,255,255,.012);
}
.cms-tbl td{padding:.8rem 1.1rem;border-bottom:1px solid var(--border2);vertical-align:middle;white-space:normal}
.cms-tbl tbody tr:last-child td{border-bottom:none}
.cms-tbl tbody tr:hover td{background:rgba(255,255,255,.025)}

/* ── Badges ── */
.badge{display:inline-flex;align-items:center;padding:.2rem .65rem;border-radius:50px;font-size:.65rem;font-weight:600;letter-spacing:.02em}
.badge-confirmed{background:rgba(79,197,122,.12);color:#4fc57a;border:1px solid rgba(79,197,122,.2)}
.badge-cancelled{background:rgba(224,92,92,.1);color:#e05c5c;border:1px solid rgba(224,92,92,.18)}
.badge-completed{background:rgba(93,168,214,.1);color:#5da8d6;border:1px solid rgba(93,168,214,.18)}

/* ── Buttons ── */
.btn{
  display:inline-flex;align-items:center;gap:.35rem;padding:.48rem 1.05rem;
  border-radius:var(--radius-sm);font-family:inherit;font-size:.78rem;font-weight:600;
  cursor:pointer;border:none;text-decoration:none;transition:all .15s;line-height:1;
}
.btn-gold{background:var(--gold);color:#0a0a0a}
.btn-gold:hover{background:#c9a52e;box-shadow:0 0 0 3px rgba(212,175,55,.15)}
.btn-outline{background:transparent;color:var(--text-dim);border:1px solid var(--border)}
.btn-outline:hover{border-color:rgba(255,255,255,.15);color:var(--text);background:rgba(255,255,255,.04)}
.btn-danger{background:rgba(224,92,92,.1);color:var(--red);border:1px solid rgba(224,92,92,.18)}
.btn-danger:hover{background:rgba(224,92,92,.18)}
.btn-sm{padding:.3rem .7rem;font-size:.72rem}
.btn-xs{padding:.2rem .55rem;font-size:.67rem}

/* ── Filter pills ── */
.cms-filters{display:flex;gap:.35rem;flex-wrap:wrap}
.filter-btn{
  padding:.32rem .85rem;border-radius:50px;border:1px solid var(--border);
  background:transparent;color:var(--text-faint);font-size:.72rem;cursor:pointer;
  font-family:inherit;transition:all .15s;
}
.filter-btn.active{background:var(--gold-soft);border-color:var(--gold-border);color:var(--gold);font-weight:600}
.filter-btn:not(.active):hover{border-color:rgba(255,255,255,.12);color:var(--text-dim);background:rgba(255,255,255,.03)}

/* ── Forms ── */
.cms-form-row{display:grid;gap:1rem;margin-bottom:1rem}
.cms-form-row.cols-2{grid-template-columns:1fr 1fr}
.cms-form-row.cols-3{grid-template-columns:1fr 1fr 1fr}
.cms-field{display:flex;flex-direction:column;gap:.3rem}
.cms-label{font-size:.65rem;letter-spacing:.07em;text-transform:uppercase;color:var(--text-faint);font-weight:600}
.cms-input,.cms-select,.cms-textarea{
  background:var(--s2);border:1px solid var(--border);color:var(--text);
  border-radius:var(--radius-sm);padding:.58rem .85rem;font-family:inherit;font-size:.83rem;
  transition:border-color .15s,box-shadow .15s;outline:none;width:100%;
}
.cms-input:focus,.cms-select:focus,.cms-textarea:focus{
  border-color:var(--gold-border);
  box-shadow:0 0 0 3px rgba(212,175,55,.08);
}
.cms-select option{background:var(--s2)}
.cms-textarea{resize:vertical;min-height:80px}
.cms-input-sm{max-width:120px}
.cms-input-md{max-width:220px}

/* ── Alerts ── */
.alert{padding:.7rem 1rem;border-radius:var(--radius-sm);font-size:.78rem;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem}
.alert-ok{background:rgba(79,197,122,.08);border:1px solid rgba(79,197,122,.2);color:#4fc57a}
.alert-err{background:rgba(224,92,92,.08);border:1px solid rgba(224,92,92,.18);color:#e05c5c}
.alert-info{background:var(--gold-soft);border:1px solid var(--gold-border);color:var(--gold)}

/* ── Grid helpers ── */
.cms-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1.25rem}
.flex-between{display:flex;align-items:center;justify-content:space-between;gap:.75rem;flex-wrap:wrap}
.mt-1{margin-top:.75rem}
.text-dim{color:var(--text-dim)}
.text-faint{color:var(--text-faint)}
.text-gold{color:var(--gold)}
.text-sm{font-size:.75rem}
.text-xs{font-size:.68rem}
.nowrap{white-space:nowrap}

/* ── Service editor ── */
.svc-cat{border:1px solid var(--border);border-radius:var(--radius-sm);overflow:hidden;margin-bottom:.85rem;background:var(--s1)}
.svc-cat-head{
  background:var(--s2);padding:.8rem 1.1rem;cursor:pointer;
  display:flex;align-items:center;justify-content:space-between;user-select:none;
  border-bottom:1px solid transparent;transition:border-color .15s;
}
.svc-cat.open .svc-cat-head{border-bottom-color:var(--border)}
.svc-cat-head h3{font-size:.82rem;font-weight:600}
.svc-cat-head .svc-cat-toggle{color:var(--text-faint);font-size:.72rem;transition:transform .2s}
.svc-cat.open .svc-cat-toggle{transform:rotate(180deg)}
.svc-cat-body{display:none;padding:1.1rem}
.svc-cat.open .svc-cat-body{display:block}
.svc-list-tbl{width:100%;border-collapse:collapse;font-size:.78rem}
.svc-list-tbl th{text-align:left;padding:.4rem .5rem;font-size:.62rem;letter-spacing:.08em;text-transform:uppercase;color:var(--text-faint);font-weight:600;border-bottom:1px solid var(--border)}
.svc-list-tbl td{padding:.45rem .5rem;border-bottom:1px solid var(--border2)}
.svc-list-tbl td input{
  background:var(--s2);border:1px solid var(--border);color:var(--text);
  border-radius:5px;padding:.28rem .5rem;font-family:inherit;font-size:.78rem;width:100%;outline:none;
  transition:border-color .15s;
}
.svc-list-tbl td input:focus{border-color:var(--gold-border)}
.matrix-tbl{border-collapse:collapse;font-size:.75rem;width:100%}
.matrix-tbl th,.matrix-tbl td{padding:.38rem .6rem;border:1px solid var(--border);text-align:center}
.matrix-tbl th{background:var(--s2);color:var(--text-dim);font-weight:600;font-size:.63rem;letter-spacing:.06em;text-transform:uppercase}
.matrix-tbl td input{
  background:var(--s1);border:none;color:var(--text);border-radius:4px;
  padding:.22rem .38rem;font-family:inherit;font-size:.75rem;width:72px;text-align:center;outline:none;
}
.matrix-tbl td input:focus{outline:2px solid var(--gold-border)}

/* ── Blocked dates ── */
.blocked-list{display:flex;flex-wrap:wrap;gap:.4rem;margin-top:.65rem}
.blocked-chip{
  display:inline-flex;align-items:center;gap:.35rem;padding:.3rem .7rem;
  background:rgba(224,92,92,.08);border:1px solid rgba(224,92,92,.18);
  border-radius:50px;font-size:.7rem;color:var(--red);
}
.blocked-chip button{background:none;border:none;cursor:pointer;color:var(--red);padding:0;line-height:1;font-size:.9rem;opacity:.7}
.blocked-chip button:hover{opacity:1}

/* ── Responsive ── */
@media(max-width:1100px){.cms-stats{grid-template-columns:repeat(2,1fr)}}
@media(max-width:960px){
  .cms-grid-2{grid-template-columns:1fr}
  .cms-form-row.cols-3{grid-template-columns:1fr 1fr}
}
@media(max-width:700px){
  .cms-sidebar{transform:translateX(-100%)}
  .cms-sidebar.sb-open{transform:translateX(0);box-shadow:0 0 0 100vw rgba(0,0,0,.7)}
  .cms-main{margin-left:0}
  .cms-hamburger{display:flex}
  .cms-stats{grid-template-columns:1fr 1fr}
  .cms-form-row.cols-2,.cms-form-row.cols-3{grid-template-columns:1fr}
  .cms-body{padding:1rem}
}
@media(max-width:440px){.cms-stats{grid-template-columns:1fr}}
</style>
</head>
<body>

<aside class="cms-sidebar" id="cms-sidebar">
  <div class="cms-sb-logo">
    <img src="../PHOTO-2026-04-24-12-40-21.jpg" alt="Braids by Portia" class="cms-sb-logo-img">
    <div class="cms-sb-logo-text">
      <div class="cms-sb-logo-name">Braids by Portia</div>
      <div class="cms-sb-logo-sub">Studio CMS</div>
    </div>
  </div>

  <nav class="cms-nav">
    <span class="cms-nav-section">Overview</span>
    <a href="dashboard.php" class="cms-nav-link <?= ($active_page??'')==='dashboard'?'active':'' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
      Dashboard
    </a>

    <span class="cms-nav-section">Appointments</span>
    <a href="bookings.php" class="cms-nav-link <?= ($active_page??'')==='bookings'?'active':'' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
      Bookings
    </a>
    <a href="availability.php" class="cms-nav-link <?= ($active_page??'')==='availability'?'active':'' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      Availability
    </a>

    <span class="cms-nav-section">Content</span>
    <a href="services.php" class="cms-nav-link <?= ($active_page??'')==='services'?'active':'' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/></svg>
      Services &amp; Pricing
    </a>

    <span class="cms-nav-section">System</span>
    <a href="settings.php" class="cms-nav-link <?= ($active_page??'')==='settings'?'active':'' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
      Settings
    </a>
  </nav>

  <div class="cms-sb-foot">
    <a href="logout.php">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      Sign out
    </a>
  </div>
</aside>

<div class="cms-main">
  <header class="cms-header">
    <div class="cms-header-left">
      <button class="cms-hamburger" id="cms-hamburger" aria-label="Menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </button>
      <span class="cms-header-breadcrumb">Braids by Portia <span>›</span> <?= htmlspecialchars($page_title ?? '') ?></span>
    </div>
    <div class="cms-header-right">
      <span class="cms-header-date"><?= date('D, M j, Y') ?></span>
      <a href="../index.php" target="_blank" class="cms-header-viewsite">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
        View site
      </a>
    </div>
  </header>

  <div class="cms-body">
    <div class="cms-page-head">
      <h1><?= htmlspecialchars($page_title ?? '') ?></h1>
      <?php if (!empty($page_sub)): ?><p><?= htmlspecialchars($page_sub) ?></p><?php endif; ?>
    </div>
<?= $content ?? '' ?>
  </div>
</div>

<script>
const sb = document.getElementById('cms-sidebar');
document.getElementById('cms-hamburger')?.addEventListener('click', () => sb.classList.toggle('sb-open'));
document.addEventListener('click', e => {
  if (sb.classList.contains('sb-open') && !sb.contains(e.target) && !e.target.closest('#cms-hamburger')) {
    sb.classList.remove('sb-open');
  }
});
</script>
<?= $extra_js ?? '' ?>
</body>
</html>
