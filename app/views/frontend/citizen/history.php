<?php use App\Core\Helper; ?>
<div style="max-width:640px;margin:0 auto;padding:16px 16px 80px">
  <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px">
    <a href="<?= rtrim(BASE_URL,'/') ?>/public/citizen-reporter" style="color:#C0001A;font-size:13px;text-decoration:none">← Submit News</a>
    <h2 style="font-size:18px;font-weight:800;margin:0">My Submitted Reports</h2>
  </div>

  <?php if (empty($reports)): ?>
  <div style="text-align:center;padding:40px;color:#9CA3AF;font-size:14px">
    <div style="font-size:40px;margin-bottom:12px">📋</div>
    No reports submitted yet from this device.
  </div>
  <?php else: ?>

  <div style="font-size:12px;color:#9CA3AF;margin-bottom:12px;padding:8px 12px;background:#FFF7ED;border-radius:6px;border:1px solid #FED7AA">
    📌 Reports stay in this list for up to <strong>30 days</strong>, and only your latest <strong>10</strong> submissions are kept.
  </div>

  <?php foreach ($reports as $r): ?>
  <?php
    $status = $r['display_status'] ?? $r['status'];
    $colors = ['pending'=>['#FEF3C7','#92400E'],'approved'=>['#D1FAE5','#065F46'],'rejected'=>['#FEE2E2','#991B1B'],'active'=>['#D1FAE5','#065F46'],'expired'=>['#F3F4F6','#6B7280']];
    [$bg,$fg] = $colors[$status] ?? ['#F3F4F6','#6B7280'];
    $label = ['pending'=>'Pending Review','approved'=>'Approved','rejected'=>'Rejected','active'=>'Published (Active)','expired'=>'Published (Expired — 30 days)'][$status] ?? ucfirst($status);
  ?>
  <div style="background:#fff;border:1px solid #F0EFE9;border-radius:10px;padding:14px;margin-bottom:10px">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;margin-bottom:8px">
      <div style="font-weight:700;font-size:14px;color:#1A1A1A;line-height:1.4"><?= Helper::e($r['title']) ?></div>
      <span style="background:<?= $bg ?>;color:<?= $fg ?>;font-size:10px;font-weight:700;padding:2px 8px;border-radius:12px;white-space:nowrap;flex-shrink:0"><?= $label ?></span>
    </div>
    <div style="font-size:12px;color:#9CA3AF;display:flex;gap:12px;flex-wrap:wrap">
      <span>📅 <?= substr($r['created_at'],0,10) ?></span>
      <?php if ($r['category_name']): ?><span>📁 <?= Helper::e($r['category_name']) ?></span><?php endif; ?>
      <?php if (!empty($r['article_slug']) && $status==='active'): ?>
      <a href="<?= rtrim(BASE_URL,'/') ?>/public/article/<?= Helper::e($r['article_slug']) ?>"
         style="color:#C0001A;font-weight:600" target="_blank">View Article →</a>
      <?php endif; ?>
    </div>
    <?php if ($r['status']==='rejected'): ?>
    <div style="margin-top:8px;font-size:12px;color:#991B1B;background:#FEE2E2;padding:6px 10px;border-radius:6px">
      உங்கள் செய்தி ஏற்றுக்கொள்ளப்படவில்லை. மீண்டும் முயற்சிக்கவும்.
    </div>
    <?php endif; ?>
  </div>
  <?php endforeach; ?>
  <?php endif; ?>
</div>
