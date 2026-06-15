<?php
    $isHome = is_current_path('/');
    $isCatalog = is_current_path('/books') || is_current_path('/books/show');
    $isMyLoans = is_current_path('/loans') || is_current_path('/loans/my');
    $isBasket = is_current_path('/loans/request');
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

        <div class="flex items-center gap-2 sm:gap-4 md:gap-6">
            <?php if (auth_check()): ?>
                <?php if (! auth_is_admin()): ?>
                    <a
                        class="basket-nav<?= $isBasket ? ' is-active' : ''; ?>"
                        data-basket-nav
                        data-empty-label="Borrowing List empty"
                        data-filled-label="Borrowing List ready"
                        href="<?= htmlspecialchars(url('/loans/request')); ?>"
                    >
                        <span class="material-symbols-outlined basket-nav__icon">book</span>
                        <span class="basket-nav__text hidden sm:block"></span>
                        <span class="basket-nav__badge" data-basket-count hidden>0</span>
                    </a>
                <?php endif; ?>

                <!-- Notification bell -->
                <button class="hidden md:flex relative text-slate-500 hover:text-slate-800 transition-colors p-1 border border-slate-200 rounded-lg bg-white shadow-sm">
                    <span class="material-symbols-outlined text-[22px]">notifications</span>
                </button>

                <!-- User dropdown trigger -->
                <div class="hidden md:block relative isolate overflow-visible" id="user-dropdown-wrapper" data-profile-dropdown>
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
                        class="absolute right-0 top-full mt-2 w-64 origin-top-right rounded-2xl border border-slate-100 bg-white p-2 shadow-xl ring-1 ring-black/5 focus:outline-none"
                        role="menu"
                        aria-hidden="true"
                        hidden
                    >
                        <div class="px-3 py-2">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Account</p>
                            <p class="mt-1 truncate text-sm font-medium text-slate-900"><?= htmlspecialchars(auth_user()['email'] ?? ''); ?></p>
                        </div>
                        <div class="my-1 h-px bg-slate-100"></div>
                        <a class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900" href="<?= htmlspecialchars(url(auth_is_admin() ? '/admin/dashboard' : '/books')); ?>" role="menuitem">
                            <span class="material-symbols-outlined text-[20px]">grid_view</span>
                            <?= htmlspecialchars(auth_is_admin() ? 'Admin Dashboard' : 'Books'); ?>
                        </a>
                        <?php if (! auth_is_admin()): ?>
                        <a class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900" href="<?= htmlspecialchars(url('/loans')); ?>" role="menuitem">
                            <span class="material-symbols-outlined text-[20px]">book</span>
                            My Loans
                        </a>
                        <?php endif; ?>
                        <div class="my-1 h-px bg-slate-100"></div>
                        <form action="<?= htmlspecialchars(url('/logout')); ?>" method="POST" class="m-0">
                            <?= csrf_field(); ?>
                            <button class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-medium text-red-600 transition hover:bg-red-50 cursor-pointer border-none bg-transparent" type="submit" role="menuitem">
                                <span class="material-symbols-outlined text-[20px]">logout</span>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="hidden md:flex items-center gap-3">
                    <a class="rounded-full px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 hover:text-slate-900" href="<?= htmlspecialchars(url('/login')); ?>">Sign In</a>
                    <a class="rounded-full bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-blue-600/20 transition hover:bg-blue-700" href="<?= htmlspecialchars(url('/register')); ?>">Create Account</a>
                </div>
            <?php endif; ?>

            <!-- Mobile menu toggle -->
            <button class="md:hidden flex h-10 w-10 items-center justify-center rounded-full text-slate-500 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 border-none bg-transparent cursor-pointer" id="mobile-menu-toggle" type="button" aria-label="Toggle menu">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>
    </div>
</header>

<!-- Mobile Menu Drawer -->
<div class="mobile-menu-backdrop" id="mobile-menu-backdrop" aria-hidden="true"></div>
<div class="mobile-menu-drawer" id="mobile-menu-drawer" role="dialog" aria-modal="true" aria-label="Mobile Navigation">
    <div class="mobile-menu-drawer__header">
        <span class="font-bold text-slate-900 text-[18px] tracking-tight">LibManage</span>
        <button class="flex h-10 w-10 items-center justify-center rounded-full text-slate-500 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 border-none bg-transparent cursor-pointer" id="mobile-menu-close" type="button" aria-label="Close menu">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>
    
    <div class="mobile-menu-drawer__content">
        <nav class="mobile-menu-drawer__nav">
            <a class="mobile-menu-drawer__link <?= $isHome ? 'bg-blue-50 text-blue-700' : ''; ?>" href="<?= htmlspecialchars(url('/')); ?>">
                <span class="material-symbols-outlined">home</span>
                Home
            </a>
            <a class="mobile-menu-drawer__link <?= $isCatalog ? 'bg-blue-50 text-blue-700' : ''; ?>" href="<?= htmlspecialchars(url('/books')); ?>">
                <span class="material-symbols-outlined">library_books</span>
                Catalog
            </a>
            <?php if (auth_check() && !auth_is_admin()): ?>
            <a class="mobile-menu-drawer__link <?= $isMyLoans ? 'bg-blue-50 text-blue-700' : ''; ?>" href="<?= htmlspecialchars(url('/loans')); ?>">
                <span class="material-symbols-outlined">book</span>
                My Loans
            </a>
            <?php endif; ?>
        </nav>
    </div>

    <div class="mobile-menu-drawer__footer">
        <?php if (auth_check()): ?>
            <div class="flex items-center gap-3 mb-6">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-[14px] font-bold text-blue-700">
                    <?= htmlspecialchars(substr(auth_user()['name'] ?? 'U', 0, 1)); ?>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[14px] font-bold text-slate-700 leading-tight truncate">
                        <?= htmlspecialchars(auth_user()['name'] ?? 'User'); ?>
                    </p>
                    <p class="text-[12px] font-medium text-slate-500 leading-tight mt-0.5 truncate">
                        <?= htmlspecialchars(auth_user()['email'] ?? ''); ?>
                    </p>
                </div>
            </div>
            
            <!-- Dashboard/Admin Link -->
            <a class="flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[14px] font-semibold text-slate-700 mb-3 hover:bg-slate-50 transition-colors" href="<?= htmlspecialchars(url(auth_is_admin() ? '/admin/dashboard' : '/books')); ?>">
                <span class="material-symbols-outlined text-[20px]">grid_view</span>
                <?= htmlspecialchars(auth_is_admin() ? 'Admin Dashboard' : 'Books'); ?>
            </a>
            
            <form action="<?= htmlspecialchars(url('/logout')); ?>" method="POST" class="m-0">
                <?= csrf_field(); ?>
                <button class="flex w-full items-center justify-center gap-2 rounded-xl bg-slate-100 px-4 py-2.5 text-[14px] font-semibold text-red-600 transition-colors hover:bg-red-50 border-none cursor-pointer" type="submit">
                    <span class="material-symbols-outlined text-[18px]">logout</span>
                    Sign Out
                </button>
            </form>
        <?php else: ?>
            <div class="flex flex-col gap-3">
                <a class="flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-[14px] font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors" href="<?= htmlspecialchars(url('/register')); ?>">Create Account</a>
                <a class="flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-3 text-[14px] font-semibold text-slate-700 hover:bg-slate-50 transition-colors" href="<?= htmlspecialchars(url('/login')); ?>">Sign In</a>
            </div>
        <?php endif; ?>
    </div>
</div>
