<?php
// Load Stripe config from data/settings.json (editable via CMS), fall back to env vars
$_stripe_settings_file = dirname(__DIR__) . '/data/settings.json';
$_stripe_s = is_file($_stripe_settings_file) ? (json_decode(file_get_contents($_stripe_settings_file), true) ?? []) : [];

define('STRIPE_PUBLISHABLE_KEY', (!empty($_stripe_s['stripe_publishable_key']) ? $_stripe_s['stripe_publishable_key'] : (getenv('STRIPE_PK') ?: 'pk_test_YOUR_PUBLISHABLE_KEY_HERE')));
define('STRIPE_SECRET_KEY',      (!empty($_stripe_s['stripe_secret_key'])      ? $_stripe_s['stripe_secret_key']      : (getenv('STRIPE_SK') ?: 'sk_test_YOUR_SECRET_KEY_HERE')));
define('STRIPE_WEBHOOK_SECRET',  (!empty($_stripe_s['stripe_webhook_secret'])  ? $_stripe_s['stripe_webhook_secret']  : (getenv('STRIPE_WH') ?: 'whsec_YOUR_WEBHOOK_SECRET_HERE')));

$_deposit = !empty($_stripe_s['deposit_amount']) ? (int)$_stripe_s['deposit_amount'] : 50;
define('DEPOSIT_AMOUNT_CENTS',   $_deposit * 100);
define('DEPOSIT_AMOUNT_DOLLARS', $_deposit);
unset($_stripe_settings_file, $_stripe_s, $_deposit);
