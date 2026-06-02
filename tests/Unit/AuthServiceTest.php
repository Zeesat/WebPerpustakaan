<?php

declare(strict_types=1);

use App\Models\User;
use App\Services\AuthService;
final class AuthServiceTest extends TestCase
{
    private PDO $connection;
    private AuthService $service;

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

        $this->service = new AuthService(new User($this->connection));
    }

    public function testRegisterCreatesHashedPasswordAndSessionUser(): void
    {
        $result = $this->service->register([
            'name' => 'Reader One',
            'email' => 'reader@example.com',
            'password' => 'StrongPass1',
            'password_confirmation' => 'StrongPass1',
        ]);

        $this->assertTrue($result['success']);
        $this->assertSame('Reader One', $_SESSION['auth.user']['name'] ?? null);

        $storedPassword = (string) $this->connection
            ->query("SELECT password FROM users WHERE email = 'reader@example.com'")
            ->fetchColumn();

        $this->assertTrue($storedPassword !== 'StrongPass1', 'Password should not be stored in plaintext.');
        $this->assertTrue(password_verify('StrongPass1', $storedPassword));
    }

    public function testRegisterRejectsDuplicateEmail(): void
    {
        $this->service->register([
            'name' => 'Reader One',
            'email' => 'reader@example.com',
            'password' => 'StrongPass1',
            'password_confirmation' => 'StrongPass1',
        ]);

        $result = $this->service->register([
            'name' => 'Reader Two',
            'email' => 'reader@example.com',
            'password' => 'StrongPass1',
            'password_confirmation' => 'StrongPass1',
        ]);

        $this->assertFalse($result['success']);
        $this->assertSame('That email is already registered.', $result['errors']['email'] ?? null);
    }

    public function testAttemptRejectsInvalidCredentialsWithGenericMessage(): void
    {
        $this->service->register([
            'name' => 'Reader One',
            'email' => 'reader@example.com',
            'password' => 'StrongPass1',
            'password_confirmation' => 'StrongPass1',
        ]);
        $_SESSION = [];

        $result = $this->service->attempt([
            'email' => 'reader@example.com',
            'password' => 'WrongPass9',
        ]);

        $this->assertFalse($result['success']);
        $this->assertSame('The provided credentials are invalid.', $result['errors']['general'] ?? null);
    }
}
