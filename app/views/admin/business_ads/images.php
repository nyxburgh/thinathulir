<?php use App\Core\{Helper, CSRF, Auth};
$assetUrl = rtrim(ASSET_URL,'/').'/public';

// Determine which slot types this ad can use
$slots = [];
if ($pkg) {
    if ($pkg['includes_square']??0)     $slots[] = ['type'=>'square',     'label'=>'Square',     'size'=>'300×250'];
    if ($pkg['includes_horizontal']??0) $slots[] = ['type'=>'horizontal', 'label'=>'Horizontal', 'size'=>'728×90'];
    if ($pkg['includes_vertical']??0)   $slots[] = ['type'=>'vertical',   'label'=>'Vertical',   'size'=>'160×600'];
}
// Fallback for old single-slot packages
if (empty($slots) && !empty($pkg['slot_type']) && $pkg['slot_type'] !== 'any') {
    $slots[] = ['type'=>$pkg['slot_type'], 'label'=>ucfirst($pkg['slot_type']), 'size'=>''];
}
// Ultimate fallback
if (empty($slots)) {
    $slots = [['type'=>'square','label'=>'Square','size'=>'']];
}

// All images for this ad
$allImages = $ad['images'] ?? [];
?>
<div class="af-topbar">
  <a href="<?= $r . $adsBase ?>/show/<?= $ad['id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
  <div class="af-topbar-title">Images — <?= Helper::e($ad['business_name']) ?></div>
</div>

<div style="max-width:860px;margin:0 auto;padding:16px 16px 80px">

  <?php if (count($slots) > 1): ?>
  <!-- Image Library — all uploaded images with slot assignment -->
  <div class="tn-card mb-4">
    <div class="af-card-head">🖼 Image Library
      <span class="text-muted fw-400 small ms-2"><?= count($allImages) ?> images uploaded</span>
    </div>
    <div class="af-card-body">
      <?php if (!empty($allImages)): ?>
      <div class="row g-2 mb-3">
        <?php foreach ($allImages as $img): ?>
        <div class="col-6 col-md-3">
          <div style="border:1px solid #E5E7EB;border-radius:8px;overflow:hidden">
            <img src="<?= $assetUrl . Helper::e($img['filepath']??'') ?>" alt=""
                 style="width:100%;height:100px;object-fit:contain;background:#F5F5F0;display:block">
            <div style="padding:6px 8px;background:#fff">
              <form method="POST" action="<?= $r . $adsBase ?>/assign-image/<?= $img['id'] ?>">
                <?= CSRF::field() ?>
                <div class="d-flex gap-1 align-items-center">
                  <select name="display_type" class="form-select form-select-sm" style="font-size:11px">
                    <?php foreach ($slots as $s): ?>
                    <option value="<?= $s['type'] ?>" <?= ($img['display_type']??'')===$s['type']?'selected':'' ?>><?= $s['label'] ?></option>
                    <?php endforeach; ?>
                  </select>
                  <button class="btn btn-xs btn-outline-primary" title="Save">✓</button>
                </div>
              </form>
              <form method="POST" action="<?= $r . $adsBase ?>/delete-image/<?= $img['id'] ?>"
                    onsubmit="return confirm('Remove?')" style="margin-top:4px">
                <?= CSRF::field() ?>
                <button class="btn btn-xs btn-outline-danger w-100" style="font-size:10px">Remove</button>
              </form>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="form-text">Select slot type for each image and click ✓ to save.</div>
      <?php else: ?>
      <div class="text-muted small">No images uploaded yet.</div>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- Upload per slot type -->
  <?php foreach ($slots as $slot): ?>
  <?php
    $type   = $slot['type'];
    $imgs   = array_values(array_filter($allImages, fn($i) => ($i['display_type']??'square') === $type));
    $count  = count($imgs);
    $canAdd = $count < 5;
  ?>
  <div class="tn-card mb-4">
    <div class="af-card-head d-flex justify-content-between align-items-center">
      <span><?= $slot['label'] ?> <small class="text-muted"><?= $slot['size'] ?></small></span>
      <span class="badge bg-secondary"><?= $count ?>/5</span>
    </div>
    <div class="af-card-body">

      <!-- Assigned images for this slot -->
      <?php if (!empty($imgs)): ?>
      <div class="row g-2 mb-3">
        <?php foreach ($imgs as $img): ?>
        <div class="col-6 col-md-4 position-relative" style="aspect-ratio:3/2">
          <img src="<?= $assetUrl . Helper::e($img['filepath']??'') ?>"
               alt="" style="width:100%;height:100%;object-fit:contain;background:#F5F5F0;border-radius:6px;border:1px solid #E5E7EB">
          <form method="POST" action="<?= $r . $adsBase ?>/delete-image/<?= $img['id'] ?>"
                style="position:absolute;top:4px;right:4px" onsubmit="return confirm('Remove?')">
            <?= CSRF::field() ?>
            <button style="background:rgba(0,0,0,.55);color:#fff;border:none;border-radius:50%;width:22px;height:22px;font-size:12px;cursor:pointer">✕</button>
          </form>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- Upload -->
      <?php if ($canAdd): ?>
      <form class="img-upload-form" data-slot="<?= $type ?>" data-adid="<?= $ad['id'] ?>">
        <?= CSRF::field() ?>
        <div class="img-drop-zone" id="drop-<?= $type ?>"
             style="border:2px dashed #D1D5DB;border-radius:8px;padding:20px;text-align:center;cursor:pointer">
          <i class="bi bi-cloud-upload" style="font-size:24px;color:#9CA3AF"></i>
          <div style="margin-top:6px;font-size:13px;color:#6B7280">
            Drag image or <label for="file-<?= $type ?>" style="color:#C0001A;cursor:pointer;font-weight:600">Browse</label>
          </div>
          <input type="file" id="file-<?= $type ?>" name="image" class="img-file-input" accept="image/*" style="display:none">
          <div class="small text-muted mt-1">Max 2MB · JPG/PNG/WebP</div>
        </div>
        <div id="preview-<?= $type ?>" style="display:none;margin-top:10px;text-align:center">
          <img id="previewImg-<?= $type ?>" src="" alt="" style="max-height:140px;max-width:100%;border-radius:6px;border:1px solid #E5E7EB">
          <div class="mt-2 d-flex gap-2 justify-content-center">
            <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-upload me-1"></i>Upload</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="resetUpload('<?= $type ?>')">Cancel</button>
          </div>
        </div>
        <div id="progress-<?= $type ?>" style="display:none;margin-top:8px">
          <div class="progress" style="height:5px"><div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" style="width:100%"></div></div>
        </div>
        <div id="msg-<?= $type ?>" class="small mt-1 fw-600" style="min-height:16px"></div>
      </form>
      <?php else: ?>
      <div class="alert alert-secondary py-2 small mb-0">Max 5 images. Remove one to replace.</div>
      <?php endif; ?>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<script>
(function(){
  var UPLOAD_URL = '<?= $r . $adsBase ?>/upload-image/<?= $ad['id'] ?>';
  var CSRF_TOKEN = '<?= CSRF::token() ?>';

  function resetUpload(type){
    document.getElementById('preview-'+type).style.display='none';
    document.getElementById('drop-'+type).style.display='block';
    var fi=document.getElementById('file-'+type);if(fi)fi.value='';
  }
  window.resetUpload = resetUpload;

  document.querySelectorAll('.img-upload-form').forEach(function(form){
    var type=form.dataset.slot;
    var fi=document.getElementById('file-'+type);
    var dz=document.getElementById('drop-'+type);
    var pv=document.getElementById('preview-'+type);
    var pi=document.getElementById('previewImg-'+type);
    var pg=document.getElementById('progress-'+type);
    var mg=document.getElementById('msg-'+type);

    function showPreview(file){
      var r=new FileReader();r.onload=function(e){pi.src=e.target.result;};r.readAsDataURL(file);
      dz.style.display='none';pv.style.display='block';
    }
    if(fi) fi.addEventListener('change',function(){if(this.files[0])showPreview(this.files[0]);});
    dz.addEventListener('dragover',function(e){e.preventDefault();this.style.borderColor='#C0001A';});
    dz.addEventListener('dragleave',function(){this.style.borderColor='#D1D5DB';});
    dz.addEventListener('drop',function(e){e.preventDefault();this.style.borderColor='#D1D5DB';var f=e.dataTransfer.files[0];if(f&&fi){fi.files=e.dataTransfer.files;showPreview(f);}});

    form.addEventListener('submit',function(e){
      e.preventDefault();
      if(!fi||!fi.files[0]){mg.textContent='Select an image first.';return;}
      var fd=new FormData();
      fd.append('image',fi.files[0]);
      fd.append('slot_type',type);
      fd.append('_token',CSRF_TOKEN);
      pv.style.display='none';pg.style.display='block';
      fetch(UPLOAD_URL,{method:'POST',body:fd})
        .then(function(r){return r.json();})
        .then(function(d){
          pg.style.display='none';
          if(d.success){mg.style.color='#15803D';mg.textContent='Uploaded!';setTimeout(function(){window.location.reload();},600);}
          else{mg.style.color='#C0001A';mg.textContent=d.error||'Failed.';resetUpload(type);}
        }).catch(function(){pg.style.display='none';mg.style.color='#C0001A';mg.textContent='Network error.';resetUpload(type);});
    });
  });
}());
</script>
