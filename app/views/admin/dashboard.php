<div class="space-y-4 pb-8">
    <!-- Header -->
    <header class="flex flex-col gap-1 relative z-10">
        <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900">Admin Dashboard</h1>
    </header>

    <!-- Top Statistics -->
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Book Titles -->
        <div class="group relative bg-white p-4 rounded-xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 rounded-full bg-blue-50/50 group-hover:bg-blue-100/50 transition-colors duration-500 blur-2xl"></div>
            <div class="relative flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold tracking-wider text-slate-500 uppercase mb-1">Book Titles</p>
                    <h2 class="text-2xl font-extrabold text-slate-900"><?= htmlspecialchars(number_format($totalBooks)); ?></h2>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-300">
                    <span class="material-symbols-outlined text-[20px]">library_books</span>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-50 flex items-center gap-2 text-xs text-slate-500">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> <?= htmlspecialchars(number_format($availableTitles)); ?> avl
                <span class="ml-2 w-1.5 h-1.5 rounded-full bg-rose-400"></span> <?= htmlspecialchars(number_format($outOfStockTitles)); ?> out
            </div>
        </div>

        <!-- Total Copies -->
        <div class="group relative bg-white p-4 rounded-xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 rounded-full bg-indigo-50/50 group-hover:bg-indigo-100/50 transition-colors duration-500 blur-2xl"></div>
            <div class="relative flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold tracking-wider text-slate-500 uppercase mb-1">Total Copies</p>
                    <h2 class="text-2xl font-extrabold text-slate-900"><?= htmlspecialchars(number_format($totalCopies)); ?></h2>
                </div>
                <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-300">
                    <span class="material-symbols-outlined text-[20px]">content_copy</span>
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="group relative bg-white p-4 rounded-xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 rounded-full bg-teal-50/50 group-hover:bg-teal-100/50 transition-colors duration-500 blur-2xl"></div>
            <div class="relative flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold tracking-wider text-slate-500 uppercase mb-1">Categories</p>
                    <h2 class="text-2xl font-extrabold text-slate-900"><?= htmlspecialchars(number_format($totalCategories)); ?></h2>
                </div>
                <div class="w-10 h-10 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-300">
                    <span class="material-symbols-outlined text-[20px]">category</span>
                </div>
            </div>
        </div>

        <!-- Users -->
        <div class="group relative bg-white p-4 rounded-xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 rounded-full bg-purple-50/50 group-hover:bg-purple-100/50 transition-colors duration-500 blur-2xl"></div>
            <div class="relative flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold tracking-wider text-slate-500 uppercase mb-1">Users</p>
                    <h2 class="text-2xl font-extrabold text-slate-900"><?= htmlspecialchars(number_format($totalUsers)); ?></h2>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-300">
                    <span class="material-symbols-outlined text-[20px]">group</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Loans Statistics -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-100 p-4 rounded-xl shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-amber-200/50 text-amber-600 flex items-center justify-center backdrop-blur-sm">
                <span class="material-symbols-outlined text-[20px]">pending_actions</span>
            </div>
            <div>
                <p class="text-amber-700 font-semibold tracking-wide text-xs uppercase">Pending Loans</p>
                <h3 class="text-2xl font-extrabold text-amber-900 mt-0.5"><?= htmlspecialchars(number_format($pendingLoansCount)); ?></h3>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-50 to-green-50 border border-emerald-100 p-4 rounded-xl shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-emerald-200/50 text-emerald-600 flex items-center justify-center backdrop-blur-sm">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
            </div>
            <div>
                <p class="text-emerald-700 font-semibold tracking-wide text-xs uppercase">Active Loans</p>
                <h3 class="text-2xl font-extrabold text-emerald-900 mt-0.5"><?= htmlspecialchars(number_format($activeLoansCount)); ?></h3>
            </div>
        </div>

        <div class="bg-gradient-to-br from-rose-50 to-red-50 border border-rose-100 p-4 rounded-xl shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-rose-200/50 text-rose-600 flex items-center justify-center backdrop-blur-sm">
                <span class="material-symbols-outlined text-[20px]">event_busy</span>
            </div>
            <div>
                <p class="text-rose-700 font-semibold tracking-wide text-xs uppercase">Late Returns</p>
                <h3 class="text-2xl font-extrabold text-rose-900 mt-0.5"><?= htmlspecialchars(number_format($lateLoansCount)); ?></h3>
            </div>
        </div>
    </section>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Pending Loans Table -->
        <div class="lg:col-span-2 space-y-4">
            <?php if ($pendingLoansCount > 0): ?>
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden flex flex-col h-full">
                    <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[16px]">notification_important</span>
                            </div>
                            <h3 class="text-base font-bold text-slate-800">Recent Pending Loans</h3>
                        </div>
                        <a href="<?= htmlspecialchars(url('/admin/loans')); ?>" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors flex items-center gap-0.5">
                            View All <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                        </a>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500">
                                    <th class="px-4 py-3 font-semibold">User</th>
                                    <th class="px-4 py-3 font-semibold">Loan Date</th>
                                    <th class="px-4 py-3 font-semibold">Due Date</th>
                                    <th class="px-4 py-3 font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 text-sm">
                                <?php foreach (array_slice($pendingLoans, 0, 5) as $loan): ?>
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-slate-900"><?= htmlspecialchars($loan['user_name']); ?></div>
                                            <div class="text-slate-500 text-xs mt-0.5"><?= htmlspecialchars($loan['user_email']); ?></div>
                                        </td>
                                        <td class="px-4 py-3 text-slate-600"><?= htmlspecialchars($loan['loan_date']); ?></td>
                                        <td class="px-4 py-3 text-slate-600"><?= htmlspecialchars($loan['due_date']); ?></td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                                Pending
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-8 flex flex-col items-center justify-center text-center h-full">
                    <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-500 mb-2">
                        <span class="material-symbols-outlined text-2xl">task_alt</span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">All Caught Up!</h3>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right Column: Quick Navigation -->
        <div class="space-y-3">
            <h3 class="text-base font-bold text-slate-800 mb-1 px-1">Quick Actions</h3>
            
            <a href="<?= htmlspecialchars(url('/admin/books')); ?>" class="group block bg-white border border-slate-200 rounded-xl p-3 hover:border-blue-300 hover:shadow-sm transition-all duration-300 relative overflow-hidden">
                <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-8 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-300 pr-3">
                    <span class="material-symbols-outlined text-blue-500 text-[20px]">chevron_right</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                        <span class="material-symbols-outlined text-[20px]">menu_book</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-800">Manage Books</h4>
                    </div>
                </div>
            </a>

            <a href="<?= htmlspecialchars(url('/admin/categories')); ?>" class="group block bg-white border border-slate-200 rounded-xl p-3 hover:border-teal-300 hover:shadow-sm transition-all duration-300 relative overflow-hidden">
                <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-8 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-300 pr-3">
                    <span class="material-symbols-outlined text-teal-500 text-[20px]">chevron_right</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center group-hover:bg-teal-600 group-hover:text-white transition-colors duration-300">
                        <span class="material-symbols-outlined text-[20px]">category</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-800">Manage Categories</h4>
                    </div>
                </div>
            </a>

            <a href="<?= htmlspecialchars(url('/admin/users')); ?>" class="group block bg-white border border-slate-200 rounded-xl p-3 hover:border-purple-300 hover:shadow-sm transition-all duration-300 relative overflow-hidden">
                <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-8 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-300 pr-3">
                    <span class="material-symbols-outlined text-purple-500 text-[20px]">chevron_right</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                        <span class="material-symbols-outlined text-[20px]">group</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-800">Manage Users</h4>
                    </div>
                </div>
            </a>

            <a href="<?= htmlspecialchars(url('/admin/loans')); ?>" class="group block bg-white border border-slate-200 rounded-xl p-3 hover:border-amber-300 hover:shadow-sm transition-all duration-300 relative overflow-hidden">
                <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-8 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-300 pr-3">
                    <span class="material-symbols-outlined text-amber-500 text-[20px]">chevron_right</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300">
                        <span class="material-symbols-outlined text-[20px]">fact_check</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-800">Loan Verification</h4>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

