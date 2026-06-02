<?php

declare(strict_types=1);

namespace App\Middleware;

class GuestMiddleware
{
    public function handle(string $method, string $uri): void
    {
        if (! auth_check()) {
            return;
        }

        redirect(guest_only_redirect_path());
    }
}
