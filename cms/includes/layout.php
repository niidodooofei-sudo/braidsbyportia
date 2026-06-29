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
  --gold:#d4af37;--gold-dim:rgba(212,175,55,.2);--gold-glow:rgba(212,175,55,.08);
  --bg:#080808;--surface:#111;--surface2:#181818;--surface3:#1e1e1e;
  --text:#f0ebe0;--text-dim:rgba(240,235,224,.55);--text-faint:rgba(240,235,224,.22);
  --border:rgba(212,175,55,.13);--border2:rgba(240,235,224,.07);
  --red:#e05252;--green:#4caf78;--blue:#5ba3d0;
  --sidebar-w:228px;--header-h:54px;
}
html,body{height:100%}
body{font-family:'Poppins',sans-serif;background:var(--bg);color:var(--text);font-size:14px;line-height:1.55}

/* ── Sidebar ── */
.cms-sidebar{
  position:fixed;top:0;left:0;width:var(--sidebar-w);height:100vh;
  background:var(--surface);border-right:1px solid var(--border);
  display:flex;flex-direction:column;z-index:200;overflow-y:auto;
  transition:transform .25s ease;
}
.cms-sb-logo{
  padding:1.2rem 1.4rem 1rem;border-bottom:1px solid var(--border);
  display:flex;align-items:center;gap:.7rem;
}
.cms-sb-logo-mark{
  width:32px;height:32px;border-radius:50%;background:var(--gold-dim);
  border:1px solid var(--gold-dim);display:flex;align-items:center;justify-content:center;
  flex-shrink:0;
}
.cms-sb-logo-mark svg{width:16px;height:16px;color:var(--gold)}
.cms-sb-logo-text{font-size:.8rem;font-weight:600;color:var(--gold);line-height:1.2}
.cms-sb-logo-text small{display:block;font-size:.62rem;font-weight:400;color:var(--text-dim)}

.cms-nav{flex:1;padding:.6rem 0}
.cms-nav-section{
  padding:.65rem 1.4rem .2rem;font-size:.6rem;letter-spacing:.12em;
  text-transform:uppercase;color:var(--text-faint);margin-top:.4rem;
}
.cms-nav-link{
  display:flex;align-items:center;gap:.65rem;padding:.55rem 1.4rem;
  color:var(--text-dim);font-size:.8rem;text-decoration:none;
  border-left:2px solid transparent;transition:all .15s;
}
.cms-nav-link:hover{color:var(--text);background:var(--gold-glow)}
.cms-nav-link.active{color:var(--gold);border-left-color:var(--gold);background:var(--gold-glow)}
.cms-nav-link svg{width:15px;height:15px;flex-shrink:0;opacity:.7}
.cms-nav-link.active svg,.cms-nav-link:hover svg{opacity:1}

.cms-sb-foot{padding:.9rem 1.4rem;border-top:1px solid var(--border)}
.cms-sb-foot a{font-size:.75rem;color:var(--text-faint);text-decoration:none;display:flex;align-items:center;gap:.4rem}
.cms-sb-foot a:hover{color:var(--red)}

/* ── Main ── */
.cms-main{margin-left:var(--sidebar-w);min-height:100vh;display:flex;flex-direction:column}
.cms-header{
  position:sticky;top:0;z-index:100;height:var(--header-h);
  background:rgba(8,8,8,.92);backdrop-filter:blur(10px);
  border-bottom:1px solid var(--border);
  display:flex;align-items:center;justify-content:space-between;padding:0 1.75rem;
}
.cms-header-title{font-size:.92rem;font-weight:600;color:var(--text)}
.cms-header-right{display:flex;align-items:center;gap:1rem;font-size:.75rem;color:var(--text-dim)}
.cms-header-date{font-size:.72rem;color:var(--text-faint)}
.cms-hamburger{display:none;background:none;border:none;color:var(--text-dim);cursor:pointer;padding:.3rem}
.cms-hamburger svg{width:20px;height:20px}
.cms-body{flex:1;padding:1.75rem}

/* ── Stat cards ── */
.cms-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.75rem}
.stat-card{background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:1.2rem 1.4rem}
.stat-label{font-size:.65rem;letter-spacing:.1em;text-transform:uppercase;color:var(--text-dim);margin-bottom:.35rem}
.stat-value{font-size:1.75rem;font-weight:700;color:var(--text);line-height:1}
.stat-sub{font-size:.7rem;color:var(--text-faint);margin-top:.3rem}
.stat-gold .stat-value{color:var(--gold)}
.stat-green .stat-value{color:var(--green)}

/* ── Cards ── */
.cms-card{background:var(--surface);border:1px solid var(--border);border-radius:10px;overflow:hidden}
.cms-card+.cms-card{margin-top:1.25rem}
.cms-card-head{
  padding:.9rem 1.4rem;border-bottom:1px solid var(--border);
  display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;
}
.cms-card-head h2{font-size:.85rem;font-weight:600;color:var(--text)}
.cms-card-body{padding:1.4rem}

/* ── Table ── */
.tbl-wrap{overflow-x:auto}
.cms-tbl{width:100%;border-collapse:collapse;font-size:.8rem;white-space:nowrap}
.cms-tbl th{
  text-align:left;padding:.55rem 1rem;font-size:.65rem;letter-spacing:.1em;
  text-transform:uppercase;color:rgba(212,175,55,.6);border-bottom:1px solid var(--border);font-weight:600;
}
.cms-tbl td{padding:.75rem 1rem;border-bottom:1px solid var(--border2);vertical-align:middle;white-space:normal}
.cms-tbl tbody tr:last-child td{border-bottom:none}
.cms-tbl tbody tr:hover td{background:rgba(212,175,55,.025)}

/* ── Badges ── */
.badge{display:inline-flex;align-items:center;padding:.18rem .6rem;border-radius:50px;font-size:.65rem;font-weight:600;letter-spacing:.02em}
.badge-confirmed{background:rgba(76,175,120,.12);color:#4caf78}
.badge-cancelled{background:rgba(224,82,82,.1);color:#e05252}
.badge-completed{background:rgba(91,163,208,.1);color:#5ba3d0}

/* ── Buttons ── */
.btn{display:inline-flex;align-items:center;gap:.35rem;padding:.45rem 1rem;border-radius:6px;font-family:inherit;font-size:.78rem;font-weight:600;cursor:pointer;border:none;text-decoration:none;transition:all .15s;line-height:1}
.btn-gold{background:var(--gold);color:#090909}
.btn-gold:hover{background:#c49b30}
.btn-outline{background:transparent;color:var(--text-dim);border:1px solid var(--border2)}
.btn-outline:hover{border-color:var(--gold-dim);color:var(--text)}
.btn-danger{background:rgba(224,82,82,.1);color:var(--red);border:1px solid rgba(224,82,82,.2)}
.btn-danger:hover{background:rgba(224,82,82,.18)}
.btn-sm{padding:.28rem .65rem;font-size:.7rem}
.btn-xs{padding:.18rem .5rem;font-size:.65rem}

/* ── Filter pills ── */
.cms-filters{display:flex;gap:.4rem;flex-wrap:wrap}
.filter-btn{
  padding:.3rem .85rem;border-radius:50px;border:1px solid var(--border2);
  background:transparent;color:var(--text-dim);font-size:.72rem;cursor:pointer;
  font-family:inherit;transition:all .15s;
}
.filter-btn.active,.filter-btn:hover{background:var(--gold-glow);border-color:var(--gold-dim);color:var(--gold)}

/* ── Forms ── */
.cms-form-row{display:grid;gap:1rem;margin-bottom:1rem}
.cms-form-row.cols-2{grid-template-columns:1fr 1fr}
.cms-form-row.cols-3{grid-template-columns:1fr 1fr 1fr}
.cms-field{display:flex;flex-direction:column;gap:.3rem}
.cms-label{font-size:.67rem;letter-spacing:.07em;text-transform:uppercase;color:var(--text-dim);font-weight:600}
.cms-input,.cms-select,.cms-textarea{
  background:var(--surface2);border:1px solid var(--border2);color:var(--text);
  border-radius:6px;padding:.55rem .8rem;font-family:inherit;font-size:.82rem;
  transition:border-color .15s;outline:none;width:100%;
}
.cms-input:focus,.cms-select:focus,.cms-textarea:focus{border-color:rgba(212,175,55,.4)}
.cms-select option{background:var(--surface2)}
.cms-textarea{resize:vertical;min-height:80px}
.cms-input-sm{max-width:120px}
.cms-input-md{max-width:200px}

/* ── Alerts ── */
.alert{padding:.7rem 1rem;border-radius:7px;font-size:.78rem;margin-bottom:1rem}
.alert-ok{background:rgba(76,175,120,.1);border:1px solid rgba(76,175,120,.2);color:#4caf78}
.alert-err{background:rgba(224,82,82,.1);border:1px solid rgba(224,82,82,.2);color:#e05252}
.alert-info{background:rgba(212,175,55,.08);border:1px solid var(--gold-dim);color:var(--gold)}

/* ── Grid helpers ── */
.cms-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1.25rem}
.flex-between{display:flex;align-items:center;justify-content:space-between;gap:.75rem;flex-wrap:wrap}
.gap-sm{gap:.5rem}
.mt-1{margin-top:.75rem}
.text-dim{color:var(--text-dim)}
.text-faint{color:var(--text-faint);font-size:.75rem}
.text-gold{color:var(--gold)}
.text-sm{font-size:.75rem}
.nowrap{white-space:nowrap}

/* ── Service editor ── */
.svc-cat{border:1px solid var(--border2);border-radius:8px;overflow:hidden;margin-bottom:1rem}
.svc-cat-head{
  background:var(--surface2);padding:.75rem 1rem;cursor:pointer;
  display:flex;align-items:center;justify-content:space-between;user-select:none;
}
.svc-cat-head h3{font-size:.82rem;font-weight:600}
.svc-cat-head .svc-cat-toggle{color:var(--text-faint);font-size:.75rem;transition:transform .2s}
.svc-cat.open .svc-cat-toggle{transform:rotate(180deg)}
.svc-cat-body{display:none;padding:1rem}
.svc-cat.open .svc-cat-body{display:block}
.svc-list-tbl{width:100%;border-collapse:collapse;font-size:.78rem}
.svc-list-tbl th{text-align:left;padding:.4rem .5rem;font-size:.65rem;color:var(--text-faint);font-weight:600;border-bottom:1px solid var(--border2)}
.svc-list-tbl td{padding:.4rem .5rem;border-bottom:1px solid rgba(240,235,224,.04)}
.svc-list-tbl td input{background:var(--surface);border:1px solid var(--border2);color:var(--text);border-radius:4px;padding:.25rem .45rem;font-family:inherit;font-size:.78rem;width:100%;outline:none}
.svc-list-tbl td input:focus{border-color:rgba(212,175,55,.35)}
.matrix-tbl{border-collapse:collapse;font-size:.75rem;width:100%}
.matrix-tbl th,.matrix-tbl td{padding:.35rem .6rem;border:1px solid var(--border2);text-align:center}
.matrix-tbl th{background:var(--surface2);color:var(--text-dim);font-weight:600;font-size:.65rem}
.matrix-tbl td input{background:var(--surface);border:none;color:var(--text);border-radius:3px;padding:.2rem .35rem;font-family:inherit;font-size:.75rem;width:70px;text-align:center;outline:none}
.matrix-tbl td input:focus{outline:1px solid rgba(212,175,55,.4)}

/* ── Blocked dates ── */
.blocked-list{display:flex;flex-wrap:wrap;gap:.4rem;margin-top:.6rem}
.blocked-chip{
  display:inline-flex;align-items:center;gap:.35rem;padding:.3rem .65rem;
  background:rgba(224,82,82,.1);border:1px solid rgba(224,82,82,.2);
  border-radius:50px;font-size:.72rem;color:var(--red);
}
.blocked-chip button{background:none;border:none;cursor:pointer;color:var(--red);padding:0;line-height:1;font-size:.85rem}

/* ── Responsive ── */
@media(max-width:960px){
  .cms-stats{grid-template-columns:repeat(2,1fr)}
  .cms-grid-2{grid-template-columns:1fr}
  .cms-form-row.cols-3{grid-template-columns:1fr 1fr}
}
@media(max-width:700px){
  .cms-sidebar{transform:translateX(-100%)}
  .cms-sidebar.sb-open{transform:translateX(0);box-shadow:0 0 0 100vw rgba(0,0,0,.6)}
  .cms-main{margin-left:0}
  .cms-hamburger{display:flex}
  .cms-stats{grid-template-columns:1fr 1fr}
  .cms-form-row.cols-2,.cms-form-row.cols-3{grid-template-columns:1fr}
  .cms-body{padding:1rem}
}
@media(max-width:420px){
  .cms-stats{grid-template-columns:1fr}
}
</style>
</head>
<body>

<aside class="cms-sidebar" id="cms-sidebar">
  <div class="cms-sb-logo">
    <div class="cms-sb-logo-mark">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L3 7v10l9 5 9-5V7z"/><path d="M12 22V12"/><path d="M3 7l9 5 9-5"/></svg>
    </div>
    <div class="cms-sb-logo-text">Braids by Portia <small>Studio CMS</small></div>
  </div>

  <nav class="cms-nav">
    <span class="cms-nav-section">Overview</span>
    <a href="dashboard.php" class="cms-nav-link <?= ($active_page??'')==='dashboard'?'active':'' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
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
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>
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
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      Sign out
    </a>
  </div>
</aside>

<div class="cms-main">
  <header class="cms-header">
    <div style="display:flex;align-items:center;gap:.75rem">
      <button class="cms-hamburger" id="cms-hamburger" aria-label="Menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </button>
      <span class="cms-header-title"><?= htmlspecialchars($page_title ?? '') ?></span>
    </div>
    <div class="cms-header-right">
      <span class="cms-header-date"><?= date('D, M j, Y') ?></span>
      <a href="../index.php" target="_blank" style="color:var(--text-faint);font-size:.7rem;text-decoration:none">↗ View site</a>
    </div>
  </header>

  <div class="cms-body">
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
