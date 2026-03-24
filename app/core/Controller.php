<?php

declare(strict_types=1);

namespace App\Core;

class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);

        $viewPath = BASE_PATH . '/app/views/' . $view . '.php';

        if (! file_exists($viewPath)) {
            http_response_code(500);
            echo "View not found: {$view}";
            return;
        }

        require BASE_PATH . '/app/views/layouts/main.php';
    }
}

