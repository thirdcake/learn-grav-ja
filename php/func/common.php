<?php

// $pages = getPages();

use Symfony\Component\Finder\Finder;
use Spatie\YamlFrontMatter\YamlFrontMatter;

function getPages():array {

    $finder = new Finder();

    // find index.md files in the pages directory
    $dir = dirname(__DIR__, 2).'/src/pages/';
    $finder->files()->in($dir)->name('index.md');

    // data array
    $pages = new Pages();

    // check if there are any search results
    if ($finder->hasResults()) {
        foreach ($finder as $fileInfo) {
            $page = new Page($fileInfo);
            $pages->insert($page);
        }
    }

    return iterator_to_array($pages);
}