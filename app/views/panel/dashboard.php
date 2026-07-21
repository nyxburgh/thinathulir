<div class="portal-page-header">
  <div>
    <h2 class="portal-page-title">Sub Admin Panel</h2>
    <p style="font-size:13px;color:var(--portal-muted);margin:2px 0 0">Import, approvals and rate cards — nothing else</p>
  </div>
</div>

<div class="row g-4">
  <div class="col-md-4">
    <a href="<?= $r ?>/panel/import" class="text-decoration-none">
      <div class="portal-card p-4">
        <i class="bi bi-link-45deg" style="font-size:28px;color:#7C3AED"></i>
        <h5 class="mt-2 mb-1">Import URL</h5>
        <p class="text-muted small mb-0">Fetch title &amp; content from an external article.</p>
      </div>
    </a>
  </div>
  <div class="col-md-4">
    <a href="<?= $r ?>/panel/approvals/news" class="text-decoration-none">
      <div class="portal-card p-4">
        <i class="bi bi-check2-square" style="font-size:28px;color:#7C3AED"></i>
        <h5 class="mt-2 mb-1">Approvals</h5>
        <p class="text-muted small mb-0">
          <?= (int)$pendingNews ?> news pending · <?= (int)$pendingAds ?> ads pending
        </p>
      </div>
    </a>
  </div>
  <div class="col-md-4">
    <a href="<?= $r ?>/panel/rates" class="text-decoration-none">
      <div class="portal-card p-4">
        <i class="bi bi-currency-exchange" style="font-size:28px;color:#7C3AED"></i>
        <h5 class="mt-2 mb-1">Rate Cards</h5>
        <p class="text-muted small mb-0">Update gold/silver/petrol/currency rates.</p>
      </div>
    </a>
  </div>
</div>
