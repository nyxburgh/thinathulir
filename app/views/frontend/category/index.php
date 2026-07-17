<?php use App\Core\Helper; ?>
<?php
function catE(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
$heE = 'catE';

$catColor = $category['color'] ?? '#C0001A';
$catSlug  = $category['slug'] ?? '';
$catName  = $category['name_tamil'] ?: $category['name'];
$labelText = $catName;
$accentColor = $catColor;

// Hero = h1 (big) + h2-h4 (3 news cards; the right grid's 4th card is the ad)
$heroArticles = array_slice($articles ?? [], 0, 4);
$gridArticles = array_slice($articles ?? [], 4);
$h1 = $heroArticles[0] ?? null;
$h2 = $heroArticles[1] ?? null;
$h3 = $heroArticles[2] ?? null;
$h4 = $heroArticles[3] ?? null;
?>

<!-- CATEGORY HEADER -->
<div class="sec-head sec-head-mt">
  <span class="sec-head-bar sec-head-bar-dyn" style="--ac:<?= $catColor ?>"></span>
  <span class="sec-head-title"><?= catE($category['name']) ?></span>
  <span class="sec-head-ta"><?= catE($catName) ?></span>
  <span class="sec-head-ta sec-head-count">(<?= number_format($total) ?> செய்திகள்)</span>
</div>

<!-- SUBCATEGORY PILLS -->
<?php if (!empty($subcategories)): ?>
<div class="subcat-pills">
  <a href="<?= $r ?>/tamil-news/<?= catE($catSlug) ?>"
     class="subcat-pill <?= empty($activeSubSlug) ? 'active' : '' ?>">அனைத்தும்</a>
  <?php foreach ($subcategories as $sub): ?>
  <a href="<?= $r ?>/tamil-news/<?= catE($catSlug) ?>?sub=<?= catE($sub['slug']) ?>"
     class="subcat-pill <?= $activeSubSlug === $sub['slug'] ? 'active' : '' ?>">
    <?= catE($sub['name_tamil'] ?: $sub['name']) ?>
  </a>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (empty($articles)): ?>
<div class="empty-state">
  <div class="empty-icon">📰</div>
  <p>இந்த பிரிவில் இன்னும் செய்திகள் இல்லை</p>
</div>
<?php else: ?>

<!-- DISTRICT FILTER — Tamil Nadu category only -->
<?php if (!empty($isTamilNadu) && !empty($districts)): ?>
<div class="tn-district-bar notranslate" translate="no">
  <div class="tn-district-bar-left">
    <span class="tn-district-title">தமிழ்நாடு</span>
    <span class="tn-district-sep">·</span>
    <span class="tn-district-sub">Tamil Nadu</span>
  </div>
  <div class="tn-district-bar-right">
    <label class="tn-district-label">மாவட்டம்</label>
    <select class="tn-district-select notranslate" onchange="if(this.value)window.location=this.value">
      <option value="<?= $r ?>/tamil-news/<?= catE($catSlug) ?>" <?= !$activeDistrictId?'selected':'' ?>>அனைத்தும்</option>
      <?php foreach ($districts as $d): ?>
      <option value="<?= $r ?>/tamil-news/<?= catE($catSlug) ?>?district=<?= $d['id'] ?>"
              <?= $d['id']==$activeDistrictId?'selected':'' ?>><?= catE($d['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
</div>
<?php endif; ?>

<?php include VIEW_PATH . '/partials/_hero_section.php'; ?>

<!-- REST: 4-col grid -->
<?php if (!empty($gridArticles)): ?>
<div class="sec-head sec-head-mt2">
  <span class="sec-head-bar sec-head-bar-dyn" style="--ac:<?= $catColor ?>"></span>
  <span class="sec-head-title">More</span>
  <span class="sec-head-ta"><?= catE($catName) ?> செய்திகள்</span>
</div>
<?php
  // Ad cadence: each row of 4 grid cells is 3 news cards + 1 square ad card
  // (the ad IS the 4th card in the row, not a 5th extra), plus a full-width
  // horizontal ad after every 3rd such row (3 rows = 9 news + 3 square ads).
  // Every ad container uses data-ad-pool so the page-wide counter in
  // loadAd() (frontend.php) hands each one a distinct advertiser — never
  // the same ad twice at once.
  $_catArtCount = 0;
  $_catGroupNum = 0;
?>
<div class="g4" id="catGrid" data-art-count="<?= count($gridArticles) ?>" data-group-num="<?= (int)floor(count($gridArticles) / 3) ?>">
  <?php foreach ($gridArticles as $a):
    $_catArtCount++; ?>
  <a href="<?= $r ?>/article/<?= catE($a['slug']) ?>" class="nc <?= empty($a['image_url']) ? 'nc-no-img' : '' ?>">
    <?php if (!empty($a['image_url'])): ?>
    <img src="<?= catE(rtrim(ASSET_URL,'/').'/public'.($a['thumb_url'] ?: $a['image_url'])) ?>" alt="<?= catE($a['title']) ?>" loading="lazy">
    <?php endif; ?>
    <div class="nc-body">
      <span class="ctag ctag-accent" style="--ac:<?= $catColor ?>"><?= catE($catName) ?></span>
      <div class="nc-title <?= empty($a['image_url']) ? 'nc-title-lg' : '' ?>"><?= catE($a['title']) ?></div>
      <?php if (empty($a['image_url']) && !empty($a['excerpt'])): ?>
      <div class="nc-no-img-excerpt"><?= catE(mb_substr(strip_tags($a['excerpt']), 0, 150)) ?></div>
      <?php endif; ?>
      <div class="hero4-meta notranslate" translate="no">
        <?= Helper::timeAgo($a['published_at']) ?>
        <?php if (($a['view_count']??0) > 0): ?> · 👁 <?= number_format($a['view_count']) ?><?php endif; ?>
      </div>
    </div>
  </a>
  <?php if ($_catArtCount % 3 === 0):
    $_catGroupNum++; ?>
  <div class="nc nc-ad notranslate" translate="no">
    <span class="nc-ad-label">Ad</span>
    <div class="ad-rotator" data-ad-pool="square"></div>
  </div>
  <?php if ($_catGroupNum % 3 === 0): ?>
  <div class="g4-ad-horizontal notranslate" translate="no">
    <div class="ad-rotator" data-ad-pool="horizontal"></div>
  </div>
  <?php endif; ?>
  <?php endif; ?>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- INFINITE SCROLL SENTINEL -->
<div id="catScrollSentinel" class="scroll-sentinel"></div>
<div id="catScrollSpinner" class="scroll-spinner" style="display:none">
  <span class="scroll-spinner-dot"></span>
  <span class="scroll-spinner-dot"></span>
  <span class="scroll-spinner-dot"></span>
</div>
<div id="catScrollEnd" class="scroll-end-msg" style="display:none">— முடிந்தது —</div>

<?php endif; ?>


