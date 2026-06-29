<?php
require_once 'includes/auth.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// Already logged in
if (!empty($_SESSION[CMS_SESSION])) {
    header('Location: dashboard.php'); exit;
}

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
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Poppins',sans-serif;background:#080808;color:#f0ebe0;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1rem}
.login-wrap{width:100%;max-width:380px}
.login-logo{text-align:center;margin-bottom:2rem}
.login-logo-mark{
  width:52px;height:52px;border-radius:50%;background:rgba(212,175,55,.1);
  border:1px solid rgba(212,175,55,.25);display:flex;align-items:center;justify-content:center;margin:0 auto .75rem;
}
.login-logo-mark svg{width:22px;height:22px;color:#d4af37}
.login-logo h1{font-size:1.1rem;font-weight:600;color:#d4af37}
.login-logo p{font-size:.75rem;color:rgba(240,235,224,.45);margin-top:.2rem}
.login-card{background:#111;border:1px solid rgba(212,175,55,.13);border-radius:12px;padding:2rem}
.login-card label{display:block;font-size:.67rem;letter-spacing:.08em;text-transform:uppercase;color:rgba(240,235,224,.55);font-weight:600;margin-bottom:.4rem}
.login-card input{
  width:100%;background:#181818;border:1px solid rgba(240,235,224,.08);color:#f0ebe0;
  border-radius:7px;padding:.65rem .9rem;font-family:inherit;font-size:.88rem;outline:none;
  transition:border-color .15s;
}
.login-card input:focus{border-color:rgba(212,175,55,.4)}
.login-btn{
  width:100%;margin-top:1.25rem;padding:.7rem;background:#d4af37;color:#090909;
  border:none;border-radius:7px;font-family:inherit;font-size:.88rem;font-weight:600;
  cursor:pointer;transition:background .15s;
}
.login-btn:hover{background:#c49b30}
.login-error{background:rgba(224,82,82,.1);border:1px solid rgba(224,82,82,.2);color:#e05252;border-radius:7px;padding:.6rem .9rem;font-size:.78rem;margin-bottom:1rem}
.login-hint{margin-top:1.25rem;font-size:.7rem;color:rgba(240,235,224,.3);text-align:center;line-height:1.5}
</style>
</head>
<body>
<div class="login-wrap">
  <div class="login-logo">
    <div class="login-logo-mark">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L3 7v10l9 5 9-5V7z"/><path d="M12 22V12"/><path d="M3 7l9 5 9-5"/></svg>
    </div>
    <h1>Braids by Portia</h1>
    <p>Studio CMS</p>
  </div>
  <div class="login-card">
    <?php if ($error): ?><div class="login-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="POST" autocomplete="off">
      <label for="password">Password</label>
      <input type="password" id="password" name="password" autofocus placeholder="Enter your password">
      <button type="submit" class="login-btn">Sign In →</button>
    </form>
    <p class="login-hint">Default password: <strong>braids2024</strong><br>Change it in Settings after signing in.</p>
  </div>
</div>
</body>
</html>
