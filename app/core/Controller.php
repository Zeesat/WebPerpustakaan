<?php

declare(strict_types=1);

namespace App\Core;

class Controller
{
    protected function view(string $view, array $data = []): void
    {
        if (! array_key_exists('status', $data)) {
            $data['status'] = flash_get('status');
        }

        if (! array_key_exists('error', $data)) {
            $data['error'] = flash_get('error');
        }

        if (! array_key_exists('currentUser', $data)) {
            $data['currentUser'] = auth_user();
        }

        extract($data);

        $viewPath = BASE_PATH . '/app/views/' . $view . '.php';

        if (! file_exists($viewPath)) {
            http_response_code(500);
            echo "View not found: {$view}";
            return;
        }

        require BASE_PATH . '/app/views/layouts/main.php';
    }

    protected function redirect(string $path, int $statusCode = 302): void
    {
        redirect($path, $statusCode);
    }
}

