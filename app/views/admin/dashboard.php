<section class="page-header">
    <h1 class="page-title">Admin Dashboard</h1>
    <p class="page-subtitle">Overview of library statistics, pending verifications, and system health.</p>
</section>

<section class="grid" style="margin-top: 24px;">
    <article class="grid-card">
        <p class="meta" style="text-transform: uppercase; letter-spacing: 0.08em; font-size: 0.82rem; font-weight: 700;">Book Titles</p>
        <h2 style="margin: 8px 0 0; font-size: 2.4rem;"><?= htmlspecialchars(number_format($totalBooks)); ?></h2>
        <p class="meta"><?= htmlspecialchars(number_format($availableTitles)); ?> available, <?= htmlspecialchars(number_format($outOfStockTitles)); ?> out of stock</p>
    </article>
    <article class="grid-card">
        <p class="meta" style="text-transform: uppercase; letter-spacing: 0.08em; font-size: 0.82rem; font-weight: 700;">Total Copies</p>
        <h2 style="margin: 8px 0 0; font-size: 2.4rem;"><?= htmlspecialchars(number_format($totalCopies)); ?></h2>
        <p class="meta">Across all titles in the catalog</p>
    </article>
    <article class="grid-card">
        <p class="meta" style="text-transform: uppercase; letter-spacing: 0.08em; font-size: 0.82rem; font-weight: 700;">Categories</p>
        <h2 style="margin: 8px 0 0; font-size: 2.4rem;"><?= htmlspecialchars(number_format($totalCategories)); ?></h2>
        <p class="meta">Organizing the library collection</p>
    </article>
    <article class="grid-card">
        <p class="meta" style="text-transform: uppercase; letter-spacing: 0.08em; font-size: 0.82rem; font-weight: 700;">Registered Users</p>
        <h2 style="margin: 8px 0 0; font-size: 2.4rem;"><?= htmlspecialchars(number_format($totalUsers)); ?></h2>
        <p class="meta">Active library members</p>
    </article>
    <article class="grid-card">
        <p class="meta" style="text-transform: uppercase; letter-spacing: 0.08em; font-size: 0.82rem; font-weight: 700;">Pending Loans</p>
        <h2 style="margin: 8px 0 0; font-size: 2.4rem;"><?= htmlspecialchars(number_format($pendingLoansCount)); ?></h2>
        <p class="meta">Awaiting verification</p>
    </article>
    <article class="grid-card">
        <p class="meta" style="text-transform: uppercase; letter-spacing: 0.08em; font-size: 0.82rem; font-weight: 700;">Active Loans</p>
        <h2 style="margin: 8px 0 0; font-size: 2.4rem;"><?= htmlspecialchars(number_format($activeLoansCount)); ?></h2>
        <p class="meta">Approved or pending</p>
    </article>
    <article class="grid-card">
        <p class="meta" style="text-transform: uppercase; letter-spacing: 0.08em; font-size: 0.82rem; font-weight: 700;">Late Returns</p>
        <h2 style="margin: 8px 0 0; font-size: 2.4rem;"><?= htmlspecialchars(number_format($lateLoansCount)); ?></h2>
        <p class="meta">Overdue loans requiring attention</p>
    </article>
</section>

<?php if ($pendingLoansCount > 0): ?>
    <section class="panel table-wrap" style="margin-top: 32px;">
        <h3>Recent Pending Loans</h3>
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Loan Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendingLoans as $loan): ?>
                    <tr>
                        <td><?= htmlspecialchars($loan['user_name']); ?> (<?= htmlspecialchars($loan['user_email']); ?>)</td>
                        <td><?= htmlspecialchars($loan['loan_date']); ?></td>
                        <td><?= htmlspecialchars($loan['due_date']); ?></td>
                        <td><span class="badge" style="background: #fef3c7; color: #b45309;">Pending</span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p style="margin: 12px 0 0;"><a href="<?= htmlspecialchars(url('/admin/loans')); ?>">View all loans &rarr;</a></p>
    </section>
<?php endif; ?>

<section class="grid" style="margin-top: 32px;">
    <a class="grid-card" href="<?= htmlspecialchars(url('/admin/books')); ?>" style="cursor: pointer;">
        <h3>Manage Books</h3>
        <p class="meta">Add, edit, or remove books from the catalog.</p>
    </a>
    <a class="grid-card" href="<?= htmlspecialchars(url('/admin/categories')); ?>" style="cursor: pointer;">
        <h3>Manage Categories</h3>
        <p class="meta">Create and organize book categories.</p>
    </a>
    <a class="grid-card" href="<?= htmlspecialchars(url('/admin/users')); ?>" style="cursor: pointer;">
        <h3>Manage Users</h3>
        <p class="meta">View registered library members.</p>
    </a>
    <a class="grid-card" href="<?= htmlspecialchars(url('/admin/loans')); ?>" style="cursor: pointer;">
        <h3>Loan Verification</h3>
        <p class="meta">Approve, reject, and track book loans.</p>
    </a>
</section>

