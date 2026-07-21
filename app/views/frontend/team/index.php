<?php use App\Core\Helper; ?>
<style>
.team-wrap{max-width:1100px;margin:0 auto;padding:24px 16px}
.team-wrap h1{font-size:24px;font-weight:800;color:#1E293B;margin-bottom:6px}
.team-wrap .team-sub{color:#64748B;margin-bottom:24px}
.team-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:18px}
.team-card{display:block;text-align:center;text-decoration:none;background:#fff;border:1px solid #E2E8F0;border-radius:10px;padding:18px 12px;transition:box-shadow .15s}
.team-card:hover{box-shadow:0 4px 14px rgba(0,0,0,.08)}
.team-photo{width:88px;height:88px;border-radius:50%;object-fit:cover;margin:0 auto 10px;display:block;background:#F1F5F9}
.team-initials{width:88px;height:88px;border-radius:50%;margin:0 auto 10px;display:flex;align-items:center;justify-content:center;background:#C0001A;color:#fff;font-weight:700;font-size:28px}
.team-name{font-weight:700;color:#1E293B;font-size:15px}
.team-designation{color:#64748B;font-size:13px;margin-top:2px}
</style>

<div class="team-wrap">
  <h1>எங்கள் குழு — Our Team</h1>
  <p class="team-sub">தினத்துளிர் ஊழியர்களை இங்கே சரிபார்க்கலாம். ஒவ்வொருவரின் அடையாள அட்டையிலும் உள்ள QR குறியீட்டை ஸ்கேன் செய்தும் இப்பக்கத்தை அடையலாம்.</p>

  <?php if (empty($members)): ?>
  <div class="empty-state"><div class="empty-icon">👥</div><p>No team members listed yet.</p></div>
  <?php else: ?>
  <div class="team-grid">
    <?php foreach ($members as $m): ?>
    <a href="<?= $r ?>/our-team/<?= (int)$m['id'] ?>" class="team-card">
      <?php if (!empty($m['avatar'])): ?>
      <img src="<?= $r ?><?= Helper::e($m['avatar']) ?>" class="team-photo" alt="<?= Helper::e($m['name']) ?>">
      <?php else: ?>
      <div class="team-initials"><?= strtoupper(substr($m['name'],0,1)) ?></div>
      <?php endif; ?>
      <div class="team-name"><?= Helper::e($m['name']) ?></div>
      <div class="team-designation"><?= Helper::e($m['designation'] ?: $m['role_name']) ?></div>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
