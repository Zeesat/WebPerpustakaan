<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

class LoanDetail
{
    public function __construct(private ?PDO $connection = null)
    {
        $this->connection ??= Database::connection();
    }

    public function create(int $loanId, int $bookId, int $quantity = 1): bool
    {
        if (! $this->connection instanceof PDO) {
            return false;
        }

        $statement = $this->connection->prepare(
            'INSERT INTO loan_details (loan_id, book_id, quantity)
             VALUES (:loan_id, :book_id, :quantity)'
        );

        return $statement->execute([
            'loan_id' => $loanId,
            'book_id' => $bookId,
            'quantity' => max(1, $quantity),
        ]);
    }
}

