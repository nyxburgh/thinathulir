<?php use App\Core\{Helper, CSRF}; ?>
<div class="tn-page-header">
  <h2 class="tn-page-title">🏢 Company Ads</h2>
  <p style="color:#9CA3AF;font-size:13px;margin:4px 0 0">
    House banners shown on individual ad pages (/ad/{id}) in place of other customers' ads.
  </p>
</div>

<div class="row g-4">
  <?php foreach ([
    ['type' => 'vertical',   'label' => 'Vertical Poster (250×750) — desktop sidebar'],
    ['type' => 'square',     'label' => 'Square Ad (900×450) — desktop, shown twice'],
    ['type' => 'horizontal', 'label' => 'Horizontal / Mobile Card (1000×150) — mobile only'],
  ] as $slot): ?>
  <div class="col-md-4">
    <div class="tn-card">
      <div class="tn-card-header"><?= Helper::e($slot['label']) ?></div>
      <div class="tn-card-body">

        <?php foreach ($bySlot[$slot['type']] as $ad): ?>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;padding:8px;border:1px solid #E8E6E0;border-radius:6px;<?= $ad['is_active'] ? '' : 'opacity:.45' ?>">
          <img src="<?= rtrim(ASSET_URL, '/') . '/public' . Helper::e($ad['filepath']) ?>" alt=""
               style="width:70px;height:44px;object-fit:contain;background:#F5F5F0;border-radius:4px;flex-shrink:0">
          <div style="flex:1;font-size:12px;color:#6B7280">
            <?= $ad['is_active'] ? '<span style="color:#16A34A">Active</span>' : '<span style="color:#9CA3AF">Hidden</span>' ?>
          </div>
          <form method="POST" action="<?= $r ?>/admin/company-ads/toggle/<?= $ad['id'] ?>" style="display:inline">
            <?= CSRF::field() ?>
            <button type="submit" class="btn btn-sm btn-outline-secondary" title="Toggle active">
              <i class="bi bi-toggle2-<?= $ad['is_active'] ? 'on' : 'off' ?>"></i>
            </button>
          </form>
          <form method="POST" action="<?= $r ?>/admin/company-ads/delete/<?= $ad['id'] ?>" style="display:inline"
                onsubmit="return confirm('Delete this banner?')">
            <?= CSRF::field() ?>
            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
              <i class="bi bi-trash"></i>
            </button>
          </form>
        </div>
        <?php endforeach; ?>
        <?php if (empty($bySlot[$slot['type']])): ?>
        <p style="font-size:12px;color:#9CA3AF">No banners uploaded yet.</p>
        <?php endif; ?>

        <form method="POST" action="<?= $r ?>/admin/company-ads/upload" enctype="multipart/form-data" class="mt-2">
          <?= CSRF::field() ?>
          <input type="hidden" name="slot_type" value="<?= $slot['type'] ?>">
          <div class="mb-2">
            <input type="file" name="banner" class="form-control form-control-sm" accept="image/*" required>
          </div>
          <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-upload me-1"></i>Add Banner</button>
        </form>

      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
