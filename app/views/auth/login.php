<?php $errors = is_array($errors ?? null) ? $errors : []; ?>
<section class="auth-shell">
    <div class="auth-hero">
        <p class="eyebrow">Member Access</p>
        <h1 class="page-title">Sign in to manage your library journey.</h1>
        <p class="page-subtitle">
            Access your borrowing dashboard, track approvals, and continue where you left off.
        </p>
        <div class="auth-hero-card">
            <strong>What you can do after login</strong>
            <ul class="auth-feature-list">
                <li>Review current and historical loan status</li>
                <li>Request books from the catalog with verification workflow</li>
                <li>Access librarian tools if your account has admin privileges</li>
            </ul>
        </div>
    </div>

    <section class="panel auth-panel">
        <div class="auth-panel-header">
            <h2>Login</h2>
            <p class="meta">Use the same account for both user and admin access.</p>
        </div>

        <?php if (! empty($errors['general'])): ?>
            <div class="form-alert form-alert-error" role="alert">
                <?= htmlspecialchars((string) $errors['general']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/login" class="auth-form" data-loading-form>
            <?= csrf_field(); ?>
            <div class="form-field">
                <label for="email">Email Address</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="<?= htmlspecialchars((string) old('email')); ?>"
                    autocomplete="email"
                    required
                >
                <?php if (! empty($errors['email'])): ?>
                    <p class="field-error"><?= htmlspecialchars((string) $errors['email']); ?></p>
                <?php endif; ?>
            </div>

            <div class="form-field">
                <div class="form-label-row">
                    <label for="password">Password</label>
                    <a href="/forgot-password">Forgot password?</a>
                </div>
                <input
                    id="password"
                    name="password"
                    type="password"
                    autocomplete="current-password"
                    required
                >
                <?php if (! empty($errors['password'])): ?>
                    <p class="field-error"><?= htmlspecialchars((string) $errors['password']); ?></p>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary btn-block" data-loading-text="Signing in...">
                Sign In
            </button>
        </form>

        <p class="auth-switch">
            New to the library portal?
            <a href="/register">Create an account</a>
        </p>
    </section>
</section>

