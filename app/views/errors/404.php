<?php
$code = 404;
$title = 'Page not found';
$message = 'The page you requested does not exist or has been moved.';
$homeUrl = \App\Core\Helper::siteUrl() . '/';
require __DIR__ . '/generic.php';
