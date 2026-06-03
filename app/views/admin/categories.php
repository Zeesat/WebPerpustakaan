<section class="page-header" style="display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
    <div>
        <h1 class="page-title">Manage Categories</h1>
        <p class="page-subtitle">Organize books by creating and managing categories.</p>
    </div>
</section>

<section class="panel" style="margin-top: 20px; max-width: 520px;">
    <h3>Add New Category</h3>
    <form action="<?= htmlspecialchars(url('/admin/categories/store')); ?>" class="auth-form" method="POST" style="margin-top: 12px; gap: 12px;">
        <?= csrf_field(); ?>
        <div class="form-field" style="display: flex; flex-direction: row; align-items: center; gap: 10px;">
            <input
                name="name"
                type="text"
                value="<?= htmlspecialchars(old('name', '')); ?>"
                placeholder="Category name"
                required
                maxlength="100"
                style="flex: 1;"
            >
            <button class="btn btn-primary" type="submit" style="white-space: nowrap; padding: 14px 20px;">Add Category</button>
        </div>
    </form>
</section>

<?php if (empty($categories)): ?>
    <section class="panel" style="margin-top: 20px;">
        <h3>No categories yet</h3>
        <p class="meta">Categories help organize your book catalog. Create one above.</p>
    </section>
<?php else: ?>
    <section class="panel table-wrap" style="margin-top: 20px;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Books</th>
                    <th>Total Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) $category['id']); ?></td>
                        <td>
                            <strong><?= htmlspecialchars($category['name']); ?></strong>
                            <?php if ((int) $category['id'] === 0): ?>
                                <span class="badge" style="background: #e2e8f0; color: #475569; font-size: 0.75rem; margin-left: 8px;">Default</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars((string) $category['book_count']); ?></td>
                        <td><?= htmlspecialchars((string) $category['stock_total']); ?></td>
                        <td>
                            <?php if ((int) $category['id'] > 0): ?>
                                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                    <button class="btn btn-secondary" style="padding: 6px 14px; font-size: 0.9rem;" onclick="document.getElementById('edit-cat-<?= (int) $category['id']; ?>').style.display='block';">Edit</button>
                                    <form action="<?= htmlspecialchars(url('/admin/categories/delete')); ?>" class="inline-form" method="POST" onsubmit="return confirm('Delete category &quot;<?= htmlspecialchars($category['name']); ?>&quot;? Books using this category must be reassigned first.');">
                                        <?= csrf_field(); ?>
                                        <input name="id" type="hidden" value="<?= htmlspecialchars((string) $category['id']); ?>">
                                        <button class="btn btn-secondary" style="padding: 6px 14px; font-size: 0.9rem; color: #b91c1c; border-color: #fecdd3;" type="submit">Delete</button>
                                    </button>
                                </div>

                                <!-- Inline edit form -->
                                <div id="edit-cat-<?= (int) $category['id']; ?>" style="display: none; margin-top: 8px;">
                                    <form action="<?= htmlspecialchars(url('/admin/categories/update')); ?>" method="POST" style="display: flex; gap: 8px; align-items: center;">
                                        <?= csrf_field(); ?>
                                        <input name="id" type="hidden" value="<?= htmlspecialchars((string) $category['id']); ?>">
                                        <input name="name" type="text" value="<?= htmlspecialchars($category['name']); ?>" required maxlength="100" style="padding: 8px 12px; border-radius: 12px; border: 1px solid var(--border); flex: 1; font: inherit;">
                                        <button class="btn btn-primary" type="submit" style="padding: 8px 14px; font-size: 0.9rem;">Save</button>
                                        <button class="btn btn-secondary" type="button" style="padding: 8px 14px; font-size: 0.9rem;" onclick="document.getElementById('edit-cat-<?= (int) $category['id']; ?>').style.display='none';">Cancel</button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <span class="meta">Protected</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
<?php endif; ?>

