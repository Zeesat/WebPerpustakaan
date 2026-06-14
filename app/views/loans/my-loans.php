<?php
$buildLoansUrl = static function (array $overrides = []) use ($filters): string {
    $query = [
        'tab' => $overrides['tab'] ?? $filters['tab'],
        'search' => $overrides['search'] ?? $filters['search'],
        'filter' => $overrides['filter'] ?? $filters['filter'],
        'sort' => $overrides['sort'] ?? $filters['sort'],
    ];

    $normalized = [];

    foreach ($query as $key => $value) {
        if ($value === null || $value === '') {
            continue;
        }

        if ($key === 'tab' && $value === 'current') {
            continue;
        }

        if ($key === 'filter' && $value === 'all') {
            continue;
        }

        if ($key === 'sort' && $value === 'latest') {
            continue;
        }

        $normalized[$key] = $value;
    }

    return url('/loans') . ($normalized === [] ? '' : '?' . http_build_query($normalized));
};

$summaryToneClasses = static function (string $tone): array {
    return match ($tone) {
        'emerald' => ['bg-emerald-50 text-emerald-600', 'text-emerald-600'],
        'orange' => ['bg-orange-50 text-orange-600', 'text-orange-600'],
        'violet' => ['bg-violet-50 text-violet-600', 'text-violet-600'],
        default => ['bg-blue-50 text-blue-600', 'text-blue-600'],
    };
};

$statusClasses = static function (string $tone): string {
    return match ($tone) {
        'yellow' => 'bg-amber-50 text-amber-700',
        'red' => 'bg-red-50 text-red-600',
        'blue' => 'bg-blue-50 text-blue-600',
        default => 'bg-emerald-50 text-emerald-600',
    };
};

$activeTabLabel = 'Current Loans';
foreach ($tabs as $tab) {
    if ($tab['selected']) {
        $activeTabLabel = $tab['label'];
        break;
    }
}

$emptyTitle = $activeTab === 'current' && ! $hasActiveLoans && ! $hasActiveFilters
    ? 'No Active Loans'
    : 'No loans found';
$emptyDescription = $activeTab === 'current' && ! $hasActiveLoans && ! $hasActiveFilters
    ? 'You currently do not have any borrowed books.'
    : 'Try adjusting your search, filter, or tab selection to find the loan records you need.';
?>

<div class="bg-[#f4f7fb] min-h-screen">
    <div class="max-w-[1240px] mx-auto px-6 py-10">
        <section class="mb-8">
            <h1 class="text-[34px] font-bold text-slate-900 tracking-tight mb-2">My Loans</h1>
            <p class="text-[16px] text-slate-500">Track your borrowed books, due dates, and loan history.</p>
        </section>

        <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
            <?php foreach ($summaryCards as $card): ?>
                <?php [$iconClasses, $unitClasses] = $summaryToneClasses($card['tone']); ?>
                <article class="bg-white rounded-xl border border-slate-100 shadow-sm p-6 flex items-center gap-5">
                    <div class="w-14 h-14 rounded-xl flex items-center justify-center <?= htmlspecialchars($iconClasses); ?>">
                        <span class="material-symbols-outlined text-[30px]"><?= htmlspecialchars($card['icon']); ?></span>
                    </div>
                    <div>
                        <p class="text-[14px] font-semibold text-slate-600 mb-1"><?= htmlspecialchars($card['label']); ?></p>
                        <p class="text-[28px] font-bold leading-none text-slate-900 mb-1.5"><?= htmlspecialchars((string) $card['value']); ?></p>
                        <p class="text-[13px] font-semibold <?= htmlspecialchars($unitClasses); ?>"><?= htmlspecialchars($card['unit']); ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>

        <section class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden mb-6">
            <div class="px-5 sm:px-6 pt-5 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <nav class="flex gap-8 overflow-x-auto" aria-label="Loan sections">
                    <?php foreach ($tabs as $tab): ?>
                        <a
                            class="whitespace-nowrap pb-4 border-b-[3px] text-[15px] font-semibold transition-colors <?= $tab['selected'] ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-800'; ?>"
                            href="<?= htmlspecialchars($buildLoansUrl(['tab' => $tab['value'], 'filter' => 'all'])); ?>"
                        >
                            <?= htmlspecialchars($tab['label']); ?>
                        </a>
                    <?php endforeach; ?>
                </nav>

                <form action="<?= htmlspecialchars(url('/loans')); ?>" class="grid grid-cols-1 lg:grid-cols-[minmax(220px,1fr)_160px_190px_auto] gap-3 w-full lg:w-auto" method="GET">
                    <?php if ($filters['tab'] !== 'current'): ?>
                        <input name="tab" type="hidden" value="<?= htmlspecialchars($filters['tab']); ?>">
                    <?php endif; ?>

                    <label class="relative block">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-slate-400">search</span>
                        <input
                            class="w-full h-11 rounded-lg border border-slate-200 bg-white pl-10 pr-3 text-[14px] text-slate-700 placeholder:text-slate-400 shadow-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
                            name="search"
                            type="search"
                            value="<?= htmlspecialchars($filters['search']); ?>"
                            placeholder="Search loans..."
                        >
                    </label>

                    <select
                        class="h-11 rounded-lg border border-slate-200 bg-white px-3 text-[14px] font-medium text-slate-600 shadow-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
                        name="filter"
                        onchange="this.form.submit()"
                    >
                        <?php foreach ($filterOptions as $option): ?>
                            <option value="<?= htmlspecialchars($option['value']); ?>" <?= $option['selected'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($option['label']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select
                        class="h-11 rounded-lg border border-slate-200 bg-white px-3 text-[14px] font-medium text-slate-600 shadow-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
                        name="sort"
                        onchange="this.form.submit()"
                    >
                        <?php foreach ($sortOptions as $option): ?>
                            <option value="<?= htmlspecialchars($option['value']); ?>" <?= $option['selected'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($option['label']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button class="h-11 rounded-lg bg-blue-600 px-5 text-[14px] font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors" type="submit">Search</button>
                </form>
            </div>

            <?php if ($hasActiveFilters): ?>
                <div class="px-5 sm:px-6 pb-4">
                    <a class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-[13px] font-semibold text-slate-600 hover:bg-slate-50 transition-colors" href="<?= htmlspecialchars($buildLoansUrl(['search' => '', 'filter' => 'all', 'sort' => 'latest'])); ?>">
                        <span class="material-symbols-outlined text-[17px]">close</span>
                        Reset filters
                    </a>
                </div>
            <?php endif; ?>

            <?php if ($rows === []): ?>
                <div class="px-6 py-14 text-center border-t border-slate-100">
                    <div class="w-16 h-16 mx-auto rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-5">
                        <span class="material-symbols-outlined text-[34px]">assignment</span>
                    </div>
                    <h2 class="text-[22px] font-bold text-slate-900 mb-2"><?= htmlspecialchars($emptyTitle); ?></h2>
                    <p class="text-[15px] text-slate-500 max-w-md mx-auto mb-6"><?= htmlspecialchars($emptyDescription); ?></p>
                    <a class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-[14px] font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors" href="<?= htmlspecialchars(url('/books')); ?>">Browse Books</a>
                </div>
            <?php else: ?>
                <div class="hidden md:block px-5 sm:px-6 pb-5">
                    <div class="overflow-x-auto rounded-lg border border-slate-200">
                        <table class="w-full min-w-[860px] border-collapse bg-white">
                            <thead>
                                <tr class="bg-slate-50 text-left">
                                    <?php if ($activeTab === 'current'): ?>
                                        <th class="px-4 py-3 text-[13px] font-bold text-slate-600">Book Cover</th>
                                        <th class="px-4 py-3 text-[13px] font-bold text-slate-600">Book Title</th>
                                        <th class="px-4 py-3 text-[13px] font-bold text-slate-600">Author</th>
                                        <th class="px-4 py-3 text-[13px] font-bold text-slate-600">Borrowed Date</th>
                                        <th class="px-4 py-3 text-[13px] font-bold text-slate-600">Due Date</th>
                                        <th class="px-4 py-3 text-[13px] font-bold text-slate-600">Status</th>
                                        <th class="px-4 py-3 text-[13px] font-bold text-slate-600">Actions</th>
                                    <?php elseif ($activeTab === 'history'): ?>
                                        <th class="px-4 py-3 text-[13px] font-bold text-slate-600">Book</th>
                                        <th class="px-4 py-3 text-[13px] font-bold text-slate-600">Borrow Date</th>
                                        <th class="px-4 py-3 text-[13px] font-bold text-slate-600">Return Date</th>
                                        <th class="px-4 py-3 text-[13px] font-bold text-slate-600">Loan Duration</th>
                                    <?php else: ?>
                                        <th class="px-4 py-3 text-[13px] font-bold text-slate-600">Book</th>
                                        <th class="px-4 py-3 text-[13px] font-bold text-slate-600">Borrow Date</th>
                                        <th class="px-4 py-3 text-[13px] font-bold text-slate-600">Return Date</th>
                                        <th class="px-4 py-3 text-[13px] font-bold text-slate-600">Status</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rows as $row): ?>
                                    <tr class="border-t border-slate-200 hover:bg-slate-50/60 transition-colors">
                                        <?php if ($activeTab === 'current'): ?>
                                            <td class="px-4 py-4">
                                                <div class="w-12 h-16 rounded-md bg-slate-100 flex-shrink-0 overflow-hidden shadow-sm flex items-center justify-center">
                                                    <?php if ($row['cover']['url'] !== null): ?>
                                                        <img src="<?= htmlspecialchars($row['cover']['url']); ?>" alt="Cover of <?= htmlspecialchars($row['book_title']); ?>" class="w-full h-full object-cover" loading="lazy">
                                                    <?php else: ?>
                                                        <span class="text-[13px] font-bold text-slate-400"><?= htmlspecialchars($row['cover']['initials']); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="min-w-[220px]">
                                                    <p class="text-[15px] font-bold text-slate-900 truncate"><?= htmlspecialchars($row['book_title']); ?></p>
                                                    <p class="text-[13px] text-slate-500">Loan #<?= htmlspecialchars(str_pad((string) $row['loan_id'], 4, '0', STR_PAD_LEFT)); ?></p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-[14px] text-slate-600"><?= htmlspecialchars($row['book_author']); ?></td>
                                            <td class="px-4 py-4 text-[14px] text-slate-600"><?= htmlspecialchars($row['borrowed_date']); ?></td>
                                            <td class="px-4 py-4">
                                                <p class="text-[14px] font-semibold text-slate-800"><?= htmlspecialchars($row['due_date']); ?></p>
                                                <p class="text-[12px] font-medium <?= $row['is_overdue'] ? 'text-red-600' : ($row['is_due_soon'] ? 'text-amber-600' : 'text-emerald-600'); ?>"><?= htmlspecialchars($row['due_hint']); ?></p>
                                            </td>
                                            <td class="px-4 py-4">
                                                <span class="inline-flex rounded-full px-3 py-1 text-[12px] font-bold <?= htmlspecialchars($statusClasses($row['status']['tone'])); ?>">
                                                    <?= htmlspecialchars($row['status']['label']); ?>
                                                </span>
                                            </td>
                                            <td class="px-4 py-4">
                                                <a class="inline-flex items-center justify-center rounded-lg border border-blue-200 px-4 py-2 text-[13px] font-semibold text-blue-600 hover:bg-blue-50 transition-colors" href="<?= htmlspecialchars($row['details_url']); ?>">View Details</a>
                                            </td>
                                        <?php elseif ($activeTab === 'history'): ?>
                                            <td class="px-4 py-4">
                                                <div class="flex items-center gap-4 min-w-[260px]">
                                                    <div class="w-12 h-16 rounded-md bg-slate-100 flex-shrink-0 overflow-hidden shadow-sm flex items-center justify-center">
                                                        <?php if ($row['cover']['url'] !== null): ?>
                                                            <img src="<?= htmlspecialchars($row['cover']['url']); ?>" alt="Cover of <?= htmlspecialchars($row['book_title']); ?>" class="w-full h-full object-cover" loading="lazy">
                                                        <?php else: ?>
                                                            <span class="text-[13px] font-bold text-slate-400"><?= htmlspecialchars($row['cover']['initials']); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="text-[15px] font-bold text-slate-900 truncate"><?= htmlspecialchars($row['book_title']); ?></p>
                                                        <p class="text-[13px] text-slate-500">Loan #<?= htmlspecialchars(str_pad((string) $row['loan_id'], 4, '0', STR_PAD_LEFT)); ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-[14px] text-slate-600"><?= htmlspecialchars($row['borrowed_date']); ?></td>
                                            <td class="px-4 py-4 text-[14px] text-slate-600"><?= htmlspecialchars($row['return_date']); ?></td>
                                            <td class="px-4 py-4 text-[14px] font-semibold text-slate-700"><?= htmlspecialchars($row['loan_duration']); ?></td>
                                        <?php else: ?>
                                            <td class="px-4 py-4">
                                                <div class="flex items-center gap-4 min-w-[260px]">
                                                    <div class="w-12 h-16 rounded-md bg-slate-100 flex-shrink-0 overflow-hidden shadow-sm flex items-center justify-center">
                                                        <?php if ($row['cover']['url'] !== null): ?>
                                                            <img src="<?= htmlspecialchars($row['cover']['url']); ?>" alt="Cover of <?= htmlspecialchars($row['book_title']); ?>" class="w-full h-full object-cover" loading="lazy">
                                                        <?php else: ?>
                                                            <span class="text-[13px] font-bold text-slate-400"><?= htmlspecialchars($row['cover']['initials']); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="text-[15px] font-bold text-slate-900 truncate"><?= htmlspecialchars($row['book_title']); ?></p>
                                                        <p class="text-[13px] text-slate-500">Loan #<?= htmlspecialchars(str_pad((string) $row['loan_id'], 4, '0', STR_PAD_LEFT)); ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-[14px] text-slate-600"><?= htmlspecialchars($row['borrowed_date']); ?></td>
                                            <td class="px-4 py-4 text-[14px] text-slate-600"><?= htmlspecialchars($row['return_date']); ?></td>
                                            <td class="px-4 py-4">
                                                <span class="inline-flex rounded-full px-3 py-1 text-[12px] font-bold <?= htmlspecialchars($statusClasses($row['status']['tone'])); ?>">
                                                    <?= htmlspecialchars($row['status']['label']); ?>
                                                </span>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <p class="mt-4 text-[14px] text-slate-500"><?= htmlspecialchars($resultSummary); ?></p>
                </div>

                <div class="md:hidden px-5 pb-5 space-y-4">
                    <?php foreach ($rows as $row): ?>
                        <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="flex gap-4">
                                <div class="w-14 h-20 rounded-md bg-slate-100 flex-shrink-0 overflow-hidden shadow-sm flex items-center justify-center">
                                    <?php if ($row['cover']['url'] !== null): ?>
                                        <img src="<?= htmlspecialchars($row['cover']['url']); ?>" alt="Cover of <?= htmlspecialchars($row['book_title']); ?>" class="w-full h-full object-cover" loading="lazy">
                                    <?php else: ?>
                                        <span class="text-[13px] font-bold text-slate-400"><?= htmlspecialchars($row['cover']['initials']); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <h2 class="text-[15px] font-bold text-slate-900 leading-snug"><?= htmlspecialchars($row['book_title']); ?></h2>
                                            <p class="text-[13px] text-slate-500"><?= htmlspecialchars($row['book_author']); ?></p>
                                        </div>
                                        <span class="inline-flex flex-shrink-0 rounded-full px-2.5 py-1 text-[11px] font-bold <?= htmlspecialchars($statusClasses($row['status']['tone'])); ?>">
                                            <?= htmlspecialchars($row['status']['label']); ?>
                                        </span>
                                    </div>

                                    <dl class="grid grid-cols-2 gap-3 mt-4">
                                        <div>
                                            <dt class="text-[11px] font-bold uppercase tracking-[0.04em] text-slate-400">Borrowed</dt>
                                            <dd class="text-[13px] font-semibold text-slate-700"><?= htmlspecialchars($row['borrowed_date']); ?></dd>
                                        </div>
                                        <div>
                                            <dt class="text-[11px] font-bold uppercase tracking-[0.04em] text-slate-400"><?= $activeTab === 'current' ? 'Due' : 'Returned'; ?></dt>
                                            <dd class="text-[13px] font-semibold text-slate-700"><?= htmlspecialchars($activeTab === 'current' ? $row['due_date'] : $row['return_date']); ?></dd>
                                        </div>
                                    </dl>

                                    <?php if ($activeTab === 'current'): ?>
                                        <p class="mt-3 text-[12px] font-semibold <?= $row['is_overdue'] ? 'text-red-600' : ($row['is_due_soon'] ? 'text-amber-600' : 'text-emerald-600'); ?>"><?= htmlspecialchars($row['due_hint']); ?></p>
                                        <a class="mt-4 inline-flex w-full items-center justify-center rounded-lg border border-blue-200 px-4 py-2 text-[13px] font-semibold text-blue-600 hover:bg-blue-50 transition-colors" href="<?= htmlspecialchars($row['details_url']); ?>">View Details</a>
                                    <?php elseif ($activeTab === 'history'): ?>
                                        <p class="mt-3 text-[13px] font-semibold text-slate-600">Duration: <?= htmlspecialchars($row['loan_duration']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                    <p class="text-[14px] text-slate-500"><?= htmlspecialchars($resultSummary); ?></p>
                </div>
            <?php endif; ?>
        </section>

        <section class="rounded-xl border border-blue-100 bg-blue-50 px-6 py-5 flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[22px]">info</span>
            </div>
            <div>
                <h2 class="text-[16px] font-bold text-slate-900 mb-1">Important Reminders</h2>
                <p class="text-[14px] text-slate-600">Please return or renew your books before the due date to avoid penalties.</p>
            </div>
        </section>
    </div>
</div>
