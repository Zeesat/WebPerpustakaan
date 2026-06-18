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

        // Use stored function when available, fall back to inline query
        try {
            $statement = $this->connection->query(
                "SELECT fn_count_active_loans({$userId}) AS cnt"
            );
            $result = $statement ? $statement->fetch() : null;

            if (is_array($result) && isset($result['cnt'])) {
                return (int) $result['cnt'];
            }
        } catch (\PDOException $e) {
            // Function may not exist yet — fall back to inline query
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
            "SELECT DISTINCT
                v.loan_id       AS id,
                v.user_id,
                v.user_name,
                v.user_email,
                v.loan_date,
                v.due_date,
                v.status,
                v.created_at
            FROM vw_active_loans v
            WHERE v.status = 'pending'
            ORDER BY v.created_at DESC
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

    // ============================================================
    //  STORED PROCEDURE WRAPPERS
    // ============================================================

    /**
     * Approve a pending loan using the sp_approve_loan stored procedure.
     * Atomically validates stock, sets status = 'approved', and deducts stock.
     *
     * @return array{success: bool, message: string}
     */
    public function approve(int $loanId, int $adminId): array
    {
        if (! $this->connection instanceof PDO) {
            return ['success' => false, 'message' => 'Database connection unavailable.'];
        }

        try {
            $statement = $this->connection->prepare('CALL sp_approve_loan(:loan_id, :admin_id)');
            $statement->execute([
                'loan_id' => $loanId,
                'admin_id' => $adminId,
            ]);

            return ['success' => true, 'message' => 'Loan has been approved successfully.'];
        } catch (\PDOException $e) {
            $message = $e->getMessage();

            // Surface meaningful constraint messages
            if (str_contains($message, 'Insufficient stock')) {
                return ['success' => false, 'message' => 'Cannot approve: insufficient stock for one or more books.'];
            }

            error_log("Loan::approve({$loanId}): {$message}");

            return ['success' => false, 'message' => 'Approval failed. Please try again.'];
        }
    }

    /**
     * Process a loan return using the sp_process_loan_return stored procedure.
     * Atomically calculates fine, sets status = 'returned', and restores book stock.
     *
     * @return array{success: bool, message: string}
     */
    public function processReturn(int $loanId): array
    {
        if (! $this->connection instanceof PDO) {
            return ['success' => false, 'message' => 'Database connection unavailable.'];
        }

        try {
            $statement = $this->connection->prepare('CALL sp_process_loan_return(:loan_id)');
            $statement->execute(['loan_id' => $loanId]);

            // Fetch the calculated fine after the procedure ran
            $fineStmt = $this->connection->prepare(
                'SELECT fine FROM loans WHERE id = :loan_id'
            );
            $fineStmt->execute(['loan_id' => $loanId]);
            $fine = $fineStmt->fetchColumn();
            $fineAmount = is_numeric($fine) ? (float) $fine : 0;

            $message = 'Loan has been processed as returned.';
            if ($fineAmount > 0) {
                $message .= ' Late fine: Rp ' . number_format($fineAmount, 0, ',', '.');
            }

            return ['success' => true, 'message' => $message, 'fine' => $fineAmount];
        } catch (\PDOException $e) {
            $message = $e->getMessage();

            error_log("Loan::processReturn({$loanId}): {$message}");

            return ['success' => false, 'message' => 'Return processing failed. Please try again.'];
        }
    }
}
