<?php

declare(strict_types=1);

namespace App\Middleware;

class AdminMiddleware
{
    public function handle(string $method, string $uri): void
    {
        if (! auth_check()) {
            set_intended_path($uri);
            flash('error', 'Please sign in to access admin pages.');
            redirect('/login');
        }

        if (auth_is_admin()) {
            return;
        }

        flash('error', 'You do not have permission to access that page.');
        redirect('/dashboard');
    }
}
