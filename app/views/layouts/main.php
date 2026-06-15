<?php $title = $title ?? 'Library Loan Management System'; ?>
<?php $isLandingPage = $isLandingPage ?? false; ?>
<?php $bodyClass = $bodyClass ?? ($isLandingPage ? 'bg-background font-body-md text-on-surface' : ''); ?>
<?php $mainClass = $mainClass ?? ($isLandingPage ? '' : 'container'); ?>
<?php
$appShellConfig = [
    'auth' => [
        'isAuthenticated' => auth_check(),
        'isAdmin' => auth_is_admin(),
        'userId' => auth_user()['id'] ?? null,
    ],
    'basket' => [
        'enabled' => auth_check() && ! auth_is_admin(),
        'storageKey' => \App\Services\BorrowBasketService::STORAGE_KEY,
        'maxItems' => \App\Services\BorrowBasketService::MAX_ITEMS_PER_REQUEST,
        'storageTtlHours' => \App\Services\BorrowBasketService::STORAGE_TTL_HOURS,
        'basketUrl' => url('/loans/request'),
        'browseUrl' => url('/books'),
    ],
    'routes' => [
        'currentPath' => current_path(),
        'loginUrl' => url('/login'),
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title); ?> | <?= htmlspecialchars(app_config('name')); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <script>
            tailwind.config = {
                corePlugins: {
                    preflight: false
                },
                darkMode: "class",
                theme: {
                    extend: {
                        colors: {
                            error: "#ba1a1a",
                            "on-secondary-container": "#4f627e",
                            "on-primary-fixed-variant": "#0d409e",
                            "on-secondary-fixed-variant": "#354862",
                            "on-primary-fixed": "#001849",
                            tertiary: "#5c1e00",
                            "on-secondary": "#ffffff",
                            "on-error": "#ffffff",
                            "surface-bright": "#faf8ff",
                            "on-surface": "#1a1b21",
                            "primary-fixed": "#dae1ff",
                            "on-error-container": "#93000a",
                            background: "#faf8ff",
                            "outline-variant": "#c4c6d4",
                            "surface-container-lowest": "#ffffff",
                            "on-tertiary": "#ffffff",
                            secondary: "#4d5f7b",
                            "secondary-fixed": "#d4e3ff",
                            "surface-variant": "#e2e2ea",
                            "on-primary-container": "#9db6ff",
                            "inverse-primary": "#b3c5ff",
                            "surface-container-low": "#f3f3fb",
                            "tertiary-fixed-dim": "#ffb598",
                            "surface-container": "#eeedf6",
                            "secondary-container": "#cbdeff",
                            "surface-tint": "#3159b7",
                            "on-secondary-fixed": "#061c35",
                            "on-tertiary-fixed-variant": "#7e2c00",
                            outline: "#747684",
                            "error-container": "#ffdad6",
                            "surface-dim": "#dad9e2",
                            surface: "#faf8ff",
                            "secondary-fixed-dim": "#b4c8e8",
                            primary: "#002c78",
                            "on-tertiary-container": "#ff9f78",
                            "on-primary": "#ffffff",
                            "on-background": "#1a1b21",
                            "inverse-surface": "#2f3037",
                            "primary-container": "#1142a0",
                            "tertiary-container": "#812d00",
                            "tertiary-fixed": "#ffdbce",
                            "on-tertiary-fixed": "#370e00",
                            "primary-fixed-dim": "#b3c5ff",
                            "inverse-on-surface": "#f1f0f9",
                            "surface-container-highest": "#e2e2ea",
                            "on-surface-variant": "#434652",
                            "surface-container-high": "#e8e7f0"
                        },
                        borderRadius: {
                            DEFAULT: "0.125rem",
                            lg: "0.25rem",
                            xl: "0.5rem",
                            full: "0.75rem"
                        },
                        spacing: {
                            gutter: "24px",
                            "stack-md": "16px",
                            "margin-page": "40px",
                            unit: "8px",
                            "stack-sm": "8px",
                            "container-max-width": "1200px",
                            "stack-lg": "32px"
                        },
                        fontFamily: {
                            manrope: ["Manrope"],
                            inter: ["Inter"],
                            "headline-md": ["Manrope"],
                            "label-md": ["Inter"],
                            "body-md": ["Inter"],
                            "body-lg": ["Inter"],
                            "display-lg": ["Manrope"],
                            caption: ["Inter"],
                            "headline-sm": ["Manrope"]
                        },
                        fontSize: {
                            "headline-md": ["32px", { lineHeight: "1.3", fontWeight: "700" }],
                            "label-md": ["14px", { lineHeight: "1.2", letterSpacing: "0.02em", fontWeight: "600" }],
                            "body-md": ["16px", { lineHeight: "1.6", fontWeight: "400" }],
                            "body-lg": ["18px", { lineHeight: "1.6", fontWeight: "400" }],
                            "display-lg": ["48px", { lineHeight: "1.2", fontWeight: "800" }],
                            caption: ["12px", { lineHeight: "1.4", fontWeight: "400" }],
                            "headline-sm": ["24px", { lineHeight: "1.4", fontWeight: "600" }]
                        }
                    }
                }
            };
        </script>
                <style>
                    /* ================================================
                       SCROLLBAR UNIFORMITY — prevent navbar shift
                       ================================================ */
                    html,
                    body {
                        max-width: 100%;
                        overflow-x: hidden;
                        overflow-x: clip;
                        overscroll-behavior-x: none;
                        overscroll-behavior-y: none;
                    }
                    html {
                        overflow-y: scroll;
                        scrollbar-width: none;
                        -ms-overflow-style: none;
                    }
                    html::-webkit-scrollbar {
                        width: 0;
                        height: 0;
                        display: none;
                    }

                    img,
                    video,
                    canvas,
                    svg,
                    iframe {
                        max-width: 100%;
                    }

                    .material-symbols-outlined {
                        font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24;
                    }
                    .hero-overlay {
                        background: linear-gradient(rgba(0, 44, 120, 0.8), rgba(0, 44, 120, 0.6));
                    }
                </style>
    <link rel="stylesheet" href="<?= htmlspecialchars(asset('css/app.css') . '?v=' . filemtime(BASE_PATH . '/public/assets/css/app.css')); ?>">
</head>
<body<?= $bodyClass !== '' ? ' class="' . htmlspecialchars($bodyClass) . '"' : ''; ?>>
    <script id="app-shell-config" type="application/json"><?= json_encode($appShellConfig, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?></script>
    <?php require BASE_PATH . '/app/views/partials/navbar-landing.php'; ?>

    <main<?= $mainClass !== '' ? ' class="' . htmlspecialchars($mainClass) . '"' : ''; ?>>
        <?php if (!empty($status) || !empty($error)): ?>
            <div id="server-flash-messages" 
                 data-status="<?= htmlspecialchars((string) $status); ?>" 
                 data-error="<?= htmlspecialchars((string) $error); ?>" 
                 hidden></div>
        <?php endif; ?>

        <?php require $viewPath; ?>
    </main>

    <?php if (auth_check() && ! auth_is_admin()): ?>
        <a
            class="basket-mobile-bar"
            data-basket-mobile-bar
            href="<?= htmlspecialchars(url('/loans/request')); ?>"
            hidden
        >
            <span class="basket-mobile-bar__label">
                <span class="material-symbols-outlined">shopping_basket</span>
                Basket
            </span>
            <span class="basket-mobile-bar__summary" data-basket-mobile-summary>0 items selected</span>
            <span class="basket-mobile-bar__action">Review</span>
        </a>
    <?php endif; ?>

    <?php require BASE_PATH . '/app/views/partials/footer-landing.php'; ?>
    <script src="<?= htmlspecialchars(asset('js/app.js') . '?v=' . filemtime(BASE_PATH . '/public/assets/js/app.js')); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const flashContainer = document.getElementById('server-flash-messages');
            if (flashContainer && typeof showToast === 'function') {
                const status = flashContainer.dataset.status;
                const error = flashContainer.dataset.error;
                
                if (status) {
                    setTimeout(() => showToast(status, 'success'), 100);
                }
                if (error) {
                    setTimeout(() => showToast(error, 'danger'), status ? 600 : 100);
                }
            }
        });
    </script>
</body>
</html>

