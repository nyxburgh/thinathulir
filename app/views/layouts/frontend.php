<!DOCTYPE html>
<html lang="ta">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="தினத்துளிர்">
<meta name="theme-color" content="#C0001A">
<meta name="format-detection" content="telephone=no">
    
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-KT3LX29G');</script>
<!-- End Google Tag Manager -->
<?php
// ── Site visit counter — fires once per page load, fails silently ──
try { (new \App\Models\SiteCounterModel())->increment(); } catch (\Exception $e) {}
$_siteViews = 0;
try { $_siteViews = (new \App\Models\SiteCounterModel())->get(); } catch (\Exception $e) {}

// ── All SEO/meta variables resolved FIRST, before any tag uses them ──
$_siteUrl   = \App\Core\Helper::siteUrl();
$_siteName  = $siteName ?? 'தினத்துளிர்';
$_metaTitle = $metaTitle ?? $_siteName;
$_metaDesc  = $metaDesc  ?? '';
$_canonical = $canonical ?? $_siteUrl . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$_ogImage   = \App\Core\Helper::shareImageUrl($ogImage ?? null);
$_robotsContent = $robotsContent ?? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';

// Title uses transliterated Unicode name — not Tamil script, not direct meaning
// "Thinathulir" is the romanised Unicode form understood across search engines
$_seoName = 'Thinathulir';
if (!str_contains($_metaTitle, $_seoName)) {
    $_metaTitle = $_metaTitle . ' | ' . $_seoName;
}
if ($_siteName && !str_contains($_metaDesc, $_siteName) && !str_contains($_metaDesc, $_seoName)) {
    $_metaDesc = ($_metaDesc !== '' ? $_metaDesc . ' - ' : '') . $_seoName;
}

// Fallback OG image — site logo or default share card
$_ogDesc = $_metaDesc;

// Phase 3: Resolve district from session → cookie
$_userDistrictId = null;
if (!empty($_SESSION['tn_district_id'])) {
    $_userDistrictId = (int)$_SESSION['tn_district_id'];
} elseif (!empty($_COOKIE['tn_district_id'])) {
    $_userDistrictId = (int)$_COOKIE['tn_district_id'];
    $_SESSION['tn_district_id'] = $_userDistrictId;
}

// Load ad images — Phase 4: district-aware with global fallback
$_adSquare     = [];
$_adHorizontal = [];
$_adVertical   = [];
try {
    $_adModel      = new \App\Models\BusinessAdModel();
    $_adSquare     = $_adModel->activeForRotation('square',     $categoryId ?? 0, $_userDistrictId);
    $_adHorizontal = $_adModel->activeForRotation('horizontal', $categoryId ?? 0, $_userDistrictId);
    $_adVertical   = $_adModel->activeForRotation('vertical',   $categoryId ?? 0, $_userDistrictId);
} catch (\Exception $e) {}

// Sidebar data — self-load when not passed by controller (article pages, category pages etc.)
if (empty($trending) || empty($breaking) || empty($editorsPick)) {
    try {
        $_sideModel = new \App\Models\FrontendArticleModel();
        if (empty($trending))    $trending    = $_sideModel->trending(5);
        if (empty($breaking))    $breaking    = $_sideModel->breaking(8);
        if (empty($editorsPick)) $editorsPick = $_sideModel->editorsPicks(3);
    } catch (\Exception $e) {}
}

$_isArticle = isset($article) && !empty($article);
$_pubDate   = $_isArticle ? $article['published_at'] ?? '' : '';
$_modDate   = $_isArticle ? $article['updated_at']   ?? $article['published_at'] ?? '' : '';
$_author    = $_isArticle ? ($article['contributor_name'] ?: $article['author_name'] ?? $_siteName) : $_siteName;
$_keywords  = $_isArticle ? ($article['tag_names'] ?? '') : '';
$_keywords  = str_replace('||', ', ', $_keywords);
?>
<link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>/public/favicon.svg">
<link rel="apple-touch-icon" href="<?= ASSET_URL ?>/public/assets/img/logo-192.png">
<title><?= htmlspecialchars($_metaTitle) ?></title>
<meta name="description" content="<?= htmlspecialchars($_metaDesc) ?>">
<link rel="canonical" href="<?= htmlspecialchars($_canonical) ?>">
<meta property="og:url" content="<?= htmlspecialchars($_canonical) ?>">
<meta property="og:type"        content="<?= isset($article) && !empty($article) ? 'article' : 'website' ?>">
<meta property="og:title"       content="<?= htmlspecialchars($_metaTitle) ?>">
<meta property="og:description" content="<?= htmlspecialchars($_ogDesc) ?>">
<?php
// Detect real image mime/dimensions so FB/WhatsApp crawlers don't reject a
// mismatched declaration (images are now saved as WebP, not jpeg).
// Find the "/public/" marker in the URL path and resolve everything after it
// against the real public/ folder — avoids assuming a fixed subfolder depth.
$_ogImgUrlPath = parse_url($_ogImage, PHP_URL_PATH) ?? '';
$_ogImgRelative = '';
if (($_ogImgPubPos = strpos($_ogImgUrlPath, '/public/')) !== false) {
    $_ogImgRelative = substr($_ogImgUrlPath, $_ogImgPubPos + strlen('/public/'));
}
$_ogImgFile = (defined('ROOT_PATH') ? ROOT_PATH : dirname(__DIR__,3)) . '/public/' . $_ogImgRelative;
$_ogW = 1200; $_ogH = 630; $_ogMime = 'image/jpeg';
if (is_file($_ogImgFile)) {
    $info = @getimagesize($_ogImgFile);
    if ($info) { $_ogW = $info[0]; $_ogH = $info[1]; $_ogMime = $info['mime'] ?? $_ogMime; }

    // WhatsApp's link-preview crawler silently drops images that are too
    // heavy or too far from landscape — fall back to the small site logo
    // rather than showing no image at all.
    $_ogTooHeavy  = filesize($_ogImgFile) > 600 * 1024;
    $_ogTooTall   = $_ogW > 0 && ($_ogH / $_ogW) > 1.3;
    if ($_ogTooHeavy || $_ogTooTall) {
        $_ogImage = \App\Core\Helper::shareImageUrl(null);
        $_ogW = 1200; $_ogH = 630; $_ogMime = 'image/jpeg';
    }
}
?>
<meta property="og:image"       content="<?= htmlspecialchars($_ogImage) ?>">
<meta property="og:image:width" content="<?= $_ogW ?>">
<meta property="og:image:height" content="<?= $_ogH ?>">
<meta property="og:image:type"  content="<?= htmlspecialchars($_ogMime) ?>">
<meta name="csrf-token"         content="<?= \App\Core\CSRF::token() ?>">
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:site"        content="@thinathulir">
<meta name="twitter:title"       content="<?= htmlspecialchars($_metaTitle) ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($_ogDesc) ?>">
<meta name="twitter:image"       content="<?= htmlspecialchars($_ogImage) ?>">
<meta property="og:site_name" content="<?= htmlspecialchars($_siteName) ?>">
<meta property="og:locale" content="ta_IN">
<meta property="og:locale:alternate" content="en_IN">
<meta property="og:locale:alternate" content="hi_IN">
<?php
// Pagination rel prev/next (set by CategoryController)
if (!empty($paginationPrev)): ?><link rel="prev" href="<?= htmlspecialchars($paginationPrev) ?>"><?php endif; ?>
<?php if (!empty($paginationNext)): ?><link rel="next" href="<?= htmlspecialchars($paginationNext) ?>"><?php endif; ?>
<?php
// hreflang for Tamil (the canonical language)
?>
<link rel="alternate" hreflang="ta" href="<?= htmlspecialchars($_canonical) ?>">
<link rel="alternate" hreflang="x-default" href="<?= htmlspecialchars($_canonical) ?>">
<?php if ($_isArticle): ?>
<meta property="article:published_time" content="<?= htmlspecialchars($_pubDate) ?>">
<meta property="article:modified_time" content="<?= htmlspecialchars($_modDate) ?>">
<meta property="article:author" content="<?= htmlspecialchars($_author) ?>">
<meta property="article:section" content="<?= htmlspecialchars($article['category_name'] ?? '') ?>">
<?php if ($_keywords): ?><meta property="article:tag" content="<?= htmlspecialchars($_keywords) ?>"><?php endif; ?>
<?php endif; ?>
<meta name="keywords" content="<?= $_keywords ? htmlspecialchars($_keywords).', ' : '' ?>தமிழ் செய்தி, Tamil News">
<?php if ($_isArticle): ?>
<meta name="news_keywords" content="<?= htmlspecialchars($_keywords ?: ($article['category_name'] ?? '')) ?>">
<meta name="author" content="<?= htmlspecialchars($_author) ?>">
<?php endif; ?>
<meta name="language" content="Tamil">
<meta http-equiv="content-language" content="ta">
<meta name="robots" content="<?= htmlspecialchars($_robotsContent) ?>">
<?php
// ── Structured data (@graph combines multiple schema objects in one block) ──
$_schemaGraph = [];

// Organization — always present, used as publisher reference across the site
$_schemaGraph[] = [
    '@type' => 'Organization',
    '@id'   => $_siteUrl . '/#organization',
    'name'  => $_siteName,
    'url'   => $_siteUrl . '/',
    'logo'  => ['@type' => 'ImageObject', 'url' => $_ogImage],
];

if ($_isArticle) {
    $_articleSchema = [
        '@type'         => 'NewsArticle',
        'headline'      => $article['title'] ?? '',
        'url'           => $_canonical,
        'datePublished' => $_pubDate,
        'dateModified'  => $_modDate,
        'author'        => ['@type' => 'Person', 'name' => $_author],
        'publisher'     => ['@id' => $_siteUrl . '/#organization'],
        'inLanguage'    => 'ta',
    ];
    if (!empty($_ogImage)) {
        $_articleSchema['image'] = [$_ogImage];
    }
    if (!empty($article['category_tamil']) || !empty($article['category_name'])) {
        $_articleSchema['articleSection'] = $article['category_tamil'] ?: $article['category_name'];
    }
    if (!empty($article['tag_names'])) {
        $_articleSchema['keywords'] = str_replace('||', ', ', $article['tag_names']);
    }
    // Speakable — AEO: lets Google Assistant / AI Overview read key parts
    $_articleSchema['speakable'] = [
        '@type'       => 'SpeakableSpecification',
        'cssSelector' => ['.art-headline', '.art-excerpt', '.art-lede'],
    ];
    // wordCount — AEO signal
    if (!empty($article['content'])) {
        $_articleSchema['wordCount'] = str_word_count(strip_tags($article['content']));
    }
    // isAccessibleForFree — paywall signal
    $_articleSchema['isAccessibleForFree'] = empty($article['is_premium']) ? 'True' : 'False';
    $_schemaGraph[] = $_articleSchema;

    // Breadcrumb: Home > Category > Article
    $_crumbs = [
        ['@type' => 'ListItem', 'position' => 1, 'name' => $_siteName, 'item' => $_siteUrl . '/'],
    ];
    if (!empty($article['category_slug'])) {
        $_crumbs[] = [
            '@type' => 'ListItem', 'position' => 2,
            'name' => $article['category_tamil'] ?: $article['category_name'],
            'item' => $_siteUrl . '/tamil-news/' . $article['category_slug'],
        ];
    }
    $_crumbs[] = ['@type' => 'ListItem', 'position' => count($_crumbs) + 1, 'name' => $article['title'] ?? ''];
    $_schemaGraph[] = ['@type' => 'BreadcrumbList', 'itemListElement' => $_crumbs];
} else {
    $_schemaGraph[] = [
        '@type'          => 'WebSite',
        '@id'            => $_siteUrl . '/#website',
        'name'           => $_siteName,
        'url'            => $_siteUrl . '/',
        'inLanguage'     => 'ta',
        'potentialAction'=> [
            '@type'       => 'SearchAction',
            'target'      => [
                '@type'       => 'EntryPoint',
                'urlTemplate' => $_siteUrl . '/search?q={search_term_string}',
            ],
            'query-input' => 'required name=search_term_string',
        ],
    ];
}
?>
<script type="application/ld+json">
<?= json_encode(['@context' => 'https://schema.org', '@graph' => $_schemaGraph], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://translate.google.com">
<link rel="preconnect" href="https://translate.googleapis.com">
<link rel="dns-prefetch" href="https://translate.google.com">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Noto+Sans+Tamil:wght@400;600;700&family=Oswald:wght@400;500;600;700&family=Source+Sans+3:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="<?= \App\Core\Helper::assetVersioned('/assets/css/frontend.css') ?>">
<link rel="stylesheet" href="<?= \App\Core\Helper::assetVersioned('/assets/css/masthead.css') ?>">
<link rel="stylesheet" href="<?= \App\Core\Helper::assetVersioned('/assets/css/responsive.css') ?>">
</head>
<body>
<a href="#main-content" class="skip-to-main">Skip to main content</a>
<?php
/* ── DATA LAYER — must fire before GTM processes events ── */
$_dlPageType = 'page';
if ($_isArticle)                      $_dlPageType = 'article';
elseif (isset($q) && isset($results)) $_dlPageType = 'search';
elseif (isset($categorySlug))         $_dlPageType = 'category';
elseif (isset($specialCategory))      $_dlPageType = 'special';
elseif (isset($photoNews))            $_dlPageType = 'photo_news';
$_dlUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http')
        . '://' . ($_SERVER['HTTP_HOST'] ?? '') . ($_SERVER['REQUEST_URI'] ?? '/');
?>
<script>
window.dataLayer = window.dataLayer || [];
window.dataLayer.push({
  page_type: <?= json_encode($_dlPageType) ?>,
  page_title: <?= json_encode($pageTitle ?? $metaTitle ?? '') ?>,
  page_url: <?= json_encode($_dlUrl) ?>,
  site_language: 'ta'
});
<?php if ($_isArticle && !empty($article)): ?>
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
  city_id: <?= (int)($article['city_id'] ?? 0) ?>,
  content_type: <?= json_encode($article['content_type'] ?? 'standard') ?>,
  read_time: <?= (int)($article['read_time'] ?? 0) ?>,
  published_at: <?= json_encode($article['published_at'] ?? '') ?>,
  is_breaking: <?= !empty($article['is_breaking']) ? 'true' : 'false' ?>,
  is_featured: <?= !empty($article['is_featured']) ? 'true' : 'false' ?>,
  is_editors_pick: <?= !empty($article['is_editors_pick']) ? 'true' : 'false' ?>,
  is_premium: <?= !empty($article['is_premium']) ? 'true' : 'false' ?>,
  is_sponsored: <?= ($article['content_type'] ?? '') === 'sponsored' ? 'true' : 'false' ?>,
  view_count: <?= (int)($article['view_count'] ?? 0) ?>,
  tag_names: <?= json_encode($article['tag_names'] ?? '', JSON_UNESCAPED_UNICODE) ?>
});
<?php endif; ?>
<?php if (isset($q) && $q !== ''): ?>
window.dataLayer.push({
  event: 'search',
  page_type: 'search',
  search_term: <?= json_encode($q ?? '') ?>,
  search_result_count: <?= (int)($total ?? 0) ?>
});
<?php endif; ?>
<?php if (isset($categorySlug)): ?>
window.dataLayer.push({
  event: 'category_view',
  page_type: 'category',
  category_slug: <?= json_encode($categorySlug ?? '') ?>,
  category_name: <?= json_encode($categoryName ?? '') ?>
});
<?php endif; ?>
</script>
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KT3LX29G"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php
/* ── ALL REQUIRED VARIABLES ── */
$reader         = \App\Core\Session::get('reader');
$siteName       = $siteName ?? 'தினத்துளிர்';
$currentPath    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$breakingTicker = $breaking ?? [];

// Load rates for desktop bar — silent fail if table doesn't exist yet
$_rates = [];
try {
    $_rates = (new \App\Models\RateModel())->allForWidget();
} catch (\Exception $e) {}
$_rateMap = [];
foreach ($_rates as $_rv) { $_rateMap[$_rv['type']] = $_rv; }
// Load nav categories from DB if not provided by controller
if (empty($navCategories)) {
    try {
        $navCategories = (new \App\Models\CategoryModel())->allWithParent();
    } catch (\Exception $_e) { $navCategories = []; }
}
$navCats = $navCategories ?? [];

// Load siteName/siteUrl if not provided
if (empty($siteName)) {
    try {
        $siteName = (new \App\Models\SettingModel())->getValue('site_name','தினத்துளிர்');
        $siteUrl  = (new \App\Models\SettingModel())->getValue('site_url', BASE_URL.'/public');
    } catch (\Exception $_e) {
        $siteName = 'தினத்துளிர்';
        $siteUrl  = BASE_URL . '/public';
    }
}
$assetUrl       = ASSET_URL;
$baseUrl        = BASE_URL;
$r              = rtrim(ASSET_URL, '/') . '/public';

/* Tamil date */
$_days   = ['ஞாயிறு','திங்கள்','செவ்வாய்','புதன்','வியாழன்','வெள்ளி','சனி'];
$_months = ['ஜனவரி','பிப்ரவரி','மார்ச்','ஏப்ரல்','மே','ஜூன்','ஜூலை','ஆகஸ்ட்','செப்டம்பர்','அக்டோபர்','நவம்பர்','டிசம்பர்'];
$tamilDate = $_days[date('w')] . ', ' . date('d') . ' ' . $_months[(int)date('n')-1] . ' ' . date('Y');

/* Ad slots — safe */
$adSquare = $adHorizontal = null;
try {
    $adModel = new \App\Models\AdModel();
    if (method_exists($adModel, 'getSlot')) {
        $adSquare     = $adModel->getSlot('square');
        $adHorizontal = $adModel->getSlot('horizontal');
    }
} catch (\Exception $e) {}

?>

<!-- ═══════════ DESKTOP NAV ═══════════ -->
<?php if (!empty($noNav)): ?>
<!-- Minimal header for ad page -->
<div style="background:#fff;border-bottom:1px solid #F0EFE9;padding:12px 16px;display:flex;align-items:center;justify-content:center;position:sticky;top:0;z-index:200;box-shadow:0 1px 4px rgba(0,0,0,.06);border-bottom:2.5px solid #C0001A">
  <a href="<?= $baseUrl ?>/public" style="text-decoration:none;display:flex;align-items:center;gap:8px">
    <span style="font-family:'Noto Sans Tamil',sans-serif;font-size:18px;font-weight:900;color:#C0001A;letter-spacing:-.5px">தினத்துளிர்</span>
  </a>
</div>
<?php else: ?>
<nav class="nav" aria-label="Main navigation">
  <div class="nav-inner">
    <?php
    $navParents  = array_values(array_filter($navCats, fn($c) => !$c['parent_id']));
    $navPrimary  = array_slice($navParents, 0, 5);
    $navOverflow = array_slice($navParents, 5);
    ?>

    <a href="<?= $baseUrl ?>/public/" class="nav-link <?= $currentPath === parse_url($baseUrl, PHP_URL_PATH).'/public/' ? 'active' : '' ?>">முகப்பு</a>

    <?php foreach ($navPrimary as $cat): ?>
    <a href="<?= $baseUrl ?>/public/tamil-news/<?= htmlspecialchars($cat['slug']) ?>"
       class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], $cat['slug']) ? 'active' : '' ?>">
      <?= htmlspecialchars($cat['name_tamil'] ?: $cat['name']) ?>
    </a>
    <?php endforeach; ?>

    <!-- Fixed items always visible -->
    <a href="<?= $baseUrl ?>/public/tamil-news/technology"
       class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/technology') ? 'active' : '' ?>">தொழில்நுட்பம்</a>
    <a href="<?= $baseUrl ?>/public/tamil-news/spiritual"
       class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/spiritual') ? 'active' : '' ?>">ஆன்மீகம்</a>
    <a href="<?= $baseUrl ?>/public/photo-news"
       class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'photo-news') ? 'active' : '' ?>">பட செய்திகள்</a>

    <script>
    window._navAll = <?= json_encode(array_map(fn($c) => [
        'slug'   => $c['slug'],
        'name'   => $c['name_tamil'] ?: $c['name'],
        'active' => str_contains($_SERVER['REQUEST_URI'], $c['slug'])
    ], $navParents)) ?>;
    window._navBase    = "<?= htmlspecialchars($baseUrl) ?>";
    window._navCurrent = "<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>";
    </script>

    <div class="nav-actions">
      <div class="lang-switcher notranslate" translate="no">
        <button data-lang-btn="ta" class="lang-btn active">தமிழ்</button>
        <button data-lang-btn="en" class="lang-btn">EN</button>
        <button data-lang-btn="hi" class="lang-btn">हि</button>
      </div>
      <!-- Profile OR Login -->
      <?php if ($reader): ?>
      <a href="<?= $baseUrl ?>/public/reader/profile" class="nav-user-wrap" title="<?= htmlspecialchars($reader['name']) ?>" style="text-decoration:none">
        <div class="nav-user-avatar nav-user-init" style="overflow:hidden;padding:0">
          <?php if (!empty($reader['avatar'])): ?>
          <img src="<?= htmlspecialchars($reader['avatar']) ?>?sz=64"
               style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block"
               referrerpolicy="no-referrer" alt="<?= htmlspecialchars(substr($reader['name'],0,1)) ?>">
          <?php else: ?>
          <?= strtoupper(substr($reader['name'],0,1)) ?>
          <?php endif; ?>
        </div>
      </a>
      <?php else: ?>
      <button class="nav-google-btn" data-action="open-modal" title="Sign in with Google">
        <svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
          <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
          <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
          <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        <span>Login</span>
      </button>
      <?php endif; ?>
      <!-- ☰ Menu — ALWAYS visible, opens right drawer -->
      <button class="nav-menu-btn" id="navMenuDrawerBtn" title="Menu">☰</button>
    </div>
  </div>
</nav>
<?php endif; // end noNav else (desktop nav) ?>

<!-- RIGHT NAV DRAWER -->
<div class="nav-drawer-overlay" id="navDrawerOverlay" data-action="close-nav-drawer"></div>
<div class="nav-drawer" id="navDrawer">
  <div class="nav-drawer-head">
    <span style="font-family:'Noto Sans Tamil',sans-serif;font-weight:800;font-size:16px;color:#1A1A1A">தினத்துளிர்</span>
    <button data-action="close-nav-drawer" class="nav-drawer-close-btn" aria-label="Close navigation">✕</button>
  </div>
  <div class="nav-drawer-body">

    <!-- All nav categories (populated by JS) -->
    <a href="<?= $baseUrl ?>/public/" class="nav-drawer-link">🏠 முகப்பு</a>
    <div id="navDrawerCats"></div>
    <a href="<?= $baseUrl ?>/public/photo-news" class="nav-drawer-link">📸 பட செய்திகள்</a>
    <a href="<?= $baseUrl ?>/public/special-articles" class="nav-drawer-link">✍️ சிறப்புக் கட்டுரைகள்</a>
    <a href="<?= $baseUrl ?>/public/citizen-reporter" class="nav-drawer-link">📢 குடிமக்கள் நிருபர்</a>
    <a href="<?= $baseUrl ?>/public/search" class="nav-drawer-link">🔍 தேடல்</a>

    <!-- Account -->
    <div class="nav-drawer-section">Account</div>
    <?php if ($reader): ?>
    <a href="<?= $baseUrl ?>/public/reader/profile" class="nav-drawer-link">👤 My Profile</a>
    <a href="<?= $baseUrl ?>/public/auth/reader/logout" class="nav-drawer-link" style="color:#C0001A">🚪 Logout</a>
    <?php else: ?>
    <div class="nav-drawer-link" data-action="open-modal" style="cursor:pointer">
      <svg width="16" height="16" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:6px"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
      Google மூலம் உள்நுழைக
    </div>
    <?php endif; ?>

  </div>
</div>

<!-- ═══════════ MOBILE STICKY TOP BAR (hidden on desktop) ═══════════ -->
<div class="mob-topbar notranslate" translate="no">
  <div class="mob-topbar-logo-row">
    <a href="<?= $baseUrl ?>/public/" class="mob-topbar-logo">
      <span class="mob-logo-w1">தினத்</span><span class="mob-logo-w2">துளிர்</span>
    </a>
  </div>
  <div class="mob-topbar-meta-row">
    <span class="mob-topbar-date"><?= $tamilDate ?></span>
  </div>
  <div class="mob-topbar-lang-row">
    <div class="lang-switcher lang-switcher-mob notranslate" translate="no">
      <button data-lang-btn="ta" class="lang-btn active">தமிழ்</button>
      <button data-lang-btn="en" class="lang-btn">EN</button>
      <button data-lang-btn="hi" class="lang-btn">हि</button>
    </div>
    <div class="mob-weather-badge" id="mobWeatherBadge">
      <span class="mob-weather-city" id="mobWeatherBadgeCity"></span>
      <span class="mob-weather-bottom">
        <span class="mob-weather-icon">🌤️</span>
        <span class="mob-weather-temp" id="mobWeatherBadgeTemp">—°C</span>
      </span>
    </div>
  </div>
</div>

<!-- ═══════════ MASTHEAD HEADER (white: ad | logo | ad) ═══════════ -->
<?php if (empty($noNav)): ?>
<header class="header" role="banner">
  <canvas id="headerCanvas"></canvas>

  <!-- DESKTOP: [square ad] [logo+tagline+date] [square ad] -->
  <div class="masthead notranslate" translate="no">
    <div class="masthead-ad">
      <?php if (empty($noAds)): ?>
      <div class="ad-rotator" data-slot="square_a" data-cat="<?= $categoryId ?? 0 ?>"></div>
      <?php endif; ?>
    </div>
    <div class="masthead-center">
      <a href="<?= $baseUrl ?>/public/" class="vel-logo-link">
        <div class="vel-brand-wrap">
          <div>
            <div class="vel-logo"><span class="vel-word1">தினத்</span><span class="vel-word2">துளிர்</span></div>
            <div class="vel-tagline">அரசியல் பழகு &nbsp;·&nbsp; அறம் செய்</div>
            <div class="masthead-sub-line"><?= $tamilDate ?> &nbsp;|&nbsp; <?= date('d F Y') ?></div>
          </div>
        </div>
      </a>
      <?php if (empty($noAds)): ?>
      <div class="masthead-rates notranslate" translate="no">
        <?php if (!empty($_rateMap['gold'])): ?><span class="mrate-item"><span class="mrate-item-top"><span class="mrate-icon">🥇</span><span class="mrate-label">Gold</span></span><span class="mrate-val">₹<?= number_format((float)$_rateMap['gold']['value'], 0) ?></span></span><?php endif; ?>
        <?php if (!empty($_rateMap['silver'])): ?><span class="mrate-item"><span class="mrate-item-top"><span class="mrate-icon">🥈</span><span class="mrate-label">Silver</span></span><span class="mrate-val">₹<?= number_format((float)$_rateMap['silver']['value'], 2) ?></span></span><?php endif; ?>
        <?php if (!empty($_rateMap['petrol'])): ?><span class="mrate-item"><span class="mrate-item-top"><span class="mrate-icon">⛽</span><span class="mrate-label">Petrol</span></span><span class="mrate-val">₹<?= number_format((float)$_rateMap['petrol']['value'], 2) ?></span></span><?php endif; ?>
        <?php if (!empty($_rateMap['diesel'])): ?><span class="mrate-item"><span class="mrate-item-top"><span class="mrate-icon">🛢️</span><span class="mrate-label">Diesel</span></span><span class="mrate-val">₹<?= number_format((float)$_rateMap['diesel']['value'], 2) ?></span></span><?php endif; ?>
        <span class="mrate-item mrate-weather"><span class="mrate-city" id="desktopWeatherCity"></span><span class="mrate-item-top"><span class="mrate-icon">🌤️</span><span class="mrate-val" id="desktopWeatherVal">—°C</span></span></span>
      </div>
      <?php endif; ?>
    </div>
    <div class="masthead-ad">
      <?php if (empty($noAds)): ?>
      <div class="ad-rotator" data-slot="square_b" data-cat="<?= $categoryId ?? 0 ?>"></div>
      <?php endif; ?>
    </div>
  </div>

  <!-- DESKTOP: double rule -->
  <div class="masthead-rule"></div>
  <div class="masthead-rule-thin"></div>

  <!-- BREAKING TICKER -->  <!-- BREAKING TICKER -->
  <?php if (!empty($breakingTicker) && empty($noBreaking)): ?>
  <div class="ticker-bar">
    <div class="ticker-label"><span class="ticker-dot"></span>BREAKING</div>
    <div class="ticker-track">
      <div class="ticker-inner" id="tickerInner">
        <?php foreach (array_merge($breakingTicker, $breakingTicker) as $b): ?>
        <a href="<?= $baseUrl ?>/public/article/<?= htmlspecialchars($b['slug']) ?>" class="ticker-item"><?= htmlspecialchars($b['title']) ?></a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- DESKTOP: 728×100 banner ad -->
  <?php if (empty($noAds)): ?>
  <div class="header-banner-ad">
    <div class="header-banner-ad-inner"><div class="ad-rotator" data-slot="horizontal" data-cat="<?= $categoryId ?? 0 ?>"></div></div>
  </div>
  <?php endif; ?>

</header>
<?php endif; // end noNav check for masthead ?>

<div class="page-layout-wrap" id="pageLayoutWrap">
<?php if (!empty($noSidebar)): ?>
  <!-- Article page: full width, no sidebar -->
  <main id="main-content" class="page-full"><?= $content ?></main>
<?php else: ?>
  <!-- All other pages: main + sidebar -->
  <div class="page-layout">
    <main id="main-content" class="page-main"><?= $content ?></main>
    <aside class="page-sidebar">
      <!-- Vertical Ad — sticky on all pages -->
      <div class="sb-vertical-ad notranslate" translate="no">
        <div class="ad-rotator" data-slot="vertical" data-cat="<?= $categoryId ?? 0 ?>"></div>
        <div class="sb-ad-label">Advertisement</div>
      </div>

      <?php if (empty($noWidgets)): ?>
      <!-- Breaking -->
      <?php if (!empty($breaking)): ?>
      <div class="sb-widget">
        <div class="sb-widget-head">🔴 Breaking</div>
        <?php foreach (array_slice($breaking,0,5) as $b): ?>
        <a href="<?= $baseUrl ?>/public/article/<?= htmlspecialchars($b['slug']) ?>" class="sb-item">
          <div class="sb-num" style="background:#C0001A;flex-shrink:0">🔴</div>
          <div>
            <div class="sb-title"><?= htmlspecialchars($b['title']) ?></div>
            <div class="sb-meta notranslate" translate="no"><?= \App\Core\Helper::timeAgo($b['published_at']) ?></div>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
      <!-- Trending -->
      <?php if (!empty($trending)): ?>
      <div class="sb-widget">
        <div class="sb-widget-head">🔥 Trending</div>
        <?php foreach ($trending as $i => $t): ?>
        <a href="<?= $baseUrl ?>/public/article/<?= htmlspecialchars($t['slug']) ?>" class="sb-item">
          <div class="sb-num"><?= $i+1 ?></div>
          <div>
            <div class="sb-title"><?= htmlspecialchars($t['title']) ?></div>
            <div class="sb-meta notranslate" translate="no"><?= \App\Core\Helper::timeAgo($t['published_at']) ?></div>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
      <?php endif; ?>
    </aside>
  </div>
<?php endif; ?>
</div>

<!-- FOOTER: copyright only -->
<footer class="site-footer notranslate" translate="no">
  <div class="site-footer-inner">
    © <?= date('Y') ?> தினத்துளிர். All rights reserved.
    <span class="ftr-sep">|</span>
    <a href="<?= $baseUrl ?>/public/about">About Us</a>
    <span class="ftr-sep">|</span>
    <a href="<?= $baseUrl ?>/public/contact">Contact</a>
    <span class="ftr-sep">|</span>
    <a href="<?= $baseUrl ?>/public/ownership">Publisher</a>
    <span class="ftr-sep">|</span>
    <a href="<?= $baseUrl ?>/public/editorial-policy">Editorial Policy</a>
    <span class="ftr-sep">|</span>
    <a href="<?= $baseUrl ?>/public/fact-checking">Fact Checking</a>
    <span class="ftr-sep">|</span>
    <a href="<?= $baseUrl ?>/public/ethics-policy">Ethics</a>
    <span class="ftr-sep">|</span>
    <a href="<?= $baseUrl ?>/public/corrections">Corrections</a>
    <span class="ftr-sep">|</span>
    <a href="<?= $baseUrl ?>/public/privacy">Privacy</a>
    <span class="ftr-sep">|</span>
    <a href="<?= $baseUrl ?>/public/terms">Terms</a>
    <span class="ftr-sep">|</span>
    <a href="<?= $baseUrl ?>/public/advertising-policy">Advertising</a>
    <span class="ftr-sep">|</span>
    <a href="<?= $baseUrl ?>/public/copyright-policy">Copyright</a>
    <span class="ftr-sep">|</span>
    <a href="<?= $baseUrl ?>/public/grievance">Grievance</a>
    <span class="ftr-sep">|</span>
    <a href="<?= $baseUrl ?>/public/ai-content-policy">AI Policy</a>
    <span class="ftr-sep">|</span>
    <a href="<?= $baseUrl ?>/public/disclaimer">Disclaimer</a>
    <span class="ftr-sep">|</span>
    <a href="<?= $baseUrl ?>/public/admin/login">Admin</a>
    <span class="ftr-sep">|</span>
    <a href="<?= $baseUrl ?>/public/contribute/login">Contribute</a>
    <span class="ftr-sep">|</span>
    <span>👁 <?= number_format($_siteViews) ?> Views</span>
  </div>
</footer>

<!-- MOBILE BOTTOM NAV -->

<!-- Rate Popup -->
<div class="mob-rate-popup notranslate" id="mobRatePopup" style="display:none" translate="no">
  <div class="mob-rate-popup-inner">
    <button class="mob-rate-popup-close" data-action="close-rate" aria-label="Close rating">✕</button>
    <div class="mob-rate-popup-icon" id="rateIcon">🥇</div>
    <div class="mob-rate-popup-label" id="rateLabel">Gold Rate</div>
    <div class="mob-rate-popup-value" id="rateValue">Loading...</div>
    <div class="mob-rate-popup-change" id="rateChange"></div>
    <div class="mob-rate-popup-city" id="rateCity"></div>
  </div>
</div>

<!-- MOBILE: Floating horizontal ad above bottom nav -->
<?php if (empty($noNav)): ?>
<div class="mob-footer-ad notranslate" translate="no">
  <div class="ad-rotator" data-slot="horizontal" data-cat="<?= $categoryId ?? 0 ?>"></div>
</div>
<?php endif; ?>

<nav class="mobile-bottom-nav notranslate" translate="no" aria-label="Mobile navigation" <?= !empty($noNav) ? 'style="display:none"' : '' ?>>
  <div class="mobile-bottom-nav-inner">
    <a href="<?= $baseUrl ?>/public/" class="mob-nav-item">
      <div class="mob-nav-icon">🏠</div><div class="mob-nav-label">முகப்பு</div>
    </a>
    <a href="<?= $baseUrl ?>/public/search" class="mob-nav-item">
      <div class="mob-nav-icon">🔍</div><div class="mob-nav-label">தேடல்</div>
    </a>
    <!-- Centre: Google login or user avatar -->
    <?php if ($reader): ?>
    <a href="<?= $baseUrl ?>/public/reader/profile" class="mob-nav-item mob-nav-google-wrap" style="text-decoration:none">
      <div class="mob-nav-google-fab logged" style="padding:0;overflow:hidden">
        <?php if (!empty($reader['avatar'])): ?>
        <img src="<?= htmlspecialchars($reader['avatar']) ?>?sz=96"
             style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block"
             alt="<?= htmlspecialchars(substr($reader['name'],0,1)) ?>"
             referrerpolicy="no-referrer">
        <?php else: ?>
        <?= strtoupper(substr($reader['name'],0,1)) ?>
        <?php endif; ?>
      </div>
    </a>
    <?php else: ?>
    <div class="mob-nav-item mob-nav-google-wrap" data-action="open-modal">
      <div class="mob-nav-google-fab">
        <svg width="20" height="20" viewBox="0 0 24 24">
          <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
          <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
          <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
          <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
      </div>
    </div>
    <?php endif; ?>
    <div class="mob-nav-item" data-action="open-rate-sheet">
      <div class="mob-nav-icon">📊</div><div class="mob-nav-label">விலைகள்</div>
    </div>
    <div class="mob-nav-item" data-action="open-drawer">
      <div class="mob-nav-icon">☰</div><div class="mob-nav-label">மெனு</div>
    </div>
  </div>
</nav>

<!-- Rate bottom sheet -->
<div class="mob-rate-overlay" id="mobRateOverlay" data-action="close-rate-sheet"></div>
<div class="mob-rate-sheet notranslate" id="mobRateSheet" translate="no">
  <div class="mob-rate-sheet-handle"></div>
  <div class="mob-rate-sheet-title">நடப்பு விலைகள்</div>
  <div class="mob-rate-sheet-grid">
    <?php
    $rateCards = [
      'gold'   => ['icon'=>'🥇','label'=>'Gold',   'dec'=>0],
      'silver' => ['icon'=>'🥈','label'=>'Silver', 'dec'=>2],
      'petrol' => ['icon'=>'⛽','label'=>'Petrol', 'dec'=>2],
      'diesel' => ['icon'=>'🛢️','label'=>'Diesel', 'dec'=>2],
    ];
    foreach ($rateCards as $type => $meta):
      $val = !empty($_rateMap[$type]) ? '₹'.number_format((float)$_rateMap[$type]['value'],$meta['dec']) : '—';
    ?>
    <div class="mob-rate-card">
      <span class="mob-rate-card-icon"><?= $meta['icon'] ?></span>
      <span class="mob-rate-card-label"><?= $meta['label'] ?></span>
      <span class="mob-rate-card-val"><?= $val ?></span>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Mobile vertical overlay — home, category, article pages only -->
<?php if (empty($noAds)): ?>
<div id="mobVerticalAd" class="mob-vertical-ad" style="display:none">
  <div class="mob-vertical-ad-inner" style="position:relative">
    <div style="display:flex;justify-content:space-between;align-items:center;padding:4px 8px 4px 12px;background:#F5F5F0;border-bottom:1px solid #E5E7EB">
      <span style="font-size:10px;color:#9CA3AF;font-weight:600">Advertisement</span>
      <div style="display:flex;align-items:center;gap:8px">
        <span id="mobAdCountdown" style="font-size:10px;color:#C0001A;font-weight:700">15</span>
        <button class="mob-vertical-ad-close" id="mobAdCloseBtn" data-action="close-vertical-ad"
                aria-label="Close ad"
                style="background:none;border:none;font-size:16px;color:#6B7280;cursor:pointer;padding:2px 4px;line-height:1;opacity:1;pointer-events:auto">✕</button>
      </div>
    </div>
    <div class="ad-rotator" data-slot="vertical" data-cat="<?= $categoryId ?? 0 ?>"></div>
  </div>
</div>
<?php endif; ?>
<!-- DRAWER OVERLAY -->
<div class="mob-drawer-overlay" id="drawerOverlay" data-action="close-drawer"></div>

<!-- RIGHT DRAWER -->
<div class="mob-drawer" id="mobDrawer">
  <div class="mob-drawer-header notranslate" translate="no">
    <span><span class="mob-logo-w1" style="font-size:18px">தினத்</span><span class="mob-logo-w2" style="font-size:18px">துளிர்</span></span>
    <button class="mob-drawer-close" data-action="close-drawer" aria-label="Close menu">✕</button>
  </div>
  <div class="mob-drawer-body">
    <div class="lang-switcher lang-switcher-drawer notranslate" translate="no">
      <button data-lang-btn="ta" class="lang-btn active">தமிழ்</button>
      <button data-lang-btn="en" class="lang-btn">English</button>
      <button data-lang-btn="hi" class="lang-btn">हिन्दी</button>
    </div>
    <div class="mob-drawer-divider"></div>
    <a href="<?= $baseUrl ?>/public/" class="mob-drawer-link">🏠 முகப்பு</a>
    <?php foreach ($navCats as $cat): ?>
    <?php if ($cat['parent_id']) continue; ?>
    <a href="<?= $baseUrl ?>/public/tamil-news/<?= htmlspecialchars($cat['slug']) ?>" class="mob-drawer-link">
      <?= htmlspecialchars($cat['name_tamil'] ?: $cat['name']) ?>
    </a>
    <?php endforeach; ?>
    <a href="<?= $baseUrl ?>/public/special-articles" class="mob-drawer-link">சிறப்புக் கட்டுரைகள்</a>
    <a href="<?= $baseUrl ?>/public/photo-news" class="mob-drawer-link">📸 பட செய்திகள்</a>
    <div class="mob-drawer-divider"></div>
    <a href="<?= $baseUrl ?>/public/newspaper" class="mob-drawer-link">📰 இ-பேப்பர்</a>
    <a href="<?= $baseUrl ?>/public/search" class="mob-drawer-link">🔍 தேடல்</a>
    <a href="<?= $baseUrl ?>/public/contribute/login" class="mob-drawer-link">✍️ கட்டுரை எழுது</a>
    <a href="<?= $baseUrl ?>/public/info" class="mob-drawer-link">ℹ️ தகவல் மையம்</a>
    <div class="mob-drawer-link notranslate" translate="no" style="cursor:default">👁 <?= number_format($_siteViews) ?> Views</div>
    <div class="mob-drawer-divider"></div>
    <?php if ($reader): ?>
    <div class="mob-drawer-user">👤 <?= htmlspecialchars($reader['name']) ?></div>
    <a href="<?= $baseUrl ?>/public/auth/reader/logout" class="mob-drawer-link">🚪 வெளியேறு</a>
    <?php else: ?>
    <div class="mob-drawer-link" data-action="drawer-open-modal" style="cursor:pointer">🔑 Google மூலம் உள்நுழைக</div>
    <?php endif; ?>
  </div>
</div>

<!-- LOGIN MODAL -->
<div class="modal-overlay" id="loginModal" data-action="modal-overlay">
  <div class="modal-box">
    <div class="modal-header">
      <button class="modal-close" data-action="close-modal" aria-label="Close">✕</button>
      <div class="vel-logo" style="font-size:20px;justify-content:center"><span class="vel-word1">தினத்</span><span class="vel-word2">துளிர்</span></div>
      <div class="vel-tagline" style="color:#6B6A64;font-size:11px;text-align:center">அரசியல் பழகு &nbsp;·&nbsp; அறம் செய்</div>
    </div>
    <div class="modal-body">
      <div class="modal-title">Welcome Back!</div>
      <div class="modal-subtitle">செய்திகளை மதிப்பிட உள்நுழையுங்கள்</div>
      <div class="modal-benefit"><span class="modal-benefit-icon">⭐</span> செய்திகளை மதிப்பிடுங்கள்</div>
      <div class="modal-benefit"><span class="modal-benefit-icon">💬</span> கருத்துகள் சொல்லுங்கள்</div>
      <div class="modal-benefit"><span class="modal-benefit-icon">🔔</span> உடனடி அறிவிப்புகள்</div>
      <div class="modal-divider">Sign in with</div>
      <button class="google-btn" data-action="google-popup" data-url="<?= $baseUrl ?>/public/auth/reader/login?return=<?= urlencode($_SERVER['REQUEST_URI']) ?>">
        <svg width="20" height="20" viewBox="0 0 18 18"><path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.875 2.684-6.615z" fill="#4285F4"/><path d="M9 18c2.43 0 4.467-.806 5.956-2.18l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 009 18z" fill="#34A853"/><path d="M3.964 10.71A5.41 5.41 0 013.682 9c0-.593.102-1.17.282-1.71V4.958H.957A8.996 8.996 0 000 9c0 1.452.348 2.827.957 4.042l3.007-2.332z" fill="#FBBC05"/><path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 00.957 4.958L3.964 6.29C4.672 4.163 6.656 3.58 9 3.58z" fill="#EA4335"/></svg>
        Google மூலம் உள்நுழைக
      </button>
      <div class="modal-note">உள்நுழைவதன் மூலம் <a href="#">விதிமுறைகளை</a> ஏற்கிறீர்கள்.</div>
    </div>
  </div>
</div>

<script src="<?= \App\Core\Helper::assetVersioned('/assets/js/frontend.js') ?>"></script>
<script>
/* Canvas particles removed — requestAnimationFrame loop conflicted with rendering */

/* Drawer */
function openDrawer(){document.getElementById('mobDrawer').classList.add('open');document.getElementById('drawerOverlay').classList.add('open');document.body.style.overflow='hidden';}
function closeDrawer(){document.getElementById('mobDrawer').classList.remove('open');document.getElementById('drawerOverlay').classList.remove('open');document.body.style.overflow='';}

/* Ticker touch swipe */
(function(){
  const t=document.getElementById('tickerInner');if(!t)return;
  let sx=0,off=0;
  t.addEventListener('touchstart',e=>{sx=e.touches[0].clientX;t.style.animationPlayState='paused';const m=new DOMMatrix(getComputedStyle(t).transform);off=m.m41||0;},{passive:true});
  t.addEventListener('touchmove',e=>{t.style.transform=`translateX(${off+e.touches[0].clientX-sx}px)`;},{passive:true});
  t.addEventListener('touchend',e=>{const d=e.changedTouches[0].clientX-sx;off+=d;const half=t.scrollWidth/2;while(off>0)off-=half;while(off<-half)off+=half;t.style.transform='';t.style.animationPlayState='running';},{passive:true});
})();





// ── RATE ICONS ────────────────────────────────────────
let ratesCache = null;
const rateConfig = {
  gold: { icon:'🥇', label:'Gold Rate', unit:'/gram' },
  silver: { icon:'🥈', label:'Silver Rate', unit:'/gram' },
  petrol: { icon:'⛽', label:'Petrol', unit:'/litre' },
  diesel: { icon:'🚛', label:'Diesel', unit:'/litre' },
  currency_usd: { icon:'💵', label:'USD Rate', unit:'/USD' },
};

function toggleRate(type) {
  const popup = document.getElementById('mobRatePopup');
  const cfg = rateConfig[type];
  document.getElementById('rateIcon').textContent  = cfg.icon;
  document.getElementById('rateLabel').textContent = cfg.label;
  document.getElementById('rateValue').textContent = 'Loading...';
  document.getElementById('rateChange').textContent = '';
  document.getElementById('rateCity').textContent = '';
  popup.style.display = 'block';

  const load = (rates) => {
    const r = rates.find(x => x.type === type);
    if (r) {
      document.getElementById('rateValue').textContent = '₹' + parseFloat(r.value).toFixed(2) + cfg.unit;
      if (r.change_val) {
        const pos = r.change_val >= 0;
        document.getElementById('rateChange').innerHTML =
          '<span style="color:' + (pos?'#10B981':'#EF4444') + '">' +
          (pos?'+':'') + parseFloat(r.change_val).toFixed(2) + ' (' +
          (r.change_pct ? parseFloat(r.change_pct).toFixed(2) + '%)' : ')') + '</span>';
      }
      if (r.city) document.getElementById('rateCity').textContent = r.city;
    } else {
      document.getElementById('rateValue').textContent = 'Not available';
    }
  };

  if (ratesCache) { load(ratesCache); return; }
  fetch('<?= $baseUrl ?>/public/api/rates')
    .then(r => r.json())
    .then(d => { if (d.success) { ratesCache = d.rates; load(ratesCache); } })
    .catch(() => { document.getElementById('rateValue').textContent = 'Unavailable'; });
}
function closeRate() { document.getElementById('mobRatePopup').style.display='none'; }


// ── AD ROTATION — two distinct ads per slot, each container gets its own pool ──
<?php
// Square: pick 2 distinct ads, each gets its own image pool
$_sq_ads = $_adSquare; // each element = one ad with 'images' array
shuffle($_sq_ads);     // randomise order
$_sq_a = !empty($_sq_ads[0]) ? $_sq_ads[0]['images'] : [];  // left container
$_sq_b = !empty($_sq_ads[1]) ? $_sq_ads[1]['images'] : ($_sq_a); // right — fallback to same if only one ad

$__hz = [];  foreach ($_adHorizontal as $a) foreach ($a['images'] as $i) $__hz[] = $i;
$__vt = [];  foreach ($_adVertical   as $a) foreach ($a['images'] as $i) $__vt[] = $i;
?>
var _adData = {
  square_a:   <?= json_encode($_sq_a)  ?>,
  square_b:   <?= json_encode($_sq_b)  ?>,
  horizontal: <?= json_encode($__hz) ?>,
  vertical:   <?= json_encode($__vt) ?>,
  // One entry per distinct ad (each entry = that ad's own images), used by
  // data-ad-pool containers so every ad slot on a page gets a different
  // advertiser instead of the same 1-2 ads repeating in every card.
  square_all:     <?= json_encode(array_values(array_map(fn($a) => $a['images'] ?? [], $_adSquare))) ?>,
  horizontal_all: <?= json_encode(array_values(array_map(fn($a) => $a['images'] ?? [], $_adHorizontal))) ?>
};
var _adPoolCounters = { square: 0, horizontal: 0 };

// Dedup: same ad, same session = count once per 30 mins
var _impressionLog = {};
function trackAdView(adId) {
  if (!adId || adId === '0') return;
  // Skip if tab not visible
  if (document.hidden) return;
  var key = 'ad_' + adId;
  var now = Date.now();
  // Deduplicate: same ad within 30 minutes counts once
  if (_impressionLog[key] && (now - _impressionLog[key]) < 1800000) return;
  _impressionLog[key] = now;
  fetch('<?= $r ?>/api/ads/track-view/' + adId).catch(function(){});
}

// Dwell timer: only count impression after ad visible for 1 continuous second
var _dwellTimers = {};
function startDwell(el, adId) {
  if (!adId || adId === '0') return;
  clearTimeout(_dwellTimers[adId]);
  _dwellTimers[adId] = setTimeout(function() {
    if (!document.hidden) trackAdView(adId);
  }, 1000);
}
function cancelDwell(adId) {
  clearTimeout(_dwellTimers[adId]);
}

// IntersectionObserver: track visibility
var _adObserver = new IntersectionObserver(function(entries) {
  entries.forEach(function(entry) {
    var el    = entry.target;
    var adId  = el.querySelector('img') ? el.querySelector('img').dataset.adId : null;
    if (!adId) return;
    if (entry.isIntersecting && entry.intersectionRatio >= 0.5) {
      startDwell(el, adId);
    } else {
      cancelDwell(adId);
    }
  });
}, { threshold: 0.5 });

// Pause/resume rotation on tab visibility
var _rotationPaused = false;
document.addEventListener('visibilitychange', function() {
  _rotationPaused = document.hidden;
  if (document.hidden) {
    // Cancel all pending dwell timers when tab hidden
    Object.keys(_dwellTimers).forEach(function(k) { clearTimeout(_dwellTimers[k]); });
  }
});

function loadAd(el) {
  var slot     = el.dataset.slot;
  var cat      = parseInt(el.dataset.cat || '0', 10);
  var poolName = el.dataset.adPool;
  var pool;

  if (poolName) {
    // Page-unique mode: this container is locked to one specific ad (cycling
    // only through that ad's own images), and each new container gets the
    // next ad in line — so the same advertiser never shows in two cards at
    // once on the same page. Only repeats if slots outnumber available ads.
    var allAds = _adData[poolName + '_all'] || [];
    if (!allAds.length) return;
    var idx = _adPoolCounters[poolName] % allAds.length;
    _adPoolCounters[poolName]++;
    pool = allAds[idx] || [];
  } else {
    pool = _adData[slot] || [];
    // Mobile: if square_b has no dedicated pool, use square_a pool
    if (!pool.length && slot === 'square_b') pool = _adData['square_a'] || [];
    // Fallback: any square slot with no pool tries square_a
    if (!pool.length && slot === 'square')   pool = _adData['square_a'] || [];
    // Category filter: prefer ads matching this category, fall back to category=0/global
    if (cat) {
      var catPool = pool.filter(function(a){ return a.category_id == cat || !a.category_id || a.category_id == 0; });
      if (catPool.length) pool = catPool;
    }
  }
  if (!pool.length) return;

  var img = document.createElement('img');
  // width:auto (not 100%) so the image sizes to its own aspect ratio instead of
  // stretching to the container's width and letterboxing with gray bars above/below.
  var slotStyles = {
    'square_a':   'display:block;width:auto;max-width:100%;height:auto;max-height:450px;margin:0 auto;object-fit:contain;transition:opacity .15s;cursor:pointer;',
    'square_b':   'display:block;width:auto;max-width:100%;height:auto;max-height:450px;margin:0 auto;object-fit:contain;transition:opacity .15s;cursor:pointer;',
    'square':     'display:block;width:auto;max-width:100%;height:auto;max-height:450px;margin:0 auto;object-fit:contain;transition:opacity .15s;cursor:pointer;',
    'horizontal': 'display:block;width:auto;max-width:100%;height:auto;max-height:150px;margin:0 auto;object-fit:contain;transition:opacity .15s;cursor:pointer;',
    'vertical':   'display:block;width:auto;max-width:100%;height:auto;max-height:750px;margin:0 auto;object-fit:contain;transition:opacity .15s;cursor:pointer;',
  };
  img.style.cssText = slotStyles[slot] || slotStyles['square'];
  el.appendChild(img);
  _adObserver.observe(el);

  // Shuffle-bag rotation: every image in the pool is shown once before any
  // repeats, instead of plain Math.random() which can repeat the same ad
  // back-to-back and leave others rarely shown.
  var bag = [];
  var bagIndex = 0;
  var lastShown = null;
  function refillBag() {
    bag = pool.slice();
    for (var i = bag.length - 1; i > 0; i--) {
      var j = Math.floor(Math.random() * (i + 1));
      var tmp = bag[i]; bag[i] = bag[j]; bag[j] = tmp;
    }
    // Avoid showing the same ad twice in a row across a bag boundary
    if (bag.length > 1 && lastShown && bag[0] === lastShown) {
      var swapWith = 1 + Math.floor(Math.random() * (bag.length - 1));
      var t = bag[0]; bag[0] = bag[swapWith]; bag[swapWith] = t;
    }
    bagIndex = 0;
  }
  refillBag();

  function show() {
    // Do not rotate when tab is hidden
    if (_rotationPaused) return;
    if (bagIndex >= bag.length) refillBag();
    var cur = bag[bagIndex++];
    lastShown = cur;
    var src = cur.src || '';
    if (src && src.indexOf('http') !== 0) src = '<?= rtrim(ASSET_URL,"/") ?>/public' + (src[0]==='/'?src:'/'+src);
    img.style.opacity = '0';
    setTimeout(function(){
      img.src = src; img.alt = cur.alt||''; img.style.opacity = '1';
      img.dataset.adId       = cur.ad_id    || '0';
      img.dataset.adName     = cur.name     || cur.alt || '';
      img.dataset.adPhone    = cur.phone    || '';
      img.dataset.adEmail    = cur.email    || '';
      img.dataset.adDistrict = cur.district || '';
      img.dataset.adLink     = cur.link     || '#';
      var a = el.closest('a'); if(a && cur.link && cur.link!=='#') a.href = cur.link;
      // Impression tracked by IntersectionObserver dwell timer — not here
    }, 150);
  }

  show();
  // Rotation interval — skips when tab hidden
  setInterval(function() { if (!_rotationPaused) show(); }, 15000);
}

function initAdRotators() {
  document.querySelectorAll('.ad-rotator').forEach(loadAd);
}
document.addEventListener('DOMContentLoaded', initAdRotators);

</script>

<!-- AD LIGHTBOX -->
<div id="adLightbox" class="ad-lightbox notranslate" translate="no" data-action="close-lightbox"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.92);z-index:9999;align-items:center;justify-content:center;padding:12px">
  <div data-stop-propagation="true"
       style="position:relative;max-width:94vw;width:480px;background:#fff;border-radius:14px;overflow:hidden;max-height:94vh;display:flex;flex-direction:column;box-shadow:0 24px 60px rgba(0,0,0,.5)">
    <button data-action="close-lightbox"
            style="position:absolute;top:10px;right:10px;background:rgba(0,0,0,.55);color:#fff;border:none;border-radius:50%;width:28px;height:28px;font-size:14px;cursor:pointer;z-index:2;display:flex;align-items:center;justify-content:center;line-height:1">✕</button>
    <img id="adLightboxImg" src="" alt=""
         style="width:100%;max-height:62vh;object-fit:contain;display:block;background:#F5F5F0;flex-shrink:0">
    <div style="padding:16px 18px 10px;overflow-y:auto;flex:1">
      <div id="adLightboxName"
           style="font-family:'Noto Sans Tamil',sans-serif;font-weight:900;font-size:17px;color:#111;margin-bottom:12px;padding-bottom:10px;border-bottom:2px solid #C0001A;line-height:1.3"></div>
      <div id="adLightboxDetails"></div>
      <div id="adLightboxShare" style="display:flex;gap:8px;margin-top:12px;flex-wrap:wrap"></div>
    </div>
    <div id="adLightboxViewAllWrap" style="padding:0 16px 14px;display:none">
      <a id="adLightboxViewAll" href="#" target="_blank" rel="noopener"
         style="display:block;padding:10px;text-align:center;background:#C0001A;color:#fff;font-weight:700;font-size:13px;text-decoration:none;border-radius:8px">
        View Ad Details &amp; Share →
      </a>
    </div>
  </div>
</div>
<script>
document.addEventListener("click", function(e) {
  var el = e.target;
  if (el.tagName !== "IMG") return;
  var p = el.parentElement;
  if (!p || !p.classList.contains("ad-rotator")) return;
  e.preventDefault();

  // Track click — only for real ads
  if (el.dataset.adId && el.dataset.adId !== '0') {
    fetch('<?= $r ?>/api/ads/track-click/' + el.dataset.adId).catch(function(){});
  }

  document.getElementById("adLightboxImg").src = el.src;
  document.getElementById("adLightboxName").textContent = el.dataset.adName || "Advertisement";

  // Rich details list
  var box = document.getElementById("adLightboxDetails");
  var rows = [
    { label: "Phone",    icon: "📞", val: el.dataset.adPhone,    href: "tel:" },
    { label: "Email",    icon: "✉️", val: el.dataset.adEmail,    href: "mailto:" },
    { label: "Location", icon: "📍", val: el.dataset.adDistrict, href: "" },
  ];
  var html = '<dl style="margin:0;display:grid;grid-template-columns:max-content 1fr;gap:3px 14px">';
  rows.forEach(function(r){
    if (!r.val) return;
    var val = r.href ? '<a href="'+r.href+r.val+'" style="color:#C0001A;font-weight:600;text-decoration:none">'+r.val+'</a>' : r.val;
    html += '<dt style="color:#9CA3AF;font-size:11px;font-weight:600;padding:4px 0;white-space:nowrap">'+r.icon+' '+r.label+'</dt>';
    html += '<dd style="margin:0;font-size:13px;color:#1A1A1A;padding:4px 0">'+val+'</dd>';
  });
  html += '</dl>';
  box.innerHTML = html;

  // Share
  var adId = el.dataset.adId;
  var shareDiv = document.getElementById("adLightboxShare");
  var viewAllWrap = document.getElementById("adLightboxViewAllWrap");
  if (adId && adId !== "0") {
    var adPageUrl = '<?= rtrim(BASE_URL,"/") ?>/public/ad/' + adId;
    var adName    = el.dataset.adName || "Advertisement";
    document.getElementById("adLightboxViewAll").href = adPageUrl;
    viewAllWrap.style.display = "block";
    var waUrl = 'https://wa.me/?text=' + encodeURIComponent(adName + ' — ' + adPageUrl);
    var waLink = document.createElement('a');
    waLink.href = waUrl; waLink.target = '_blank'; waLink.rel = 'noopener';
    waLink.textContent = 'WhatsApp';
    waLink.style.cssText = 'display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:600;color:#25D366;text-decoration:none;padding:6px 12px;border:1.5px solid #25D366;border-radius:6px';
    var cpBtn = document.createElement('button');
    cpBtn.textContent = 'Copy Link';
    cpBtn.style.cssText = 'display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:600;color:#6B7280;background:none;border:1.5px solid #E5E7EB;border-radius:6px;padding:6px 12px;cursor:pointer';
    cpBtn.onclick = function(){ navigator.clipboard && navigator.clipboard.writeText(adName + '\n' + adPageUrl).then(function(){ cpBtn.textContent='✓ Copied'; setTimeout(function(){ cpBtn.textContent='Copy Link'; }, 1500); }); };
    shareDiv.innerHTML = '';
    shareDiv.appendChild(waLink);
    shareDiv.appendChild(cpBtn);
    shareDiv.style.display = "flex";
  } else {
    if(viewAllWrap) viewAllWrap.style.display = "none";
    shareDiv.innerHTML = "";
  }

  var lb = document.getElementById("adLightbox");
  lb.style.display = "flex";
  document.body.style.overflow = "hidden";
});
function closeAdLightbox() {
  document.getElementById("adLightbox").style.display = "none";
  document.body.style.overflow = "";
}

// ── MOBILE VERTICAL AD: show on page load, closeable anytime, auto-hides after 15s if ignored ──
(function() {
  if (window.innerWidth >= 1024) return;
  var el = document.getElementById('mobVerticalAd');
  if (!el) return;

  // Only run on home, category, article pages
  var path = window.location.pathname;
  var allowed = path.match(/\/(public\/)?(|article\/|tamil-news\/|category\/|india\/|cinema\/|sports\/|technology\/)/) && !path.match(/\/ad\/|\/citizen|\/reader|\/contribute|\/portal|\/admin/);
  if (!allowed) return;

  var closeBtn  = document.getElementById('mobAdCloseBtn');
  var countdown = document.getElementById('mobAdCountdown');
  var secs      = 15;
  var interval;

  // Show on load
  el.style.display = 'flex';

  // Count down and auto-hide if the user never closes it
  interval = setInterval(function() {
    secs--;
    if (countdown) countdown.textContent = secs;
    if (secs <= 0) {
      clearInterval(interval);
      el.style.display = 'none';
    }
  }, 1000);

  // Close handler — always available, user can dismiss anytime
  if (closeBtn) {
    closeBtn.addEventListener('click', function() {
      clearInterval(interval);
      el.style.display = 'none';
      // Don't show again this session
      try { sessionStorage.setItem('mobVAdClosed','1'); } catch(e){}
    });
  }

  // Don't re-show if closed this session
  try { if (sessionStorage.getItem('mobVAdClosed')) { el.style.display='none'; clearInterval(interval); } } catch(e){}
}());

// ── RATE SHEET ──
function openRateSheet() {
  document.getElementById('mobRateOverlay').classList.add('open');
  document.getElementById('mobRateSheet').classList.add('open');
}
function closeRateSheet() {
  document.getElementById('mobRateOverlay').classList.remove('open');
  document.getElementById('mobRateSheet').classList.remove('open');
}

</script>
<!-- FLOATING SEARCH BAR — desktop only, always visible, bottom-center -->
<?php if (empty($noAds) && empty($_SESSION['reader_id'])): ?>
<a href="<?= $baseUrl ?>/public/auth/reader/login?return=<?= urlencode('/public/citizen-reporter') ?>"
   class="float-reporter-btn" aria-label="Be a Citizen Reporter">
  📰 <span>நிருபர் ஆக</span>
</a>
<?php elseif (empty($noAds) && !str_contains($_SERVER['REQUEST_URI'] ?? '','citizen-reporter')): ?>
<a href="<?= $baseUrl ?>/public/citizen-reporter" class="float-reporter-btn" aria-label="Be a Citizen Reporter">
  📰 <span>நிருபர் ஆக</span>
</a>
<?php endif; ?>

<div class="float-search-bar" id="floatSearchBar">
  <form action="<?= $baseUrl ?>/public/search" method="GET" class="float-search-bar-form" role="search" aria-label="Search articles">
    <span class="float-search-bar-icon">🔍</span>
    <input type="text" name="q" placeholder="செய்திகளைத் தேடுங்கள்... Search news..."
           value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" autocomplete="off">
    <button type="submit" aria-label="Search">தேடு</button>
  </form>
</div>
<script src="<?= \App\Core\Helper::assetVersioned('/assets/js/language-switcher.js') ?>"></script>

<!-- Firebase Push (injected config, replace after project setup) -->
<?php if (!empty($_ENV['FCM_API_KEY']) && $_ENV['FCM_API_KEY'] !== 'REPLACE_WITH_API_KEY'): ?>
<script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js"></script>
<script>
window._pushBase = '<?= $r ?>';
window.FCM_CONFIG = {
  apiKey:            '<?= $_ENV['FCM_API_KEY'] ?? '' ?>',
  authDomain:        '<?= $_ENV['FCM_AUTH_DOMAIN'] ?? '' ?>',
  projectId:         '<?= $_ENV['FCM_PROJECT_ID'] ?? '' ?>',
  storageBucket:     '<?= $_ENV['FCM_STORAGE_BUCKET'] ?? '' ?>',
  messagingSenderId: '<?= $_ENV['FCM_SENDER_ID'] ?? '' ?>',
  appId:             '<?= $_ENV['FCM_APP_ID'] ?? '' ?>',
  vapidKey:          '<?= $_ENV['FCM_VAPID_KEY'] ?? '' ?>',
};
</script>
<script src="<?= \App\Core\Helper::assetVersioned('/assets/js/push-subscribe.js') ?>"></script>
<?php endif; ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    if (!(window.Capacitor && window.Capacitor.Plugins && window.Capacitor.Plugins.App)) return;

    const App = window.Capacitor.Plugins.App;
    const isHome = window.location.pathname === '<?= $r ?>/' || window.location.pathname === '<?= $r ?>';
    let lastBackPress = 0;

    App.addListener("backButton", function () {
        if (isHome) {
            const now = Date.now();
            if (now - lastBackPress < 2000) {
                App.exitApp();
            } else {
                lastBackPress = now;
                if (typeof showToast === 'function') {
                    showToast('மீண்டும் பின் சொடுக்கி வெளியேறவும்');
                }
            }
            return;
        }
        if (typeof goBackSmart === 'function') {
            goBackSmart();
        } else if (window.history.length > 1) {
            window.history.back();
        } else {
            window.location = '<?= $r ?>/';
        }
    });
});
</script>
</body>
</html>
