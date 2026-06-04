<?php

declare(strict_types=1);

use App\Models\Loan;
use App\Services\LoanService;

class FakeLoanModel extends Loan
{
    public function __construct(private array $items)
    {
    }

    public function getLoanItemsByUser(int $userId): array
    {
        return array_values(array_filter(
            $this->items,
            static fn (array $item): bool => (int) ($item['user_id'] ?? 0) === $userId
        ));
    }
}

class LoanServiceTest extends TestCase
{
    public function testBuildsUserSpecificLoanDashboardData(): void
    {
        $service = new LoanService(
            new FakeLoanModel([
                $this->loanItem(1, 'Clean Code', 'Robert C. Martin', '2026-06-01', '2026-06-20', 'approved', null, 2),
                $this->loanItem(1, 'Atomic Habits', 'James Clear', '2026-06-02', '2026-06-10', 'approved', null, 1),
                $this->loanItem(1, 'The Hobbit', 'J.R.R. Tolkien', '2026-05-12', '2026-06-01', 'approved', null, 1),
                $this->loanItem(1, 'Deep Work', 'Cal Newport', '2026-05-01', '2026-05-15', 'returned', '2026-05-10 10:00:00', 1),
                $this->loanItem(2, 'Other User Book', 'Hidden Author', '2026-06-01', '2026-06-08', 'approved', null, 1),
            ]),
            new DateTimeImmutable('2026-06-04')
        );

        $data = $service->getMyLoansPageData(['id' => 1], []);

        $this->assertSame(3, count($data['rows']));
        $this->assertSame(4, $data['summaryCards'][0]['value']);
        $this->assertSame(1, $data['summaryCards'][1]['value']);
        $this->assertSame(1, $data['summaryCards'][2]['value']);
        $this->assertSame(1, $data['summaryCards'][3]['value']);

        $statuses = array_column(array_column($data['rows'], 'status'), 'label');
        $this->assertTrue(in_array('On Time', $statuses, true));
        $this->assertTrue(in_array('Due Soon', $statuses, true));
        $this->assertTrue(in_array('Overdue', $statuses, true));
    }

    public function testSearchAndReturnedTabUseRealReturnedRows(): void
    {
        $service = new LoanService(
            new FakeLoanModel([
                $this->loanItem(1, 'Deep Work', 'Cal Newport', '2026-05-01', '2026-05-15', 'returned', '2026-05-10 10:00:00', 1),
                $this->loanItem(1, 'Clean Code', 'Robert C. Martin', '2026-06-01', '2026-06-20', 'approved', null, 1),
            ]),
            new DateTimeImmutable('2026-06-04')
        );

        $data = $service->getMyLoansPageData(['id' => 1], [
            'tab' => 'returns',
            'search' => 'deep',
            'filter' => 'returned',
        ]);

        $this->assertSame(1, count($data['rows']));
        $this->assertSame('Deep Work', $data['rows'][0]['book_title']);
        $this->assertSame('Returned', $data['rows'][0]['status']['label']);
    }

    private function loanItem(
        int $userId,
        string $title,
        string $author,
        string $loanDate,
        string $dueDate,
        string $status,
        ?string $returnedAt,
        int $quantity
    ): array {
        static $id = 1;

        return [
            'loan_id' => $id,
            'user_id' => $userId,
            'loan_date' => $loanDate,
            'due_date' => $dueDate,
            'status' => $status,
            'returned_at' => $returnedAt,
            'fine' => 0,
            'created_at' => $loanDate . ' 08:00:00',
            'loan_detail_id' => $id,
            'quantity' => $quantity,
            'book_id' => $id++,
            'book_title' => $title,
            'book_author' => $author,
            'book_cover' => null,
        ];
    }
}
