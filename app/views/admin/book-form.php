<?php
$bookFormContext = $mode === 'edit' && $book !== null
    ? 'book:edit:' . (int) $book['id']
    : 'book:create';
$hasBookOldInput = ($error ?? null) !== null && old('_form_context') === $bookFormContext;
$bookFormValue = static fn (string $key, mixed $default = ''): mixed => $hasBookOldInput
    ? old($key, $default)
    : $default;
?>

<section class="page-header" style="display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
    <div>
        <h1 class="page-title"><?= $mode === 'create' ? 'Add New Book' : 'Edit Book'; ?></h1>
        <p class="page-subtitle"><?= $mode === 'create' ? 'Fill in the details to add a new book to the catalog.' : 'Update the details of this book.'; ?></p>
    </div>
    <a class="site-nav__button site-nav__button--ghost" href="<?= htmlspecialchars(url('/admin/books')); ?>">&larr; Back to Books</a>
</section>

<?php if ($mode === 'edit' && $book !== null): ?>
<?php
$currentCoverUrl = isset($book['cover']) && is_string($book['cover']) && $book['cover'] !== ''
    ? cover_url($book['cover'])
    : null;
$csrfToken = csrf_token();
?>
<?php endif; ?>

<section class="panel" style="margin-top: 20px; max-width: 680px;">
    <!-- Book detail form -->
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
                value="<?= htmlspecialchars((string) $bookFormValue('title', $book['title'] ?? '')); ?>"
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
                value="<?= htmlspecialchars((string) $bookFormValue('author', $book['author'] ?? '')); ?>"
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
                <?php $selectedCategoryId = (int) $bookFormValue('category_id', $book['category_id'] ?? 0); ?>
                <option value="0" <?= $selectedCategoryId === 0 ? 'selected' : ''; ?>>Uncategorized</option>
                <?php foreach ($categories as $category): ?>
                    <?php
                        $selected = $selectedCategoryId === (int) $category['id'];
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
            ><?= htmlspecialchars((string) $bookFormValue('description', $book['description'] ?? '')); ?></textarea>
        </div>

        <div class="form-field">
            <label for="stock">Stock Copies</label>
            <input
                id="stock"
                name="stock"
                type="number"
                value="<?= htmlspecialchars((string) $bookFormValue('stock', $book['stock'] ?? '0')); ?>"
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

    <?php if ($mode === 'edit' && $book !== null): ?>
    <!-- Cover upload section -->
    <hr style="margin: 32px 0 24px; border: none; border-top: 1px solid var(--border);">

    <div style="margin-bottom: 16px;">
        <h3 style="margin: 0 0 4px;">Book Cover</h3>
        <p style="margin: 0; color: var(--muted); font-size: 0.92rem;">
            Upload an image to display as the book cover. Recommended: JPG or PNG, max 5MB.
        </p>
    </div>

    <!-- Current cover preview -->
    <div id="cover-preview" style="margin-bottom: 16px; <?= $currentCoverUrl ? '' : 'display: none;' ?>">
        <p style="margin: 0 0 8px; font-weight: 600; font-size: 0.92rem; color: var(--muted);">Current cover:</p>
        <div style="position: relative; display: inline-block;">
            <img
                id="cover-image"
                src="<?= htmlspecialchars($currentCoverUrl ?? ''); ?>"
                alt="Book cover preview"
                style="width: 200px; height: 280px; object-fit: cover; border-radius: 16px; border: 1px solid var(--border); box-shadow: var(--shadow); display: <?= $currentCoverUrl ? 'block' : 'none'; ?>;"
                onerror="this.style.display='none'; document.getElementById('cover-fallback').style.display='flex';"
            >
            <div
                id="cover-fallback"
                style="width: 200px; height: 280px; border-radius: 16px; background: linear-gradient(150deg, #2563eb, #1d4ed8); color: #fff; display: <?= $currentCoverUrl ? 'none' : 'flex'; ?>; align-items: center; justify-content: center; font-size: 3rem; font-weight: 800; border: 1px solid var(--border);"
            >
                <?= htmlspecialchars($book['title'] ? mb_strtoupper(mb_substr($book['title'], 0, 2)) : 'BK'); ?>
            </div>
            <button
                id="remove-cover-btn"
                type="button"
                style="position: absolute; top: 8px; right: 8px; background: rgba(185, 28, 28, 0.9); color: #fff; border: none; border-radius: 50%; width: 32px; height: 32px; cursor: pointer; font-size: 18px; line-height: 1; display: <?= $currentCoverUrl ? 'flex' : 'none'; ?>; align-items: center; justify-content: center;"
                title="Remove cover"
                onclick="removeCover(<?= (int) $book['id']; ?>, '<?= htmlspecialchars($csrfToken, ENT_QUOTES); ?>')"
            >&times;</button>
        </div>
    </div>

    <!-- Upload form -->
    <div id="cover-upload-area" style="border: 2px dashed var(--border); border-radius: 16px; padding: 24px; text-align: center; background: var(--surface); transition: border-color 0.2s;">
        <input
            type="file"
            id="cover-input"
            accept="image/jpeg,image/png,image/webp"
            style="display: none;"
        >
        <p style="margin: 0 0 12px; color: var(--muted);">
            <strong id="upload-status-text">Click to select an image</strong>
        </p>
        <button type="button" class="btn btn-secondary" onclick="document.getElementById('cover-input').click()" style="padding: 10px 20px;">
            Choose Image
        </button>
        <div id="upload-progress" style="display: none; margin-top: 12px;">
            <div style="width: 100%; height: 6px; background: var(--border); border-radius: 3px; overflow: hidden;">
                <div id="progress-bar" style="width: 0%; height: 100%; background: var(--primary); transition: width 0.3s;"></div>
            </div>
            <p id="progress-text" style="margin: 6px 0 0; font-size: 0.85rem; color: var(--muted);">Processing...</p>
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

            progress.style.display = 'block';
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
                    coverImage.style.display = 'block';
                }
                if (coverFallback) coverFallback.style.display = 'none';
                if (coverPreview) coverPreview.style.display = 'block';
                if (removeBtn) removeBtn.style.display = 'flex';

                // Reset input
                coverInput.value = '';

                setTimeout(() => {
                    progress.style.display = 'none';
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
        removeBtn.textContent = '...';

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
            document.getElementById('cover-preview').style.display = 'none';
            document.getElementById('cover-image').style.display = 'none';
            document.getElementById('cover-fallback').style.display = 'flex';

        } catch (err) {
            alert('Error: ' + err.message);
        } finally {
            removeBtn.disabled = false;
            removeBtn.textContent = '\u00d7';
            removeBtn.style.display = 'none';
        }
    }
    </script>
    <?php endif; ?>
</section>
