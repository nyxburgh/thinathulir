<?php use App\Core\Helper; ?>
<style>
.team-verify-wrap{max-width:520px;margin:0 auto;padding:32px 16px}
.team-verify-card{background:#fff;border:1px solid #E2E8F0;border-radius:14px;padding:28px;text-align:center}
.team-verify-photo{width:130px;height:130px;border-radius:50%;object-fit:cover;margin:0 auto 16px;display:block;background:#F1F5F9;border:3px solid #C0001A}
.team-verify-initials{width:130px;height:130px;border-radius:50%;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;background:#C0001A;color:#fff;font-weight:700;font-size:44px;border:3px solid #C0001A}
.team-verify-name{font-size:22px;font-weight:800;color:#1E293B}
.team-verify-role{color:#C0001A;font-weight:600;margin-top:2px}
.team-verify-badge{display:inline-block;margin-top:14px;padding:6px 14px;border-radius:20px;background:#DCFCE7;color:#166534;font-weight:700;font-size:13px}
.team-verify-details{text-align:left;margin-top:22px;border-top:1px solid #E2E8F0;padding-top:18px}
.team-verify-row{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px dashed #E2E8F0;font-size:14px}
.team-verify-row span:first-child{color:#64748B}
.team-verify-row span:last-child{font-weight:600;color:#1E293B}
.team-verify-notfound{color:#B91C1C;background:#FEF2F2;border:1px solid #FCA5A5;border-radius:10px;padding:24px;text-align:center}
.team-verify-back{display:inline-block;margin-top:20px;color:#C0001A;text-decoration:none;font-weight:600}
</style>

<div class="team-verify-wrap">
  <?php if (!empty($member)): ?>
  <div class="team-verify-card">
    <?php if (!empty($member['avatar'])): ?>
    <img src="<?= $r ?><?= Helper::e($member['avatar']) ?>" class="team-verify-photo" alt="<?= Helper::e($member['name']) ?>">
    <?php else: ?>
    <div class="team-verify-initials"><?= strtoupper(substr($member['name'],0,1)) ?></div>
    <?php endif; ?>
    <div class="team-verify-name"><?= Helper::e($member['name']) ?></div>
    <div class="team-verify-role"><?= Helper::e($member['designation'] ?: $member['role_name']) ?></div>
    <div class="team-verify-badge">✅ Verified Active Staff — சரிபார்க்கப்பட்ட ஊழியர்</div>

    <div class="team-verify-details">
      <?php if (!empty($member['id_no'])): ?>
      <div class="team-verify-row"><span>ID No.</span><span><?= Helper::e($member['id_no']) ?></span></div>
      <?php endif; ?>
      <div class="team-verify-row"><span>Role</span><span><?= Helper::e($member['role_name']) ?></span></div>
      <?php if (!empty($member['phone'])): ?>
      <div class="team-verify-row"><span>Phone</span><span><?= Helper::e($member['phone']) ?></span></div>
      <?php endif; ?>
      <?php if (!empty($member['dob'])): ?>
      <div class="team-verify-row"><span>Date of Birth</span><span><?= Helper::e(date('d M Y', strtotime($member['dob']))) ?></span></div>
      <?php endif; ?>
    </div>
  </div>
  <?php else: ?>
  <div class="team-verify-notfound">
    <strong>⚠️ Not currently verified</strong>
    <p class="mb-0">இந்த அடையாள அட்டை தற்போது செல்லுபடியாகாது — This ID could not be verified. It may belong to someone no longer active with தினத்துளிர். Please contact the office to confirm.</p>
  </div>
  <?php endif; ?>
  <div style="text-align:center">
    <a href="<?= $r ?>/our-team" class="team-verify-back">← Our Team</a>
  </div>
</div>
