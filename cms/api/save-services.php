<?php
header('Content-Type: application/json');
require_once dirname(__DIR__) . '/includes/auth.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION[CMS_SESSION])) { http_response_code(401); echo json_encode(['error'=>'Unauthorized']); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['error'=>'Method not allowed']); exit; }

$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data) || empty($data)) {
    http_response_code(400); echo json_encode(['error'=>'Invalid data']); exit;
}

// Basic sanitisation — ensure expected keys exist per category
$allowed_types = ['list', 'matrix'];
foreach ($data as $key => &$cat) {
    if (!isset($cat['name']) || !isset($cat['type'])) { http_response_code(400); echo json_encode(['error'=>'Invalid category: '.$key]); exit; }
    if (!in_array($cat['type'], $allowed_types, true)) { http_response_code(400); echo json_encode(['error'=>'Invalid type']); exit; }
    $cat['name'] = substr(strip_tags($cat['name']), 0, 100);
    $cat['desc'] = substr(strip_tags($cat['desc'] ?? ''), 0, 300);
}
unset($cat);

$f  = CMS_DATA . '/services.json';
$fp = fopen($f, 'c+');
$ok = false;
if (flock($fp, LOCK_EX)) {
    ftruncate($fp, 0); rewind($fp);
    fwrite($fp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    flock($fp, LOCK_UN);
    $ok = true;
}
fclose($fp);

echo json_encode(['success' => $ok]);
