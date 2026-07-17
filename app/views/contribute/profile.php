<?php use App\Core\{Helper, CSRF};
$r = rtrim(ASSET_URL,'/') . '/public';
$assetUrl = $r;
?>
<div class="portal-page-header">
  <div><h2 class="portal-page-title">My Profile</h2></div>
</div>
<div class="portal-card" style="max-width:500px">
  <div class="portal-card-body">
    <form method="POST" action="<?= $r ?>/contribute/profile/update" enctype="multipart/form-data">
      <?= CSRF::field() ?>
      <div class="mb-4 text-center">
        <?php if (!empty($contributor['avatar'])): ?>
        <img src="<?= $assetUrl . Helper::e($contributor['avatar']) ?>"
             style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid #E5E7EB" alt="Avatar">
        <?php else: ?>
        <div style="width:80px;height:80px;border-radius:50%;background:#C0001A;color:#fff;font-size:28px;font-weight:700;display:flex;align-items:center;justify-content:center;margin:0 auto">
          <?= strtoupper(substr($contributor['name']??'C',0,1)) ?>
        </div>
        <?php endif; ?>
        <div class="mt-2">
          <label class="form-label small text-muted">Change Avatar</label>
          <input type="file" name="avatar" class="form-control form-control-sm" accept="image/*">
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label fw-600 small">Name</label>
        <input type="text" class="form-control form-control-sm" value="<?= Helper::e($contributor['name']??'') ?>" disabled>
        <div class="form-text">Contact admin to change name.</div>
      </div>
      <div class="mb-3">
        <label class="form-label fw-600 small">Email</label>
        <input type="text" class="form-control form-control-sm" value="<?= Helper::e($contributor['email']??'') ?>" disabled>
      </div>
      <div class="mb-3">
        <label class="form-label fw-600 small">Bio</label>
        <textarea name="bio" class="form-control form-control-sm" rows="4" placeholder="Short bio..."><?= Helper::e($contributor['bio']??'') ?></textarea>
      </div>
      <button class="btn btn-primary w-100">Save Changes</button>
    </form>
  </div>
</div>
