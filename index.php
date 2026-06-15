<?php

declare(strict_types=1);

ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');

$isHttps = (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['SERVER_PORT'] ?? null) === '443');

session_set_cookie_params([
    'httponly' => true,
    'lifetime' => 0,
    'path' => '/',
    'samesite' => 'Lax',
    'secure' => $isHttps,
]);

session_start();

define('BASE_PATH', __DIR__);

require BASE_PATH . '/app/helpers/functions.php';
require BASE_PATH . '/app/core/Router.php';
require BASE_PATH . '/app/core/Controller.php';
require BASE_PATH . '/app/core/Database.php';
require BASE_PATH . '/app/core/App.php';

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';

    if (strpos($class, $prefix) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = BASE_PATH . '/app/' . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
        return;
    }

    $segments = explode('\\', $relativeClass);
    $fileName = array_pop($segments);
    $fallbackFile = BASE_PATH . '/app/' . implode('/', array_map('strtolower', $segments));
    $fallbackFile .= ($fallbackFile === BASE_PATH . '/app/' ? '' : '/')
        . $fileName . '.php';

    if (file_exists($fallbackFile)) {
        require $fallbackFile;
    }
});

$app = new App\Core\App();
$app->run();

