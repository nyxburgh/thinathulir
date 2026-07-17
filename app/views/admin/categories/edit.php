<?php use App\Core\{Helper, CSRF}; ?>
<div class="tn-page-header">
  <div><h2 class="tn-page-title">Edit Category</h2></div>
  <a href="<?= $r ?>/admin/categories" class="btn btn-outline-secondary btn-sm">← Back</a>
</div>
<div class="tn-card" style="max-width:560px">
  <div class="tn-card-body">
    <form method="POST" action="<?= $r ?>/admin/categories/edit/<?= $category['id'] ?>">
      <?= CSRF::field() ?>
      <div class="mb-3">
        <label class="form-label fw-600">Name (English)</label>
        <input type="text" name="name" class="form-control" required value="<?= Helper::e($category['name']) ?>">
      </div>
      <div class="mb-3">
        <label class="form-label fw-600">Name (Tamil)</label>
        <input type="text" name="name_tamil" class="form-control" value="<?= Helper::e($category['name_tamil'] ?? '') ?>">
      </div>
      <div class="mb-3">
        <label class="form-label fw-600">Slug</label>
        <input type="text" name="slug" class="form-control" value="<?= Helper::e($category['slug']) ?>">
      </div>
      <div class="mb-3">
        <label class="form-label fw-600">Parent Category</label>
        <select name="parent_id" class="form-select">
          <option value="">— Top Level —</option>
          <?php foreach ($categories as $cat): ?>
          <?php if ($cat['id'] == $category['id']) continue; ?>
          <option value="<?= $cat['id'] ?>" <?= ($category['parent_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
            <?= Helper::e($cat['name_tamil'] ?: $cat['name']) ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label fw-600">Description</label>
        <textarea name="description" class="form-control" rows="3"><?= Helper::e($category['description'] ?? '') ?></textarea>
      </div>
      <div class="mb-3">
        <label class="form-check-label">
          <input type="checkbox" name="is_active" value="1" class="form-check-input me-1"
                 <?= ($category['is_active'] ?? 1) ? 'checked' : '' ?>> Active
        </label>
      </div>
      <button class="btn btn-primary">Update Category</button>
    </form>
  </div>
</div>
