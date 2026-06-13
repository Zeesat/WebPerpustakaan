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

        if (request_expects_json()) {
            json_response([
                'success' => false,
                'code' => 'session_expired',
                'message' => 'Your session has expired. Please sign in again.',
            ], 401);
            exit;
        }

        set_intended_path($uri);
        flash('error', 'Please sign in to continue.');
        redirect('/login');
    }
}
