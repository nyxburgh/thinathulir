<?php use App\Core\{Helper, Auth, CSRF}; ?>
<div class="af-topbar">
  <a href="<?= $r . $adsBase ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
  <div class="af-topbar-title">New Advertisement</div>
  <button type="submit" form="adCreateForm" class="af-topbar-save"><i class="bi bi-check-lg"></i> <span class="d-none d-sm-inline">Save</span></button>
</div>

<form method="POST" action="<?= BASE_URL ?>/public<?= $adsBase === '/portal/ads' ? '/portal/ads/create' : '/admin/business-ads/store' ?>" id="adCreateForm">
<?= CSRF::field() ?>
<div class="row g-3" style="max-width:900px;margin:0 auto;padding:16px">

  <!-- SECTION: Business Info -->
  <div class="col-12"><div class="af-card-head">Business Information</div></div>
  <div class="col-12">
    <label class="af-label">Business Name <span class="af-req">*</span></label>
    <input type="text" name="business_name" class="af-input" required placeholder="Business / Shop name">
  </div>
  <div class="col-md-6">
    <label class="af-label">Contact Person <span class="af-req">*</span></label>
    <input type="text" name="contact_person" class="af-input" required placeholder="Owner / Manager name">
  </div>
  <div class="col-md-6">
    <label class="af-label">Mobile Number <span class="af-req">*</span></label>
    <input type="tel" name="contact_phone" class="af-input" required placeholder="+91 98765 43210">
  </div>
  <div class="col-md-6">
    <label class="af-label">Email</label>
    <input type="email" name="contact_email" class="af-input" placeholder="email@example.com">
  </div>
  <div class="col-md-6">
    <label class="af-label">Website</label>
    <input type="url" name="website_url" class="af-input" placeholder="https://...">
  </div>
  <div class="col-md-4">
    <label class="af-label">Facebook</label>
    <input type="url" name="facebook_url" class="af-input" placeholder="Facebook page URL">
  </div>
  <div class="col-md-4">
    <label class="af-label">Instagram</label>
    <input type="url" name="instagram_url" class="af-input" placeholder="Instagram URL">
  </div>
  <div class="col-md-4">
    <label class="af-label">YouTube</label>
    <input type="url" name="youtube_url" class="af-input" placeholder="YouTube channel URL">
  </div>
  <div class="col-12">
    <label class="af-label">Address</label>
    <textarea name="address" class="af-textarea" rows="2" placeholder="Shop / office address"></textarea>
  </div>
  <div class="col-12">
    <label class="af-label">Short Description</label>
    <textarea name="small_desc" class="af-textarea" rows="2" placeholder="Brief description of business…"></textarea>
  </div>

  <!-- SECTION: Package -->
  <div class="col-12"><div class="af-card-head mt-2">Package Selection</div></div>
  <div class="col-md-6">
    <label class="af-label">Package <span class="af-req">*</span></label>
    <select name="package_id" id="pkgSel" class="af-select" required>
      <option value="">— Select Package —</option>
      <?php foreach ($packages as $p): ?>
      <option value="<?= $p['id'] ?>"
              data-code="<?= Helper::e($p['code']??'') ?>"
              data-includes="<?= ($p['includes_square']?'SQ ':''). ($p['includes_horizontal']?'HR ':''). ($p['includes_vertical']?'VT':'') ?>"
              data-vt="<?= (int)($p['slot_type']==='vertical'||$p['includes_vertical']?1:0) ?>"
              data-price="<?= (float)$p['price_inr'] ?>">
        [<?= Helper::e($p['code']??'--') ?>] <?= Helper::e($p['name']) ?> — ₹<?= number_format($p['price_inr'],0) ?>
      </option>
      <?php endforeach; ?>
    </select>
    <div id="pkgIncludes" class="form-text" style="min-height:18px"></div>
  </div>
  <div class="col-md-6">
    <label class="af-label">Start Date</label>
    <input type="date" name="valid_from" id="validFrom" class="af-input" value="<?= date('Y-m-d') ?>">
  </div>
  <div id="vtDaysRow" class="col-md-6" style="display:none">
    <label class="af-label">Duration (days) <small class="text-muted">Min 30</small></label>
    <input type="number" name="custom_days" class="af-input" min="30" value="30" placeholder="30">
  </div>

  <!-- SECTION: Targeting -->
  <div class="col-12"><div class="af-card-head mt-2">Display Targeting <small class="text-muted fw-400">(optional)</small></div></div>
  <div class="col-md-6">
    <label class="af-label">District</label>
    <select name="district_id" class="af-select">
      <option value="">— Sitewide (All Districts) —</option>
      <?php foreach ($districts as $d): ?>
      <option value="<?= $d['id'] ?>"><?= Helper::e($d['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <!-- SECTION: Payment -->
  <div class="col-12"><div class="af-card-head mt-2">Payment Details</div></div>
  <div class="col-md-4">
    <label class="af-label">Amount Received (₹)</label>
    <input type="number" name="payment_amount" class="af-input" step="0.01" min="0" id="pkgAmount" placeholder="0.00">
  </div>
  <div class="col-md-4">
    <label class="af-label">Payment Reference No</label>
    <input type="text" name="payment_ref" class="af-input" placeholder="UPI / bank ref">
  </div>
  <div class="col-md-4">
    <label class="af-label">Notes</label>
    <input type="text" name="payment_note" class="af-input" placeholder="Any note">
  </div>
  <div class="col-12">
    <label class="af-label">Internal Notes</label>
    <textarea name="notes" class="af-textarea" rows="2" placeholder="Staff notes…"></textarea>
  </div>

  <div class="col-12 mt-2">
    <button class="btn btn-danger w-100" type="submit"><i class="bi bi-send me-2"></i>Save Advertisement</button>
    <div class="form-text text-center mt-1">Image upload is done separately after saving.</div>
  </div>
</div>
</form>

<script>
var pkgSel=document.getElementById('pkgSel');
var vtRow=document.getElementById('vtDaysRow');
var pkgInfo=document.getElementById('pkgIncludes');
var pkgAmt=document.getElementById('pkgAmount');
pkgSel.addEventListener('change',function(){
  var o=this.options[this.selectedIndex];
  if(!o.value){pkgInfo.textContent='';vtRow.style.display='none';return;}
  var inc=o.dataset.includes||''; pkgInfo.textContent='Includes: '+inc;
  vtRow.style.display=o.dataset.vt==='1'?'':'none';
  if(pkgAmt&&!pkgAmt.value) pkgAmt.value=o.dataset.price||'';
});
</script>
