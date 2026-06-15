<div class="space-y-8 pb-12">
    <!-- Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="<?= htmlspecialchars(url('/admin')); ?>" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 hover:bg-amber-100 hover:text-amber-600 flex items-center justify-center transition-colors">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                </a>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-slate-900">Loan Verification Panel</h1>
            </div>
            <p class="text-slate-500 max-w-2xl text-lg ml-11">Area utama admin untuk approve, reject, return, dan cek keterlambatan.</p>
        </div>
    </header>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <!-- Toolbar -->
        <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <button class="px-4 py-2 bg-amber-50 text-amber-700 font-semibold text-sm rounded-lg border border-amber-200">Pending</button>
                <button class="px-4 py-2 bg-white text-slate-600 font-medium text-sm rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">Active</button>
                <button class="px-4 py-2 bg-white text-slate-600 font-medium text-sm rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">Late</button>
                <button class="px-4 py-2 bg-white text-slate-600 font-medium text-sm rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">Returned</button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500">
                        <th class="px-6 py-4 font-semibold">User</th>
                        <th class="px-6 py-4 font-semibold">Book Title</th>
                        <th class="px-6 py-4 font-semibold">Loan Date</th>
                        <th class="px-6 py-4 font-semibold">Due Date</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <!-- Dummy Data Row -->
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">Sample User</div>
                            <div class="text-slate-500 text-xs mt-0.5">user@example.com</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-800">The Great Gatsby</div>
                            <div class="text-slate-500 text-xs mt-0.5">F. Scott Fitzgerald</div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">2026-03-25</td>
                        <td class="px-6 py-4 font-medium text-slate-800">2026-04-01</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                Pending
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white font-medium text-sm rounded-lg transition-colors border border-emerald-200 hover:border-emerald-600">
                                    <span class="material-symbols-outlined text-[16px]">check</span> Approve
                                </button>
                                <button class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-600 hover:text-white font-medium text-sm rounded-lg transition-colors border border-rose-200 hover:border-rose-600">
                                    <span class="material-symbols-outlined text-[16px]">close</span> Reject
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex items-center justify-between text-sm text-slate-500">
            <span>Showing <span class="font-bold text-slate-800">1</span> pending loan requests</span>
        </div>
    </div>
</div>

