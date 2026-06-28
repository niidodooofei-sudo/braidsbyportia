<?php
header('Content-Type: application/json');
require dirname(__DIR__) . '/config/stripe.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); echo json_encode(['error'=>'Method not allowed']); exit;
}

$body = json_decode(file_get_contents('php://input'), true);
$pi_id   = preg_replace('/[^a-zA-Z0-9_]/', '', $body['payment_intent_id'] ?? '');
$booking = $body['booking'] ?? [];

if (!$pi_id || !$booking) {
    http_response_code(400); echo json_encode(['error'=>'Missing data']); exit;
}

// Verify PaymentIntent status with Stripe
$ch = curl_init('https://api.stripe.com/v1/payment_intents/' . $pi_id);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_USERPWD        => STRIPE_SECRET_KEY . ':',
]);
$res  = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$pi = json_decode($res, true);
if ($code !== 200 || ($pi['status'] ?? '') !== 'succeeded') {
    http_response_code(402);
    echo json_encode(['error' => 'Payment not confirmed']);
    exit;
}

// Build booking record
$ref = 'bp-' . date('Ymd') . '-' . substr(bin2hex(random_bytes(3)), 0, 6);
$record = [
    'id'               => $ref,
    'service_name'     => htmlspecialchars($booking['service_name'] ?? ''),
    'service_id'       => htmlspecialchars($booking['service_id']   ?? ''),
    'price'            => (int)($booking['price']    ?? 0),
    'deposit'          => DEPOSIT_AMOUNT_DOLLARS,
    'duration'         => (int)($booking['duration'] ?? 0),
    'date'             => preg_replace('/[^0-9\-]/', '', $booking['date'] ?? ''),
    'time'             => preg_replace('/[^0-9:]/', '', $booking['time'] ?? ''),
    'customer'         => [
        'name'  => htmlspecialchars($booking['customer']['name']  ?? ''),
        'email' => filter_var($booking['customer']['email'] ?? '', FILTER_SANITIZE_EMAIL),
        'phone' => htmlspecialchars($booking['customer']['phone'] ?? ''),
        'notes' => htmlspecialchars($booking['customer']['notes'] ?? ''),
    ],
    'payment_intent_id'=> $pi_id,
    'status'           => 'confirmed',
    'created_at'       => date('c'),
];

// Persist to bookings.json — best-effort (filesystem may be read-only on Vercel)
$path = dirname(__DIR__) . '/data/bookings.json';
if (is_writable(dirname($path))) {
    $fp = @fopen($path, 'c+');
    if ($fp && flock($fp, LOCK_EX)) {
        $all = json_decode(stream_get_contents($fp), true) ?? [];
        $all[] = $record;
        ftruncate($fp, 0); rewind($fp);
        fwrite($fp, json_encode($all, JSON_PRETTY_PRINT));
        flock($fp, LOCK_UN);
    }
    if ($fp) fclose($fp);
}

// Email to client
require_once dirname(__DIR__) . '/includes/mailer.php';
$client_msg = "Hi {$record['customer']['name']},\n\n"
    . "Your appointment is confirmed!\n\n"
    . "Service: {$record['service_name']}\n"
    . "Date:    " . date('l, F j, Y', strtotime($record['date'])) . "\n"
    . "Time:    " . date('g:i A', strtotime($record['time'])) . "\n"
    . "Deposit paid: \$" . $record['deposit'] . "\n"
    . "Balance due at appointment: \$" . ($record['price'] - $record['deposit']) . "\n\n"
    . "Please arrive 5–10 minutes early. To reschedule, contact us at least 24 hrs in advance.\n\n"
    . "— Braids by Portia";
send_email($record['customer']['email'], 'Booking Confirmed – Braids by Portia', $client_msg);

// Notification to salon
$salon = getenv('SALON_EMAIL') ?: 'portia@braidsbyportia.com';
$salon_msg = "New booking from {$record['customer']['name']} ({$record['customer']['phone']})\n"
    . "Service: {$record['service_name']}\n"
    . "Date: {$record['date']} at {$record['time']}\n"
    . "Notes: {$record['customer']['notes']}";
send_email($salon, 'New Booking – ' . $record['service_name'], $salon_msg);

echo json_encode(['success' => true, 'ref' => $ref]);
