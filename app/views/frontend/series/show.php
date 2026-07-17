<?php use App\Core\Helper; ?>
<div style="max-width:900px;margin:0 auto;padding:16px 16px 60px">

  <div class="breadcrumb">
    <a href="<?= $r ?>/">முகப்பு</a>
    <span>›</span>
    <span><?= Helper::e($series['title']) ?></span>
  </div>

  <div style="margin:16px 0 24px">
    <span class="ctag" style="background:#0EA5E9;color:#fff;margin-bottom:8px;display:inline-block">
      <?= $series['status'] === 'completed' ? 'நிறைவடைந்தது · Completed' : 'தொடர்கிறது · Ongoing' ?> Series
    </span>
    <h1 class="art-title" style="margin:8px 0"><?= Helper::e($series['title']) ?></h1>
    <?php if (!empty($series['category_name'])): ?>
    <div style="font-size:13px;color:#9CA3AF;margin-bottom:8px">
      📁 <?= Helper::e($series['category_name']) ?>
      <?php if (!empty($series['contributor_name'])): ?> · ✍️ <?= Helper::e($series['contributor_name']) ?><?php endif; ?>
    </div>
    <?php endif; ?>
    <?php if (!empty($series['description'])): ?>
    <p style="color:#4B5563;font-size:15px;line-height:1.6"><?= nl2br(Helper::e($series['description'])) ?></p>
    <?php endif; ?>
  </div>

  <div class="sec-head sec-head-mt">
    <span class="sec-head-bar-dyn" style="--ac:#0EA5E9"></span>
    <span class="sec-head-title">அனைத்து பாகங்களும்</span>
    <span class="sec-head-ta">All Parts (<?= count($parts) ?>)</span>
  </div>

  <?php if (empty($parts)): ?>
  <div style="text-align:center;padding:40px;color:#9CA3AF;font-size:14px">
    No parts published yet. Check back soon.
  </div>
  <?php else: ?>
  <div class="g4">
    <?php foreach ($parts as $p):
      $hasImg = !empty($p['image_url']); ?>
    <a href="<?= $r ?>/article/<?= Helper::e($p['slug']) ?>" class="nc <?= $hasImg ? '' : 'nc-no-img' ?>">
      <?php if ($hasImg): ?>
      <img src="<?= rtrim(ASSET_URL,'/').'/public/'.ltrim($p['thumb_url'] ?: $p['image_url'],'/') ?>" alt="<?= Helper::e($p['title']) ?>" loading="lazy" onerror="this.remove()">
      <?php endif; ?>
      <div class="nc-body">
        <span class="ctag">பாகம் <?= (int)$p['series_part'] ?></span>
        <div class="nc-title <?= $hasImg ? '' : 'nc-title-lg' ?>"><?= Helper::e($p['title']) ?></div>
        <?php if (!$hasImg && !empty($p['excerpt'])): ?>
        <div class="nc-no-img-excerpt"><?= Helper::e(mb_substr(strip_tags($p['excerpt']),0,140)) ?></div>
        <?php endif; ?>
        <div class="hero4-meta notranslate" translate="no"><?= Helper::timeAgo($p['published_at']) ?></div>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
