<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Models\Book;
use App\Models\Loan;
use App\Models\LoanDetail;
use DateTimeImmutable;
use PDO;
use Throwable;

class BorrowBasketService
{
    public const STORAGE_KEY = 'library.loan-basket.v1';
    public const MAX_ITEMS_PER_REQUEST = 3;
    public const STORAGE_TTL_HOURS = 12;
    private const LOAN_PERIOD_DAYS = 7;

    public function __construct(
        private ?Book $books = null,
        private ?Loan $loans = null,
        private ?LoanDetail $loanDetails = null,
        private ?DateTimeImmutable $today = null,
    ) {
        $this->books ??= new Book();
        $this->loans ??= new Loan();
        $this->loanDetails ??= new LoanDetail();
        $this->today ??= new DateTimeImmutable('today');
    }

    public function getPageData(?array $sessionUser): array
    {
        $userId = $this->resolveUserId($sessionUser);
        $activeLoanCount = $userId !== null ? $this->loans->countActiveByUser($userId) : 0;

        return [
            'title' => 'Borrow Basket',
            'mainClass' => 'basket-main',
            'bodyClass' => 'basket-page-body',
            'basketConfig' => [
                'storageKey' => self::STORAGE_KEY,
                'maxItems' => self::MAX_ITEMS_PER_REQUEST,
                'storageTtlHours' => self::STORAGE_TTL_HOURS,
                'loanDurationDays' => self::LOAN_PERIOD_DAYS,
                'submitUrl' => url('/loans/request'),
                'browseUrl' => url('/books'),
                'myLoansUrl' => url('/loans'),
                'csrfToken' => csrf_token(),
                'currentUser' => [
                    'id' => $userId,
                    'name' => (string) ($sessionUser['name'] ?? 'Reader'),
                ],
                'activeLoanCount' => $activeLoanCount,
                'hasBlockingActiveLoan' => $activeLoanCount > 0,
            ],
            'basketHighlights' => [
                [
                    'label' => 'Selected',
                    'value' => '0',
                    'meta' => 'Ready to review',
                    'icon' => 'shopping_basket',
                ],
                [
                    'label' => 'Request Limit',
                    'value' => (string) self::MAX_ITEMS_PER_REQUEST,
                    'meta' => 'Titles per request',
                    'icon' => 'rule',
                ],
                [
                    'label' => 'Request Window',
                    'value' => self::LOAN_PERIOD_DAYS . 'd',
                    'meta' => 'Initial due date',
                    'icon' => 'calendar_month',
                ],
                [
                    'label' => 'Approval',
                    'value' => 'Admin',
                    'meta' => 'Stock rechecked later',
                    'icon' => 'verified_user',
                ],
            ],
            'basketRules' => [
                [
                    'label' => 'Max ' . self::MAX_ITEMS_PER_REQUEST . ' books',
                    'tone' => 'neutral',
                    'icon' => 'rule',
                ],
                [
                    'label' => 'One active request at a time',
                    'tone' => $activeLoanCount > 0 ? 'warning' : 'success',
                    'icon' => $activeLoanCount > 0 ? 'pending_actions' : 'check_circle',
                ],
                [
                    'label' => 'Admin approval required',
                    'tone' => 'info',
                    'icon' => 'admin_panel_settings',
                ],
            ],
            'blockingNotice' => $activeLoanCount > 0
                ? 'You already have an active loan or pending request. Review your basket, but submission is currently locked.'
                : null,
        ];
    }

    public function submitRequest(?array $sessionUser, array $payload): array
    {
        $userId = $this->resolveUserId($sessionUser);

        if ($userId === null) {
            return $this->buildFailureResponse(
                'session_expired',
                'Your session has expired. Please sign in again.',
                401
            );
        }

        $normalized = $this->normalizeItems($payload['items'] ?? null);
        $items = $normalized['items'];
        $issues = $normalized['issues'];

        if ($items === [] && $issues === []) {
            return $this->buildFailureResponse(
                'empty_basket',
                'Your basket is empty. Add at least one title before submitting.',
                422
            );
        }

        if (count($items) > self::MAX_ITEMS_PER_REQUEST) {
            $issues[] = [
                'scope' => 'basket',
                'code' => 'max_items_exceeded',
                'message' => 'This request exceeds the limit of ' . self::MAX_ITEMS_PER_REQUEST . ' books.',
            ];
        }

        $activeLoanCount = $this->loans->countActiveByUser($userId);
        if ($activeLoanCount > 0) {
            $issues[] = [
                'scope' => 'basket',
                'code' => 'active_loan_exists',
                'message' => 'You already have an active loan or pending request.',
            ];
        }

        $bookIds = array_column($items, 'book_id');
        $books = $this->books->findByIds($bookIds);
        $booksById = [];

        foreach ($books as $book) {
            $bookId = (int) ($book['id'] ?? 0);
            if ($bookId > 0) {
                $booksById[$bookId] = $book;
            }
        }

        foreach ($bookIds as $bookId) {
            if (! array_key_exists($bookId, $booksById)) {
                $issues[] = [
                    'scope' => 'item',
                    'code' => 'book_missing',
                    'book_id' => $bookId,
                    'message' => 'One of the selected titles is no longer in the catalog.',
                ];
                continue;
            }

            $book = $booksById[$bookId];
            $stock = (int) ($book['stock'] ?? 0);

            if ($stock <= 0) {
                $issues[] = [
                    'scope' => 'item',
                    'code' => 'book_unavailable',
                    'book_id' => $bookId,
                    'message' => '"' . (string) ($book['title'] ?? 'This title') . '" is currently unavailable.',
                ];
            }
        }

        if ($issues !== []) {
            return [
                'success' => false,
                'code' => 'validation_failed',
                'message' => 'Review the highlighted items before submitting your loan request.',
                'issues' => array_values($issues),
                'meta' => [
                    'maxItems' => self::MAX_ITEMS_PER_REQUEST,
                    'activeLoanCount' => $activeLoanCount,
                ],
                'statusCode' => 422,
            ];
        }

        $connection = Database::connection();
        if (! $connection instanceof PDO) {
            return $this->buildFailureResponse(
                'server_unavailable',
                'The request service is temporarily unavailable. Please try again shortly.',
                500
            );
        }

        $loanModel = new Loan($connection);
        $loanDetailModel = new LoanDetail($connection);
        $loanDate = $this->today->format('Y-m-d');
        $dueDate = $this->today->modify('+' . self::LOAN_PERIOD_DAYS . ' days')->format('Y-m-d');

        try {
            $connection->beginTransaction();

            $loanId = $loanModel->createPending($userId, $loanDate, $dueDate);

            if (! is_int($loanId) || $loanId <= 0) {
                throw new \RuntimeException('Loan record could not be created.');
            }

            foreach ($items as $item) {
                $created = $loanDetailModel->create(
                    $loanId,
                    (int) $item['book_id'],
                    (int) $item['quantity']
                );

                if (! $created) {
                    throw new \RuntimeException('Loan detail could not be created.');
                }
            }

            $connection->commit();
        } catch (Throwable $throwable) {
            if ($connection->inTransaction()) {
                $connection->rollBack();
            }

            error_log($throwable->getMessage());

            return $this->buildFailureResponse(
                'submission_failed',
                'We could not submit your request right now. Please try again.',
                500
            );
        }

        return [
            'success' => true,
            'message' => 'Your loan request has been submitted for admin approval.',
            'loan' => [
                'id' => $loanId,
                'reference' => $this->formatReference($loanId),
                'status' => 'pending',
                'loanDate' => $loanDate,
                'dueDate' => $dueDate,
                'itemCount' => count($items),
            ],
            'redirectUrl' => url('/loans'),
        ];
    }

    private function resolveUserId(?array $sessionUser): ?int
    {
        $userId = filter_var($sessionUser['id'] ?? null, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        return $userId === false ? null : $userId;
    }

    private function normalizeItems(mixed $payload): array
    {
        if (! is_array($payload)) {
            return [
                'items' => [],
                'issues' => [[
                    'scope' => 'basket',
                    'code' => 'invalid_payload',
                    'message' => 'The submitted basket format is invalid.',
                ]],
            ];
        }

        $items = [];
        $issues = [];
        $seenBookIds = [];

        foreach ($payload as $entry) {
            if (! is_array($entry)) {
                $issues[] = [
                    'scope' => 'basket',
                    'code' => 'invalid_item',
                    'message' => 'One of the basket items could not be processed.',
                ];
                continue;
            }

            $bookId = filter_var($entry['book_id'] ?? null, FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 1],
            ]);

            if ($bookId === false) {
                $issues[] = [
                    'scope' => 'basket',
                    'code' => 'invalid_item',
                    'message' => 'One of the selected titles is invalid.',
                ];
                continue;
            }

            if (isset($seenBookIds[$bookId])) {
                $issues[] = [
                    'scope' => 'item',
                    'code' => 'duplicate_item',
                    'book_id' => $bookId,
                    'message' => 'This title was submitted more than once.',
                ];
                continue;
            }

            $quantity = filter_var($entry['quantity'] ?? 1, FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 1],
            ]);

            if ($quantity === false || $quantity !== 1) {
                $issues[] = [
                    'scope' => 'item',
                    'code' => 'invalid_quantity',
                    'book_id' => $bookId,
                    'message' => 'Multiple copies of the same title are not supported.',
                ];
                continue;
            }

            $seenBookIds[$bookId] = true;
            $items[] = [
                'book_id' => $bookId,
                'quantity' => 1,
            ];
        }

        return [
            'items' => $items,
            'issues' => $issues,
        ];
    }

    private function buildFailureResponse(string $code, string $message, int $statusCode): array
    {
        return [
            'success' => false,
            'code' => $code,
            'message' => $message,
            'issues' => [],
            'statusCode' => $statusCode,
        ];
    }

    private function formatReference(int $loanId): string
    {
        return 'LN-' . str_pad((string) $loanId, 4, '0', STR_PAD_LEFT);
    }
}
