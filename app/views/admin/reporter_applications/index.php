<?php use App\Core\Helper; ?>
<div class="tn-page-header">
  <div>
    <h2 class="tn-page-title">Reporter Applications</h2>
    <p class="tn-page-sub"><?= number_format($total) ?> applications · <span class="text-warning fw-600"><?= $pending ?> pending</span></p>
  </div>
</div>

<?php if ($pending > 0): ?>
<div class="alert alert-warning py-2 mb-3 small">
  <i class="bi bi-exclamation-triangle me-1"></i>
  <?= $pending ?> application<?= $pending > 1 ? 's' : '' ?> waiting for review.
</div>
<?php endif; ?>

<div class="tn-card">
  <div class="tn-card-body p-0">
    <?php if (empty($applications)): ?>
    <div class="text-center text-muted py-5">No reporter applications yet.</div>
    <?php else: ?>
    <table class="table table-sm table-hover mb-0">
      <thead>
        <tr>
          <th>Applicant</th>
          <th>District</th>
          <th>Experience</th>
          <th>Status</th>
          <th>Date</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($applications as $app):
          $sc = match($app['status']) {
            'contacted' => 'info',
            'rejected'  => 'secondary',
            default     => 'warning'
          };
        ?>
        <tr>
          <td>
            <div class="fw-600 small"><?= Helper::e($app['name']) ?></div>
            <div class="text-muted" style="font-size:11px"><?= Helper::e($app['phone']) ?></div>
          </td>
          <td class="small text-muted"><?= Helper::e($app['district_name'] ?? '—') ?></td>
          <td class="small text-muted"><?= Helper::e($app['experience'] ?: '—') ?></td>
          <td><span class="badge bg-<?= $sc ?>"><?= ucfirst($app['status']) ?></span></td>
          <td class="small text-muted"><?= substr($app['created_at'], 0, 10) ?></td>
          <td>
            <a href="<?= $r . ($raBase ?? '/portal/reporter-applications') ?>/<?= $app['id'] ?>"
               class="btn btn-xs btn-outline-primary">View</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>
<?php $queryExtra = ''; include VIEW_PATH . '/partials/pagination.php'; ?>
