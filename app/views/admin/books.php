<div class="space-y-8 pb-12">
    <!-- Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="<?= htmlspecialchars(url('/admin')); ?>" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 hover:bg-blue-100 hover:text-blue-600 flex items-center justify-center transition-colors">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                </a>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-slate-900">Manage Books</h1>
            </div>
            <p class="text-slate-500 max-w-2xl text-lg ml-11">Add, edit, or remove books from the library catalog.</p>
        </div>
        <a class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300" href="<?= htmlspecialchars(url('/admin/books/create')); ?>">
            <span class="material-symbols-outlined text-[20px]">add_circle</span> Add New Book
        </a>
    </header>

    <?php if (empty($books)): ?>
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-16 flex flex-col items-center justify-center text-center">
            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center text-slate-400 mb-6">
                <span class="material-symbols-outlined text-5xl">auto_stories</span>
            </div>
            <h3 class="text-2xl font-bold text-slate-800 mb-2">No books in catalog</h3>
            <p class="text-slate-500 max-w-md mx-auto mb-8">The library catalog is currently empty. Start building your collection by adding your first book.</p>
            <a class="inline-flex items-center gap-2 px-6 py-3 bg-blue-50 text-blue-700 font-bold rounded-xl hover:bg-blue-100 transition-colors" href="<?= htmlspecialchars(url('/admin/books/create')); ?>">
                <span class="material-symbols-outlined text-[20px]">add</span> Add First Book
            </a>
        </div>
    <?php else: ?>
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500">
                            <th class="px-6 py-4 font-semibold text-center w-16">ID</th>
                            <th class="px-6 py-4 font-semibold w-20">Cover</th>
                            <th class="px-6 py-4 font-semibold">Title &amp; Author</th>
                            <th class="px-6 py-4 font-semibold">Category</th>
                            <th class="px-6 py-4 font-semibold text-center w-24">Stock</th>
                            <th class="px-6 py-4 font-semibold text-right w-48">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <?php foreach ($books as $book): ?>
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4 text-center font-medium text-slate-400"><?= htmlspecialchars((string) $book['id']); ?></td>
                                <td class="px-6 py-4">
                                    <?php if (isset($book['cover']) && is_string($book['cover']) && $book['cover'] !== ''): ?>
                                        <div class="w-12 h-16 rounded-md overflow-hidden border border-slate-200 shadow-sm relative group-hover:shadow-md transition-shadow">
                                            <img src="<?= htmlspecialchars(cover_url($book['cover']) ?? ''); ?>" alt="Cover" class="w-full h-full object-cover" onerror="this.outerHTML='<div class=\'w-full h-full bg-blue-50 flex items-center justify-center text-blue-300 font-bold text-[10px]\'>--</div>'">
                                        </div>
                                    <?php else: ?>
                                        <div class="w-12 h-16 rounded-md bg-blue-50 border border-blue-100/50 flex items-center justify-center text-blue-300 font-bold text-xs shadow-sm">
                                            --
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900 text-base mb-1"><?= htmlspecialchars($book['title']); ?></div>
                                    <div class="text-slate-500 text-sm flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[14px]">person</span>
                                        <?= htmlspecialchars($book['author']); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                        <?= htmlspecialchars($book['category_name'] ?? 'Uncategorized'); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full <?= $book['stock'] > 0 ? 'bg-emerald-50 text-emerald-700 font-bold' : 'bg-rose-50 text-rose-700 font-bold' ?>">
                                        <?= htmlspecialchars((string) $book['stock']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                                        <a href="<?= htmlspecialchars(url('/admin/books/edit?id=' . $book['id'])); ?>" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-colors" title="Edit">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>
                                        <form action="<?= htmlspecialchars(url('/admin/books/delete')); ?>" method="POST" class="m-0" onsubmit="return confirm('Delete &quot;<?= htmlspecialchars(addslashes($book['title'])); ?>&quot;? This action cannot be undone.');">
                                            <?= csrf_field(); ?>
                                            <input name="id" type="hidden" value="<?= htmlspecialchars((string) $book['id']); ?>">
                                            <button type="submit" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-colors" title="Delete">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex items-center justify-between text-sm text-slate-500">
                <span>Showing <span class="font-bold text-slate-800"><?= count($books) ?></span> total titles</span>
            </div>
        </div>
    <?php endif; ?>
</div>

