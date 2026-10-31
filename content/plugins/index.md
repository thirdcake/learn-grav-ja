---
title: プラグイン
lastmod: '2026-10-28T19:06:24+09:00'
description: 'Grav は、強力なプラグインアーキテクチャのおかげで、自由に機能を拡張できます。コア機能の強化から、便利な機能の追加まで、思いのままです。'
weight: 40
layout: 'chapter'
params:
    icon: '#plugins'
    srcPath: /plugins
    chapter_number: 4
---

[公式ドキュメント](https://learn.getgrav.org/plugins)

Grav に機能を追加する **プラグイン** を解説するチャプターです。

Grav のコア機能は、 [コンテンツのチャプター](../content/) で解説された通り、ほとんど「ページ」の操作がメインです。
そもそも、基本的な設計方針として、コア機能は超薄く（ **super-lean** ）作られており、テーマは表示に関する Twig 変換しかしません。

それ以外の機能は、このチャプターで解説される「プラグイン」を使って拡張します。

## プラグインの基本{#plugin-basics}

[公式ドキュメント](https://learn.getgrav.org/plugins/plugin-basics) （[md](https://learn.getgrav.org/plugins/plugin-basics.md)）

プラグインがどんなものかを解説しています。

## プラグインのインストール{#plugin-install}

[公式ドキュメント](https://learn.getgrav.org/plugins/plugin-install) （[md](https://learn.getgrav.org/plugins/plugin-install.md)）

プラグインのインストール方法を解説しています。  
推奨方法は GPM コマンドと書いてありますが、管理パネルからも可能です。

## プラグインのチュートリアル{#plugin-tutorial} 

[公式ドキュメント](https://learn.getgrav.org/plugins/plugin-tutorial) （[md](https://learn.getgrav.org/plugins/plugin-tutorial.md)）

プラグインを自作する場合のチュートリアルです。  
Symfony ベースのイベントディスパッチャーの具体的な使い方が学べます。

## イベントフック{#event-hooks}

[公式ドキュメント](https://learn.getgrav.org/plugins/event-hooks) （[md](https://learn.getgrav.org/plugins/event-hooks.md)）

Grav のコアで用意されているイベントのリファレンスです。  
どんなときにどんなイベントが発火するのか、その順番は大事なのでプラグインを自作する際には必ず参照します。

```txt
https://learn.getgrav.org/plugins/event-hooks.md

上記のページを参考に、適切なイベント名を教えてください。
Twig テンプレートに、 URL のパラメータ（`?restaurant_id=1`）次第で異なる文字列を渡したいです。
具体的には、 `restaurant_id` が以下のとき、Twig の変数 `restaurant` に以下の文字列を渡したいです。
1のとき：`サイゼリヤ`
2のとき：`びっくりドンキー`
3のとき：`ロイヤルホスト`
それ以外のとき： `ジョイフル`
```

## Grav ライフサイクル{#grav-lifecycle}

[公式ドキュメント](https://learn.getgrav.org/plugins/grav-lifecycle) （[md](https://learn.getgrav.org/plugins/grav-lifecycle.md)）

このページも、イベントに関するリファレンスです。  
上記のイベント説明とは違い、ここでは発火の順番が分かります。

どこでどんなサービスが初期化されているかがわからないと、その時点のイベントで何が使えるかわからないので、便利です。

なお、ここに列挙されているのは、コアで発火するイベントです。  
これらの他に、たとえば API プラグインで発火するイベントは、[その説明ページが別にあります](https://learn.getgrav.org/api/events) 。

## フレックスオブジェクトのプラグイン{#plugin-flex}

[公式ドキュメント](https://learn.getgrav.org/plugins/plugin-flex) （[md](https://learn.getgrav.org/plugins/plugin-flex.md)）

Flex Objects のプラグインを Devtools で作るときのチュートリアルです。  
そもそも Flex Objects とは何かについては、「アドバンス」のチャプターを参照してください。

## API 統合{#plugin-api-integration}

[公式ドキュメント](https://learn.getgrav.org/plugins/plugin-api-integration) （[md](https://learn.getgrav.org/plugins/plugin-api-integration.md)）

API プラグインを使って、独自の RESTful API のエンドポイントを作る方法のチュートリアルです。

## プラグインの互換性{#plugin-compatibility}

[公式ドキュメント](https://learn.getgrav.org/plugins/plugin-compatibility) （[md](https://learn.getgrav.org/plugins/plugin-compatibility.md)）

プラグインの設計図（ブループリント）ファイルで、プラグインが対応する Grav のバージョンを指定できます。  
1.x 系と 2.x 系での設定方法が解説されています。

## 管理画面翻訳{#admin-translations}

[公式ドキュメント](https://learn.getgrav.org/plugins/admin-translations) （[md](https://learn.getgrav.org/plugins/admin-translations.md)）

管理パネル（Admin2 プラグイン）で表示する翻訳ファイルの書き方について、バージョンごとに解説されています。

## マークダウン拡張{#markdown-extensions}

[公式ドキュメント](https://learn.getgrav.org/plugins/markdown-extensions) （[md](https://learn.getgrav.org/plugins/markdown-extensions.md)）

マークダウンがパースされるときに、独自の処理を追加したい場合の書き方が解説されています。

## GPM 提出{#gpm-submission}

[公式ドキュメント](https://learn.getgrav.org/plugins/gpm-submission) （[md](https://learn.getgrav.org/plugins/gpm-submission.md)）

プラグインを一般に公開して、 GPM から利用可能にするために必要なことが解説されています。

