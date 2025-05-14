<?php

// php php/sitemap.php

require_once __DIR__.'/class/pages.php';
require_once __DIR__.'/common.php';

$pages = getPages();  // $pages: Pages

$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset></urlset>');
foreach($pages->toPageList() as $node) {
    $page = $node->page;
    if($page->redirect !== false) {
        continue;
    }
    $url = $xml->addChild('url');
    $url->addChild('loc', htmlspecialchars($page->loc));
    $url->addChild('priority', htmlspecialchars($page->priority));
    $url->addChild('changefreq', htmlspecialchars($page->changefreq));
    $url->addChild('lastmod', htmlspecialchars($page->lastmod));
}

file_put_contents(dirname(__DIR__).'/public/sitemap.xml', $xml->asXML());
