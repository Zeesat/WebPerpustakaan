<?php

declare(strict_types=1);

session_start();

define('BASE_PATH', dirname(__DIR__));

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
    }
});

$app = new App\Core\App();
$app->run();

