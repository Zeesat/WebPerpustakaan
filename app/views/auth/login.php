<?php $errors = is_array($errors ?? null) ? $errors : []; ?>
<section class="relative overflow-hidden bg-surface">
    <div class="absolute inset-x-0 top-0 h-40 bg-gradient-to-b from-primary/10 to-transparent"></div>
    <div class="max-w-lg mx-auto px-4 py-4">
        <div class="max-w-lg mx-auto">
            <section class="relative flex items-center">
                <div class="absolute inset-0 -z-10 rounded-[32px] bg-gradient-to-br from-white via-surface-container-lowest to-surface-container-low shadow-xl"></div>
                <div class="w-full rounded-[32px] border border-white/70 bg-white/90 p-6 shadow-[0_30px_80px_rgba(0,44,120,0.12)] backdrop-blur md:p-10">
                    <div class="flex flex-wrap items-start justify-between">
                        <div class="space-y-1">
                            <div class="space-y-0">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-primary">
                                    Welcome Back
                                </p>

                                <h2 class="font-headline-md text-headline-md text-primary">
                                    Sign in to LibManage
                                </h2>
                            </div>

                            <p class="text-xs text-slate-500">
                            Need an account?
                                <a href="/register" class="font-semibold text-primary">
                                    Register here
                        </a>
                            </p>
                        </div>

    <!-- Error -->
                    <?php if (! empty($errors['general'])): ?>
      <div class="auth-alert auth-alert--error" role="alert">
        <span class="material-symbols-outlined">error</span>
                            <?= htmlspecialchars((string) $errors['general']); ?>
                        </div>
                                <?php endif; ?>

                    <form action="/login" class="mt-4 space-y-4" data-loading-form method="POST">
                        <?= csrf_field(); ?>

                        <div class="grid gap-3">
                            <div>
                                <label class="mb-1 block text-xs font-medium text-on-surface" for="email">Email Address</label>
                                <div class="relative">
                                    <span class="material-symbols-outlined pointer-events-none absolute left-3 text-sm top-1/2 -translate-y-1/2 text-slate-400">mail</span>
                                    <input
                                        class="block w-full rounded-xl border border-outline-variant bg-white px-3 py-2 pl-9 text-xs text-on-surface placeholder:text-xs placeholder:text-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/15"
                                        id="email"
                                        name="email"
                                        type="email"
                                        value="<?= htmlspecialchars((string) old('email')); ?>"
            autocomplete="email" placeholder="you@campus.ac.id" required
            class="auth-form__input<?= ! empty($errors['email']) ? ' auth-form__input--error' : ''; ?>"
                                    >
                                <?php if (! empty($errors['email'])): ?>
            <p class="auth-form__error"><?= htmlspecialchars((string) $errors['email']); ?></p>
                                <?php endif; ?>
                        </div>

                            <div>
                                <div class="mb-1 flex items-center justify-between gap-4">
                                    <label class="block text-xs font-medium text-on-surface" for="password">Password</label>
                                    <a class="text-xs font-semibold text-primary transition hover:text-primary-container hover:underline" href="/forgot-password">
                                        Forgot password?
                                    </a>
                                </div>
                                <div class="relative">
                                    <span class="material-symbols-outlined pointer-events-none absolute left-3 text-sm top-1/2 -translate-y-1/2 text-slate-400">lock</span>
                                    <input
                                        class="block w-full rounded-xl border border-outline-variant bg-white px-3 py-2 pl-9 text-xs text-on-surface placeholder:text-xs placeholder:text-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/15"
                                        id="password"
                                        name="password"
                                        type="password"
                                        autocomplete="current-password"
                                        placeholder="Enter your password"
                                        required
                                    >
                                <?php if (! empty($errors['password'])): ?>
            <p class="auth-form__error"><?= htmlspecialchars((string) $errors['password']); ?></p>
                                <?php endif; ?>
                </div>
                        </div>

                        <div class="rounded-2xl bg-surface-container-low px-5 py-4 text-xs leading-6 text-on-surface-variant">
                            One account works across the student dashboard and admin area, with access controlled by your assigned role.
                        </div>
                        <button
          type="submit" data-loading-text="Signing in..."
          class="auth-form__submit"
                        >
                            Sign In
                            <span class="material-symbols-outlined text-sm">login</span>
                        </button>
                    </form>
                                    </div>
</section>
        </div>
    </div>
</section>

