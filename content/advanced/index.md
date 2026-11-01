---
title: '上級者向け'
lastmod: '2026-11-01T15:50:15+09:00'
description: 'この章では、 Grav の高度な特長と機能を解説します。'
weight: 80
layout: 'chapter'
params:
    icon: '#advanced'
    srcPath: /advanced
    chapter_number: 8
---

[公式ドキュメント](https://learn.getgrav.org/advanced) （[md](https://learn.getgrav.org/advanced.md)）

Grav の高度なカスタマイズなど、やや上級者向けの内容を解説するチャプターです。

## Grav 開発者向け情報{#grav-development}

[公式ドキュメント](https://learn.getgrav.org/advanced/grav-development) （[md](https://learn.getgrav.org/advanced/grav-development.md)）

Grav の（コアやプラグインなどの）開発者向けの解説です。

バージョン 1.x 系からのアップデートに関する情報も書かれています。

## MCP サーバー{#mcp-server}

[公式ドキュメント](https://learn.getgrav.org/advanced/mcp-server) （[md](https://learn.getgrav.org/advanced/mcp-server.md)）

Grav を AI から動かすための仕組みです。  
内部的に REST API を経由するので、パーミッション設定などが必要になります。

## パフォーマンスとキャッシュ{#performance-and-caching}

[公式ドキュメント](https://learn.getgrav.org/advanced/performance-and-caching) （[md](https://learn.getgrav.org/advanced/performance-and-caching.md)）

Grav は一般的に早いと言われていますが、それはキャッシュによる部分が大きいです。  
すべてのページを毎回走査していれば、かなり遅くなります。

基本的には、Grav の初期設定のキャッシュを使うべきですが、場合に寄っては特定の条件下ではキャッシュさせたくない（毎回その時点の最新情報が欲しい/ユーザーごとに違うデータを取得させたい）などもあるかもしれません。  
その場合は、このページでどこでキャッシュされるかを調べる必要があります。

## PHP リファレンス{#php-reference}

[公式ドキュメント](https://learn.getgrav.org/advanced/php-reference) （[md](https://learn.getgrav.org/advanced/php-reference.md)）

Grav 内部のクラスとそのメソッドの概要です。

かなりの量があるので、人間が読むのは大変だと思います。  
しかも最近は、エディタが高性能化したので、メソッドは勝手に調べてくれるかもしれません。

## デバッグとログ{#debugging}

[公式ドキュメント](https://learn.getgrav.org/advanced/debugging) （[md](https://learn.getgrav.org/advanced/debugging.md)）

開発中にデバッグやログを取りたい場合の使い方が解説されています。  
おそらく Monolog なので、開発者には使いやすいはずです。

## 環境設定{#environment-config}

[公式ドキュメント](https://learn.getgrav.org/advanced/environment-config) （[md](https://learn.getgrav.org/advanced/environment-config.md)）

ローカル環境のときだけ、特定の設定を読み込ませるようなことができます。  
CI/CD の中で制御する必要が無くなるので、便利です。

## Flex Objects{#flex}

[公式ドキュメント](https://learn.getgrav.org/advanced/flex) （[md](https://learn.getgrav.org/advanced/flex.md)）

Grav で、大量のデータを効率的に扱うための仕組みです。  
奥が深いので、ある程度ページコレクションに慣れてから使うことをおすすめします。

## マルチサイト{#multisite-setup}

[公式ドキュメント](https://learn.getgrav.org/advanced/multisite-setup) （[md](https://learn.getgrav.org/advanced/multisite-setup.md)）

1つの Grav だけで、複数サイトを運営する方法です。  
Grav が1つになるので、アップデートなどの手間が減ります。

「あるサイトのみ、このプラグインを有効化する」など、細かい設定が可能です。

## プラグインの優先順位{#plugin-prioritization}

[公式ドキュメント](https://learn.getgrav.org/advanced/plugin-prioritization) （[md](https://learn.getgrav.org/advanced/plugin-prioritization.md)）

Symfony のイベントディスパッチャーの基本的な説明です。  
同じイベントに複数のプラグインから処理が登録されている場合、どんな順番になるかを制御できます。

## スケジュール実行{#scheduler}

[公式ドキュメント](https://learn.getgrav.org/advanced/scheduler) （[md](https://learn.getgrav.org/advanced/scheduler.md)）

cron を使って、定期的に Grav の処理を実行することができます。  
cron を使うので、レンタルサーバーの場合は、 cron の権限のあるプランを使っている必要があります。


## バックアップ{#backups}

[公式ドキュメント](https://learn.getgrav.org/advanced/backups) （[md](https://learn.getgrav.org/advanced/backups.md)）

バックアップのとり方に関する解説です。  
上記のスケジュール実行と組み合わせて、定期バックアップが可能です。

## YAML 構文{#yaml}

[公式ドキュメント](https://learn.getgrav.org/advanced/yaml) （[md](https://learn.getgrav.org/advanced/yaml.md)）

Grav の設定で使われる YAML の解説です。  
一般的なことが書かれています。

## ユーザーグループと認証{#groups-and-permissions}

[公式ドキュメント](https://learn.getgrav.org/advanced/groups-and-permissions) （[md](https://learn.getgrav.org/advanced/groups-and-permissions.md)）

基本的に、あるユーザーの認可範囲は、 [API を使って制御します](https://learn.getgrav.org/2/api/authentication#permissions) 。  
ユーザーグループとは、同じ認可を持つユーザーをグループにまとめれば、一括して認可範囲を制御できる機能です。

が、ドキュメントの内容が 1.x 系で、2.x 系でも同じことができるのか、わかりません。検証された方は、教えていただけると幸いです。

## ベース URL の変更{#change-site-url}

[公式ドキュメント](https://learn.getgrav.org/advanced/change-site-url) （[md](https://learn.getgrav.org/advanced/change-site-url.md)）

ウェブサーバーのリライト機能と組み合わせて、 Grav を特定の場所で実行しているようにカスタマイズできます。

