<?php use App\Core\Helper;
$siteUrl  = rtrim(BASE_URL,'/') . '/public';
$assetUrl = rtrim(ASSET_URL,'/');
?>

<div class="breadcrumb">
  <a href="<?= $siteUrl ?>/">முகப்பு</a>
  <span>›</span>
  <span>பட செய்திகள்</span>
</div>

<div class="pn-page">

  <div class="pn-page-head">
    <h1 class="pn-page-title">📸 பட செய்திகள்</h1>
    <p class="pn-page-sub">Visual news — click any card to view and share</p>
  </div>

  <?php if (empty($cards)): ?>
  <div style="text-align:center;padding:80px 0;color:#9CA3AF;font-size:14px">No photo news yet.</div>
  <?php else: ?>

  <div class="pn-grid">
    <?php foreach ($cards as $i => $card): ?>
    <button class="pn-card" onclick="pnOpen(<?= $i ?>)">
      <div class="pn-card-img-wrap">
        <img src="<?= $assetUrl ?>/public<?= htmlspecialchars($card['image_path']) ?>"
             alt="<?= htmlspecialchars($card['title']) ?>" loading="lazy" class="pn-card-img">
        <div class="pn-card-hover"><i class="bi bi-fullscreen"></i></div>
      </div>
      <div class="pn-card-title"><?= htmlspecialchars($card['title']) ?></div>
    </button>
    <?php endforeach; ?>
  </div>

  <?php if ($total > $per): ?>
  <div class="pn-pagination">
    <?php if ($page > 1): ?><a href="?page=<?= $page-1 ?>" class="pn-pg-btn">← முந்தைய</a><?php endif; ?>
    <span class="pn-pg-info">Page <?= $page ?> / <?= ceil($total/$per) ?></span>
    <?php if ($page * $per < $total): ?><a href="?page=<?= $page+1 ?>" class="pn-pg-btn">அடுத்து →</a><?php endif; ?>
  </div>
  <?php endif; ?>
  <?php endif; ?>
</div>

<!-- Lightbox -->
<div class="pn-lb-overlay" id="pnOverlay" role="dialog" aria-modal="true" aria-label="Photo News Viewer">
  <button class="pn-lb-close" id="pnClose" aria-label="Close">✕</button>
  <button class="pn-lb-nav pn-lb-prev" id="pnPrev" aria-label="Previous photo">‹</button>
  <button class="pn-lb-nav pn-lb-next" id="pnNext" aria-label="Next photo">›</button>
  <div class="pn-lb-box">
    <div class="pn-lb-img-wrap">
      <img id="pnLbImg" src="" alt="" aria-describedby="pnLbTitle">
    </div>
    <div class="pn-lb-info">
      <a class="pn-lb-title" id="pnLbTitle" href="#" target="_blank"></a>

      <!-- Full news link — shown only if article exists -->
      <a id="pnFullNews" href="#" class="pn-full-news" style="display:none" target="_blank">
        📰 Full News படிக்க →
      </a>

      <!-- Share actions — pick a platform, or share the image file directly -->
      <div class="pn-lb-share">
        <a id="pnShareWa" href="#" target="_blank" rel="noopener" class="pn-icon-btn pn-icon-wa" title="WhatsApp">
          <i class="bi bi-whatsapp"></i>
        </a>
        <a id="pnShareFb" href="#" target="_blank" rel="noopener" class="pn-icon-btn pn-icon-fb" title="Facebook">
          <i class="bi bi-facebook"></i>
        </a>
        <a id="pnShareTw" href="#" target="_blank" rel="noopener" class="pn-icon-btn pn-icon-tw" title="X / Twitter">
          <i class="bi bi-twitter-x"></i>
        </a>
        <a id="pnShareTg" href="#" target="_blank" rel="noopener" class="pn-icon-btn pn-icon-tg" title="Telegram">
          <i class="bi bi-telegram"></i>
        </a>
        <button id="pnShareImg" class="pn-icon-btn pn-icon-native" title="Share image">
          <i class="bi bi-share-fill"></i>
        </button>
        <a id="pnDownload" href="#" download class="pn-icon-btn pn-icon-dl" title="Download">
          <i class="bi bi-download"></i>
        </a>
        <button id="pnCopy" class="pn-icon-btn pn-icon-copy" title="Copy link">
          <i class="bi bi-link-45deg"></i>
        </button>
      </div>
      <div class="pn-copy-toast" id="pnCopyToast" style="display:none">Link copied</div>
    </div>
  </div>
</div>

<style>
.pn-page{max-width:1200px;margin:0 auto;padding:16px 16px 80px;}
.pn-page-head{text-align:center;margin-bottom:24px;}
.pn-page-title{font-family:'Noto Sans Tamil',sans-serif;font-size:26px;font-weight:900;color:#C0001A;margin:0;}
.pn-page-sub{font-size:13px;color:#6B6A64;margin:4px 0 0;}
.pn-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:14px;}
@media(max-width:580px){.pn-grid{grid-template-columns:repeat(2,1fr);gap:10px;}}
.pn-card{background:#fff;border:none;border-radius:12px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,.08);cursor:pointer;transition:transform .15s,box-shadow .15s;text-align:left;padding:0;}
.pn-card:hover{transform:translateY(-3px);box-shadow:0 8px 24px rgba(0,0,0,.14);}
.pn-card-img-wrap{position:relative;aspect-ratio:3/4;overflow:hidden;background:#F5F5F0;}
.pn-card-img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .2s;}
.pn-card:hover .pn-card-img{transform:scale(1.04);}
.pn-card-hover{position:absolute;inset:0;background:rgba(0,0,0,0);display:flex;align-items:center;justify-content:center;transition:background .2s;}
.pn-card:hover .pn-card-hover{background:rgba(0,0,0,.28);}
.pn-card-hover i{color:#fff;font-size:22px;opacity:0;transition:opacity .15s;}
.pn-card:hover .pn-card-hover i{opacity:1;}
.pn-card-title{font-family:'Noto Sans Tamil',sans-serif;font-size:12px;font-weight:700;color:#1A1A1A;padding:8px 10px 10px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.pn-pagination{display:flex;align-items:center;justify-content:center;gap:16px;margin-top:28px;}
.pn-pg-btn{background:#C0001A;color:#fff;padding:8px 20px;border-radius:6px;text-decoration:none;font-weight:700;font-size:13px;}
.pn-pg-info{font-size:13px;color:#6B6A64;}

/* Lightbox */
.pn-lb-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.92);z-index:9999;align-items:center;justify-content:center;}
.pn-lb-overlay.open{display:flex;}
.pn-lb-close{position:fixed;top:16px;right:20px;background:rgba(255,255,255,.15);color:#fff;border:none;width:40px;height:40px;border-radius:50%;font-size:20px;cursor:pointer;z-index:2;}
.pn-lb-close:hover{background:rgba(255,255,255,.3);}
.pn-lb-nav{position:fixed;top:50%;transform:translateY(-50%);background:rgba(255,255,255,.15);color:#fff;border:none;width:48px;height:48px;border-radius:50%;font-size:28px;cursor:pointer;z-index:2;}
.pn-lb-nav:hover{background:rgba(255,255,255,.3);}
.pn-lb-prev{left:16px;} .pn-lb-next{right:16px;}
.pn-lb-nav:disabled{opacity:.25;cursor:default;}
.pn-lb-box{background:#fff;border-radius:16px;max-width:440px;width:calc(100% - 120px);max-height:90vh;overflow-y:auto;}
.pn-lb-img-wrap{overflow:hidden;border-radius:16px 16px 0 0;}
.pn-lb-img-wrap img{width:100%;display:block;}
.pn-lb-info{padding:14px 16px 16px;}
.pn-lb-title{display:block;font-family:'Noto Sans Tamil',sans-serif;font-size:14px;font-weight:800;color:#1A1A1A;line-height:1.5;margin:0 0 10px;text-decoration:none;}
.pn-lb-title:hover{text-decoration:underline;color:#C0001A;}
.pn-full-news{display:flex;align-items:center;padding:9px 14px;background:#FEF2F2;border:1px solid #FECACA;border-radius:8px;color:#C0001A;font-size:13px;font-weight:700;text-decoration:none;margin-bottom:12px;}
.pn-full-news:hover{background:#FEE2E2;}
.pn-lb-share{display:flex;gap:8px;flex-wrap:wrap;}
.pn-icon-btn{display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:50%;font-size:16px;cursor:pointer;text-decoration:none;border:none;flex-shrink:0;}
.pn-icon-wa{background:#25D366;color:#fff;}
.pn-icon-fb{background:#1877F2;color:#fff;}
.pn-icon-tw{background:#000;color:#fff;}
.pn-icon-tg{background:#26A5E4;color:#fff;}
.pn-icon-native{background:#C0001A;color:#fff;}
.pn-icon-dl{background:#1D4ED8;color:#fff;}
.pn-icon-copy{background:#F3F4F6;color:#1A1A1A;}
.pn-icon-btn:hover{filter:brightness(1.1);transform:translateY(-1px);}
.pn-copy-toast{margin-top:8px;font-size:12px;font-weight:700;color:#16A34A;}
@media(max-width:600px){.pn-lb-box{max-width:calc(100% - 80px);}.pn-lb-prev{left:6px;}.pn-lb-next{right:6px;}}
</style>

<script>
<?php
$jsCards = array_map(function($c) use ($assetUrl, $siteUrl) {
    return [
        'id'          => (int)$c['id'],
        'img'         => $assetUrl . '/public' . $c['image_path'],
        'imgPath'     => $c['image_path'],
        'title'       => $c['title'],
        'articleUrl'  => $c['article_slug'] ? $siteUrl . '/article/' . $c['article_slug'] : null,
        // Per-photo deep link — sharing this shows this photo's own OG
        // image/title, not the generic gallery listing.
        'pageUrl'     => $siteUrl . '/photo-news?photo=' . (int)$c['id'],
    ];
}, $cards);
?>
var PN_CARDS  = <?= json_encode($jsCards, JSON_UNESCAPED_UNICODE) ?>;
var pnCurrent = 0;

function pnOpen(idx) {
  pnCurrent = idx;
  pnRender();
  document.getElementById('pnOverlay').classList.add('open');
  document.body.style.overflow = 'hidden';
  var c = PN_CARDS[idx];
  if (c && typeof dataLayer !== 'undefined') {
    dataLayer.push({event:'photo_news_view', photo_index:idx, photo_title:c.title, photo_url:c.pageUrl});
  }
}

function pnClose() {
  document.getElementById('pnOverlay').classList.remove('open');
  document.body.style.overflow = '';
}

function pnRender() {
  var c = PN_CARDS[pnCurrent];
  if (!c) return;

  var img = document.getElementById('pnLbImg');
  img.src = c.img;
  img.alt = c.title;

  // Title itself is a clickable link — goes to the article if one is
  // linked, otherwise to this photo's own shareable deep link.
  var shareUrl   = c.articleUrl || c.pageUrl;
  var titleEl    = document.getElementById('pnLbTitle');
  titleEl.textContent = c.title;
  titleEl.href        = shareUrl;

  /* Full news link */
  var fnEl = document.getElementById('pnFullNews');
  if (c.articleUrl) {
    fnEl.href = c.articleUrl;
    fnEl.style.display = 'flex';
  } else {
    fnEl.style.display = 'none';
  }

  /* Share icon row — title + link, not a bare URL */
  var shareText = encodeURIComponent(c.title);
  var shareLink = encodeURIComponent(shareUrl);
  document.getElementById('pnShareWa').href = 'https://wa.me/?text=' + shareText + '%0A' + shareLink;
  document.getElementById('pnShareFb').href = 'https://www.facebook.com/sharer/sharer.php?u=' + shareLink;
  document.getElementById('pnShareTw').href = 'https://twitter.com/intent/tweet?text=' + shareText + '&url=' + shareLink;
  document.getElementById('pnShareTg').href = 'https://t.me/share/url?url=' + shareLink + '&text=' + shareText;

  /* Download — keep the source file's real extension so the saved
     file's name matches its actual format (jpg/png/webp) */
  var extMatch = c.img.match(/\.([a-zA-Z0-9]+)(?:\?.*)?$/);
  var ext = extMatch ? extMatch[1] : 'jpg';
  document.getElementById('pnDownload').href = c.img;
  document.getElementById('pnDownload').download = 'thinathulir-photo-news.' + ext;

  /* Nav buttons */
  document.getElementById('pnPrev').disabled = pnCurrent === 0;
  document.getElementById('pnNext').disabled = pnCurrent === PN_CARDS.length - 1;
  document.getElementById('pnCopyToast').style.display = 'none';
}

/* Share image via Web Share API (mobile) — fallback to WhatsApp */
document.getElementById('pnShareImg').addEventListener('click', async function () {
  var card = PN_CARDS[pnCurrent];
  if (!card) return;
  var imgUrl = card.img;
  var title  = card.title || '<?= htmlspecialchars($pageTitle ?? 'Thinathulir Photo News') ?>';
  var shareUrl = card.articleUrl || card.pageUrl || window.location.href;

  /* Try Web Share API with file blob */
  if (navigator.share && navigator.canShare) {
    try {
      var res  = await fetch(imgUrl);
      var blob = await res.blob();
      var file = new File([blob], 'thinathulir-photo.jpg', { type: blob.type });
      if (navigator.canShare({ files: [file] })) {
        await navigator.share({ files: [file], title: title, text: title });
        return;
      }
    } catch(e) {}
    /* Try sharing URL if file share not supported */
    try { await navigator.share({ title: title, text: title, url: shareUrl }); return; } catch(e) {}
  }

  /* Fallback: WhatsApp with title + link so previews render */
  window.open('https://wa.me/?text=' + encodeURIComponent(title + "\n" + shareUrl), '_blank');
});

/* Copy link */
function pnShowCopyToast(msg) {
  var toast = document.getElementById('pnCopyToast');
  toast.textContent = msg;
  toast.style.display = 'block';
  setTimeout(function () { toast.style.display = 'none'; }, 2000);
}

function pnFallbackCopy(text) {
  var ta = document.createElement('textarea');
  ta.value = text;
  ta.style.position = 'fixed';
  ta.style.opacity = '0';
  document.body.appendChild(ta);
  ta.focus();
  ta.select();
  var ok = false;
  try { ok = document.execCommand('copy'); } catch (e) { ok = false; }
  document.body.removeChild(ta);
  return ok;
}

document.getElementById('pnCopy').addEventListener('click', function () {
  var card = PN_CARDS[pnCurrent];
  var url = card?.articleUrl || card?.pageUrl || window.location.href;
  var text = card?.title ? card.title + '\n' + url : url;

  if (navigator.clipboard && navigator.clipboard.writeText) {
    navigator.clipboard.writeText(text).then(function () {
      pnShowCopyToast('✓ Link copied');
    }).catch(function () {
      pnShowCopyToast(pnFallbackCopy(text) ? '✓ Link copied' : '✗ Copy failed');
    });
  } else {
    pnShowCopyToast(pnFallbackCopy(text) ? '✓ Link copied' : '✗ Copy failed');
  }
});

document.getElementById('pnClose').addEventListener('click', pnClose);
document.getElementById('pnPrev').addEventListener('click', function () {
  if (pnCurrent > 0) { pnCurrent--; pnRender(); }
});
document.getElementById('pnNext').addEventListener('click', function () {
  if (pnCurrent < PN_CARDS.length - 1) { pnCurrent++; pnRender(); }
});
document.getElementById('pnOverlay').addEventListener('click', function (e) {
  if (e.target === this) pnClose();
});
document.addEventListener('keydown', function (e) {
  if (!document.getElementById('pnOverlay').classList.contains('open')) return;
  if (e.key === 'Escape') pnClose();
  if (e.key === 'ArrowLeft'  && pnCurrent > 0)                  { pnCurrent--; pnRender(); }
  if (e.key === 'ArrowRight' && pnCurrent < PN_CARDS.length - 1){ pnCurrent++; pnRender(); }
});

// Opened via a shared per-photo link (?photo=ID) — jump straight to it
<?php if (!empty($openPhoto)): ?>
(function () {
  var wantId = <?= (int)$openPhoto ?>;
  for (var i = 0; i < PN_CARDS.length; i++) {
    if (PN_CARDS[i].id === wantId) { pnOpen(i); break; }
  }
})();
<?php endif; ?>
</script>
