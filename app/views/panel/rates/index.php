<?php use App\Core\{Helper, CSRF}; ?>

<div class="portal-page-header">
  <div>
    <h2 class="portal-page-title">Live Rates</h2>
    <p style="font-size:13px;color:var(--portal-muted);margin:2px 0 0">Update gold/silver/petrol/diesel/currency rates</p>
  </div>
</div>

<div class="row g-4">
  <div class="col-md-7">
    <div class="portal-card">
      <div class="portal-card-header">Current Rates</div>
      <div class="table-responsive">
        <table class="table tn-table mb-0">
          <thead><tr><th>Type</th><th>City</th><th>Value</th><th>Change</th><th>Updated</th></tr></thead>
          <tbody>
          <?php if (empty($rates)): ?>
          <tr><td colspan="5" class="text-center py-4 text-muted">No rates added yet.</td></tr>
          <?php else: ?>
          <?php foreach ($rates as $rate): ?>
          <tr>
            <td><strong><?= $types[$rate['type']] ?? $rate['type'] ?></strong></td>
            <td><?= $rate['city'] ?? '—' ?></td>
            <td>₹<?= number_format($rate['value'], 2) ?></td>
            <td>
              <?php if ($rate['change_val'] !== null): ?>
              <span style="color:<?= $rate['change_val'] >= 0 ? '#10B981' : '#EF4444' ?>">
                <?= $rate['change_val'] >= 0 ? '+' : '' ?><?= number_format($rate['change_val'], 2) ?>
                (<?= number_format($rate['change_pct'] ?? 0, 2) ?>%)
              </span>
              <?php else: ?>—<?php endif; ?>
            </td>
            <td style="font-size:11px"><?= Helper::timeAgo($rate['updated_at']) ?></td>
          </tr>
          <?php endforeach; ?>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-md-5">
    <div class="portal-card">
      <div class="portal-card-header">Update Rate</div>
      <div class="portal-card-body">
        <form method="POST" action="<?= $r ?>/panel/rates/update">
          <?= CSRF::field() ?>
          <div class="mb-3">
            <label class="form-label fw-600">Type</label>
            <select name="type" class="form-select">
              <?php foreach ($types as $slug => $label): ?>
              <option value="<?= $slug ?>"><?= $label ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-600">Value (₹)</label>
            <input type="number" name="value" class="form-control" step="0.01" required placeholder="0.00">
          </div>
          <div class="mb-3">
            <label class="form-label fw-600">City <small class="text-muted">(optional — blank = national)</small></label>
            <input type="text" name="city" class="form-control" placeholder="e.g. Chennai">
          </div>
          <div class="mb-3">
            <label class="form-label fw-600">Change Amount <small class="text-muted">(optional)</small></label>
            <input type="number" name="change" class="form-control" step="0.01" placeholder="+/- amount">
          </div>
          <button class="btn btn-primary w-100">Update Rate</button>
        </form>
      </div>
    </div>
  </div>
</div>
