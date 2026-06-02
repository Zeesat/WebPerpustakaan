<header class="site-header">
    <div class="container">
        <a class="brand" href="/">ShelfFlow</a>

        <nav class="nav">
            <a href="/">Home</a>
            <a href="/books">Books</a>
            <?php if (auth_check()): ?>
                <a href="/dashboard">Dashboard</a>
                <?php if (auth_is_admin()): ?>
                    <a href="/admin">Admin</a>
                <?php endif; ?>
                <span class="nav-user">Hi, <?= htmlspecialchars((string) (auth_user()['name'] ?? 'Reader')); ?></span>
                <form action="/logout" method="POST" class="inline-form">
                    <?= csrf_field(); ?>
                    <button type="submit" class="btn btn-secondary">Logout</button>
                </form>
            <?php else: ?>
                <a href="/login" class="btn btn-secondary">Login</a>
                <a href="/register" class="btn btn-primary">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

