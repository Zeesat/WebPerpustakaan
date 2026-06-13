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

    public function getLoanItemsByUser(int $userId): array
    {
        if (! $this->connection instanceof PDO) {
            return [];
        }

        $statement = $this->connection->prepare(
            "SELECT
                l.id AS loan_id,
                l.user_id,
                l.loan_date,
                l.due_date,
                l.status,
                l.returned_at,
                l.fine,
                l.created_at,
                ld.id AS loan_detail_id,
                ld.quantity,
                b.id AS book_id,
                b.title AS book_title,
                b.author AS book_author,
                b.cover AS book_cover
            FROM loans l
            INNER JOIN loan_details ld ON ld.loan_id = l.id
            INNER JOIN books b ON b.id = ld.book_id
            WHERE l.user_id = :user_id
                AND l.status <> 'rejected'
            ORDER BY l.loan_date DESC, l.id DESC, ld.id ASC"
        );
        $statement->execute(['user_id' => $userId]);

        return $statement->fetchAll() ?: [];
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

    public function createPending(int $userId, string $loanDate, string $dueDate): int|false
    {
        if (! $this->connection instanceof PDO) {
            return false;
        }

        $statement = $this->connection->prepare(
            'INSERT INTO loans (user_id, loan_date, due_date, status)
             VALUES (:user_id, :loan_date, :due_date, :status)'
        );

        $created = $statement->execute([
            'user_id' => $userId,
            'loan_date' => $loanDate,
            'due_date' => $dueDate,
            'status' => 'pending',
        ]);

        if (! $created) {
            return false;
        }

        return (int) $this->connection->lastInsertId();
    }
}
