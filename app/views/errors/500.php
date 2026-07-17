<?php
$code = 500;
$title = 'Server error';
$message = 'Something went wrong on our side. Please try again shortly.';
$homeUrl = \App\Core\Helper::siteUrl() . '/';
require __DIR__ . '/generic.php';
