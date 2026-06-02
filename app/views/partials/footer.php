<footer class="site-footer">
    <div class="container site-footer__inner">
        <div class="site-footer__brand">
            <span class="site-footer__title">LibManage</span>
            <p>&copy; <span data-year></span> LibManage. Institutional reliability for modern library operations.</p>
        </div>

        <div class="site-footer__links">
            <a href="<?= htmlspecialchars(url('/')); ?>">Home</a>
            <a href="<?= htmlspecialchars(url('/books')); ?>">Catalog</a>
            <a href="<?= htmlspecialchars(url(auth_check() ? (auth_is_admin() ? '/admin' : '/dashboard') : '/login')); ?>">
                <?= htmlspecialchars(auth_check() ? (auth_is_admin() ? 'Admin Panel' : 'Dashboard') : 'Login'); ?>
            </a>
        </div>
    </div>
</footer>
