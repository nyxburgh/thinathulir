<?php use App\Core\{Helper, Auth, CSRF};
$statusConfig = [
    ''        => ['label'=>'All',     'color'=>'#6B7280', 'bg'=>'#F3F4F6'],
    'active'  => ['label'=>'Active',  'color'=>'#065F46', 'bg'=>'#D1FAE5'],
    'pending' => ['label'=>'Pending', 'color'=>'#92400E', 'bg'=>'#FEF3C7'],
    'expired' => ['label'=>'Expired', 'color'=>'#6B7280', 'bg'=>'#F3F4F6'],
    'paused'  => ['label'=>'Paused',  'color'=>'#1E40AF', 'bg'=>'#DBEAFE'],
    'rejected'=> ['label'=>'Rejected','color'=>'#991B1B', 'bg'=>'#FEE2E2'],
];
$rowColors = [
    'active'  => '#F0FDF4',
    'pending' => '#FFFBEB',
    'expired' => '#F9FAFB',
    'paused'  => '#EFF6FF',
    'rejected'=> '#FFF1F2',
    'approved'=> '#F0FDF4',
];
$badgeBg = [
    'active'  => '#10B981', 'pending'=>'#F59E0B', 'expired'=>'#9CA3AF',
    'paused'  => '#3B82F6', 'rejected'=>'#EF4444', 'approved'=>'#10B981',
];
$pBadge = ['pending'=>'#F59E0B','confirmed'=>'#10B981','rejected'=>'#EF4444'];
?>
<div class="tn-page-header">
  <div><h2 class="tn-page-title">Business Ads <span class="text-muted fw-300 fs-6">(<?= number_format($total) ?>)</span></h2></div>
  <a href="<?= $r . $adsBase ?>/create" class="btn btn-danger btn-sm"><i class="bi bi-plus-circle me-1"></i>New Ad</a>
</div>

<!-- Pill Filters -->
<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:14px">
  <?php foreach ($statusConfig as $sv => $sc): ?>
  <a href="<?= $r . $adsBase ?>?status=<?= $sv ?>&search=<?= urlencode($search??'') ?>"
     style="display:inline-flex;align-items:center;gap:5px;padding:5px 14px;border-radius:20px;
            font-size:12px;font-weight:700;text-decoration:none;
            background:<?= ($statusFilter??'')===$sv ? $sc['color'] : $sc['bg'] ?>;
            color:<?= ($statusFilter??'')===$sv ? '#fff' : $sc['color'] ?>;
            border:1.5px solid <?= $sc['color'] ?>;
            transition:.15s">
    <?= $sc['label'] ?>
  </a>
  <?php endforeach; ?>
</div>

<!-- Search Bar -->
<form method="GET" style="margin-bottom:12px;display:flex;gap:8px">
  <input type="hidden" name="status" value="<?= htmlspecialchars($statusFilter??'') ?>">
  <input type="text" name="search" class="form-control form-control-sm" style="max-width:260px"
         placeholder="Name / Phone / Contact…" value="<?= htmlspecialchars($search??'') ?>">
  <button class="btn btn-sm btn-outline-secondary">Search</button>
  <?php if (!empty($search)): ?>
  <a href="<?= $r . $adsBase ?>?status=<?= urlencode($statusFilter??'') ?>" class="btn btn-sm btn-outline-secondary">✕</a>
  <?php endif; ?>
</form>

<div class="tn-card">
  <div class="table-responsive">
    <table class="table tn-table mb-0" style="font-size:13px">
      <thead><tr>
        <th>#</th><th>Business</th><th>Contact</th><th>Package</th>
        <th>Amount</th><th>Payment</th><th>Status</th><th>Valid Until</th><th>Actions</th>
      </tr></thead>
      <tbody>
      <?php foreach ($ads as $ad):
        $st  = $ad['status'] ?? 'pending';
        $rbg = $rowColors[$st] ?? '#fff';
      ?>
      <tr style="background:<?= $rbg ?>">
        <td class="text-muted small">#<?= $ad['id'] ?></td>
        <td>
          <div style="font-weight:700"><?= Helper::e($ad['business_name']) ?></div>
          <?php if (!empty($ad['contact_person'])): ?>
          <div style="font-size:11px;color:#9CA3AF"><?= Helper::e($ad['contact_person']) ?></div>
          <?php endif; ?>
        </td>
        <td><?= Helper::e($ad['contact_phone']??'—') ?></td>
        <td>
          <?php if (!empty($ad['package_name'])): ?>
          <?php if (!empty($ad['package_code'])): ?>
          <span style="background:#1F2937;color:#fff;font-size:9px;font-weight:800;padding:1px 6px;border-radius:3px"><?= Helper::e($ad['package_code']) ?></span>
          <?php endif; ?>
          <div style="font-size:11px;color:#6B7280;margin-top:2px"><?= Helper::e($ad['package_name']) ?></div>
          <?php else: ?><span class="text-muted small">—</span><?php endif; ?>
        </td>
        <td><?= $ad['payment_amount'] ? '<strong>₹'.number_format($ad['payment_amount'],0).'</strong>' : '—' ?></td>
        <td>
          <span style="background:<?= $pBadge[$ad['payment_status']]??'#9CA3AF' ?>;color:#fff;font-size:10px;font-weight:700;padding:2px 8px;border-radius:10px">
            <?= ucfirst($ad['payment_status']) ?>
          </span>
        </td>
        <td>
          <span style="background:<?= $badgeBg[$st]??'#9CA3AF' ?>;color:#fff;font-size:10px;font-weight:700;padding:2px 8px;border-radius:10px">
            <?= ucfirst($st) ?>
          </span>
        </td>
        <td class="small">
          <?php
            $until = $ad['valid_until'] ?? '';
            $daysLeft = $until ? (int)ceil((strtotime($until) - time()) / 86400) : null;
          ?>
          <div><?= $until ?></div>
          <?php if ($daysLeft !== null): ?>
          <div style="font-size:10px;color:<?= $daysLeft < 0 ? '#EF4444' : ($daysLeft < 15 ? '#F59E0B' : '#10B981') ?>">
            <?= $daysLeft < 0 ? abs($daysLeft).' days ago' : $daysLeft.' days left' ?>
          </div>
          <?php endif; ?>
        </td>
        <td>
          <div style="display:flex;gap:4px;flex-wrap:wrap">
            <a href="<?= $r . $adsBase ?>/show/<?= $ad['id'] ?>" class="btn btn-xs btn-outline-primary">View</a>
            <a href="<?= $r . $adsBase ?>/edit/<?= $ad['id'] ?>" class="btn btn-xs btn-outline-secondary">Edit</a>
            <a href="<?= $r . $adsBase ?>/images/<?= $ad['id'] ?>" class="btn btn-xs btn-outline-success">Images</a>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($ads)): ?>
      <tr><td colspan="9" class="text-center py-4 text-muted">No ads found.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php $queryExtra='&search='.urlencode($search??'').'&status='.urlencode($statusFilter??'');
include VIEW_PATH.'/partials/pagination.php'; ?>
