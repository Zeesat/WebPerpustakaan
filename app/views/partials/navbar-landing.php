<?php
    $isHome = is_current_path('/');
    $isCatalog = is_current_path('/books') || is_current_path('/books/show');
    $isMyLoans = is_current_path('/loans') || is_current_path('/loans/my');
    $navLinkBase = 'text-slate-600 hover:text-blue-700 font-medium text-[15px] transition-colors duration-150';
    $navLinkActive = 'text-blue-600 border-b-[3px] border-blue-600 pb-1 font-semibold text-[15px] pt-1';
?>
<header class="bg-white border-b border-gray-200 sticky top-0 z-[1000] overflow-visible font-inter antialiased">
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
            <a class="<?= $isHome ? $navLinkActive : $navLinkBase; ?>" href="<?= htmlspecialchars(url('/')); ?>">Home</a>
            <?php if (auth_check()): ?>
                <a class="<?= $isMyLoans ? $navLinkActive : $navLinkBase; ?>" href="<?= htmlspecialchars(url('/loans')); ?>">My Loans</a>
            <?php endif; ?>
            <a class="<?= $isCatalog ? $navLinkActive : $navLinkBase; ?>" href="<?= htmlspecialchars(url('/books')); ?>">Browse Books</a>
        </nav>

        <div class="flex items-center gap-6">
            <?php if (auth_check()): ?>
                <!-- Notification bell -->
                <button class="relative text-slate-500 hover:text-slate-800 transition-colors p-1 border border-slate-200 rounded-lg bg-white shadow-sm">
                    <span class="material-symbols-outlined text-[22px]">notifications</span>
                </button>

                <!-- User dropdown trigger -->
                <div class="relative isolate overflow-visible" id="user-dropdown-wrapper" data-profile-dropdown>
                    <button
                        id="user-dropdown-trigger"
                        data-profile-dropdown-trigger
                        type="button"
                        class="flex items-center gap-3 border-l border-slate-200 pl-6 cursor-pointer hover:bg-slate-50 p-1.5 -m-1.5 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500/30"
                        aria-haspopup="true"
                        aria-expanded="false"
                        aria-controls="user-dropdown-menu"
                    >
                        <div class="w-[34px] h-[34px] rounded-full bg-slate-100 flex items-center justify-center text-slate-500 border border-slate-200">
                            <span class="material-symbols-outlined text-xl">person</span>
                        </div>
                        <div class="hidden sm:flex items-center gap-1.5 text-[14px] font-medium text-slate-700">
                            <span>Hi, <?= htmlspecialchars(auth_user()['name'] ?? 'User'); ?></span>
                            <span class="material-symbols-outlined text-[18px] text-slate-400 transition-transform duration-200" id="dropdown-chevron">expand_more</span>
                        </div>
                    </button>

                    <!-- Dropdown menu -->
                    <div
                        id="user-dropdown-menu"
                        data-profile-dropdown-menu
                        class="absolute right-0 top-full mt-2 w-52 rounded-xl border border-slate-200 bg-white py-1.5 shadow-lg z-[9999]"
                        role="menu"
                        aria-hidden="true"
                        hidden
                    >
                        <div class="px-4 py-2.5 border-b border-slate-100">
                            <p class="text-[13px] font-semibold text-slate-800 truncate"><?= htmlspecialchars(auth_user()['name'] ?? 'User'); ?></p>
                            <p class="text-[11px] text-slate-500 truncate"><?= htmlspecialchars(auth_user()['email'] ?? ''); ?></p>
                        </div>

                        <?php if (auth_is_admin()): ?>
                            <a href="<?= htmlspecialchars(url('/admin')); ?>" class="flex items-center gap-3 px-4 py-2.5 text-[13px] text-slate-700 hover:bg-slate-50 transition-colors" role="menuitem">
                                <span class="material-symbols-outlined text-[18px] text-slate-400">admin_panel_settings</span>
                                Admin Panel
                            </a>
                        <?php endif; ?>

                        <form action="<?= htmlspecialchars(url('/logout')); ?>" method="POST" class="m-0">
                            <?= csrf_field(); ?>
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-[13px] text-red-600 hover:bg-red-50 transition-colors border-none bg-transparent cursor-pointer text-left" role="menuitem">
                                <span class="material-symbols-outlined text-[18px]">logout</span>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <!-- Guest: Login button -->
                <a href="<?= htmlspecialchars(url('/login')); ?>" class="flex items-center gap-2 px-5 py-2.5 rounded-lg bg-[#002c78] text-white font-semibold text-[14px] shadow-sm hover:bg-[#001849] active:scale-95 transition-all duration-150">
                    <span class="material-symbols-outlined text-[18px]">login</span>
                    Login
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>
