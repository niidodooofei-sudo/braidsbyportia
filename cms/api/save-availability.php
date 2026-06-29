<?php
header('Content-Type: application/json');
require_once dirname(__DIR__) . '/includes/auth.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION[CMS_SESSION])) { http_response_code(401); echo json_encode(['error'=>'Unauthorized']); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['error'=>'Method not allowed']); exit; }

$body = json_decode(file_get_contents('php://input'), true);

// Validate and sanitise
$working_days = array_map('intval', array_filter($body['working_days'] ?? [], fn($d) => $d >= 0 && $d <= 6));
$start_time   = preg_match('/^\d{2}:\d{2}$/', $body['start_time'] ?? '') ? $body['start_time'] : '09:00';
$end_time     = preg_match('/^\d{2}:\d{2}$/', $body['end_time']   ?? '') ? $body['end_time']   : '19:00';
$slot_interval   = in_array((int)($body['slot_interval'] ?? 30), [15,30,45,60]) ? (int)$body['slot_interval'] : 30;
$max_weeks_ahead = max(1, min(52, (int)($body['max_weeks_ahead'] ?? 8)));
$blocked_dates   = array_values(array_filter($body['blocked_dates'] ?? [], fn($d) => preg_match('/^\d{4}-\d{2}-\d{2}$/', $d)));

$data = compact('working_days','start_time','end_time','slot_interval','max_weeks_ahead','blocked_dates');

$f  = CMS_DATA . '/availability.json';
$fp = fopen($f, 'c+');
$ok = false;
if (flock($fp, LOCK_EX)) {
    ftruncate($fp, 0); rewind($fp);
    fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
    flock($fp, LOCK_UN);
    $ok = true;
}
fclose($fp);

echo json_encode(['success' => $ok]);
