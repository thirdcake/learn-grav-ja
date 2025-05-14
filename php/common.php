<?php

// $pages = getPages();

require_once dirname(__DIR__).'/vendor/autoload.php';
require_once __DIR__.'/class/pages.php';

use Symfony\Component\Finder\Finder;
use Spatie\YamlFrontMatter\YamlFrontMatter;

function getPages():Pages {

    $finder = new Finder();

    // find index.md files in the pages directory
    $dir = dirname(__DIR__).'/src/pages/';
    $finder->files()->in($dir)->name('index.md');

    // data array
    $pages = new Pages();

    // check if there are any search results
    if ($finder->hasResults()) {
        foreach ($finder as $fileInfo) {
            $pages->addPage($fileInfo);
        }
    }

    return $pages;
}