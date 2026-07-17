<?php use App\Core\{Helper, CSRF}; ?>
<div class="tn-page-header">
  <div>
    <h2 class="tn-page-title">Reporter Application</h2>
    <p class="tn-page-sub"><?= Helper::e($app['name']) ?> · <?= Helper::e($app['phone']) ?></p>
  </div>
  <a href="<?= $r . $raBase ?>" class="btn btn-outline-secondary btn-sm">← Back</a>
</div>

<div class="row g-3">
  <div class="col-lg-8">
    <div class="tn-card mb-3">
      <div class="tn-card-header">
        <span>Applicant Details</span>
        <?php
        $sc = match($app['status']) {
            'contacted' => 'info',
            'rejected'  => 'secondary',
            default     => 'warning'
        };
        ?>
        <span class="badge bg-<?= $sc ?> ms-2"><?= ucfirst($app['status']) ?></span>
      </div>
      <div class="tn-card-body">
        <div class="row g-2 small text-muted mb-3">
          <div class="col-6"><i class="bi bi-person me-1"></i><?= Helper::e($app['name']) ?></div>
          <div class="col-6"><i class="bi bi-telephone me-1"></i><?= Helper::e($app['phone']) ?></div>
          <?php if ($app['email']): ?>
          <div class="col-6"><i class="bi bi-envelope me-1"></i><?= Helper::e($app['email']) ?></div>
          <?php endif; ?>
          <?php if ($app['district_name'] ?? null): ?>
          <div class="col-6"><i class="bi bi-geo me-1"></i><?= Helper::e($app['district_name']) ?></div>
          <?php endif; ?>
          <?php if ($app['experience']): ?>
          <div class="col-6"><i class="bi bi-briefcase me-1"></i><?= Helper::e($app['experience']) ?></div>
          <?php endif; ?>
          <div class="col-6"><i class="bi bi-clock me-1"></i><?= substr($app['created_at'], 0, 16) ?></div>
        </div>
        <?php if ($app['message']): ?>
        <div class="reporter-app-message">
          <?= nl2br(Helper::e($app['message'])) ?>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <?php if ($app['status'] === 'pending'): ?>
    <div class="tn-card">
      <div class="tn-card-body d-flex gap-2">
        <form method="POST" action="<?= $r . $raBase ?>/<?= $app['id'] ?>/contacted">
          <?= CSRF::field() ?>
          <button class="btn btn-success btn-sm"><i class="bi bi-check-circle me-1"></i>Mark Contacted</button>
        </form>
        <form method="POST" action="<?= $r . $raBase ?>/<?= $app['id'] ?>/reject">
          <?= CSRF::field() ?>
          <button class="btn btn-outline-danger btn-sm"><i class="bi bi-x-circle me-1"></i>Reject</button>
        </form>
      </div>
    </div>
    <?php elseif ($app['status'] === 'contacted'): ?>
    <div class="alert alert-info small"><i class="bi bi-check-circle me-1"></i>Already contacted.</div>
    <?php else: ?>
    <div class="alert alert-secondary small"><i class="bi bi-x-circle me-1"></i>Rejected.</div>
    <?php endif; ?>
  </div>
</div>
