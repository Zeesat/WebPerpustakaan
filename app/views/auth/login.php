<?php $errors = is_array($errors ?? null) ? $errors : []; ?>
<section class="relative overflow-hidden bg-surface">
    <div class="absolute inset-x-0 top-0 h-40 bg-gradient-to-b from-primary/10 to-transparent"></div>
    <div class="max-w-[1200px] mx-auto px-6 py-12 md:py-16 relative">
        <div class="grid gap-8 lg:grid-cols-[0.98fr_1.02fr] items-stretch">
            <section class="relative flex items-center order-2 lg:order-1">
                <div class="absolute inset-0 -z-10 rounded-[32px] bg-gradient-to-br from-white via-surface-container-lowest to-surface-container-low shadow-xl"></div>
                <div class="w-full rounded-[32px] border border-white/70 bg-white/90 p-6 shadow-[0_30px_80px_rgba(0,44,120,0.12)] backdrop-blur md:p-10">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div class="space-y-3">
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primary">Welcome Back</p>
                            <div>
                                <h1 class="font-headline-md text-headline-md text-primary">Sign in to LibManage</h1>
                                <p class="mt-2 max-w-md text-on-surface-variant">
                                    Continue to your dashboard, track approval status, and manage your borrowing activity with the same verified workflow.
                                </p>
                            </div>
                        </div>
                        <a class="inline-flex items-center gap-2 rounded-full border border-outline-variant px-4 py-2 text-sm font-semibold text-primary transition hover:border-primary hover:bg-blue-50" href="/register">
                            Need an account?
                            <span class="material-symbols-outlined text-[18px]">person_add</span>
                        </a>
                    </div>

                    <?php if (! empty($errors['general'])): ?>
                        <div class="mt-8 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-medium text-rose-800" role="alert">
                            <?= htmlspecialchars((string) $errors['general']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="/login" class="mt-8 space-y-6" data-loading-form method="POST">
                        <?= csrf_field(); ?>

                        <div class="grid gap-5">
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

                            <div>
                                <div class="mb-2 flex items-center justify-between gap-4">
                                    <label class="block text-sm font-semibold text-on-surface" for="password">Password</label>
                                    <a class="text-sm font-semibold text-primary transition hover:text-primary-container hover:underline" href="/forgot-password">
                                        Forgot password?
                                    </a>
                                </div>
                                <div class="relative">
                                    <span class="material-symbols-outlined pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">lock</span>
                                    <input
                                        class="block w-full rounded-2xl border border-outline-variant bg-white px-4 py-4 pl-12 text-on-surface placeholder:text-slate-400 focus:border-primary focus:ring-2 focus:ring-primary/15"
                                        id="password"
                                        name="password"
                                        type="password"
                                        autocomplete="current-password"
                                        placeholder="Enter your password"
                                        required
                                    >
                                </div>
                                <?php if (! empty($errors['password'])): ?>
                                    <p class="mt-2 text-sm font-medium text-error"><?= htmlspecialchars((string) $errors['password']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="rounded-2xl bg-surface-container-low px-5 py-4 text-sm leading-6 text-on-surface-variant">
                            One account works across the student dashboard and admin area, with access controlled by your assigned role.
                        </div>

                        <button
                            class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-primary px-6 py-4 text-sm font-bold text-white shadow-lg shadow-blue-900/20 transition hover:bg-primary-container"
                            data-loading-text="Signing in..."
                            type="submit"
                        >
                            Sign In
                            <span class="material-symbols-outlined text-[18px]">login</span>
                        </button>
                    </form>
                </div>
            </section>

            <aside class="relative min-h-0 lg:min-h-[620px] overflow-hidden rounded-[32px] bg-white shadow-2xl order-1 lg:order-2">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(17,66,160,0.18),transparent_32%),linear-gradient(180deg,#ffffff_0%,#edf3ff_100%)]"></div>
                <div class="relative z-10 flex h-full flex-col justify-between p-8 md:p-10">
                    <div class="space-y-8">
                        <div class="inline-flex items-center gap-3 rounded-full border border-blue-100 bg-blue-50 px-4 py-2">
                            <span class="material-symbols-outlined text-[20px] text-primary">menu_book</span>
                            <span class="text-sm font-semibold tracking-[0.18em] text-primary uppercase">Member Access</span>
                        </div>

                        <div class="space-y-5">
                            <h2 class="font-display-lg text-[clamp(2.2rem,4.5vw,3.9rem)] leading-[1.05] text-blue-950">
                                A calmer entry point for readers and librarians.
                            </h2>
                            <p class="max-w-lg text-base leading-7 text-slate-600 md:text-lg">
                                This login page keeps the same typography, spacing rhythm, and blue identity from the home page, but shifts the emphasis from discovery to access.
                            </p>
                        </div>

                        <div class="grid gap-4">
                            <div class="rounded-[24px] border border-blue-100 bg-white/90 p-5 shadow-sm">
                                <div class="flex items-start gap-4">
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-100 text-primary">
                                        <span class="material-symbols-outlined text-[24px]">history</span>
                                    </div>
                                    <div>
                                        <h3 class="font-headline-sm text-[20px] text-blue-950">Track every request</h3>
                                        <p class="mt-2 text-sm leading-6 text-slate-600">
                                            Check pending, approved, returned, or late status from a single dashboard.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-[24px] border border-blue-100 bg-white/90 p-5 shadow-sm">
                                <div class="flex items-start gap-4">
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-100 text-primary">
                                        <span class="material-symbols-outlined text-[24px]">shield_lock</span>
                                    </div>
                                    <div>
                                        <h3 class="font-headline-sm text-[20px] text-blue-950">Verified access</h3>
                                        <p class="mt-2 text-sm leading-6 text-slate-600">
                                            Sessions are protected so only authorized users reach lending and admin workflows.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 rounded-[28px] bg-blue-950 px-6 py-5 text-white">
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-blue-200">New here?</p>
                        <p class="mt-3 text-sm leading-6 text-blue-100">
                            Create a member account first, then return here anytime to continue your borrowing activity.
                        </p>
                        <a class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-white hover:text-blue-200" href="/register">
                            Go to registration
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
