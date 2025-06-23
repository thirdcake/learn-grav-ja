<?php

use Spatie\YamlFrontMatter\YamlFrontMatter;
// php ./php/func/sitemap.php

function sitemapdom (array $pages):string {
    $dom = new \DOMDocument('1.0', 'UTF-8');
    $dom->formatOutput = true;

    $urlset = $dom->createElement('urlset');
    $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
    $dom->appendChild($urlset);

    // home page
    $homeFrontmatter = YamlFrontMatter::parse(file_get_contents(dirname(__DIR__,2).'/src/pages/index.md'));
    $url = $dom->createElement('url');
    $loc = $dom->createElement('loc', 'https://thirdcake.github.io/learn-grav-ja/');
    $priority = $dom->createElement('priority', '0.7');
    $changefreq = $dom->createElement('changefreq', 'yearly');
    $lastmod = $dom->createElement('lastmod', $homeFrontmatter->lastmod);

    $url->appendChild($loc);
    $url->appendChild($priority);
    $url->appendChild($changefreq);
    $url->appendChild($lastmod);

    $urlset->appendChild($url);

    foreach($pages as $page) {
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
    return $dom->saveXML();
}
