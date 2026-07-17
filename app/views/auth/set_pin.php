<!DOCTYPE html>
<html lang="ta">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Set your PIN — Thinathulir</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Tamil:wght@400;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="<?= ASSET_URL ?>/assets/css/portal.css" rel="stylesheet">
</head>
<body style="min-height:100vh;background:#F5F5F0;display:flex;align-items:center;justify-content:center;padding:20px">

<div style="width:100%;max-width:420px">

  <!-- LOGO -->
  <div style="text-align:center;margin-bottom:32px">
    <div style="display:inline-flex;align-items:center;gap:12px;text-decoration:none">
      <div style="width:48px;height:48px;background:#C0001A;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px">📰</div>
      <div style="text-align:left">
        <div style="font-family:'Noto Sans Tamil',sans-serif;font-size:22px;font-weight:700;color:#0B2265;line-height:1">THINATHULIR</div>
        <div style="font-size:11px;color:#9A9890;letter-spacing:1.5px;text-transform:uppercase">Staff Portal</div>
      </div>
    </div>
  </div>

  <!-- CARD -->
  <div style="background:#fff;border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,.08);padding:32px">
    <h2 style="font-size:20px;font-weight:700;margin-bottom:6px;color:#1A1A1A">Set a 6-digit PIN</h2>
    <p style="font-size:13px;color:#9A9890;margin-bottom:24px">Use this PIN to sign in faster next time on this device.</p>

    <?php
    $alertType = \App\Core\Session::getFlash('alert_type');
    $alertMsg  = \App\Core\Session::getFlash('alert_msg');
    if ($alertType && $alertMsg):
    ?>
    <div class="alert alert-<?= $alertType ?> py-2 px-3 mb-3 rounded-3 small">
      <?= htmlspecialchars($alertMsg) ?>
    </div>
    <?php endif; ?>

    <form action="<?= ASSET_URL ?>/portal/set-pin" method="POST" id="pinForm">
      <?= \App\Core\CSRF::field() ?>

      <div class="mb-3">
        <label class="form-label fw-600 small">New PIN</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
          <input type="password" name="pin" class="form-control pin-input"
                 placeholder="6-digit PIN" inputmode="numeric" pattern="\d{6}"
                 maxlength="6" autocomplete="off" required autofocus>
        </div>
      </div>

      <div class="mb-4">
        <label class="form-label fw-600 small">Confirm PIN</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
          <input type="password" name="pin_confirm" class="form-control pin-input"
                 placeholder="Re-enter PIN" inputmode="numeric" pattern="\d{6}"
                 maxlength="6" autocomplete="off" required>
        </div>
      </div>

      <button type="submit" class="btn btn-danger w-100 py-2 fw-600">
        <i class="bi bi-check-circle me-2"></i>Save PIN
      </button>
    </form>
  </div>

</div>

<script>
document.querySelectorAll('.pin-input').forEach(function (el) {
  el.addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '').slice(0, 6);
  });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
