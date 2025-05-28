<?php

// tree 構造を作る
function pagestree(array $pages):string {
    $arr = [];
    foreach($pages as $page) {
        $paths = explode('/', $page->pathname);
        $current = &$arr;
        foreach($paths as $path) {
            if(!isset($current[$path])) {
                $current[$path] = [];
            }
            $current = &$current[$path];
        }
        $current['pathname'] = $page->pathname;
        $current['title'] = $page->title;
        $current['redirect'] = $page->redirect;
        $current['isonlyja'] = $page->isonlyja;
    }
    return json_encode($arr, JSON_PRETTY_PRINT);
}

// list 構造を作る
function pageslist(array $pages):string {
    $arr = [];
    foreach($pages as $page) {
        $arr[] = [
            'pathname' => $page->pathname,
            'title'=> $page->title,
            'redirect'=> $page->redirect,
            'isonlyja'=> $page->isonlyja,
        ];
    }
    return json_encode($arr, JSON_PRETTY_PRINT);
}
