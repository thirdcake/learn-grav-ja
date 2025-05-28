<?php

// php ./php/update.php

require_once dirname(__DIR__).'/vendor/autoload.php';
require_once __DIR__.'/class/pages.php';
require_once __DIR__.'/func/common.php';
require_once __DIR__.'/func/sitemap.php';
require_once __DIR__.'/func/pagesjson.php';

$pages = getPages();

// sitemap
$sitemapxml = sitemapdom($pages);
file_put_contents(dirname(__DIR__).'/public/sitemap.xml', $sitemapxml);

// pagetree.json
$treejson = pagestree($pages);
file_put_contents(dirname(__DIR__).'/src/pagestree.json', $treejson);
// pagelist.json
$listjson = pageslist($pages);
file_put_contents(dirname(__DIR__).'/src/pageslist.json', $listjson);
