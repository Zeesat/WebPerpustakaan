<?php
$activeCategory = null;
foreach ($categories as $category) {
    if ($category['active']) {
        $activeCategory = $category;
        break;
    }
}

$selectedAvailabilityLabel = 'All Books';
foreach ($availabilityOptions as $option) {
    if ($option['selected']) {
        $selectedAvailabilityLabel = $option['label'];
        break;
    }
}

$buildCatalogUrl = static function (array $overrides = []) use ($filters): string {
    $query = [
        'search' => $overrides['search'] ?? $filters['search'],
        'category' => array_key_exists('category', $overrides) ? $overrides['category'] : $filters['category_id'],
        'availability' => $overrides['availability'] ?? $filters['availability'],
        'sort' => $overrides['sort'] ?? $filters['sort'],
    ];

    $normalized = [];

    foreach ($query as $key => $value) {
        if ($value === null || $value === '') {
            continue;
        }

        if ($key === 'availability' && $value === 'all') {
            continue;
        }

        if ($key === 'sort' && $value === 'latest') {
            continue;
        }

        $normalized[$key] = $value;
    }

    $queryString = $normalized === [] ? '' : '?' . http_build_query($normalized);

    return url('/books') . $queryString;
};
?>

<div class="catalog-page">
    <section class="catalog-hero">
        <div class="catalog-hero__layout">
            <div class="catalog-hero__content">
                <p class="catalog-hero__eyebrow">Library Catalog</p>
                <h1 class="catalog-hero__title">Welcome, <?= htmlspecialchars($welcomeName); ?></h1>
                <p class="catalog-hero__subtitle">
                    Discover verified library titles, monitor live availability, and move from search to borrowing without leaving the catalog.
                </p>

                <form action="<?= htmlspecialchars(url('/books')); ?>" class="catalog-search" method="GET">
                    <input
                        class="catalog-search__field"
                        name="search"
                        type="search"
                        value="<?= htmlspecialchars($filters['search']); ?>"
                        placeholder="Search by title, author, or keyword"
                    >
                    <?php if ($filters['category_id'] !== null): ?>
                        <input name="category" type="hidden" value="<?= htmlspecialchars((string) $filters['category_id']); ?>">
                    <?php endif; ?>
                    <?php if ($filters['availability'] !== 'all'): ?>
                        <input name="availability" type="hidden" value="<?= htmlspecialchars($filters['availability']); ?>">
                    <?php endif; ?>
                    <?php if ($filters['sort'] !== 'latest'): ?>
                        <input name="sort" type="hidden" value="<?= htmlspecialchars($filters['sort']); ?>">
                    <?php endif; ?>
                    <button class="catalog-search__button" type="submit">Search Catalog</button>
                </form>
            </div>

            <aside class="catalog-hero__summary">
                <p class="catalog-section__eyebrow">Current Snapshot</p>
                <h2 class="catalog-section__title"><?= htmlspecialchars($catalogTitle); ?></h2>
                <p><?= htmlspecialchars($catalogSubtitle); ?></p>
                <ul class="catalog-hero__summary-list">
                    <li>
                        <span>Visible titles</span>
                        <strong><?= htmlspecialchars((string) count($books)); ?></strong>
                    </li>
                    <li>
                        <span>Availability mode</span>
                        <strong><?= htmlspecialchars($selectedAvailabilityLabel); ?></strong>
                    </li>
                    <li>
                        <span>Active category</span>
                        <strong><?= htmlspecialchars($activeCategory['name'] ?? 'All Categories'); ?></strong>
                    </li>
                </ul>
            </aside>
        </div>

        <div class="catalog-stats">
            <?php foreach ($heroStats as $stat): ?>
                <article class="catalog-stat-card">
                    <p class="catalog-section__eyebrow"><?= htmlspecialchars($stat['label']); ?></p>
                    <h2 class="catalog-stat-card__value"><?= htmlspecialchars($stat['value']); ?></h2>
                    <p class="catalog-stat-card__detail"><?= htmlspecialchars($stat['detail']); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <div class="catalog-layout">
        <aside>
            <section class="catalog-filter-card">
                <div class="catalog-filter-group">
                    <span class="catalog-filter-group__label">Categories</span>
                    <a class="catalog-filter-link<?= $activeCategory === null ? ' is-active' : ''; ?>" href="<?= htmlspecialchars($buildCatalogUrl(['category' => null])); ?>">
                        <span class="catalog-filter-link__title">All Categories</span>
                        <span class="catalog-filter-link__meta"><?= htmlspecialchars((string) $catalogTotals['total_titles']); ?> titles</span>
                    </a>

                    <?php foreach ($categories as $category): ?>
                        <a class="catalog-filter-link<?= $category['active'] ? ' is-active' : ''; ?>" href="<?= htmlspecialchars($buildCatalogUrl(['category' => $category['id']])); ?>">
                            <span class="catalog-filter-link__title"><?= htmlspecialchars($category['name']); ?></span>
                            <span class="catalog-filter-link__meta"><?= htmlspecialchars((string) $category['book_count']); ?> titles</span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="catalog-filter-card">
                <form action="<?= htmlspecialchars(url('/books')); ?>" class="catalog-filter-group" method="GET">
                    <span class="catalog-filter-group__label">Availability</span>
                    <?php if ($filters['search'] !== ''): ?>
                        <input name="search" type="hidden" value="<?= htmlspecialchars($filters['search']); ?>">
                    <?php endif; ?>
                    <?php if ($filters['category_id'] !== null): ?>
                        <input name="category" type="hidden" value="<?= htmlspecialchars((string) $filters['category_id']); ?>">
                    <?php endif; ?>
                    <?php if ($filters['sort'] !== 'latest'): ?>
                        <input name="sort" type="hidden" value="<?= htmlspecialchars($filters['sort']); ?>">
                    <?php endif; ?>

                    <?php foreach ($availabilityOptions as $option): ?>
                        <label class="catalog-radio<?= $option['selected'] ? ' is-active' : ''; ?>">
                            <span>
                                <input
                                    name="availability"
                                    type="radio"
                                    value="<?= htmlspecialchars($option['value']); ?>"
                                    <?= $option['selected'] ? 'checked' : ''; ?>
                                >
                                <span class="catalog-radio__label"><?= htmlspecialchars($option['label']); ?></span>
                            </span>
                            <span class="catalog-radio__count">
                                <?php if ($option['value'] === 'available'): ?>
                                    <?= htmlspecialchars((string) $catalogTotals['available_titles']); ?>
                                <?php elseif ($option['value'] === 'out_of_stock'): ?>
                                    <?= htmlspecialchars((string) $catalogTotals['out_of_stock_titles']); ?>
                                <?php else: ?>
                                    <?= htmlspecialchars((string) $catalogTotals['total_titles']); ?>
                                <?php endif; ?>
                            </span>
                        </label>
                    <?php endforeach; ?>

                    <button class="catalog-search__button" type="submit">Apply Availability</button>
                </form>
            </section>
        </aside>

        <section>
            <div class="catalog-section__header">
                <div>
                    <p class="catalog-section__eyebrow">Browse</p>
                    <h2 class="catalog-section__title"><?= htmlspecialchars($catalogTitle); ?></h2>
                    <p class="catalog-section__subtitle"><?= htmlspecialchars($resultSummary); ?></p>
                </div>

                <form action="<?= htmlspecialchars(url('/books')); ?>" class="catalog-toolbar__controls" method="GET">
                    <?php if ($filters['search'] !== ''): ?>
                        <input name="search" type="hidden" value="<?= htmlspecialchars($filters['search']); ?>">
                    <?php endif; ?>
                    <?php if ($filters['category_id'] !== null): ?>
                        <input name="category" type="hidden" value="<?= htmlspecialchars((string) $filters['category_id']); ?>">
                    <?php endif; ?>
                    <?php if ($filters['availability'] !== 'all'): ?>
                        <input name="availability" type="hidden" value="<?= htmlspecialchars($filters['availability']); ?>">
                    <?php endif; ?>

                    <select class="catalog-toolbar__select" name="sort" onchange="this.form.submit()">
                        <?php foreach ($sortOptions as $option): ?>
                            <option value="<?= htmlspecialchars($option['value']); ?>" <?= $option['selected'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($option['label']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>

            <?php if ($hasActiveFilters): ?>
                <div class="catalog-toolbar" style="margin-top: 16px;">
                    <div class="catalog-toolbar__controls">
                        <a class="catalog-chip" href="<?= htmlspecialchars(url('/books')); ?>">Reset all filters</a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($books === []): ?>
                <article class="empty-state" style="margin-top: 22px;">
                    <p class="catalog-section__eyebrow">No Results</p>
                    <h3 class="empty-state__title">No books matched your current filters.</h3>
                    <p class="empty-state__text">
                        Try switching category, clearing the search keyword, or returning to the full catalog to explore all available titles.
                    </p>
                    <a class="empty-state__button" href="<?= htmlspecialchars(url('/books')); ?>">Back to Full Catalog</a>
                </article>
            <?php else: ?>
                <div class="catalog-grid">
                    <?php foreach ($books as $book): ?>
                                                <article class="book-card">
                            <?php if ($book['cover']['url'] !== null): ?>
                            <div class="book-card__cover" style="padding: 0; min-height: 240px;">
                                <img
                                    src="<?= htmlspecialchars($book['cover']['url']); ?>"
                                    alt="Cover of <?= htmlspecialchars($book['title']); ?>"
                                    class="book-card__cover-img"
                                    loading="lazy"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                >
                                <div class="book-card__cover book-card__cover--<?= htmlspecialchars($book['cover']['tone']); ?>" style="display: none; min-height: 240px;">
                                    <span class="book-card__cover-badge"><?= htmlspecialchars($book['availability']['label']); ?></span>
                                    <span class="book-card__cover-initials"><?= htmlspecialchars($book['cover']['initials']); ?></span>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="book-card__cover book-card__cover--<?= htmlspecialchars($book['cover']['tone']); ?>">
                                <span class="book-card__cover-badge"><?= htmlspecialchars($book['availability']['label']); ?></span>
                                <span class="book-card__cover-initials"><?= htmlspecialchars($book['cover']['initials']); ?></span>
                            </div>
                            <?php endif; ?>

                            <span class="book-card__category book-card__category--<?= htmlspecialchars($book['category']['tone']); ?>">
                                <?= htmlspecialchars($book['category']['name']); ?>
                            </span>

                            <div>
                                <h3 class="book-card__title"><?= htmlspecialchars($book['title']); ?></h3>
                                <p class="book-card__author">by <?= htmlspecialchars($book['author']); ?></p>
                            </div>

                            <p class="book-card__description"><?= htmlspecialchars($book['description']); ?></p>

                            <div class="book-card__meta">
                                <span class="book-card__stock">Stock: <?= htmlspecialchars((string) $book['stock']); ?></span>
                                <span class="book-card__status book-card__status--<?= htmlspecialchars($book['availability']['tone']); ?>">
                                    <?= htmlspecialchars($book['availability']['detail']); ?>
                                </span>
                            </div>

                            <a class="book-card__button" href="<?= htmlspecialchars(url('/books/show?id=' . $book['id'])); ?>">View Details</a>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>
