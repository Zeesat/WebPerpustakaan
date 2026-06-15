<?php
$bookFormContext = $mode === 'edit' && $book !== null
    ? 'book:edit:' . (int) $book['id']
    : 'book:create';
$hasBookOldInput = ($error ?? null) !== null && old('_form_context') === $bookFormContext;
$bookFormValue = static fn (string $key, mixed $default = ''): mixed => $hasBookOldInput
    ? old($key, $default)
    : $default;
?>

<div class="space-y-8 pb-12 max-w-4xl mx-auto">
    <!-- Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="<?= htmlspecialchars(url('/admin/books')); ?>" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 hover:bg-blue-100 hover:text-blue-600 flex items-center justify-center transition-colors">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                </a>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-slate-900"><?= $mode === 'create' ? 'Add New Book' : 'Edit Book'; ?></h1>
            </div>
            <p class="text-slate-500 text-lg ml-11"><?= $mode === 'create' ? 'Fill in the details to add a new book to the catalog.' : 'Update the details of this book.'; ?></p>
        </div>
    </header>

    <?php if ($mode === 'edit' && $book !== null): ?>
    <?php
    $currentCoverUrl = isset($book['cover']) && is_string($book['cover']) && $book['cover'] !== ''
        ? cover_url($book['cover'])
        : null;
    $csrfToken = csrf_token();
    ?>
    <?php endif; ?>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-8">
            <!-- Book detail form -->
            <form action="<?= htmlspecialchars(url($mode === 'create' ? '/admin/books/store' : '/admin/books/update')); ?>" method="POST" class="space-y-6" <?= $mode === 'create' ? 'enctype="multipart/form-data"' : '' ?>>
                <?= csrf_field(); ?>
                <input type="hidden" name="_form_context" value="<?= htmlspecialchars($bookFormContext); ?>">

                <?php if ($mode === 'edit' && $book !== null): ?>
                    <input name="id" type="hidden" value="<?= htmlspecialchars((string) $book['id']); ?>">
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-semibold text-slate-700 mb-1.5">Book Title <span class="text-rose-500">*</span></label>
                        <input
                            id="title"
                            name="title"
                            type="text"
                            value="<?= htmlspecialchars((string) $bookFormValue('title', $book['title'] ?? '')); ?>"
                            placeholder="Enter the full book title"
                            required
                            maxlength="255"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all"
                        >
                    </div>

                    <div>
                        <label for="author" class="block text-sm font-semibold text-slate-700 mb-1.5">Author <span class="text-rose-500">*</span></label>
                        <input
                            id="author"
                            name="author"
                            type="text"
                            value="<?= htmlspecialchars((string) $bookFormValue('author', $book['author'] ?? '')); ?>"
                            placeholder="Author's full name"
                            required
                            maxlength="150"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="category_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Category</label>
                        <div class="relative">
                            <select
                                id="category_id"
                                name="category_id"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all appearance-none bg-white"
                            >
                                <?php $selectedCategoryId = (int) $bookFormValue('category_id', $book['category_id'] ?? 0); ?>
                                <option value="0" <?= $selectedCategoryId === 0 ? 'selected' : ''; ?>>Uncategorized</option>
                                <?php foreach ($categories as $category): ?>
                                    <?php $selected = $selectedCategoryId === (int) $category['id']; ?>
                                    <option value="<?= htmlspecialchars((string) $category['id']); ?>" <?= $selected ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">expand_more</span>
                        </div>
                    </div>

                    <div>
                        <label for="stock" class="block text-sm font-semibold text-slate-700 mb-1.5">Stock Copies</label>
                        <input
                            id="stock"
                            name="stock"
                            type="number"
                            value="<?= htmlspecialchars((string) $bookFormValue('stock', $book['stock'] ?? '0')); ?>"
                            min="0"
                            max="99999"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all"
                        >
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-slate-700 mb-1.5">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        placeholder="Brief summary or notes about the book (optional)"
                        rows="4"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all resize-y"
                    ><?= htmlspecialchars((string) $bookFormValue('description', $book['description'] ?? '')); ?></textarea>
                </div>

                <?php if ($mode === 'create'): ?>
                <div>
                    <label for="cover" class="block text-sm font-semibold text-slate-700 mb-1.5">Book Cover (Optional)</label>
                    <input type="file" id="cover" name="cover" accept="image/jpeg,image/png,image/webp" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all bg-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-slate-500 mt-2">Recommended: JPG, PNG, or WebP. Max 5MB.</p>
                </div>
                <?php endif; ?>

                <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
                        <span class="material-symbols-outlined text-[20px]">save</span> <?= $mode === 'create' ? 'Add Book' : 'Save Changes'; ?>
                    </button>
                    <a href="<?= htmlspecialchars(url('/admin/books')); ?>" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-colors">
                        Cancel
                    </a>
                </div>
            </form>

            <?php if ($mode === 'edit' && $book !== null): ?>
            <!-- Cover upload section -->
            <!-- Cover upload section -->
            <div class="mt-12 pt-8 border-t border-slate-200">
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-slate-800 mb-1">Book Cover</h3>
                    <p class="text-sm text-slate-500">
                        Upload an image to display as the book cover. Recommended: JPG or PNG, max 5MB.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-8">
                    <!-- Current cover preview -->
                    <div id="cover-preview" class="<?= $currentCoverUrl ? 'block' : 'hidden' ?>">
                        <p class="text-sm font-semibold text-slate-500 mb-3">Current cover</p>
                        <div class="relative inline-block">
                            <img
                                id="cover-image"
                                src="<?= htmlspecialchars($currentCoverUrl ?? ''); ?>"
                                alt="Book cover preview"
                                class="w-48 h-64 object-cover rounded-xl border border-slate-200 shadow-sm <?= $currentCoverUrl ? 'block' : 'hidden' ?>"
                            >
                            <form action="<?= htmlspecialchars(url('/admin/books/delete-cover')); ?>" method="POST" onsubmit="return confirm('Remove the current cover image?');" class="m-0 absolute -top-3 -right-3">
                                <?= csrf_field(); ?>
                                <input type="hidden" name="id" value="<?= (int) $book['id']; ?>">
                                <button
                                    type="submit"
                                    class="bg-rose-500 hover:bg-rose-600 text-white border-4 border-white rounded-full w-8 h-8 flex items-center justify-center shadow-sm transition-colors <?= $currentCoverUrl ? 'flex' : 'hidden' ?>"
                                    title="Remove cover"
                                >
                                    <span class="material-symbols-outlined text-[16px]">close</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Upload form -->
                    <div class="flex-1">
                        <form action="<?= htmlspecialchars(url('/admin/books/update-cover')); ?>" method="POST" enctype="multipart/form-data" class="border-2 border-dashed border-slate-300 rounded-2xl p-8 text-center bg-slate-50 hover:bg-slate-100 transition-colors h-full flex flex-col items-center justify-center min-h-[200px]">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="id" value="<?= (int) $book['id']; ?>">
                            
                            <input
                                type="file"
                                name="cover"
                                id="cover-input"
                                accept="image/jpeg,image/png,image/webp"
                                class="hidden"
                                onchange="document.getElementById('upload-status-text').textContent = this.files[0] ? this.files[0].name : 'Click to select an image'; document.getElementById('save-cover-btn').classList.remove('hidden');"
                            >
                            <div class="w-16 h-16 bg-white rounded-full shadow-sm flex items-center justify-center text-blue-500 mb-4">
                                <span class="material-symbols-outlined text-[32px]">cloud_upload</span>
                            </div>
                            <p class="text-slate-600 font-medium mb-4" id="upload-status-text">Click to select an image</p>
                            
                            <div class="flex gap-3 justify-center">
                                <button type="button" class="px-6 py-2.5 bg-white border border-slate-200 shadow-sm text-slate-700 font-bold rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all" onclick="document.getElementById('cover-input').click()">
                                    Choose Image
                                </button>
                                
                                <button type="submit" id="save-cover-btn" class="hidden px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 flex items-center">
                                    <span class="material-symbols-outlined text-[20px] mr-1">save</span> Save Cover
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
