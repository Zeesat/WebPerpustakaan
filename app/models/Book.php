<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

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
            $where[] = '(v.title LIKE :search OR v.author LIKE :search OR COALESCE(v.description, \'\') LIKE :search)';
            $params['search'] = '%' . $search . '%';
        }

        $categoryId = $filters['category_id'] ?? null;
        if (is_int($categoryId) && $categoryId > 0) {
            $where[] = 'v.category_id = :category_id';
            $params['category_id'] = $categoryId;
        }

        $availability = (string) ($filters['availability'] ?? 'all');
        if ($availability === 'available') {
            $where[] = 'v.stock > 0';
        } elseif ($availability === 'out_of_stock') {
            $where[] = 'v.stock <= 0';
        }

        $whereSql = $where === [] ? '' : 'WHERE ' . implode(' AND ', $where);

        $orderBy = match ((string) ($filters['sort'] ?? 'latest')) {
            'title_asc' => 'v.title ASC, v.author ASC',
            'title_desc' => 'v.title DESC, v.author DESC',
            'author_asc' => 'v.author ASC, v.title ASC',
            'stock_desc' => 'v.stock DESC, v.title ASC',
            default => 'v.id DESC',
        };

        $stmt = $this->connection->prepare(
            "SELECT
                v.id,
                v.title,
                v.author,
                v.stock,
                v.cover,
                v.description,
                v.category_id,
                v.category_name
            FROM vw_book_catalog v
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
            'SELECT
                v.id,
                v.title,
                v.author,
                v.stock,
                v.cover,
                v.description,
                v.category_id,
                v.category_name
            FROM vw_book_catalog v
            ORDER BY v.id DESC'
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
                v.id,
                v.title,
                v.author,
                v.stock,
                v.cover,
                v.description,
                v.category_id,
                v.category_name
            FROM vw_book_catalog v
            WHERE v.id = :id
            LIMIT 1'
        );
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch();

        return is_array($result) ? $result : null;
    }

    public function findByIds(array $ids): array
    {
        if (! $this->connection instanceof PDO) {
            return [];
        }

        $normalizedIds = array_values(array_unique(array_filter(
            array_map(
                static fn (mixed $value): int => (int) $value,
                $ids
            ),
            static fn (int $value): bool => $value > 0
        )));

        if ($normalizedIds === []) {
            return [];
        }

        $placeholders = [];
        $params = [];

        foreach ($normalizedIds as $index => $id) {
            $placeholder = ':id_' . $index;
            $placeholders[] = $placeholder;
            $params['id_' . $index] = $id;
        }

        $statement = $this->connection->prepare(
            'SELECT
                v.id,
                v.title,
                v.author,
                v.stock,
                v.cover,
                v.description,
                v.category_id,
                v.category_name
            FROM vw_book_catalog v
            WHERE v.id IN (' . implode(', ', $placeholders) . ')'
        );
        $statement->execute($params);

        return $statement->fetchAll() ?: [];
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
                v.id,
                v.title,
                v.author,
                v.stock,
                v.cover,
                v.description,
                v.category_id,
                v.category_name
            FROM vw_book_catalog v
            WHERE v.category_id = :category_id
                AND v.id <> :exclude_id
            ORDER BY v.id DESC
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

        try {
            $stmt = $this->connection->prepare(
                'INSERT INTO books (title, author, category_id, description, stock)
                 VALUES (:title, :author, :category_id, :description, :stock)'
            );

            $created = $stmt->execute([
                'title' => $title,
                'author' => $author,
                'category_id' => max(0, $categoryId),
                'description' => $description !== '' ? $description : null,
                'stock' => max(0, $stock),
            ]);

            if (! $created) {
                return false;
            }

            return (int) $this->connection->lastInsertId();
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return false;
        }
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

        try {
            $stmt = $this->connection->prepare(
                'UPDATE books
                 SET title = :title,
                     author = :author,
                     category_id = :category_id,
                     description = :description,
                     stock = :stock
                 WHERE id = :id'
            );

            return $stmt->execute([
                'id' => $id,
                'title' => $title,
                'author' => $author,
                'category_id' => max(0, $categoryId),
                'description' => $description !== '' ? $description : null,
                'stock' => max(0, $stock),
            ]);
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return false;
        }
    }

        public function delete(int $id): bool
    {
        if (! $this->connection instanceof PDO) {
            return false;
        }

        try {
            $stmt = $this->connection->prepare('DELETE FROM books WHERE id = :id');
            $stmt->execute(['id' => $id]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return false;
        }
    }

            // ============================================================
    //  COVER OPERATIONS
    // ============================================================

        public function updateCover(int $id, ?string $cover): bool
    {
        if (! $this->connection instanceof PDO) {
            return false;
        }

        try {
            $stmt = $this->connection->prepare(
                'UPDATE books SET cover = :cover WHERE id = :id'
            );

            return $stmt->execute([
                'id' => $id,
                'cover' => $cover,
            ]);
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            return false;
        }
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
