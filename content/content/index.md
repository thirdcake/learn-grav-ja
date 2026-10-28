---
title: 'コンテンツ'
lastmod: '2026-10-26T19:07:32+09:00'
description: 'Grav では、コンテンツが最優先です。コンテンツを迅速かつ直感的に作成し、整理する方法を学びましょう。'
weight: 20
layout: 'chapter'
params:
    icon: '#content'
    srcPath: '/content'
    chapter_number: 2
---

[公式ドキュメント](https://learn.getgrav.org/content)

Grav でのコンテンツを解説するチャプターです。

コンテンツを書くときに必要な知識と、コンテンツどうしをつなげるときに必要な知識があります。

- 全体概要：
  - [ページ](#content-pages)
- 書くときに必要（主にページ編集者）：
  - [ヘッダー / フロントマター](#headers)
  - [マークダウン構文](#markdown)
  - [ページリンク](#linking)
  - [イメージリンク](#image-linking)
  - [メディア](#media)
  - [ショートコードの作成](#creating-a-shortcode)
  - [コンテンツ内の Twig](#twig-in-content)
- つなげるときに必要（主にサイト管理者やサイト制作者）：
  - [ページ・コレクション](#collections)
  - [タクソノミー](#taxonomy)
  - [モジュラーページ](#modular)
  - [ルーティング](#routing)
  - [多言語対応](#multi-language)
- その他：
  - [コンテンツ・タイプ](#content-types)
  - [AI エージェント用マークダウン](#markdown-output)

## ページ{#content-pages}

[公式ドキュメント](https://learn.getgrav.org/content/content-pages) （[md](https://learn.getgrav.org/content/content-pages.md)）

このチャプターの概要です。

コレクションページや、モジュラーページなど、 Grav 特有の用語が解説されています。

## ヘッダー / フロントマター{#headers}

[公式ドキュメント](https://learn.getgrav.org/content/headers) （[md](https://learn.getgrav.org/content/headers.md)）

Grav で利用するフロントマター（ページのメタデータ）のリファレンスです。

最初から、すべてを読む必要は無いと思います。  
ページ単位で何かの設定が必要になったら、ここを見てみるのが良いと思います。（サイト単位で設定が必要なら、 `user/config/system.yaml`, `user/config/site.yaml` などのファイルに書きます）

なお、コレクションページやモジュラーページのフロントマターの書き方は、それぞれ解説ページが別にあります。

## マークダウン構文{#markdown}

[公式ドキュメント](https://learn.getgrav.org/content/markdown) （[md](https://learn.getgrav.org/content/markdown.md)）

マークダウンの書き方について解説されています。  
すでにマークダウンを知っている方には、特に新しい知識は無いかもしれません。

基本的には、 Grav を使うならマークダウンを覚えたほうが、不具合も少なく、何かと便利です。  
ただし、もしその学習コストをかけたくない場合は、 TipTap ベースや、 TinyMCE ベースの管理パネル（Admin2）で使える WYSIWYG エディタプラグインがあるので、探してみてください。

## ページリンク{#linking}

[公式ドキュメント](https://learn.getgrav.org/content/linking) （[md](https://learn.getgrav.org/content/linking.md)）

これもマークダウンの書き方の一部なのですが、 Grav では、`user/pages/01.home/` のように、ディレクトリ名にナビゲーション用の順番を付けることができ、この番号は実際のルーティングでは消えます。  
リンクをするときに、この番号を省略しても、付けたままでも、柔軟に対応することが解説されています。

## イメージリンク{#image-linking}

[公式ドキュメント](https://learn.getgrav.org/content/image-linking) （[md](https://learn.getgrav.org/content/image-linking.md)）

これも、「ページリンク」と同じ理由で、マークダウンを使いながらも、リンク先の URL を柔軟に設定にできるという解説です。

## メディア{#media}

[公式ドキュメント](https://learn.getgrav.org/content/media) （[md](https://learn.getgrav.org/content/media.md)）

Grav では、GD 拡張などを使って、画像を動的に処理できます。

よくある使い方は、ギャラリーページで、サムネイルの一覧は小さく切り取って素早く表示し、リンク先の高画質の画像は元画像を表示するような場合です。  
画像だけでなく、アニメーションやオーディオなどの設定も解説されています。

## ショートコードの作成{#creating-a-shortcode}

[公式ドキュメント](https://learn.getgrav.org/content/creating-a-shortcode) （[md](https://learn.getgrav.org/content/creating-a-shortcode.md)）

Grav のインストールをすると、最初から入っている「ショートコード」プラグインを使って、オリジナルのタグを実装できます。

たとえば、通常のマークダウン構文では、文字に色を付けたりすることはできません（`<span style="color:red;">文字を赤く</span>` のようなことができません）が、ショートコードを作れば、オリジナルのタグ（たとえば `[red]文字を赤く[/red]` のようなタグ）を作れます。  
やや開発者向けの内容です。

## コンテンツ内の Twig{#twig-in-content}

[公式ドキュメント](https://learn.getgrav.org/content/twig-in-content) （[md](https://learn.getgrav.org/content/twig-in-content.md)）

Grav 1.x 系では、マークダウン内に Twig を直書きして、動的な処理ができるようになっていました。  
しかし、セキュリティ的にいろいろと問題が噴出したので、さまざまな対策が講じられるようになりました。  
これからページを作る人は、この機能を使う必要は無く、上記の「ショートコード」を使ってください。  
もし、過去に運用されていた Grav アップデートするときは、上記のような経緯があり、いろいろと対策されてきたことを知っておくと良いかもしれません。


## ページ・コレクション{#collections}

[公式ドキュメント](https://learn.getgrav.org/content/collections) （[md](https://learn.getgrav.org/content/collections.md)）

コレクションページの作り方や設定方法、カスタマイズ方法を解説しています。  
ブログの一覧ページを作りたい場合などは、このページを読み込ませて、設定方法を確認すると良いです。

**プロンプト例：**

```txt
https://learn.getgrav.org/content/collections.md

このページを読んで、ブログの一覧ページの作り方を教えてください。
一覧ページは、`user/pages/01.home/blog.md` にあり、ブログ記事は、 `user/pages/02.blog/...` 以下のフォルダに並列に（ネストせず）置いてあります。
最終変更日の降順で並べて、1ページあたり10記事を一覧表示したいです。
```

## タクソノミー{#taxonomy}

[公式ドキュメント](https://learn.getgrav.org/content/taxonomy) （[md](https://learn.getgrav.org/content/taxonomy.md)）

タクソノミーとは、分類のことで、ブログをカテゴリー分けしたり、タグ分けしたりする際に使います。  
タクソノミーで分類する方法が解説されています。

## モジュラーページ{#modular}

[公式ドキュメント](https://learn.getgrav.org/content/modular) （[md](https://learn.getgrav.org/content/modular.md)）

上記の「コレクションページ」と似ていますが、モジュラーページは、部品を組み合わせるイメージです。  
各部品に、テンプレートが別々に用意できるため、複雑なデザインのページに対応できます。

## ルーティング{#routing}

[公式ドキュメント](https://learn.getgrav.org/content/routing) （[md](https://learn.getgrav.org/content/routing.md)）

Grav では、ページのリダイレクトや別名（エイリアス）が簡単に設定できます。

URL を本来の（カノニカルな）ページにリダイレクトする方法と、 URL を別名のままで、内容だけ本来のものを表示する方法があります。  
他の CMS から引っ越すとき、元 CMS の URL の付け方ルールが違ったとしても、柔軟に対応できる設計になっています。

## 多言語対応{#multi-language}

[公式ドキュメント](https://learn.getgrav.org/content/multi-language) （[md](https://learn.getgrav.org/content/multi-language.md)）

Grav では、多言語サイトを簡単に設定できます。

1つのディレクトリ内に、別言語用のマークダウンファイルを設置するだけで、その言語用ルーティングを生成してくれます。  
また、対応言語が無い場合にどのようにフォールバックするかも設定できます。

## コンテンツ・タイプ{#content-types}

[公式ドキュメント](https://learn.getgrav.org/content/content-types) （[md](https://learn.getgrav.org/content/content-types.md)）

Grav では、基本的には html ファイルをレスポンスしますが、 `.json` ファイルが欲しい場合もあります。  
そのような場合に、 Twig テンプレートを追加するだけで自動的に Json ファイルにしてくれる機能があります。

## AI エージェント用マークダウン{#markdown-output}

[公式ドキュメント](https://learn.getgrav.org/content/markdown-output) （[md](https://learn.getgrav.org/content/markdown-output.md)）

Grav 2.1 からの新機能です。

上記のコンテンツタイプをわざわざ設定しなくても、メインコンテンツをマークダウンファイルにしてくれる機能の解説です。  
AI に読み込ませる際には、大変便利です。

