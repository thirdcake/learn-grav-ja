---
title: テーマ
lastmod: '2026-10-27T18:30:58+09:00'
description: 'Grav サイトの見た目は、テーマで決まります。テーマのしくみや、編集・作成の手軽さを見ていきましょう。'
weight: 30
layout: 'chapter'
params:
    srcPath: /themes
    icon: '#themes'
    chapter_number: 3
---

Grav のテーマ（見た目を処理する）機能を解説するチャプターです。

## テーマの基本{#theme-basics}

[公式ドキュメント](https://learn.getgrav.org/themes/theme-basics) （[md](https://learn.getgrav.org/themes/theme-basics.md)）

テーマディレクトリ（`user/themes/theme-name/`）の概要について書かれています。  
それぞれの内容は、以下で詳しく解説されます。

## テーマのチュートリアル{#theme-tutorial}

[公式ドキュメント](https://learn.getgrav.org/themes/theme-tutorial) （[md](https://learn.getgrav.org/themes/theme-tutorial.md)）

テーマを自作する際のやり方を解説したチュートリアルです。

## Twig 入門{#twig-primer}

[公式ドキュメント](https://learn.getgrav.org/themes/twig-primer) （[md](https://learn.getgrav.org/themes/twig-primer.md)）

Symfony 系列でよく使われる Twig というテンプレートエンジンの解説です。

Twig は、 PHP の世界でとても有名なテンプレートエンジンなので、 [公式ドキュメント](https://twig.symfony.com/doc/3.x/) も読みやすく整備されています。

## Twig のタグ・フィルタ・関数{#twig-tags-filters-functions}

[公式ドキュメント](https://learn.getgrav.org/themes/twig-tags-filters-functions) （[md](https://learn.getgrav.org/themes/twig-tags-filters-functions.md)）

以下の3つのページへの導入用の解説です。

### Twig のタグ{#tags}

[公式ドキュメント](https://learn.getgrav.org/themes/twig-tags-filters-functions/tags) （[md](https://learn.getgrav.org/themes/twig-tags-filters-functions/tags.md)）

Grav で追加した Twig 中で使えるタグのリファレンスです。

### Twig のフィルタ{#filters}

[公式ドキュメント](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters) （[md](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters.md)）


Grav で追加した Twig 中で使えるフィルタのリファレンスです。

### Twig の関数{#functions}

[公式ドキュメント](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions) （[md](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions.md)）

Grav で追加した Twig 中で使える関数のリファレンスです。

## テーマの設定{#theme-configuration}

[公式ドキュメント](https://learn.getgrav.org/themes/theme-configuration) （[md](https://learn.getgrav.org/themes/theme-configuration.md)）

テーマの設定ファイル（`user/themes/theme-name/theme-name.yaml`, `user/themes/theme-name/blueprints.yaml` など）をどうやって使うのかを解説しています。

## テーマの変数{#theme-vars}

[公式ドキュメント](https://learn.getgrav.org/themes/theme-vars) （[md](https://learn.getgrav.org/themes/theme-vars.md)）

Twig テンプレートで使える、 Grav が用意してくれた変数のリファレンスです。

便利な変数がたくさんあるので、テンプレートでページファイルなどに書いた情報を表示させたいときは、まずこのリファレンスを調べるのが良いです。

**プロンプト例：**

```txt
https://learn.getgrav.org/themes/theme-vars.md

上記のページを参考に、 Twig の書き方を教えてください。
あるページに対して、その親ページがホーム（トップページ `/`）ではなく、かつ `visible: true` だったときだけ、親ページへのリンクを張りたいです。
リンクテキストは、親ページのメニュー名（無ければタイトル）にしたいです。
```

## アセットマネージャー{#asset-manager}

[公式ドキュメント](https://learn.getgrav.org/themes/asset-manager) （[md](https://learn.getgrav.org/themes/asset-manager.md)）

アセット（JavaScript や CSS）をどうやって読み込むかを、かなり詳細に設定できます。  
普通は、head タグ内に置くことが多いと思いますが、body 閉じタグの直前にもおけます。また、link かインラインか、JS ならモジュラーかどうかなども設定でき、プラグインなどで遅延させて挿入もできます。  
便利ですが、書き方が分かりづらいので、基本的には AI に頼んだほうが良いです。

## カスタマイズ{#customization}

[公式ドキュメント](https://learn.getgrav.org/themes/customization) （[md](https://learn.getgrav.org/themes/customization.md)）

既存テーマをカスタマイズさせる方法が解説されています。

サイトをさくっと作りたい場合は、ここがとても重宝します。  
普通にファイルを上書きしてしまうと、テーマがアップデートされたときに消えてしまうので、そうならないための設定方法です。  
元テーマから CSS だけ追加したい場合、子テーマで上書きしたい場合などが紹介されています。

## GMP 提出{#gpm-submission}

[公式ドキュメント](https://learn.getgrav.org/themes/gpm-submission) （[md](https://learn.getgrav.org/themes/gpm-submission.md)）

自作したテーマを、 Grav のリポジトリで公開できます。

公開すると、公式サイトの一覧に表示され、Grav を利用している世界中の人が GMP 経由でダウンロードが可能になります。

## コアテンプレート{#core-templates}

[公式ドキュメント](https://learn.getgrav.org/themes/core-templates) （[md](https://learn.getgrav.org/themes/core-templates.md)）

Grav のコア（system）に入っているテンプレートの解説です。  
自作のテンプレートを作るときに、特に書かなくても、コアが用意したものを使って便利な機能を追加できます。


