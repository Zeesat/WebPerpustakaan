<section class="page-header" style="display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
    <div>
        <h1 class="page-title">Manage Books</h1>
        <p class="page-subtitle">Add, edit, or remove books from the library catalog.</p>
    </div>
    <a class="site-nav__button site-nav__button--primary" href="<?= htmlspecialchars(url('/admin/books/create')); ?>">+ Add New Book</a>
</section>

<?php if (empty($books)): ?>
    <section class="panel" style="margin-top: 20px;">
        <h3>No books in catalog</h3>
        <p class="meta">The library catalog is empty. Start by adding your first book.</p>
    </section>
<?php else: ?>
    <section class="panel table-wrap" style="margin-top: 20px;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) $book['id']); ?></td>
                        <td><strong><?= htmlspecialchars($book['title']); ?></strong></td>
                        <td><?= htmlspecialchars($book['author']); ?></td>
                        <td><span class="badge"><?= htmlspecialchars($book['category_name'] ?? 'Uncategorized'); ?></span></td>
                        <td><?= htmlspecialchars((string) $book['stock']); ?></td>
                        <td>
                            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                <a class="btn btn-secondary" href="<?= htmlspecialchars(url('/admin/books/edit?id=' . $book['id'])); ?>" style="padding: 6px 14px; font-size: 0.9rem;">Edit</a>
                                <form action="<?= htmlspecialchars(url('/admin/books/delete')); ?>" class="inline-form" method="POST" onsubmit="return confirm('Delete &quot;<?= htmlspecialchars($book['title']); ?>&quot;? This action cannot be undone.');">
                                    <?= csrf_field(); ?>
                                    <input name="id" type="hidden" value="<?= htmlspecialchars((string) $book['id']); ?>">
                                    <button class="btn btn-secondary" style="padding: 6px 14px; font-size: 0.9rem; color: #b91c1c; border-color: #fecdd3;" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
<?php endif; ?>

