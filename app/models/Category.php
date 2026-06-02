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

    public function getAllWithBookCounts(): array
    {
        if (! $this->connection instanceof PDO) {
            return [];
        }

        $statement = $this->connection->query(
            'SELECT
                c.id,
                c.name,
                COUNT(b.id) AS book_count,
                COALESCE(SUM(b.stock), 0) AS stock_total
            FROM categories c
            LEFT JOIN books b ON b.category_id = c.id
            GROUP BY c.id, c.name
            ORDER BY c.name ASC'
        );

        return $statement ? ($statement->fetchAll() ?: []) : [];
    }
}

