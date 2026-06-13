<div class="basket-page" data-basket-page>
    <script id="basket-page-config" type="application/json"><?= json_encode($basketConfig, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?></script>

    <section class="basket-hero">
        <div class="basket-hero__copy">
            <span class="basket-eyebrow">Borrow Flow</span>
            <h1 class="basket-hero__title">Borrow Basket</h1>
            <p class="basket-hero__subtitle">Select titles, review the request, then send one approval-ready loan.</p>
        </div>

        <div class="basket-hero__actions">
            <a class="basket-button basket-button--ghost" href="<?= htmlspecialchars(url('/books')); ?>">
                <span class="material-symbols-outlined">arrow_back</span>
                Continue Browsing
            </a>
            <button class="basket-button basket-button--subtle" data-basket-clear-button type="button">
                <span class="material-symbols-outlined">delete_sweep</span>
                Clear Basket
            </button>
        </div>
    </section>

    <section class="basket-highlights" aria-label="Basket summary">
        <?php foreach ($basketHighlights as $highlight): ?>
            <article class="basket-highlight">
                <div class="basket-highlight__icon">
                    <span class="material-symbols-outlined"><?= htmlspecialchars($highlight['icon']); ?></span>
                </div>
                <div class="basket-highlight__body">
                    <p class="basket-highlight__label"><?= htmlspecialchars($highlight['label']); ?></p>
                    <p class="basket-highlight__value"<?= $highlight['label'] === 'Selected' ? ' data-basket-selected-count' : ''; ?>>
                        <?= htmlspecialchars($highlight['value']); ?>
                    </p>
                    <p class="basket-highlight__meta"><?= htmlspecialchars($highlight['meta']); ?></p>
                </div>
            </article>
        <?php endforeach; ?>
    </section>

    <?php if ($blockingNotice !== null): ?>
        <div class="basket-banner basket-banner--warning" data-basket-server-banner>
            <span class="material-symbols-outlined">pending_actions</span>
            <span><?= htmlspecialchars($blockingNotice); ?></span>
        </div>
    <?php endif; ?>

    <div class="basket-feedback" data-basket-feedback hidden></div>

    <div class="basket-layout">
        <section class="basket-panel basket-panel--items">
            <div class="basket-panel__header">
                <div>
                    <span class="basket-eyebrow">Selected Titles</span>
                    <h2 class="basket-panel__title">Review Basket</h2>
                </div>
                <div class="basket-panel__meta">
                    <span class="basket-count-pill" data-basket-list-count>0 items</span>
                </div>
            </div>

            <div class="basket-rules" aria-label="Borrow rules">
                <?php foreach ($basketRules as $rule): ?>
                    <span class="basket-rule basket-rule--<?= htmlspecialchars($rule['tone']); ?>">
                        <span class="material-symbols-outlined"><?= htmlspecialchars($rule['icon']); ?></span>
                        <?= htmlspecialchars($rule['label']); ?>
                    </span>
                <?php endforeach; ?>
            </div>

            <div class="basket-empty-state" data-basket-empty-state hidden>
                <div class="basket-empty-state__icon">
                    <span class="material-symbols-outlined">shopping_basket</span>
                </div>
                <div>
                    <h3 class="basket-empty-state__title">Your basket is empty</h3>
                    <p class="basket-empty-state__text">Pick a few titles, then return here when you're ready to request them.</p>
                </div>
                <a class="basket-button basket-button--primary" href="<?= htmlspecialchars(url('/books')); ?>">
                    Browse Catalog
                </a>
            </div>

            <div class="basket-items" data-basket-items></div>
        </section>

        <aside class="basket-panel basket-panel--summary" data-basket-summary-panel>
            <div class="basket-panel__header basket-panel__header--stack">
                <div>
                    <span class="basket-eyebrow">Submission</span>
                    <h2 class="basket-panel__title">Request Summary</h2>
                </div>
                <span class="basket-status" data-basket-submit-state>Ready to review</span>
            </div>

            <dl class="basket-summary-grid">
                <div class="basket-summary-item">
                    <dt>Selected</dt>
                    <dd data-basket-summary-selected>0</dd>
                </div>
                <div class="basket-summary-item">
                    <dt>Remaining</dt>
                    <dd data-basket-summary-remaining><?= htmlspecialchars((string) $basketConfig['maxItems']); ?></dd>
                </div>
                <div class="basket-summary-item">
                    <dt>Flow</dt>
                    <dd>Pending</dd>
                </div>
                <div class="basket-summary-item">
                    <dt>Due Date</dt>
                    <dd><?= htmlspecialchars((string) $basketConfig['loanDurationDays']); ?> days</dd>
                </div>
            </dl>

            <div class="basket-submit-checklist">
                <div class="basket-submit-checklist__item" data-basket-check="count">
                    <span class="material-symbols-outlined">counter_1</span>
                    <span>Within request limit</span>
                </div>
                <div class="basket-submit-checklist__item" data-basket-check="active">
                    <span class="material-symbols-outlined">counter_2</span>
                    <span>No active loan blocking submission</span>
                </div>
                <div class="basket-submit-checklist__item" data-basket-check="availability">
                    <span class="material-symbols-outlined">counter_3</span>
                    <span>All titles still available</span>
                </div>
            </div>

            <div class="basket-inline-state" data-basket-inline-state>
                <p class="basket-inline-state__title">Approval required</p>
                <p class="basket-inline-state__text">One request. Multiple titles. Admin reviews after submission.</p>
            </div>

            <button class="basket-button basket-button--primary basket-button--full" data-basket-submit-button type="button">
                <span class="material-symbols-outlined">send</span>
                Submit Loan Request
            </button>

            <a class="basket-button basket-button--ghost basket-button--full" href="<?= htmlspecialchars(url('/loans')); ?>">
                View My Loans
            </a>
        </aside>
    </div>

    <section class="basket-success-state" data-basket-success-state hidden>
        <div class="basket-success-state__icon">
            <span class="material-symbols-outlined">verified</span>
        </div>
        <div class="basket-success-state__body">
            <span class="basket-eyebrow">Request Submitted</span>
            <h2 class="basket-success-state__title">Loan request sent</h2>
            <p class="basket-success-state__text" data-basket-success-message>Your request is now waiting for admin approval.</p>
            <div class="basket-success-state__meta">
                <span class="basket-success-chip" data-basket-success-reference>LN-0000</span>
                <span class="basket-success-chip">Pending approval</span>
            </div>
        </div>
        <div class="basket-success-state__actions">
            <a class="basket-button basket-button--primary" data-basket-success-loans href="<?= htmlspecialchars(url('/loans')); ?>">Open My Loans</a>
            <a class="basket-button basket-button--ghost" href="<?= htmlspecialchars(url('/books')); ?>">Continue Browsing</a>
        </div>
    </section>
</div>
