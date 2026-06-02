<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

class Book
{
    public function __construct(private ?PDO $connection = null)
    {
        $this->connection ??= Database::connection();
    }

    public function getCatalogBooks(array $filters = []): array
    {
        if (! $this->connection instanceof PDO) {
            return [];
        }

        $conditions = [];
        $bindings = [];

        $search = trim((string) ($filters['search'] ?? ''));
        if ($search !== '') {
            $conditions[] = '(b.title LIKE :search OR b.author LIKE :search OR COALESCE(b.description, \'\') LIKE :search)';
            $bindings['search'] = '%' . $search . '%';
        }

        $categoryId = $filters['category_id'] ?? null;
        if (is_int($categoryId) && $categoryId > 0) {
            $conditions[] = 'b.category_id = :category_id';
            $bindings['category_id'] = $categoryId;
        }

        $availability = (string) ($filters['availability'] ?? 'all');
        if ($availability === 'available') {
            $conditions[] = 'b.stock > 0';
        } elseif ($availability === 'out_of_stock') {
            $conditions[] = 'b.stock <= 0';
        }

        $whereClause = $conditions === [] ? '' : 'WHERE ' . implode(' AND ', $conditions);

        $sortSql = match ((string) ($filters['sort'] ?? 'latest')) {
            'title_asc' => 'b.title ASC, b.author ASC',
            'title_desc' => 'b.title DESC, b.author DESC',
            'author_asc' => 'b.author ASC, b.title ASC',
            'stock_desc' => 'b.stock DESC, b.title ASC',
            default => 'b.id DESC',
        };

        $statement = $this->connection->prepare(
            "SELECT
                b.id,
                b.title,
                b.author,
                b.stock,
                COALESCE(b.description, '') AS description,
                c.id AS category_id,
                c.name AS category_name
            FROM books b
            INNER JOIN categories c ON c.id = b.category_id
            {$whereClause}
            ORDER BY {$sortSql}"
        );
        $statement->execute($bindings);

        return $statement->fetchAll() ?: [];
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

        $statement = $this->connection->query(
            'SELECT
                COUNT(*) AS total_titles,
                SUM(CASE WHEN stock > 0 THEN 1 ELSE 0 END) AS available_titles,
                SUM(CASE WHEN stock <= 0 THEN 1 ELSE 0 END) AS out_of_stock_titles,
                COALESCE(SUM(stock), 0) AS total_copies
            FROM books'
        );

        $summary = $statement ? $statement->fetch() : null;

        if (! is_array($summary)) {
            return [
                'total_titles' => 0,
                'available_titles' => 0,
                'out_of_stock_titles' => 0,
                'total_copies' => 0,
            ];
        }

        return [
            'total_titles' => (int) ($summary['total_titles'] ?? 0),
            'available_titles' => (int) ($summary['available_titles'] ?? 0),
            'out_of_stock_titles' => (int) ($summary['out_of_stock_titles'] ?? 0),
            'total_copies' => (int) ($summary['total_copies'] ?? 0),
        ];
    }

    public function findById(int $id): ?array
    {
        if (! $this->connection instanceof PDO) {
            return null;
        }

        $statement = $this->connection->prepare(
            'SELECT
                b.id,
                b.title,
                b.author,
                b.stock,
                COALESCE(b.description, \'\') AS description,
                c.id AS category_id,
                c.name AS category_name
            FROM books b
            INNER JOIN categories c ON c.id = b.category_id
            WHERE b.id = :id
            LIMIT 1'
        );
        $statement->execute(['id' => $id]);

        $book = $statement->fetch();

        return is_array($book) ? $book : null;
    }

    public function findRelatedByCategory(int $categoryId, int $excludeId, int $limit = 3): array
    {
        if (! $this->connection instanceof PDO) {
            return [];
        }

        $limit = max(1, min($limit, 8));

        $statement = $this->connection->prepare(
            "SELECT
                b.id,
                b.title,
                b.author,
                b.stock,
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
        $statement->execute([
            'category_id' => $categoryId,
            'exclude_id' => $excludeId,
        ]);

        return $statement->fetchAll() ?: [];
    }
}

