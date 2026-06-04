<header class="bg-white border-b border-gray-200 sticky top-0 z-50 font-inter antialiased">
    <div class="max-w-[1240px] mx-auto flex justify-between items-center h-20 px-6">
        <div class="flex items-center gap-3">
            <div class="w-[42px] h-[42px] rounded-lg bg-[#002c78] text-white flex items-center justify-center shadow-sm">
                <span class="material-symbols-outlined text-2xl">menu_book</span>
            </div>
            <div class="flex flex-col">
                <span class="text-lg font-bold text-[#0f172a] leading-tight">LibManage</span>
                <span class="text-[10px] text-slate-500 uppercase tracking-[0.08em] leading-tight mt-0.5">Library Loan Management System</span>
            </div>
        </div>

        <nav class="hidden md:flex items-center gap-10">
            <a class="text-slate-600 hover:text-blue-700 font-medium text-[15px]" href="#">Home</a>
            <a class="text-slate-600 hover:text-blue-700 font-medium text-[15px]" href="#">My Loans</a>
            <a class="text-blue-600 border-b-[3px] border-blue-600 pb-1 font-semibold text-[15px] pt-1" href="/books">Browse Books</a>
            <a class="text-slate-600 hover:text-blue-700 font-medium text-[15px]" href="#">About</a>
        </nav>

        <div class="flex items-center gap-6">
            <button class="relative text-slate-500 hover:text-slate-800 transition-colors p-1 border border-slate-200 rounded-lg bg-white shadow-sm">
                <span class="material-symbols-outlined text-[22px]">notifications</span>
                <span class="absolute -top-1.5 -right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-blue-600 text-[9px] font-bold text-white shadow-sm ring-2 ring-white">2</span>
            </button>
            <div class="flex items-center gap-3 border-l border-slate-200 pl-6 cursor-pointer hover:bg-slate-50 p-1.5 -m-1.5 rounded-lg transition-colors">
                <div class="w-[34px] h-[34px] rounded-full bg-slate-100 flex items-center justify-center text-slate-500 border border-slate-200">
                    <span class="material-symbols-outlined text-xl">person</span>
                </div>
                <div class="hidden sm:flex items-center gap-1.5 text-[14px] font-medium text-slate-700">
                    <span>Hi, John Doe</span>
                    <span class="material-symbols-outlined text-[18px] text-slate-400">expand_more</span>
                </div>
            </div>
        </div>
    </div>
</header>
