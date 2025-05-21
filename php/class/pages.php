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
    public function __construct(SplFileInfo $fileInfo) {
        $this->pathname = $this->set_pathname($fileInfo->getRelativePathname());
        $this->loc = 'https://thirdcake.github.io/learn-grav-ja/'.$this->pathname.'/';
        $this->priority = '1.0';
        $this->changefreq = 'yearly';
        $frontmatter = YamlFrontMatter::parse(file_get_contents($fileInfo->getRealPath()));
        $this->lastmod = $frontmatter->matter('lastmod');
        $this->title = $frontmatter->matter('title');
        $this->redirect = $frontmatter->matter('redirect') ?? false;
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

// Pages で tree を作るために必要
class Node {
    public Page|null $page;
    public string|null $name;
    public array $children;
    public function __construct() {
        $this->page = null;
        $this->name = null;
        $this->children = [];
    }

    public function searchChild(string $route): Node {
        if($route==='') {
            return $this;
        }
        foreach($this->children as $child) {
            if($child->name === $route) {
                return $child;
            }
        }
        $newNode = new Node();
        $newNode->name = $route;
        $this->children[] = $newNode;
        return $newNode;
    }

    public function sortedChildren(): array {
        // 子 Node を sort
        usort($this->children, 
            fn(Node $a, Node $b): int => strcmp($a->name, $b->name)
        );
        $list = [];
        foreach($this->children as $child) {
            $list[] = $child;
            $list = array_merge($list, $child->sortedChildren());
        }
        return $list;
    }

}

// sitemap などを作成するための Page の集まり。
// list 形式と tree （ネスト）形式の2つが必要
class Pages {
    private array $list;
    private Node $root;
    public function __construct() {
        $this->list = [];
        $this->root = new Node();
    }

    public function addPage(SplFileInfo $fileInfo):void {
        $page = new Page($fileInfo);
        $routes = explode('/', trim($page->pathname, '/'));
        $node = $this->root;
        foreach($routes as $route) {
            $node = $node->searchChild($route);
        }
        $node->page = $page;
    }

    public function toPageList():array {
        $this->list = $this->root->sortedChildren();
        return $this->list;
    }

    public function toPageTree():Node {
        return $this->root;
    }

}
