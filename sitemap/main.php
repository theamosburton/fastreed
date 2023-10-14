<?php
$_SERVROOT = '../../';
include($_SERVROOT.'/secrets/DEV_OPTIONS.php');
header('Content-Type: application/xml');
echo '<?xml version="1.0" encoding="UTF-8" ?>
  <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" >
    <url>
      <loc>'.DOMAIN.'/</loc>
      <lastmod>2023-10-14T17:54:06+00:00</lastmod>
      <changefreq>always</changefreq>
      <priority>1</priority>
    </url>
    <url>
      <loc>'.DOMAIN.'/account/sign</loc>
      <lastmod>2023-10-14T17:54:06+00:00</lastmod>
      <priority>0.9</priority>
    </url>
    <url>
      <loc>'.DOMAIN.'/about/</loc>
      <lastmod>2023-10-14T17:54:06+00:00</lastmod>
      <priority>0.9</priority>
    </url>
    <url>
      <loc>'.DOMAIN.'/terms-privacy/</loc>
      <lastmod>2023-10-14T17:54:06+00:00</lastmod>
      <priority>0.9</priority>
    </url>
    <url>
      <loc>'.DOMAIN.'/about/</loc>
      <lastmod>2023-10-14T17:54:06+00:00</lastmod>
      <priority>0.9</priority>
    </url>
  </urlset>';

 ?>
