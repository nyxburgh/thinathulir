<?php use App\Core\{Helper, CSRF}; ?>
<div class="portal-page-header">
  <div>
    <h2 class="portal-page-title">My Series</h2>
    <p style="font-size:13px;color:var(--portal-muted);margin:2px 0 0">Group your articles into a story, web series, or ongoing coverage</p>
  </div>
  <a href="<?= $r ?>/contribute/series/create" class="btn btn-success">
    <i class="bi bi-plus-circle me-2"></i>New Series
  </a>
</div>

<div class="portal-card">
  <div class="table-responsive">
    <table class="table tn-table mb-0">
      <thead>
        <tr><th>Title</th><th>Category</th><th>Status</th><th>Parts</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php if (empty($series)): ?>
        <tr><td colspan="5" class="text-center py-5 text-muted">
          No series yet. <a href="<?= $r ?>/contribute/series/create">Create your first series →</a>
        </td></tr>
        <?php endif; ?>
        <?php foreach ($series as $s): ?>
        <tr>
          <td>
            <div class="fw-500"><?= Helper::e($s['title']) ?></div>
            <div class="text-muted small">/series/<?= Helper::e($s['slug']) ?></div>
          </td>
          <td><span style="font-size:11px;color:#10b981;font-weight:600"><?= Helper::e($s['category_name'] ?? '') ?></span></td>
          <td>
            <span class="badge bg-<?= $s['status'] === 'completed' ? 'secondary' : 'success' ?>"><?= ucfirst($s['status']) ?></span>
          </td>
          <td class="text-muted small"><?= (int)$s['part_count'] ?> part<?= (int)$s['part_count'] === 1 ? '' : 's' ?> (<?= (int)$s['published_count'] ?> published)</td>
          <td>
            <a href="<?= $r ?>/contribute/series/edit/<?= $s['id'] ?>" class="btn btn-sm btn-outline-primary" title="Edit">
              <i class="bi bi-pencil"></i>
            </a>
            <a href="<?= $r ?>/contribute/articles/create?series_id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-success" title="Add part">
              <i class="bi bi-plus-lg"></i>
            </a>
            <?php if ((int)$s['part_count'] === 0): ?>
            <form action="<?= $r ?>/contribute/series/delete/<?= $s['id'] ?>" method="POST" class="d-inline"
                  onsubmit="return confirm('Delete this series?')">
              <?= CSRF::field() ?>
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </form>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
