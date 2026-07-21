<!DOCTYPE html>
<html lang="ta">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>/public/favicon.svg">
<title><?= htmlspecialchars($pageTitle ?? 'Sub Admin Panel') ?> — Thinathulir</title>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Tamil:wght@400;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="<?= \App\Core\Helper::assetVersioned('/assets/css/portal.css') ?>" rel="stylesheet">
<meta name="csrf-token" content="<?= \App\Core\CSRF::token() ?>">
<meta name="base-url"   content="<?= ASSET_URL ?>">
</head>
<body class="portal-body">

<?php
$auth      = \App\Core\Auth::user();
$userName  = $auth['name']  ?? 'Sub Admin';
$userEmail = $auth['email'] ?? '';
$r         = rtrim(ASSET_URL, '/') . '/public';
$current   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$roleColor = '#7C3AED';

function subActive(string $p, string $c): string {
    return str_contains($c, $p) ? 'active' : '';
}

/* Pending-count badges — best-effort, never block the panel if a table drifts */
$pendingNews = 0; $pendingAds = 0;
try { $pendingNews = (new \App\Models\ArticleModel())->count('status = ?', ['review']); } catch (\Exception $e) {}
try { $pendingAds   = (new \App\Models\BusinessAdModel())->count('status = ?', ['pending']); } catch (\Exception $e) {}
$pendingTotal = $pendingNews + $pendingAds;
?>

<!-- Topbar — deliberately only 4 modules: Import URL, Approvals, Rate Cards, plus Dashboard -->
<div class="portal-topbar">
  <div class="portal-topbar-inner">

    <a href="<?= $r ?>/panel/dashboard" class="portal-logo">
      <div class="portal-logo-icon" style="background:<?= $roleColor ?>">🛡️</div>
      <div>
        <div class="portal-logo-title">
          <span style="color:#C0001A;font-family:'Noto Sans Tamil',sans-serif;font-weight:900">தினத்</span><span style="color:#fff;background:#C0001A;padding:0 5px;border-radius:3px;font-family:'Noto Sans Tamil',sans-serif;font-weight:900;margin-left:2px">துளிர்</span>
        </div>
        <div class="portal-logo-sub" style="color:<?= $roleColor ?>">Sub Admin Panel</div>
      </div>
    </a>

    <nav class="portal-nav">
      <a href="<?= $r ?>/panel/dashboard" class="portal-nav-link <?= subActive('/panel/dashboard',$current) ?>">
        <i class="bi bi-speedometer2"></i><span>Dashboard</span>
      </a>
      <a href="<?= $r ?>/panel/import" class="portal-nav-link <?= subActive('/panel/import',$current) ?>">
        <i class="bi bi-link-45deg"></i><span>Import URL</span>
      </a>
      <a href="<?= $r ?>/panel/approvals/news" class="portal-nav-link <?= subActive('/panel/approvals',$current) ?>">
        <i class="bi bi-check2-square"></i><span>Approvals</span>
        <?php if ($pendingTotal > 0): ?> <em class="portal-nav-badge"><?= $pendingTotal > 9 ? '9+' : $pendingTotal ?></em><?php endif; ?>
      </a>
      <a href="<?= $r ?>/panel/rates" class="portal-nav-link <?= subActive('/panel/rates',$current) ?>">
        <i class="bi bi-currency-exchange"></i><span>Rate Cards</span>
      </a>
    </nav>

    <div class="portal-topbar-right">
      <div class="portal-user" onclick="togglePortalMenu()">
        <div class="portal-user-avatar" style="background:<?= $roleColor ?>">
          <?= strtoupper(substr($userName,0,1)) ?>
        </div>
        <span class="portal-user-name d-none d-lg-inline"><?= htmlspecialchars(explode(' ',$userName)[0]) ?></span>
        <div class="portal-user-dropdown" id="portalUserMenu">
          <div class="portal-user-dropdown-header">
            <div class="fw-600"><?= htmlspecialchars($userName) ?></div>
            <div style="font-size:11px;color:#6B6A64"><?= htmlspecialchars($userEmail) ?></div>
            <span class="portal-role-badge" style="background:<?= $roleColor ?>">Sub Admin</span>
          </div>
          <a href="<?= $r ?>/admin/logout" class="portal-user-dropdown-item" style="color:#C0001A">
            <i class="bi bi-box-arrow-right me-2"></i>Logout
          </a>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- Flash -->
<?php
$alertType = \App\Core\Session::getFlash('alert_type');
$alertMsg  = \App\Core\Session::getFlash('alert_msg');
if ($alertType && $alertMsg):
?>
<div style="max-width:1200px;margin:12px auto 0;padding:0 20px">
  <div class="alert alert-<?= $alertType ?> alert-dismissible fade show mb-0">
    <?= htmlspecialchars($alertMsg) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
</div>
<?php endif; ?>

<div class="portal-content"><?= $content ?></div>

<div class="portal-footer">
  <span>© <?= date('Y') ?> Thinathulir</span>
  <span style="color:<?= $roleColor ?>;font-weight:700">Sub Admin</span>
</div>

<!-- Mobile footer -->
<nav class="portal-mob-footer" style="--role-color:<?= $roleColor ?>">
  <a href="<?= $r ?>/panel/dashboard" class="portal-mob-item <?= subActive('/panel/dashboard',$current) ?>">
    <i class="bi bi-house-fill"></i><span>Home</span>
  </a>
  <a href="<?= $r ?>/panel/import" class="portal-mob-item <?= subActive('/panel/import',$current) ?>">
    <i class="bi bi-link-45deg"></i><span>Import</span>
  </a>
  <a href="<?= $r ?>/panel/approvals/news" class="portal-mob-item <?= subActive('/panel/approvals',$current) ?>">
    <i class="bi bi-check2-square"></i><span>Approve</span>
    <?php if ($pendingTotal > 0): ?><span class="portal-mob-badge"><?= $pendingTotal > 9 ? '9+' : $pendingTotal ?></span><?php endif; ?>
  </a>
  <a href="<?= $r ?>/panel/rates" class="portal-mob-item <?= subActive('/panel/rates',$current) ?>">
    <i class="bi bi-currency-exchange"></i><span>Rates</span>
  </a>
  <a href="<?= $r ?>/admin/logout" class="portal-mob-item">
    <i class="bi bi-box-arrow-right"></i><span>Logout</span>
  </a>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= \App\Core\Helper::assetVersioned('/assets/js/portal-nav.js') ?>"></script>
</body>
</html>
