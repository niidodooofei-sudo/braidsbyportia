<?php
header('Content-Type: application/json');
require dirname(__DIR__) . '/config/stripe.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); echo json_encode(['error'=>'Method not allowed']); exit;
}

$body = json_decode(file_get_contents('php://input'), true);
$service_name = htmlspecialchars($body['service_name'] ?? 'Braiding Service');

// Build Stripe PaymentIntent via REST
$ch = curl_init('https://api.stripe.com/v1/payment_intents');
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_USERPWD        => STRIPE_SECRET_KEY . ':',
    CURLOPT_POSTFIELDS     => http_build_query([
        'amount'                    => DEPOSIT_AMOUNT_CENTS,
        'currency'                  => 'usd',
        'description'               => 'Deposit — ' . $service_name,
        'payment_method_types[]'    => 'card',
        'metadata[service]'         => $service_name,
    ]),
]);
$res  = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$pi = json_decode($res, true);
if ($code !== 200 || empty($pi['client_secret'])) {
    http_response_code(500);
    echo json_encode(['error' => $pi['error']['message'] ?? 'Stripe error']);
    exit;
}

echo json_encode(['client_secret' => $pi['client_secret']]);
