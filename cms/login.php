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
  font-family:'Poppins',sans-serif;background:#0c0e14;color:#e8e4d9;
  min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1.5rem;
  -webkit-font-smoothing:antialiased;
}
.login-bg{
  position:fixed;inset:0;
  background:radial-gradient(ellipse 60% 50% at 50% 0%, rgba(212,175,55,.06) 0%, transparent 70%);
  pointer-events:none;
}
.login-wrap{width:100%;max-width:360px;position:relative;z-index:1}
.login-logo{text-align:center;margin-bottom:2rem}
.login-logo-img{
  width:64px;height:64px;border-radius:50%;object-fit:cover;
  border:2px solid rgba(212,175,55,.35);
  box-shadow:0 0 0 6px rgba(212,175,55,.07);
  margin-bottom:.85rem;
}
.login-logo h1{font-size:1.1rem;font-weight:700;color:#d4af37;letter-spacing:-.01em}
.login-logo p{font-size:.72rem;color:rgba(232,228,217,.35);margin-top:.2rem}
.login-card{
  background:#131620;border:1px solid rgba(255,255,255,.055);
  border-radius:14px;padding:2rem;
  box-shadow:0 20px 60px rgba(0,0,0,.5),0 0 0 1px rgba(212,175,55,.04);
}
.login-field{margin-bottom:1.1rem}
.login-field label{display:block;font-size:.65rem;letter-spacing:.08em;text-transform:uppercase;color:rgba(232,228,217,.4);font-weight:600;margin-bottom:.4rem}
.login-field input{
  width:100%;background:#181b26;border:1px solid rgba(255,255,255,.06);color:#e8e4d9;
  border-radius:8px;padding:.7rem .9rem;font-family:inherit;font-size:.9rem;outline:none;
  transition:border-color .15s,box-shadow .15s;
}
.login-field input:focus{border-color:rgba(212,175,55,.35);box-shadow:0 0 0 3px rgba(212,175,55,.07)}
.login-btn{
  width:100%;margin-top:1.25rem;padding:.75rem;
  background:linear-gradient(135deg,#d4af37,#c49b30);
  color:#090909;border:none;border-radius:8px;font-family:inherit;
  font-size:.88rem;font-weight:700;cursor:pointer;
  transition:opacity .15s,box-shadow .15s;letter-spacing:.01em;
}
.login-btn:hover{opacity:.92;box-shadow:0 0 0 3px rgba(212,175,55,.18)}
.login-error{
  background:rgba(224,92,92,.08);border:1px solid rgba(224,92,92,.2);color:#e05c5c;
  border-radius:8px;padding:.65rem .9rem;font-size:.78rem;margin-bottom:1rem;
}
.login-hint{margin-top:1.25rem;font-size:.68rem;color:rgba(232,228,217,.25);text-align:center;line-height:1.6;border-top:1px solid rgba(255,255,255,.04);padding-top:1rem}
.login-hint strong{color:rgba(232,228,217,.45)}
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
    <p class="login-hint">Default password: <strong>braids2024</strong><br>Change it immediately in Settings.</p>
  </div>
</div>
</body>
</html>
