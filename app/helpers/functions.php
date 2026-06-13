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

function request_expects_json(): bool
{
    $accept = strtolower((string) ($_SERVER['HTTP_ACCEPT'] ?? ''));
    $requestedWith = strtolower((string) ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? ''));
    $contentType = strtolower((string) ($_SERVER['CONTENT_TYPE'] ?? ''));

    return str_contains($accept, 'application/json')
        || $requestedWith === 'xmlhttprequest'
        || str_contains($contentType, 'application/json');
}

function json_response(array $payload, int $statusCode = 200): void
{
    http_response_code($statusCode);

    if (! headers_sent()) {
        header('Content-Type: application/json; charset=UTF-8');
    }

    echo json_encode($payload, JSON_UNESCAPED_SLASHES);
}

function json_attr(mixed $value): string
{
    $encoded = json_encode($value, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);

    return htmlspecialchars($encoded !== false ? $encoded : 'null', ENT_QUOTES, 'UTF-8');
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

// ============================================================
//  COVER IMAGE HELPERS
// ============================================================

/**
 * Generate URL for a book cover image.
 * Returns null if no cover is set.
 */
function cover_url(?string $cover): ?string
{
    if ($cover === null || $cover === '') {
        return null;
    }

    return url('/uploads/covers/' . ltrim($cover, '/'));
}

/**
 * Save an uploaded cover file to the covers directory.
 * The file should already be WebP (converted client-side).
 * Falls back to GD conversion if needed.
 * Returns the filename (e.g., "42.webp") on success, or null on failure.
 */
function save_cover_file(array $file, int $bookId): ?string
{
    if (! isset($file['tmp_name']) || ! is_uploaded_file($file['tmp_name'])) {
        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    // Validate supported image types
    $allowedMimes = ['image/webp', 'image/jpeg', 'image/png', 'image/gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (! in_array($mime, $allowedMimes, true)) {
        return null;
    }

    // Max file size: 5MB
    if ($file['size'] > 5 * 1024 * 1024) {
        return null;
    }

    $uploadDir = BASE_PATH . '/public/uploads/covers/';

    if (! is_dir($uploadDir)) {
        if (! mkdir($uploadDir, 0755, true) && ! is_dir($uploadDir)) {
            return null;
        }
    }

    $filename = $bookId . '.webp';
    $destination = $uploadDir . $filename;

    // If already WebP, just move it
    if ($mime === 'image/webp') {
        if (! move_uploaded_file($file['tmp_name'], $destination)) {
            return null;
        }
        return $filename;
    }

    // Server-side conversion fallback via GD
    $image = null;
    switch ($mime) {
        case 'image/jpeg':
            $image = @imagecreatefromjpeg($file['tmp_name']);
            break;
        case 'image/png':
            $image = @imagecreatefrompng($file['tmp_name']);
            break;
        case 'image/gif':
            $image = @imagecreatefromgif($file['tmp_name']);
            break;
    }

    if ($image) {
        imagealphablending($image, true);
        imagesavealpha($image, true);
        $success = imagewebp($image, $destination, 85);
        imagedestroy($image);

        if ($success) {
            return $filename;
        }
    }

    // Final fallback: store as-is
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return $filename;
    }

    return null;
}

/**
 * Delete a cover file by filename.
 */
function delete_cover_file(?string $cover): void
{
    if ($cover === null || $cover === '') {
        return;
    }

    $filePath = BASE_PATH . '/public/uploads/covers/' . ltrim($cover, '/');

    if (file_exists($filePath)) {
        @unlink($filePath);
    }
}
