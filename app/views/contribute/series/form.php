<?php use App\Core\{Helper, CSRF}; ?>
<div class="portal-page-header">
  <div>
    <h2 class="portal-page-title"><?= $isEdit ? 'Edit Series' : 'New Series' ?></h2>
    <p style="font-size:13px;color:var(--portal-muted);margin:2px 0 0">A series groups multiple articles as parts of one ongoing story</p>
  </div>
  <a href="<?= $r ?>/contribute/series" class="portal-back-btn"><i class="bi bi-arrow-left"></i> Back to Series</a>
</div>

<form method="POST" action="<?= $r ?>/contribute/series/<?= $isEdit ? 'edit/'.$seriesItem['id'] : 'create' ?>">
  <?= CSRF::field() ?>
  <div class="row g-4">

    <div class="col-xl-8">
      <div class="portal-card mb-4">
        <div class="portal-card-body">
          <div class="mb-3">
            <label class="form-label fw-600">Series Title <span class="text-danger">*</span></label>
            <input type="text" name="title" id="titleInput" class="form-control form-control-lg"
                   placeholder="e.g. My Village Diary, Election Watch 2026…"
                   value="<?= Helper::e($seriesItem['title'] ?? '') ?>" required>
          </div>
          <div>
            <label class="form-label fw-600">URL Slug</label>
            <div class="input-group">
              <span class="input-group-text text-muted">/series/</span>
              <input type="text" name="slug" id="slugInput" class="form-control"
                     value="<?= Helper::e($seriesItem['slug'] ?? '') ?>" placeholder="auto-generated">
            </div>
          </div>
        </div>
      </div>

      <div class="portal-card">
        <div class="portal-card-header"><span><i class="bi bi-text-paragraph me-2"></i>Description</span></div>
        <div class="portal-card-body">
          <textarea name="description" class="form-control" rows="5"
                    placeholder="What is this series about?"><?= Helper::e($seriesItem['description'] ?? '') ?></textarea>
        </div>
      </div>
    </div>

    <div class="col-xl-4">
      <div class="portal-card mb-4">
        <div class="portal-card-header"><span><i class="bi bi-send me-2"></i>Save</span></div>
        <div class="portal-card-body">
          <div class="mb-3">
            <label class="form-label fw-600">Status</label>
            <select name="status" class="form-select">
              <?php $curStatus = $seriesItem['status'] ?? 'ongoing'; ?>
              <option value="ongoing"   <?= $curStatus === 'ongoing'   ? 'selected' : '' ?>>Ongoing</option>
              <option value="completed" <?= $curStatus === 'completed' ? 'selected' : '' ?>>Completed</option>
            </select>
          </div>
          <div class="d-grid gap-2">
            <button type="submit" class="btn fw-600" style="background:#10b981;color:white">
              <?= $isEdit ? 'Update Series' : 'Create Series' ?>
            </button>
            <a href="<?= $r ?>/contribute/series" class="btn btn-outline-secondary">Cancel</a>
          </div>
        </div>
      </div>

      <div class="portal-card">
        <div class="portal-card-header"><span><i class="bi bi-folder me-2"></i>Category <span class="text-danger">*</span></span></div>
        <div class="portal-card-body">
          <?php if (empty($categories)): ?>
          <p class="text-muted small mb-0">No categories assigned. Contact admin to assign categories.</p>
          <?php else: ?>
          <?php
          $cParents = []; $cChildMap = [];
          foreach ($categories as $cat) {
            if (!$cat['parent_id']) $cParents[] = $cat;
            else $cChildMap[$cat['parent_id']][] = $cat;
          }
          $curCatId = (int)($seriesItem['category_id'] ?? 0);
          $curParent = 0; $curChild = 0;
          foreach ($categories as $cat) {
            if ($cat['id'] === $curCatId) {
              if ($cat['parent_id']) { $curParent = (int)$cat['parent_id']; $curChild = $curCatId; }
              else { $curParent = $curCatId; }
            }
          }
          ?>
          <select id="cParentSel" class="form-select mb-2">
            <option value="">-- Category தேர்வு செய்யுங்கள் --</option>
            <?php foreach ($cParents as $cat): ?>
            <option value="<?= $cat['id'] ?>"
                    data-children="<?= htmlspecialchars(json_encode($cChildMap[$cat['id']] ?? [])) ?>"
                    <?= $curParent === (int)$cat['id'] ? 'selected' : '' ?>>
              <?= Helper::e($cat['name_tamil'] ?: $cat['name']) ?>
            </option>
            <?php endforeach; ?>
          </select>
          <div id="cSubcatWrap" style="<?= empty($cChildMap[$curParent]) ? 'display:none' : '' ?>">
            <select id="cSubSel" class="form-select">
              <option value="<?= $curParent ?>">-- அனைத்தும் (subcat இல்லை) --</option>
              <?php foreach ($cChildMap[$curParent] ?? [] as $sub): ?>
              <option value="<?= $sub['id'] ?>" <?= $curChild === (int)$sub['id'] ? 'selected' : '' ?>>
                <?= Helper::e($sub['name_tamil'] ?: $sub['name']) ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>
          <input type="hidden" name="category_id" id="cCatFallback" value="<?= $curCatId ?: $curParent ?>">
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</form>

<script src="<?= ASSET_URL ?>/assets/js/contribute-article-form.js"></script>
