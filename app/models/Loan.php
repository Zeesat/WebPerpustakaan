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
}

