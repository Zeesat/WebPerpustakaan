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

<div class="catalog-page-wrapper bg-[#f4f7fb] min-h-screen">
    <div class="catalog-hero-redesign relative bg-[#0a1e42] text-white overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80')] bg-cover bg-center opacity-10 mix-blend-overlay"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-[#0a1e42] via-[#0a1e42]/90 to-[#0a1e42]/40"></div>
        
        <div class="max-w-[1240px] mx-auto px-6 py-4 relative z-10 flex flex-col lg:flex-row justify-between items-start gap-10">
            <div class="w-full lg:w-[55%]">
                <h1 class="text-[28px] md:text-[34px] font-bold mb-3 tracking-tight">Welcome, <?= htmlspecialchars($welcomeName); ?>! 👋</h1>
                <p class="text-[14px] md:text-[16px] text-blue-100 mb-8 max-w-xl">
                    Discover books, request loans, and expand your knowledge.
                </p>

                <form action="<?= htmlspecialchars(url('/books')); ?>" class="relative flex items-center bg-white rounded-lg shadow-lg overflow-hidden h-[54px] w-full max-w-2xl" method="GET">
                    <div class="pl-5 text-slate-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">search</span>
                    </div>
                    <input
                        class="flex-1 bg-transparent border-none outline-none text-slate-700 px-3 py-3 text-[14px] md:text-[15px] placeholder:text-slate-400"
                        name="search"
                        type="search"
                        value="<?= htmlspecialchars($filters['search']); ?>"
                        placeholder="Search books by title, author, or keyword..."
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
                    <div class="pr-2">
                        <button class="bg-[#1e4ed8] hover:bg-blue-800 text-white font-medium px-5 md:px-7 py-2 md:py-2.5 text-[14px] md:text-[15px] rounded-md transition-colors" type="submit">Search</button>
                    </div>
                </form>
            </div>

            <div class="w-full lg:w-[45%] hidden md:flex gap-4 justify-end">
                <?php foreach ($heroStats as $index => $stat): ?>
                    <?php 
                        $icon = 'menu_book';
                        $colorClass = 'text-blue-500 bg-blue-50';
                        if ($index === 1) { $icon = 'category'; $colorClass = 'text-green-600 bg-green-50'; }
                        if ($index === 2) { $icon = 'bookmark'; $colorClass = 'text-purple-500 bg-purple-50'; }
                    ?>
                    <article class="bg-white rounded-xl p-5 w-[140px] shadow-sm flex flex-col items-center text-center">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-1 <?= $colorClass ?>">
                            <span class="material-symbols-outlined text-[20px]"><?= $icon ?></span>
                        </div>
                        <h2 class="text-xl font-bold text-slate-800 leading-none mb-1.5"><?= htmlspecialchars($stat['value']); ?></h2>
                        <p class="text-[12px] text-slate-500 font-medium leading-snug"><?= htmlspecialchars($stat['label']); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="max-w-[1240px] mx-auto px-6 py-8 flex flex-col md:flex-row gap-8 items-start">
        <aside id="catalog-filters-sidebar" class="hidden md:flex w-[260px] flex-shrink-0 flex-col gap-6 sticky top-24 self-start">
            <section class="bg-white rounded-xl shadow-sm px-4 pt-1 pb-4 border border-slate-100">
                <h3 class="text-[15px] font-bold text-slate-800 mb-3 px-2">Categories</h3>
                <div class="flex flex-col gap-1">
                    <a class="flex items-center justify-between px-3 py-2 rounded-lg text-[14px] <?= $activeCategory === null ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:bg-slate-50'; ?>" href="<?= htmlspecialchars($buildCatalogUrl(['category' => null])); ?>">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[18px] <?= $activeCategory === null ? 'text-blue-600' : 'text-slate-400' ?>">menu_book</span>
                            <span>All Categories</span>
                        </div>
                        <span class="<?= $activeCategory === null ? 'text-blue-600 bg-blue-100' : 'text-slate-400 bg-slate-100' ?> text-[11px] font-bold px-2 py-0.5 rounded-md"><?= htmlspecialchars((string) $catalogTotals['total_titles']); ?></span>
                    </a>

                    <?php foreach ($categories as $index => $category): ?>
                        <?php
                            $catIcons = ['book', 'science', 'computer', 'history', 'business_center', 'self_improvement', 'category'];
                            $icon = $catIcons[$index % count($catIcons)];
                        ?>
                        <a class="flex items-center justify-between px-3 py-2 rounded-lg text-[14px] <?= $category['active'] ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:bg-slate-50'; ?>" href="<?= htmlspecialchars($buildCatalogUrl(['category' => $category['id']])); ?>">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-[18px] <?= $category['active'] ? 'text-blue-600' : 'text-slate-400' ?>"><?= $icon ?></span>
                                <span><?= htmlspecialchars($category['name']); ?></span>
                            </div>
                            <span class="<?= $category['active'] ? 'text-blue-600 bg-blue-100' : 'text-slate-400 bg-slate-100' ?> text-[11px] font-bold px-2 py-0.5 rounded-md"><?= htmlspecialchars((string) $category['book_count']); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="bg-white rounded-xl shadow-sm px-4 pt-1 pb-4 border border-slate-100">
                <h3 class="text-[15px] font-bold text-slate-800 mb-4">Availability</h3>
                <form action="<?= htmlspecialchars(url('/books')); ?>" class="flex flex-col gap-3.5" method="GET" id="availability-form">
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
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input
                                name="availability"
                                type="radio"
                                value="<?= htmlspecialchars($option['value']); ?>"
                                <?= $option['selected'] ? 'checked' : ''; ?>
                                class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500"
                                onchange="document.getElementById('availability-form').submit()"
                            >
                            <span class="text-[14px] <?= $option['selected'] ? 'text-slate-800 font-medium' : 'text-slate-600 group-hover:text-slate-800' ?>"><?= htmlspecialchars($option['label']); ?></span>
                        </label>
                    <?php endforeach; ?>
                </form>
            </section>
        </aside>

        <section class="flex-1 min-w-0">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <div class="flex items-baseline gap-3">
                    <h2 class="text-[20px] font-bold text-slate-800"><?= htmlspecialchars($catalogTitle); ?></h2>
                    <p class="text-[14px] text-slate-500"><?= htmlspecialchars($resultSummary); ?></p>
                </div>

                <div class="flex items-center gap-2">
                    <button class="md:hidden flex items-center gap-1.5 bg-white rounded-lg border border-slate-200 px-3 py-1.5 shadow-sm hover:bg-slate-50 transition-colors" id="mobile-filter-toggle" type="button">
                        <span class="material-symbols-outlined text-[18px] text-slate-500">tune</span>
                        <span class="text-[13px] font-medium text-slate-600">Filters</span>
                    </button>
                    <form action="<?= htmlspecialchars(url('/books')); ?>" class="flex items-center gap-2 bg-white rounded-lg border border-slate-200 px-3 py-1.5 shadow-sm" method="GET">
                    <?php if ($filters['search'] !== ''): ?>
                        <input name="search" type="hidden" value="<?= htmlspecialchars($filters['search']); ?>">
                    <?php endif; ?>
                    <?php if ($filters['category_id'] !== null): ?>
                        <input name="category" type="hidden" value="<?= htmlspecialchars((string) $filters['category_id']); ?>">
                    <?php endif; ?>
                    <?php if ($filters['availability'] !== 'all'): ?>
                        <input name="availability" type="hidden" value="<?= htmlspecialchars($filters['availability']); ?>">
                    <?php endif; ?>

                    <span class="text-[13px] font-medium text-slate-500 whitespace-nowrap">Sort by:</span>
                    <select class="bg-transparent border-none outline-none text-[14px] text-slate-700 font-medium cursor-pointer pr-6 py-1" style="box-shadow: none;" name="sort" onchange="this.form.submit()">
                        <?php foreach ($sortOptions as $option): ?>
                            <option value="<?= htmlspecialchars($option['value']); ?>" <?= $option['selected'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($option['label']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
            </div>

            <?php if ($hasActiveFilters): ?>
                <div class="mb-6">
                    <a class="inline-block px-4 py-1.5 bg-white border border-slate-200 rounded-full text-[13px] font-medium text-slate-600 hover:bg-slate-50 transition-colors" href="<?= htmlspecialchars(url('/books')); ?>">Reset all filters</a>
                </div>
            <?php endif; ?>

            <?php if ($books === []): ?>
                <article class="bg-white rounded-xl p-10 text-center shadow-sm border border-slate-100">
                    <h3 class="text-xl font-bold text-slate-800 mb-2">No books matched your current filters.</h3>
                    <p class="text-slate-500 mb-6 max-w-md mx-auto">
                        Try switching category, clearing the search keyword, or returning to the full catalog to explore all available titles.
                    </p>
                    <a class="inline-block px-6 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors" href="<?= htmlspecialchars(url('/books')); ?>">Back to Full Catalog</a>
                </article>
            <?php else: ?>
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2 md:gap-5">
                    <?php foreach ($books as $book): ?>
                        <article class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow group flex flex-col h-full">
                            <div class="w-full aspect-[3/4] p-5 pb-0 bg-white flex items-center justify-center relative overflow-hidden">
                                <?php if ($book['cover']['url'] !== null): ?>
                                    <img
                                        src="<?= htmlspecialchars($book['cover']['url']); ?>"
                                        alt="Cover of <?= htmlspecialchars($book['title']); ?>"
                                        class="h-full w-auto object-contain shadow-md rounded-sm group-hover:scale-105 transition-transform duration-300"
                                        loading="lazy"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                    >
                                    <div class="h-full w-full flex items-center justify-center bg-slate-100 rounded-sm" style="display: none;">
                                        <span class="text-3xl font-bold text-slate-300"><?= htmlspecialchars($book['cover']['initials']); ?></span>
                                    </div>
                                <?php else: ?>
                                    <div class="h-full w-full flex items-center justify-center bg-slate-100 rounded-sm shadow-md">
                                        <span class="text-3xl font-bold text-slate-400"><?= htmlspecialchars($book['cover']['initials']); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="px-5 pt-0 pb-1.5 flex flex-col flex-1">
                                <h3 class="text-[15px] font-bold text-slate-800 leading-tight mb-0 truncate"><?= htmlspecialchars($book['title']); ?></h3>
                                <p class="text-[13px] text-slate-500 mb-1 truncate"><?= htmlspecialchars($book['author']); ?></p>

                                <div class="mt-auto flex flex-col gap-3">
                                    <div class="flex flex-col items-start gap-1">
                                        <?php
                                            // Determine badge color
                                            $catTone = $book['category']['tone'] ?? 'default';
                                            $badgeClasses = 'bg-blue-50 text-blue-600';
                                            if ($catTone === 'success') $badgeClasses = 'bg-green-50 text-green-600';
                                            if ($catTone === 'warning') $badgeClasses = 'bg-orange-50 text-orange-600';
                                            if ($catTone === 'error') $badgeClasses = 'bg-red-50 text-red-600';
                                            if ($catTone === 'purple') $badgeClasses = 'bg-purple-50 text-purple-600';
                                        ?>
                                        <span class="inline-block px-2 py-0.5 rounded text-[11px] font-medium <?= $badgeClasses ?> mb-1">
                                            <?= htmlspecialchars($book['category']['name']); ?>
                                        </span>
                                        <span class="text-[12px] font-medium <?= $book['stock'] > 0 ? 'text-emerald-600' : 'text-rose-500' ?>">
                                            Stock: <?= htmlspecialchars((string) $book['stock']); ?> available
                                        </span>
                                    </div>
                                    <a class="block w-full py-2 border border-blue-200 text-blue-600 text-[14px] font-semibold text-center rounded-lg hover:bg-blue-50 transition-colors" href="<?= htmlspecialchars(url('/books/show?id=' . $book['id'])); ?>">View Details</a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <!-- Mobile Filter Drawer -->
    <div class="mobile-menu-backdrop md:hidden" id="mobile-filter-backdrop" aria-hidden="true"></div>
    <div class="mobile-menu-drawer md:hidden" id="mobile-filter-drawer" role="dialog" aria-modal="true" aria-label="Filters">
        <div class="mobile-menu-drawer__header">
            <span class="font-bold text-slate-900 text-[18px] tracking-tight">Filters</span>
            <button class="flex h-10 w-10 items-center justify-center rounded-full text-slate-500 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 border-none bg-transparent cursor-pointer" id="mobile-filter-close" type="button" aria-label="Close filters">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <div class="mobile-menu-drawer__content p-5 overflow-y-auto">
            <h3 class="text-[15px] font-bold text-slate-800 mb-3">Categories</h3>
            <div class="flex flex-col gap-1 mb-8">
                <a class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[14px] <?= $activeCategory === null ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:bg-slate-50'; ?>" href="<?= htmlspecialchars($buildCatalogUrl(['category' => null])); ?>">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[18px] <?= $activeCategory === null ? 'text-blue-600' : 'text-slate-400' ?>">menu_book</span>
                        <span>All Categories</span>
                    </div>
                    <span class="<?= $activeCategory === null ? 'text-blue-600 bg-blue-100' : 'text-slate-400 bg-slate-100' ?> text-[11px] font-bold px-2 py-0.5 rounded-md"><?= htmlspecialchars((string) $catalogTotals['total_titles']); ?></span>
                </a>

                <?php foreach ($categories as $index => $category): ?>
                    <?php
                        $catIcons = ['book', 'science', 'computer', 'history', 'business_center', 'self_improvement', 'category'];
                        $icon = $catIcons[$index % count($catIcons)];
                    ?>
                    <a class="flex items-center justify-between px-3 py-2.5 rounded-lg text-[14px] <?= $category['active'] ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:bg-slate-50'; ?>" href="<?= htmlspecialchars($buildCatalogUrl(['category' => $category['id']])); ?>">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[18px] <?= $category['active'] ? 'text-blue-600' : 'text-slate-400' ?>"><?= $icon ?></span>
                            <span><?= htmlspecialchars($category['name']); ?></span>
                        </div>
                        <span class="<?= $category['active'] ? 'text-blue-600 bg-blue-100' : 'text-slate-400 bg-slate-100' ?> text-[11px] font-bold px-2 py-0.5 rounded-md"><?= htmlspecialchars((string) $category['book_count']); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>

            <h3 class="text-[15px] font-bold text-slate-800 mb-4">Availability</h3>
            <form action="<?= htmlspecialchars(url('/books')); ?>" class="flex flex-col gap-3.5 mb-6" method="GET" id="mobile-availability-form">
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
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input
                            name="availability"
                            type="radio"
                            value="<?= htmlspecialchars($option['value']); ?>"
                            <?= $option['selected'] ? 'checked' : ''; ?>
                            class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500"
                            onchange="document.getElementById('mobile-availability-form').submit()"
                        >
                        <span class="text-[14px] <?= $option['selected'] ? 'text-slate-800 font-medium' : 'text-slate-600 group-hover:text-slate-800' ?>"><?= htmlspecialchars($option['label']); ?></span>
                    </label>
                <?php endforeach; ?>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggle = document.getElementById('mobile-filter-toggle');
            const close = document.getElementById('mobile-filter-close');
            const drawer = document.getElementById('mobile-filter-drawer');
            const backdrop = document.getElementById('mobile-filter-backdrop');

            if (toggle && close && drawer && backdrop) {
                const openFilter = () => {
                    drawer.classList.add('is-open');
                    backdrop.classList.add('is-open');
                    document.body.style.overflow = 'hidden';
                };

                const closeFilter = () => {
                    drawer.classList.remove('is-open');
                    backdrop.classList.remove('is-open');
                    document.body.style.overflow = '';
                };

                toggle.addEventListener('click', openFilter);
                close.addEventListener('click', closeFilter);
                backdrop.addEventListener('click', closeFilter);
            }
        });
    </script>
</div>
