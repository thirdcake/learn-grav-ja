<?php

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class Page {
    public string $loc;  // sitemap.xml の <loc>
    public string $priority;  // sitemap.xml の <priority>
    public string $changefreq;  // sitemap.xml の <changefreq>
    public string $lastmod;  // sitemap.xml の <lastmod>
    public string $pathname;  // path 名
    public string $title;  // ページのタイトル
    public string|bool $redirect;  // redirect があるかどうか、あれば対象ページ
    public array $children;  // 子ページ
    public function __construct(SplFileInfo $fileInfo) {
        $relativePathname = $fileInfo->getRelativePathname();
        $this->loc = $this->set_loc($relativePathname);
        $this->priority = '1.0';
        $this->changefreq = 'yearly';
        $this->lastmod = $this->set_lastmod($fileInfo->getMTime());
        $this->pathname = $relativePathname;
        $frontmatter = YamlFrontMatter::parse(file_get_contents($fileInfo->getRealPath()));
        $this->title = $frontmatter->matter('title');
        $this->redirect = $frontmatter->matter('redirect') ?? false;
        $this->children = [];
    }

    // set $this->loc
    private function set_loc(string $pathname):string {
        if(str_ends_with($pathname, 'index.md')) {
            $loc = substr($pathname, 0, -8);
        }
        $loc = 'https://thirdcake.github.io/learn-grav-ja/'.$loc;
        return $loc;
    }
    
    // set $this->lastmod
    private function set_lastmod(int|false $timestamp):string {
        $dateString = ($timestamp === false) 
            ? '2025-01-01'
            : date('Y-m-d', $timestamp);
        return $dateString;
    }

}

class Pages {
    private array $list;
    private bool $is_list;
    private array $root;
    public function __construct() {
        $this->list = [];
        $this->is_list = false;
        $this->root = [];
    }
    public function addChild(SplFileInfo $fileInfo) {
        $page = new Page($fileInfo);
        $this->list[] = $page;
    }
    public function toPageList():array {
        usort($this->list, function (Page $a, Page $b): int {
            $apath = $a->pathname;
            $bpath = $b->pathname;
            if(str_starts_with($apath, $bpath) || str_starts_with($bpath, $apath)) {
                return strlen($apath) <=> strlen($bpath);
            }
            return strcmp($apath, $bpath);
        });
        $this->is_list = true;
        return $this->list;
    }
    public function toPageTree():array {
        if($this->is_list === false) {
            $this->toPageList();
        }
    }
    private function cmp(Page $a, Page $b): int {

    }
}
