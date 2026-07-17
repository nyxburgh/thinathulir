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

<div style="max-width:680px;margin:0 auto;padding:20px 16px 80px">

  <!-- Business info — clean professional list -->
  <div style="margin-bottom:24px">
    <h1 style="font-family:'Noto Sans Tamil',sans-serif;font-size:22px;font-weight:900;color:#111;margin:0 0 12px;line-height:1.3"><?= Helper::e($bizName) ?></h1>

    <dl style="margin:0;display:grid;grid-template-columns:max-content 1fr;gap:4px 16px;font-size:13px">
      <?php if ($contact): ?>
      <dt style="color:#9CA3AF;font-weight:600;padding:4px 0">Contact</dt>
      <dd style="margin:0;color:#1A1A1A;padding:4px 0"><?= Helper::e($contact) ?></dd>
      <?php endif; ?>
      <?php if ($phone): ?>
      <dt style="color:#9CA3AF;font-weight:600;padding:4px 0">Phone</dt>
      <dd style="margin:0;padding:4px 0"><a href="tel:<?= Helper::e($phone) ?>" style="color:#C0001A;font-weight:600;text-decoration:none"><?= Helper::e($phone) ?></a></dd>
      <?php endif; ?>
      <?php if ($email): ?>
      <dt style="color:#9CA3AF;font-weight:600;padding:4px 0">Email</dt>
      <dd style="margin:0;padding:4px 0"><a href="mailto:<?= Helper::e($email) ?>" style="color:#1A1A1A;text-decoration:none"><?= Helper::e($email) ?></a></dd>
      <?php endif; ?>
      <?php if (!empty($ad['district_name'])): ?>
      <dt style="color:#9CA3AF;font-weight:600;padding:4px 0">District</dt>
      <dd style="margin:0;color:#1A1A1A;padding:4px 0"><?= Helper::e($ad['district_name']) ?></dd>
      <?php endif; ?>
      <?php if ($address): ?>
      <dt style="color:#9CA3AF;font-weight:600;padding:4px 0">Address</dt>
      <dd style="margin:0;color:#1A1A1A;padding:4px 0;line-height:1.6"><?= nl2br(Helper::e($address)) ?></dd>
      <?php endif; ?>
      <?php if ($website): ?>
      <dt style="color:#9CA3AF;font-weight:600;padding:4px 0">Website</dt>
      <dd style="margin:0;padding:4px 0"><a href="<?= Helper::e($website) ?>" target="_blank" rel="noopener" style="color:#C0001A;text-decoration:none"><?= Helper::e(parse_url($website, PHP_URL_HOST) ?: $website) ?></a></dd>
      <?php endif; ?>
      <?php foreach ([[$fb,'Facebook'],[$ig,'Instagram'],[$yt,'YouTube']] as [$url,$lbl]): ?>
      <?php if ($url): ?>
      <dt style="color:#9CA3AF;font-weight:600;padding:4px 0"><?= $lbl ?></dt>
      <dd style="margin:0;padding:4px 0"><a href="<?= Helper::e($url) ?>" target="_blank" rel="noopener" style="color:#C0001A;text-decoration:none">@<?= Helper::e(basename(rtrim($url,'/'))) ?></a></dd>
      <?php endif; ?>
      <?php endforeach; ?>
    </dl>

    <?php if ($desc): ?>
    <p style="margin:12px 0 0;font-size:13px;color:#6B7280;line-height:1.7;border-top:1px solid #F3F4F6;padding-top:12px"><?= nl2br(Helper::e($desc)) ?></p>
    <?php endif; ?>

    <!-- Share row -->
    <?php
    $_adFbUrl = urlencode($shareUrl);
    $_adTwText = urlencode($bizName . ' ' . $shareUrl);
    ?>
    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:14px;padding-top:14px;border-top:1px solid #F3F4F6">
      <a href="https://wa.me/?text=<?= urlencode($bizName . "\n" . $shareUrl) ?>" target="_blank" rel="noopener"
         style="display:inline-flex;align-items:center;gap:5px;font-size:12px;color:#25D366;font-weight:600;text-decoration:none;padding:6px 12px;border:1px solid #25D366;border-radius:6px">
        <i class="bi bi-whatsapp"></i> WhatsApp
      </a>
      <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $_adFbUrl ?>" target="_blank" rel="noopener"
         style="display:inline-flex;align-items:center;gap:5px;font-size:12px;color:#1877F2;font-weight:600;text-decoration:none;padding:6px 12px;border:1px solid #1877F2;border-radius:6px">
        <i class="bi bi-facebook"></i> Facebook
      </a>
      <a href="https://twitter.com/intent/tweet?text=<?= $_adTwText ?>" target="_blank" rel="noopener"
         style="display:inline-flex;align-items:center;gap:5px;font-size:12px;color:#1A1A1A;font-weight:600;text-decoration:none;padding:6px 12px;border:1px solid #1A1A1A;border-radius:6px">
        <i class="bi bi-twitter-x"></i> X
      </a>
      <button id="cpBtn" onclick="copyLink()"
              style="display:inline-flex;align-items:center;gap:5px;font-size:12px;color:#6B7280;font-weight:600;background:none;border:1px solid #E5E7EB;border-radius:6px;padding:6px 12px;cursor:pointer">
        <i class="bi bi-link-45deg"></i> Copy Link
      </button>
    </div>
  </div>

  <!-- Images grouped by slot type — carousel per type -->
  <?php $lbOffset=0; $typeLabel=['square'=>'விளம்பர படங்கள்','horizontal'=>'Banner','vertical'=>'Vertical']; ?>
  <?php foreach ($imgByType as $type => $imgs): ?>
  <?php $n=count($imgs); $cid='cr'.$type; $lbl=$typeLabel[$type]??ucfirst($type); ?>

  <div style="margin-bottom:24px">
    <?php if (count($imgByType)>1): ?>
    <div style="font-size:10px;font-weight:700;color:#9CA3AF;text-transform:uppercase;letter-spacing:.1em;margin-bottom:8px"><?= $lbl ?></div>
    <?php endif; ?>

    <!-- Carousel container — full width, image inside fills it -->
    <div style="position:relative;width:100%;border-radius:6px;overflow:hidden" id="<?= $cid ?>wrap">
      <div id="<?= $cid ?>track"
           style="display:flex;width:100%;transition:transform .35s cubic-bezier(.4,0,.2,1)">
        <?php foreach ($imgs as $i=>$img): $gi=$lbOffset+$i; ?>
        <div style="min-width:100%;width:100%;flex-shrink:0;box-sizing:border-box;cursor:zoom-in" onclick="lbShow(<?= $gi ?>)">
          <img src="<?= $assetUrl . Helper::e($img['src']) ?>"
               alt="<?= Helper::e($bizName) ?>"
               style="width:100%;aspect-ratio:<?= $type==='horizontal'?'20/3':($type==='vertical'?'1/3':'2/1') ?>;object-fit:contain;display:block"
               loading="<?= $i===0?'eager':'lazy' ?>">
        </div>
        <?php endforeach; ?>
      </div>

      <?php if ($n>1): ?>
      <button onclick="event.stopPropagation();carNav('<?= $cid ?>',-1)"
              style="position:absolute;left:8px;top:50%;transform:translateY(-50%);background:rgba(0,0,0,.45);color:#fff;border:none;width:30px;height:30px;border-radius:50%;font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;z-index:2">‹</button>
      <button onclick="event.stopPropagation();carNav('<?= $cid ?>',1)"
              style="position:absolute;right:8px;top:50%;transform:translateY(-50%);background:rgba(0,0,0,.45);color:#fff;border:none;width:30px;height:30px;border-radius:50%;font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;z-index:2">›</button>
      <div style="position:absolute;bottom:8px;left:50%;transform:translateX(-50%);display:flex;gap:5px;z-index:2">
        <?php for($i=0;$i<$n;$i++): ?>
        <span onclick="event.stopPropagation();carGo('<?= $cid ?>',<?= $i ?>)" id="<?= $cid ?>dot<?= $i ?>"
              style="width:6px;height:6px;border-radius:50%;background:<?= $i===0?'#fff':'rgba(255,255,255,.4)' ?>;cursor:pointer;display:block;transition:.2s"></span>
        <?php endfor; ?>
      </div>
      <div style="position:absolute;top:8px;right:10px;background:rgba(0,0,0,.5);color:#fff;font-size:10px;padding:2px 7px;border-radius:8px" id="<?= $cid ?>cnt">1/<?= $n ?></div>
      <?php endif; ?>
    </div>
  </div>
  <?php $lbOffset+=$n; ?>
  <?php endforeach; ?>

  <!-- Sponsored News -->
  <?php if (!empty($articles)): ?>
  <div style="border-top:1px solid #F3F4F6;padding-top:20px;margin-top:4px">
    <div style="font-size:10px;font-weight:700;color:#9CA3AF;text-transform:uppercase;letter-spacing:.1em;margin-bottom:12px">Sponsored Articles</div>
    <?php foreach ($articles as $art): ?>
    <a href="<?= $siteUrl ?>/article/<?= Helper::e($art['slug']) ?>" target="_blank"
       style="display:flex;gap:10px;padding:10px 0;border-bottom:1px solid #F9FAFB;text-decoration:none;align-items:flex-start">
      <?php if (!empty($art['thumb_url'])||!empty($art['image_url'])): ?>
      <img src="<?= $assetUrl . Helper::e($art['thumb_url']?:$art['image_url']) ?>" alt=""
           style="width:56px;height:56px;object-fit:cover;border-radius:6px;flex-shrink:0">
      <?php endif; ?>
      <div>
        <div style="font-family:'Noto Sans Tamil',sans-serif;font-size:13px;font-weight:700;color:#111;line-height:1.4"><?= Helper::e(mb_substr($art['title'],0,70)) ?></div>
        <div style="font-size:11px;color:#9CA3AF;margin-top:3px"><?= Helper::timeAgo($art['published_at']) ?></div>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <!-- Recent News -->
  <?php if (!empty($recentArticles)): ?>
  <div style="border-top:1px solid #F3F4F6;padding-top:20px;margin-top:20px">
    <div style="font-size:10px;font-weight:700;color:#9CA3AF;text-transform:uppercase;letter-spacing:.1em;margin-bottom:12px">சமீபத்திய செய்திகள் · Recent News</div>
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
      <?php if ($_raArtCount % 3 === 0): ?>
      <div class="nc nc-ad notranslate" translate="no">
        <span class="nc-ad-label">Ad</span>
        <div class="ad-rotator" data-ad-pool="square"></div>
      </div>
      <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

</div>

<!-- Lightbox -->
<div id="lb" onclick="if(event.target===this)lbHide()"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.95);z-index:9999;align-items:center;justify-content:center"
     role="dialog" aria-modal="true">
  <button onclick="lbHide()" style="position:fixed;top:12px;right:14px;background:rgba(255,255,255,.15);border:none;color:#fff;width:36px;height:36px;border-radius:50%;font-size:18px;cursor:pointer" aria-label="Close">✕</button>
  <?php if (count($lbSrcs)>1): ?>
  <button onclick="lbPrev()" style="position:fixed;left:8px;top:50%;transform:translateY(-50%);background:rgba(255,255,255,.15);border:none;color:#fff;width:38px;height:38px;border-radius:50%;font-size:24px;cursor:pointer">‹</button>
  <button onclick="lbNext()" style="position:fixed;right:8px;top:50%;transform:translateY(-50%);background:rgba(255,255,255,.15);border:none;color:#fff;width:38px;height:38px;border-radius:50%;font-size:24px;cursor:pointer">›</button>
  <?php endif; ?>
  <img id="lbI" src="" alt="" style="max-width:96vw;max-height:90vh;object-fit:contain;border-radius:6px">
  <?php if (count($lbSrcs)>1): ?>
  <div id="lbN" style="position:fixed;bottom:12px;left:50%;transform:translateX(-50%);color:rgba(255,255,255,.55);font-size:11px"></div>
  <?php endif; ?>
</div>

<script>
/* Carousel */
var _cs={};
function _cApply(id){
  var s=_cs[id];if(!s)return;
  document.getElementById(id+'track').style.transform='translateX(-'+(s.i*100)+'%)';
  for(var x=0;x<s.n;x++){var d=document.getElementById(id+'dot'+x);if(d)d.style.background=x===s.i?'#fff':'rgba(255,255,255,.4)';}
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
