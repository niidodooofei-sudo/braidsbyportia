<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: same-origin');

$date     = $_GET['date']     ?? '';
$duration = (int)($_GET['duration'] ?? 0);

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || $duration <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid parameters']);
    exit;
}

$avail  = require dirname(__DIR__) . '/config/availability.php';
$dow    = (int)date('w', strtotime($date));

if (!in_array($dow, $avail['working_days']) || in_array($date, $avail['blocked_dates'])) {
    echo json_encode(['slots' => []]);
    exit;
}

// Don't allow past dates
if ($date < date('Y-m-d')) {
    echo json_encode(['slots' => []]);
    exit;
}

// Load existing bookings for that date (file may not exist on Vercel — treat as no bookings)
$bookings_path = dirname(__DIR__) . '/data/bookings.json';
$all_bookings  = file_exists($bookings_path) ? (json_decode(file_get_contents($bookings_path), true) ?? []) : [];
$day_bookings  = array_filter($all_bookings, fn($b) => ($b['date'] ?? '') === $date && ($b['status'] ?? '') === 'confirmed');

// Generate candidate slots
$start_ts = strtotime($date . ' ' . $avail['start_time']);
$end_ts   = strtotime($date . ' ' . $avail['end_time']);
$interval = $avail['slot_interval'] * 60;
$now_ts   = time() + 3600; // buffer: must be at least 1 hr from now

$slots = [];
for ($ts = $start_ts; $ts < $end_ts; $ts += $interval) {
    $slot_end = $ts + ($duration * 60);

    // Slot must end by closing time
    if ($slot_end > $end_ts) break;

    // Must be in the future
    if ($ts < $now_ts) continue;

    // Check for overlap with existing bookings
    $overlaps = false;
    foreach ($day_bookings as $b) {
        $b_start = strtotime($date . ' ' . $b['time']);
        $b_end   = $b_start + ($b['duration'] * 60);
        if ($ts < $b_end && $slot_end > $b_start) {
            $overlaps = true;
            break;
        }
    }

    if (!$overlaps) {
        $slots[] = date('H:i', $ts);
    }
}

echo json_encode(['slots' => $slots]);
