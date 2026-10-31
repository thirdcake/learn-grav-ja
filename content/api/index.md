---
title: 'API'
lastmod: '2026-10-31T20:13:08+09:00'
description: 'Grav の RESTful API は、サイトのコンテンツ、メディア、設定、ユーザー、及びシステム管理へのヘッドレスなアクセスを提供します。 AI エージェント、カスタムされたフロントエンド、モバイルアプリ、そして次世代管理インターフェースへ向けて構築されています。'
weight: 65
layout: 'chapter'
params:
    icon: '#api'
    srcPath: /api
---

[公式ドキュメント](https://learn.getgrav.org/api) （[md](https://learn.getgrav.org/api.md)）

公式の [Api](https://getgrav.org/downloads/plugins#api) プラグインでできることをまとめているチャプターです。

Grav が 2.0 系になり、ファーストパーティー製の RESTful API が使えるようになりました。  
基本的に、特定のユーザー権限を持つ場合に使える機能です。

## はじめに{#getting-started}

[公式ドキュメント](https://learn.getgrav.org/api/getting-started) （[md](https://learn.getgrav.org/api/getting-started.md)）

API プラグインでできることの概要が書かれています。  
普通の REST API アプリとしても使えますし、 MCP サーバー経由で AI が操作することもできますし、 Admin2 プラグイン経由で人間が操作することもできます。

## 認証{#authentication}

[公式ドキュメント](https://learn.getgrav.org/api/authentication) （[md](https://learn.getgrav.org/api/authentication.md)）

認証方法の解説です。  
API キーを作らなくても、たとえば Admin2 で認証しているユーザーはセッション認証で利用できます。

ユーザーのパーミッションのリファレンスも、このページに一覧があります。

## Rest API リファレンス{#endpoints}

[公式ドキュメント](https://learn.getgrav.org/api/endpoints) （[md](https://learn.getgrav.org/api/endpoints.md)）

API の具体的なリファレンスです。  
ページや設定などを、どのようなリクエストで、どのようなレスポンスを受け取れるのかがリファレンス形式で解説されています。  
REST API を作りたい場合は、最も役に立つページだと思います。

## イベント{#events}

[公式ドキュメント](https://learn.getgrav.org/api/events) （[md](https://learn.getgrav.org/api/events.md)）

API プラグインが発火するイベント一覧です。  
自作のプラグインで、 API プラグインの挙動に合わせて何か処理を実装したい場合は、これらのイベントを利用します。

## 開発者向けガイド{#developer-guide}

[公式ドキュメント](https://learn.getgrav.org/api/developer-guide) （[md](https://learn.getgrav.org/api/developer-guide.md)）

主に Admin2 プラグイン内で API と連動しながらカスタム処理を追加したい場合の実装方法が書かれています。  
Blueprint の他に、標準の JavaScript （いわゆる Vanilla JS）のカスタムエレメントを使った拡張方法があり、かなり込み入った機能も追加可能です。

## デモモード{#demo-mode}

[公式ドキュメント](https://learn.getgrav.org/api/demo-mode) （[md](https://learn.getgrav.org/api/demo-mode.md)）

この機能は使ったことが無いので詳細は分かりませんが、 Admin2 プラグインの中まで含めて、一般の外部ユーザーが使用感を試せるデモモードにできるようです。

