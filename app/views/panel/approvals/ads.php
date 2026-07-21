<?php use App\Core\{Helper, CSRF}; ?>

<div class="portal-page-header">
  <div>
    <h2 class="portal-page-title">Approve Ads <span class="text-muted fw-300 fs-6">(<?= number_format($total) ?>)</span></h2>
    <p style="font-size:13px;color:var(--portal-muted);margin:2px 0 0">Business ads awaiting approval</p>
  </div>
  <a href="<?= $r ?>/panel/approvals/news" class="btn btn-sm btn-outline-secondary">← News Queue</a>
</div>

<div class="portal-card">
  <div class="table-responsive">
    <table class="table tn-table mb-0" style="font-size:13px">
      <thead><tr><th>Business</th><th>Contact</th><th>Package</th><th>Requested</th><th>Actions</th></tr></thead>
      <tbody>
      <?php if (empty($ads)): ?>
      <tr><td colspan="5" class="text-center py-5 text-muted">Nothing pending approval.</td></tr>
      <?php endif; ?>
      <?php foreach ($ads as $ad): ?>
      <tr>
        <td class="fw-500"><?= Helper::e($ad['business_name']) ?></td>
        <td><?= Helper::e($ad['contact_person'] ?? '—') ?><div class="text-muted small"><?= Helper::e($ad['contact_phone'] ?? '') ?></div></td>
        <td><?= Helper::e($ad['package_name'] ?? '—') ?></td>
        <td class="text-muted small"><?= Helper::timeAgo($ad['created_at']) ?></td>
        <td>
          <form action="<?= $r ?>/panel/approvals/ads/<?= $ad['id'] ?>/approve" method="POST" class="d-inline">
            <?= CSRF::field() ?>
            <button class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i> Approve</button>
          </form>
          <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectAd<?= $ad['id'] ?>">
            <i class="bi bi-x-lg"></i> Reject
          </button>
        </td>
      </tr>

      <div class="modal fade" id="rejectAd<?= $ad['id'] ?>" tabindex="-1">
        <div class="modal-dialog">
          <form class="modal-content" action="<?= $r ?>/panel/approvals/ads/<?= $ad['id'] ?>/reject" method="POST">
            <?= CSRF::field() ?>
            <div class="modal-header"><h6 class="modal-title">Reject: <?= Helper::e($ad['business_name']) ?></h6></div>
            <div class="modal-body">
              <label class="form-label">Reason</label>
              <textarea name="reason" class="form-control" rows="3" required></textarea>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
              <button class="btn btn-danger">Reject</button>
            </div>
          </form>
        </div>
      </div>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
