<?php
$code        = (int)($code ?? 500);
$title       = $title ?? 'Something went wrong';
$message     = $message ?? 'Please return to the home page.';
$homeUrl     = $homeUrl ?? (\App\Core\Helper::siteUrl() . '/');
$delaySeconds = (int)($delaySeconds ?? 6);
?>
<!DOCTYPE html>
<html lang="ta">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($code . ' — Thinathulir') ?></title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="refresh" content="<?= $delaySeconds ?>;url=<?= htmlspecialchars($homeUrl) ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#F5F5F0;color:#1A1A1A;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;font-family:Arial,sans-serif}
.wrap{max-width:520px;width:100%;text-align:center;background:#fff;border:1px solid #E5E7EB;border-radius:18px;padding:32px 24px;box-shadow:0 20px 60px rgba(0,0,0,.08)}
.code{font-size:72px;font-weight:900;line-height:1;color:#C0001A;letter-spacing:-3px}
.title{font-size:24px;font-weight:800;margin:10px 0 8px}
.msg{font-size:14px;line-height:1.7;color:#6B7280;margin-bottom:22px}
.btn-home{display:inline-flex;align-items:center;justify-content:center;padding:11px 20px;border-radius:10px;background:#C0001A;color:#fff;text-decoration:none;font-weight:700}
.meta{margin-top:14px;font-size:12px;color:#9CA3AF}
</style>
</head>
<body>
<div class="wrap">
  <div class="code"><?= htmlspecialchars((string)$code) ?></div>
  <div class="title"><?= htmlspecialchars($title) ?></div>
  <div class="msg"><?= htmlspecialchars($message) ?></div>
  <a class="btn-home" href="<?= htmlspecialchars($homeUrl) ?>">Go to Home</a>
  <div class="meta">Redirecting automatically in <?= (int)$delaySeconds ?> seconds</div>
</div>
<script>
setTimeout(function () {
  window.location.href = <?= json_encode($homeUrl) ?>;
}, <?= (int)$delaySeconds * 1000 ?>);
</script>
</body>
</html>
