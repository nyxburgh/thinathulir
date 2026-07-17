<?php
$code = 400;
$title = 'Bad request';
$message = 'The request could not be processed.';
$homeUrl = \App\Core\Helper::siteUrl() . '/';
require __DIR__ . '/generic.php';
