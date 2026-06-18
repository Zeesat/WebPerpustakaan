<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

class Category
{
    public function __construct(private ?PDO $connection = null)
    {
        $this->connection ??= Database::connection();
    }

    public function getAll(): array
    {
        if (! $this->connection instanceof PDO) {
            return [];
        }

        $statement = $this->connection->query(
            'SELECT id, name FROM categories ORDER BY id ASC'
        );

        return $statement ? ($statement->fetchAll() ?: []) : [];
    }

    public function getAllWithBookCounts(): array
    {
        if (! $this->connection instanceof PDO) {
            return [];
        }

        $statement = $this->connection->query(
            'SELECT
                v.id,
                v.name,
                v.book_count,
                v.stock_total
            FROM vw_category_summary v
            ORDER BY v.name ASC'
        );

        return $statement ? ($statement->fetchAll() ?: []) : [];
    }

    public function findById(int $id): ?array
    {
        if (! $this->connection instanceof PDO) {
            return null;
        }

        $statement = $this->connection->prepare(
            'SELECT id, name FROM categories WHERE id = :id LIMIT 1'
        );
        $statement->execute(['id' => $id]);

        $row = $statement->fetch();

        return is_array($row) ? $row : null;
    }

    public function create(string $name): int|false
    {
        if (! $this->connection instanceof PDO) {
            return false;
        }

        $statement = $this->connection->prepare(
            'INSERT INTO categories (name) VALUES (:name)'
        );
        $statement->execute(['name' => $name]);

        return (int) $this->connection->lastInsertId();
    }

    public function update(int $id, string $name): bool
    {
        if (! $this->connection instanceof PDO) {
            return false;
        }

        $statement = $this->connection->prepare(
            'UPDATE categories SET name = :name WHERE id = :id'
        );
        $statement->execute(['id' => $id, 'name' => $name]);

        return $statement->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        if (! $this->connection instanceof PDO) {
            return false;
        }

        $statement = $this->connection->prepare('DELETE FROM categories WHERE id = :id');
        $statement->execute(['id' => $id]);

        return $statement->rowCount() > 0;
    }

    /**
     * Check if a category name already exists, optionally excluding a specific id.
     */
    public function nameExists(string $name, ?int $excludeId = null): bool
    {
        if (! $this->connection instanceof PDO) {
            return false;
        }

        $sql = 'SELECT COUNT(*) AS cnt FROM categories WHERE name = :name';
        $params = ['name' => $name];

        if ($excludeId !== null) {
            $sql .= ' AND id <> :exclude_id';
            $params['exclude_id'] = $excludeId;
        }

        $statement = $this->connection->prepare($sql);
        $statement->execute($params);

        $row = $statement->fetch();

        return ($row['cnt'] ?? 0) > 0;
    }
}
