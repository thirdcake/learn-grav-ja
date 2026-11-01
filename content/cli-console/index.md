---
title: 'CLI コンソール'
lastmod: '2026-11-01T15:38:58+09:00'
description: 'Grav は、開発者にとって使いやすく、たくさんの便利なコマンドラインのツールや機能を提供します。これにより、可能な限りシンプルに、スクリプト操作で利用しやすいものとなっています。'
weight: 70
layout: 'chapter'
params:
    icon: '#cli'
    srcPath: /cli-console
    chapter_number: 7
---

[公式ドキュメント](https://learn.getgrav.org/cli-console) （[md](https://learn.getgrav.org/cli-console.md)）

CLI （コマンドライン・インターフェース）は、よくドラマなどでハッカーが打ち込んでいる黒い画面のことです。  
馴染みがない方もいるかもしれませんが、慣れればマウスで操作するよりも簡単で便利なので、ぜひこの機会に触ってみてください。

## コマンドラインをはじめる{#command-line-intro}

[公式ドキュメント](https://learn.getgrav.org/cli-console/command-line-intro) （[md](https://learn.getgrav.org/cli-console/command-line-intro.md)）

コマンドラインの導入記事です。  
Grav はファイルシステムを使っているため、シンボリックリンクとの相性も良いです。

## grav コマンド{#grav-cli}

[公式ドキュメント](https://learn.getgrav.org/cli-console/grav-cli) （[md](https://learn.getgrav.org/cli-console/grav-cli.md)）

具体的なコマンド紹介です。  
まず紹介されるのは、 `bin/grav` コマンドです。

サイトの状況などを調べたりできます。

もし実行権限を与えていない場合は、 `php bin/grav` のように 最初に php を書いておくとうまくいくかもしれません。

## plugin コマンド{#grav-cli-plugin}

[公式ドキュメント](https://learn.getgrav.org/cli-console/grav-cli-plugin) （[md](https://learn.getgrav.org/cli-console/grav-cli-plugin.md)）

次に紹介されるのは、 `bin/plugin` コマンドです。

コアではなく、プラグインの機能を利用できるものです。  
さまざまなプラグインがこれに対応しているので、 `php bin/plugin plugin_name list` などで調べてみると発見があるかもしれません。

## gpm コマンド{#grav-cli-gpm}

[公式ドキュメント](https://learn.getgrav.org/cli-console/grav-cli-gpm) （[md](https://learn.getgrav.org/cli-console/grav-cli-gpm.md)）

最後が、パッケージマネージャーのコマンドです。

普通に管理する場合は、このコマンドを多用するかもしれません。  
コアやプラグインのアップデートなどを簡単にできます。

## スクリプトを使ったアップグレード{#scripted-upgrades}

[公式ドキュメント](https://learn.getgrav.org/cli-console/scripted-upgrades) （[md](https://learn.getgrav.org/cli-console/scripted-upgrades.md)）

CLI 操作ができるということは、シェルスクリプトで自由に自分のやりたい操作を組み込めるということでもあります。

ここでは例として、 Deployer を使って、複数の Grav コアを一斉アップデートする方法が紹介されています。  
CLI 操作は、コストが小さく、応用が効きやすいので、ぜひいろんな使い方を試してみてください。

