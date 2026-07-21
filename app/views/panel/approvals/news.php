<?php use App\Core\{Helper, CSRF}; ?>

<div class="portal-page-header">
  <div>
    <h2 class="portal-page-title">Approve News <span class="text-muted fw-300 fs-6">(<?= number_format($total) ?>)</span></h2>
    <p style="font-size:13px;color:var(--portal-muted);margin:2px 0 0">Articles awaiting review</p>
  </div>
  <a href="<?= $r ?>/panel/approvals/ads" class="btn btn-sm btn-outline-secondary">Ads Queue →</a>
</div>

<div class="portal-card">
  <div class="table-responsive">
    <table class="table tn-table mb-0" style="font-size:13px">
      <thead><tr><th>Title</th><th>Reporter</th><th>Category</th><th>Submitted</th><th>Actions</th></tr></thead>
      <tbody>
      <?php if (empty($articles)): ?>
      <tr><td colspan="5" class="text-center py-5 text-muted">Nothing pending review.</td></tr>
      <?php endif; ?>
      <?php foreach ($articles as $a): ?>
      <tr>
        <td class="fw-500"><?= Helper::e($a['title']) ?></td>
        <td><?= Helper::e($a['author_name'] ?? $a['user_name'] ?? '—') ?></td>
        <td><?= Helper::e($a['category_name'] ?? '—') ?></td>
        <td class="text-muted small"><?= Helper::timeAgo($a['updated_at'] ?? $a['created_at']) ?></td>
        <td>
          <form action="<?= $r ?>/panel/approvals/news/<?= $a['id'] ?>/approve" method="POST" class="d-inline">
            <?= CSRF::field() ?>
            <button class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i> Approve</button>
          </form>
          <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectNews<?= $a['id'] ?>">
            <i class="bi bi-x-lg"></i> Reject
          </button>
        </td>
      </tr>

      <div class="modal fade" id="rejectNews<?= $a['id'] ?>" tabindex="-1">
        <div class="modal-dialog">
          <form class="modal-content" action="<?= $r ?>/panel/approvals/news/<?= $a['id'] ?>/reject" method="POST">
            <?= CSRF::field() ?>
            <div class="modal-header"><h6 class="modal-title">Reject: <?= Helper::e($a['title']) ?></h6></div>
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
