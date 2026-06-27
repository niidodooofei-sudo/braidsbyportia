<?php
require dirname(__DIR__) . '/config/stripe.php';

$payload   = file_get_contents('php://input');
$sig       = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
$secret    = STRIPE_WEBHOOK_SECRET;

// Verify signature
$parts = [];
foreach (explode(',', $sig) as $part) {
    [$k, $v] = explode('=', $part, 2);
    $parts[$k] = $v;
}
$signed_payload = ($parts['t'] ?? '') . '.' . $payload;
$expected       = hash_hmac('sha256', $signed_payload, $secret);

if (!hash_equals($expected, $parts['v1'] ?? '')) {
    http_response_code(400); exit;
}

$event = json_decode($payload, true);
if (($event['type'] ?? '') === 'payment_intent.succeeded') {
    // Backup confirmation — the frontend confirm-booking.php is the primary path.
    // Log the event for audit purposes.
    $log_path = dirname(__DIR__) . '/data/webhook-log.txt';
    file_put_contents($log_path, date('c') . ' payment_intent.succeeded ' . $event['data']['object']['id'] . PHP_EOL, FILE_APPEND);
}

http_response_code(200);
echo json_encode(['received' => true]);
