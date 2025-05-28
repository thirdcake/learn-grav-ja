<?php

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Spatie\YamlFrontMatter\YamlFrontMatter;

// Symfony Finder から fileInfo を受け取って作る Page オブジェクト
class Page {
    public string $pathname;  // path 名
    public string $loc;  // sitemap.xml の <loc>
    public string $priority;  // sitemap.xml の <priority>
    public string $changefreq;  // sitemap.xml の <changefreq>
    public string $lastmod;  // sitemap.xml の <lastmod>
    public string $title;  // ページのタイトル
    public string|bool $redirect;  // redirect があるかどうか、あれば対象ページ
    public bool $isonlyja;  // 日本語限定か
    public function __construct(SplFileInfo $fileInfo) {
        $this->pathname = $this->set_pathname($fileInfo->getRelativePathname());
        $this->loc = 'https://thirdcake.github.io/learn-grav-ja/'.$this->pathname.'/';
        $this->priority = '1.0';
        $this->changefreq = 'yearly';
        $frontmatter = YamlFrontMatter::parse(file_get_contents($fileInfo->getRealPath()));
        $this->lastmod = $frontmatter->matter('lastmod');
        $this->title = $frontmatter->matter('title');
        $this->redirect = $frontmatter->matter('redirect') ?? false;
        $this->isonlyja = $frontmatter->matter('isonlyja') ?? false;
    }

    // set $this->pathname
    private function set_pathname(string $pathname):string {
        $lastSlashPos = strrpos($pathname, '/');
        if($lastSlashPos === false) {
            return $pathname;
        }
        return substr($pathname, 0, $lastSlashPos);
    }

}

// ページの順番を決めるヒープ
class Pages extends \SplMinHeap {
    // 比較関数
    public function compare(mixed $page1, mixed $page2):int {
        $path1 = explode('/', $page1->pathname);
        $path2 = explode('/', $page2->pathname);
        $count1 = count($path1);
        $count2 = count($path2);
        $countmin = min($count1, $count2);
        for($i=0; $i<$countmin; $i++) {
            if($path1[$i] === $path2[$i]) {
                continue;
            } else {
                return strcmp($path2[$i], $path1[$i]);
            }
        }
        return $count2 - $count1;
    }
}
