<section class="page-header" style="display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
    <div>
        <h1 class="page-title"><?= $mode === 'create' ? 'Add New Book' : 'Edit Book'; ?></h1>
        <p class="page-subtitle"><?= $mode === 'create' ? 'Fill in the details to add a new book to the catalog.' : 'Update the details of this book.'; ?></p>
    </div>
    <a class="site-nav__button site-nav__button--ghost" href="<?= htmlspecialchars(url('/admin/books')); ?>">&larr; Back to Books</a>
</section>

<section class="panel" style="margin-top: 20px; max-width: 680px;">
    <form action="<?= htmlspecialchars(url($mode === 'create' ? '/admin/books/store' : '/admin/books/update')); ?>" class="auth-form" method="POST">
        <?= csrf_field(); ?>

        <?php if ($mode === 'edit' && $book !== null): ?>
            <input name="id" type="hidden" value="<?= htmlspecialchars((string) $book['id']); ?>">
        <?php endif; ?>

        <div class="form-field">
            <label for="title">Book Title <span style="color: #b91c1c;">*</span></label>
            <input
                id="title"
                name="title"
                type="text"
                value="<?= htmlspecialchars(old('title', $book['title'] ?? '')); ?>"
                placeholder="Enter the full book title"
                required
                maxlength="255"
            >
        </div>

        <div class="form-field">
            <label for="author">Author <span style="color: #b91c1c;">*</span></label>
            <input
                id="author"
                name="author"
                type="text"
                value="<?= htmlspecialchars(old('author', $book['author'] ?? '')); ?>"
                placeholder="Author's full name"
                required
                maxlength="150"
            >
        </div>

        <div class="form-field">
            <label for="category_id">Category</label>
            <select
                id="category_id"
                name="category_id"
                style="width: 100%; padding: 14px 16px; border-radius: 16px; border: 1px solid var(--border); background: #fff; color: var(--text); font: inherit;"
            >
                <option value="0">Uncategorized</option>
                <?php foreach ($categories as $category): ?>
                    <?php
                        $selected = (int) old('category_id', $book['category_id'] ?? 0) === (int) $category['id'];
                    ?>
                    <option value="<?= htmlspecialchars((string) $category['id']); ?>" <?= $selected ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-field">
            <label for="description">Description</label>
            <textarea
                id="description"
                name="description"
                placeholder="Brief summary or notes about the book (optional)"
                style="width: 100%; padding: 14px 16px; border-radius: 16px; border: 1px solid var(--border); background: #fff; color: var(--text); font: inherit; min-height: 120px; resize: vertical;"
            ><?= htmlspecialchars(old('description', $book['description'] ?? '')); ?></textarea>
        </div>

        <div class="form-field">
            <label for="stock">Stock Copies</label>
            <input
                id="stock"
                name="stock"
                type="number"
                value="<?= htmlspecialchars((string) old('stock', $book['stock'] ?? '0')); ?>"
                min="0"
                max="99999"
                style="width: 120px;"
            >
        </div>

        <div style="display: flex; gap: 12px; margin-top: 8px;">
            <button class="btn btn-primary" type="submit" style="padding: 14px 28px;">
                <?= $mode === 'create' ? 'Add Book' : 'Save Changes'; ?>
            </button>
            <a class="btn btn-secondary" href="<?= htmlspecialchars(url('/admin/books')); ?>" style="padding: 14px 28px;">Cancel</a>
        </div>
    </form>
</section>
