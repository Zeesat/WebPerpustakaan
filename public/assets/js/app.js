document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-year]').forEach((node) => {
        node.textContent = new Date().getFullYear();
    });

    // ──────────────────────────────────────────────
    //  USER DROPDOWN MENU
    // ──────────────────────────────────────────────
    const trigger  = document.getElementById('user-dropdown-trigger');
    const menu     = document.getElementById('user-dropdown-menu');
    const chevron  = document.getElementById('dropdown-chevron');

    if (trigger && menu) {
        const open = () => {
            menu.classList.remove('opacity-0', 'invisible', '-translate-y-2');
            menu.classList.add('opacity-100', 'visible', 'translate-y-0');
            trigger.setAttribute('aria-expanded', 'true');
            if (chevron) chevron.style.transform = 'rotate(180deg)';
        };

        const close = () => {
            menu.classList.add('opacity-0', 'invisible', '-translate-y-2');
            menu.classList.remove('opacity-100', 'visible', 'translate-y-0');
            trigger.setAttribute('aria-expanded', 'false');
            if (chevron) chevron.style.transform = '';
        };

        const isOpen = () => trigger.getAttribute('aria-expanded') === 'true';

        // Toggle on click
        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            isOpen() ? close() : open();
        });

        // Close on outside click
        document.addEventListener('click', (e) => {
            if (isOpen() && !menu.contains(e.target)) {
                close();
            }
        });

        // Close on ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && isOpen()) {
                close();
                trigger.focus();
            }
        });

        // Close when a menu item link is clicked
        menu.querySelectorAll('a[role="menuitem"]').forEach((link) => {
            link.addEventListener('click', () => close());
        });
    }

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

