document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-year]').forEach((node) => {
        node.textContent = new Date().getFullYear();
    });

    document.querySelectorAll('[data-loading-form]').forEach((form) => {
        form.addEventListener('submit', () => {
            const submitButton = form.querySelector('button[type="submit"][data-loading-text]');

            if (!(submitButton instanceof HTMLButtonElement)) {
                return;
            }

            form.classList.add('is-submitting');
            submitButton.dataset.originalText = submitButton.textContent ?? '';
            submitButton.textContent = submitButton.dataset.loadingText ?? 'Submitting...';
            submitButton.disabled = true;
        });
    });

    // Drag-and-drop highlight for cover upload area
    const uploadArea = document.getElementById('cover-upload-area');
    const coverInput = document.getElementById('cover-input');

    if (uploadArea && coverInput) {
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#2563eb';
            uploadArea.style.background = 'rgba(37, 99, 235, 0.05)';
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.style.borderColor = '';
            uploadArea.style.background = '';
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '';
            uploadArea.style.background = '';

            const file = e.dataTransfer?.files?.[0];
            if (file) {
                coverInput.files = e.dataTransfer.files;
                coverInput.dispatchEvent(new Event('change'));
            }
        });
    }
});

