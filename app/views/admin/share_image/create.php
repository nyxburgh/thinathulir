<?php use App\Core\{Helper, CSRF}; ?>

<div class="af-topbar">
  <a href="<?= $r . $artBase ?>" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-arrow-left"></i>
  </a>
  <div class="af-topbar-title">Create Share Image</div>
</div>

<div style="max-width:600px;margin:0 auto;padding:0 16px 60px">

  <div class="tn-card mb-3">
    <div class="af-card-head"><?= Helper::e($article['title']) ?></div>
    <div class="af-card-body">
      <?php if (!empty($article['image_url'])): ?>
      <img src="<?= rtrim(ASSET_URL,'/') . '/public' . Helper::e($article['image_url']) ?>"
           style="max-width:100%;border-radius:8px;display:block;border:1px solid #E8E6E0" alt="">
      <?php else: ?>
      <p class="text-muted small mb-0">This article has no featured image — a share image cannot be generated until one is added.</p>
      <?php endif; ?>
    </div>
  </div>

  <?php if (!empty($existing) && !empty($existing['image_path'])): ?>
  <div class="tn-card mb-3">
    <div class="af-card-head">Current Share Image</div>
    <div class="af-card-body">
      <img src="<?= rtrim(ASSET_URL,'/') . '/public' . Helper::e($existing['image_path']) ?>"
           style="max-width:100%;border-radius:8px;display:block;border:1px solid #E8E6E0" alt="">
      <small class="text-muted d-block mt-2">Generating again will replace this image.</small>
    </div>
  </div>
  <?php endif; ?>

  <?php if (!empty($article['image_url'])): ?>
  <form method="POST" action="<?= BASE_URL . '/public' . $base . '/generate/' . $article['id'] ?>">
    <?= CSRF::field() ?>

    <div class="tn-card mb-3">
      <div class="af-card-head">Image Placement</div>
      <div class="af-card-body">
        <div class="d-flex gap-3 flex-wrap">
          <label class="d-flex align-items-center gap-2">
            <input type="radio" name="placement" value="left"> Left
          </label>
          <label class="d-flex align-items-center gap-2">
            <input type="radio" name="placement" value="center" checked> Center
          </label>
          <label class="d-flex align-items-center gap-2">
            <input type="radio" name="placement" value="right"> Right
          </label>
        </div>
        <div class="form-text">Where the article photo sits on the generated 1080×1080 graphic — the headline fills the rest.</div>
      </div>
    </div>

    <button type="submit" class="btn btn-danger w-100">
      <?= !empty($existing) ? '🔄 Regenerate Share Image' : '🖼️ Generate Share Image' ?>
    </button>
  </form>
  <?php endif; ?>

</div>
