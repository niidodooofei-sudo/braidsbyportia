<?php
header('Content-Type: application/json');
require_once dirname(__DIR__) . '/includes/auth.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION[CMS_SESSION])) { http_response_code(401); echo json_encode(['error'=>'Unauthorized']); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['error'=>'Method not allowed']); exit; }

require_once CMS_ROOT . '/config/stripe.php';

$body = json_decode(file_get_contents('php://input'), true);
$id   = preg_replace('/[^a-zA-Z0-9\-]/', '', $body['id'] ?? '');
$pi   = preg_replace('/[^a-zA-Z0-9_]/', '', $body['payment_intent_id'] ?? '');
$do_refund = !empty($body['refund']);

if (!$id) { http_response_code(400); echo json_encode(['error'=>'Missing booking ID']); exit; }

$all = cms_bookings();
$found = false;
foreach ($all as &$b) {
    if (($b['id'] ?? '') === $id) {
        $b['status'] = 'cancelled';
        $b['cancelled_at'] = date('c');
        $found = true;
        break;
    }
}
unset($b);

if (!$found) { http_response_code(404); echo json_encode(['error'=>'Booking not found']); exit; }

cms_save_bookings($all);

$refund_msg = '';
if ($do_refund && $pi) {
    $ch = curl_init('https://api.stripe.com/v1/refunds');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_USERPWD        => STRIPE_SECRET_KEY . ':',
        CURLOPT_POSTFIELDS     => http_build_query(['payment_intent' => $pi]),
    ]);
    $res  = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $rf = json_decode($res, true);
    if ($code === 200 && !empty($rf['id'])) {
        $refund_msg = ' Deposit refunded to client.';
    } else {
        $refund_msg = ' Refund failed: ' . ($rf['error']['message'] ?? 'unknown error') . '. Issue it manually in Stripe.';
    }
}

echo json_encode(['success' => true, 'message' => 'Booking cancelled.' . $refund_msg]);
