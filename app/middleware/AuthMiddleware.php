<?php

declare(strict_types=1);

namespace App\Middleware;

class AuthMiddleware
{
    public function handle(string $method, string $uri): void
    {
        if (auth_check()) {
            return;
        }

        set_intended_path($uri);
        flash('error', 'Please sign in to continue.');
        redirect('/login');
    }
}
