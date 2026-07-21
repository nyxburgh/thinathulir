<?php use App\Core\{Helper, CSRF, Auth}; ?>
<?php
  $roleIdBySlug     = array_column($roles, 'id', 'slug');
  $reporterRoleId   = $roleIdBySlug['reporter'] ?? 7;
  $editorialRoleIds = array_values(array_filter(array_map(
      fn($slug) => $roleIdBySlug[$slug] ?? null,
      Auth::EDITORIAL_ROLES
  )));
  $overridesBySlug = array_column($permOverrides ?? [], 'effect', 'permission_slug');
  $teamUrl = $isEdit ? rtrim($r, '/') . '/our-team/' . $user['id'] : '';
?>
<div class="tn-page-header">
  <h2 class="tn-page-title"><?= $isEdit ? 'Edit User' : 'Add User' ?></h2>
  <a href="<?= $r ?>/admin/users" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Back</a>
</div>

<div class="row g-4">
  <!-- BASIC INFO -->
  <div class="col-lg-7">
    <div class="tn-card mb-4">
      <div class="tn-card-header"><span><i class="bi bi-person me-2"></i>Basic Info</span></div>
      <div class="tn-card-body">
        <form action="<?= $r ?>/admin/users/<?= $isEdit ? 'edit/'.$user['id'] : 'create' ?>" method="POST">
          <?= CSRF::field() ?>
          <input type="hidden" name="_basic_info" value="1">
          <div class="row g-3">
            <div class="col-sm-6">
              <label class="form-label fw-600">Full Name *</label>
              <input type="text" name="name" class="form-control" required
                     value="<?= Helper::e($user['name'] ?? '') ?>">
            </div>
            <div class="col-sm-6">
              <label class="form-label fw-600">Email *</label>
              <input type="email" name="email" class="form-control" required
                     value="<?= Helper::e($user['email'] ?? '') ?>">
            </div>
            <div class="col-sm-6">
              <label class="form-label fw-600">Password <?= $isEdit ? '(leave blank to keep)' : '*' ?></label>
              <input type="password" name="password" class="form-control" <?= $isEdit ? '' : 'required' ?>
                     placeholder="Min. 8 characters" minlength="8">
            </div>
            <div class="col-sm-6">
              <label class="form-label fw-600">Role *</label>
              <select name="role_id" class="form-select" id="roleSelect" onchange="handleRoleChange()">
                <?php foreach ($roles as $role): ?>
                <option value="<?= $role['id'] ?>"
                  <?= ($user['role_id'] ?? 3) == $role['id'] ? 'selected' : '' ?>>
                  <?= Helper::e($role['name']) ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-sm-6">
              <label class="form-label fw-600">Assigned District</label>
              <select name="assigned_district_id" class="form-select">
                <option value="">None</option>
                <?php foreach ($districts as $d): ?>
                <option value="<?= $d['id'] ?>"
                  <?= ($user['assigned_district_id'] ?? '') == $d['id'] ? 'selected' : '' ?>>
                  <?= Helper::e($d['name']) ?>
                </option>
                <?php endforeach; ?>
              </select>
              <small class="text-muted">Reporter's area — auto-tags their articles</small>
            </div>
            <div class="col-sm-6">
              <label class="form-label fw-600">Status</label>
              <select name="is_active" class="form-select">
                <option value="1" <?= ($user['is_active'] ?? 1) ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= !($user['is_active'] ?? 1) ? 'selected' : '' ?>>Inactive</option>
              </select>
            </div>
            <div class="col-sm-6">
              <label class="form-label fw-600">Phone</label>
              <input type="text" name="phone" class="form-control" value="<?= Helper::e($user['phone'] ?? '') ?>">
            </div>
            <div class="col-sm-6">
              <label class="form-label fw-600">Designation</label>
              <input type="text" name="designation" class="form-control" placeholder="e.g. Staff Reporter"
                     value="<?= Helper::e($user['designation'] ?? '') ?>">
            </div>
            <div class="col-sm-6">
              <label class="form-label fw-600">ID No.</label>
              <input type="text" name="id_no" class="form-control" placeholder="Employee/Press ID No."
                     value="<?= Helper::e($user['id_no'] ?? '') ?>">
            </div>
            <div class="col-sm-6">
              <label class="form-label fw-600">Date of Birth</label>
              <input type="date" name="dob" class="form-control" value="<?= Helper::e($user['dob'] ?? '') ?>">
            </div>
          </div>
          <hr class="my-4">
          <!-- AUTO APPROVE — editorial staff only, set by chief editor/admin -->
          <?php if (\App\Core\Auth::can('set_auto_approve')): ?>
          <div class="form-check form-switch mb-3" id="autoApproveRow" style="<?= in_array($user['role_id'] ?? 3, $editorialRoleIds) ? '' : 'display:none' ?>">
            <input class="form-check-input" type="checkbox" name="auto_approve" value="1"
                   id="autoApprove" <?= !empty($user['auto_approve']) ? 'checked' : '' ?>>
            <label class="form-check-label" for="autoApprove">
              <strong>⚡ Auto Approve</strong>
              <span class="text-muted small ms-2">— Articles publish instantly, chief editor gets notification (works for reporters and editors alike — use it to block an editor's own auto-approve too)</span>
            </label>
          </div>
          <?php endif; ?>

          <!-- ASSIGN TO DISTRICT EDITOR — reporters only, set by chief editor/admin -->
          <?php if (\App\Core\Auth::can('assign_reporters') && !empty($districtEditors)): ?>
          <div class="mb-3" id="assignEditorRow" style="<?= in_array($user['role_id'] ?? 3, [$reporterRoleId]) ? '' : 'display:none' ?>">
            <label class="form-label fw-600">
              Assign to District Editor
              <span class="text-muted small ms-2">— This reporter's articles go to this editor for approval</span>
            </label>
            <select name="assigned_district_editor_id" class="form-select">
              <option value="">-- Auto-assign (by district/fallback to editor) --</option>
              <?php foreach ($districtEditors as $de): ?>
              <option value="<?= $de['id'] ?>" <?= ($user['assigned_district_editor_id'] ?? 0) == $de['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($de['name']) ?> (<?= htmlspecialchars($de['role_name']) ?>)
              </option>
              <?php endforeach; ?>
            </select>
          </div>
          <?php endif; ?>
          <button type="submit" class="btn btn-primary fw-600 px-4">
            <i class="bi bi-save me-2"></i><?= $isEdit ? 'Update User' : 'Create User' ?>
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- EDITOR PERMISSIONS -->
  <div class="col-lg-5" id="permSection" style="<?= in_array($user['role_id'] ?? 3, [2,4,5,6]) ? '' : 'display:none' ?>">
    <div class="tn-card mb-4">
      <div class="tn-card-header">
        <span><i class="bi bi-shield-check me-2"></i>Approval Permissions</span>
        <span class="badge bg-info">Editors Only</span>
      </div>
      <div class="tn-card-body">
        <?php if (!empty($perms)): ?>
        <div class="mb-3">
          <label class="form-label fw-600 small text-muted">CURRENT PERMISSIONS</label>
          <?php foreach ($perms as $p): ?>
          <div class="d-flex align-items-center gap-2 mb-2 p-2" style="background:var(--bs-dark-bg-subtle,#2d3748);border-radius:6px">
            <span class="badge <?= $p['perm_type']==='district' ? 'bg-primary' : 'bg-success' ?>">
              <?= ucfirst($p['perm_type']) ?>
            </span>
            <span class="small flex-grow-1">
              <?= Helper::e($p['district_name'] ?? $p['category_name'] ?? 'Unknown') ?>
            </span>
            <?php if ($p['can_publish']): ?>
            <span class="badge bg-warning text-dark">Can Publish</span>
            <?php endif; ?>
            <form action="<?= $r ?>/admin/users/perm-remove/<?= $user['id'] ?>" method="POST" class="d-inline">
              <?= CSRF::field() ?>
              <input type="hidden" name="perm_id" value="<?= $p['id'] ?>">
              <button class="btn btn-xs btn-outline-danger"><i class="bi bi-x"></i></button>
            </form>
          </div>
          <?php endforeach; ?>
        </div>
        <hr>
        <?php endif; ?>

        <?php if ($isEdit): ?>
        <form action="<?= $r ?>/admin/users/edit/<?= $user['id'] ?>" method="POST">
          <?= CSRF::field() ?>
          <!-- hidden fields to re-submit basic info (workaround for two forms) -->
          <input type="hidden" name="role_id" value="<?= $user['role_id'] ?>">
          <input type="hidden" name="name" value="<?= Helper::e($user['name']) ?>">
          <input type="hidden" name="email" value="<?= Helper::e($user['email']) ?>">
          <input type="hidden" name="is_active" value="<?= $user['is_active'] ?>">
          <input type="hidden" name="assigned_district_id" value="<?= $user['assigned_district_id'] ?? '' ?>">
        <?php endif; ?>

          <label class="form-label fw-600">Add District Permissions</label>
          <div style="max-height:180px;overflow-y:auto;border:1px solid var(--bs-border-color);border-radius:6px;padding:10px" class="mb-3">
            <?php foreach ($districts as $d): ?>
            <?php $checked = in_array($d['id'], array_column(array_filter($perms, fn($p) => $p['perm_type']==='district'), 'ref_id')); ?>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="perm_districts[]"
                     value="<?= $d['id'] ?>" id="d<?= $d['id'] ?>" <?= $checked ? 'checked' : '' ?>>
              <label class="form-check-label small" for="d<?= $d['id'] ?>">
                📍 <?= Helper::e($d['name']) ?>
              </label>
            </div>
            <?php endforeach; ?>
          </div>

          <label class="form-label fw-600">Add Category Permissions</label>
          <div style="max-height:180px;overflow-y:auto;border:1px solid var(--bs-border-color);border-radius:6px;padding:10px" class="mb-3">
            <?php foreach ($categories as $c): ?>
            <?php $checked = in_array($c['id'], array_column(array_filter($perms, fn($p) => $p['perm_type']==='category'), 'ref_id')); ?>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="perm_categories[]"
                     value="<?= $c['id'] ?>" id="c<?= $c['id'] ?>" <?= $checked ? 'checked' : '' ?>>
              <label class="form-check-label small" for="c<?= $c['id'] ?>">
                📂 <?= Helper::e($c['name_tamil'] ?: $c['name']) ?>
              </label>
            </div>
            <?php endforeach; ?>
          </div>

          <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" name="can_publish" value="1" id="canPublish">
            <label class="form-check-label" for="canPublish">
              <strong>Can Publish</strong> <small class="text-muted">(no chief editor approval needed)</small>
            </label>
          </div>

          <?php if ($isEdit): ?>
          <button type="submit" class="btn btn-info fw-600 w-100">
            <i class="bi bi-shield-check me-2"></i>Save Permissions
          </button>
        </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php if ($isEdit): ?>
<div class="row g-4 mt-0">
  <!-- TEAM / ID CARD -->
  <div class="col-lg-6">
    <div class="tn-card mb-4">
      <div class="tn-card-header"><span><i class="bi bi-person-vcard me-2"></i>Team / ID Card</span></div>
      <div class="tn-card-body">
        <div class="d-flex align-items-center gap-3 mb-3">
          <?php if (!empty($user['avatar'])): ?>
          <img src="<?= $r ?><?= Helper::e($user['avatar']) ?>" alt="" style="width:80px;height:80px;object-fit:cover;border-radius:8px">
          <?php else: ?>
          <div style="width:80px;height:80px;border-radius:8px;background:var(--bs-dark-bg-subtle,#2d3748);display:flex;align-items:center;justify-content:center">
            <i class="bi bi-person fs-2 text-muted"></i>
          </div>
          <?php endif; ?>
          <form action="<?= $r ?>/admin/users/photo/<?= $user['id'] ?>" method="POST" enctype="multipart/form-data" class="flex-grow-1">
            <?= CSRF::field() ?>
            <input type="file" name="photo" class="form-control form-control-sm mb-2" accept="image/*" required>
            <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-upload me-1"></i>Upload Photo</button>
          </form>
        </div>

        <p class="small text-muted mb-2">
          Public verification page — shown on the "Our Team" page when this user is active and not blocked.
          Encode this URL as a QR code and print it on their physical ID card:
        </p>
        <div class="input-group mb-2">
          <input type="text" class="form-control form-control-sm" id="teamUrlInput" value="<?= Helper::e($teamUrl) ?>" readonly>
          <a href="<?= Helper::e($teamUrl) ?>" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-box-arrow-up-right"></i></a>
        </div>
        <div id="qrHolder" class="my-2"></div>
        <button type="button" class="btn btn-sm btn-outline-info" id="qrDownloadBtn"><i class="bi bi-qr-code me-1"></i>Download QR</button>
      </div>
    </div>
  </div>

  <!-- CUSTOM PERMISSIONS -->
  <div class="col-lg-6">
    <div class="tn-card mb-4">
      <div class="tn-card-header">
        <span><i class="bi bi-sliders me-2"></i>Custom Permissions</span>
        <span class="badge bg-warning text-dark">Overrides role</span>
      </div>
      <div class="tn-card-body">
        <p class="small text-muted">Grant a permission this user's role doesn't normally have, or revoke one it does — without changing their role.</p>
        <form action="<?= $r ?>/admin/users/permissions/<?= $user['id'] ?>" method="POST">
          <?= CSRF::field() ?>
          <div style="max-height:360px;overflow-y:auto">
            <?php foreach (Auth::PERMISSION_CATALOG as $group => $slugs): ?>
            <div class="fw-600 small text-muted mt-2 mb-1"><?= Helper::e($group) ?></div>
            <?php foreach ($slugs as $slug => $label): $effect = $overridesBySlug[$slug] ?? 'default'; ?>
            <div class="d-flex align-items-center justify-content-between gap-2 py-1 border-bottom">
              <span class="small"><?= Helper::e($label) ?></span>
              <div class="btn-group btn-group-sm" role="group">
                <input type="radio" class="btn-check" name="perm_<?= $slug ?>" id="p_<?= $slug ?>_d" value="default" <?= $effect==='default'?'checked':'' ?> onchange="syncPerm('<?= $slug ?>')">
                <label class="btn btn-outline-secondary" for="p_<?= $slug ?>_d">Default</label>

                <input type="radio" class="btn-check" name="perm_<?= $slug ?>" id="p_<?= $slug ?>_g" value="grant" <?= $effect==='grant'?'checked':'' ?> onchange="syncPerm('<?= $slug ?>')">
                <label class="btn btn-outline-success" for="p_<?= $slug ?>_g">Grant</label>

                <input type="radio" class="btn-check" name="perm_<?= $slug ?>" id="p_<?= $slug ?>_r" value="revoke" <?= $effect==='revoke'?'checked':'' ?> onchange="syncPerm('<?= $slug ?>')">
                <label class="btn btn-outline-danger" for="p_<?= $slug ?>_r">Revoke</label>
              </div>
            </div>
            <?php endforeach; ?>
            <?php endforeach; ?>
          </div>
          <div id="permHidden"></div>
          <button type="submit" class="btn btn-warning fw-600 w-100 mt-3">
            <i class="bi bi-sliders me-2"></i>Save Custom Permissions
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
QRCode.toCanvas(document.createElement('canvas'), document.getElementById('teamUrlInput').value, function () {});
(function () {
  const url = document.getElementById('teamUrlInput').value;
  const holder = document.getElementById('qrHolder');
  const canvas = document.createElement('canvas');
  holder.appendChild(canvas);
  QRCode.toCanvas(canvas, url, { width: 160 }, function (err) {
    if (err) { holder.innerHTML = '<span class="text-danger small">QR generation failed</span>'; return; }
    document.getElementById('qrDownloadBtn').addEventListener('click', function () {
      const a = document.createElement('a');
      a.download = 'team-qr-<?= $user['id'] ?>.png';
      a.href = canvas.toDataURL('image/png');
      a.click();
    });
  });
})();

// Custom Permissions form only needs to actually post the non-default
// choices as perm_grant[]/perm_revoke[] — build those from the radios on submit.
document.querySelectorAll('form[action*="/admin/users/permissions/"]').forEach(function (form) {
  form.addEventListener('submit', function () {
    const hidden = document.getElementById('permHidden');
    hidden.innerHTML = '';
    form.querySelectorAll('input[type=radio]:checked').forEach(function (radio) {
      if (radio.value === 'default') return;
      const slug = radio.name.replace('perm_', '');
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = (radio.value === 'grant' ? 'perm_grant[]' : 'perm_revoke[]');
      input.value = slug;
      hidden.appendChild(input);
    });
  });
});
</script>
<?php endif; ?>

<script>
const EDITORIAL_ROLE_IDS = <?= json_encode($editorialRoleIds) ?>;
function handleRoleChange() {
  const role    = parseInt(document.getElementById('roleSelect').value);
  const section = document.getElementById('permSection');
  // Show permissions for editor roles (2=chief_editor, 4=district_editor, 5=category_editor, 6=senior_reporter)
  section.style.display = [2,4,5,6].includes(role) ? '' : 'none';

  const autoApproveRow = document.getElementById('autoApproveRow');
  if (autoApproveRow) autoApproveRow.style.display = EDITORIAL_ROLE_IDS.includes(role) ? '' : 'none';
}
</script>
