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
    error_log('stripe webhook: payment_intent.succeeded ' . ($event['data']['object']['id'] ?? 'unknown'));
}

http_response_code(200);
echo json_encode(['received' => true]);
