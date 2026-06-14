<?php $errors = is_array($errors ?? null) ? $errors : []; ?>
<div class="auth-page">
  <div class="auth-container">
    <!-- Brand -->
    <div class="auth-brand">
      <div class="auth-brand__icon"><span class="material-symbols-outlined">menu_book</span></div>
      <h1 class="auth-brand__title">Welcome back</h1>
      <p class="auth-brand__subtitle">Sign in to continue to your account</p>
    </div>

    <!-- Error -->
                    <?php if (! empty($errors['general'])): ?>
      <div class="auth-alert auth-alert--error" role="alert">
        <span class="material-symbols-outlined">error</span>
                            <?= htmlspecialchars((string) $errors['general']); ?>
                        </div>
                    <?php endif; ?>

    <!-- Card -->
    <div class="auth-card">
      <form action="/login" method="POST" data-loading-form class="auth-form">
                        <?= csrf_field(); ?>

        <div class="auth-form__field">
          <label for="email" class="auth-form__label">Email</label>
                                    <input
            id="email" name="email" type="email"
                                        value="<?= htmlspecialchars((string) old('email')); ?>"
            autocomplete="email" placeholder="you@campus.ac.id" required
            class="auth-form__input<?= ! empty($errors['email']) ? ' auth-form__input--error' : ''; ?>"
                                    >
                                <?php if (! empty($errors['email'])): ?>
            <p class="auth-form__error"><?= htmlspecialchars((string) $errors['email']); ?></p>
                                <?php endif; ?>
                            </div>

        <div class="auth-form__field">
          <div class="auth-form__label-row">
            <label for="password" class="auth-form__label">Password</label>
            <a href="/forgot-password" class="auth-form__link">Forgot?</a>
                                </div>
                                    <input
            id="password" name="password" type="password"
            autocomplete="current-password" placeholder="Enter your password" required
            class="auth-form__input<?= ! empty($errors['password']) ? ' auth-form__input--error' : ''; ?>"
                                    >
                                <?php if (! empty($errors['password'])): ?>
            <p class="auth-form__error"><?= htmlspecialchars((string) $errors['password']); ?></p>
                                <?php endif; ?>
                            </div>
                        <button
          type="submit" data-loading-text="Signing in..."
          class="auth-form__submit"
                        >
          Sign in
          <span class="material-symbols-outlined">arrow_forward</span>
                        </button>
                    </form>

      <div class="auth-divider"><span>or</span></div>

      <p class="auth-switch">
        Don't have an account?
        <a href="/register">Create one</a>
                            </p>
                        </div>

    <p class="auth-footer">&copy; <span data-year></span> <?= htmlspecialchars(app_config('name')); ?></p>
                                    </div>
                                    </div>

