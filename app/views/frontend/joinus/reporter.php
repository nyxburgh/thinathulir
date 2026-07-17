<?php use App\Core\{Helper, CSRF, Session}; ?>

<div class="breadcrumb">
  <a href="<?= $r ?>/">முகப்பு</a>
  <span>›</span>
  <a href="<?= $r ?>/join-us">எங்களுடன் இணையுங்கள்</a>
  <span>›</span>
  <span>தினத்துளிர் நிருபர்</span>
</div>

<div class="page-layout-wrap">
  <div class="main">
    <div class="sec-head sec-head-mt">
      <span class="sec-head-bar" style="--ac:#C0001A"></span>
      <span class="sec-head-title">தினத்துளிர் நிருபர்</span>
      <span class="sec-head-ta">Reporter in Thinathulir</span>
    </div>

    <div class="citizen-intro">
      <p>உங்கள் அடிப்படை விவரங்களை பதிவு செய்யுங்கள் — ஆசிரியர் குழு விரைவில் உங்களைத் தொடர்பு கொள்ளும்.</p>
    </div>

    <?php
    $alertType = Session::getFlash('alert_type');
    $alertMsg  = Session::getFlash('alert_msg');
    if ($alertType && $alertMsg):
    ?>
    <div class="alert alert-<?= Helper::e($alertType) ?>" role="alert"><?= Helper::e($alertMsg) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= $r ?>/join-us/reporter" class="citizen-form">
      <?= CSRF::field() ?>

      <div class="citizen-form-row">
        <div class="citizen-form-group">
          <label for="ra_name" class="citizen-label">பெயர் <span class="req">*</span></label>
          <input type="text" id="ra_name" name="name" class="citizen-input" required placeholder="உங்கள் பெயர்">
        </div>
        <div class="citizen-form-group">
          <label for="ra_phone" class="citizen-label">தொலைபேசி <span class="req">*</span></label>
          <input type="tel" id="ra_phone" name="phone" class="citizen-input" required placeholder="9XXXXXXXXX">
        </div>
      </div>

      <div class="citizen-form-row">
        <div class="citizen-form-group">
          <label for="ra_email" class="citizen-label">மின்னஞ்சல்</label>
          <input type="email" id="ra_email" name="email" class="citizen-input" placeholder="விருப்பமான">
        </div>
        <div class="citizen-form-group">
          <label for="ra_district" class="citizen-label">மாவட்டம்</label>
          <select id="ra_district" name="district_id" class="citizen-input">
            <option value="">-- தேர்ந்தெடுக்கவும் --</option>
            <?php foreach ($districts as $d): ?>
            <option value="<?= $d['id'] ?>"><?= Helper::e($d['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="citizen-form-group">
        <label for="ra_experience" class="citizen-label">அனுபவம் (இருந்தால்)</label>
        <input type="text" id="ra_experience" name="experience" class="citizen-input" placeholder="எ.கா. 2 வருடங்கள் பத்திரிகை அனுபவம்">
      </div>

      <div class="citizen-form-group">
        <label for="ra_message" class="citizen-label">உங்களைப் பற்றி</label>
        <textarea id="ra_message" name="message" class="citizen-input citizen-textarea"
                  placeholder="ஏன் நிருபராக இணைய விரும்புகிறீர்கள் என்பதை எழுதுங்கள்"></textarea>
      </div>

      <button type="submit" class="citizen-submit">விண்ணப்பிக்க →</button>
    </form>
  </div>
</div>
