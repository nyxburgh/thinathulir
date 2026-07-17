<?php use App\Core\Helper; ?>
<?php
function tgE(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
$heE         = 'tgE';
$accentColor = '#6B6A64';
$labelText   = $tag['name_tamil'] ?: $tag['name'];

$heroArticles = array_slice($articles ?? [], 0, 4);
$gridArticles = array_slice($articles ?? [], 4);
$h1 = $heroArticles[0] ?? null;
$h2 = $heroArticles[1] ?? null;
$h3 = $heroArticles[2] ?? null;
$h4 = $heroArticles[3] ?? null;
?>

<div class="sec-head sec-head-mt">
  <span class="sec-head-bar sec-head-bar-dyn" style="--ac:<?= $accentColor ?>"></span>
  <span class="sec-head-title">🏷️ <?= tgE($tag['name']) ?></span>
  <?php if ($tag['name_tamil']): ?>
  <span class="sec-head-ta"><?= tgE($tag['name_tamil']) ?></span>
  <?php endif; ?>
  <span class="sec-head-ta sec-head-count">(<?= number_format($total) ?> articles)</span>
</div>

<?php if (empty($articles)): ?>
<div class="empty-state"><div class="empty-icon">🏷️</div><p>No articles found for this tag</p></div>
<?php else: ?>

<?php include VIEW_PATH . '/partials/_hero_section.php'; ?>

<?php if (!empty($gridArticles)): ?>
<div class="g4 g4-mt">
  <?php $_tgArtCount = 0; foreach ($gridArticles as $a):
    $_tgArtCount++; ?>
  <a href="<?= $r ?>/article/<?= tgE($a['slug']) ?>" class="nc <?= empty($a['image_url']) ? 'nc-no-img' : '' ?>">
    <?php if (!empty($a['image_url'])): ?>
    <img src="<?= tgE($a['thumb_url'] ?: $a['image_url']) ?>" alt="<?= tgE($a['title']) ?>" loading="lazy">
    <?php endif; ?>
    <div class="nc-body">
      <span class="ctag"><?= tgE($a['category_tamil'] ?: $a['category_name']) ?></span>
      <div class="nc-title <?= empty($a['image_url']) ? 'nc-title-lg' : '' ?>"><?= tgE($a['title']) ?></div>
      <?php if (empty($a['image_url']) && !empty($a['excerpt'])): ?>
      <div class="nc-no-img-excerpt"><?= tgE(mb_substr(strip_tags($a['excerpt']), 0, 150)) ?></div>
      <?php endif; ?>
      <div class="hero4-meta notranslate" translate="no">
        <?= Helper::timeAgo($a['published_at']) ?>
        <?php if (($a['view_count']??0) > 0): ?> · 👁 <?= number_format($a['view_count']) ?><?php endif; ?>
      </div>
    </div>
  </a>
  <?php if ($_tgArtCount % 3 === 0): ?>
  <div class="nc nc-ad notranslate" translate="no">
    <span class="nc-ad-label">Ad</span>
    <div class="ad-rotator" data-ad-pool="square"></div>
  </div>
  <?php endif; ?>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<?php $queryExtra = ''; include VIEW_PATH . '/partials/pagination.php'; ?>

<?php endif; ?>
