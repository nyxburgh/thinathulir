<?php use App\Core\{Helper, CSRF, Auth};
$displayName = $ad['business_name'] ?? '';
$contactPerson = $ad['contact_person'] ?? $displayName;
$descText = $ad['small_desc'] ?? ($ad['notes'] ?? '');
$canEdit = Auth::can('manage_ads');
$sColor=['pending'=>'warning','active'=>'success','paused'=>'secondary','expired'=>'dark','rejected'=>'danger'];
$pColor=['pending'=>'warning','confirmed'=>'success','rejected'=>'danger'];
?>
<div class="af-topbar">
  <a href="<?= $r . $adsBase ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
  <div class="af-topbar-title"><?= Helper::e($displayName) ?></div>
  <div class="d-flex gap-2">
    <a href="<?= $r . $adsBase ?>/edit/<?= $ad['id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
    <a href="<?= $r . $adsBase ?>/images/<?= $ad['id'] ?>" class="btn btn-sm btn-danger"><i class="bi bi-images me-1"></i>Images</a>
  </div>
</div>

<div class="row g-3" style="max-width:900px;margin:0 auto;padding:16px 16px 80px">

  <!-- Customer Details -->
  <div class="col-md-6">
    <div class="tn-card h-100">
      <div class="af-card-head">Customer Details</div>
      <div class="af-card-body">
        <table class="table table-sm table-borderless mb-0">
          <tr><th class="text-muted small w-40">Business</th><td><strong><?= Helper::e($displayName) ?></strong></td></tr>
          <tr><th class="text-muted small">Contact</th><td><?= Helper::e($contactPerson) ?></td></tr>
          <tr><th class="text-muted small">Phone</th><td><?= $ad['contact_phone'] ? '<a href="tel:'.Helper::e($ad['contact_phone']).'">'.Helper::e($ad['contact_phone']).'</a>' : '—' ?></td></tr>
          <tr><th class="text-muted small">Email</th><td><?= $ad['contact_email'] ? '<a href="mailto:'.Helper::e($ad['contact_email']).'">'.Helper::e($ad['contact_email']).'</a>' : '—' ?></td></tr>
          <?php if (!empty($ad['website_url'])): ?>
          <tr><th class="text-muted small">Website</th><td><a href="<?= Helper::e($ad['website_url']) ?>" target="_blank">🔗 Visit</a></td></tr>
          <?php endif; ?>
          <?php foreach (['facebook_url'=>'Facebook','instagram_url'=>'Instagram','youtube_url'=>'YouTube'] as $col=>$label): ?>
          <?php if (!empty($ad[$col])): ?>
          <tr><th class="text-muted small"><?= $label ?></th><td><a href="<?= Helper::e($ad[$col]) ?>" target="_blank">🔗</a></td></tr>
          <?php endif; ?>
          <?php endforeach; ?>
          <?php if (!empty($ad['address'])): ?>
          <tr><th class="text-muted small">Address</th><td><?= nl2br(Helper::e($ad['address'])) ?></td></tr>
          <?php endif; ?>
          <?php if (!empty($descText)): ?>
          <tr><th class="text-muted small">Description</th><td><?= Helper::e($descText) ?></td></tr>
          <?php endif; ?>
        </table>
      </div>
    </div>
  </div>

  <!-- Package + Validity -->
  <div class="col-md-6">
    <div class="tn-card mb-3">
      <div class="af-card-head">Package & Validity</div>
      <div class="af-card-body">
        <?php if ($pkg): ?>
        <div class="d-flex align-items-center gap-2 mb-2">
          <span class="badge bg-dark fs-6"><?= Helper::e($pkg['code']??'') ?></span>
          <span class="fw-600"><?= Helper::e($pkg['name']) ?></span>
        </div>
        <div class="small text-muted mb-2">
          <?php $slots=[]; if($pkg['includes_square']??0)$slots[]='Square'; if($pkg['includes_horizontal']??0)$slots[]='Horizontal'; if($pkg['includes_vertical']??0)$slots[]='Vertical'; ?>
          Includes: <?= $slots ? implode(', ',$slots) : ucfirst($pkg['slot_type']??'—') ?>
          · <?= $pkg['news_quota']??0 ?> News
        </div>
        <?php else: ?>
        <div class="text-muted small">No package assigned</div>
        <?php endif; ?>
        <div class="d-flex gap-3 mt-2">
          <div><div class="small text-muted">From</div><strong><?= $ad['valid_from'] ?></strong></div>
          <div><div class="small text-muted">Until</div><strong><?= $ad['valid_until'] ?></strong></div>
        </div>
        <?php
        $today=new DateTime(); $until=new DateTime($ad['valid_until']);
        $diff=$today->diff($until); $daysLeft=$diff->invert?0:(int)$diff->days;
        $pct=min(100,max(0,round($daysLeft/(max(1,round(($until->getTimestamp()-strtotime($ad['valid_from']))/86400)))*100)));
        ?>
        <div class="mt-2">
          <div class="d-flex justify-content-between small text-muted mb-1"><span>Validity</span><span><?= $daysLeft ?> days left</span></div>
          <div class="progress" style="height:6px"><div class="progress-bar bg-<?= $daysLeft<30?'danger':($daysLeft<90?'warning':'success') ?>" style="width:<?= $pct ?>%"></div></div>
        </div>
      </div>
    </div>

    <!-- Status + Payment -->
    <div class="tn-card">
      <div class="af-card-head">Status & Payment</div>
      <div class="af-card-body">
        <div class="d-flex gap-3 mb-3">
          <div><div class="small text-muted">Ad Status</div><span class="badge bg-<?= $sColor[$ad['status']]??'secondary' ?>"><?= ucfirst($ad['status']) ?></span></div>
          <div><div class="small text-muted">Payment</div><span class="badge bg-<?= $pColor[$ad['payment_status']]??'secondary' ?>"><?= ucfirst($ad['payment_status']) ?></span></div>
          <?php if ($ad['payment_amount']): ?>
          <div><div class="small text-muted">Amount</div><strong>₹<?= number_format($ad['payment_amount'],0) ?></strong></div>
          <?php endif; ?>
        </div>
        <?php if (!empty($ad['payment_ref'])): ?>
        <div class="small mb-1"><span class="text-muted">Ref:</span> <?= Helper::e($ad['payment_ref']) ?></div>
        <?php endif; ?>
        <?php if ($ad['payment_status'] !== 'confirmed' && $canEdit): ?>
        <form method="POST" action="<?= $r . $adsBase ?>/confirm-payment/<?= $ad['id'] ?>">
          <?= CSRF::field() ?>
          <div class="row g-2 mb-2">
            <div class="col"><input type="text" name="payment_ref" class="form-control form-control-sm" placeholder="Ref No" value="<?= Helper::e($ad['payment_ref']??'') ?>"></div>
            <div class="col"><input type="text" name="payment_note" class="form-control form-control-sm" placeholder="Note"></div>
          </div>
          <button class="btn btn-sm btn-success w-100">✓ Confirm Payment & Activate</button>
        </form>
        <?php endif; ?>
        <div class="d-flex gap-2 mt-2">
          <form method="POST" action="<?= $r . $adsBase ?>/toggle/<?= $ad['id'] ?>">
            <?= CSRF::field() ?>
            <button class="btn btn-sm btn-outline-secondary"><?= $ad['status']==='active'?'⏸ Pause':'▶ Activate' ?></button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- News Quota -->
  <div class="col-12">
    <div class="tn-card">
      <div class="af-card-head d-flex justify-content-between align-items-center">
        <span>Sponsored News</span>
        <?php if ($newsUsed < $newsQuota && $newsQuota > 0): ?>
        <a href="<?= $r ?>/portal/my-ads/<?= $ad['id'] ?>/write-news" class="btn btn-sm btn-danger">+ Create News</a>
        <?php elseif ($newsQuota > 0): ?>
        <span class="badge bg-secondary">Quota Full</span>
        <?php endif; ?>
      </div>
      <div class="af-card-body">
        <?php if ($newsQuota > 0): ?>
        <div class="d-flex gap-4 mb-3">
          <div class="text-center"><div class="fs-4 fw-700 text-danger"><?= $newsQuota ?></div><div class="small text-muted">Allowed</div></div>
          <div class="text-center"><div class="fs-4 fw-700"><?= $newsUsed ?></div><div class="small text-muted">Used</div></div>
          <div class="text-center"><div class="fs-4 fw-700 text-success"><?= max(0,$newsQuota-$newsUsed) ?></div><div class="small text-muted">Remaining</div></div>
        </div>
        <?php endif; ?>
        <?php if (!empty($news)): ?>
        <table class="table table-sm mb-0">
          <thead><tr><th>Title</th><th>Status</th><th>Published</th></tr></thead>
          <tbody>
          <?php foreach ($news as $n): ?>
          <tr>
            <td><a href="<?= $r ?>/article/<?= Helper::e($n['slug']) ?>" target="_blank"><?= Helper::e(mb_substr($n['title'],0,60)) ?></a></td>
            <td><span class="badge bg-<?= $n['status']==='published'?'success':'secondary' ?>"><?= ucfirst($n['status']) ?></span></td>
            <td class="small text-muted"><?= substr($n['published_at']??'',0,10) ?></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
        <?php else: ?><div class="text-muted small">No news articles yet.</div><?php endif; ?>
      </div>
    </div>
  </div>

</div>
