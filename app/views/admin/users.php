<div class="space-y-8 pb-12">
    <!-- Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="<?= htmlspecialchars(url('/admin')); ?>" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 hover:bg-purple-100 hover:text-purple-600 flex items-center justify-center transition-colors">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                </a>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-slate-900">Manage Users</h1>
            </div>
            <p class="text-slate-500 max-w-2xl text-lg ml-11">Area monitoring user dan role yang terdaftar pada sistem.</p>
        </div>
        <div class="flex items-center gap-2">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">search</span>
                <input type="text" placeholder="Search users..." class="pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-200 outline-none w-64 shadow-sm">
            </div>
        </div>
    </header>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500">
                        <th class="px-6 py-4 font-semibold">User Details</th>
                        <th class="px-6 py-4 font-semibold">Role</th>
                        <th class="px-6 py-4 font-semibold">Registered</th>
                        <th class="px-6 py-4 font-semibold text-center">Active Loans</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <!-- Dummy Data Row 1 -->
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center font-bold text-lg">
                                J
                            </div>
                            <div>
                                <div class="font-bold text-slate-900">John Doe</div>
                                <div class="text-slate-500 text-xs mt-0.5">john.doe@example.com</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-1 rounded text-[10px] font-bold bg-slate-100 text-slate-600 uppercase tracking-wide">
                                User
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600">Oct 12, 2025</td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-bold text-slate-700">2</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                                <button class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:bg-purple-50 hover:text-purple-600 transition-colors" title="View Profile">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </button>
                                <button class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:bg-rose-50 hover:text-rose-600 transition-colors" title="Block User">
                                    <span class="material-symbols-outlined text-[18px]">block</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Dummy Data Row 2 -->
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold text-lg">
                                A
                            </div>
                            <div>
                                <div class="font-bold text-slate-900">Admin Master</div>
                                <div class="text-slate-500 text-xs mt-0.5">admin@example.com</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-1 rounded text-[10px] font-bold bg-blue-100 text-blue-700 uppercase tracking-wide border border-blue-200">
                                Admin
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600">Jan 1, 2025</td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-bold text-slate-700">-</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                                <button class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:bg-purple-50 hover:text-purple-600 transition-colors" title="View Profile">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex items-center justify-between text-sm text-slate-500">
            <span>Showing <span class="font-bold text-slate-800">2</span> registered users</span>
        </div>
    </div>
</div>

