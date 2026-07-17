<!DOCTYPE html>
<html lang="ta">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle ?? 'Login') ?> — Thinathulir</title>
<meta property="og:title" content="<?= htmlspecialchars($pageTitle ?? 'Login') ?> — Thinathulir">
<meta property="og:description" content="Staff login for Thinathulir">
<meta property="og:image" content="<?= htmlspecialchars(\App\Core\Helper::shareImageUrl(null)) ?>">
<meta property="og:url" content="<?= htmlspecialchars(\App\Core\Helper::siteUrl() . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) ?>">
<meta property="og:type" content="website">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= htmlspecialchars($pageTitle ?? 'Login') ?> — Thinathulir">
<meta name="twitter:description" content="Staff login for Thinathulir">
<meta name="twitter:image" content="<?= htmlspecialchars(\App\Core\Helper::shareImageUrl(null)) ?>">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Tamil:wght@400;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="<?= \App\Core\Helper::assetVersioned('/assets/css/portal.css') ?>" rel="stylesheet">
</head>
<body style="min-height:100vh;background:#F5F5F0;display:flex;align-items:center;justify-content:center;padding:20px">
<?php
$alertType = \App\Core\Session::getFlash('alert_type');
$alertMsg  = \App\Core\Session::getFlash('alert_msg');
?>
<?= $content ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
