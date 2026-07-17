<?php use App\Core\{Helper, CSRF}; ?>
<div class="portal-page-header">
  <div><h2 class="portal-page-title">✍️ Sponsored Article</h2><p class="portal-page-sub"><?= Helper::e($ad['business_name']) ?></p></div>
  <a href="<?= $r ?>/portal/my-ads/<?= $ad['id'] ?>" class="portal-back-btn">← Back</a>
</div>
<?php if (!empty($quota['quota'])): ?>
<div class="portal-card mb-3"><div class="portal-card-body py-2">
  <small class="text-muted">Quota: <strong><?= (int)($quota['used']??0) ?>/<?= $quota['quota'] ?></strong> used</small>
</div></div>
<?php endif; ?>
<div class="portal-card">
  <div class="portal-card-header">New Sponsored Article</div>
  <div class="portal-card-body">
    <form method="POST" action="<?= $r ?>/portal/my-ads/<?= $ad['id'] ?>/submit-news" enctype="multipart/form-data">
      <?= CSRF::field() ?>
      <div class="mb-3">
        <label class="form-label fw-600 small">Article Title <span class="text-danger">*</span></label>
        <input type="text" name="title" class="form-control" required minlength="5" placeholder="Enter headline...">
      </div>
      <div class="mb-3">
        <label class="form-label fw-600 small">Featured Image</label>
        <input type="file" name="featured_image" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp">
        <div class="form-text">Optional. JPG/PNG/WebP, max 5MB.</div>
      </div>
      <div class="mb-3">
        <label class="form-label fw-600 small">Content <span class="text-danger">*</span></label>
        <textarea name="content" class="form-control" rows="12" required minlength="50" placeholder="Write your sponsored article..."></textarea>
        <div class="form-text">Min 50 characters. Will be reviewed before publishing.</div>
      </div>
      <div class="d-flex gap-2">
        <button class="btn btn-primary">Submit for Review</button>
        <a href="<?= $r ?>/portal/my-ads/<?= $ad['id'] ?>" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
