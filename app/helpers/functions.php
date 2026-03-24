<?php

declare(strict_types=1);

function app_config(string $key, mixed $default = null): mixed
{
    static $config = null;

    if ($config === null) {
        $config = require BASE_PATH . '/app/config/app.php';
    }

    return $config[$key] ?? $default;
}

function asset(string $path): string
{
    return rtrim(app_config('base_url', '/'), '/') . '/assets/' . ltrim($path, '/');
}

