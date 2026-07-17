<?php use App\Core\{Helper, CSRF}; ?>

<div class="tn-page-header">
  <div>
    <h2 class="tn-page-title">📦 Sidebar Widgets</h2>
    <p class="tn-page-sub">Drag to reorder. Toggle to show/hide.</p>
  </div>
  <button class="btn btn-primary" onclick="document.getElementById('addWidgetModal').style.display='flex'">
    <i class="bi bi-plus-circle me-1"></i> Add Widget
  </button>
</div>

<div class="tn-card">
  <div class="tn-card-body p-0">
    <table class="tn-table">
      <thead><tr>
        <th style="width:30px"></th>
        <th>Widget</th>
        <th>Type</th>
        <th>Position</th>
        <th>Desktop</th>
        <th>Mobile</th>
        <th>Status</th>
        <th>Actions</th>
      </tr></thead>
      <tbody id="widgetList">
      <?php foreach ($widgets as $w): ?>
      <tr data-id="<?= $w['id'] ?>">
        <td style="cursor:grab;color:#9A9890">⠿</td>
        <td>
          <div style="font-weight:600"><?= Helper::e($w['name']) ?></div>
          <?php if ($w['title']): ?><div style="font-size:11px;color:#9A9890"><?= Helper::e($w['title']) ?></div><?php endif; ?>
        </td>
        <td><span class="badge bg-secondary"><?= $w['type'] ?></span></td>
        <td><?= $w['position'] ?></td>
        <td><?= $w['show_desktop'] ? '✓' : '—' ?></td>
        <td><?= $w['show_mobile']  ? '✓' : '—' ?></td>
        <td>
          <span class="badge <?= $w['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
            <?= $w['is_active'] ? 'Active' : 'Hidden' ?>
          </span>
        </td>
        <td>
          <div class="d-flex gap-1">
            <button class="btn btn-sm btn-outline-primary" onclick="editWidget(<?= htmlspecialchars(json_encode($w)) ?>)">Edit</button>
            <form method="POST" action="<?= $r ?>/admin/widgets/toggle/<?= $w['id'] ?>">
              <?= CSRF::field() ?>
              <button class="btn btn-sm <?= $w['is_active'] ? 'btn-outline-secondary' : 'btn-outline-success' ?>">
                <?= $w['is_active'] ? 'Hide' : 'Show' ?>
              </button>
            </form>
            <form method="POST" action="<?= $r ?>/admin/widgets/delete/<?= $w['id'] ?>"
                  onsubmit="return confirm('Delete widget \'<?= addslashes($w['name']) ?>\'?')">
              <?= CSRF::field() ?>
              <button class="btn btn-sm btn-outline-danger">Delete</button>
            </form>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add Widget Modal -->
<div id="addWidgetModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1050;align-items:center;justify-content:center">
  <div style="background:var(--card-bg,#1e2530);border-radius:8px;width:500px;max-width:95vw;padding:24px">
    <div style="display:flex;justify-content:space-between;margin-bottom:16px">
      <h5 class="mb-0">Add Widget</h5>
      <button onclick="document.getElementById('addWidgetModal').style.display='none'" class="btn-close btn-close-white"></button>
    </div>
    <form method="POST" action="<?= $r ?>/admin/widgets/create">
      <?= CSRF::field() ?>
      <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" required placeholder="Widget name">
      </div>
      <div class="row g-2 mb-3">
        <div class="col">
          <label class="form-label">Type</label>
          <select name="type" class="form-select">
            <option value="trending_news">Trending News</option>
            <option value="breaking_news">Breaking News</option>
            <option value="category_news">Category News</option>
            <option value="ad_square">Square Ad</option>
            <option value="rate_gold">Gold Rate</option>
            <option value="rate_petrol">Petrol Rate</option>
            <option value="poll">Poll</option>
            <option value="custom_html">Custom HTML</option>
          </select>
        </div>
        <div class="col">
          <label class="form-label">Position</label>
          <select name="position" class="form-select">
            <option value="sidebar">Sidebar (Desktop)</option>
            <option value="before_footer">Before Footer (Mobile)</option>
          </select>
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">Title (optional)</label>
        <input type="text" name="title" class="form-control">
      </div>
      <button class="btn btn-primary w-100">Add Widget</button>
    </form>
  </div>
</div>

<script>
// Sortable rows (simple drag reorder)
const list = document.getElementById('widgetList');
let dragging = null;
list.querySelectorAll('tr').forEach(tr => {
  tr.draggable = true;
  tr.addEventListener('dragstart', () => dragging = tr);
  tr.addEventListener('dragover', e => { e.preventDefault(); tr.style.outline = '2px solid #C0001A'; });
  tr.addEventListener('dragleave', () => tr.style.outline = '');
  tr.addEventListener('drop', () => {
    tr.style.outline = '';
    if (dragging && dragging !== tr) list.insertBefore(dragging, tr);
    saveOrder();
  });
});

function saveOrder() {
  const ids = [...list.querySelectorAll('tr')].map(tr => tr.dataset.id);
  fetch('<?= $r ?>/admin/widgets/reorder', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: 'ids=' + JSON.stringify(ids) + '&csrf_token=' + document.querySelector('[name=csrf_token]')?.value
  });
}
</script>

<!-- Edit Widget Modal -->
<div id="editWidgetModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1050;align-items:center;justify-content:center">
  <div style="background:var(--card-bg,#1e2530);border-radius:8px;width:500px;max-width:95vw;padding:24px">
    <div style="display:flex;justify-content:space-between;margin-bottom:16px">
      <h5 class="mb-0">Edit Widget</h5>
      <button onclick="document.getElementById('editWidgetModal').style.display='none'" class="btn-close btn-close-white"></button>
    </div>
    <form id="editWidgetForm" method="POST">
      <?= \App\Core\CSRF::field() ?>
      <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" id="ewName" class="form-control" required>
      </div>
      <div class="row g-2 mb-3">
        <div class="col">
          <label class="form-label">Type</label>
          <select name="type" id="ewType" class="form-select">
            <option value="trending_news">Trending News</option>
            <option value="breaking_news">Breaking News</option>
            <option value="category_news">Category News</option>
            <option value="ad_square">Square Ad</option>
            <option value="rate_gold">Gold Rate</option>
            <option value="rate_petrol">Petrol Rate</option>
            <option value="poll">Poll</option>
            <option value="custom_html">Custom HTML</option>
          </select>
        </div>
        <div class="col">
          <label class="form-label">Position</label>
          <select name="position" id="ewPosition" class="form-select">
            <option value="sidebar">Sidebar (Desktop)</option>
            <option value="before_footer">Before Footer (Mobile)</option>
          </select>
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">Title (optional)</label>
        <input type="text" name="title" id="ewTitle" class="form-control">
      </div>
      <div class="row g-2 mb-3">
        <div class="col-6"><label class="form-label"><input type="checkbox" name="show_desktop" id="ewDesktop" value="1" class="form-check-input me-1"> Show on Desktop</label></div>
        <div class="col-6"><label class="form-label"><input type="checkbox" name="show_mobile" id="ewMobile" value="1" class="form-check-input me-1"> Show on Mobile</label></div>
      </div>
      <button class="btn btn-primary w-100">Update Widget</button>
    </form>
  </div>
</div>

<script>
function editWidget(w) {
  document.getElementById('editWidgetForm').action = '<?= $r ?>/admin/widgets/update/' + w.id;
  document.getElementById('ewName').value = w.name;
  document.getElementById('ewTitle').value = w.title || '';
  document.getElementById('ewType').value = w.type;
  document.getElementById('ewPosition').value = w.position;
  document.getElementById('ewDesktop').checked = w.show_desktop == 1;
  document.getElementById('ewMobile').checked = w.show_mobile == 1;
  document.getElementById('editWidgetModal').style.display = 'flex';
}
</script>

<!-- Edit Widget Modal -->
<div id="editWidgetModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1050;align-items:center;justify-content:center">
  <div style="background:var(--card-bg,#fff);border-radius:8px;width:480px;max-width:95vw;padding:24px;max-height:90vh;overflow-y:auto">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
      <h5 class="mb-0">Edit Widget</h5>
      <button onclick="document.getElementById('editWidgetModal').style.display='none'" class="btn-close"></button>
    </div>
    <form id="editWidgetForm" method="POST">
      <?= App\Core\CSRF::field() ?>
      <div class="mb-3"><label class="form-label fw-600 small">Name</label>
        <input type="text" name="name" id="ewName" class="form-control" required></div>
      <div class="row g-2 mb-3">
        <div class="col"><label class="form-label fw-600 small">Type</label>
          <select name="type" id="ewType" class="form-select">
            <option value="trending_news">Trending News</option>
            <option value="breaking_news">Breaking News</option>
            <option value="category_news">Category News</option>
            <option value="ad_square">Square Ad</option>
            <option value="rate_gold">Gold Rate</option>
            <option value="rate_petrol">Petrol Rate</option>
            <option value="poll">Poll</option>
            <option value="custom_html">Custom HTML</option>
          </select></div>
        <div class="col"><label class="form-label fw-600 small">Position</label>
          <select name="position" id="ewPos" class="form-select">
            <option value="sidebar">Sidebar</option>
            <option value="before_footer">Before Footer</option>
          </select></div>
      </div>
      <div class="mb-3"><label class="form-label fw-600 small">Title (optional)</label>
        <input type="text" name="title" id="ewTitle" class="form-control"></div>
      <div class="d-flex gap-3 mb-3">
        <label><input type="checkbox" name="show_desktop" id="ewDesk" value="1" class="form-check-input me-1">Desktop</label>
        <label><input type="checkbox" name="show_mobile" id="ewMob" value="1" class="form-check-input me-1">Mobile</label>
      </div>
      <button class="btn btn-primary w-100">Update Widget</button>
    </form>
  </div>
</div>
<script>
function editWidget(w){
  document.getElementById('editWidgetForm').action='<?= $r ?>/admin/widgets/update/'+w.id;
  document.getElementById('ewName').value=w.name||'';
  document.getElementById('ewTitle').value=w.title||'';
  document.getElementById('ewType').value=w.type||'custom_html';
  document.getElementById('ewPos').value=w.position||'sidebar';
  document.getElementById('ewDesk').checked=w.show_desktop==1;
  document.getElementById('ewMob').checked=w.show_mobile==1;
  document.getElementById('editWidgetModal').style.display='flex';
}
</script>