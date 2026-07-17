<?php
$code = 403;
$title = 'Access denied';
$message = 'You do not have permission to view this page.';
$homeUrl = \App\Core\Helper::siteUrl() . '/';
require __DIR__ . '/generic.php';
