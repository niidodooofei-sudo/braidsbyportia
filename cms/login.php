<?php
require_once 'includes/auth.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!empty($_SESSION[CMS_SESSION])) { header('Location: dashboard.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass = $_POST['password'] ?? '';
    if ($pass && cms_verify_password($pass)) {
        cms_login();
        header('Location: dashboard.php'); exit;
    }
    $error = 'Incorrect password. Try again.';
}
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In — Braids by Portia CMS</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{
  font-family:'Poppins',sans-serif;background:#080808;color:#ede8dc;
  min-height:100vh;display:flex;align-items:center;justify-content:center;
  padding:1.5rem;-webkit-font-smoothing:antialiased;
}
.login-bg{
  position:fixed;inset:0;pointer-events:none;
  background:radial-gradient(ellipse 55% 40% at 50% -5%,rgba(212,175,55,.07) 0%,transparent 65%);
}
.login-wrap{width:100%;max-width:350px;position:relative;z-index:1}
.login-logo{text-align:center;margin-bottom:2rem}
.login-logo-img{
  width:66px;height:66px;border-radius:50%;object-fit:cover;
  border:2px solid rgba(212,175,55,.3);
  box-shadow:0 0 0 6px rgba(212,175,55,.05),0 0 0 12px rgba(212,175,55,.02);
  margin-bottom:.9rem;
}
.login-logo h1{font-size:1.05rem;font-weight:700;color:#d4af37;letter-spacing:-.01em}
.login-logo p{font-size:.7rem;color:rgba(237,232,220,.3);margin-top:.2rem}
.login-card{
  background:#0f0f0f;
  border:1px solid rgba(255,255,255,.07);
  border-radius:14px;padding:2rem;
  box-shadow:0 24px 64px rgba(0,0,0,.6);
}
.login-field{margin-bottom:1.1rem}
.login-field label{display:block;font-size:.64rem;letter-spacing:.08em;text-transform:uppercase;color:rgba(237,232,220,.35);font-weight:600;margin-bottom:.42rem}
.login-field input{
  width:100%;background:#151515;border:1px solid rgba(255,255,255,.07);color:#ede8dc;
  border-radius:8px;padding:.68rem .9rem;font-family:inherit;font-size:.9rem;outline:none;
  transition:border-color .15s,box-shadow .15s;
}
.login-field input:focus{border-color:rgba(212,175,55,.3);box-shadow:0 0 0 3px rgba(212,175,55,.06)}
.login-btn{
  width:100%;margin-top:1.2rem;padding:.72rem;
  background:linear-gradient(135deg,#d4af37 0%,#c49b2e 100%);
  color:#080808;border:none;border-radius:8px;font-family:inherit;
  font-size:.88rem;font-weight:700;cursor:pointer;letter-spacing:.01em;
  transition:opacity .15s,box-shadow .15s;
}
.login-btn:hover{opacity:.9;box-shadow:0 0 0 3px rgba(212,175,55,.15)}
.login-error{background:rgba(224,92,92,.07);border:1px solid rgba(224,92,92,.16);color:#e05c5c;border-radius:7px;padding:.6rem .85rem;font-size:.78rem;margin-bottom:.9rem}
.login-hint{margin-top:1.1rem;font-size:.66rem;color:rgba(237,232,220,.22);text-align:center;line-height:1.7;border-top:1px solid rgba(255,255,255,.04);padding-top:.9rem}
.login-hint strong{color:rgba(237,232,220,.4)}
</style>
</head>
<body>
<div class="login-bg"></div>
<div class="login-wrap">
  <div class="login-logo">
    <img src="../PHOTO-2026-04-24-12-40-21.jpg" alt="Braids by Portia" class="login-logo-img">
    <h1>Braids by Portia</h1>
    <p>Studio CMS</p>
  </div>
  <div class="login-card">
    <?php if ($error): ?><div class="login-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="POST" autocomplete="off">
      <div class="login-field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" autofocus placeholder="Enter your password">
      </div>
      <button type="submit" class="login-btn">Sign In →</button>
    </form>
    <p class="login-hint">Default password: <strong>braids2024</strong><br>Change it in Settings after signing in.</p>
  </div>
</div>
</body>
</html>
