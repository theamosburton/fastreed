<?php
// Create an array of URLs and associated data
$urls = array(
    array(
        'loc' => 'https://timesofindia.indiatimes.com/life-style/health-fitness/web-stories/foods-you-need-to-avoid-if-you-have-arthritis/photostory/104423666.cms',
        'publication_name' => 'Times of India',
        'language' => 'en',
        'publication_date' => '2023-10-14T16:45:50+05:30',
        'title' => 'Foods you need to avoid if you have arthritis',
        'keywords' => 'Foods you need to avoid if you have arthritis, Foods to avoid if you have arthritis, foods for arthritis, avoid these if you have arthritis, do not eat these if you have arthritis',
        'lastmod' => '2023-10-14T16:48:51+05:30',
        'image_loc' => 'https://timesofindia.indiatimes.com/life-style/health-fitness/web-stories/foods-you-need-to-avoid-if-you-have-arthritis/photo/104423666.cms?imgsize=873536'
    ),
);

// Create a new XML document
$xml = new DOMDocument('1.0', 'UTF-8');
$xml->formatOutput = true;

// Create the root element
$urlset = $xml->createElement('urlset');
$urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
$xml->appendChild($urlset);

// Loop through the URLs and add them to the sitemap
foreach ($urls as $urlData) {
    $urlElement = $xml->createElement('url');

    $loc = $xml->createElement('loc', $urlData['loc']);
    $urlElement->appendChild($loc);

    $news = $xml->createElement('news:news');

    $publication = $xml->createElement('news:publication');
    $publication_name = $xml->createElement('news:name', $urlData['publication_name']);
    $language = $xml->createElement('news:language', $urlData['language']);
    $publication->appendChild($publication_name);
    $publication->appendChild($language);
    $news->appendChild($publication);

    $publication_date = $xml->createElement('news:publication_date', $urlData['publication_date']);
    $news->appendChild($publication_date);

    $title = $xml->createElement('news:title', $urlData['title']);
    $news->appendChild($title);

    $keywords = $xml->createElement('news:keywords', $urlData['keywords']);
    $news->appendChild($keywords);

    $urlElement->appendChild($news);

    $lastmod = $xml->createElement('lastmod', $urlData['lastmod']);
    $urlElement->appendChild($lastmod);

    $image = $xml->createElement('image:image');
    $image_loc = $xml->createElement('image:loc', $urlData['image_loc']);
    $image->appendChild($image_loc);
    $urlElement->appendChild($image);

    $urlset->appendChild($urlElement);
}

// Set the content type to XML
header('Content-Type: application/xml');

// Output the XML content
echo $xml->saveXML();
?>
