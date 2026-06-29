<?php
define('CMS_ROOT',    dirname(dirname(__DIR__)));
define('CMS_DATA',    CMS_ROOT . '/data');
define('CMS_CONFIG',  CMS_ROOT . '/config');
define('CMS_SESSION', 'bbp_cms_auth');
define('CMS_DEFAULT_PASS', 'braids2024');

function cms_settings(): array {
    $f = CMS_DATA . '/settings.json';
    return is_file($f) ? (json_decode(file_get_contents($f), true) ?? []) : [];
}

function cms_save_settings(array $patch): void {
    $f   = CMS_DATA . '/settings.json';
    $cur = cms_settings();
    $fp  = fopen($f, 'c+');
    if (flock($fp, LOCK_EX)) {
        ftruncate($fp, 0); rewind($fp);
        fwrite($fp, json_encode(array_merge($cur, $patch), JSON_PRETTY_PRINT));
        flock($fp, LOCK_UN);
    }
    fclose($fp);
}

function cms_bookings(): array {
    $f = CMS_DATA . '/bookings.json';
    return is_file($f) ? (json_decode(file_get_contents($f), true) ?? []) : [];
}

function cms_save_bookings(array $all): void {
    $f  = CMS_DATA . '/bookings.json';
    $fp = fopen($f, 'c+');
    if (flock($fp, LOCK_EX)) {
        ftruncate($fp, 0); rewind($fp);
        fwrite($fp, json_encode($all, JSON_PRETTY_PRINT));
        flock($fp, LOCK_UN);
    }
    fclose($fp);
}

function cms_check_auth(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION[CMS_SESSION])) {
        header('Location: login.php'); exit;
    }
}

function cms_verify_password(string $input): bool {
    $s = cms_settings();
    if (!empty($s['admin_password_hash'])) {
        return password_verify($input, $s['admin_password_hash']);
    }
    return hash_equals(CMS_DEFAULT_PASS, $input);
}

function cms_login(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    session_regenerate_id(true);
    $_SESSION[CMS_SESSION] = true;
}

function cms_logout(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}
