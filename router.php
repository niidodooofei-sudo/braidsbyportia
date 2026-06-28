<?php
require __DIR__ . '/config/app.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Strip subdirectory prefix so routes always start with /
if (APP_BASE !== '' && strpos($uri, APP_BASE) === 0) {
    $uri = substr($uri, strlen(APP_BASE));
}
$uri = rtrim($uri, '/') ?: '/';

// Block direct access to data directory
if (strpos($uri, '/data/') === 0) {
    http_response_code(403);
    exit;
}

$routes = [
    '/'                          => 'index.php',
    '/services'                  => 'services.php',
    '/gallery'                   => 'gallery.php',
    '/about'                     => 'about.php',
    '/contact'                   => 'contact.php',
    '/booking'                   => 'booking.php',
    '/booking-success'           => 'booking-success.php',
    '/api/availability'          => 'api/availability.php',
    '/api/create-payment-intent' => 'api/create-payment-intent.php',
    '/api/confirm-booking'       => 'api/confirm-booking.php',
    '/api/webhook'               => 'api/webhook.php',
    '/admin'                     => 'admin/index.php',
];

// Strip .php suffix so both /services and /services.php work
$clean = preg_replace('/\.php$/', '', $uri);
$file  = $routes[$uri] ?? $routes[$clean] ?? null;

if ($file && file_exists($file)) {
    require $file;
} else {
    http_response_code(404);
    require 'index.php';
}
