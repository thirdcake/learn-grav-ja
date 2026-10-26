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

Grav でのコンテンツに関する解説をするチャプターです。  
主に、ページの中身の編集に関することと、ページ同士の関係性に関することの説明に分かれています。

サイト制作者（サイトの管理をする人）や、開発者（テーマやプラグインを作る人）は、このチャプターの内容を、ある程度知っておく必要があります。  
一方、ページ編集者（コンテンツを作成する人）は、もしかしたら [Administration Panel](https://learn.getgrav.org/2/admin-panel) の使い方の方が優先度が高いかもしれません。

## ページ{#content-pages}

[公式ドキュメント](https://learn.getgrav.org/content/content-pages) （[md](https://learn.getgrav.org/content/content-pages.md)）

このチャプターの概論です。  
それぞれの詳しい内容は、後の章で解説されます。

まず、 Grav における3種類のページが解説されます：

1. 1つのファイルに書いた内容がそのまま表示される「レギュラーページ」
1. ある範囲内の複数ファイルの内容を集約し、一覧表示する「コレクションページ」（ブログの一覧ページなどに使う）
1. ある範囲内の複数ファイルの内容を集約し、モジュラー（コンポーネント部品）にして表示する「モジュラーページ」（複雑なデザインのページに使う）

また、マークダウンファイルの冒頭にメタデータとして yaml 形式の設定を書くことができ、それを「フロントマター」と呼びます。

## ヘッダー / フロントマター{#headers}

[公式ドキュメント](https://learn.getgrav.org/content/headers) （[md](https://learn.getgrav.org/content/headers.md)）

Grav で利用されるフロントマターのリファレンスです。  
最初のうちは、すべてを読むのは大変だと思います。  
ページ単位で何かの設定が必要になったら、ここを見てみるのが良いと思います。（サイト単位で設定が必要なら、 `user/config/system.yaml`, `user/config/site.yaml` などのファイルに書きます）

なお、コレクションページやモジュラーページのフロントマターの書き方は、それぞれ解説ページが別にあります。

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

## マークダウン構文{#markdown}

[公式ドキュメント](https://learn.getgrav.org/content/markdown) （[md](https://learn.getgrav.org/content/markdown.md)）

マークダウンの書き方について解説されています。  
基本的には、 Grav を使うならマークダウンを覚えたほうが、不具合も少なく、何かと便利です。  
しかし、もしその学習コストをかけたくない場合は、 TipTap ベースや、 TinyMCE ベースの管理パネル（Admin2）で使える WYSIWYG エディタプラグインがあるので、探してみてください。

## ページリンク{#linking}

[公式ドキュメント](https://learn.getgrav.org/content/linking) （[md](https://learn.getgrav.org/content/linking.md)）

これもマークダウンの書き方の一部なのですが、 Grav では、`user/pages/01.home/` のように、ナビゲーション用の順番を付けることができ、この番号は実際のルーティングでは消えます。  
リンクをするときに、この番号を省略しても、付けたままでも対応することが書かれています。

## コンテンツ内の Twig{#twig-in-content}

[公式ドキュメント](https://learn.getgrav.org/content/twig-in-content) （[md](https://learn.getgrav.org/content/twig-in-content.md)）

Grav 1.x 系では、マークダウン内に Twig を直書きして、動的な処理ができるようになっていました。  
しかし、セキュリティ的にいろいろと問題が噴出したので、さまざまな対策が講じられるようになりました。  
これからページを作る人が、このページを読む必要はありません。  
動的な処理を入れたい場合は、次の「ショートコード」を利用してください。

## ショートコードの作成

[公式ドキュメント](https://learn.getgrav.org/content/creating-a-shortcode) （[md](https://learn.getgrav.org/content/creating-a-shortcode.md)）

Grav のインストールをすると、最初から入っている「ショートコード」プラグインを使って、オリジナルのタグを実装できます。  
たとえば、通常のマークダウン構文では、文字に色を付けたりすることはできません（`<span style="color:red;">...</span>` のようなことができません）が、ショートコードを作れば、オリジナルのタグ（たとえば `[red]...[/red]` のようなタグ）を作れます。
やや開発者向けの内容です。

## イメージリンク

[公式ドキュメント](https://learn.getgrav.org/content/image-linking) （[md](https://learn.getgrav.org/content/image-linking.md)）

これも、「ページリンク」と同じ理由で、マークダウンを使いながらも、リンク先の URL をある程度自由にできるという解説です。

## メディア

[公式ドキュメント](https://learn.getgrav.org/content/media) （[md](https://learn.getgrav.org/content/media.md)）

Grav では、Gregwar Image Library などを使って、画像を動的に処理できます。  
画像だけでなく、アニメーションやオーディオなどの設定も解説されています。

## タクソノミー

[公式ドキュメント](https://learn.getgrav.org/content/taxonomy) （[md](https://learn.getgrav.org/content/taxonomy.md)）

タクソノミーとは、分類のことで、ブログをカテゴリー分けしたり、タグ分けしたりする際に使います。  
タクソノミーで分類する方法が解説されています。

## モジュラーページ

[公式ドキュメント](https://learn.getgrav.org/content/modular) （[md](https://learn.getgrav.org/content/modular.md)）

上記の「コレクションページ」と似ていますが、モジュラーページは、部品を組み合わせるイメージです。  
各部品に、テンプレートが別々に用意できます。

## ルーティング

[公式ドキュメント](https://learn.getgrav.org/content/routing) （[md](https://learn.getgrav.org/content/routing.md)）

Grav では、ページのリダイレクトや別名（エイリアス）が簡単に設定できます。  
他の CMS から引っ越すとき、 URL の付け方のルールが違う場合でも、柔軟に対応できる機能です。

## 多言語対応

[公式ドキュメント](https://learn.getgrav.org/content/multi-language) （[md](https://learn.getgrav.org/content/multi-language.md)）

Grav では、多言語サイトが必要な場合に、簡単に設定できます。  
1つのディレクトリ内に、別言語用のマークダウンファイルを設置するだけで、その言語用ルーティングを生成してくれます。  
また、対応言語が無い場合にどのようにフォールバックするかも設定できます。

## コンテンツ・タイプ

[公式ドキュメント](https://learn.getgrav.org/content/content-types) （[md](https://learn.getgrav.org/content/content-types.md)）

Grav では、基本的には html ファイルをレスポンスしますが、 `.json` ファイルが欲しい場合もあります。  
そのような場合に、 Twig テンプレートを追加するだけで自動的に Json ファイルにしてくれる機能があります。

## AI エージェント用マークダウン

[公式ドキュメント](https://learn.getgrav.org/content/markdown-output) （[md](https://learn.getgrav.org/content/markdown-output.md)）

Grav 2.1 からの新機能です。  
上記のコンテンツタイプを設定しなくても、メインコンテンツをマークダウンファイルにしてくれる機能の解説です。  
AI に読み込ませる際には、大変便利です。

