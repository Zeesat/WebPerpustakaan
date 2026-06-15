<div class="basket-page" data-basket-page>
    <script id="basket-page-config" type="application/json"><?= json_encode($basketConfig, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?></script>

    <div class="cart-header">
        <div class="cart-header__title-group">
            <h1 class="cart-header__title">Borrowing List</h1>
            <span class="cart-header__count" data-basket-list-count>0 items</span>
        </div>
        <div class="cart-header__actions">
            <a class="cart-btn cart-btn--ghost" href="<?= htmlspecialchars(url('/books')); ?>">
                <span class="material-symbols-outlined">arrow_back</span>
                Continue Browsing
            </a>
            <button class="cart-btn cart-btn--danger-text" data-basket-clear-button type="button">
                <span class="material-symbols-outlined">delete_outline</span>
                Clear List
            </button>
        </div>
    </div>

    <?php if ($blockingNotice !== null): ?>
        <div class="cart-banner cart-banner--warning" data-basket-server-banner>
            <span class="material-symbols-outlined">error_outline</span>
            <span><?= htmlspecialchars($blockingNotice); ?></span>
        </div>
    <?php endif; ?>

    <div class="basket-feedback" data-basket-feedback hidden></div>

    <div class="cart-layout">
        <section class="cart-main">
            <div class="cart-rules" aria-label="Borrow rules">
                <?php foreach ($basketRules as $rule): ?>
                    <span class="cart-rule cart-rule--<?= htmlspecialchars($rule['tone']); ?>">
                        <span class="material-symbols-outlined"><?= htmlspecialchars($rule['icon']); ?></span>
                        <?= htmlspecialchars($rule['label']); ?>
                    </span>
                <?php endforeach; ?>
            </div>

            <div class="cart-empty-state" data-basket-empty-state hidden>
                <span class="material-symbols-outlined cart-empty-state__icon">book</span>
                <h3 class="cart-empty-state__title">Your borrowing list is empty</h3>
                <a class="cart-btn cart-btn--primary" href="<?= htmlspecialchars(url('/books')); ?>">Browse Catalog</a>
            </div>

            <div class="cart-items" data-basket-items></div>
        </section>

        <aside class="cart-sidebar" data-basket-summary-panel>
            <div class="cart-sidebar__header">
                <h2 class="cart-sidebar__title">Request Summary</h2>
                <span class="cart-status" data-basket-submit-state>Ready</span>
            </div>

            <div class="cart-summary-stats">
                <div class="cart-stat">
                    <span class="cart-stat__label">Selected</span>
                    <span class="cart-stat__value" data-basket-summary-selected>0</span>
                </div>
                <div class="cart-stat">
                    <span class="cart-stat__label">Remaining</span>
                    <span class="cart-stat__value" data-basket-summary-remaining><?= htmlspecialchars((string) $basketConfig['maxItems']); ?></span>
                </div>
                <div class="cart-stat">
                    <span class="cart-stat__label">Due In</span>
                    <span class="cart-stat__value"><?= htmlspecialchars((string) $basketConfig['loanDurationDays']); ?> days</span>
                </div>
            </div>

            <div class="cart-checklist">
                <div class="cart-check" data-basket-check="count">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span>Within limit</span>
                </div>
                <div class="cart-check" data-basket-check="active">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span>No active blocks</span>
                </div>
                <div class="cart-check" data-basket-check="availability">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span>Available</span>
                </div>
            </div>

            <button class="cart-btn cart-btn--primary cart-btn--block cart-submit-btn" data-basket-submit-button type="button">
                Submit Request
            </button>
            <a class="cart-btn cart-btn--outline cart-btn--block" href="<?= htmlspecialchars(url('/loans')); ?>">
                My Loans
            </a>
        </aside>
    </div>

    <section class="cart-success" data-basket-success-state hidden>
        <span class="material-symbols-outlined cart-success__icon">check_circle</span>
        <h2 class="cart-success__title">Request Submitted</h2>
        <p class="cart-success__text" data-basket-success-message>Your request is pending admin approval.</p>
        <div class="cart-success__meta">
            <span class="cart-success__chip" data-basket-success-reference>LN-0000</span>
            <span class="cart-success__chip">Pending</span>
        </div>
        <div class="cart-success__actions">
            <a class="cart-btn cart-btn--primary" data-basket-success-loans href="<?= htmlspecialchars(url('/loans')); ?>">View Loans</a>
            <a class="cart-btn cart-btn--ghost" href="<?= htmlspecialchars(url('/books')); ?>">Browse More</a>
        </div>
    </section>
</div>
