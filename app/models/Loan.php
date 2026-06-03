<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

class Loan
{
    public function __construct(private ?PDO $connection = null)
    {
        $this->connection ??= Database::connection();
    }

    public function countActiveByUser(int $userId): int
    {
        if (! $this->connection instanceof PDO) {
            return 0;
        }

        $statement = $this->connection->prepare(
            "SELECT COUNT(*)
            FROM loans
            WHERE user_id = :user_id
                AND status IN ('pending', 'approved', 'late')"
        );
        $statement->execute(['user_id' => $userId]);

        return (int) $statement->fetchColumn();
    }

    public function getPendingLoans(): array
    {
        if (! $this->connection instanceof PDO) {
            return [];
        }

        $statement = $this->connection->query(
            "SELECT
                l.id,
                l.user_id,
                u.name AS user_name,
                u.email AS user_email,
                l.loan_date,
                l.due_date,
                l.status,
                l.created_at
            FROM loans l
            INNER JOIN users u ON u.id = l.user_id
            WHERE l.status = 'pending'
            ORDER BY l.created_at DESC
            LIMIT 10"
        );

        return $statement ? ($statement->fetchAll() ?: []) : [];
    }

    public function countActive(): int
    {
        if (! $this->connection instanceof PDO) {
            return 0;
        }

        $statement = $this->connection->query(
            "SELECT COUNT(*) FROM loans WHERE status IN ('approved', 'pending')"
        );

        return $statement ? (int) $statement->fetchColumn() : 0;
    }

    public function countLate(): int
    {
        if (! $this->connection instanceof PDO) {
            return 0;
        }

        $statement = $this->connection->query(
            "SELECT COUNT(*) FROM loans WHERE status = 'late'"
        );

        return $statement ? (int) $statement->fetchColumn() : 0;
    }
}
