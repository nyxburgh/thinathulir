<?php
use App\Core\Helper;
function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function artImg(array $a, string $size='thumb'): string {
    $raw  = $size === 'full' ? ($a['image_url'] ?? '') : ($a['thumb_url'] ?? $a['image_url'] ?? '');
    $path = $raw ? rtrim(ASSET_URL,'/').'/public/'.ltrim($raw,'/') : '';
    return $path ?: '';
}
function catClass(string $slug): string {
    $map = ['tamil-nadu'=>'red','india'=>'blue','world'=>'teal','cinema'=>'purple','sports'=>'green','technology'=>'blue'];
    return 'cat-' . ($map[$slug] ?? 'red');
}
$_articleUrl = rtrim(BASE_URL, '/') . '/public/article/' . $article['slug'];

// Generate short URL
$_shortCode = '';
try {
    $_shortCode = (new \App\Models\ShortUrlModel())->forArticle((int)$article['id'], $_articleUrl);
} catch (\Exception $e) {}
$_shortUrl = $_shortCode ? rtrim(BASE_URL, '/') . '/public/s/' . $_shortCode : $_articleUrl;

// Share text leads with the title (not a bare URL) — reads better, looks professional
$whatsappMsg = urlencode($article['title'] . "\n" . $_shortUrl);
$_fbUrl      = urlencode($_shortUrl);
$_twText     = urlencode($article['title'] . ' ' . $_shortUrl);
$tags     = array_filter(explode('||', $article['tag_names'] ?? ''));
$tagSlugs = array_filter(explode('||', $article['tag_slugs'] ?? ''));
$isVideo  = !empty($article['youtube_video_id']);
?>

<!-- BREADCRUMB -->
<div class="breadcrumb">
  <button type="button" class="art-back-btn" onclick="goBackSmart()" title="பின் செல்ல" aria-label="Back">
    <i class="bi bi-arrow-left"></i>
  </button>
  <a href="<?= $r ?>/">முகப்பு</a>
  <span>›</span>
  <a href="<?= $r ?>/tamil-news/<?= e($article['category_slug'] ?? '') ?>"><?= e($article['category_tamil'] ?: $article['category_name']) ?></a>
  <span>›</span>
  <span><?= e(mb_substr($article['title'], 0, 50)) ?>…</span>
  <?php if (!empty($districts)): ?>
  <div class="breadcrumb-right notranslate" translate="no">
    <select class="art-district-select" aria-label="மாவட்டம் தேர்வு செய்யவும்"
            onchange="if(this.value)window.location=this.value">
      <option value="">📍 மாவட்டம்</option>
      <?php foreach ($districts as $d): ?>
      <option value="<?= $r ?>/tamil-news/tamil-nadu?district=<?= (int)$d['id'] ?>"
              <?= ((int)($article['district_id'] ?? 0) === (int)$d['id']) ? 'selected' : '' ?>><?= e($d['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <?php endif; ?>
</div>

    <!-- TITLE -->
    <?php if (($article['content_type'] ?? '') === 'special'): ?>
    <span class="ctag" style="background:#7F4FE0;color:#fff;margin-bottom:8px;display:inline-block">சிறப்பு கட்டுரை · Special Article</span>
    <?php elseif (($article['content_type'] ?? '') === 'sponsored'): ?>
    <span class="ctag sponsored-badge">Sponsored · விளம்பர செய்தி</span>
    <?php endif; ?>
    <?php if (!empty($article['series_id'])): ?>
    <a href="<?= $r ?>/series/<?= e($article['series_slug']) ?>" class="ctag" style="background:#0EA5E9;color:#fff;margin-bottom:8px;display:inline-block;text-decoration:none">
      பாகம் <?= (int)$article['series_part'] ?> · <?= e($article['series_title']) ?>
    </a>
    <?php endif; ?>
    <?php if (!empty($article['citizen_reporter_name']) || !empty($article['image_credit'])): ?>
    <?php $_reporterLabel = $article['citizen_reporter_name'] ?? $article['image_credit'] ?? ''; ?>
    <?php if ($_reporterLabel): ?>
    <div class="citizen-reporter-badge" style="display:flex;align-items:center;gap:8px;margin-bottom:8px;padding:8px 12px;background:#FFF7ED;border:1px solid #FED7AA;border-radius:8px;font-size:13px">
      <span style="font-size:20px">📰</span>
      <div>
        <div style="font-weight:700;color:#C0001A;font-size:11px;text-transform:uppercase;letter-spacing:.05em">குடிமக்கள் நிருபர் · Citizen Reporter</div>
        <div style="font-weight:600;color:#1A1A1A"><?= e($_reporterLabel) ?></div>
      </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
    <h1 class="art-title art-headline"><?= e($article['title']) ?></h1>

    <!-- REPORTER/DATE left, FONT SIZE + SHARE right -->
    <div class="art-meta-bar notranslate" translate="no">
      <div class="art-byline">
        <span class="art-byline-author"><?= e($article['contributor_name'] ?: $article['author_name'] ?: 'Reporter') ?></span>
        <?php if (!empty($article['district_name']) || !empty($article['city_name'])): ?>
        <span class="art-byline-sep">·</span>
        <span class="art-byline-location">📍
          <?php if (!empty($article['city_name'])): ?><?= e($article['city_name']) ?><?php endif; ?>
          <?php if (!empty($article['city_name']) && !empty($article['district_name'])): ?>, <?php endif; ?>
          <?php if (!empty($article['district_name'])): ?><?= e($article['district_name']) ?><?php endif; ?>
        </span>
        <?php endif; ?>
        <span class="art-byline-sep">·</span>
        <span class="art-byline-date"><?= Helper::formatDate($article['published_at'], 'd M Y, h:i A') ?></span>
        <?php if (!empty($article['view_count']) && $article['view_count'] > 0): ?>
        <span class="art-byline-sep">·</span>
        <span class="art-byline-detail">👁 <?= number_format($article['view_count']) ?></span>
        <?php endif; ?>
        <?php if (!empty($ratingStats['total']) && $ratingStats['total'] > 0): ?>
        <span class="art-byline-sep">·</span>
        <span class="art-byline-detail">⭐ <?= number_format((float)$ratingStats['average'], 1) ?></span>
        <?php endif; ?>
      </div>
      <div class="font-size-toggle">
        <button class="font-btn" onclick="setFont('sm')" id="fsm">A-</button>
        <button class="font-btn active" onclick="setFont('md')" id="fmd">A</button>
        <button class="font-btn" onclick="setFont('lg')" id="flg">A+</button>
        <button class="font-btn" onclick="setFont('xl')" id="fxl">A++</button>
      </div>
      <div class="art-share-inline">
        <a href="https://wa.me/?text=<?= $whatsappMsg ?>" target="_blank" rel="noopener" class="sbc sbc-wa" onclick="trackWA()" title="Share on WhatsApp">
          <i class="bi bi-whatsapp"></i><span>WhatsApp</span>
        </a>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $_fbUrl ?>" target="_blank" rel="noopener" class="sbc sbc-fb" title="Share on Facebook">
          <i class="bi bi-facebook"></i><span>Facebook</span>
        </a>
        <a href="https://twitter.com/intent/tweet?text=<?= $_twText ?>" target="_blank" rel="noopener" class="sbc sbc-tw" title="Share on X">
          <i class="bi bi-twitter-x"></i><span>X</span>
        </a>
        <button class="sbc sbc-cp" data-url="<?= Helper::e($_shortUrl) ?>" data-title="<?= Helper::e($article['title']) ?>" onclick="copyShortLink(this)" title="Copy short link">
          <i class="bi bi-link-45deg"></i><span>Copy Link</span>
        </button>
      </div>
    </div><!-- /art-meta-bar -->

    <!-- HERO IMAGE or VIDEO -->
    <?php if ($isVideo): ?>
    <div class="video-embed-wrap">
      <iframe src="https://www.youtube.com/embed/<?= e($article['youtube_video_id']) ?>?rel=0"
              title="<?= e($article['title']) ?>"
              frameborder="0" allowfullscreen loading="lazy"></iframe>
    </div>
    <?php elseif (!empty($article['image_url'])): ?>
    <div class="art-image-col">
      <img src="<?= ($article['image_url'] ?? '' ? rtrim(ASSET_URL,'/').'/public/'.ltrim($article['image_url'],'/')  : '') ?>" alt="<?= e($article['title']) ?>"
           class="art-hero-img" loading="eager" onerror="this.closest('.art-image-col').remove()">
    </div>
    <?php endif; ?>

    <!-- IN-ARTICLE AD (after image) -->
    <?php if (!empty($ads['in_article_1']['ad_code'])): ?>
    <div class="ad-slot ad-inarticle"><?= $ads['in_article_1']['ad_code'] ?></div>
    <?php endif; ?>


    <!-- PREMIUM GATE -->
    <?php if (!empty($isPremiumLocked)): ?>
    <div class="premium-gate">
      <div class="premium-gate-icon">🔒</div>
      <div class="premium-gate-title">Premium Article</div>
      <div class="premium-gate-sub">இந்த செய்தியை படிக்க உள்நுழைக அல்லது premium சந்தா எடுக்கவும்</div>
      <div class="premium-gate-actions">
        <a href="<?= $r ?>/auth/reader/login?return=<?= urlencode($_SERVER['REQUEST_URI']) ?>"
           class="premium-gate-btn-primary">
          <svg width="18" height="18" viewBox="0 0 18 18"><path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.875 2.684-6.615z" fill="#4285F4"/><path d="M9 18c2.43 0 4.467-.806 5.956-2.18l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 009 18z" fill="#34A853"/><path d="M3.964 10.71A5.41 5.41 0 013.682 9c0-.593.102-1.17.282-1.71V4.958H.957A8.996 8.996 0 000 9c0 1.452.348 2.827.957 4.042l3.007-2.332z" fill="#FBBC05"/><path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 00.957 4.958L3.964 6.29C4.672 4.163 6.656 3.58 9 3.58z" fill="#EA4335"/></svg>
          Google மூலம் உள்நுழைக
        </a>
      </div>
      <div class="premium-gate-note">Already a subscriber? <a href="<?= $r ?>/auth/reader/login?return=<?= urlencode($_SERVER['REQUEST_URI']) ?>">Sign in</a></div>
    </div>

    <!-- BLURRED CONTENT PREVIEW -->
    <div class="premium-blur-wrap">
      <div class="art-body premium-blurred">
        <?= $article['content'] ?>
      </div>
    </div>

    <?php else: ?>
    <!-- FULL CONTENT -->
    <div class="art-body" translate="yes">
      <?php
      // Split content at midpoint for mobile mid-article ad
      $_content = $article['content'] ?? '';
      // Split at ~50% — try </p>, then </div>, then hard split
      $_half  = (int)(mb_strlen($_content) / 2);
      $_split = false;
      foreach (['</p>', '</div>', '. ', '。'] as $_tag) {
          $_pos = mb_strpos($_content, $_tag, $_half);
          if ($_pos !== false && $_pos < mb_strlen($_content) - 100) {
              $_split = $_pos + mb_strlen($_tag);
              break;
          }
      }
      if ($_split) {
          $_part1 = mb_substr($_content, 0, $_split);
          $_part2 = mb_substr($_content, $_split);
      } else {
          $_part1 = $_content;
          $_part2 = '';
      }
      ?>
      <?= $_part1 ?>
      <?php if ($_part2): ?>
      <!-- Mid-article ad (mobile only) -->
      <div class="mid-article-ad mob-only-ad" id="midArticleAd">
        <div class="ad-rotator" data-slot="square_a" data-cat="<?= $article['category_id'] ?? 0 ?>"></div>
        <div class="mid-ad-label">Advertisement</div>
      </div>
      <?= $_part2 ?>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- TAGS — always shown, category as fallback -->
    <div class="art-tags">
      <span class="art-tags-label">🏷️</span>
      <!-- Category always as first tag -->
      <a href="<?= $r ?>/tamil-news/<?= e($article['category_slug']??'') ?>" class="art-tag art-tag-cat">
        <?= e($article['category_tamil'] ?: $article['category_name']) ?>
      </a>
      <?php if (!empty($article['additional_category_slug'])): ?>
      <a href="<?= $r ?>/tamil-news/<?= e($article['additional_category_slug']) ?>" class="art-tag">
        <?= e($article['additional_category_tamil'] ?: $article['additional_category_name']) ?>
      </a>
      <?php endif; ?>
      <?php foreach ($tags as $tag): ?>
      <a href="<?= $r ?>/search?q=<?= urlencode($tag) ?>" class="art-tag"><?= e($tag) ?></a>
      <?php endforeach; ?>
    </div>


    <!-- Horizontal ad between article and related news -->
    <div class="art-between-ad notranslate" translate="no">
      <div class="ad-rotator" data-slot="horizontal" data-cat="<?= $article['category_id'] ?? 0 ?>"></div>
    </div>

    <!-- RELATED ARTICLES -->
    <?php if (!empty($related)): ?>
    <div class="related-section">
      <div class="sec-head sec-head-mt">
        <span class="sec-head-bar-dyn" style="--ac:#C0001A"></span>
        <span class="sec-head-title">தொடர்புடைய செய்திகள்</span>
        <span class="sec-head-ta">Related News</span>
      </div>
      <div class="g4">
        <?php foreach (array_slice($related, 0, 3) as $_ri => $ri):
          $hasImg = !empty($ri['image_url']); ?>
        <a href="<?= $r ?>/article/<?= e($ri['slug']) ?>"
           class="nc <?= $hasImg ? '' : 'nc-no-img' ?>">
          <?php if ($hasImg): ?>
          <img src="<?= ( ($ri['thumb_url'] ?: ($ri['image_url'] ?? '')) ? rtrim(ASSET_URL,'/').'/public/'.ltrim($ri['thumb_url'] ?: $ri['image_url'] ,'/')  : '') ?>" alt="<?= e($ri['title']) ?>" loading="lazy" onerror="this.remove()">
          <?php endif; ?>
          <div class="nc-body">
            <span class="ctag"><?= e($ri['category_tamil'] ?: $ri['category_name']) ?></span>
            <div class="nc-title <?= $hasImg ? '' : 'nc-title-lg' ?>"><?= e($ri['title']) ?></div>
            <?php if (!$hasImg && !empty($ri['excerpt'])): ?>
            <div class="nc-no-img-excerpt"><?= e(mb_substr(strip_tags($ri['excerpt']),0,140)) ?></div>
            <?php endif; ?>
            <div class="hero4-meta notranslate" translate="no"><?= Helper::timeAgo($ri['published_at']) ?></div>
          </div>
        </a>
        <?php endforeach; ?>
        <!-- 4th slot: in-feed ad -->
        <div class="nc nc-ad notranslate" translate="no">
          <span class="nc-ad-label">Ad</span>
          <div class="ad-rotator" data-ad-pool="square"></div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- RATING & REVIEW SECTION -->
    <div class="rating-section" id="ratingSection">
      <div class="rating-section-head">
        <div class="rating-section-title">மதிப்பீடு மற்றும் கருத்துகள்</div>
        <div class="rating-section-sub">இந்த செய்தி உங்களுக்கு எவ்வளவு பயனுள்ளதாக இருந்தது?</div>
      </div>

      <!-- OVERALL RATING -->
      <?php if ($ratingStats['total'] > 0): ?>
      <div class="rating-overall">
        <div class="rating-big-num"><?= number_format((float)$ratingStats['average'], 1) ?></div>
        <div>
          <div class="overall-stars" id="overallStars">
            <?php for ($i = 1; $i <= 5; $i++): ?>
            <span style="color:<?= $i <= round($ratingStats['average']) ? '#E8A000' : '#D8D6CE' ?>;font-size:20px">★</span>
            <?php endfor; ?>
          </div>
          <div style="font-size:12px;color:var(--gray-4)"><?= number_format($ratingStats['total']) ?> மதிப்பீடுகள்</div>
        </div>
        <div class="rating-bars" id="ratingBars">
          <?php
          $barLabels = [5, 4, 3, 2, 1];
          $barFields = ['five', 'four', 'three', 'two', 'one'];
          $total     = $ratingStats['total'] ?: 1;
          foreach ($barLabels as $bi => $bv):
            $cnt = (int)($ratingStats[$barFields[$bi]] ?? 0);
            $pct = round($cnt / $total * 100);
          ?>
          <div class="rating-bar-row">
            <div class="rating-bar-label"><?= $bv ?>★</div>
            <div class="rating-bar-track"><div class="rating-bar-fill" style="width:<?= $pct ?>%"></div></div>
            <div class="rating-bar-count"><?= $cnt ?></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- WRITE REVIEW -->
      <?php if ($readerId): ?>
      <?php if ($userRating > 0): ?>
      <!-- Already rated — show thank-you card -->
      <div class="review-form" id="reviewForm" style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:10px;padding:16px;text-align:center">
        <div style="font-size:28px;margin-bottom:6px">✅</div>
        <div style="font-weight:700;color:#065F46;margin-bottom:4px">நீங்கள் மதிப்பிட்டுவிட்டீர்கள்!</div>
        <div style="font-size:13px;color:#6B7280;margin-bottom:8px">
          உங்கள் மதிப்பீடு:
          <?php for ($i=1;$i<=5;$i++): ?>
          <span style="color:<?= $i<=$userRating?'#E8A000':'#D8D6CE' ?>;font-size:18px">★</span>
          <?php endfor; ?>
          <?= ['','மிகவும் மோசம்','மோசம்','சராசரி','நல்லது','மிகவும் நல்லது'][$userRating] ?>
        </div>
        <?php if (!empty($userReview)): ?>
        <div style="font-size:13px;color:#374151;font-style:italic;background:#fff;padding:8px 12px;border-radius:6px;text-align:left">
          "<?= htmlspecialchars($userReview) ?>"
        </div>
        <?php endif; ?>
      </div>
      <?php else: ?>
      <div class="review-form" id="reviewForm">
        <div class="review-form-title">உங்கள் மதிப்பீட்டை சமர்ப்பிக்கவும்</div>
        <div class="star-picker-row">
          <?php for ($i = 1; $i <= 5; $i++): ?>
          <span class="star-pick <?= $i <= $userRating ? 'active' : '' ?>"
                role="radio" aria-label="<?= $i ?> star<?= $i > 1 ? 's' : '' ?>"
                aria-checked="<?= $i <= $userRating ? 'true' : 'false' ?>"
                tabindex="0" data-val="<?= $i ?>" onclick="pickStar(<?= $i ?>)"
                onkeydown="if(event.key==='Enter'||event.key===' ')pickStar(<?= $i ?>)">★</span>
          <?php endfor; ?>
          <span class="star-label" id="starLabel">
            <?= $userRating > 0 ? ['','மிகவும் மோசம்','மோசம்','சராசரி','நல்லது','மிகவும் நல்லது'][$userRating] : 'மதிப்பிடவும்' ?>
          </span>
        </div>
        <textarea id="reviewText" class="review-textarea" aria-label="Write your review"
                  rows="3" maxlength="500"
                  placeholder="உங்கள் கருத்தை பகிர்ந்து கொள்ளுங்கள்... (optional)"
                  onkeyup="updateCount()"></textarea>
        <div class="review-form-footer">
          <span class="char-count"><span id="charCount">0</span>/500</span>
          <button class="review-submit-btn" id="submitBtn"
                  onclick="submitReview(<?= $article['id'] ?>)"
                  <?= $userRating === 0 ? 'disabled' : '' ?>>
            சமர்ப்பி
          </button>
        </div>
      </div>
      <?php endif; ?>
      <?php else: ?>
      <div class="login-prompt-box" id="loginPromptBox">
        <div class="login-prompt-icon">⭐</div>
        <div class="login-prompt-title">மதிப்பிட உள்நுழைக</div>
        <div class="login-prompt-sub">Google மூலம் உள்நுழைந்து உங்கள் கருத்தை பகிரவும்</div>
        <button class="login-prompt-btn" onclick="openModal()">
          Google மூலம் உள்நுழைக
        </button>
      </div>
      <?php endif; ?>

      <!-- REVIEWS LIST -->
      <?php if (!empty($reviews)): ?>
      <div class="reviews-list-head">
        <span id="reviewCount"><?= count($reviews) ?> கருத்துகள்</span>
      </div>
      <div id="reviewsList">
        <?php foreach ($reviews as $rv): ?>
        <div class="review-card">
          <div class="review-card-top">
            <?php if ($rv['reader_avatar']): ?>
            <img src="<?= e($rv['reader_avatar']) ?>" class="reviewer-avatar" alt="<?= e($rv['reader_name'] ?? 'Reader') ?>'s avatar">
            <?php else: ?>
            <div class="reviewer-avatar" style="background:var(--charcoal);color:white;display:flex;align-items:center;justify-content:center;font-weight:600">
              <?= strtoupper(substr($rv['reader_name'] ?? 'R', 0, 1)) ?>
            </div>
            <?php endif; ?>
            <div class="reviewer-info">
              <div class="reviewer-name"><?= e($rv['reader_name'] ?? 'Reader') ?></div>
              <div class="reviewer-meta">
                <div class="reviewer-stars">
                  <?php for ($i = 1; $i <= 5; $i++): ?>
                  <span class="reviewer-star <?= $i <= $rv['rating'] ? 'on' : '' ?>">★</span>
                  <?php endfor; ?>
                </div>
                <span><?= Helper::timeAgo($rv['created_at']) ?></span>
              </div>
            </div>
          </div>
          <?php if ($rv['review']): ?>
          <div class="review-text"><?= e($rv['review']) ?></div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>



<script>
window._baseUrl = '<?= $r ?>';
const ARTICLE_ID = <?= (int)$article['id'] ?>;
const CSRF_TOKEN = '<?= e($csrf) ?>';
let selectedStar = <?= (int)$userRating ?>;
const starLabels = ['','மிகவும் மோசம்','மோசம்','சராசரி','நல்லது','மிகவும் நல்லது'];

function pickStar(val) {
  selectedStar = val;
  document.querySelectorAll('.star-pick').forEach((s,i) => s.classList.toggle('active', i < val));
  const lbl = document.getElementById('starLabel');
  if (lbl) lbl.textContent = starLabels[val];
  const btn = document.getElementById('submitBtn');
  if (btn) btn.disabled = false;
}

function updateCount() {
  const len = document.getElementById('reviewText')?.value.length || 0;
  const el  = document.getElementById('charCount');
  if (el) el.textContent = len;
}

async function submitReview(articleId) {
  if (!selectedStar) { showToast('முதலில் ஒரு நட்சத்திரத்தை தேர்வு செய்யுங்கள்.'); return; }
  const review = document.getElementById('reviewText')?.value || '';
  const btn    = document.getElementById('submitBtn');
  if (btn) { btn.disabled = true; btn.textContent = 'சமர்ப்பிக்கிறது...'; }

  const fd = new FormData();
  fd.append('article_id', articleId);
  fd.append('rating',     selectedStar);
  fd.append('review',     review);
  fd.append('_token',     CSRF_TOKEN);

  try {
    const res  = await fetch(window._baseUrl + '/api/rate', { method: 'POST', body: fd });
    let data;
    try { data = await res.json(); }
    catch { data = { error: 'Server error. Please refresh the page and try again.' }; }

    if (data.success) {
      showToast('✓ உங்கள் மதிப்பீடு சமர்ப்பிக்கப்பட்டது! நன்றி.');
      if (btn) { btn.textContent = '✓ சமர்ப்பிக்கப்பட்டது'; btn.disabled = true; }
      // Refresh rating stats display after 1s
      setTimeout(() => location.reload(), 1200);
    } else if (data.redirect) {
      showToast('உள்நுழையவும் — திருப்பி அனுப்புகிறோம்...');
      setTimeout(() => { window.location = window._baseUrl + data.redirect; }, 1500);
    } else {
      showToast(data.error || 'ஏதோ தவறு நடந்தது. மீண்டும் முயற்சிக்கவும்.');
      if (btn) { btn.disabled = false; btn.textContent = 'சமர்ப்பி'; }
    }
  } catch(e) {
    showToast('நெட்வொர்க் பிழை. மீண்டும் முயற்சிக்கவும்.');
    if (btn) { btn.disabled = false; btn.textContent = 'சமர்ப்பி'; }
  }
}

function goBackSmart() {
  const cameFromSite = document.referrer && document.referrer.indexOf(window.location.origin) === 0;
  if (cameFromSite && window.history.length > 1) {
    window.history.back();
  } else {
    window.location = window._baseUrl + '/';
  }
}

function trackWA() {
  fetch(window._baseUrl + '/api/rate', { method: 'POST', body: new URLSearchParams({ article_id: ARTICLE_ID, _token: CSRF_TOKEN, action: 'whatsapp' }) }).catch(()=>{});
}

function copyLink() {
  navigator.clipboard?.writeText(window.location.href).then(() => showToast('✓ Link copied!'));
}

// Star picker hover
document.querySelectorAll('.star-pick').forEach(s => {
  s.addEventListener('mouseover', () => {
    const v = parseInt(s.dataset.val);
    document.querySelectorAll('.star-pick').forEach((x,i) => x.style.color = i<v ? 'var(--star-on)' : 'var(--star-off)');
  });
  s.addEventListener('mouseout', () => {
    document.querySelectorAll('.star-pick').forEach((x,i) => x.style.color = i<selectedStar ? 'var(--star-on)' : 'var(--star-off)');
  });
});

// ── FONT SIZE TOGGLE ──────────────────────────────────
function setFont(size) {
  const body = document.querySelector('.art-body');
  if (!body) return;
  ['sm','md','lg','xl'].forEach(s => {
    body.classList.remove('art-body-' + s);
    document.getElementById('f' + s)?.classList.remove('active');
  });
  body.classList.add('art-body-' + size);
  document.getElementById('f' + size)?.classList.add('active');
  localStorage.setItem('tn_font_size', size);
}
// Restore saved preference
(function() {
  const saved = localStorage.getItem('tn_font_size');
  if (saved) setFont(saved);
})();
</script>
<script>
window.dataLayer = window.dataLayer || [];
window.dataLayer.push({
  event: 'article_view',
  page_type: 'article',
  article_id: <?= (int)($article['id'] ?? 0) ?>,
  article_title: <?= json_encode($article['title'] ?? '') ?>,
  article_slug: <?= json_encode($article['slug'] ?? '') ?>,
  category_id: <?= (int)($article['category_id'] ?? 0) ?>,
  category_name: <?= json_encode($article['category_name'] ?? '') ?>,
  category_slug: <?= json_encode($article['category_slug'] ?? '') ?>,
  author_id: <?= (int)($article['user_id'] ?? 0) ?>,
  contributor_id: <?= (int)($article['contributor_id'] ?? 0) ?>,
  district_id: <?= (int)($article['district_id'] ?? 0) ?>,
  content_type: <?= json_encode($article['content_type'] ?? 'standard') ?>,
  read_time: <?= (int)($article['read_time'] ?? 0) ?>,
  published_at: <?= json_encode($article['published_at'] ?? '') ?>,
  view_count: <?= (int)($article['view_count'] ?? 0) ?>,
  is_breaking: <?= !empty($article['is_breaking']) ? 'true' : 'false' ?>,
  is_featured: <?= !empty($article['is_featured']) ? 'true' : 'false' ?>,
  is_editors_pick: <?= !empty($article['is_editors_pick']) ? 'true' : 'false' ?>,
  is_premium: <?= !empty($article['is_premium']) ? 'true' : 'false' ?>,
  is_sponsored: <?= ($article['content_type'] ?? '') === 'sponsored' ? 'true' : 'false' ?>
});
</script>
<script>
// Track view
fetch('<?= rtrim(BASE_URL,"/") ?>/public/api/article/track-view/<?= (int)$article["id"] ?>', {method:"POST", headers:{"X-CSRF-TOKEN":"<?= \App\Core\CSRF::token() ?>"}}).catch(function(){});
</script>
