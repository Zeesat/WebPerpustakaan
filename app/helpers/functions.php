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

function url(string $path = '/'): string
{
    $baseUrl = rtrim((string) app_config('base_url', '/'), '/');
    $normalizedPath = '/' . ltrim($path, '/');

    if ($baseUrl === '') {
        return $normalizedPath;
    }

    return $baseUrl . ($normalizedPath === '/' ? '' : $normalizedPath);
}

function current_path(): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

    if (! is_string($path) || $path === '') {
        return '/';
    }

    return $path;
}

function is_current_path(string $path): bool
{
    $normalizedPath = '/' . ltrim($path, '/');

    return current_path() === $normalizedPath;
}

function redirect(string $path, int $statusCode = 302): void
{
    if (defined('APP_TESTING') && APP_TESTING) {
        throw new RuntimeException(sprintf('REDIRECT|%s|%d', $path, $statusCode));
    }

    header('Location: ' . url($path), true, $statusCode);
    exit;
}

function session_get(string $key, mixed $default = null): mixed
{
    return $_SESSION[$key] ?? $default;
}

function session_put(string $key, mixed $value): void
{
    $_SESSION[$key] = $value;
}

function session_forget(string $key): void
{
    unset($_SESSION[$key]);
}

function flash(string $key, mixed $value): void
{
    $_SESSION['_flash'][$key] = $value;
}

function flash_get(string $key, mixed $default = null): mixed
{
    $value = $_SESSION['_flash'][$key] ?? $default;
    unset($_SESSION['_flash'][$key]);

    return $value;
}

function flash_has(string $key): bool
{
    return array_key_exists($key, $_SESSION['_flash'] ?? []);
}

function old(string $key, mixed $default = ''): mixed
{
    return $_SESSION['_old'][$key] ?? $default;
}

function with_old_input(array $input): void
{
    $_SESSION['_old'] = $input;
}

function clear_old_input(): void
{
    unset($_SESSION['_old']);
}

function auth_user(): ?array
{
    $user = session_get('auth.user');

    return is_array($user) ? $user : null;
}

function auth_check(): bool
{
    return auth_user() !== null;
}

function auth_is_admin(): bool
{
    return (auth_user()['role'] ?? null) === 'admin';
}

function guest_only_redirect_path(): string
{
    if (! auth_check()) {
        return '/login';
    }

    return auth_is_admin() ? '/admin' : '/dashboard';
}

function consume_intended_path(): string
{
    $path = session_get('url.intended', auth_is_admin() ? '/admin' : '/dashboard');
    session_forget('url.intended');

    return is_string($path) ? $path : '/dashboard';
}

function set_intended_path(string $path): void
{
    if ($path !== '') {
        session_put('url.intended', $path);
    }
}

function csrf_token(): string
{
    $token = session_get('csrf.token');

    if (! is_string($token) || $token === '') {
        $token = bin2hex(random_bytes(32));
        session_put('csrf.token', $token);
    }

    return $token;
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

function verify_csrf_token(?string $token): bool
{
    $sessionToken = session_get('csrf.token');

    if (! is_string($sessionToken) || $sessionToken === '' || ! is_string($token)) {
        return false;
    }

    return hash_equals($sessionToken, $token);
}

