<!DOCTYPE html>

<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Registration - Lumina Library</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "inverse-primary": "#b1c5ff",
                        "inverse-on-surface": "#eaf1ff",
                        "surface-bright": "#f8f9ff",
                        "primary-fixed": "#dae2ff",
                        "on-primary-container": "#a5bdff",
                        "on-secondary-fixed-variant": "#314671",
                        "tertiary-fixed": "#e0e3e5",
                        "on-secondary-container": "#415681",
                        "on-surface-variant": "#434653",
                        "background": "#f8f9ff",
                        "outline-variant": "#c3c6d5",
                        "on-error": "#ffffff",
                        "secondary-fixed-dim": "#b1c6f9",
                        "primary": "#00327d",
                        "on-tertiary-fixed": "#191c1e",
                        "surface-container-low": "#eff4ff",
                        "tertiary": "#343739",
                        "tertiary-fixed-dim": "#c4c7c9",
                        "surface-container": "#e5eeff",
                        "surface-container-high": "#dce9ff",
                        "on-tertiary-container": "#bcbfc1",
                        "surface": "#f8f9ff",
                        "error-container": "#ffdad6",
                        "on-primary-fixed-variant": "#00419e",
                        "on-primary": "#ffffff",
                        "on-error-container": "#93000a",
                        "tertiary-container": "#4b4e50",
                        "outline": "#737784",
                        "surface-variant": "#d3e4fe",
                        "secondary-fixed": "#d8e2ff",
                        "surface-container-lowest": "#ffffff",
                        "on-tertiary-fixed-variant": "#444749",
                        "on-primary-fixed": "#001946",
                        "on-secondary-fixed": "#001a42",
                        "on-secondary": "#ffffff",
                        "inverse-surface": "#213145",
                        "surface-dim": "#cbdbf5",
                        "on-surface": "#0b1c30",
                        "on-background": "#0b1c30",
                        "primary-container": "#0047ab",
                        "on-tertiary": "#ffffff",
                        "secondary-container": "#b7ccfe",
                        "surface-tint": "#2559bd",
                        "secondary": "#495e8a",
                        "surface-container-highest": "#d3e4fe",
                        "error": "#ba1a1a",
                        "primary-fixed-dim": "#b1c5ff"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "stack-unit": "0.5rem",
                        "gutter": "1.5rem",
                        "form-max-width": "640px",
                        "sidebar-width": "35%",
                        "margin-mobile": "1rem",
                        "margin-desktop": "3rem"
                    },
                    "fontFamily": {
                        "headline-lg-mobile": ["Plus Jakarta Sans"],
                        "label-sm": ["Plus Jakarta Sans"],
                        "body-md": ["Plus Jakarta Sans"],
                        "title-md": ["Plus Jakarta Sans"],
                        "headline-lg": ["Plus Jakarta Sans"],
                        "display-lg": ["Plus Jakarta Sans"],
                        "label-md": ["Plus Jakarta Sans"]
                    },
                    "fontSize": {
                        "headline-lg-mobile": ["24px", { "lineHeight": "32px", "fontWeight": "700" }],
                        "label-sm": ["12px", { "lineHeight": "16px", "fontWeight": "400" }],
                        "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "title-md": ["18px", { "lineHeight": "24px", "fontWeight": "600" }],
                        "headline-lg": ["28px", { "lineHeight": "36px", "fontWeight": "700" }],
                        "display-lg": ["40px", { "lineHeight": "48px", "fontWeight": "700" }],
                        "label-md": ["14px", { "lineHeight": "20px", "fontWeight": "600" }]
                    }
                }
            }
        }
    </script>
<style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .soft-shadow {
            box-shadow: 0 40px 60px -15px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="bg-surface text-on-surface min-h-screen flex flex-col md:flex-row font-body-md">
<!-- Left Narrative Anchor (Sidebar) -->
<div class="hidden md:flex md:w-[35%] bg-primary flex-col justify-between p-12 text-on-primary relative overflow-hidden">
<!-- Background Overlay -->
<div class="absolute inset-0 z-0 bg-gradient-to-b from-primary to-transparent opacity-90"></div>
<img alt="Library Background" class="absolute inset-0 z-[-1] object-cover w-full h-full opacity-30 mix-blend-overlay" data-alt="A beautifully lit, cozy library setting with warm, ambient lighting. A stack of hardbound books rests on a rich, dark wooden table, an open book inviting reading. The background is softly out of focus, showing rows of bookshelves in a modern, sophisticated library environment. Deep indigo blues and warm highlights create an inviting, intellectual atmosphere." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBoHanIrYaPYfuaEqWCE-Oxy6zDu7qywH36MZL2ru3mRjg5jgvY8sCxfYxtVq9t2TFJWXoJH2lBNYrQJ_2JXr-fCe69Y9qx0SbKUoyGT_bl7CONtiTgTg8KSKWaf4hH4KLP580F5UelvDFtTHQfsapEdfifwCsuhw0k34XU8AWvw8wwf3bqgA5TJik0VLK5ohebMo7kXBRGcXV86FRW5MS6oI1iHSO9JDY-zgJKz8mMFtC1yC0n9oBVl1pAQHJIFyjE34sU_pe5KpWh"/>
<div class="relative z-10">
<!-- Brand Logo -->
<div class="flex items-center gap-4 mb-16">
<div class="w-12 h-12 bg-on-primary rounded-xl flex items-center justify-center text-primary">
<span class="material-symbols-outlined text-3xl" data-weight="fill">menu_book</span>
</div>
<div>
<h1 class="font-headline-lg text-headline-lg m-0 leading-none">Lumina Library</h1>
<p class="font-label-sm text-label-sm text-primary-fixed mt-1">Knowledge for All</p>
</div>
</div>
<!-- Welcome Message -->
<div>
<h2 class="font-display-lg text-display-lg mb-4">Welcome!</h2>
<p class="font-title-md text-title-md text-primary-fixed-dim max-w-sm">
                    Create your account to get started and explore our library.
                </p>
<div class="w-12 h-1 bg-primary-container mt-6"></div>
</div>
</div>
<!-- Quote -->
<div class="relative z-10 mt-auto pt-12">
<span class="material-symbols-outlined text-4xl text-primary-container mb-2 block opacity-70">format_quote</span>
<blockquote class="font-title-md text-title-md italic text-primary-fixed mb-2">
                "Books are a uniquely portable magic."
            </blockquote>
<cite class="font-label-sm text-label-sm text-primary-fixed-dim not-italic">- Stephen King</cite>
</div>
</div>
<!-- Right Functional Surface (Form Area) -->
<div class="flex-1 flex flex-col min-h-screen relative">
<!-- Top Nav Link -->
<div class="absolute top-0 right-0 p-6 md:p-8 flex justify-end w-full z-20">
<p class="font-body-md text-body-md text-on-surface-variant flex items-center gap-2">
                Already have an account? 
                <a class="text-primary-container font-title-md hover:underline transition-all" href="login.php">Login</a>
</p>
</div>
<!-- Main Form Container -->
<main class="flex-1 flex items-center justify-center p-4 sm:p-8 pt-24 pb-12 w-full max-w-7xl mx-auto">
<!-- Registration Card -->
<div class="bg-surface-container-lowest rounded-xl soft-shadow p-8 md:p-12 w-full max-w-[640px] relative z-10">
<!-- Card Header -->
<div class="text-center mb-10">
<h2 class="font-headline-lg text-headline-lg text-on-surface mb-2">Create an Account</h2>
<p class="font-body-md text-body-md text-on-surface-variant">Join Lumina Library and start your reading journey.</p>
<div class="w-16 h-[3px] bg-primary-container mx-auto mt-6"></div>
</div>
<!-- PHP Error Handling (Simulated Output) -->
<!--?php if (isset($errors) && !empty($errors)): ?-->
<div class="bg-error-container text-on-error-container p-4 rounded-lg mb-8 font-label-md text-label-md">
<ul class="list-disc pl-5">
<!--?php foreach ($errors as $error): ?-->
<li><!--?= htmlspecialchars($error) ?--></li>
<!--?php endforeach; ?-->
</ul>
</div>
<!--?php endif; ?-->
<!-- Form -->
<form action="register.php" class="space-y-6" method="POST">
<!-- CSRF Token -->
<input name="csrf_token" type="hidden" value="&lt;?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?&gt;"/>
<!-- Full Name -->
<div class="space-y-2">
<label class="block font-label-md text-label-md text-on-surface" for="fullName">Full Name</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">person</span>
<input class="w-full pl-12 pr-4 py-3 rounded-lg border border-outline-variant bg-surface focus:border-primary-container focus:ring-1 focus:ring-primary-container focus:outline-none transition-colors font-body-md" id="fullName" name="full_name" placeholder="Enter your full name" required="" type="text" value="&lt;?= htmlspecialchars($_POST['full_name'] ?? '') ?&gt;"/>
</div>
</div>
<!-- Email Address -->
<div class="space-y-2">
<label class="block font-label-md text-label-md text-on-surface" for="email">Email Address</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">mail</span>
<input class="w-full pl-12 pr-4 py-3 rounded-lg border border-outline-variant bg-surface focus:border-primary-container focus:ring-1 focus:ring-primary-container focus:outline-none transition-colors font-body-md" id="email" name="email" placeholder="Enter your email address" required="" type="email" value="&lt;?= htmlspecialchars($_POST['email'] ?? '') ?&gt;"/>
</div>
</div>
<!-- Password -->
<div class="space-y-2">
<label class="block font-label-md text-label-md text-on-surface" for="password">Password</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">lock</span>
<input class="w-full pl-12 pr-12 py-3 rounded-lg border border-outline-variant bg-surface focus:border-primary-container focus:ring-1 focus:ring-primary-container focus:outline-none transition-colors font-body-md" id="password" name="password" placeholder="Create a password" required="" type="password"/>
<button aria-label="Toggle password visibility" class="absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface transition-colors" type="button">
<span class="material-symbols-outlined">visibility_off</span>
</button>
</div>
<p class="font-label-sm text-label-sm text-on-surface-variant mt-1">Password must be at least 6 characters.</p>
</div>
<!-- Confirm Password -->
<div class="space-y-2">
<label class="block font-label-md text-label-md text-on-surface" for="confirmPassword">Confirm Password</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">lock</span>
<input class="w-full pl-12 pr-12 py-3 rounded-lg border border-outline-variant bg-surface focus:border-primary-container focus:ring-1 focus:ring-primary-container focus:outline-none transition-colors font-body-md" id="confirmPassword" name="confirm_password" placeholder="Confirm your password" required="" type="password"/>
<button aria-label="Toggle password visibility" class="absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface transition-colors" type="button">
<span class="material-symbols-outlined">visibility_off</span>
</button>
</div>
</div>
<!-- Submit Button -->
<div class="pt-4">
<button class="w-full bg-primary-container text-on-primary font-title-md py-4 rounded-lg hover:bg-on-primary-fixed-variant transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-container" type="submit">
                            Register
                        </button>
</div>
</form>
<!-- Footer Terms -->
<div class="mt-8 text-center">
<p class="font-label-sm text-label-sm text-on-surface-variant">
                        By registering, you agree to our 
                        <a class="text-primary-container hover:underline" href="#">Terms of Use</a> and 
                        <a class="text-primary-container hover:underline" href="#">Privacy Policy</a>.
                    </p>
</div>
</div>
</main>
</div>
</body></html>