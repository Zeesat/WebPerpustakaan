<!-- Hero Section -->
<section class="relative h-[640px] flex items-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img
            alt="Library Background"
            class="w-full h-full object-cover"
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuB5QpvZOm-Bp5IuJG8T4qmjfSyHWlELWJy9D_4ICiTxe1DAicNmeUhR00rOp-Qx9uluT8QmoqqFVB_rlACE2sMZ0_IeWAO-Z1Ct3wHAGCOlN390Lt8EF414m_9jtgUTQRlYutBnwMQ0oG8JiGQOETpRisMItkR09rxPlV0dxfi45h3YRRx3A92pJqpl3naoUy6mfm1VG3lbZ9AZYLV_yMoPdaIyiOuQtGcbZlI7k6wWx__3yHLZ_Ua9Kg1Qy_iTsjXQyOBk3LTh7rg"
        />
        <div class="absolute inset-0 hero-overlay"></div>
    </div>
    <div class="relative z-10 max-w-[1200px] mx-auto px-6 w-full grid md:grid-cols-2 gap-12 items-center">
        <div class="text-white space-y-6">
            <h1 class="font-display-lg text-display-lg">Smart Library. Better Management.</h1>
            <p class="font-body-lg text-body-lg text-blue-50/90 max-w-xl">
                Streamline your academic resources with our high-efficiency library management system.
                Verify loans, manage collections, and provide students with seamless access to knowledge.
            </p>
            <div class="flex flex-wrap gap-4 pt-4">
                <a class="px-8 py-4 bg-white text-blue-900 font-bold rounded-lg shadow-lg hover:bg-blue-50 transition-colors" href="/books">
                    Browse Books
                </a>
                <a class="px-8 py-4 bg-transparent border-2 border-white text-white font-bold rounded-lg hover:bg-white/10 transition-colors flex items-center gap-2" href="/dashboard">
                    How It Works <span class="material-symbols-outlined">play_circle</span>
                </a>
            </div>
        </div>

        <div class="hidden md:block">
            <div class="bg-white p-8 rounded-xl shadow-2xl space-y-6 border border-white/20">
                <div class="space-y-2">
                    <h2 class="font-headline-sm text-headline-sm text-primary">Welcome to LibManage</h2>
                    <p class="text-on-surface-variant font-body-md">Select your portal to continue with your library tasks.</p>
                </div>
                <div class="grid gap-4">
                    <a class="group p-5 rounded-lg border border-slate-200 hover:border-primary-container hover:bg-surface-container-low transition-all cursor-pointer flex items-center gap-4" href="/dashboard">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined">person</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-label-md text-label-md text-primary">For Users</h3>
                            <p class="text-sm text-slate-500">Browse books, check availability, and request loans.</p>
                        </div>
                        <span class="material-symbols-outlined text-slate-300 group-hover:text-primary transition-colors">chevron_right</span>
                    </a>

                    <a class="group p-5 rounded-lg border border-slate-200 hover:border-primary-container hover:bg-surface-container-low transition-all cursor-pointer flex items-center gap-4" href="/admin">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined">admin_panel_settings</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-label-md text-label-md text-primary">For Admins</h3>
                            <p class="text-sm text-slate-500">Verify requests, manage catalog, and monitor logs.</p>
                        </div>
                        <span class="material-symbols-outlined text-slate-300 group-hover:text-primary transition-colors">chevron_right</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-24 bg-white">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="text-center mb-16 space-y-4">
            <h2 class="font-display-lg text-headline-md text-blue-900">Why LibManage?</h2>
            <p class="text-slate-500 max-w-2xl mx-auto">
                Our platform combines traditional library ethics with modern digital tools to provide the ultimate resource management experience.
            </p>
        </div>
        <div class="grid md:grid-cols-4 gap-8">
            <div class="p-6 rounded-xl space-y-4 text-center">
                <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-700 flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined" style="font-size: 32px;">search</span>
                </div>
                <h3 class="font-headline-sm text-[18px] text-blue-900">Easy Book Discovery</h3>
                <p class="text-on-surface-variant font-body-md text-sm">Advanced filtering and search algorithms to find exactly what you need in seconds.</p>
            </div>

            <div class="p-6 rounded-xl space-y-4 text-center">
                <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-700 flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined" style="font-size: 32px;">verified_user</span>
                </div>
                <h3 class="font-headline-sm text-[18px] text-blue-900">Verified Loan Process</h3>
                <p class="text-on-surface-variant font-body-md text-sm">Multi-step verification ensures that every loan request is authenticated and secure.</p>
            </div>

            <div class="p-6 rounded-xl space-y-4 text-center">
                <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-700 flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined" style="font-size: 32px;">security</span>
                </div>
                <h3 class="font-headline-sm text-[18px] text-blue-900">Secure &amp; Reliable</h3>
                <p class="text-on-surface-variant font-body-md text-sm">Enterprise-grade encryption protecting institutional data and student privacy.</p>
            </div>

            <div class="p-6 rounded-xl space-y-4 text-center">
                <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-700 flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined" style="font-size: 32px;">history</span>
                </div>
                <h3 class="font-headline-sm text-[18px] text-blue-900">On-Time Returns</h3>
                <p class="text-on-surface-variant font-body-md text-sm">Automated reminder system to minimize overdue books and maximize resource circulation.</p>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Bar -->
<section class="bg-blue-900 text-white py-12">
    <div class="max-w-[1200px] mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8">
        <div class="text-center space-y-2">
            <div class="font-display-lg text-headline-md">1,250+</div>
            <div class="text-blue-200 font-label-md uppercase tracking-wider text-xs">Books Available</div>
        </div>
        <div class="text-center space-y-2">
            <div class="font-display-lg text-headline-md">320+</div>
            <div class="text-blue-200 font-label-md uppercase tracking-wider text-xs">Registered Users</div>
        </div>
        <div class="text-center space-y-2">
            <div class="font-display-lg text-headline-md">850+</div>
            <div class="text-blue-200 font-label-md uppercase tracking-wider text-xs">Loans Processed</div>
        </div>
        <div class="text-center space-y-2">
            <div class="font-display-lg text-headline-md">100%</div>
            <div class="text-blue-200 font-label-md uppercase tracking-wider text-xs">Verified &amp; Secure</div>
        </div>
    </div>
</section>
