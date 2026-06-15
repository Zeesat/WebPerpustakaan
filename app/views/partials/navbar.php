<?php
    $isCatalogPage = is_current_path('/books') || is_current_path('/books/show');
    $isMyLoansPage = is_current_path('/loans') || is_current_path('/loans/my');
?>
<header class="site-nav">
    <div class="container site-nav__inner">
        <a class="site-nav__brand" href="<?= htmlspecialchars(url('/')); ?>">
            <span class="site-nav__brand-mark">L</span>
            <span>
                <span class="site-nav__brand-title">LibManage</span>
                <span class="site-nav__brand-subtitle">Library Loan Management</span>
            </span>
        </a>

        <nav class="site-nav__links" aria-label="Primary navigation">
            <a class="site-nav__link<?= is_current_path('/') ? ' is-active' : ''; ?>" href="<?= htmlspecialchars(url('/')); ?>">Home</a>
            <a class="site-nav__link<?= $isCatalogPage ? ' is-active' : ''; ?>" href="<?= htmlspecialchars(url('/books')); ?>">Catalog</a>
            <?php if (auth_check()): ?>
                <a class="site-nav__link<?= $isMyLoansPage ? ' is-active' : ''; ?>" href="<?= htmlspecialchars(url('/loans')); ?>">My Loans</a>
            <?php endif; ?>
        </nav>

        <div class="site-nav__actions">
            <?php if (auth_check()): ?>
                <a class="site-nav__button site-nav__button--ghost" href="<?= htmlspecialchars(url(auth_is_admin() ? '/admin' : '/books')); ?>">
                    <?= htmlspecialchars(auth_is_admin() ? 'Admin Panel' : 'Books'); ?>
                </a>
                <form action="<?= htmlspecialchars(url('/logout')); ?>" class="inline-form" method="POST">
                    <?= csrf_field(); ?>
                    <button class="site-nav__button site-nav__button--primary" type="submit">Logout</button>
                </form>
            <?php else: ?>
                <a class="site-nav__button site-nav__button--ghost" href="<?= htmlspecialchars(url('/login')); ?>">Login</a>
                <a class="site-nav__button site-nav__button--primary" href="<?= htmlspecialchars(url('/register')); ?>">Get Started</a>
            <?php endif; ?>
        </div>
    </div>
</header>
