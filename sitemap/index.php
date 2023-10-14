<?php
$_SERVROOT = '../../';
include($_SERVROOT.'/secrets/DEV_OPTIONS.php');
header('Content-Type: application/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <sitemap>
    <loc>'.DOMAIN.'/sitemap_main.xml</loc>
  </sitemap>
  <sitemap>
    <loc>'.DOMAIN.'/sitemap_authors.xml</loc>
  </sitemap>
  <sitemap>
    <loc>'.DOMAIN.'/sitemap_webstories.xml</loc>
  </sitemap>
</sitemapindex>';

 ?>
