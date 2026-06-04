<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Loan;
use DateTimeImmutable;

class LoanService
{
    private const TAB_OPTIONS = [
        'current' => 'Current Loans',
        'history' => 'Loan History',
        'returns' => 'Return History',
    ];

    private const FILTER_OPTIONS = [
        'all' => 'All',
        'active' => 'Active',
        'due_soon' => 'Due Soon',
        'overdue' => 'Overdue',
        'returned' => 'Returned',
    ];

    private const SORT_OPTIONS = [
        'latest' => 'Latest Borrowed',
        'oldest' => 'Oldest Borrowed',
        'nearest_due' => 'Nearest Due Date',
        'most_overdue' => 'Most Overdue',
    ];

    private Loan $loans;
    private DateTimeImmutable $today;

    public function __construct(?Loan $loans = null, ?DateTimeImmutable $today = null)
    {
        $this->loans = $loans ?? new Loan();
        $this->today = $today ?? new DateTimeImmutable('today');
    }

    public function getMyLoansPageData(?array $sessionUser, array $input = []): array
    {
        $filters = $this->normalizeInput($input);
        $userId = filter_var($sessionUser['id'] ?? null, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        $rows = $userId === false
            ? []
            : array_values(array_filter(array_map(
                [$this, 'presentLoanItem'],
                $this->loans->getLoanItemsByUser((int) $userId)
            )));

        $currentRows = array_values(array_filter($rows, static fn (array $row): bool => ! $row['is_returned']));
        $returnedRows = array_values(array_filter($rows, static fn (array $row): bool => $row['is_returned']));

        $tabRows = match ($filters['tab']) {
            'history', 'returns' => $returnedRows,
            default => $currentRows,
        };

        $visibleRows = $this->sortRows(
            $this->filterRows($tabRows, $filters),
            $filters['sort']
        );

        return [
            'title' => 'My Loans',
            'mainClass' => '',
            'bodyClass' => '',
            'filters' => $filters,
            'tabs' => $this->mapOptions(self::TAB_OPTIONS, $filters['tab'], [
                'current' => count($currentRows),
                'history' => count($returnedRows),
                'returns' => count($returnedRows),
            ]),
            'filterOptions' => $this->mapOptions(self::FILTER_OPTIONS, $filters['filter']),
            'sortOptions' => $this->mapOptions(self::SORT_OPTIONS, $filters['sort']),
            'summaryCards' => $this->buildSummaryCards($rows),
            'rows' => $visibleRows,
            'activeTab' => $filters['tab'],
            'resultSummary' => $this->buildResultSummary(count($visibleRows), count($tabRows)),
            'hasAnyLoans' => $rows !== [],
            'hasActiveLoans' => $currentRows !== [],
            'hasActiveFilters' => $filters['search'] !== '' || $filters['filter'] !== 'all' || $filters['sort'] !== 'latest',
        ];
    }

    private function normalizeInput(array $input): array
    {
        $tab = (string) ($input['tab'] ?? 'current');
        if (! array_key_exists($tab, self::TAB_OPTIONS)) {
            $tab = 'current';
        }

        $search = mb_substr(trim((string) ($input['search'] ?? '')), 0, 100);

        $filter = (string) ($input['filter'] ?? 'all');
        if (! array_key_exists($filter, self::FILTER_OPTIONS)) {
            $filter = 'all';
        }

        $sort = (string) ($input['sort'] ?? 'latest');
        if (! array_key_exists($sort, self::SORT_OPTIONS)) {
            $sort = 'latest';
        }

        return [
            'tab' => $tab,
            'search' => $search,
            'filter' => $filter,
            'sort' => $sort,
        ];
    }

    private function presentLoanItem(array $row): ?array
    {
        $loanDate = $this->parseDate($row['loan_date'] ?? null);
        $dueDate = $this->parseDate($row['due_date'] ?? null);

        if ($loanDate === null || $dueDate === null) {
            return null;
        }

        $returnDate = $this->parseDate($row['returned_at'] ?? null);
        $databaseStatus = strtolower((string) ($row['status'] ?? ''));
        $isReturned = $returnDate !== null || $databaseStatus === 'returned';
        $daysUntilDue = (int) $this->today->diff($dueDate)->format('%r%a');
        $isOverdue = ! $isReturned && $daysUntilDue < 0;
        $isDueSoon = ! $isReturned && ! $isOverdue && $daysUntilDue <= 7;
        $status = $this->presentStatus($isReturned, $isOverdue, $isDueSoon);
        $bookTitle = (string) ($row['book_title'] ?? 'Untitled Book');

        return [
            'loan_id' => (int) ($row['loan_id'] ?? 0),
            'loan_detail_id' => (int) ($row['loan_detail_id'] ?? 0),
            'book_id' => (int) ($row['book_id'] ?? 0),
            'book_title' => $bookTitle,
            'book_author' => (string) ($row['book_author'] ?? 'Unknown Author'),
            'quantity' => max(1, (int) ($row['quantity'] ?? 1)),
            'cover' => [
                'url' => cover_url($this->nullableString($row['book_cover'] ?? null)),
                'initials' => $this->buildInitials($bookTitle),
            ],
            'borrowed_date' => $this->formatDate($loanDate),
            'due_date' => $this->formatDate($dueDate),
            'return_date' => $returnDate !== null ? $this->formatDate($returnDate) : ($isReturned ? 'Not recorded' : 'Not returned'),
            'loan_date_iso' => $loanDate->format('Y-m-d'),
            'due_date_iso' => $dueDate->format('Y-m-d'),
            'return_date_iso' => $returnDate?->format('Y-m-d'),
            'status' => $status,
            'database_status' => $databaseStatus,
            'is_returned' => $isReturned,
            'is_due_soon' => $isDueSoon,
            'is_overdue' => $isOverdue,
            'due_hint' => $this->buildDueHint($isReturned, $daysUntilDue, $returnDate),
            'loan_duration' => $returnDate !== null ? $this->buildDuration($loanDate, $returnDate) : ($isReturned ? 'Completed' : 'In progress'),
            'details_url' => url('/books/show?id=' . (int) ($row['book_id'] ?? 0)),
            'is_renewable' => false,
        ];
    }

    private function filterRows(array $rows, array $filters): array
    {
        $search = mb_strtolower($filters['search']);

        return array_values(array_filter($rows, static function (array $row) use ($filters, $search): bool {
            if ($search !== '') {
                $haystack = mb_strtolower(implode(' ', [
                    $row['book_title'],
                    $row['book_author'],
                    'LN-' . str_pad((string) $row['loan_id'], 4, '0', STR_PAD_LEFT),
                ]));

                if (! str_contains($haystack, $search)) {
                    return false;
                }
            }

            return match ($filters['filter']) {
                'active' => ! $row['is_returned'],
                'due_soon' => $row['is_due_soon'],
                'overdue' => $row['is_overdue'],
                'returned' => $row['is_returned'],
                default => true,
            };
        }));
    }

    private function sortRows(array $rows, string $sort): array
    {
        usort($rows, static function (array $first, array $second) use ($sort): int {
            return match ($sort) {
                'oldest' => strcmp($first['loan_date_iso'], $second['loan_date_iso'])
                    ?: ($first['loan_id'] <=> $second['loan_id']),
                'nearest_due' => strcmp($first['due_date_iso'], $second['due_date_iso'])
                    ?: strcmp($second['loan_date_iso'], $first['loan_date_iso']),
                'most_overdue' => ((int) $second['is_overdue'] <=> (int) $first['is_overdue'])
                    ?: strcmp($first['due_date_iso'], $second['due_date_iso']),
                default => strcmp($second['loan_date_iso'], $first['loan_date_iso'])
                    ?: ($second['loan_id'] <=> $first['loan_id']),
            };
        });

        return $rows;
    }

    private function buildSummaryCards(array $rows): array
    {
        $currentlyBorrowed = $this->sumQuantities(array_filter($rows, static fn (array $row): bool => ! $row['is_returned']));
        $dueSoon = $this->sumQuantities(array_filter($rows, static fn (array $row): bool => $row['is_due_soon']));
        $overdue = $this->sumQuantities(array_filter($rows, static fn (array $row): bool => $row['is_overdue']));
        $history = $this->sumQuantities(array_filter($rows, static fn (array $row): bool => $row['is_returned']));

        return [
            [
                'label' => 'Currently Borrowed',
                'value' => $currentlyBorrowed,
                'unit' => $this->pluralize($currentlyBorrowed, 'Book', 'Books'),
                'icon' => 'menu_book',
                'tone' => 'blue',
            ],
            [
                'label' => 'Due Soon',
                'value' => $dueSoon,
                'unit' => $this->pluralize($dueSoon, 'Book', 'Books'),
                'icon' => 'calendar_month',
                'tone' => 'emerald',
            ],
            [
                'label' => 'Overdue',
                'value' => $overdue,
                'unit' => $this->pluralize($overdue, 'Book', 'Books'),
                'icon' => 'warning',
                'tone' => 'orange',
            ],
            [
                'label' => 'Loan History',
                'value' => $history,
                'unit' => $this->pluralize($history, 'Book', 'Books'),
                'icon' => 'history',
                'tone' => 'violet',
            ],
        ];
    }

    private function buildResultSummary(int $visibleCount, int $totalCount): string
    {
        if ($visibleCount === 0) {
            return 'No loans to show.';
        }

        return 'Showing 1 to ' . $visibleCount . ' of ' . $totalCount . ' loans.';
    }

    private function presentStatus(bool $isReturned, bool $isOverdue, bool $isDueSoon): array
    {
        if ($isReturned) {
            return ['key' => 'returned', 'label' => 'Returned', 'tone' => 'blue'];
        }

        if ($isOverdue) {
            return ['key' => 'overdue', 'label' => 'Overdue', 'tone' => 'red'];
        }

        if ($isDueSoon) {
            return ['key' => 'due_soon', 'label' => 'Due Soon', 'tone' => 'yellow'];
        }

        return ['key' => 'on_time', 'label' => 'On Time', 'tone' => 'green'];
    }

    private function buildDueHint(bool $isReturned, int $daysUntilDue, ?DateTimeImmutable $returnDate): string
    {
        if ($isReturned) {
            return $returnDate !== null ? 'Returned ' . $this->formatDate($returnDate) : 'Returned';
        }

        if ($daysUntilDue < 0) {
            $days = abs($daysUntilDue);
            return $days . ' ' . $this->pluralize($days, 'day', 'days') . ' overdue';
        }

        if ($daysUntilDue === 0) {
            return 'Due today';
        }

        return $daysUntilDue . ' ' . $this->pluralize($daysUntilDue, 'day', 'days') . ' left';
    }

    private function buildDuration(DateTimeImmutable $loanDate, DateTimeImmutable $returnDate): string
    {
        $days = (int) $loanDate->diff($returnDate)->format('%a');

        if ($days === 0) {
            return 'Same day';
        }

        return $days . ' ' . $this->pluralize($days, 'day', 'days');
    }

    private function parseDate(mixed $value): ?DateTimeImmutable
    {
        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        return new DateTimeImmutable($value);
    }

    private function formatDate(DateTimeImmutable $date): string
    {
        return $date->format('M j, Y');
    }

    private function buildInitials(string $title): string
    {
        $words = preg_split('/\s+/', trim($title)) ?: [];
        $initials = '';

        foreach ($words as $word) {
            if ($word === '') {
                continue;
            }

            $initials .= mb_strtoupper(mb_substr($word, 0, 1));

            if (mb_strlen($initials) >= 2) {
                break;
            }
        }

        return $initials !== '' ? $initials : 'BK';
    }

    private function sumQuantities(array $rows): int
    {
        return array_reduce(
            $rows,
            static fn (int $total, array $row): int => $total + (int) $row['quantity'],
            0
        );
    }

    private function pluralize(int $count, string $singular, string $plural): string
    {
        return $count === 1 ? $singular : $plural;
    }

    private function nullableString(mixed $value): ?string
    {
        return is_string($value) && $value !== '' ? $value : null;
    }

    private function mapOptions(array $options, string $selected, array $counts = []): array
    {
        $mapped = [];

        foreach ($options as $value => $label) {
            $mapped[] = [
                'value' => $value,
                'label' => $label,
                'selected' => $value === $selected,
                'count' => $counts[$value] ?? null,
            ];
        }

        return $mapped;
    }
}

