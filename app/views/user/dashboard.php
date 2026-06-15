<div class="user-dashboard">
  <header class="user-dashboard__header">
    <div>
      <h1 class="user-dashboard__greeting">Hello, <?= htmlspecialchars((string) (auth_user()['name'] ?? 'Member')); ?> 👋</h1>
      <p class="user-dashboard__subtitle">Track your loans, browse the catalog, and manage your requests.</p>
    </div>
    <a href="/catalog" class="user-dashboard__cta">
      <span class="material-symbols-outlined">search</span>
      Browse Catalog
    </a>
  </header>

  <div class="user-dashboard__stats">
    <div class="user-dashboard__stat">
      <div class="user-dashboard__stat-icon user-dashboard__stat-icon--pending">
        <span class="material-symbols-outlined">schedule</span>
      </div>
      <div>
        <p class="user-dashboard__stat-value"><?= (int) ($metrics['pending'] ?? 0); ?></p>
        <p class="user-dashboard__stat-label">Pending</p>
      </div>
    </div>
    <div class="user-dashboard__stat">
      <div class="user-dashboard__stat-icon user-dashboard__stat-icon--approved">
        <span class="material-symbols-outlined">check_circle</span>
      </div>
      <div>
        <p class="user-dashboard__stat-value"><?= (int) ($metrics['approved'] ?? 0); ?></p>
        <p class="user-dashboard__stat-label">Approved</p>
      </div>
    </div>
    <div class="user-dashboard__stat">
      <div class="user-dashboard__stat-icon user-dashboard__stat-icon--returned">
        <span class="material-symbols-outlined">book</span>
      </div>
      <div>
        <p class="user-dashboard__stat-value"><?= (int) ($metrics['returned'] ?? 0); ?></p>
        <p class="user-dashboard__stat-label">Returned</p>
      </div>
    </div>
    <div class="user-dashboard__stat">
      <div class="user-dashboard__stat-icon user-dashboard__stat-icon--late">
        <span class="material-symbols-outlined">warning</span>
      </div>
      <div>
        <p class="user-dashboard__stat-value"><?= (int) ($metrics['late'] ?? 0); ?></p>
        <p class="user-dashboard__stat-label">Late</p>
      </div>
    </div>
  </div>

  <div class="user-dashboard__grid">
    <section class="user-dashboard__card">
      <div class="user-dashboard__card-header">
        <h2 class="user-dashboard__card-title">Active Loans</h2>
        <a href="/loans" class="user-dashboard__card-link">View all</a>
      </div>
      <?php if (! empty($activeLoans ?? [])): ?>
        <div class="user-dashboard__loan-list">
          <?php foreach ($activeLoans as $loan): ?>
            <div class="user-dashboard__loan">
              <div class="user-dashboard__loan-cover">
                <?php if (! empty($loan['cover'])): ?>
                  <img src="<?= htmlspecialchars((string) cover_url($loan['cover'])); ?>" alt="" class="user-dashboard__loan-cover-img">
                <?php else: ?>
                  <span class="user-dashboard__loan-cover-placeholder"><?= htmlspecialchars(strtoupper(substr((string) ($loan['title'] ?? 'B'), 0, 2))); ?></span>
                <?php endif; ?>
              </div>
              <div class="user-dashboard__loan-info">
                <h3 class="user-dashboard__loan-title"><?= htmlspecialchars((string) ($loan['title'] ?? 'Untitled')); ?></h3>
                <p class="user-dashboard__loan-meta">Due: <?= htmlspecialchars((string) ($loan['due_date'] ?? '—')); ?></p>
              </div>
              <span class="user-dashboard__loan-badge user-dashboard__loan-badge--<?= htmlspecialchars((string) ($loan['status'] ?? 'pending')); ?>">
                <?= htmlspecialchars(ucfirst((string) ($loan['status'] ?? 'Pending'))); ?>
              </span>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="user-dashboard__empty">
          <span class="material-symbols-outlined">auto_stories</span>
          <p>No active loans. <a href="/catalog">Browse books</a> to get started.</p>
        </div>
      <?php endif; ?>
    </section>

    <section class="user-dashboard__card">
      <div class="user-dashboard__card-header">
        <h2 class="user-dashboard__card-title">Recent Requests</h2>
        <a href="/loans" class="user-dashboard__card-link">View all</a>
      </div>
      <?php if (! empty($recentRequests ?? [])): ?>
        <div class="user-dashboard__request-list">
          <?php foreach ($recentRequests as $req): ?>
            <div class="user-dashboard__request">
              <p class="user-dashboard__request-title"><?= htmlspecialchars((string) ($req['title'] ?? 'Untitled')); ?></p>
              <span class="user-dashboard__request-date"><?= htmlspecialchars((string) ($req['requested_at'] ?? '')); ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="user-dashboard__empty">
          <span class="material-symbols-outlined">history</span>
          <p>No recent requests yet.</p>
        </div>
      <?php endif; ?>
    </section>
  </div>
</div>
