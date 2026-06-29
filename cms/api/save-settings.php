<?php
header('Content-Type: application/json');
require_once dirname(__DIR__) . '/includes/auth.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION[CMS_SESSION])) { http_response_code(401); echo json_encode(['error'=>'Unauthorized']); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['error'=>'Method not allowed']); exit; }

$body = json_decode(file_get_contents('php://input'), true);

$patch = [];

if (isset($body['salon_email'])) {
    $email = filter_var(trim($body['salon_email']), FILTER_VALIDATE_EMAIL);
    if (!$email) { echo json_encode(['error'=>'Invalid email address']); exit; }
    $patch['salon_email'] = $email;
}

if (isset($body['deposit_amount'])) {
    $dep = (int)$body['deposit_amount'];
    if ($dep < 1 || $dep > 500) { echo json_encode(['error'=>'Deposit must be $1–$500']); exit; }
    $patch['deposit_amount'] = $dep;
}

if (isset($body['stripe_publishable_key'])) {
    $patch['stripe_publishable_key'] = trim($body['stripe_publishable_key']);
}
if (isset($body['stripe_secret_key'])) {
    $v = trim($body['stripe_secret_key']);
    if ($v && !str_starts_with($v, 'sk_')) { echo json_encode(['error'=>'Secret key must start with sk_']); exit; }
    $patch['stripe_secret_key'] = $v;
}
if (isset($body['stripe_webhook_secret'])) {
    $patch['stripe_webhook_secret'] = trim($body['stripe_webhook_secret']);
}

$msg = 'Settings saved.';
if (!empty($body['new_password'])) {
    $pass = $body['new_password'];
    if (strlen($pass) < 8) { echo json_encode(['error'=>'Password must be at least 8 characters']); exit; }
    $patch['admin_password_hash'] = password_hash($pass, PASSWORD_DEFAULT);
    $msg = 'Settings saved. Password updated — you will use the new password next time you sign in.';
}

cms_save_settings($patch);
echo json_encode(['success' => true, 'message' => $msg]);
