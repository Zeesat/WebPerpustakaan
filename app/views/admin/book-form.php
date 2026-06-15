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
            <form action="<?= htmlspecialchars(url($mode === 'create' ? '/admin/books/store' : '/admin/books/update')); ?>" method="POST" class="space-y-6">
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
                                onerror="this.style.display='none'; document.getElementById('cover-fallback').style.display='flex';"
                            >
                            <div
                                id="cover-fallback"
                                class="w-48 h-64 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-700 text-white <?= $currentCoverUrl ? 'hidden' : 'flex' ?> items-center justify-center text-5xl font-black border border-slate-200 shadow-sm"
                            >
                                <?= htmlspecialchars($book['title'] ? mb_strtoupper(mb_substr($book['title'], 0, 2)) : 'BK'); ?>
                            </div>
                            <button
                                id="remove-cover-btn"
                                type="button"
                                class="absolute -top-3 -right-3 bg-rose-500 hover:bg-rose-600 text-white border-4 border-white rounded-full w-8 h-8 flex items-center justify-center shadow-sm transition-colors <?= $currentCoverUrl ? 'flex' : 'hidden' ?>"
                                title="Remove cover"
                                onclick="removeCover(<?= (int) $book['id']; ?>, '<?= htmlspecialchars($csrfToken, ENT_QUOTES); ?>')"
                            >
                                <span class="material-symbols-outlined text-[16px]">close</span>
                            </button>
                        </div>
                    </div>

                    <!-- Upload form -->
                    <div class="flex-1">
                        <div id="cover-upload-area" class="border-2 border-dashed border-slate-300 rounded-2xl p-8 text-center bg-slate-50 hover:bg-slate-100 transition-colors h-full flex flex-col items-center justify-center min-h-[200px]">
                            <input
                                type="file"
                                id="cover-input"
                                accept="image/jpeg,image/png,image/webp"
                                class="hidden"
                            >
                            <div class="w-16 h-16 bg-white rounded-full shadow-sm flex items-center justify-center text-blue-500 mb-4">
                                <span class="material-symbols-outlined text-[32px]">cloud_upload</span>
                            </div>
                            <p class="text-slate-600 font-medium mb-4" id="upload-status-text">Click to select an image</p>
                            <button type="button" class="px-6 py-2.5 bg-white border border-slate-200 shadow-sm text-slate-700 font-bold rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all" onclick="document.getElementById('cover-input').click()">
                                Choose Image
                            </button>
                            
                            <!-- Progress Bar -->
                            <div id="upload-progress" class="hidden w-full max-w-xs mt-6">
                                <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
                                    <div id="progress-bar" class="h-full bg-blue-500 w-0 transition-all duration-300"></div>
                                </div>
                                <p id="progress-text" class="text-xs text-slate-500 mt-2 font-medium">Processing...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
            document.addEventListener('DOMContentLoaded', function () {
                const coverInput = document.getElementById('cover-input');
                if (!coverInput) return;

                const MAX_WIDTH = 320;
                const bookId = <?= (int) ($book['id'] ?? 0); ?>;
                const csrfToken = '<?= htmlspecialchars($csrfToken, ENT_QUOTES); ?>';
                const uploadUrl = '<?= htmlspecialchars(url('/admin/books/update-cover')); ?>';

                coverInput.addEventListener('change', async function (e) {
                    const file = e.target.files?.[0];
                    if (!file) return;

                    // Validate file type
                    const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
                    if (!validTypes.includes(file.type)) {
                        alert('Please select a JPG, PNG, or WebP image.');
                        coverInput.value = '';
                        return;
                    }

                    // Validate file size (10MB)
                    if (file.size > 10 * 1024 * 1024) {
                        alert('File size must be under 10MB.');
                        coverInput.value = '';
                        return;
                    }

                    // Show progress
                    const progress = document.getElementById('upload-progress');
                    const progressBar = document.getElementById('progress-bar');
                    const progressText = document.getElementById('progress-text');
                    const statusText = document.getElementById('upload-status-text');

                    progress.classList.remove('hidden');
                    progressBar.style.width = '10%';
                    progressText.textContent = 'Reading image...';
                    statusText.textContent = 'Processing...';

                    try {
                        // Step 1: Create ImageBitmap
                        const bitmap = await createImageBitmap(file);
                        progressBar.style.width = '30%';
                        progressText.textContent = 'Resizing...';

                        // Step 2: Calculate dimensions (max 320px width)
                        let width = bitmap.width;
                        let height = bitmap.height;

                        if (width > MAX_WIDTH) {
                            height = Math.round(height * (MAX_WIDTH / width));
                            width = MAX_WIDTH;
                        }

                        // Step 3: Draw to canvas
                        const canvas = document.createElement('canvas');
                        canvas.width = width;
                        canvas.height = height;
                        const ctx = canvas.getContext('2d');

                        if (!ctx) {
                            throw new Error('Canvas 2D context not available.');
                        }

                        // Fill background for transparent images
                        ctx.fillStyle = '#f1f5f9';
                        ctx.fillRect(0, 0, width, height);
                        ctx.drawImage(bitmap, 0, 0, width, height);

                        progressBar.style.width = '60%';
                        progressText.textContent = 'Converting to WebP...';

                        // Step 4: Convert to WebP blob
                        const blob = await new Promise((resolve, reject) => {
                            canvas.toBlob(
                                (b) => {
                                    if (b) resolve(b);
                                    else reject(new Error('WebP conversion failed.'));
                                },
                                'image/webp',
                                0.8
                            );
                        });

                        progressBar.style.width = '80%';
                        progressText.textContent = 'Uploading...';

                        // Step 5: Upload the WebP blob
                        const formData = new FormData();
                        formData.append('cover', blob, 'cover.webp');
                        formData.append('_token', csrfToken);
                        formData.append('id', String(bookId));

                        const response = await fetch(uploadUrl, {
                            method: 'POST',
                            body: formData,
                        });

                        const result = await response.json();

                        if (!result.success) {
                            throw new Error(result.message || 'Upload failed.');
                        }

                        // Success!
                        progressBar.style.width = '100%';
                        progressText.textContent = 'Cover updated!';
                        statusText.textContent = 'Cover uploaded successfully';

                        // Update the preview
                        const coverImage = document.getElementById('cover-image');
                        const coverFallback = document.getElementById('cover-fallback');
                        const coverPreview = document.getElementById('cover-preview');
                        const removeBtn = document.getElementById('remove-cover-btn');

                        if (coverImage) {
                            coverImage.src = result.cover_url + '?t=' + Date.now();
                            coverImage.classList.remove('hidden');
                            coverImage.classList.add('block');
                        }
                        if (coverFallback) {
                            coverFallback.classList.add('hidden');
                            coverFallback.classList.remove('flex');
                        }
                        if (coverPreview) {
                            coverPreview.classList.remove('hidden');
                            coverPreview.classList.add('block');
                        }
                        if (removeBtn) {
                            removeBtn.classList.remove('hidden');
                            removeBtn.classList.add('flex');
                        }

                        // Reset input
                        coverInput.value = '';

                        setTimeout(() => {
                            progress.classList.add('hidden');
                            statusText.textContent = 'Click to select an image';
                        }, 2000);

                    } catch (err) {
                        progressBar.style.width = '0%';
                        progressText.textContent = 'Error: ' + err.message;
                        statusText.textContent = 'Upload failed. Try again.';
                        coverInput.value = '';
                        console.error('Cover upload error:', err);
                    }
                });
            });

            async function removeCover(bookId, token) {
                if (!confirm('Remove the current cover image?')) return;

                const removeBtn = document.getElementById('remove-cover-btn');
                removeBtn.disabled = true;
                removeBtn.innerHTML = '<span class="material-symbols-outlined text-[16px] animate-spin">refresh</span>';

                try {
                    const formData = new FormData();
                    formData.append('id', String(bookId));
                    formData.append('_token', token);

                    const response = await fetch('<?= htmlspecialchars(url('/admin/books/delete-cover')); ?>', {
                        method: 'POST',
                        body: formData,
                    });

                    const result = await response.json();

                    if (!result.success) {
                        throw new Error(result.message || 'Failed to remove cover.');
                    }

                    // Hide preview
                    document.getElementById('cover-preview').classList.add('hidden');
                    document.getElementById('cover-preview').classList.remove('block');
                    document.getElementById('cover-image').classList.add('hidden');
                    document.getElementById('cover-image').classList.remove('block');
                    document.getElementById('cover-fallback').classList.add('flex');
                    document.getElementById('cover-fallback').classList.remove('hidden');

                } catch (err) {
                    alert('Error: ' + err.message);
                } finally {
                    removeBtn.disabled = false;
                    removeBtn.innerHTML = '<span class="material-symbols-outlined text-[16px]">close</span>';
                    removeBtn.classList.add('hidden');
                    removeBtn.classList.remove('flex');
                }
            }
            </script>
            <?php endif; ?>
        </div>
    </div>
</div>
