<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

class Book
{
    public function __construct(private ?PDO  = null)
    {
        ->connection ??= Database::connection();
    }

    public function getCatalogBooks(array  = []): array
    {
        if (! ->connection instanceof PDO) {
            return [];
        }

        \ = [];
        \ = [];

        \ = trim((string) (\['search'] ?? ''));
        if (\ !== '') {
            \[] = '(b.title LIKE :search OR b.author LIKE :search OR COALESCE(b.description, \'\') LIKE :search)';
            \['search'] = '%' . \ . '%';
        }

        \ = \['category_id'] ?? null;
        if (is_int(\) && \ > 0) {
            \[] = 'b.category_id = :category_id';
            \['category_id'] = \;
        }

        \ = (string) (\['availability'] ?? 'all');
        if (\ === 'available') {
            \[] = 'b.stock > 0';
        } elseif (\ === 'out_of_stock') {
            \[] = 'b.stock <= 0';
        }

        \ = \ === [] ? '' : 'WHERE ' . implode(' AND ', \);

        \ = match ((string) (\['sort'] ?? 'latest')) {
            'title_asc' => 'b.title ASC, b.author ASC',
            'title_desc' => 'b.title DESC, b.author DESC',
            'author_asc' => 'b.author ASC, b.title ASC',
            'stock_desc' => 'b.stock DESC, b.title ASC',
            default => 'b.id DESC',
        };

        \ = ->connection->prepare(
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
            {\}
            ORDER BY {\}"
        );
        \->execute(\);

        return \->fetchAll() ?: [];
    }

    public function getAll(): array
    {
        if (! ->connection instanceof PDO) {
            return [];
        }

        \ = ->connection->query(
            "SELECT
                b.id,
                b.title,
                b.author,
                b.stock,
                COALESCE(b.description, '') AS description,
                b.category_id,
                COALESCE(c.name, 'Uncategorized') AS category_name
            FROM books b
            LEFT JOIN categories c ON c.id = b.category_id
            ORDER BY b.id DESC"
        );

        return \->fetchAll() ?: [];
    }

    public function getCatalogSummary(): array
    {
        if (! ->connection instanceof PDO) {
            return [
                'total_titles' => 0,
                'available_titles' => 0,
                'out_of_stock_titles' => 0,
                'total_copies' => 0,
            ];
        }

        \ = ->connection->query(
            'SELECT
                COUNT(*) AS total_titles,
                SUM(CASE WHEN stock > 0 THEN 1 ELSE 0 END) AS available_titles,
                SUM(CASE WHEN stock <= 0 THEN 1 ELSE 0 END) AS out_of_stock_titles,
                COALESCE(SUM(stock), 0) AS total_copies
            FROM books'
        );

        \ = \ ? \->fetch() : null;

        if (! is_array(\)) {
            return [
                'total_titles' => 0,
                'available_titles' => 0,
                'out_of_stock_titles' => 0,
                'total_copies' => 0,
            ];
        }

        return [
            'total_titles' => (int) (\['total_titles'] ?? 0),
            'available_titles' => (int) (\['available_titles'] ?? 0),
            'out_of_stock_titles' => (int) (\['out_of_stock_titles'] ?? 0),
            'total_copies' => (int) (\['total_copies'] ?? 0),
        ];
    }

    public function findById(int \): ?array
    {
        if (! ->connection instanceof PDO) {
            return null;
        }

        \ = ->connection->prepare(
            'SELECT
                b.id,
                b.title,
                b.author,
                b.stock,
                COALESCE(b.description, \'\') AS description,
                b.category_id,
                COALESCE(c.name, \'Uncategorized\') AS category_name
            FROM books b
            LEFT JOIN categories c ON c.id = b.category_id
            WHERE b.id = :id
            LIMIT 1'
        );
        \->execute(['id' => \]);

        \ = \->fetch();

        return is_array(\) ? \ : null;
    }

    public function findRelatedByCategory(int \, int \, int \ = 3): array
    {
        if (! ->connection instanceof PDO) {
            return [];
        }

        \ = max(1, min(\, 8));

        \ = ->connection->prepare(
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
            LIMIT {\}"
        );
        \->execute([
            'category_id' => \,
            'exclude_id' => \,
        ]);

        return \->fetchAll() ?: [];
    }

    // ============================================================
    //  ADMIN: CRUD Operations
    // ============================================================

    public function create(string \, string \, int \, string \, int \): int|false
    {
        if (! ->connection instanceof PDO) {
            return false;
        }

        \ = ->connection->prepare(
            'INSERT INTO books (title, author, category_id, description, stock)
             VALUES (:title, :author, :category_id, :description, :stock)'
        );

        \->execute([
            'title' => \,
            'author' => \,
            'category_id' => \,
            'description' => \ !== '' ? \ : null,
            'stock' => \,
        ]);

        return (int) ->connection->lastInsertId();
    }

    public function update(int \, string \, string \, int \, string \, int \): bool
    {
        if (! ->connection instanceof PDO) {
            return false;
        }

        \ = ->connection->prepare(
            'UPDATE books
             SET title = :title,
                 author = :author,
                 category_id = :category_id,
                 description = :description,
                 stock = :stock
             WHERE id = :id'
        );

        \->execute([
            'id' => \,
            'title' => \,
            'author' => \,
            'category_id' => \,
            'description' => \ !== '' ? \ : null,
            'stock' => \,
        ]);

        return \->rowCount() > 0;
    }

    public function delete(int \): bool
    {
        if (! ->connection instanceof PDO) {
            return false;
        }

        \ = ->connection->prepare('DELETE FROM books WHERE id = :id');
        \->execute(['id' => \]);

        return \->rowCount() > 0;
    }

    public function countByCategory(int \): int
    {
        if (! ->connection instanceof PDO) {
            return 0;
        }

        \ = ->connection->prepare(
            'SELECT COUNT(*) AS cnt FROM books WHERE category_id = :category_id'
        );
        \->execute(['category_id' => \]);

        \ = \->fetch();

        return (int) (\['cnt'] ?? 0);
    }
}

// test
