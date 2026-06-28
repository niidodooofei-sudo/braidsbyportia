<?php
if (!defined('APP_BASE')) {
    // Works at domain root (/router.php → '') or any subdirectory (/test/router.php → '/test')
    $__d = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    define('APP_BASE', $__d === '/' ? '' : rtrim($__d, '/'));
}
