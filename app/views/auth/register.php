<?php $errors = is_array($errors ?? null) ? $errors : []; ?>
<section class="auth-shell">
    <div class="auth-hero">
        <p class="eyebrow">Create Account</p>
        <h1 class="page-title">Join the library and start requesting books securely.</h1>
        <p class="page-subtitle">
            Registration creates a standard user account. Admin access remains controlled by seeded or managed accounts.
        </p>
        <div class="auth-hero-card">
            <strong>Registration checklist</strong>
            <ul class="auth-feature-list">
                <li>Use a valid email address you can remember</li>
                <li>Choose a password with at least 8 characters</li>
                <li>Your session begins automatically after successful registration</li>
            </ul>
        </div>
    </div>

    <section class="panel auth-panel">
        <div class="auth-panel-header">
            <h2>Register</h2>
            <p class="meta">Set up your member account for the borrowing workflow.</p>
        </div>

        <?php if (! empty($errors['general'])): ?>
            <div class="form-alert form-alert-error" role="alert">
                <?= htmlspecialchars((string) $errors['general']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/register" class="auth-form" data-loading-form>
            <?= csrf_field(); ?>
            <div class="form-field">
                <label for="name">Full Name</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="<?= htmlspecialchars((string) old('name')); ?>"
                    autocomplete="name"
                    maxlength="100"
                    required
                >
                <?php if (! empty($errors['name'])): ?>
                    <p class="field-error"><?= htmlspecialchars((string) $errors['name']); ?></p>
                <?php endif; ?>
            </div>

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
                <label for="password">Password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    autocomplete="new-password"
                    required
                >
                <?php if (! empty($errors['password'])): ?>
                    <p class="field-error"><?= htmlspecialchars((string) $errors['password']); ?></p>
                <?php endif; ?>
            </div>

            <div class="form-field">
                <label for="password_confirmation">Confirm Password</label>
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    required
                >
                <?php if (! empty($errors['password_confirmation'])): ?>
                    <p class="field-error"><?= htmlspecialchars((string) $errors['password_confirmation']); ?></p>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary btn-block" data-loading-text="Creating account...">
                Create Account
            </button>
        </form>

        <p class="auth-switch">
            Already have an account?
            <a href="/login">Sign in here</a>
        </p>
    </section>
</section>

