<?php $errors = is_array($errors ?? null) ? $errors : []; ?>
<section class="relative overflow-hidden bg-surface">
    <div class="absolute inset-x-0 top-0 h-40 bg-gradient-to-b from-primary/10 to-transparent"></div>
    <div class="max-w-[1200px] mx-auto px-6 py-12 md:py-16 relative">
        <div class="grid gap-8 lg:grid-cols-[1.05fr_0.95fr] items-stretch">
            <aside class="relative min-h-0 lg:min-h-[620px] overflow-hidden rounded-[32px] bg-primary text-white shadow-2xl">
                <img
                    alt="Library shelves and study area"
                    class="absolute inset-0 h-full w-full object-cover"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuB5QpvZOm-Bp5IuJG8T4qmjfSyHWlELWJy9D_4ICiTxe1DAicNmeUhR00rOp-Qx9uluT8QmoqqFVB_rlACE2sMZ0_IeWAO-Z1Ct3wHAGCOlN390Lt8EF414m_9jtgUTQRlYutBnwMQ0oG8JiGQOETpRisMItkR09rxPlV0dxfi45h3YRRx3A92pJqpl3naoUy6mfm1VG3lbZ9AZYLV_yMoPdaIyiOuQtGcbZlI7k6wWx__3yHLZ_Ua9Kg1Qy_iTsjXQyOBk3LTh7rg"
                />
                <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(0,44,120,0.2),rgba(0,44,120,0.85))]"></div>
                <div class="relative z-10 flex h-full flex-col justify-between p-8 md:p-10">
                    <div class="space-y-8">
                        <div class="inline-flex items-center gap-3 rounded-full border border-white/20 bg-white/10 px-4 py-2 backdrop-blur-sm">
                            <span class="material-symbols-outlined text-[20px]">library_books</span>
                            <span class="text-sm font-semibold tracking-[0.18em] text-blue-100 uppercase">Student Registration</span>
                        </div>

                        <div class="max-w-xl space-y-5">
                            <h1 class="font-display-lg text-[clamp(2.5rem,5vw,4.25rem)] leading-[1.05]">
                                Start your borrowing experience with a verified account.
                            </h1>
                            <p class="max-w-lg text-base leading-7 text-blue-100/90 md:text-lg">
                                LibManage keeps the registration flow simple while staying aligned with the verified lending workflow used across the platform.
                            </p>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="rounded-2xl border border-white/15 bg-white/10 p-5 backdrop-blur-sm">
                                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-blue-100">Fast Setup</p>
                                <p class="mt-3 text-sm leading-6 text-blue-50/90">
                                    Register once, then go straight to your dashboard and request books without extra setup.
                                </p>
                            </div>
                            <div class="rounded-2xl border border-white/15 bg-white/10 p-5 backdrop-blur-sm">
                                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-blue-100">Secure Access</p>
                                <p class="mt-3 text-sm leading-6 text-blue-50/90">
                                    Your account is protected with hashed passwords and session-based access controls.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 rounded-[28px] border border-white/15 bg-white/10 p-6 backdrop-blur-sm">
                        <div class="flex items-start gap-4">
                            <div class="mt-1 flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/15">
                                <span class="material-symbols-outlined text-[24px]">verified_user</span>
                            </div>
                            <div>
                                <h2 class="font-headline-sm text-headline-sm text-white">Consistent with the home experience</h2>
                                <p class="mt-2 text-sm leading-6 text-blue-50/90">
                                    The same visual language from the landing page continues here: deep blue surfaces, soft cards, and a verification-first tone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <section class="relative flex items-center">
                <div class="absolute inset-0 -z-10 rounded-[32px] bg-gradient-to-br from-white via-surface-container-lowest to-surface-container-low shadow-xl"></div>
                <div class="w-full rounded-[32px] border border-white/70 bg-white/90 p-6 shadow-[0_30px_80px_rgba(0,44,120,0.12)] backdrop-blur md:p-10">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div class="space-y-3">
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primary">Create Account</p>
                            <div>
                                <h2 class="font-headline-md text-headline-md text-primary">Join LibManage</h2>
                                <p class="mt-2 max-w-md text-on-surface-variant">
                                    Create your student account to browse the catalog, request loans, and monitor approvals in one place.
                                </p>
                            </div>
                        </div>
                        <a class="inline-flex items-center gap-2 rounded-full border border-outline-variant px-4 py-2 text-sm font-semibold text-primary transition hover:border-primary hover:bg-blue-50" href="/login">
                            Already registered?
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </a>
                    </div>

                    <?php if (! empty($errors['general'])): ?>
                        <div class="mt-8 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-medium text-rose-800" role="alert">
                            <?= htmlspecialchars((string) $errors['general']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="/register" class="mt-8 space-y-6" data-loading-form method="POST">
                        <?= csrf_field(); ?>

                        <div class="grid gap-5">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-on-surface" for="name">Full Name</label>
                                <div class="relative">
                                    <span class="material-symbols-outlined pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">person</span>
                                    <input
                                        class="block w-full rounded-2xl border border-outline-variant bg-white px-4 py-4 pl-12 text-on-surface placeholder:text-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/15"
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
                                <label class="mb-2 block text-sm font-semibold text-on-surface" for="email">Email Address</label>
                                <div class="relative">
                                    <span class="material-symbols-outlined pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">mail</span>
                                    <input
                                        class="block w-full rounded-2xl border border-outline-variant bg-white px-4 py-4 pl-12 text-on-surface placeholder:text-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/15"
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

                            <div class="grid gap-5 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-on-surface" for="password">Password</label>
                                    <div class="relative">
                                        <span class="material-symbols-outlined pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">lock</span>
                                        <input
                                            class="block w-full rounded-2xl border border-outline-variant bg-white px-4 py-4 pl-12 text-on-surface placeholder:text-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/15"
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
                                    <label class="mb-2 block text-sm font-semibold text-on-surface" for="password_confirmation">Confirm Password</label>
                                    <div class="relative">
                                        <span class="material-symbols-outlined pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">task_alt</span>
                                        <input
                                            class="block w-full rounded-2xl border border-outline-variant bg-white px-4 py-4 pl-12 text-on-surface placeholder:text-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/15"
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

                        <div class="rounded-2xl bg-surface-container-low px-5 py-4 text-sm leading-6 text-on-surface-variant">
                            Registration creates a standard member account. Admin access is still controlled separately by the library team.
                        </div>

                        <button
                            class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-primary px-6 py-4 text-sm font-bold text-white shadow-lg shadow-blue-900/20 transition hover:bg-primary-container"
                            data-loading-text="Creating account..."
                            type="submit"
                        >
                            Create My Account
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </div>
</section>
