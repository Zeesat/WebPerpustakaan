<?php

declare(strict_types=1);

use App\Models\User;
final class UserModelTest extends TestCase
{
    private PDO $connection;

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
    }

    public function testCreateAndFindByEmailPersistUser(): void
    {
        $userModel = new User($this->connection);
        $userId = $userModel->create([
            'name' => 'Reader One',
            'email' => 'reader@example.com',
            'password' => password_hash('StrongPass1', PASSWORD_DEFAULT),
            'role' => 'user',
        ]);

        $this->assertNotNull($userId);

        $storedUser = $userModel->findByEmail('reader@example.com');

        $this->assertSame('Reader One', $storedUser['name'] ?? null);
        $this->assertSame('user', $storedUser['role'] ?? null);
        $this->assertTrue(password_verify('StrongPass1', (string) ($storedUser['password'] ?? '')));
    }

    public function testFindByIdExcludesPasswordField(): void
    {
        $userModel = new User($this->connection);
        $userId = $userModel->create([
            'name' => 'Admin One',
            'email' => 'admin@example.com',
            'password' => password_hash('StrongPass1', PASSWORD_DEFAULT),
            'role' => 'admin',
        ]);

        $user = $userModel->findById((int) $userId);

        $this->assertSame('Admin One', $user['name'] ?? null);
        $this->assertFalse(array_key_exists('password', $user ?? []));
    }
}
