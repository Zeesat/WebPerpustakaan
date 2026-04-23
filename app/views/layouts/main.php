<?php $title = $title ?? 'Library Loan Management System'; ?>
<?php $isLandingPage = $isLandingPage ?? false; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title); ?> | <?= htmlspecialchars(app_config('name')); ?></title>
    <?php if ($isLandingPage): ?>
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&amp;family=Inter:wght@400;600&amp;display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
        <script>
            tailwind.config = {
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
            .material-symbols-outlined {
                font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24;
            }
            .hero-overlay {
                background: linear-gradient(rgba(0, 44, 120, 0.8), rgba(0, 44, 120, 0.6));
            }
        </style>
    <?php else: ?>
        <link rel="stylesheet" href="<?= htmlspecialchars(asset('css/app.css')); ?>">
    <?php endif; ?>
</head>
<body<?= $isLandingPage ? ' class="bg-background font-body-md text-on-surface"' : ''; ?>>
    <?php if ($isLandingPage): ?>
        <?php require BASE_PATH . '/app/views/partials/navbar-landing.php'; ?>
    <?php else: ?>
        <?php require BASE_PATH . '/app/views/partials/navbar.php'; ?>
    <?php endif; ?>

    <main<?= $isLandingPage ? '' : ' class="container"' ?>>
        <?php require $viewPath; ?>
    </main>

    <?php if ($isLandingPage): ?>
        <?php require BASE_PATH . '/app/views/partials/footer-landing.php'; ?>
    <?php else: ?>
        <?php require BASE_PATH . '/app/views/partials/footer.php'; ?>
    <?php endif; ?>
    <script src="<?= htmlspecialchars(asset('js/app.js')); ?>"></script>
</body>
</html>

