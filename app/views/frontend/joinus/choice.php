<div class="breadcrumb">
  <a href="<?= $r ?>/">முகப்பு</a>
  <span>›</span>
  <span>எங்களுடன் இணையுங்கள்</span>
</div>

<div class="page-layout-wrap">
  <div class="main">
    <div class="sec-head sec-head-mt">
      <span class="sec-head-bar" style="--ac:#C0001A"></span>
      <span class="sec-head-title">எங்களுடன் இணையுங்கள்</span>
      <span class="sec-head-ta">Join Us</span>
    </div>

    <div class="citizen-intro">
      <p>நீங்கள் எப்படி எங்களுடன் இணைய விரும்புகிறீர்கள் என்பதைத் தேர்ந்தெடுக்கவும்.</p>
    </div>

    <div class="join-choice-grid">
      <?php if (empty($_SESSION['reader_id'])): ?>
      <a href="<?= $baseUrl ?>/public/auth/reader/login?return=<?= urlencode('/public/citizen-reporter') ?>" class="join-choice-card">
      <?php else: ?>
      <a href="<?= $r ?>/citizen-reporter" class="join-choice-card">
      <?php endif; ?>
        <div class="join-choice-icon">📰</div>
        <div class="join-choice-title">குடிமக்கள் நிருபர்</div>
        <div class="join-choice-sub">Citizen Reporter</div>
        <p class="join-choice-desc">உங்கள் பகுதியில் நடக்கும் செய்திகளை உடனடியாக அனுப்புங்கள் — Google கணக்குடன் உள்நுழையவும்.</p>
      </a>

      <a href="<?= $r ?>/join-us/reporter" class="join-choice-card">
        <div class="join-choice-icon">🎙️</div>
        <div class="join-choice-title">தினத்துளிர் நிருபர்</div>
        <div class="join-choice-sub">Reporter in Thinathulir</div>
        <p class="join-choice-desc">தினத்துளிரின் நிருபராக இணைய விண்ணப்பிக்கவும் — உங்கள் விவரங்களை பதிவு செய்யுங்கள்.</p>
      </a>
    </div>
  </div>
</div>
