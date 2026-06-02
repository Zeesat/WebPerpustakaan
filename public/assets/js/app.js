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
});

