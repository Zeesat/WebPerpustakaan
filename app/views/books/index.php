<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>LibManage - Home</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&amp;family=Manrope:wght@600;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .fill-icon {
            font-variation-settings: 'FILL' 1;
        }
    </style>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-dim": "#dad9e2",
                        "on-surface": "#1a1b21",
                        "surface-container": "#eeedf6",
                        "on-error-container": "#93000a",
                        "error-container": "#ffdad6",
                        "secondary-container": "#cbdeff",
                        "on-secondary-container": "#4f627e",
                        "surface": "#faf8ff",
                        "on-tertiary-container": "#ff9f78",
                        "tertiary-container": "#812d00",
                        "on-error": "#ffffff",
                        "primary": "#002c78",
                        "surface-container-lowest": "#ffffff",
                        "surface-tint": "#3159b7",
                        "secondary-fixed-dim": "#b4c8e8",
                        "inverse-primary": "#b3c5ff",
                        "on-primary-fixed-variant": "#0d409e",
                        "secondary": "#4d5f7b",
                        "tertiary": "#5c1e00",
                        "tertiary-fixed": "#ffdbce",
                        "on-primary-fixed": "#001849",
                        "primary-fixed": "#dae1ff",
                        "primary-fixed-dim": "#b3c5ff",
                        "on-tertiary": "#ffffff",
                        "secondary-fixed": "#d4e3ff",
                        "on-primary-container": "#9db6ff",
                        "inverse-surface": "#2f3037",
                        "on-secondary": "#ffffff",
                        "tertiary-fixed-dim": "#ffb598",
                        "on-secondary-fixed-variant": "#354862",
                        "error": "#ba1a1a",
                        "on-background": "#1a1b21",
                        "on-tertiary-fixed-variant": "#7e2c00",
                        "surface-variant": "#e2e2ea",
                        "outline": "#747684",
                        "outline-variant": "#c4c6d4",
                        "background": "#faf8ff",
                        "surface-container-high": "#e8e7f0",
                        "surface-container-low": "#f3f3fb",
                        "on-tertiary-fixed": "#370e00",
                        "surface-container-highest": "#e2e2ea",
                        "primary-container": "#1142a0",
                        "on-surface-variant": "#434652",
                        "on-secondary-fixed": "#061c35",
                        "on-primary": "#ffffff",
                        "inverse-on-surface": "#f1f0f9",
                        "surface-bright": "#faf8ff"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "stack-md": "16px",
                        "gutter": "24px",
                        "unit": "8px",
                        "container-max-width": "1200px",
                        "stack-sm": "8px",
                        "stack-lg": "32px",
                        "margin-page": "40px"
                    },
                    "fontFamily": {
                        "display-lg": ["Manrope"],
                        "label-md": ["Inter"],
                        "headline-md": ["Manrope"],
                        "headline-sm": ["Manrope"],
                        "caption": ["Inter"],
                        "body-md": ["Inter"],
                        "body-lg": ["Inter"]
                    },
                    "fontSize": {
                        "display-lg": ["48px", { "lineHeight": "1.2", "fontWeight": "800" }],
                        "label-md": ["14px", { "lineHeight": "1.2", "letterSpacing": "0.02em", "fontWeight": "600" }],
                        "headline-md": ["32px", { "lineHeight": "1.3", "fontWeight": "700" }],
                        "headline-sm": ["24px", { "lineHeight": "1.4", "fontWeight": "600" }],
                        "caption": ["12px", { "lineHeight": "1.4", "fontWeight": "400" }],
                        "body-md": ["16px", { "lineHeight": "1.6", "fontWeight": "400" }],
                        "body-lg": ["18px", { "lineHeight": "1.6", "fontWeight": "400" }]
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-background text-on-background font-body-md antialiased">
<!-- TopNavBar -->
<header class="bg-surface-container-lowest w-full top-0 sticky shadow-sm border-b border-outline-variant z-50">
<div class="flex justify-between items-center h-16 px-gutter max-w-container-max-width mx-auto">
<!-- Brand -->
<div class="flex items-center gap-3">
<div class="bg-primary text-on-primary p-2 rounded-lg flex items-center justify-center">
<span class="material-symbols-outlined" data-icon="menu_book">menu_book</span>
</div>
<div>
<h1 class="font-display-lg text-headline-sm font-extrabold text-primary">LibManage</h1>
<p class="font-caption text-caption text-on-surface-variant">Library Loan Management System</p>
</div>
</div>
<!-- Navigation Links -->
<nav class="hidden md:flex gap-8 h-full items-center">
<a class="h-full flex items-center text-primary border-b-2 border-primary font-semibold pb-1 font-label-md text-label-md" href="#">Home</a>
<a class="h-full flex items-center text-on-secondary-container hover:text-primary font-medium font-label-md text-label-md" href="#">My Loans</a>
<a class="h-full flex items-center text-on-secondary-container hover:text-primary font-medium font-label-md text-label-md" href="#">Browse Books</a>
<a class="h-full flex items-center text-on-secondary-container hover:text-primary font-medium font-label-md text-label-md" href="#">About</a>
</nav>
<!-- Actions -->
<div class="flex items-center gap-4">
<button class="relative p-2 text-on-surface-variant hover:bg-surface-container-high transition-colors duration-200 rounded-full cursor-pointer active:opacity-80">
<span class="material-symbols-outlined" data-icon="notifications">notifications</span>
<span class="absolute top-1 right-1 bg-primary text-on-primary text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center border-2 border-surface-container-lowest">2</span>
</button>
<div class="flex items-center gap-2 cursor-pointer hover:opacity-80 transition-opacity">
<img alt="User profile" class="w-8 h-8 rounded-full border border-outline-variant" data-alt="A small circular portrait placeholder image representing a user avatar in a modern web application interface." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDnoACCifqd01AM_7w8hne7weTfZ2aR-By1pD-hCRgCNBlCWuAPozmAGtaNIqOAyXWbDIT2XsggN1aTIkeGKK11V1UWL_cHbl_uRzG1XshxFP5DrHZjYtgN4yTopXZvGToaNhDDHZ9o3mLc-0cFw-knlj9rikfWPLaWx71f1v5mqXVPKx0XVE0QFNefARYScP42HZ9VPqssE1CkTI0AZzfVXY98UVIApVV_gnRkOQnQSW1dRQhB27jXH-__A-gZ4-R42tseACazjWk"/>
<span class="font-label-md text-label-md hidden md:block">Hi, John Doe</span>
<span class="material-symbols-outlined text-sm hidden md:block" data-icon="expand_more">expand_more</span>
</div>
</div>
</div>
</header>
<!-- Hero Section -->
<section class="bg-primary-container text-on-primary px-gutter py-stack-lg relative overflow-hidden">
<div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>
<div class="max-w-container-max-width mx-auto relative z-10 flex flex-col md:flex-row justify-between items-center gap-stack-lg">
<div class="flex-1 w-full">
<h2 class="font-display-lg text-display-lg mb-2">Welcome, John! <span class="text-4xl">👋</span></h2>
<p class="font-body-lg text-body-lg text-primary-fixed-dim mb-8">Discover books, request loans, and expand your knowledge.</p>
<div class="relative max-w-xl">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline" data-icon="search">search</span>
<input class="w-full pl-12 pr-24 py-3 rounded-xl border-none focus:ring-2 focus:ring-primary shadow-sm text-on-surface placeholder:text-outline-variant font-body-md text-body-md" placeholder="Search books by title, author, or keyword..." type="text"/>
<button class="absolute right-2 top-1/2 -translate-y-1/2 bg-primary text-on-primary px-4 py-1.5 rounded-lg font-label-md text-label-md hover:bg-on-primary-fixed-variant transition-colors">Search</button>
</div>
</div>
<div class="flex gap-4 w-full md:w-auto overflow-x-auto pb-4 md:pb-0">
<!-- Stat Card 1 -->
<div class="bg-surface-container-lowest text-on-surface rounded-xl p-6 min-w-[160px] flex flex-col items-center shadow-[0px_4px_12px_rgba(0,0,0,0.05)] border border-outline-variant">
<div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center mb-3 text-primary">
<span class="material-symbols-outlined fill-icon" data-icon="menu_book">menu_book</span>
</div>
<span class="font-headline-md text-headline-md text-on-surface">1,250+</span>
<span class="font-caption text-caption text-on-surface-variant mt-1">Books Available</span>
</div>
<!-- Stat Card 2 -->
<div class="bg-surface-container-lowest text-on-surface rounded-xl p-6 min-w-[160px] flex flex-col items-center shadow-[0px_4px_12px_rgba(0,0,0,0.05)] border border-outline-variant">
<div class="w-12 h-12 rounded-full bg-green-500/10 flex items-center justify-center mb-3 text-green-700">
<span class="material-symbols-outlined fill-icon" data-icon="category">category</span>
</div>
<span class="font-headline-md text-headline-md text-on-surface">25</span>
<span class="font-caption text-caption text-on-surface-variant mt-1">Categories</span>
</div>
<!-- Stat Card 3 -->
<div class="bg-surface-container-lowest text-on-surface rounded-xl p-6 min-w-[160px] flex flex-col items-center shadow-[0px_4px_12px_rgba(0,0,0,0.05)] border border-outline-variant">
<div class="w-12 h-12 rounded-full bg-purple-500/10 flex items-center justify-center mb-3 text-purple-700">
<span class="material-symbols-outlined fill-icon" data-icon="bookmark">bookmark</span>
</div>
<span class="font-headline-md text-headline-md text-on-surface">3</span>
<span class="font-caption text-caption text-on-surface-variant mt-1">My Active Loans</span>
</div>
</div>
</div>
</section>
<!-- Main Content -->
<main class="max-w-container-max-width mx-auto px-gutter py-stack-lg flex flex-col md:flex-row gap-gutter">
<!-- Sidebar -->
<aside class="w-full md:w-64 flex-shrink-0 space-y-stack-md">
<!-- Categories -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-4 shadow-[0px_4px_12px_rgba(0,0,0,0.05)]">
<h3 class="font-headline-sm text-headline-sm mb-4 px-2">Categories</h3>
<ul class="space-y-1">
<li>
<button class="w-full flex items-center justify-between px-3 py-2 bg-secondary-container text-on-secondary-container rounded-lg font-label-md text-label-md font-bold">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-[18px]" data-icon="menu_book">menu_book</span>
                                All Categories
                            </div>
<span class="bg-primary/10 px-2 py-0.5 rounded text-xs">25</span>
</button>
</li>
<li>
<button class="w-full flex items-center justify-between px-3 py-2 text-on-surface-variant hover:bg-surface-container-high rounded-lg font-body-md text-body-md transition-colors">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-[18px]" data-icon="book">book</span>
                                Fiction
                            </div>
<span class="text-xs text-outline">8</span>
</button>
</li>
<li>
<button class="w-full flex items-center justify-between px-3 py-2 text-on-surface-variant hover:bg-surface-container-high rounded-lg font-body-md text-body-md transition-colors">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-[18px]" data-icon="science">science</span>
                                Science
                            </div>
<span class="text-xs text-outline">6</span>
</button>
</li>
<li>
<button class="w-full flex items-center justify-between px-3 py-2 text-on-surface-variant hover:bg-surface-container-high rounded-lg font-body-md text-body-md transition-colors">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-[18px]" data-icon="biotech">biotech</span>
                                Technology
                            </div>
<span class="text-xs text-outline">5</span>
</button>
</li>
<li>
<button class="w-full flex items-center justify-between px-3 py-2 text-on-surface-variant hover:bg-surface-container-high rounded-lg font-body-md text-body-md transition-colors">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-[18px]" data-icon="account_balance">account_balance</span>
                                History
                            </div>
<span class="text-xs text-outline">4</span>
</button>
</li>
<li>
<button class="w-full flex items-center justify-between px-3 py-2 text-on-surface-variant hover:bg-surface-container-high rounded-lg font-body-md text-body-md transition-colors">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-[18px]" data-icon="trending_up">trending_up</span>
                                Business
                            </div>
<span class="text-xs text-outline">3</span>
</button>
</li>
<li>
<button class="w-full flex items-center justify-between px-3 py-2 text-on-surface-variant hover:bg-surface-container-high rounded-lg font-body-md text-body-md transition-colors">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-[18px]" data-icon="self_improvement">self_improvement</span>
                                Self-Help
                            </div>
<span class="text-xs text-outline">3</span>
</button>
</li>
<li>
<button class="w-full flex items-center justify-between px-3 py-2 text-on-surface-variant hover:bg-surface-container-high rounded-lg font-body-md text-body-md transition-colors">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-[18px]" data-icon="more_horiz">more_horiz</span>
                                Others
                            </div>
<span class="text-xs text-outline">-</span>
</button>
</li>
</ul>
</div>
<!-- Availability -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-4 shadow-[0px_4px_12px_rgba(0,0,0,0.05)]">
<h3 class="font-headline-sm text-headline-sm mb-4 px-2">Availability</h3>
<div class="space-y-3 px-2">
<label class="flex items-center gap-3 cursor-pointer">
<input checked="" class="text-primary focus:ring-primary h-4 w-4 border-outline-variant" name="availability" type="radio"/>
<span class="font-body-md text-body-md text-on-surface">All Books</span>
</label>
<label class="flex items-center gap-3 cursor-pointer">
<input class="text-primary focus:ring-primary h-4 w-4 border-outline-variant" name="availability" type="radio"/>
<span class="font-body-md text-body-md text-on-surface">Available Only</span>
</label>
<label class="flex items-center gap-3 cursor-pointer">
<input class="text-primary focus:ring-primary h-4 w-4 border-outline-variant" name="availability" type="radio"/>
<span class="font-body-md text-body-md text-on-surface">Out of Stock</span>
</label>
</div>
</div>
</aside>
<!-- Main Book Grid Area -->
<div class="flex-1">
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
<div class="flex items-baseline gap-2">
<h2 class="font-headline-sm text-headline-sm text-on-surface">All Books</h2>
<span class="font-body-md text-body-md text-on-surface-variant">1,250+ books available</span>
</div>
<div class="flex items-center gap-2">
<span class="font-caption text-caption text-on-surface-variant">Sort by:</span>
<select class="font-body-md text-body-md border-outline-variant rounded-lg bg-surface-container-lowest text-on-surface py-2 pl-3 pr-8 focus:ring-primary focus:border-primary shadow-sm">
<option>Latest Added</option>
<option>Title A-Z</option>
<option>Title Z-A</option>
<option>Most Popular</option>
</select>
</div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
<!-- Book Card 1 -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-4 shadow-[0px_4px_12px_rgba(0,0,0,0.05)] hover:shadow-lg transition-shadow duration-200 flex flex-col">
<div class="aspect-[2/3] w-full bg-surface-variant rounded-lg mb-4 overflow-hidden relative">
<img alt="The Hobbit" class="object-cover w-full h-full" data-alt="A stylized book cover illustration showing a mountain landscape with an ancient ring, reminiscent of classic high fantasy literature in a professional digital art style." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBWEdgnWeTDyYPLG4lH6p3u7BfbgXXScUqllD0Mjwb2KWnkS-4UQvg5aOgjtxj1gsFpFrFugA618s0u6npePzlvxXanLfrXvjqkvkSqsB80Ik_Gfd97KG8frBnkStXRhcrVPWAbiqI_LKrZblvB81rbABhcAyNoj0TGvmYr8t2dTKQOqCDq2kPl97u8pKnyc-5DRzuJXeaVl42COUhmmaj8VL0Fm06jMtFMIJVx_ZsUkZup0VerpULQ6dy0pZoNMogU-e5RxoZiz2U"/>
</div>
<h4 class="font-label-md text-label-md text-on-surface mb-1 truncate">The Hobbit</h4>
<p class="font-caption text-caption text-on-surface-variant mb-2">J.R.R. Tolkien</p>
<div class="flex flex-wrap gap-1 mb-3">
<span class="bg-blue-100 text-blue-800 text-[10px] px-2 py-0.5 rounded font-medium">Fiction</span>
</div>
<p class="font-caption text-caption text-green-700 font-medium mb-4 mt-auto">Stock: 4 available</p>
<button class="w-full py-2 border-[1.5px] border-primary text-primary rounded-lg font-label-md text-label-md hover:bg-primary/5 transition-colors">View Details</button>
</div>
<!-- Book Card 2 -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-4 shadow-[0px_4px_12px_rgba(0,0,0,0.05)] hover:shadow-lg transition-shadow duration-200 flex flex-col">
<div class="aspect-[2/3] w-full bg-surface-variant rounded-lg mb-4 overflow-hidden relative">
<img alt="Atomic Habits" class="object-cover w-full h-full" data-alt="A clean, minimalist book cover design with bold typography on a light beige background, typical of modern self-improvement and productivity non-fiction books." src="https://lh3.googleusercontent.com/aida-public/AB6AXuC3tX6SDRCtD1_Z2ptmncFoedzRykLIVqrK74qmk4j96Kkkgs2y_9JNAe8tE-wF7Fnv_VC3ZB0ErYr19XtJrvor7_c8drrsbyO6oBdMpAB6vOfoZhEOnw8uJVEiSzRS1ouA2yi10qocTvzwYcJ4hTq2wbXYxeZacsarAL7Lr_73dqjK8umb566VGQzTLlpdCdvJuaRVQfXLPcM-CBcI1_O0Bk7RF7-Iki6fm1PaYf6Y-euN01c1Dcq9yM7HcU-HureRMgWRAh8ThW8"/>
</div>
<h4 class="font-label-md text-label-md text-on-surface mb-1 truncate">Atomic Habits</h4>
<p class="font-caption text-caption text-on-surface-variant mb-2">James Clear</p>
<div class="flex flex-wrap gap-1 mb-3">
<span class="bg-purple-100 text-purple-800 text-[10px] px-2 py-0.5 rounded font-medium">Self-Help</span>
</div>
<p class="font-caption text-caption text-green-700 font-medium mb-4 mt-auto">Stock: 6 available</p>
<button class="w-full py-2 border-[1.5px] border-primary text-primary rounded-lg font-label-md text-label-md hover:bg-primary/5 transition-colors">View Details</button>
</div>
<!-- Book Card 3 -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-4 shadow-[0px_4px_12px_rgba(0,0,0,0.05)] hover:shadow-lg transition-shadow duration-200 flex flex-col">
<div class="aspect-[2/3] w-full bg-surface-variant rounded-lg mb-4 overflow-hidden relative">
<img alt="A Brief History of Time" class="object-cover w-full h-full" data-alt="A book cover design featuring a starry night sky or cosmos background with serious, academic typography representing scientific literature." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAoE6QpQ0x2Jt-dRPfIIqnktIF70galcKjavlw-bladSmMOyuQnqrFGjj2lGP2P8riYriDAhCWbWCUdLUmQI0ypLgF38aUe5POilhdksbTkaMbzcupssRZ6hlBFCSQdEONf21sic8jMbWRCC-XdRAeqJuUclwW76FIPsldiUu2kRsUBOZChbQDNvfyZpm_OclcoLNiewyEbiVNMKmmq5CiiPVtjZAhlr_jOCK9lJystHVS_FSCfd8sFl_4dDlml1LkOCd9M24xRyx8"/>
</div>
<h4 class="font-label-md text-label-md text-on-surface mb-1 truncate">A Brief History of Time</h4>
<p class="font-caption text-caption text-on-surface-variant mb-2">Stephen Hawking</p>
<div class="flex flex-wrap gap-1 mb-3">
<span class="bg-green-100 text-green-800 text-[10px] px-2 py-0.5 rounded font-medium">Science</span>
</div>
<p class="font-caption text-caption text-yellow-600 font-medium mb-4 mt-auto">Stock: 3 available</p>
<button class="w-full py-2 border-[1.5px] border-primary text-primary rounded-lg font-label-md text-label-md hover:bg-primary/5 transition-colors">View Details</button>
</div>
<!-- Book Card 4 -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-4 shadow-[0px_4px_12px_rgba(0,0,0,0.05)] hover:shadow-lg transition-shadow duration-200 flex flex-col">
<div class="aspect-[2/3] w-full bg-surface-variant rounded-lg mb-4 overflow-hidden relative">
<img alt="Sapiens" class="object-cover w-full h-full" data-alt="An intellectual book cover with a thumbprint graphic on an off-white background, signaling anthropological or historical non-fiction themes." src="https://lh3.googleusercontent.com/aida-public/AB6AXuByCTB2-AEdgL_xrtATLnV7uLviVrdG-copNY65FnwfNyiKYY5WfK-awsyq-Y7GU3U5Lr-3PAevj6xHV3vaFpcCOokRHMcj3hE_XkUfowI2KJdNBOx6ZQV6Z-n3tg6qHpKlveoZl95UAhsjShWR0OV2LzbqwispBj_0dTmPO5D9Se6AOSooY0donmVgamJop46TBsyxNiCftyA7g-Xju97P680OyLZRg8ah_CJux4uM_jeh8T80kbMP7oWj8B9-Y61YQ-sGwaZKP2Q"/>
</div>
<h4 class="font-label-md text-label-md text-on-surface mb-1 truncate">Sapiens</h4>
<p class="font-caption text-caption text-on-surface-variant mb-2">Yuval Noah Harari</p>
<div class="flex flex-wrap gap-1 mb-3">
<span class="bg-orange-100 text-orange-800 text-[10px] px-2 py-0.5 rounded font-medium">History</span>
</div>
<p class="font-caption text-caption text-yellow-600 font-medium mb-4 mt-auto">Stock: 2 available</p>
<button class="w-full py-2 border-[1.5px] border-primary text-primary rounded-lg font-label-md text-label-md hover:bg-primary/5 transition-colors">View Details</button>
</div>
<!-- Book Card 5 -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-4 shadow-[0px_4px_12px_rgba(0,0,0,0.05)] hover:shadow-lg transition-shadow duration-200 flex flex-col">
<div class="aspect-[2/3] w-full bg-surface-variant rounded-lg mb-4 overflow-hidden relative">
<img alt="Clean Code" class="object-cover w-full h-full" data-alt="A dark, technical book cover showing lines of abstract code or binary patterns, representing computer science and software engineering literature." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCeCChDsclMHJzEhN4ucZKRSOuqIa8jX0RymrxzXi-nKVz8LCJsXgpEq66JPXWXSUXqxArN8rUYoRX-NBFq-csatxoZZD0-xUxdscpi0gUbrGA-5sUX5TLrQgkFkCxDOlPr6W9OuZsEpqIUG-8BxJxYPcM7mcGPdeSQT0QbM91RDqP3YqkyacD1wLAwJh8yTpm05pUwl57J5XXvpT7j2femCSVhoTu-h36eW8AzepY36KJrPGR2zks_kCMqq1BYAgrPvssWK0Dkc90"/>
</div>
<h4 class="font-label-md text-label-md text-on-surface mb-1 truncate">Clean Code</h4>
<p class="font-caption text-caption text-on-surface-variant mb-2">Robert C. Martin</p>
<div class="flex flex-wrap gap-1 mb-3">
<span class="bg-blue-100 text-blue-800 text-[10px] px-2 py-0.5 rounded font-medium">Technology</span>
</div>
<p class="font-caption text-caption text-green-700 font-medium mb-4 mt-auto">Stock: 5 available</p>
<button class="w-full py-2 border-[1.5px] border-primary text-primary rounded-lg font-label-md text-label-md hover:bg-primary/5 transition-colors">View Details</button>
</div>
<!-- Book Card 6 -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-4 shadow-[0px_4px_12px_rgba(0,0,0,0.05)] hover:shadow-lg transition-shadow duration-200 flex flex-col">
<div class="aspect-[2/3] w-full bg-surface-variant rounded-lg mb-4 overflow-hidden relative">
<img alt="The Alchemist" class="object-cover w-full h-full" data-alt="A warm, orange-toned book cover featuring desert dunes or a sun motif, suggesting a mystical or philosophical fiction novel." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCssWn4eXtNYYQaxNP4dqkvXQJLdLwiY5GFCcf7qLkg-j5c4SdzPPmn4qmUKKppCMz8CvgXlP44ULs_k9U_IA2ylLymObIToV6zG1QW5p1AhFl64Vkpccq-QvEKjdxCzLZbxVgFJN67h5yX7mfpwXpMj1WfVDifUe6Ati76mfx-tv7KWQmJEtKOGLMmdDR8FawgPmoVZOX8Z6VEJojWRrqky895F7W68H-v0f5By_0Y9upVV2vvjtHrXPkibOU5QGzDdKsVnoPHX1g"/>
</div>
<h4 class="font-label-md text-label-md text-on-surface mb-1 truncate">The Alchemist</h4>
<p class="font-caption text-caption text-on-surface-variant mb-2">Paulo Coelho</p>
<div class="flex flex-wrap gap-1 mb-3">
<span class="bg-blue-100 text-blue-800 text-[10px] px-2 py-0.5 rounded font-medium">Fiction</span>
</div>
<p class="font-caption text-caption text-green-700 font-medium mb-4 mt-auto">Stock: 7 available</p>
<button class="w-full py-2 border-[1.5px] border-primary text-primary rounded-lg font-label-md text-label-md hover:bg-primary/5 transition-colors">View Details</button>
</div>
<!-- Book Card 7 -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-4 shadow-[0px_4px_12px_rgba(0,0,0,0.05)] hover:shadow-lg transition-shadow duration-200 flex flex-col">
<div class="aspect-[2/3] w-full bg-surface-variant rounded-lg mb-4 overflow-hidden relative">
<img alt="Thinking, Fast and Slow" class="object-cover w-full h-full" data-alt="A minimalist book cover with a simple pencil icon and clean typography, representing psychological and business non-fiction." src="https://lh3.googleusercontent.com/aida-public/AB6AXuApqFdH6HGlfyO0sJcW5pBPuSb_FMsGih0eyu_kZiCWoheXnioqzydbtWA8XaiZNFvuZICeTTQ49POsIDtiOdH4idGAy5be2Vg9rAHKCs89z7KdMxXfJwL56A1WnLcrqbtiF3uk29_yJRpdO3Jyx2HPh_hw1s8_XZTE_hKsdMygsBJdvMTj1WkQlFjjIbgo-25db2qbjejVbYEpJGrghhjsSpgJDlcOJ1et1BbSysNnVtaWbbsBoSbXTG-y0al7lIvwPDzbi12R9BQ"/>
</div>
<h4 class="font-label-md text-label-md text-on-surface mb-1 truncate">Thinking, Fast and Slow</h4>
<p class="font-caption text-caption text-on-surface-variant mb-2">Daniel Kahneman</p>
<div class="flex flex-wrap gap-1 mb-3">
<span class="bg-orange-100 text-orange-800 text-[10px] px-2 py-0.5 rounded font-medium">Business</span>
</div>
<p class="font-caption text-caption text-green-700 font-medium mb-4 mt-auto">Stock: 4 available</p>
<button class="w-full py-2 border-[1.5px] border-primary text-primary rounded-lg font-label-md text-label-md hover:bg-primary/5 transition-colors">View Details</button>
</div>
<!-- Book Card 8 -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-4 shadow-[0px_4px_12px_rgba(0,0,0,0.05)] hover:shadow-lg transition-shadow duration-200 flex flex-col">
<div class="aspect-[2/3] w-full bg-surface-variant rounded-lg mb-4 overflow-hidden relative">
<img alt="1984" class="object-cover w-full h-full" data-alt="A stark, dystopian book cover design with a large watchful eye graphic, representing classic political fiction literature." src="https://lh3.googleusercontent.com/aida-public/AB6AXuD3nSirfxSMb_1gHgDDQHz4vopEl2Fzcg-kdfV4xHzVWgiWJa4YDOXDqvYMvwa-TVJl0sKAvco_eA1nngfJojqCGppE99GEISO6d70PnUl-f4AhGsARkvwA9hnu1SbHtdVpaeaJCmBtaVDHjTmj5K2pGF2kbMVU4OsezuqxdMU5Ep5DyMeNf95UZn3PLfzgteQMJzXWJwmqzcEm0D3oFVkH_S_ZMG9ObEd4doahZHuLowmi2Bu7VeRHPnEfpKKxte02bz_TPK2_sLo"/>
</div>
<h4 class="font-label-md text-label-md text-on-surface mb-1 truncate">1984</h4>
<p class="font-caption text-caption text-on-surface-variant mb-2">George Orwell</p>
<div class="flex flex-wrap gap-1 mb-3">
<span class="bg-blue-100 text-blue-800 text-[10px] px-2 py-0.5 rounded font-medium">Fiction</span>
</div>
<p class="font-caption text-caption text-yellow-600 font-medium mb-4 mt-auto">Stock: 3 available</p>
<button class="w-full py-2 border-[1.5px] border-primary text-primary rounded-lg font-label-md text-label-md hover:bg-primary/5 transition-colors">View Details</button>
</div>
</div>
</div>
</main>
<!-- Footer -->
<footer class="bg-surface-container-lowest w-full py-stack-md mt-stack-lg border-t border-outline-variant">
<div class="flex flex-col md:flex-row justify-between items-center px-margin-page max-w-container-max-width mx-auto gap-4">
<div class="font-caption text-caption text-secondary">
                © 2024 LibManage Systems. All rights reserved.
            </div>
<div class="flex gap-6">
<a class="font-caption text-caption text-on-surface-variant hover:text-primary hover:underline transition-all cursor-default" href="#">Privacy Policy</a>
<a class="font-caption text-caption text-on-surface-variant hover:text-primary hover:underline transition-all cursor-default" href="#">Terms of Service</a>
<a class="font-caption text-caption text-on-surface-variant hover:text-primary hover:underline transition-all cursor-default" href="#">Contact Support</a>
</div>
</div>
</footer>
</body></html>