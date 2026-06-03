<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

class Book
{
    private ?PDO $connection;

    public function __construct(?PDO $connection = null)
    {
        $this->connection = $connection ?? Database::connection();
    }

        public function getCatalogBooks(array $filters = []): array
    {
        if (! $this->connection instanceof PDO) {
            return [];
        }

        $where = [];
        $params = [];

        $search = trim((string) ($filters['search'] ?? ''));
        if ($search !== '') {
            $where[] = '(b.title LIKE :search OR b.author LIKE :search OR COALESCE(b.description, \'\') LIKE :search)';
            $params['search'] = '%' . $search . '%';
        }

        $categoryId = $filters['category_id'] ?? null;
        if (is_int($categoryId) && $categoryId > 0) {
            $where[] = 'b.category_id = :category_id';
            $params['category_id'] = $categoryId;
        }

        $availability = (string) ($filters['availability'] ?? 'all');
        if ($availability === 'available') {
            $where[] = 'b.stock > 0';
        } elseif ($availability === 'out_of_stock') {
            $where[] = 'b.stock <= 0';
        }

        $whereSql = $where === [] ? '' : 'WHERE ' . implode(' AND ', $where);

        $orderBy = match ((string) ($filters['sort'] ?? 'latest')) {
            'title_asc' => 'b.title ASC, b.author ASC',
            'title_desc' => 'b.title DESC, b.author DESC',
            'author_asc' => 'b.author ASC, b.title ASC',
            'stock_desc' => 'b.stock DESC, b.title ASC',
            default => 'b.id DESC',
        };

        $stmt = $this->connection->prepare(
            "SELECT
                b.id,
                b.title,
                b.author,
                                b.stock,
                b.cover,
                COALESCE(b.description, '') AS description,
                c.id AS category_id,
                c.name AS category_name
            FROM books b
            INNER JOIN categories c ON c.id = b.category_id
            {$whereSql}
            ORDER BY {$orderBy}"
        );
        $stmt->execute($params);

        return $stmt->fetchAll() ?: [];
    }

        public function getAll(): array
    {
        if (! $this->connection instanceof PDO) {
            return [];
        }

        $stmt = $this->connection->query(
            "SELECT
                b.id,
                b.title,
                b.author,
                                b.stock,
                b.cover,
                COALESCE(b.description, '') AS description,
                b.category_id,
                COALESCE(c.name, 'Uncategorized') AS category_name
            FROM books b
            LEFT JOIN categories c ON c.id = b.category_id
            ORDER BY b.id DESC"
        );

        return $stmt->fetchAll() ?: [];
    }

        public function getCatalogSummary(): array
    {
        if (! $this->connection instanceof PDO) {
            return [
                'total_titles' => 0,
                'available_titles' => 0,
                'out_of_stock_titles' => 0,
                'total_copies' => 0,
            ];
        }

        $stmt = $this->connection->query(
            'SELECT
                COUNT(*) AS total_titles,
                SUM(CASE WHEN stock > 0 THEN 1 ELSE 0 END) AS available_titles,
                SUM(CASE WHEN stock <= 0 THEN 1 ELSE 0 END) AS out_of_stock_titles,
                COALESCE(SUM(stock), 0) AS total_copies
            FROM books'
        );

        $result = $stmt ? $stmt->fetch() : null;

        if (! is_array($result)) {
            return [
                'total_titles' => 0,
                'available_titles' => 0,
                'out_of_stock_titles' => 0,
                'total_copies' => 0,
            ];
        }

        return [
            'total_titles' => (int) ($result['total_titles'] ?? 0),
            'available_titles' => (int) ($result['available_titles'] ?? 0),
            'out_of_stock_titles' => (int) ($result['out_of_stock_titles'] ?? 0),
            'total_copies' => (int) ($result['total_copies'] ?? 0),
        ];
    }

        public function findById(int $id): ?array
    {
        if (! $this->connection instanceof PDO) {
            return null;
        }

        $stmt = $this->connection->prepare(
            'SELECT
                b.id,
                b.title,
                b.author,
                                b.stock,
                b.cover,
                COALESCE(b.description, \'\') AS description,
                b.category_id,
                COALESCE(c.name, \'Uncategorized\') AS category_name
            FROM books b
            LEFT JOIN categories c ON c.id = b.category_id
            WHERE b.id = :id
            LIMIT 1'
        );
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch();

        return is_array($result) ? $result : null;
    }

        public function findRelatedByCategory(
        int $categoryId,
        int $excludeId,
        int $limit = 3
    ): array {
        if (! $this->connection instanceof PDO) {
            return [];
        }

        $limit = max(1, min($limit, 8));

        $stmt = $this->connection->prepare(
            "SELECT
                b.id,
                b.title,
                b.author,
                                b.stock,
                b.cover,
                COALESCE(b.description, '') AS description,
                c.id AS category_id,
                c.name AS category_name
            FROM books b
            INNER JOIN categories c ON c.id = b.category_id
            WHERE b.category_id = :category_id
                AND b.id <> :exclude_id
            ORDER BY b.id DESC
            LIMIT {$limit}"
        );
        $stmt->execute([
            'category_id' => $categoryId,
            'exclude_id' => $excludeId,
        ]);

        return $stmt->fetchAll() ?: [];
    }

    // ============================================================
    //  ADMIN: CRUD Operations
    // ============================================================

        public function create(
        string $title,
        string $author,
        int $categoryId,
        string $description,
        int $stock
    ): int|false {
        if (! $this->connection instanceof PDO) {
            return false;
        }

        $stmt = $this->connection->prepare(
            'INSERT INTO books (title, author, category_id, description, stock)
             VALUES (:title, :author, :category_id, :description, :stock)'
        );

        $stmt->execute([
            'title' => $title,
            'author' => $author,
            'category_id' => $categoryId,
            'description' => $description !== '' ? $description : null,
            'stock' => $stock,
        ]);

        return (int) $this->connection->lastInsertId();
    }

        public function update(
        int $id,
        string $title,
        string $author,
        int $categoryId,
        string $description,
        int $stock
    ): bool {
        if (! $this->connection instanceof PDO) {
            return false;
        }

        $stmt = $this->connection->prepare(
            'UPDATE books
             SET title = :title,
                 author = :author,
                 category_id = :category_id,
                 description = :description,
                 stock = :stock
             WHERE id = :id'
        );

        $stmt->execute([
            'id' => $id,
            'title' => $title,
            'author' => $author,
            'category_id' => $categoryId,
            'description' => $description !== '' ? $description : null,
            'stock' => $stock,
        ]);

        return $stmt->rowCount() > 0;
    }

        public function delete(int $id): bool
    {
        if (! $this->connection instanceof PDO) {
            return false;
        }

        $stmt = $this->connection->prepare('DELETE FROM books WHERE id = :id');
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }

            // ============================================================
    //  COVER OPERATIONS
    // ============================================================

        public function updateCover(int $id, ?string $cover): bool
    {
        if (! $this->connection instanceof PDO) {
            return false;
        }

        $stmt = $this->connection->prepare(
            'UPDATE books SET cover = :cover WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'cover' => $cover,
        ]);

        return $stmt->rowCount() > 0;
    }

        public function getCover(int $id): ?string
    {
        if (! $this->connection instanceof PDO) {
            return null;
        }

        $stmt = $this->connection->prepare(
            'SELECT cover FROM books WHERE id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch();

        $cover = $result['cover'] ?? null;

        return is_string($cover) && $cover !== '' ? $cover : null;
    }

        public function countByCategory(int $categoryId): int
    {
        if (! $this->connection instanceof PDO) {
            return 0;
        }

        $stmt = $this->connection->prepare(
            'SELECT COUNT(*) AS cnt FROM books WHERE category_id = :category_id'
        );
        $stmt->execute(['category_id' => $categoryId]);

        $result = $stmt->fetch();

        return (int) ($result['cnt'] ?? 0);
    }
}
