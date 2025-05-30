<?php

// tree 構造を作る
function pagestree(array $pages):string {
    $arr = [];
    $arr['children'] = [];
    foreach($pages as $page) {
        $paths = explode('/', $page->pathname);
        $newpage = [
            'pathname' => $page->pathname,
            'title'    => $page->title,
            'redirect' => $page->redirect,
            'isonlyja' => $page->isonlyja,
            'dirname'  => $paths[count($paths)-1],
            'children' => [],
        ];
        $current = &$arr;
        foreach($paths as $depth => $path) {
            foreach($current['children'] as $idx=>$child) {
                if($child['dirname'] === $path) {
                    $current = &$current['children'][$idx];
                }
            }
        }
        $current['children'][] = $newpage;
    }
    return json_encode($arr, JSON_PRETTY_PRINT);
}

// list 構造を作る
function pageslist(array $pages):string {
    $arr = [];
    foreach($pages as $page) {
        $newpage = [
            'pathname' => $page->pathname,
            'title'    => $page->title,
            'redirect' => $page->redirect,
            'isonlyja' => $page->isonlyja,
        ];
        $arr[] = $newpage;
    }
    return json_encode($arr, JSON_PRETTY_PRINT);
}
