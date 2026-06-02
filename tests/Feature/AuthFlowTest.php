<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Middleware\AdminMiddleware;
use App\Middleware\AuthMiddleware;
use App\Models\User;
use App\Services\AuthService;

final class AuthFlowTest extends TestCase
{
    private PDO $connection;
    private AuthController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->connection = new PDO('sqlite::memory:');
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->connection->exec(
            'CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL,
                role TEXT NOT NULL DEFAULT "user",
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            )'
        );

        $this->controller = new AuthController(new AuthService(new User($this->connection)));
        $_SESSION['csrf.token'] = 'known-token';
    }

    public function testAuthMiddlewareRedirectsGuestsToLogin(): void
    {
        $middleware = new AuthMiddleware();

        $this->expectRedirect(
            static function () use ($middleware): void {
                $middleware->handle('GET', '/dashboard');
            },
            '/login'
        );

        $this->assertSame('/dashboard', $_SESSION['url.intended'] ?? null);
        $this->assertSame('Please sign in to continue.', $_SESSION['_flash']['error'] ?? null);
    }

    public function testAdminMiddlewareBlocksAuthenticatedNonAdminUsers(): void
    {
        $_SESSION['auth.user'] = [
            'id' => 10,
            'name' => 'Reader One',
            'email' => 'reader@example.com',
            'role' => 'user',
        ];

        $middleware = new AdminMiddleware();

        $this->expectRedirect(
            static function () use ($middleware): void {
                $middleware->handle('GET', '/admin');
            },
            '/dashboard'
        );

        $this->assertSame(
            'You do not have permission to access that page.',
            $_SESSION['_flash']['error'] ?? null
        );
    }

    public function testLoginControllerAuthenticatesAndRedirectsToIntendedPath(): void
    {
        $passwordHash = password_hash('StrongPass1', PASSWORD_DEFAULT);
        $statement = $this->connection->prepare(
            'INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)'
        );
        $statement->execute([
            'name' => 'Reader One',
            'email' => 'reader@example.com',
            'password' => $passwordHash,
            'role' => 'user',
        ]);

        $_SESSION['url.intended'] = '/loans/my';
        $_POST = [
            '_token' => 'known-token',
            'email' => 'reader@example.com',
            'password' => 'StrongPass1',
        ];

        $this->expectRedirect(
            function (): void {
                $this->controller->authenticate();
            },
            '/loans/my'
        );

        $this->assertSame('Reader One', $_SESSION['auth.user']['name'] ?? null);
    }

    public function testLogoutControllerClearsSessionAndRedirectsHome(): void
    {
        $_SESSION['auth.user'] = [
            'id' => 1,
            'name' => 'Reader One',
            'email' => 'reader@example.com',
            'role' => 'user',
        ];
        $_POST = ['_token' => 'known-token'];

        $this->expectRedirect(
            function (): void {
                $this->controller->logout();
            },
            '/'
        );

        $this->assertFalse(isset($_SESSION['auth.user']));
        $this->assertSame('You have been signed out.', $_SESSION['_flash']['status'] ?? null);
    }
}
