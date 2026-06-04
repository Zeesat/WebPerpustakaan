<?php

declare(strict_types=1);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$file = __DIR__ . '/public' . ($path === '/' ? '/index.php' : $path);

if (is_string($path) && is_file($file)) {
    return false;
}

require __DIR__ . '/public/index.php';
