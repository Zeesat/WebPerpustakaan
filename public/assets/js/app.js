const initUserDropdown = () => {
    document.querySelectorAll('[data-profile-dropdown]').forEach((dropdown) => {
        if (dropdown.dataset.dropdownBound === 'true') {
            return;
        }

        const trigger = dropdown.querySelector('[data-profile-dropdown-trigger]');
        const menu = dropdown.querySelector('[data-profile-dropdown-menu]');
        const chevron = dropdown.querySelector('#dropdown-chevron');

        if (!(trigger instanceof HTMLButtonElement) || !(menu instanceof HTMLElement)) {
            return;
        }

        dropdown.dataset.dropdownBound = 'true';

        const syncState = (isOpen) => {
            trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            menu.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
            menu.hidden = !isOpen;

            if (chevron instanceof HTMLElement) {
                chevron.style.transform = '';
            }
        };

        const isOpen = () => trigger.getAttribute('aria-expanded') === 'true';

        syncState(false);

        trigger.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();

            syncState(!isOpen());
        });

        document.addEventListener(
            'pointerdown',
            (event) => {
                if (!isOpen()) {
                    return;
                }

                const target = event.target;
                if (target instanceof Node && !dropdown.contains(target)) {
                    syncState(false);
                }
            },
            true
        );

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && isOpen()) {
                syncState(false);
                trigger.focus();
            }
        });

        menu.addEventListener('click', (event) => {
            const target = event.target;
            if (target instanceof Element && target.closest('[role="menuitem"]')) {
                syncState(false);
            }
        });

        syncState(false);
    });
};

const initLoadingForms = () => {
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
};

const initCoverUpload = () => {
    const uploadArea = document.getElementById('cover-upload-area');
    const coverInput = document.getElementById('cover-input');

    if (uploadArea && coverInput) {
        uploadArea.addEventListener('dragover', (event) => {
            event.preventDefault();
            uploadArea.style.borderColor = '#2563eb';
            uploadArea.style.background = 'rgba(37, 99, 235, 0.05)';
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.style.borderColor = '';
            uploadArea.style.background = '';
        });

        uploadArea.addEventListener('drop', (event) => {
            event.preventDefault();
            uploadArea.style.borderColor = '';
            uploadArea.style.background = '';

            const file = event.dataTransfer?.files?.[0];
            if (file) {
                coverInput.files = event.dataTransfer.files;
                coverInput.dispatchEvent(new Event('change'));
            }
        });
    }
};

const initApp = () => {
    document.querySelectorAll('[data-year]').forEach((node) => {
        node.textContent = new Date().getFullYear();
    });

    document.querySelectorAll('[data-profile-dropdown]').forEach((dropdown) => {
        const trigger = dropdown.querySelector('[data-profile-dropdown-trigger]');
        const menu = dropdown.querySelector('[data-profile-dropdown-menu]');
        const chevron = dropdown.querySelector('#dropdown-chevron');

        if (trigger instanceof HTMLElement) {
            trigger.setAttribute('aria-expanded', 'false');
        }

        if (menu instanceof HTMLElement) {
            menu.setAttribute('aria-hidden', 'true');
            menu.hidden = true;
        }

        if (chevron instanceof HTMLElement) {
            chevron.style.transform = '';
        }
    });

    initUserDropdown();
    initLoadingForms();
    initCoverUpload();
};

document.addEventListener('DOMContentLoaded', initApp);
window.addEventListener('pageshow', initApp);
