<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\User;

class BookService
{
    private const SORT_OPTIONS = [
        'latest' => 'Latest Added',
        'title_asc' => 'Title A-Z',
        'title_desc' => 'Title Z-A',
        'author_asc' => 'Author A-Z',
        'stock_desc' => 'Highest Stock',
    ];

    private const AVAILABILITY_OPTIONS = [
        'all' => 'All Books',
        'available' => 'Available Only',
        'out_of_stock' => 'Out of Stock',
    ];

    public function __construct(
        private ?Book $books = null,
        private ?Category $categories = null,
        private ?Loan $loans = null,
        private ?User $users = null,
    ) {
        $this->books ??= new Book();
        $this->categories ??= new Category();
        $this->loans ??= new Loan();
        $this->users ??= new User();
    }

    public function getCatalogPageData(?array $sessionUser, array $input = []): array
    {
        $filters = $this->normalizeFilters($input);
        $catalogSummary = $this->books->getCatalogSummary();
        $categoryRows = $this->categories->getAllWithBookCounts();
        $bookRows = $this->books->getCatalogBooks($filters);
        $visibleCount = count($bookRows);
        $resolvedUser = $this->resolveUser($sessionUser);
        $selectedCategory = $this->findSelectedCategory($categoryRows, $filters['category_id']);
        $activeLoans = isset($resolvedUser['id']) ? $this->loans->countActiveByUser((int) $resolvedUser['id']) : 0;

        return [
            'title' => 'Book Catalog',
            'mainClass' => '',
            'bodyClass' => '',
            'filters' => $filters,
            'sortOptions' => $this->mapOptions(self::SORT_OPTIONS, $filters['sort']),
            'availabilityOptions' => $this->mapOptions(self::AVAILABILITY_OPTIONS, $filters['availability']),
            'categories' => $this->presentCategories($categoryRows, $filters['category_id']),
            'books' => array_map([$this, 'presentBookCard'], $bookRows),
            'heroStats' => [
                $this->buildStatCard('Book Titles', $catalogSummary['total_titles'], 'Curated titles in the catalog'),
                $this->buildStatCard('Available Copies', $catalogSummary['total_copies'], 'Copies currently tracked in stock'),
                $this->buildStatCard('Categories', count($categoryRows), 'Collections organized by subject'),
                $this->buildStatCard('My Active Loans', $activeLoans, auth_check() ? 'Loans pending, approved, or late' : 'Login to track your loans'),
            ],
            'catalogTotals' => [
                'total_titles' => $catalogSummary['total_titles'],
                'total_copies' => $catalogSummary['total_copies'],
                'available_titles' => $catalogSummary['available_titles'],
                'out_of_stock_titles' => $catalogSummary['out_of_stock_titles'],
                'category_count' => count($categoryRows),
                'active_loans' => $activeLoans,
            ],
            'welcomeName' => $resolvedUser['name'] ?? 'reader',
            'catalogTitle' => $selectedCategory['name'] ?? 'All Books',
            'catalogSubtitle' => $this->buildCatalogSubtitle($filters, $selectedCategory, $visibleCount, $catalogSummary),
            'resultSummary' => $this->buildResultSummary($filters, $visibleCount, $catalogSummary['total_titles']),
            'hasActiveFilters' => $filters['search'] !== '' || $filters['category_id'] !== null || $filters['availability'] !== 'all' || $filters['sort'] !== 'latest',
        ];
    }

    public function getBookDetailPageData(int $id, ?array $sessionUser): ?array
    {
        $book = $this->books->findById($id);

        if ($book === null) {
            return null;
        }

        $resolvedUser = $this->resolveUser($sessionUser);
        $relatedBooks = $this->books->findRelatedByCategory(
            (int) $book['category_id'],
            (int) $book['id'],
            3
        );

        return [
            'title' => (string) $book['title'],
            'mainClass' => 'catalog-main',
            'bodyClass' => 'catalog-page-body',
            'book' => $this->presentBookDetail($book),
            'relatedBooks' => array_map([$this, 'presentBookCard'], $relatedBooks),
            'welcomeName' => $resolvedUser['name'] ?? 'reader',
        ];
    }

    private function normalizeFilters(array $input): array
    {
        $search = trim((string) ($input['search'] ?? ''));
        $search = mb_substr($search, 0, 100);

        $categoryId = filter_var($input['category'] ?? null, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        $availability = (string) ($input['availability'] ?? 'all');
        if (! array_key_exists($availability, self::AVAILABILITY_OPTIONS)) {
            $availability = 'all';
        }

        $sort = (string) ($input['sort'] ?? 'latest');
        if (! array_key_exists($sort, self::SORT_OPTIONS)) {
            $sort = 'latest';
        }

        return [
            'search' => $search,
            'category_id' => $categoryId === false ? null : $categoryId,
            'availability' => $availability,
            'sort' => $sort,
        ];
    }

    private function resolveUser(?array $sessionUser): ?array
    {
        if (! is_array($sessionUser)) {
            return null;
        }

        $userId = filter_var($sessionUser['id'] ?? null, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        if ($userId === false) {
            return $sessionUser;
        }

        $freshUser = $this->users->findById($userId);

        if ($freshUser !== null) {
            session_put('auth.user', $freshUser);
            return $freshUser;
        }

        return $sessionUser;
    }

    private function findSelectedCategory(array $categories, ?int $selectedCategoryId): ?array
    {
        if ($selectedCategoryId === null) {
            return null;
        }

        foreach ($categories as $category) {
            if ((int) ($category['id'] ?? 0) === $selectedCategoryId) {
                return $category;
            }
        }

        return null;
    }

    private function presentCategories(array $categories, ?int $selectedCategoryId): array
    {
        return array_map(
            static fn (array $category): array => [
                'id' => (int) $category['id'],
                'name' => (string) $category['name'],
                'book_count' => (int) $category['book_count'],
                'stock_total' => (int) $category['stock_total'],
                'active' => $selectedCategoryId !== null && (int) $category['id'] === $selectedCategoryId,
            ],
            $categories
        );
    }

    private function presentBookCard(array $book): array
    {
        $stock = (int) ($book['stock'] ?? 0);
        $categoryName = trim((string) ($book['category_name'] ?? 'General'));
        $theme = $this->resolveTheme($categoryName);
        $availability = $this->presentAvailability($stock);
        $coverFilename = isset($book['cover']) && is_string($book['cover']) && $book['cover'] !== ''
            ? $book['cover']
            : null;
        $detailUrl = url('/books/show?id=' . (int) $book['id']);
        $coverUrl = cover_url($coverFilename);
        $initials = $this->buildInitials((string) $book['title']);

        return [
            'id' => (int) $book['id'],
            'title' => (string) $book['title'],
            'author' => (string) $book['author'],
            'description' => trim((string) ($book['description'] ?? '')) ?: 'No description is available for this title yet.',
            'category' => [
                'id' => (int) $book['category_id'],
                'name' => $categoryName,
                'tone' => $theme['badge'],
            ],
            'stock' => $stock,
            'availability' => $availability,
            'available_for_request' => $stock > 0,
            'stock_label' => $stock === 1 ? '1 copy available' : $stock . ' copies available',
            'detail_url' => $detailUrl,
            'cover' => [
                'initials' => $initials,
                'tone' => $theme['cover'],
                'url' => $coverUrl,
            ],
            'basket_item' => [
                'book_id' => (int) $book['id'],
                'quantity' => 1,
                'title' => (string) $book['title'],
                'author' => (string) $book['author'],
                'category' => $categoryName,
                'category_tone' => $theme['badge'],
                'detail_url' => $detailUrl,
                'stock' => $stock,
                'availability' => $availability['label'],
                'cover' => [
                    'url' => $coverUrl,
                    'initials' => $initials,
                    'tone' => $theme['cover'],
                ],
            ],
        ];
    }

    private function presentBookDetail(array $book): array
    {
        $presented = $this->presentBookCard($book);
        $presented['description'] = trim((string) ($book['description'] ?? '')) !== ''
            ? trim((string) $book['description'])
            : 'This title is available in the library catalog, but the full description has not been filled in yet.';

        return $presented;
    }

    private function presentAvailability(int $stock): array
    {
        if ($stock <= 0) {
            return [
                'label' => 'Out of stock',
                'detail' => 'Currently unavailable for new requests',
                'tone' => 'danger',
            ];
        }

        if ($stock <= 2) {
            return [
                'label' => 'Limited stock',
                'detail' => $stock . ' copy left in circulation',
                'tone' => 'warning',
            ];
        }

        return [
            'label' => 'Available now',
            'detail' => $stock . ' copies ready to borrow',
            'tone' => 'success',
        ];
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

    private function resolveTheme(string $categoryName): array
    {
        $key = strtolower($categoryName);

        return match (true) {
            str_contains($key, 'science') => ['badge' => 'emerald', 'cover' => 'science'],
            str_contains($key, 'tech') || str_contains($key, 'computer') => ['badge' => 'cyan', 'cover' => 'technology'],
            str_contains($key, 'history') => ['badge' => 'amber', 'cover' => 'history'],
            str_contains($key, 'business') => ['badge' => 'orange', 'cover' => 'business'],
            str_contains($key, 'self') => ['badge' => 'violet', 'cover' => 'growth'],
            default => ['badge' => 'blue', 'cover' => 'classic'],
        };
    }

    private function buildStatCard(string $label, int $value, string $detail): array
    {
        return [
            'label' => $label,
            'value' => number_format($value),
            'detail' => $detail,
        ];
    }

    private function buildCatalogSubtitle(array $filters, ?array $selectedCategory, int $visibleCount, array $catalogSummary): string
    {
        if ($filters['search'] !== '') {
            return 'Results for "' . $filters['search'] . '" across the library catalog.';
        }

        if ($selectedCategory !== null) {
            return $selectedCategory['book_count'] . ' title(s) organized under this category.';
        }

        if ($catalogSummary['total_titles'] === 0) {
            return 'The catalog is ready. Add your first title to start building the collection.';
        }

        return $visibleCount . ' title(s) ready to explore from the latest library catalog.';
    }

    private function buildResultSummary(array $filters, int $visibleCount, int $totalTitles): string
    {
        $parts = [];

        if ($filters['category_id'] !== null) {
            $parts[] = 'category filter applied';
        }

        if ($filters['availability'] !== 'all') {
            $parts[] = str_replace('_', ' ', $filters['availability']);
        }

        if ($filters['search'] !== '') {
            $parts[] = 'search active';
        }

        $summary = $visibleCount . ' of ' . $totalTitles . ' title(s)';

        if ($parts === []) {
            return $summary . ' in the catalog.';
        }

        return $summary . ' with ' . implode(', ', $parts) . '.';
    }

    private function mapOptions(array $options, string $selected): array
    {
        $mapped = [];

        foreach ($options as $value => $label) {
            $mapped[] = [
                'value' => $value,
                'label' => $label,
                'selected' => $value === $selected,
            ];
        }

        return $mapped;
    }
}

