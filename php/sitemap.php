<?php

// php php/sitemap.php

require_once __DIR__.'/class/pages.php';
require_once __DIR__.'/common.php';

$pages = getPages();  // $pages: Pages

$dom = new DOMDocument('1.0', 'UTF-8');
$dom->formatOutput = true;

$urlset = $dom->createElement('urlset');
$urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
$dom->appendChild($urlset);

foreach($pages->toPageList() as $node) {
    $page = $node->page;
    if($page->redirect !== false) {
        continue;
    }
    $url = $dom->createElement('url');
    $loc = $dom->createElement('loc', $page->loc);
    $priority = $dom->createElement('priority', $page->priority);
    $changefreq = $dom->createElement('changefreq', $page->changefreq);
    $lastmod = $dom->createElement('lastmod', $page->lastmod);

    $url->appendChild($loc);
    $url->appendChild($priority);
    $url->appendChild($changefreq);
    $url->appendChild($lastmod);

    $urlset->appendChild($url);
}

file_put_contents(dirname(__DIR__).'/public/sitemap.xml', $dom->saveXML());

