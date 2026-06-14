<?php $errors = is_array($errors ?? null) ? $errors : []; ?>
<div class="auth-page">
  <div class="auth-container">
    <div class="auth-brand">
      <div class="auth-brand__icon"><span class="material-symbols-outlined">person_add</span></div>
      <h1 class="auth-brand__title">Create your account</h1>
      <p class="auth-brand__subtitle">Join LibManage to browse and borrow books</p>
    </div>

                    <?php if (! empty($errors['general'])): ?>
      <div class="auth-alert auth-alert--error" role="alert">
        <span class="material-symbols-outlined">error</span>
                            <?= htmlspecialchars((string) $errors['general']); ?>
                        </div>
                    <?php endif; ?>

    <div class="auth-card">
      <form action="/register" method="POST" data-loading-form class="auth-form">
                        <?= csrf_field(); ?>

        <div class="auth-form__field">
          <label for="name" class="auth-form__label">Full Name</label>
                                    <input
            id="name" name="name" type="text"
                                        value="<?= htmlspecialchars((string) old('name')); ?>"
            autocomplete="name" maxlength="100" placeholder="Enter your full name" required
            class="auth-form__input<?= ! empty($errors['name']) ? ' auth-form__input--error' : ''; ?>"
                                    >
                                <?php if (! empty($errors['name'])): ?>
            <p class="auth-form__error"><?= htmlspecialchars((string) $errors['name']); ?></p>
                                <?php endif; ?>
                            </div>

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

        <div class="auth-form__row">
          <div class="auth-form__field">
            <label for="password" class="auth-form__label">Password</label>
                                        <input
              id="password" name="password" type="password"
              autocomplete="new-password" placeholder="Min. 8 characters" required
              class="auth-form__input<?= ! empty($errors['password']) ? ' auth-form__input--error' : ''; ?>"
                                        >
                                    <?php if (! empty($errors['password'])): ?>
              <p class="auth-form__error"><?= htmlspecialchars((string) $errors['password']); ?></p>
                                    <?php endif; ?>
                                </div>
          <div class="auth-form__field">
            <label for="password_confirmation" class="auth-form__label">Confirm</label>
                                        <input
              id="password_confirmation" name="password_confirmation" type="password"
              autocomplete="new-password" placeholder="Repeat password" required
              class="auth-form__input<?= ! empty($errors['password_confirmation']) ? ' auth-form__input--error' : ''; ?>"
                                        >
                                    <?php if (! empty($errors['password_confirmation'])): ?>
              <p class="auth-form__error"><?= htmlspecialchars((string) $errors['password_confirmation']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <button
          type="submit" data-loading-text="Creating account..."
          class="auth-form__submit"
                        >
          Create account
          <span class="material-symbols-outlined">arrow_forward</span>
                        </button>
                    </form>

      <div class="auth-divider"><span>or</span></div>

      <p class="auth-switch">
        Already have an account?
        <a href="/login">Sign in</a>
      </p>
    </div>

    <p class="auth-footer">&copy; <span data-year></span> <?= htmlspecialchars(app_config('name')); ?></p>
  </div>
</div>

