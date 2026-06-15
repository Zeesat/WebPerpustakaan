<div class="space-y-8 pb-12">
    <!-- Header -->
    <header class="flex flex-col gap-2 relative z-10">
        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-slate-900">Admin Dashboard</h1>
        <p class="text-slate-500 max-w-2xl text-lg">Overview of library statistics, pending verifications, and system health.</p>
    </header>

    <!-- Top Statistics -->
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Book Titles -->
        <div class="group relative bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-blue-50/50 group-hover:bg-blue-100/50 transition-colors duration-500 blur-2xl"></div>
            <div class="relative flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold tracking-wider text-slate-500 uppercase mb-1">Book Titles</p>
                    <h2 class="text-4xl font-extrabold text-slate-900"><?= htmlspecialchars(number_format($totalBooks)); ?></h2>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-300">
                    <span class="material-symbols-outlined text-[28px]">library_books</span>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-50 flex items-center gap-2 text-sm text-slate-500">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span> <?= htmlspecialchars(number_format($availableTitles)); ?> available
                <span class="ml-2 w-2 h-2 rounded-full bg-rose-400"></span> <?= htmlspecialchars(number_format($outOfStockTitles)); ?> out
            </div>
        </div>

        <!-- Total Copies -->
        <div class="group relative bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-indigo-50/50 group-hover:bg-indigo-100/50 transition-colors duration-500 blur-2xl"></div>
            <div class="relative flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold tracking-wider text-slate-500 uppercase mb-1">Total Copies</p>
                    <h2 class="text-4xl font-extrabold text-slate-900"><?= htmlspecialchars(number_format($totalCopies)); ?></h2>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-300">
                    <span class="material-symbols-outlined text-[28px]">content_copy</span>
                </div>
            </div>
            <p class="mt-4 pt-4 border-t border-slate-50 text-sm text-slate-500">Across all titles in catalog</p>
        </div>

        <!-- Categories -->
        <div class="group relative bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-teal-50/50 group-hover:bg-teal-100/50 transition-colors duration-500 blur-2xl"></div>
            <div class="relative flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold tracking-wider text-slate-500 uppercase mb-1">Categories</p>
                    <h2 class="text-4xl font-extrabold text-slate-900"><?= htmlspecialchars(number_format($totalCategories)); ?></h2>
                </div>
                <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-300">
                    <span class="material-symbols-outlined text-[28px]">category</span>
                </div>
            </div>
            <p class="mt-4 pt-4 border-t border-slate-50 text-sm text-slate-500">Organizing the collection</p>
        </div>

        <!-- Users -->
        <div class="group relative bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-purple-50/50 group-hover:bg-purple-100/50 transition-colors duration-500 blur-2xl"></div>
            <div class="relative flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold tracking-wider text-slate-500 uppercase mb-1">Users</p>
                    <h2 class="text-4xl font-extrabold text-slate-900"><?= htmlspecialchars(number_format($totalUsers)); ?></h2>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-300">
                    <span class="material-symbols-outlined text-[28px]">group</span>
                </div>
            </div>
            <p class="mt-4 pt-4 border-t border-slate-50 text-sm text-slate-500">Active library members</p>
        </div>
    </section>

    <!-- Loans Statistics -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-100 p-6 rounded-2xl shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-full bg-amber-200/50 text-amber-600 flex items-center justify-center backdrop-blur-sm">
                <span class="material-symbols-outlined text-[32px]">pending_actions</span>
            </div>
            <div>
                <p class="text-amber-700 font-semibold tracking-wide text-sm uppercase">Pending Loans</p>
                <h3 class="text-3xl font-extrabold text-amber-900 mt-1"><?= htmlspecialchars(number_format($pendingLoansCount)); ?></h3>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-50 to-green-50 border border-emerald-100 p-6 rounded-2xl shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-full bg-emerald-200/50 text-emerald-600 flex items-center justify-center backdrop-blur-sm">
                <span class="material-symbols-outlined text-[32px]">check_circle</span>
            </div>
            <div>
                <p class="text-emerald-700 font-semibold tracking-wide text-sm uppercase">Active Loans</p>
                <h3 class="text-3xl font-extrabold text-emerald-900 mt-1"><?= htmlspecialchars(number_format($activeLoansCount)); ?></h3>
            </div>
        </div>

        <div class="bg-gradient-to-br from-rose-50 to-red-50 border border-rose-100 p-6 rounded-2xl shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-full bg-rose-200/50 text-rose-600 flex items-center justify-center backdrop-blur-sm">
                <span class="material-symbols-outlined text-[32px]">event_busy</span>
            </div>
            <div>
                <p class="text-rose-700 font-semibold tracking-wide text-sm uppercase">Late Returns</p>
                <h3 class="text-3xl font-extrabold text-rose-900 mt-1"><?= htmlspecialchars(number_format($lateLoansCount)); ?></h3>
            </div>
        </div>
    </section>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Pending Loans Table -->
        <div class="lg:col-span-2 space-y-6">
            <?php if ($pendingLoansCount > 0): ?>
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col h-full">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center">
                                <span class="material-symbols-outlined">notification_important</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800">Recent Pending Loans</h3>
                        </div>
                        <a href="<?= htmlspecialchars(url('/admin/loans')); ?>" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors flex items-center gap-1">
                            View All <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white border-b border-slate-100 text-sm uppercase tracking-wider text-slate-500">
                                    <th class="px-6 py-4 font-semibold">User</th>
                                    <th class="px-6 py-4 font-semibold">Loan Date</th>
                                    <th class="px-6 py-4 font-semibold">Due Date</th>
                                    <th class="px-6 py-4 font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 text-sm">
                                <?php foreach (array_slice($pendingLoans, 0, 5) as $loan): ?>
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-slate-900"><?= htmlspecialchars($loan['user_name']); ?></div>
                                            <div class="text-slate-500 text-xs mt-0.5"><?= htmlspecialchars($loan['user_email']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600"><?= htmlspecialchars($loan['loan_date']); ?></td>
                                        <td class="px-6 py-4 text-slate-600"><?= htmlspecialchars($loan['due_date']); ?></td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
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
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-12 flex flex-col items-center justify-center text-center h-full">
                    <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-500 mb-4">
                        <span class="material-symbols-outlined text-4xl">task_alt</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">All Caught Up!</h3>
                    <p class="text-slate-500 max-w-sm">There are no pending loan requests at the moment. Great job keeping the queue clear.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right Column: Quick Navigation -->
        <div class="space-y-4">
            <h3 class="text-lg font-bold text-slate-800 mb-2 px-1">Quick Actions</h3>
            
            <a href="<?= htmlspecialchars(url('/admin/books')); ?>" class="group block bg-white border border-slate-200 rounded-2xl p-5 hover:border-blue-300 hover:shadow-md transition-all duration-300 relative overflow-hidden">
                <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-8 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-300 pr-4">
                    <span class="material-symbols-outlined text-blue-500">chevron_right</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                        <span class="material-symbols-outlined">menu_book</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800">Manage Books</h4>
                        <p class="text-sm text-slate-500 mt-0.5">Add, edit, or remove titles.</p>
                    </div>
                </div>
            </a>

            <a href="<?= htmlspecialchars(url('/admin/categories')); ?>" class="group block bg-white border border-slate-200 rounded-2xl p-5 hover:border-teal-300 hover:shadow-md transition-all duration-300 relative overflow-hidden">
                <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-8 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-300 pr-4">
                    <span class="material-symbols-outlined text-teal-500">chevron_right</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center group-hover:bg-teal-600 group-hover:text-white transition-colors duration-300">
                        <span class="material-symbols-outlined">category</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800">Manage Categories</h4>
                        <p class="text-sm text-slate-500 mt-0.5">Organize book classifications.</p>
                    </div>
                </div>
            </a>

            <a href="<?= htmlspecialchars(url('/admin/users')); ?>" class="group block bg-white border border-slate-200 rounded-2xl p-5 hover:border-purple-300 hover:shadow-md transition-all duration-300 relative overflow-hidden">
                <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-8 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-300 pr-4">
                    <span class="material-symbols-outlined text-purple-500">chevron_right</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                        <span class="material-symbols-outlined">group</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800">Manage Users</h4>
                        <p class="text-sm text-slate-500 mt-0.5">View registered library members.</p>
                    </div>
                </div>
            </a>

            <a href="<?= htmlspecialchars(url('/admin/loans')); ?>" class="group block bg-white border border-slate-200 rounded-2xl p-5 hover:border-amber-300 hover:shadow-md transition-all duration-300 relative overflow-hidden">
                <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-8 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-300 pr-4">
                    <span class="material-symbols-outlined text-amber-500">chevron_right</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300">
                        <span class="material-symbols-outlined">fact_check</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800">Loan Verification</h4>
                        <p class="text-sm text-slate-500 mt-0.5">Approve, reject &amp; track loans.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

