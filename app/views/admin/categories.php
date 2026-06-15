<div class="space-y-8 pb-12">
    <!-- Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="<?= htmlspecialchars(url('/admin')); ?>" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 hover:bg-teal-100 hover:text-teal-600 flex items-center justify-center transition-colors">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                </a>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-slate-900">Manage Categories</h1>
            </div>
            <p class="text-slate-500 max-w-2xl text-lg ml-11">Organize your library catalog by creating and managing categories.</p>
        </div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Add Category Form -->
        <div class="lg:col-span-1">
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 sticky top-6">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-[24px]">add_box</span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Add New Category</h3>
                <p class="text-sm text-slate-500 mb-6">Create a new category to group similar books together.</p>
                
                <form action="<?= htmlspecialchars(url('/admin/categories/store')); ?>" method="POST" class="space-y-4">
                    <?= csrf_field(); ?>
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Category Name</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="<?= htmlspecialchars(old('name', '')); ?>"
                            placeholder="e.g., Science Fiction"
                            required
                            maxlength="100"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 outline-none transition-all"
                        >
                    </div>
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
                        <span class="material-symbols-outlined text-[20px]">add</span> Add Category
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column: Categories List -->
        <div class="lg:col-span-2">
            <?php if (empty($categories)): ?>
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-12 flex flex-col items-center justify-center text-center h-full">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-400 mb-4">
                        <span class="material-symbols-outlined text-4xl">category</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">No categories yet</h3>
                    <p class="text-slate-500 max-w-sm">Categories help organize your book catalog. Use the form to create your first one.</p>
                </div>
            <?php else: ?>
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500">
                                    <th class="px-6 py-4 font-semibold w-16 text-center">ID</th>
                                    <th class="px-6 py-4 font-semibold">Name</th>
                                    <th class="px-6 py-4 font-semibold text-center w-24">Books</th>
                                    <th class="px-6 py-4 font-semibold text-center w-24">Stock</th>
                                    <th class="px-6 py-4 font-semibold text-right w-32">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                <?php foreach ($categories as $category): ?>
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="px-6 py-4 text-center font-medium text-slate-400"><?= htmlspecialchars((string) $category['id']); ?></td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-slate-900 text-base"><?= htmlspecialchars($category['name']); ?></span>
                                                <?php if ((int) $category['id'] === 0): ?>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 text-slate-600 uppercase tracking-wide">Default</span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Inline edit form (Hidden by default) -->
                                            <?php if ((int) $category['id'] > 0): ?>
                                                <div id="edit-cat-<?= (int) $category['id']; ?>" class="hidden mt-3">
                                                    <form action="<?= htmlspecialchars(url('/admin/categories/update')); ?>" method="POST" class="flex gap-2">
                                                        <?= csrf_field(); ?>
                                                        <input name="id" type="hidden" value="<?= htmlspecialchars((string) $category['id']); ?>">
                                                        <input name="name" type="text" value="<?= htmlspecialchars($category['name']); ?>" required maxlength="100" class="flex-1 px-3 py-1.5 rounded-lg border border-slate-200 focus:border-teal-500 focus:ring-1 focus:ring-teal-200 outline-none text-sm">
                                                        <button type="submit" class="px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg text-sm transition-colors">Save</button>
                                                        <button type="button" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg text-sm transition-colors" onclick="document.getElementById('edit-cat-<?= (int) $category['id']; ?>').classList.add('hidden');">Cancel</button>
                                                    </form>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-center text-slate-600 font-medium"><?= htmlspecialchars((string) $category['book_count']); ?></td>
                                        <td class="px-6 py-4 text-center text-slate-600 font-medium"><?= htmlspecialchars((string) $category['stock_total']); ?></td>
                                        <td class="px-6 py-4 text-right">
                                            <?php if ((int) $category['id'] > 0): ?>
                                                <div class="flex items-center justify-end gap-2 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                                                    <button type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:bg-teal-50 hover:text-teal-600 transition-colors" title="Edit" onclick="document.getElementById('edit-cat-<?= (int) $category['id']; ?>').classList.toggle('hidden');">
                                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                                    </button>
                                                    <form action="<?= htmlspecialchars(url('/admin/categories/delete')); ?>" method="POST" class="m-0" onsubmit="return confirm('Delete category &quot;<?= htmlspecialchars(addslashes($category['name'])); ?>&quot;? Books using this category must be reassigned first.');">
                                                        <?= csrf_field(); ?>
                                                        <input name="id" type="hidden" value="<?= htmlspecialchars((string) $category['id']); ?>">
                                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-colors" title="Delete">
                                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-400">Protected</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
