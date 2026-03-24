<?php $title = $title ?? 'Library Loan Management System'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title); ?> | <?= htmlspecialchars(app_config('name')); ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars(asset('css/app.css')); ?>">
</head>
<body>
    <?php require BASE_PATH . '/app/views/partials/navbar.php'; ?>

    <main class="container">
        <?php require $viewPath; ?>
    </main>

    <?php require BASE_PATH . '/app/views/partials/footer.php'; ?>
    <script src="<?= htmlspecialchars(asset('js/app.js')); ?>"></script>
</body>
</html>

