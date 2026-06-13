<section class="book-detail">
    <article class="book-detail__card">
        <p class="catalog-section__eyebrow">Book Detail</p>
        <h1 class="book-detail__title"><?= htmlspecialchars($book['title']); ?></h1>
        <div class="book-detail__meta">
            <span class="book-card__category book-card__category--<?= htmlspecialchars($book['category']['tone']); ?>">
                <?= htmlspecialchars($book['category']['name']); ?>
            </span>
            <span class="book-card__status book-card__status--<?= htmlspecialchars($book['availability']['tone']); ?>">
                <?= htmlspecialchars($book['availability']['label']); ?>
            </span>
            <span class="book-detail__support">Author: <?= htmlspecialchars($book['author']); ?></span>
            <span class="book-detail__support">Stock: <?= htmlspecialchars((string) $book['stock']); ?></span>
        </div>
        <p class="book-detail__description"><?= nl2br(htmlspecialchars($book['description'])); ?></p>
    </article>

    <aside class="book-detail__sidebar">
                <?php if ($book['cover']['url'] !== null): ?>
        <div class="book-detail__cover" style="padding: 0; min-height: 320px;">
            <img
                src="<?= htmlspecialchars($book['cover']['url']); ?>"
                alt="Cover of <?= htmlspecialchars($book['title']); ?>"
                class="book-detail__cover-img"
                loading="lazy"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
            >
            <div class="book-detail__cover book-detail__cover--<?= htmlspecialchars($book['cover']['tone']); ?>" style="display: none; min-height: 320px;">
                <span class="book-detail__cover-badge"><?= htmlspecialchars($book['category']['name']); ?></span>
                <span class="book-detail__cover-initials"><?= htmlspecialchars($book['cover']['initials']); ?></span>
            </div>
        </div>
        <?php else: ?>
        <div class="book-detail__cover book-detail__cover--<?= htmlspecialchars($book['cover']['tone']); ?>">
            <span class="book-detail__cover-badge"><?= htmlspecialchars($book['category']['name']); ?></span>
            <span class="book-detail__cover-initials"><?= htmlspecialchars($book['cover']['initials']); ?></span>
        </div>
        <?php endif; ?>

        <div class="book-detail__cta">
            <a class="book-card__button book-card__button--ghost" href="<?= htmlspecialchars(url('/books')); ?>">Back to Catalog</a>
            <?php if (auth_check() && ! auth_is_admin()): ?>
                <button
                    class="catalog-chip catalog-chip--basket"
                    data-basket-add
                    data-basket-book="<?= json_attr($book['basket_item']); ?>"
                    data-default-text="Request Loan"
                    data-added-text="In Basket"
                    data-loading-text="Adding..."
                    data-limit-text="Limit Reached"
                    data-unavailable-text="Unavailable"
                    type="button"
                    <?= ! $book['available_for_request'] ? 'disabled' : ''; ?>
                >
                    Request Loan
                </button>
            <?php elseif (! auth_check()): ?>
                <a class="catalog-chip" href="<?= htmlspecialchars(url('/login')); ?>">Login to Borrow</a>
            <?php endif; ?>
        </div>
    </aside>
</section>

<?php if ($relatedBooks !== []): ?>
    <section class="book-detail__related">
        <div class="catalog-section__header">
            <div>
                <p class="catalog-section__eyebrow">Related Titles</p>
                <h2 class="catalog-section__title">More from this category</h2>
                <p class="catalog-section__subtitle">Quick picks to continue exploring the collection.</p>
            </div>
        </div>

        <div class="catalog-grid">
            <?php foreach ($relatedBooks as $relatedBook): ?>
                                <article class="book-card">
                    <?php if ($relatedBook['cover']['url'] !== null): ?>
                    <div class="book-card__cover" style="padding: 0; min-height: 240px;">
                        <img
                            src="<?= htmlspecialchars($relatedBook['cover']['url']); ?>"
                            alt="Cover of <?= htmlspecialchars($relatedBook['title']); ?>"
                            class="book-card__cover-img"
                            loading="lazy"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                        >
                        <div class="book-card__cover book-card__cover--<?= htmlspecialchars($relatedBook['cover']['tone']); ?>" style="display: none; min-height: 240px;">
                            <span class="book-card__cover-badge"><?= htmlspecialchars($relatedBook['availability']['label']); ?></span>
                            <span class="book-card__cover-initials"><?= htmlspecialchars($relatedBook['cover']['initials']); ?></span>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="book-card__cover book-card__cover--<?= htmlspecialchars($relatedBook['cover']['tone']); ?>">
                        <span class="book-card__cover-badge"><?= htmlspecialchars($relatedBook['availability']['label']); ?></span>
                        <span class="book-card__cover-initials"><?= htmlspecialchars($relatedBook['cover']['initials']); ?></span>
                    </div>
                    <?php endif; ?>

                    <span class="book-card__category book-card__category--<?= htmlspecialchars($relatedBook['category']['tone']); ?>">
                        <?= htmlspecialchars($relatedBook['category']['name']); ?>
                    </span>

                    <div>
                        <h3 class="book-card__title"><?= htmlspecialchars($relatedBook['title']); ?></h3>
                        <p class="book-card__author">by <?= htmlspecialchars($relatedBook['author']); ?></p>
                    </div>

                    <p class="book-card__description"><?= htmlspecialchars($relatedBook['description']); ?></p>

                    <div class="book-card__actions">
                        <a class="book-card__button book-card__button--ghost" href="<?= htmlspecialchars(url('/books/show?id=' . $relatedBook['id'])); ?>">View Details</a>
                        <?php if (auth_check() && ! auth_is_admin()): ?>
                            <button
                                class="book-card__button"
                                data-basket-add
                                data-basket-book="<?= json_attr($relatedBook['basket_item']); ?>"
                                data-default-text="Request Loan"
                                data-added-text="In Basket"
                                data-loading-text="Adding..."
                                data-limit-text="Limit Reached"
                                data-unavailable-text="Unavailable"
                                type="button"
                                <?= ! $relatedBook['available_for_request'] ? 'disabled' : ''; ?>
                            >
                                Request Loan
                            </button>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>
