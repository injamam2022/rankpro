<?php

$publicPath = getcwd();

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

// Blade uses {{ asset('') }}web/... ; some templates still produce //web/...
// Apache collapses that; PHP's built-in server does not.
$uri = preg_replace('#/+#', '/', $uri);

if ($uri !== '/' && file_exists($publicPath.$uri)) {
    return false;
}

require_once $publicPath.'/index.php';
