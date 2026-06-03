<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class User
{
    public function __construct(private ?PDO $connection = null)
    {
        $this->connection ??= Database::connection();
    }

    public function findByEmail(string $email): ?array
    {
        if (! $this->connection instanceof PDO) {
            return null;
        }

        $statement = $this->connection->prepare(
            'SELECT id, name, email, password, role, created_at FROM users WHERE email = :email LIMIT 1'
        );
        $statement->execute(['email' => $email]);

        $user = $statement->fetch();

        return is_array($user) ? $user : null;
    }

    public function findById(int $id): ?array
    {
        if (! $this->connection instanceof PDO) {
            return null;
        }

        $statement = $this->connection->prepare(
            'SELECT id, name, email, role, created_at FROM users WHERE id = :id LIMIT 1'
        );
        $statement->execute(['id' => $id]);

        $user = $statement->fetch();

        return is_array($user) ? $user : null;
    }

    public function create(array $attributes): ?int
    {
        if (! $this->connection instanceof PDO) {
            return null;
        }

        try {
            $statement = $this->connection->prepare(
                'INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)'
            );
            $statement->execute([
                'name' => $attributes['name'],
                'email' => $attributes['email'],
                'password' => $attributes['password'],
                'role' => $attributes['role'] ?? 'user',
            ]);
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return null;
        }

        $id = $this->connection->lastInsertId();

        return $id === false ? null : (int) $id;
    }

    public function countAll(): int
    {
        if (! $this->connection instanceof PDO) {
            return 0;
        }

        $statement = $this->connection->query('SELECT COUNT(*) FROM users');

        return $statement ? (int) $statement->fetchColumn() : 0;
    }

    public function getAll(): array
    {
        if (! $this->connection instanceof PDO) {
            return [];
        }

        $statement = $this->connection->query(
            'SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC'
        );

        return $statement ? ($statement->fetchAll() ?: []) : [];
    }
}

