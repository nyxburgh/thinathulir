<?php
use App\Core\{Helper, CSRF};
$statusBadge = [
    'pending'   => 'warning',
    'converted' => 'success',
    'discarded' => 'secondary',
];
?>
<div class="portal-page-header">
  <div>
    <h2 class="portal-page-title">Import from URL</h2>
    <p style="font-size:13px;color:var(--portal-muted);margin:2px 0 0">Fetch title &amp; content from a third-party article</p>
  </div>
</div>

<div class="portal-card mb-4">
  <div class="portal-card-header"><span><i class="bi bi-link-45deg me-2"></i>Fetch a URL</span></div>
  <div class="portal-card-body">
    <form method="POST" action="<?= $r ?>/panel/import/fetch" class="d-flex gap-2 flex-wrap">
      <?= CSRF::field() ?>
      <input type="url" name="source_url" class="form-control" style="flex:1;min-width:260px"
             placeholder="https://example.com/news/some-article" required>
      <button type="submit" class="btn fw-600" style="background:#10b981;color:white">
        <i class="bi bi-download me-2"></i>Fetch Content
      </button>
    </form>
    <small class="text-muted d-block mt-2">Pulls the title and body paragraphs only.</small>
  </div>
</div>

<div class="portal-card">
  <div class="table-responsive">
    <table class="table tn-table mb-0">
      <thead>
        <tr><th>Title / Source</th><th>Status</th><th>Fetched</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php if (empty($imports)): ?>
        <tr><td colspan="4" class="text-center py-5 text-muted">No imports yet. Paste a URL above to get started.</td></tr>
        <?php endif; ?>
        <?php foreach ($imports as $imp): ?>
        <tr>
          <td>
            <div class="fw-500"><?= Helper::e($imp['title'] ?: '(no title found)') ?></div>
            <div class="text-muted small text-truncate" style="max-width:420px">
              <a href="<?= Helper::e($imp['source_url']) ?>" target="_blank" rel="noopener"><?= Helper::e($imp['source_url']) ?></a>
            </div>
          </td>
          <td><span class="badge bg-<?= $statusBadge[$imp['status']] ?? 'secondary' ?>"><?= ucfirst($imp['status']) ?></span></td>
          <td class="text-muted small"><?= date('d M, H:i', strtotime($imp['created_at'])) ?></td>
          <td>
            <?php if ($imp['status'] === 'pending'): ?>
            <form action="<?= $r ?>/panel/import/discard/<?= $imp['id'] ?>" method="POST" class="d-inline"
                  onsubmit="return confirm('Discard this import?')">
              <?= CSRF::field() ?>
              <button class="btn btn-sm btn-outline-danger" title="Discard"><i class="bi bi-trash"></i></button>
            </form>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
