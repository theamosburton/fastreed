<?php
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$basePath = dirname($_SERVER['SCRIPT_NAME']);
$baseUrl = $protocol . '://' . $host . $basePath . '/';
echo $baseUrl;
$urlParts = explode('/', $baseUrl);
$firstPathSegment = $urlParts[3];
$newUrl = $protocol . '://' . $host . '/' . $firstPathSegment . '/';
echo $newUrl;

?>
