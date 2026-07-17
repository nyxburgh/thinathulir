<?php use App\Core\Helper;
$assetUrl = rtrim(ASSET_URL,'/').'/public';
$siteUrl  = rtrim(BASE_URL,'/').'/public';
$bizName  = $ad['business_name'] ?? '';
$contact  = $ad['contact_person'] ?? '';
$desc     = $ad['small_desc'] ?? ($ad['notes'] ?? '');
$phone    = $ad['contact_phone'] ?? '';
$email    = $ad['contact_email'] ?? '';
$website  = $ad['website_url'] ?? '';
$fb       = $ad['facebook_url'] ?? '';
$ig       = $ad['instagram_url'] ?? '';
$yt       = $ad['youtube_url'] ?? '';
$address  = $ad['address'] ?? '';

// Group images by slot type
$imgByType = [];
foreach ($ad['images'] ?? [] as $img) {
    $t = $img['slot_type'] ?? $img['display_type'] ?? 'square';
    $imgByType[$t][] = $img;
}
$imgByType = array_filter($imgByType);
$allFlat = array_merge(...array_values($imgByType ?: [[]]));
$lbSrcs  = array_map(fn($i) => $assetUrl . ($i['src'] ?? ''), $allFlat);
?>

<style>
.cad-page{max-width:1040px;margin:0 auto;padding:20px 16px 80px;display:flex;gap:22px;align-items:flex-start}
.cad-desktop-only{display:block}
.cad-mobile-only{display:none}
@media (max-width:1023px){.cad-desktop-only{display:none !important}.cad-mobile-only{display:block}}

/* Sidebar / promo strips */
.cad-sidebar{width:250px;flex-shrink:0;position:sticky;top:20px}
.cad-ad-label{font-family:'Oswald',sans-serif;font-size:10px;font-weight:700;color:var(--gray-4,#6B6A64);text-transform:uppercase;letter-spacing:.14em;margin-bottom:8px;display:flex;align-items:center;gap:6px}
.cad-ad-label::before{content:'';width:12px;height:2px;background:var(--red,#C0001A);border-radius:2px}
.cad-sidebar img{width:100%;border-radius:12px;display:block;box-shadow:0 4px 18px rgba(0,0,0,.1);transition:transform .25s ease,box-shadow .25s ease}
.cad-sidebar img:hover{transform:translateY(-3px);box-shadow:0 10px 26px rgba(0,0,0,.16)}
.cad-hero-banner{margin-bottom:18px;border-radius:12px;overflow:hidden;box-shadow:0 4px 16px rgba(0,0,0,.1)}
.cad-hero-banner img{width:100%;display:block}
.cad-squares{display:flex;gap:14px;margin-bottom:18px}
.cad-squares > div{flex:1;border-radius:12px;overflow:hidden;box-shadow:0 4px 16px rgba(0,0,0,.1);transition:transform .25s ease,box-shadow .25s ease}
.cad-squares > div:hover{transform:translateY(-3px);box-shadow:0 10px 26px rgba(0,0,0,.16)}
.cad-squares img{width:100%;display:block}

/* Main column */
.cad-main{flex:1;min-width:0;max-width:680px;margin:0 auto}

/* Business info card */
.cad-card{background:var(--white,#fff);border:1px solid var(--gray-2,#E8E6E0);border-radius:16px;box-shadow:0 2px 14px rgba(0,0,0,.06);padding:24px 26px;margin-bottom:20px;position:relative;overflow:hidden}
.cad-card::before{content:'';position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,var(--red,#C0001A),var(--gold,#E8A000))}
.cad-title{font-family:'Noto Sans Tamil',sans-serif;font-size:24px;font-weight:900;color:var(--charcoal,#1A1A1A);margin:4px 0 4px;line-height:1.3}
.cad-badge{display:inline-flex;align-items:center;gap:5px;font-family:'Oswald',sans-serif;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--red,#C0001A);background:var(--glow-red,rgba(192,0,26,.1));border:1px solid rgba(192,0,26,.25);border-radius:20px;padding:3px 10px;margin-bottom:12px}

.cad-info-grid{margin:16px 0 0;display:grid;grid-template-columns:1fr;gap:2px}
.cad-info-item{display:flex;align-items:flex-start;gap:12px;padding:9px 0;border-bottom:1px dashed var(--gray-2,#E8E6E0)}
.cad-info-item:last-child{border-bottom:none}
.cad-info-icon{width:32px;height:32px;border-radius:9px;background:var(--gray-1,#F0EFE9);color:var(--red,#C0001A);display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0}
.cad-info-body{min-width:0;padding-top:3px}
.cad-info-label{font-family:'Oswald',sans-serif;font-size:9.5px;font-weight:700;color:var(--gray-4,#6B6A64);text-transform:uppercase;letter-spacing:.1em;margin-bottom:2px}
.cad-info-value{font-size:13.5px;color:var(--charcoal,#1A1A1A);line-height:1.6;word-break:break-word}
.cad-info-value a{color:var(--red,#C0001A);text-decoration:none;font-weight:600}
.cad-info-value a:hover{text-decoration:underline}

.cad-desc{margin:16px 0 0;padding-top:14px;border-top:1px solid var(--gray-1,#F0EFE9);font-size:13.5px;color:var(--text-muted,#5A5A5A);line-height:1.75}

.cad-share{display:flex;gap:9px;flex-wrap:wrap;margin-top:18px;padding-top:16px;border-top:1px solid var(--gray-1,#F0EFE9)}

/* Gallery */
.cad-gallery{margin-bottom:20px}
.cad-gallery-label{font-family:'Oswald',sans-serif;font-size:10px;font-weight:700;color:var(--gray-4,#6B6A64);text-transform:uppercase;letter-spacing:.12em;margin-bottom:10px;display:flex;align-items:center;gap:6px}
.cad-gallery-label::before{content:'';width:12px;height:2px;background:var(--gold,#E8A000);border-radius:2px}
.cad-car-wrap{position:relative;width:100%;border-radius:14px;overflow:hidden;box-shadow:0 6px 22px rgba(0,0,0,.1);background:var(--gray-1,#F0EFE9)}
.cad-car-track{display:flex;width:100%;transition:transform .4s cubic-bezier(.4,0,.2,1)}
.cad-car-slide{min-width:100%;width:100%;flex-shrink:0;box-sizing:border-box;cursor:zoom-in;overflow:hidden}
.cad-car-slide img{width:100%;display:block;transition:transform .35s ease}
.cad-car-slide:hover img{transform:scale(1.02)}
.cad-car-btn{position:absolute;top:50%;transform:translateY(-50%);background:rgba(20,20,20,.5);backdrop-filter:blur(3px);color:#fff;border:none;width:34px;height:34px;border-radius:50%;font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;z-index:2;transition:background .15s,transform .15s}
.cad-car-btn:hover{background:var(--red,#C0001A);transform:translateY(-50%) scale(1.08)}
.cad-car-btn.prev{left:10px}
.cad-car-btn.next{right:10px}
.cad-car-dots{position:absolute;bottom:10px;left:50%;transform:translateX(-50%);display:flex;gap:6px;z-index:2}
.cad-car-dots span{width:6px;height:6px;border-radius:50%;background:rgba(255,255,255,.45);cursor:pointer;display:block;transition:.2s}
.cad-car-dots span.on{background:#fff;width:16px;border-radius:3px}
.cad-car-cnt{position:absolute;top:10px;right:12px;background:rgba(20,20,20,.55);backdrop-filter:blur(3px);color:#fff;font-size:10.5px;font-weight:600;padding:3px 9px;border-radius:10px;z-index:2}

/* Sponsored / recent articles */
.cad-section{border-top:1px solid var(--gray-1,#F0EFE9);padding-top:20px;margin-top:4px}
.cad-section + .cad-section{margin-top:24px}
.cad-art-row{display:flex;gap:12px;padding:10px 8px;border-radius:10px;text-decoration:none;align-items:flex-start;transition:background .15s}
.cad-art-row:hover{background:var(--gray-1,#F0EFE9)}
.cad-art-row img{width:60px;height:60px;object-fit:cover;border-radius:8px;flex-shrink:0}
.cad-art-title{font-family:'Noto Sans Tamil',sans-serif;font-size:13.5px;font-weight:700;color:var(--charcoal,#1A1A1A);line-height:1.45}
.cad-art-meta{font-size:11px;color:var(--gray-4,#9A9890);margin-top:4px}

/* Lightbox */
.cad-lb{display:none;position:fixed;inset:0;background:rgba(10,10,10,.96);z-index:9999;align-items:center;justify-content:center}
.cad-lb-close{position:fixed;top:14px;right:16px;background:rgba(255,255,255,.12);border:none;color:#fff;width:38px;height:38px;border-radius:50%;font-size:18px;cursor:pointer;transition:background .15s}
.cad-lb-close:hover{background:var(--red,#C0001A)}
.cad-lb-nav{position:fixed;top:50%;transform:translateY(-50%);background:rgba(255,255,255,.12);border:none;color:#fff;width:42px;height:42px;border-radius:50%;font-size:24px;cursor:pointer;transition:background .15s}
.cad-lb-nav:hover{background:var(--red,#C0001A)}
.cad-lb-nav.prev{left:12px}
.cad-lb-nav.next{right:12px}
.cad-lb img{width:96vw;height:90vh;max-width:96vw;max-height:90vh;object-fit:contain;border-radius:10px;box-shadow:0 10px 40px rgba(0,0,0,.5)}
.cad-lb-count{position:fixed;bottom:14px;left:50%;transform:translateX(-50%);color:rgba(255,255,255,.6);font-size:11px;letter-spacing:.05em}

@media (max-width:600px){
  .cad-page{padding:14px 12px 70px}
  .cad-card{padding:18px 18px}
  .cad-title{font-size:20px}
}
</style>

<div class="cad-page">

  <!-- Company vertical poster — desktop sidebar only -->
  <?php if (!empty($companyVertical)): ?>
  <aside class="cad-sidebar cad-desktop-only">
    <div class="cad-ad-label">Advertisement</div>
    <img src="<?= $assetUrl . Helper::e($companyVertical[0]['filepath']) ?>"
         alt="<?= Helper::e($companyVertical[0]['alt_text'] ?: 'தினத்துளிர்') ?>">
  </aside>
  <?php endif; ?>

  <div class="cad-main">

  <!-- Company horizontal banner — mobile only -->
  <?php if (!empty($companyHorizontal)): ?>
  <div class="cad-hero-banner cad-mobile-only">
    <img src="<?= $assetUrl . Helper::e($companyHorizontal[0]['filepath']) ?>"
         alt="<?= Helper::e($companyHorizontal[0]['alt_text'] ?: 'தினத்துளிர்') ?>">
  </div>
  <?php endif; ?>

  <!-- Company square banners — desktop only, top of page -->
  <?php if (!empty($companySquares)): ?>
  <div class="cad-squares cad-desktop-only">
    <?php foreach (array_slice($companySquares, 0, 2) as $sq): ?>
    <div>
      <img src="<?= $assetUrl . Helper::e($sq['filepath']) ?>"
           alt="<?= Helper::e($sq['alt_text'] ?: 'தினத்துளிர்') ?>">
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <!-- Business info -->
  <div class="cad-card">
    <div class="cad-badge"><i class="bi bi-patch-check-fill"></i> Business Listing</div>
    <h1 class="cad-title"><?= Helper::e($bizName) ?></h1>

    <div class="cad-info-grid">
      <?php if ($contact): ?>
      <div class="cad-info-item">
        <div class="cad-info-icon"><i class="bi bi-person-fill"></i></div>
        <div class="cad-info-body"><div class="cad-info-label">Contact</div><div class="cad-info-value"><?= Helper::e($contact) ?></div></div>
      </div>
      <?php endif; ?>
      <?php if ($phone): ?>
      <div class="cad-info-item">
        <div class="cad-info-icon"><i class="bi bi-telephone-fill"></i></div>
        <div class="cad-info-body"><div class="cad-info-label">Phone</div><div class="cad-info-value"><a href="tel:<?= Helper::e($phone) ?>"><?= Helper::e($phone) ?></a></div></div>
      </div>
      <?php endif; ?>
      <?php if ($email): ?>
      <div class="cad-info-item">
        <div class="cad-info-icon"><i class="bi bi-envelope-fill"></i></div>
        <div class="cad-info-body"><div class="cad-info-label">Email</div><div class="cad-info-value"><a href="mailto:<?= Helper::e($email) ?>"><?= Helper::e($email) ?></a></div></div>
      </div>
      <?php endif; ?>
      <?php if (!empty($ad['district_name'])): ?>
      <div class="cad-info-item">
        <div class="cad-info-icon"><i class="bi bi-geo-alt-fill"></i></div>
        <div class="cad-info-body"><div class="cad-info-label">District</div><div class="cad-info-value"><?= Helper::e($ad['district_name']) ?></div></div>
      </div>
      <?php endif; ?>
      <?php if ($address): ?>
      <div class="cad-info-item">
        <div class="cad-info-icon"><i class="bi bi-signpost-fill"></i></div>
        <div class="cad-info-body"><div class="cad-info-label">Address</div><div class="cad-info-value"><?= nl2br(Helper::e($address)) ?></div></div>
      </div>
      <?php endif; ?>
      <?php if ($website): ?>
      <div class="cad-info-item">
        <div class="cad-info-icon"><i class="bi bi-globe2"></i></div>
        <div class="cad-info-body"><div class="cad-info-label">Website</div><div class="cad-info-value"><a href="<?= Helper::e($website) ?>" target="_blank" rel="noopener"><?= Helper::e(parse_url($website, PHP_URL_HOST) ?: $website) ?></a></div></div>
      </div>
      <?php endif; ?>
      <?php
      $_socials = [[$fb,'Facebook','bi-facebook'],[$ig,'Instagram','bi-instagram'],[$yt,'YouTube','bi-youtube']];
      foreach ($_socials as [$url,$lbl,$icon]): if (!$url) continue; ?>
      <div class="cad-info-item">
        <div class="cad-info-icon"><i class="bi <?= $icon ?>"></i></div>
        <div class="cad-info-body"><div class="cad-info-label"><?= $lbl ?></div><div class="cad-info-value"><a href="<?= Helper::e($url) ?>" target="_blank" rel="noopener">@<?= Helper::e(basename(rtrim($url,'/'))) ?></a></div></div>
      </div>
      <?php endforeach; ?>
    </div>

    <?php if ($desc): ?>
    <p class="cad-desc"><?= nl2br(Helper::e($desc)) ?></p>
    <?php endif; ?>

    <!-- Share row -->
    <?php
    $_adFbUrl = urlencode($shareUrl);
    $_adTwText = urlencode($bizName . ' ' . $shareUrl);
    ?>
    <div class="cad-share">
      <a href="https://wa.me/?text=<?= urlencode($bizName . "\n" . $shareUrl) ?>" target="_blank" rel="noopener" class="sbc sbc-wa">
        <i class="bi bi-whatsapp"></i><span>WhatsApp</span>
      </a>
      <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $_adFbUrl ?>" target="_blank" rel="noopener" class="sbc sbc-fb">
        <i class="bi bi-facebook"></i><span>Facebook</span>
      </a>
      <a href="https://twitter.com/intent/tweet?text=<?= $_adTwText ?>" target="_blank" rel="noopener" class="sbc sbc-tw">
        <i class="bi bi-twitter-x"></i><span>X</span>
      </a>
      <button id="cpBtn" onclick="copyLink()" class="sbc sbc-cp">
        <i class="bi bi-link-45deg"></i><span>Copy Link</span>
      </button>
    </div>
  </div>

  <!-- Images grouped by slot type — carousel per type -->
  <?php $lbOffset=0; $typeLabel=['square'=>'விளம்பர படங்கள்','horizontal'=>'Banner','vertical'=>'Vertical']; ?>
  <?php foreach ($imgByType as $type => $imgs): ?>
  <?php $n=count($imgs); $cid='cr'.$type; $lbl=$typeLabel[$type]??ucfirst($type); ?>

  <div class="cad-gallery">
    <?php if (count($imgByType)>1): ?>
    <div class="cad-gallery-label"><?= $lbl ?></div>
    <?php endif; ?>

    <div class="cad-car-wrap" id="<?= $cid ?>wrap">
      <div class="cad-car-track" id="<?= $cid ?>track">
        <?php foreach ($imgs as $i=>$img): $gi=$lbOffset+$i; ?>
        <div class="cad-car-slide" onclick="lbShow(<?= $gi ?>)">
          <img src="<?= $assetUrl . Helper::e($img['src']) ?>"
               alt="<?= Helper::e($bizName) ?>"
               style="aspect-ratio:<?= $type==='horizontal'?'20/3':($type==='vertical'?'1/3':'2/1') ?>;object-fit:contain"
               loading="<?= $i===0?'eager':'lazy' ?>">
        </div>
        <?php endforeach; ?>
      </div>

      <?php if ($n>1): ?>
      <button class="cad-car-btn prev" onclick="event.stopPropagation();carNav('<?= $cid ?>',-1)">‹</button>
      <button class="cad-car-btn next" onclick="event.stopPropagation();carNav('<?= $cid ?>',1)">›</button>
      <div class="cad-car-dots">
        <?php for($i=0;$i<$n;$i++): ?>
        <span onclick="event.stopPropagation();carGo('<?= $cid ?>',<?= $i ?>)" id="<?= $cid ?>dot<?= $i ?>" class="<?= $i===0?'on':'' ?>"></span>
        <?php endfor; ?>
      </div>
      <div class="cad-car-cnt" id="<?= $cid ?>cnt">1/<?= $n ?></div>
      <?php endif; ?>
    </div>
  </div>
  <?php $lbOffset+=$n; ?>
  <?php endforeach; ?>

  <!-- Sponsored News -->
  <?php if (!empty($articles)): ?>
  <div class="cad-section">
    <div class="cad-gallery-label">Sponsored Articles</div>
    <?php foreach ($articles as $art): ?>
    <a href="<?= $siteUrl ?>/article/<?= Helper::e($art['slug']) ?>" target="_blank" class="cad-art-row">
      <?php if (!empty($art['thumb_url'])||!empty($art['image_url'])): ?>
      <img src="<?= $assetUrl . Helper::e($art['thumb_url']?:$art['image_url']) ?>" alt="">
      <?php endif; ?>
      <div>
        <div class="cad-art-title"><?= Helper::e(mb_substr($art['title'],0,70)) ?></div>
        <div class="cad-art-meta"><?= Helper::timeAgo($art['published_at']) ?></div>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <!-- Recent News -->
  <?php if (!empty($recentArticles)): ?>
  <div class="cad-section">
    <div class="cad-gallery-label">சமீபத்திய செய்திகள் · Recent News</div>
    <div class="g4">
      <?php $_raArtCount = 0; foreach ($recentArticles as $ra):
        $raHasImg = !empty($ra['image_url']); $_raArtCount++; ?>
      <a href="<?= $siteUrl ?>/article/<?= Helper::e($ra['slug']) ?>" class="nc <?= $raHasImg ? '' : 'nc-no-img' ?>">
        <?php if ($raHasImg): ?>
        <img src="<?= $assetUrl . Helper::e($ra['thumb_url'] ?: $ra['image_url']) ?>" alt="<?= Helper::e($ra['title']) ?>" loading="lazy" onerror="this.remove()">
        <?php endif; ?>
        <div class="nc-body">
          <span class="ctag"><?= Helper::e($ra['category_tamil'] ?: $ra['category_name']) ?></span>
          <div class="nc-title <?= $raHasImg ? '' : 'nc-title-lg' ?>"><?= Helper::e($ra['title']) ?></div>
          <?php if (!$raHasImg && !empty($ra['excerpt'])): ?>
          <div class="nc-no-img-excerpt"><?= Helper::e(mb_substr(strip_tags($ra['excerpt']), 0, 140)) ?></div>
          <?php endif; ?>
          <div class="hero4-meta notranslate" translate="no"><?= Helper::numericDate($ra['published_at']) ?></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  </div>
</div>

<!-- Lightbox -->
<div id="lb" class="cad-lb" onclick="if(event.target===this)lbHide()" role="dialog" aria-modal="true">
  <button onclick="lbHide()" class="cad-lb-close" aria-label="Close">✕</button>
  <?php if (count($lbSrcs)>1): ?>
  <button onclick="lbPrev()" class="cad-lb-nav prev">‹</button>
  <button onclick="lbNext()" class="cad-lb-nav next">›</button>
  <?php endif; ?>
  <img id="lbI" src="" alt="">
  <?php if (count($lbSrcs)>1): ?>
  <div id="lbN" class="cad-lb-count"></div>
  <?php endif; ?>
</div>

<script>
/* Carousel */
var _cs={};
function _cApply(id){
  var s=_cs[id];if(!s)return;
  document.getElementById(id+'track').style.transform='translateX(-'+(s.i*100)+'%)';
  for(var x=0;x<s.n;x++){var d=document.getElementById(id+'dot'+x);if(d)d.className=x===s.i?'on':'';}
  var c=document.getElementById(id+'cnt');if(c)c.textContent=(s.i+1)+'/'+s.n;
}
function carNav(id,d){ var s=_cs[id];if(!s)return; s.i=(s.i+d+s.n)%s.n; _cApply(id); }
function carGo(id,i){ _cs[id].i=i; _cApply(id); }

document.querySelectorAll('[id$="track"]').forEach(function(tr){
  var id=tr.id.replace('track','');
  var n=tr.children.length;
  _cs[id]={n:n,i:0};
  var sx=0,sy=0;
  var wrap=tr.parentElement;
  wrap.addEventListener('touchstart',function(e){sx=e.touches[0].clientX;sy=e.touches[0].clientY;},{passive:true});
  wrap.addEventListener('touchend',function(e){
    var dx=e.changedTouches[0].clientX-sx,dy=e.changedTouches[0].clientY-sy;
    if(Math.abs(dx)>Math.abs(dy)&&Math.abs(dx)>30) carNav(id,dx<0?1:-1);
  });
});

/* Lightbox */
var _src=<?= json_encode($lbSrcs) ?>;
var _li=0;
function lbShow(i){ _li=i; document.getElementById('lbI').src=_src[i]||''; var n=document.getElementById('lbN'); if(n)n.textContent=(i+1)+' / '+_src.length; document.getElementById('lb').style.display='flex'; document.body.style.overflow='hidden'; }
function lbHide(){ document.getElementById('lb').style.display='none'; document.body.style.overflow=''; }
function lbPrev(){ _li=(_li-1+_src.length)%_src.length; lbShow(_li); }
function lbNext(){ _li=(_li+1)%_src.length; lbShow(_li); }
document.addEventListener('keydown',function(e){ if(document.getElementById('lb').style.display!=='flex')return; if(e.key==='Escape')lbHide(); if(e.key==='ArrowLeft')lbPrev(); if(e.key==='ArrowRight')lbNext(); });

function copyLink(){ navigator.clipboard?.writeText(<?= json_encode($bizName . "\n" . $shareUrl) ?>).then(function(){ var b=document.getElementById('cpBtn'); b.innerHTML='<i class="bi bi-check2"></i> Copied'; setTimeout(function(){b.innerHTML='<i class="bi bi-link-45deg"></i> Copy Link';},2000); }); }
</script>
