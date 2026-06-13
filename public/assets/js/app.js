const parseJsonScript = (id, fallback = null) => {
    const element = document.getElementById(id);

    if (!(element instanceof HTMLScriptElement)) {
        return fallback;
    }

    try {
        return JSON.parse(element.textContent || '');
    } catch (error) {
        return fallback;
    }
};

const readAppShellConfig = () => parseJsonScript('app-shell-config', {
    auth: {
        isAuthenticated: false,
        isAdmin: false,
        userId: null,
    },
    basket: {
        enabled: false,
        storageKey: 'library.loan-basket.v1',
        maxItems: 3,
        storageTtlHours: 12,
        basketUrl: '/loans/request',
        browseUrl: '/books',
    },
    routes: {
        currentPath: '/',
        loginUrl: '/login',
    },
});

const readBasketPageConfig = () => parseJsonScript('basket-page-config', null);

const escapeHtml = (value) => String(value ?? '')
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;');

const pluralize = (count, singular, plural) => (count === 1 ? singular : plural);

const ensureToastHost = () => {
    let host = document.querySelector('[data-basket-toast-host]');

    if (host instanceof HTMLElement) {
        return host;
    }

    host = document.createElement('div');
    host.dataset.basketToastHost = 'true';
    host.className = 'basket-toast-host';
    document.body.append(host);

    return host;
};

const showToast = (message, tone = 'success') => {
    const host = ensureToastHost();
    const toast = document.createElement('div');

    toast.className = `basket-toast basket-toast--${tone}`;
    toast.textContent = message;
    host.append(toast);

    window.setTimeout(() => {
        toast.classList.add('is-visible');
    }, 20);

    window.setTimeout(() => {
        toast.classList.remove('is-visible');
        window.setTimeout(() => toast.remove(), 220);
    }, 2800);
};

const pulseBasketControls = () => {
    document.querySelectorAll('[data-basket-nav], [data-basket-mobile-bar]').forEach((element) => {
        element.classList.remove('is-pulsing');
        window.requestAnimationFrame(() => {
            element.classList.add('is-pulsing');
            window.setTimeout(() => element.classList.remove('is-pulsing'), 900);
        });
    });
};

const basketPageState = {
    feedback: null,
    issues: [],
    loading: false,
    completedLoan: null,
    completedMessage: '',
    serverMeta: {},
};

const createBasketStore = () => {
    const version = 1;

    const getConfig = () => readAppShellConfig();
    const getStorageKey = () => String(getConfig().basket?.storageKey || 'library.loan-basket.v1');
    const getNoticeKey = (suffix) => `${getStorageKey()}:${suffix}`;
    const getCurrentUserId = () => {
        const userId = getConfig().auth?.userId;
        return Number.isInteger(userId) ? userId : null;
    };
    const getTtlMs = () => {
        const hours = Number(getConfig().basket?.storageTtlHours || 12);
        return Math.max(1, hours) * 60 * 60 * 1000;
    };
    const nowIso = () => new Date().toISOString();

    const writeNotice = (suffix) => {
        try {
            window.sessionStorage.setItem(getNoticeKey(suffix), '1');
        } catch (error) {
        }
    };

    const consumeNotice = () => {
        const suffixes = ['expired', 'switched'];

        for (const suffix of suffixes) {
            try {
                if (window.sessionStorage.getItem(getNoticeKey(suffix)) === '1') {
                    window.sessionStorage.removeItem(getNoticeKey(suffix));
                    return suffix;
                }
            } catch (error) {
                return null;
            }
        }

        return null;
    };

    const blankBasket = () => ({
        version,
        userId: getCurrentUserId(),
        items: [],
        updatedAt: nowIso(),
        expiresAt: new Date(Date.now() + getTtlMs()).toISOString(),
    });

    const normalizeItem = (item) => {
        if (!item || typeof item !== 'object') {
            return null;
        }

        const bookId = Number(item.bookId ?? item.book_id);
        if (!Number.isInteger(bookId) || bookId <= 0) {
            return null;
        }

        return {
            bookId,
            quantity: 1,
            title: String(item.title || 'Untitled Book'),
            author: String(item.author || 'Unknown Author'),
            category: String(item.category || 'General'),
            categoryTone: String(item.categoryTone ?? item.category_tone ?? 'blue'),
            detailUrl: String(item.detailUrl ?? item.detail_url ?? '#'),
            stock: Number.isFinite(Number(item.stock)) ? Number(item.stock) : 0,
            availability: String(item.availability || 'Available'),
            cover: {
                url: item.cover?.url ? String(item.cover.url) : null,
                initials: String(item.cover?.initials || 'BK'),
                tone: String(item.cover?.tone || 'classic'),
            },
            addedAt: String(item.addedAt || nowIso()),
        };
    };

    const normalizeBasket = (basket) => {
        const normalized = blankBasket();

        if (!basket || typeof basket !== 'object') {
            return normalized;
        }

        const items = Array.isArray(basket.items) ? basket.items.map(normalizeItem).filter(Boolean) : [];

        normalized.userId = basket.userId ?? normalized.userId;
        normalized.items = items;
        normalized.updatedAt = typeof basket.updatedAt === 'string' ? basket.updatedAt : normalized.updatedAt;
        normalized.expiresAt = typeof basket.expiresAt === 'string' ? basket.expiresAt : normalized.expiresAt;

        return normalized;
    };

    const persist = (basket, { silent = false } = {}) => {
        const nextBasket = normalizeBasket({
            ...basket,
            userId: getCurrentUserId(),
            updatedAt: nowIso(),
            expiresAt: new Date(Date.now() + getTtlMs()).toISOString(),
        });

        try {
            window.localStorage.setItem(getStorageKey(), JSON.stringify(nextBasket));
        } catch (error) {
        }

        if (!silent) {
            window.dispatchEvent(new CustomEvent('basket:updated', {
                detail: { basket: nextBasket },
            }));
        }

        return nextBasket;
    };

    const clear = (options = {}) => {
        const emptyBasket = blankBasket();

        try {
            window.localStorage.removeItem(getStorageKey());
        } catch (error) {
        }

        if (!options.silent) {
            window.dispatchEvent(new CustomEvent('basket:updated', {
                detail: { basket: emptyBasket },
            }));
        }

        return emptyBasket;
    };

    const load = () => {
        let basket = blankBasket();

        try {
            const raw = window.localStorage.getItem(getStorageKey());
            if (raw) {
                basket = normalizeBasket(JSON.parse(raw));
            }
        } catch (error) {
            basket = blankBasket();
        }

        const currentUserId = getCurrentUserId();
        const expiresAt = Date.parse(basket.expiresAt);

        if (Number.isFinite(expiresAt) && expiresAt <= Date.now()) {
            clear({ silent: true });
            writeNotice('expired');
            return blankBasket();
        }

        if (currentUserId !== null && basket.userId !== null && Number(basket.userId) !== currentUserId) {
            clear({ silent: true });
            writeNotice('switched');
            return blankBasket();
        }

        if (currentUserId !== null && basket.userId === null) {
            basket.userId = currentUserId;
            return persist(basket, { silent: true });
        }

        return basket;
    };

    const hasItem = (bookId) => load().items.some((item) => item.bookId === Number(bookId));

    const add = (book) => {
        const basket = load();
        const item = normalizeItem(book);
        const maxItems = Number(getConfig().basket?.maxItems || 3);

        if (!item) {
            return { status: 'invalid', basket };
        }

        if (basket.items.some((existing) => existing.bookId === item.bookId)) {
            return { status: 'duplicate', basket };
        }

        if (basket.items.length >= maxItems) {
            return { status: 'limit', basket };
        }

        const nextBasket = persist({
            ...basket,
            items: [...basket.items, item],
        });

        return {
            status: 'added',
            basket: nextBasket,
            item,
        };
    };

    const remove = (bookId) => {
        const basket = load();
        const nextBasket = persist({
            ...basket,
            items: basket.items.filter((item) => item.bookId !== Number(bookId)),
        });

        return nextBasket;
    };

    return {
        load,
        add,
        remove,
        clear,
        hasItem,
        consumeNotice,
    };
};

const basketStore = createBasketStore();

const getBasketCountLabel = (count) => `${count} ${pluralize(count, 'item', 'items')}`;

const renderBasketChrome = () => {
    const config = readAppShellConfig();
    const basket = basketStore.load();
    const count = basket.items.length;
    const maxItems = Number(config.basket?.maxItems || 3);
    const countLabel = getBasketCountLabel(count);

    document.querySelectorAll('[data-basket-count]').forEach((badge) => {
        badge.textContent = count > 9 ? '9+' : String(count);
        badge.hidden = count === 0;
    });

    document.querySelectorAll('[data-basket-nav]').forEach((link) => {
        link.classList.toggle('is-empty', count === 0);
        link.classList.toggle('is-filled', count > 0);
        link.classList.toggle('is-warning', count >= maxItems);
        link.setAttribute('aria-label', count === 0 ? 'Basket empty' : `${countLabel} in basket`);
    });

    document.querySelectorAll('[data-basket-mobile-bar]').forEach((bar) => {
        const summary = bar.querySelector('[data-basket-mobile-summary]');

        if (summary instanceof HTMLElement) {
            summary.textContent = count === 0 ? 'No titles selected' : `${countLabel} selected`;
        }

        bar.hidden = count === 0;
    });
};

const parseBasketButtonBook = (button) => {
    try {
        return JSON.parse(button.dataset.basketBook || 'null');
    } catch (error) {
        return null;
    }
};

const syncBasketAddButton = (button) => {
    const book = parseBasketButtonBook(button);
    const basket = basketStore.load();
    const maxItems = Number(readAppShellConfig().basket?.maxItems || 3);
    const inBasket = book ? basket.items.some((item) => item.bookId === Number(book.book_id ?? book.bookId)) : false;
    const outOfStock = Number(book?.stock || 0) <= 0;
    const limitReached = basket.items.length >= maxItems;

    button.classList.remove('is-in-basket', 'is-just-added', 'is-limit-state');

    if (outOfStock) {
        button.disabled = true;
        button.textContent = button.dataset.unavailableText || 'Unavailable';
        return;
    }

    if (button.dataset.flashState === 'added') {
        button.disabled = false;
        button.classList.add('is-just-added');
        button.textContent = 'Added';
        return;
    }

    if (inBasket) {
        button.disabled = false;
        button.classList.add('is-in-basket');
        button.textContent = button.dataset.addedText || 'In Basket';
        return;
    }

    if (limitReached) {
        button.disabled = true;
        button.classList.add('is-limit-state');
        button.textContent = button.dataset.limitText || 'Limit Reached';
        return;
    }

    button.disabled = false;
    button.textContent = button.dataset.defaultText || 'Request Loan';
};

const syncBasketAddButtons = () => {
    document.querySelectorAll('[data-basket-add]').forEach((button) => {
        if (!(button instanceof HTMLButtonElement)) {
            return;
        }

        syncBasketAddButton(button);
    });
};

const initBasketAddButtons = () => {
    document.querySelectorAll('[data-basket-add]').forEach((button) => {
        if (!(button instanceof HTMLButtonElement) || button.dataset.basketBound === 'true') {
            return;
        }

        button.dataset.basketBound = 'true';

        button.addEventListener('click', () => {
            const book = parseBasketButtonBook(button);

            if (!book) {
                showToast('This title could not be added right now.', 'danger');
                return;
            }

            const result = basketStore.add(book);

            if (result.status === 'duplicate') {
                pulseBasketControls();
                showToast('This title is already in your basket.', 'info');
                syncBasketAddButtons();
                return;
            }

            if (result.status === 'limit') {
                showToast(`You can request up to ${readAppShellConfig().basket?.maxItems || 3} books per request.`, 'warning');
                syncBasketAddButtons();
                return;
            }

            if (result.status !== 'added') {
                showToast('This title could not be added right now.', 'danger');
                return;
            }

            button.dataset.flashState = 'added';
            syncBasketAddButton(button);
            renderBasketChrome();
            pulseBasketControls();
            showToast(`Added "${book.title}" to your basket.`, 'success');

            window.setTimeout(() => {
                delete button.dataset.flashState;
                syncBasketAddButtons();
            }, 1100);
        });
    });

    syncBasketAddButtons();
};

const getAvailabilityTone = (item) => {
    if (Number(item.stock) <= 0) {
        return 'danger';
    }

    if (Number(item.stock) <= 2) {
        return 'warning';
    }

    return 'success';
};

const renderChecklistState = (container, isValid) => {
    container.classList.toggle('is-valid', isValid);
    container.classList.toggle('is-invalid', !isValid);
};

const setFeedbackBanner = (message, tone = 'info') => {
    basketPageState.feedback = message ? { message, tone } : null;
};

const normalizeIssuesByBook = (issues) => {
    const map = new Map();

    issues.forEach((issue) => {
        if (!issue || typeof issue !== 'object' || !issue.book_id) {
            return;
        }

        map.set(Number(issue.book_id), issue);
    });

    return map;
};

const renderBasketPage = () => {
    const page = document.querySelector('[data-basket-page]');
    const pageConfig = readBasketPageConfig();

    if (!(page instanceof HTMLElement) || !pageConfig) {
        return;
    }

    const basket = basketStore.load();
    const itemIssues = normalizeIssuesByBook(basketPageState.issues);
    const itemsHost = page.querySelector('[data-basket-items]');
    const emptyState = page.querySelector('[data-basket-empty-state]');
    const feedback = page.querySelector('[data-basket-feedback]');
    const submitButton = page.querySelector('[data-basket-submit-button]');
    const submitState = page.querySelector('[data-basket-submit-state]');
    const inlineState = page.querySelector('[data-basket-inline-state]');
    const successState = page.querySelector('[data-basket-success-state]');
    const summaryPanel = page.querySelector('[data-basket-summary-panel]');
    const selectedCount = basket.items.length;
    const maxItems = Number(pageConfig.maxItems || 3);
    const remaining = Math.max(maxItems - selectedCount, 0);
    const notice = basketStore.consumeNotice();

    if (notice === 'expired' && !basketPageState.feedback) {
        setFeedbackBanner('Your basket expired after being idle for a while. Start a fresh request when you are ready.', 'warning');
    }

    if (notice === 'switched' && !basketPageState.feedback) {
        setFeedbackBanner('Your basket was cleared because a different account is now active in this browser.', 'info');
    }

    page.querySelectorAll('[data-basket-selected-count]').forEach((element) => {
        element.textContent = String(selectedCount);
    });

    const listCount = page.querySelector('[data-basket-list-count]');
    const summarySelected = page.querySelector('[data-basket-summary-selected]');
    const summaryRemaining = page.querySelector('[data-basket-summary-remaining]');

    if (listCount instanceof HTMLElement) {
        listCount.textContent = `${selectedCount} ${pluralize(selectedCount, 'item', 'items')}`;
    }

    if (summarySelected instanceof HTMLElement) {
        summarySelected.textContent = String(selectedCount);
    }

    if (summaryRemaining instanceof HTMLElement) {
        summaryRemaining.textContent = String(remaining);
    }

    const withinLimit = selectedCount > 0 && selectedCount <= maxItems;
    const activeLoanBlocked = Boolean(pageConfig.hasBlockingActiveLoan || Number(basketPageState.serverMeta.activeLoanCount || 0) > 0);
    const availabilityBlocked = basket.items.some((item) => {
        const issue = itemIssues.get(item.bookId);
        return issue?.code === 'book_unavailable' || issue?.code === 'book_missing' || Number(item.stock) <= 0;
    });
    const canSubmit = withinLimit && !activeLoanBlocked && !availabilityBlocked && !basketPageState.loading;

    const countCheck = page.querySelector('[data-basket-check="count"]');
    const activeCheck = page.querySelector('[data-basket-check="active"]');
    const availabilityCheck = page.querySelector('[data-basket-check="availability"]');

    if (countCheck instanceof HTMLElement) {
        renderChecklistState(countCheck, withinLimit);
    }

    if (activeCheck instanceof HTMLElement) {
        renderChecklistState(activeCheck, !activeLoanBlocked);
    }

    if (availabilityCheck instanceof HTMLElement) {
        renderChecklistState(availabilityCheck, !availabilityBlocked && selectedCount > 0);
    }

    if (feedback instanceof HTMLElement) {
        if (basketPageState.feedback) {
            feedback.hidden = false;
            feedback.className = `basket-feedback basket-feedback--${basketPageState.feedback.tone}`;
            feedback.innerHTML = `
                <span class="material-symbols-outlined">info</span>
                <span>${escapeHtml(basketPageState.feedback.message)}</span>
            `;
        } else {
            feedback.hidden = true;
            feedback.textContent = '';
        }
    }

    if (selectedCount === 0) {
        if (itemsHost instanceof HTMLElement) {
            itemsHost.innerHTML = '';
        }

        if (emptyState instanceof HTMLElement) {
            emptyState.hidden = false;
        }
    } else {
        if (emptyState instanceof HTMLElement) {
            emptyState.hidden = true;
        }

        if (itemsHost instanceof HTMLElement) {
            itemsHost.innerHTML = basket.items.map((item) => {
                const issue = itemIssues.get(item.bookId);
                const tone = getAvailabilityTone(item);

                return `
                    <article class="basket-item${issue ? ' is-flagged' : ''}">
                        <div class="basket-item__cover basket-item__cover--${escapeHtml(item.cover.tone)}">
                            ${item.cover.url
                                ? `<img alt="Cover of ${escapeHtml(item.title)}" class="basket-item__cover-image" loading="lazy" src="${escapeHtml(item.cover.url)}">`
                                : `<span class="basket-item__cover-initials">${escapeHtml(item.cover.initials)}</span>`}
                        </div>
                        <div class="basket-item__body">
                            <div class="basket-item__header">
                                <div>
                                    <h3 class="basket-item__title">${escapeHtml(item.title)}</h3>
                                    <p class="basket-item__author">${escapeHtml(item.author)}</p>
                                </div>
                                <button class="basket-item__remove" data-basket-remove="${item.bookId}" type="button">
                                    <span class="material-symbols-outlined">close</span>
                                    Remove
                                </button>
                            </div>
                            <div class="basket-item__meta">
                                <span class="basket-pill basket-pill--category">${escapeHtml(item.category)}</span>
                                <span class="basket-pill basket-pill--${tone}">${escapeHtml(item.availability)}</span>
                                <span class="basket-item__stock">${escapeHtml(item.stock)} ${pluralize(Number(item.stock), 'copy', 'copies')}</span>
                                <a class="basket-item__link" href="${escapeHtml(item.detailUrl)}">View details</a>
                            </div>
                            ${issue
                                ? `<div class="basket-item__issue">
                                    <span class="material-symbols-outlined">warning</span>
                                    <span>${escapeHtml(issue.message)}</span>
                                </div>`
                                : ''}
                        </div>
                    </article>
                `;
            }).join('');
        }
    }

    if (submitState instanceof HTMLElement) {
        if (basketPageState.loading) {
            submitState.textContent = 'Submitting...';
        } else if (selectedCount === 0) {
            submitState.textContent = 'Basket empty';
        } else if (activeLoanBlocked) {
            submitState.textContent = 'Submission locked';
        } else if (!withinLimit) {
            submitState.textContent = 'Limit reached';
        } else if (availabilityBlocked) {
            submitState.textContent = 'Fix highlighted items';
        } else {
            submitState.textContent = 'Ready to submit';
        }
    }

    if (inlineState instanceof HTMLElement) {
        const title = inlineState.querySelector('.basket-inline-state__title');
        const text = inlineState.querySelector('.basket-inline-state__text');

        if (title instanceof HTMLElement && text instanceof HTMLElement) {
            if (selectedCount === 0) {
                title.textContent = 'Build your request';
                text.textContent = 'Add titles from the catalog, then return here to review them.';
            } else if (activeLoanBlocked) {
                title.textContent = 'Active loan found';
                text.textContent = 'Submission is unavailable until your current pending or active loan is resolved.';
            } else if (!withinLimit) {
                title.textContent = 'Too many titles selected';
                text.textContent = `Remove at least ${selectedCount - maxItems} ${pluralize(selectedCount - maxItems, 'title', 'titles')} to continue.`;
            } else if (availabilityBlocked) {
                title.textContent = 'Review highlighted titles';
                text.textContent = 'One or more titles can no longer be requested in their current state.';
            } else {
                title.textContent = 'Approval required';
                text.textContent = 'One request. Multiple titles. Admin reviews after submission.';
            }
        }
    }

    if (submitButton instanceof HTMLButtonElement) {
        submitButton.disabled = !canSubmit;
        submitButton.classList.toggle('is-loading', basketPageState.loading);

        if (basketPageState.loading) {
            submitButton.innerHTML = '<span class="material-symbols-outlined">progress_activity</span>Submitting...';
        } else {
            submitButton.innerHTML = '<span class="material-symbols-outlined">send</span>Submit Loan Request';
        }
    }

    if (successState instanceof HTMLElement && summaryPanel instanceof HTMLElement) {
        const shouldShowSuccess = basketPageState.completedLoan !== null && selectedCount === 0;

        successState.hidden = !shouldShowSuccess;
        summaryPanel.parentElement?.classList.toggle('is-complete', shouldShowSuccess);

        if (shouldShowSuccess) {
            const successMessage = successState.querySelector('[data-basket-success-message]');
            const successReference = successState.querySelector('[data-basket-success-reference]');
            const successLoans = successState.querySelector('[data-basket-success-loans]');

            if (successMessage instanceof HTMLElement) {
                successMessage.textContent = basketPageState.completedMessage || 'Your request is now waiting for admin approval.';
            }

            if (successReference instanceof HTMLElement) {
                successReference.textContent = basketPageState.completedLoan.reference || 'LN-0000';
            }

            if (successLoans instanceof HTMLAnchorElement && basketPageState.completedLoan.redirectUrl) {
                successLoans.href = basketPageState.completedLoan.redirectUrl;
            }
        }
    }
};

const clearBasketWithConfirmation = () => {
    const basket = basketStore.load();

    if (basket.items.length === 0) {
        setFeedbackBanner('Your basket is already empty.', 'info');
        renderBasketPage();
        return;
    }

    if (!window.confirm('Clear all titles from your borrow basket?')) {
        return;
    }

    basketPageState.issues = [];
    basketPageState.completedLoan = null;
    basketPageState.completedMessage = '';
    setFeedbackBanner('Basket cleared.', 'info');
    basketStore.clear();
    showToast('Borrow basket cleared.', 'info');
};

const submitBasketRequest = async () => {
    const pageConfig = readBasketPageConfig();

    if (!pageConfig) {
        return;
    }

    const basket = basketStore.load();

    if (basket.items.length === 0) {
        setFeedbackBanner('Your basket is empty. Add at least one title before submitting.', 'warning');
        renderBasketPage();
        return;
    }

    basketPageState.loading = true;
    basketPageState.issues = [];
    basketPageState.serverMeta = {};
    setFeedbackBanner(null);
    renderBasketPage();

    try {
        const response = await window.fetch(pageConfig.submitUrl, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                _token: pageConfig.csrfToken,
                items: basket.items.map((item) => ({
                    book_id: item.bookId,
                    quantity: 1,
                })),
            }),
        });

        const payload = await response.json().catch(() => null);

        if (!response.ok || !payload || payload.success !== true) {
            basketPageState.issues = Array.isArray(payload?.issues) ? payload.issues : [];
            basketPageState.serverMeta = payload?.meta && typeof payload.meta === 'object' ? payload.meta : {};
            basketPageState.completedLoan = null;
            basketPageState.completedMessage = '';

            if (payload?.code === 'session_expired') {
                setFeedbackBanner(payload.message || 'Your session has expired. Please sign in again.', 'danger');
            } else if (payload?.code === 'validation_failed') {
                setFeedbackBanner(payload.message || 'Review the highlighted items before submitting.', 'warning');
            } else {
                setFeedbackBanner(payload?.message || 'We could not submit your request. Please try again.', 'danger');
            }

            renderBasketPage();
            return;
        }

        basketPageState.completedLoan = {
            reference: payload.loan?.reference || 'LN-0000',
            redirectUrl: payload.redirectUrl || pageConfig.myLoansUrl,
        };
        basketPageState.completedMessage = payload.message || 'Your request is now waiting for admin approval.';
        basketPageState.issues = [];
        basketPageState.serverMeta = {};
        setFeedbackBanner(null);
        basketStore.clear();
        showToast('Loan request submitted successfully.', 'success');
    } catch (error) {
        setFeedbackBanner('Network issue detected. Your basket is still safe. Please retry when the connection stabilizes.', 'danger');
    } finally {
        basketPageState.loading = false;
        renderBasketPage();
    }
};

const initBasketPage = () => {
    const page = document.querySelector('[data-basket-page]');

    if (!(page instanceof HTMLElement) || page.dataset.basketPageBound === 'true') {
        renderBasketPage();
        return;
    }

    page.dataset.basketPageBound = 'true';

    page.addEventListener('click', (event) => {
        const target = event.target;

        if (!(target instanceof Element)) {
            return;
        }

        const removeButton = target.closest('[data-basket-remove]');
        if (removeButton instanceof HTMLButtonElement) {
            const bookId = Number(removeButton.dataset.basketRemove || 0);
            basketPageState.issues = basketPageState.issues.filter((issue) => Number(issue.book_id || 0) !== bookId);
            basketStore.remove(bookId);
            showToast('Title removed from your basket.', 'info');
            return;
        }

        const clearButton = target.closest('[data-basket-clear-button]');
        if (clearButton instanceof HTMLButtonElement) {
            clearBasketWithConfirmation();
        }
    });

    const submitButton = page.querySelector('[data-basket-submit-button]');
    if (submitButton instanceof HTMLButtonElement) {
        submitButton.addEventListener('click', () => {
            if (!basketPageState.loading) {
                submitBasketRequest();
            }
        });
    }

    renderBasketPage();
};

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
        if (form.dataset.loadingBound === 'true') {
            return;
        }

        form.dataset.loadingBound = 'true';
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

    if (!(uploadArea instanceof HTMLElement) || !(coverInput instanceof HTMLInputElement) || uploadArea.dataset.coverBound === 'true') {
        return;
    }

    uploadArea.dataset.coverBound = 'true';

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
};

const initBasketObservers = () => {
    if (document.documentElement.dataset.basketObserversBound === 'true') {
        return;
    }

    document.documentElement.dataset.basketObserversBound = 'true';

    window.addEventListener('basket:updated', () => {
        renderBasketChrome();
        syncBasketAddButtons();
        renderBasketPage();
    });

    window.addEventListener('storage', (event) => {
        if (event.key !== readAppShellConfig().basket?.storageKey) {
            return;
        }

        renderBasketChrome();
        syncBasketAddButtons();
        renderBasketPage();
    });
};

const initApp = () => {
    document.querySelectorAll('[data-year]').forEach((node) => {
        node.textContent = new Date().getFullYear();
    });

    initUserDropdown();
    initLoadingForms();
    initCoverUpload();
    initBasketObservers();
    initBasketAddButtons();
    renderBasketChrome();
    initBasketPage();
};

document.addEventListener('DOMContentLoaded', initApp);
window.addEventListener('pageshow', initApp);
