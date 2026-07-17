<!DOCTYPE html>
<html lang="ta" data-bs-theme="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — Thinathulir Admin</title>
<meta property="og:title" content="Login — Thinathulir Admin">
<meta property="og:description" content="Staff login for Thinathulir Admin">
<meta property="og:image" content="<?= htmlspecialchars(\App\Core\Helper::shareImageUrl(null)) ?>">
<meta property="og:url" content="<?= htmlspecialchars(\App\Core\Helper::siteUrl() . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) ?>">
<meta property="og:type" content="website">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Login — Thinathulir Admin">
<meta name="twitter:description" content="Staff login for Thinathulir Admin">
<meta name="twitter:image" content="<?= htmlspecialchars(\App\Core\Helper::shareImageUrl(null)) ?>">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="<?= \App\Core\Helper::assetVersioned('/assets/css/admin.css') ?>" rel="stylesheet">
</head>
<body class="tn-auth-body">
<?= $content ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
