<?php use App\Core\{Helper, CSRF};
$old    = $old    ?? [];
$errors = $errors ?? [];
$v = fn($k, $d = '') => Helper::e($old[$k] ?? $d);
$isAdminAds = $adsBase === '/admin/business-ads';
$editAction = $isAdminAds ? BASE_URL.'/public/admin/business-ads/update/'.$ad['id'] : BASE_URL.'/public/portal/ads/edit/'.$ad['id'];
?>
<div class="af-topbar">
  <a href="<?= $r . $adsBase ?>/show/<?= $ad['id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
  <div class="af-topbar-title">Edit — <?= Helper::e($ad['business_name']) ?></div>
  <button type="submit" form="adEditForm" class="af-topbar-save"><i class="bi bi-check-lg"></i> <span class="d-none d-sm-inline">Update</span></button>
</div>

<form method="POST" action="<?= $editAction ?>" id="adEditForm" data-validate novalidate>
<?= CSRF::field() ?>
<div class="row g-3" style="max-width:900px;margin:0 auto;padding:16px">

  <div class="col-12"><div class="af-card-head">Business Information</div></div>
  <div class="col-12 fv-field">
    <label class="af-label">Business Name <span class="af-req">*</span></label>
    <input type="text" name="business_name" class="af-input <?= !empty($errors['business_name']) ? 'is-invalid' : '' ?>" required value="<?= $v('business_name', $ad['business_name']) ?>">
    <?php if (!empty($errors['business_name'])): ?><div class="fv-error"><?= Helper::e($errors['business_name']) ?></div><?php endif; ?>
  </div>
  <div class="col-md-6">
    <label class="af-label">Contact Person</label>
    <input type="text" name="contact_person" class="af-input" value="<?= $v('contact_person', $ad['contact_person']??'') ?>">
  </div>
  <div class="col-md-6 fv-field">
    <label class="af-label">Mobile Number</label>
    <input type="tel" name="contact_phone" class="af-input <?= !empty($errors['contact_phone']) ? 'is-invalid' : '' ?>"
           value="<?= $v('contact_phone', $ad['contact_phone']??'') ?>"
           data-check-duplicate="contact_phone"
           data-check-url="<?= BASE_URL ?>/public<?= $adsBase ?>/check-field"
           data-exclude-id="<?= (int)$ad['id'] ?>">
    <?php if (!empty($errors['contact_phone'])): ?><div class="fv-error"><?= Helper::e($errors['contact_phone']) ?></div><?php endif; ?>
  </div>
  <div class="col-md-6 fv-field">
    <label class="af-label">Email</label>
    <input type="email" name="contact_email" class="af-input <?= !empty($errors['contact_email']) ? 'is-invalid' : '' ?>"
           value="<?= $v('contact_email', $ad['contact_email']??'') ?>"
           data-check-duplicate="contact_email"
           data-check-url="<?= BASE_URL ?>/public<?= $adsBase ?>/check-field"
           data-exclude-id="<?= (int)$ad['id'] ?>">
    <?php if (!empty($errors['contact_email'])): ?><div class="fv-error"><?= Helper::e($errors['contact_email']) ?></div><?php endif; ?>
  </div>
  <div class="col-md-6">
    <label class="af-label">Website</label>
    <input type="url" name="website_url" class="af-input" value="<?= $v('website_url', $ad['website_url']??'') ?>">
  </div>
  <div class="col-md-4">
    <label class="af-label">Facebook</label>
    <input type="url" name="facebook_url" class="af-input" value="<?= $v('facebook_url', $ad['facebook_url']??'') ?>">
  </div>
  <div class="col-md-4">
    <label class="af-label">Instagram</label>
    <input type="url" name="instagram_url" class="af-input" value="<?= $v('instagram_url', $ad['instagram_url']??'') ?>">
  </div>
  <div class="col-md-4">
    <label class="af-label">YouTube</label>
    <input type="url" name="youtube_url" class="af-input" value="<?= $v('youtube_url', $ad['youtube_url']??'') ?>">
  </div>
  <div class="col-12">
    <label class="af-label">Address</label>
    <textarea name="address" class="af-textarea" rows="2"><?= $v('address', $ad['address']??'') ?></textarea>
  </div>
  <div class="col-12">
    <label class="af-label">Short Description</label>
    <textarea name="small_desc" class="af-textarea" rows="2"><?= $v('small_desc', $ad['small_desc']??'') ?></textarea>
  </div>

  <div class="col-12"><div class="af-card-head mt-2">Package & Dates</div></div>
  <div class="col-md-6">
    <label class="af-label">Package</label>
    <select name="package_id" class="af-select">
      <option value="">— None —</option>
      <?php foreach ($packages as $p): ?>
      <option value="<?= $p['id'] ?>" <?= ($old['package_id'] ?? $ad['package_id'] ?? '')==$p['id']?'selected':'' ?>>[<?= Helper::e($p['code']??'') ?>] <?= Helper::e($p['name']) ?> — ₹<?= number_format($p['price_inr'],0) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-3">
    <label class="af-label">Valid From</label>
    <input type="date" name="valid_from" class="af-input" value="<?= $v('valid_from', $ad['valid_from']) ?>">
  </div>
  <div class="col-md-3">
    <label class="af-label">Valid Until</label>
    <input type="date" name="valid_until" class="af-input" value="<?= $v('valid_until', $ad['valid_until']) ?>">
  </div>

  <div class="col-12"><div class="af-card-head mt-2">Payment & Status</div></div>
  <div class="col-md-3">
    <label class="af-label">Amount (₹)</label>
    <input type="number" name="payment_amount" class="af-input" step="0.01" value="<?= $v('payment_amount', $ad['payment_amount']??'') ?>">
  </div>
  <div class="col-md-3">
    <label class="af-label">Payment Ref</label>
    <input type="text" name="payment_ref" class="af-input" value="<?= $v('payment_ref', $ad['payment_ref']??'') ?>">
  </div>
  <div class="col-md-3">
    <label class="af-label">Payment Status</label>
    <select name="payment_status" class="af-select">
      <?php foreach (['pending','confirmed','rejected'] as $ps): ?>
      <option value="<?= $ps ?>" <?= ($old['payment_status'] ?? $ad['payment_status'])===$ps?'selected':'' ?>><?= ucfirst($ps) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-3">
    <label class="af-label">Ad Status</label>
    <select name="status" class="af-select">
      <?php foreach (['pending','active','paused','expired','rejected'] as $ss): ?>
      <option value="<?= $ss ?>" <?= ($old['status'] ?? $ad['status'])===$ss?'selected':'' ?>><?= ucfirst($ss) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-12">
    <label class="af-label">Notes</label>
    <textarea name="notes" class="af-textarea" rows="2"><?= $v('notes', $ad['notes']??'') ?></textarea>
  </div>

  <div class="col-12 mt-2">
    <button class="btn btn-danger w-100" type="submit">Update Advertisement</button>
  </div>
</div>
</form>
<script src="<?= ASSET_URL ?>/public/assets/js/form-validate.js"></script>
