<?php $errors = is_array($errors ?? null) ? $errors : []; ?>
<section class="relative flex h-[100dvh] items-center justify-center overflow-hidden bg-surface">
    <div class="absolute inset-x-0 top-0 h-40 bg-gradient-to-b from-primary/10 to-transparent"></div>
    <div class="w-full max-w-lg px-4 py-4">
        <div class="w-full">
    

            <section class="relative flex items-center">
                <div class="absolute inset-0 -z-10 rounded-[32px] bg-gradient-to-br from-white via-surface-container-lowest to-surface-container-low shadow-xl"></div>
                <div class="w-full rounded-[32px] border border-white/70 bg-white/90 p-6 shadow-[0_30px_80px_rgba(0,44,120,0.12)] backdrop-blur md:p-10">
                    <div class="flex flex-wrap items-start justify-between">
                        <div class="space-y-1">
                            <div class="space-y-0">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-primary">
                                    Create Account
                                </p>

                                <h2 class="font-headline-md text-headline-md text-primary">
                                    Join LibManage
                                </h2>
                            </div>

                            <p class="text-xs text-slate-500">
                                Already have an account?
                                <a href="/login" class="font-semibold text-primary">
                                    Login
                                </a>
                            </p>
                        </div>
                    </div>

                    <?php if (! empty($errors['general'])): ?>
                        <div class="mt-8 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-medium text-rose-800" role="alert">
                            <?= htmlspecialchars((string) $errors['general']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="/register" class="mt-4 space-y-4" data-loading-form method="POST">
                        <?= csrf_field(); ?>

                        <div class="grid gap-3">
                            <div>
                                <label class="mb-1 block text-xs font-medium text-on-surface" for="name">Full Name</label>
                                <div class="relative">
                                    <span class="material-symbols-outlined pointer-events-none absolute left-3 text-sm top-1/2 -translate-y-1/2 text-slate-400">person</span>
                                    <input
                                        class="block w-full rounded-xl border border-outline-variant bg-white px-3 py-2 pl-9 text-xs text-on-surface placeholder:text-xs placeholder:text-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/15"
                                        id="name"
                                        name="name"
                                        type="text"
                                        value="<?= htmlspecialchars((string) old('name')); ?>"
                                        autocomplete="name"
                                        maxlength="100"
                                        placeholder="Enter your full name"
                                        required
                                    >
                                </div>
                                <?php if (! empty($errors['name'])): ?>
                                    <p class="mt-2 text-sm font-medium text-error"><?= htmlspecialchars((string) $errors['name']); ?></p>
                                <?php endif; ?>
                            </div>

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
                                        autocomplete="email"
                                        placeholder="you@campus.ac.id"
                                        required
                                    >
                                </div>
                                <?php if (! empty($errors['email'])): ?>
                                    <p class="mt-2 text-sm font-medium text-error"><?= htmlspecialchars((string) $errors['email']); ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="grid gap-3 md:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-on-surface" for="password">Password</label>
                                    <div class="relative">
                                        <span class="material-symbols-outlined pointer-events-none absolute left-3 text-sm top-1/2 -translate-y-1/2 text-slate-400">lock</span>
                                        <input
                                            class="block w-full rounded-xl border border-outline-variant bg-white px-3 py-2 pl-9 text-xs text-on-surface placeholder:text-xs placeholder:text-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/15"
                                            id="password"
                                            name="password"
                                            type="password"
                                            autocomplete="new-password"
                                            placeholder="Minimum 8 characters"
                                            required
                                        >
                                    </div>
                                    <?php if (! empty($errors['password'])): ?>
                                        <p class="mt-2 text-sm font-medium text-error"><?= htmlspecialchars((string) $errors['password']); ?></p>
                                    <?php endif; ?>
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-medium text-on-surface" for="password_confirmation">Confirm Password</label>
                                    <div class="relative">
                                        <span class="material-symbols-outlined pointer-events-none absolute left-3 text-sm top-1/2 -translate-y-1/2 text-slate-400">task_alt</span>
                                        <input
                                            class="block w-full rounded-xl border border-outline-variant bg-white px-3 py-2 pl-9 text-xs text-on-surface placeholder:text-xs placeholder:text-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/15"
                                            id="password_confirmation"
                                            name="password_confirmation"
                                            type="password"
                                            autocomplete="new-password"
                                            placeholder="Repeat your password"
                                            required
                                        >
                                    </div>
                                    <?php if (! empty($errors['password_confirmation'])): ?>
                                        <p class="mt-2 text-sm font-medium text-error"><?= htmlspecialchars((string) $errors['password_confirmation']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <button
                            class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-primary px-6 py-4 text-sm font-bold text-white shadow-lg shadow-blue-900/20 transition hover:bg-primary-container"
                            data-loading-text="Creating account..."
                            type="submit"
                        >
                            Create My Account
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </div>
</section>
