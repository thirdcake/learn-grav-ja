---
title: セキュリティ
lastmod: '2026-11-08T18:56:31+09:00'
description: 'この章では、 Grav のセキュリティポリシー、セキュリティ問題の報告方法、そして現在までの報告状況について詳しく説明します。'
weight: 130
layout: 'chapter'
params:
    icon: '#security'
    srcPath: /security
    chapter_number: 13
---

[公式ドキュメント](https://learn.getgrav.org/security) （[md](https://learn.getgrav.org/security.md)）

Grav のセキュリティに関するチャプターです。  
昨今は、さまざまなセキュリティ問題が起きがちなので、これらのページを十分に活用してください。

公開環境（レンタルサーバー、クラウドサービス、 VPS など）ごとに気をつけるべきところは違いますが、いずれにも共通する基本的な対策は以下の通りです：

1. バックアップを定期的に取っておく（コンテンツ部分は Git 管理も有効）
1. Grav コア、テーマ、プラグインを定期的に更新する
1. 管理パネルを使う場合は、2要素認証やログイン試行制限を有効化する

バックアップやアップデート方法については、コマンドラインで行う場合は、 [Grav や GPM コマンドの解説ページ](https://learn.getgrav.org/2/cli-console) を参照してください。  
管理パネルで行う場合は、 [管理パネルの使い方ページ](https://learn.getgrav.org/2/admin-panel) を参照してください。

2要素認証（2FA）やログイン試行制限（フラッドプロテクション）については、 [2.x 系の解説](https://learn.getgrav.org/2/admin-panel) では説明が不足していると思うので、 [1.7 系のフラッドプロテクション](https://learn.getgrav.org/17/admin-panel/security/rate-limiting) および [1.7 系の2FA](https://learn.getgrav.org/17/admin-panel/security/2fa) も参照してみてください。
画面のデザインは違いますが、Admin2 の管理パネルでも同じ手順で有効化できます。

## 概要{#overview}

[公式ドキュメント](https://learn.getgrav.org/security/overview) （[md](https://learn.getgrav.org/security/overview.md)）

Grav にセキュリティ上の問題点を見つけた場合の取るべき行動などが解説されています。  
開発者向けの内容であり、サイトを管理するだけなら斜め読みで良いと思います。

## 推奨設定{#configuration}

[公式ドキュメント](https://learn.getgrav.org/security/configuration) （[md](https://learn.getgrav.org/security/configuration.md)）

`system.yaml` の推奨設定について例示されています。  
AI とはじめて Grav を構築する際には、必ずここを読み込ませてください。

## Email リンク用の信頼できるホスト{#trusted-host}

[公式ドキュメント](https://learn.getgrav.org/security/trusted-host) （[md](https://learn.getgrav.org/security/trusted-host.md)）

こちらも、Grav を最初に立ち上げるときに読んでおくべき設定です。  
初期設定時に、上記の推奨設定とともに、 AI に読み込ませてください。

## 外部にさらされるユーザーフォルダ{#user-folder-exposure}

[公式ドキュメント](https://learn.getgrav.org/security/user-folder-exposure) （[md](https://learn.getgrav.org/security/user-folder-exposure.md)）

Grav は、ファイルシステムベースであり、PHP が動く環境なので、危険なファイルをアップロードされ、それが外部から自由に実行できる状況にあると、大変危険です。  
大手レンタルサーバーの場合は、一般的なセキュリティ構成になっていることが多く、デフォルトの設定を守っていれば、過度に心配する必要はありません。  
一方、自分でサーバーを構築する場合（特に Apache2 以外のサーバーソフトウェアを選ぶ場合）は、十分に注意してください。

このページでは、管理パネルのダッシュボードに、 `Your web server is not applying Grav's access rules.` というような表示が出る場合の対策方法が解説されています。  
本来は外部に公開してはいけないフォルダが公開されているかもしれないという注意表示です。  
web サーバーソフトウェアごとの適切な設定をする必要があります。

## ユーザーセキュリティ{#users}

[公式ドキュメント](https://learn.getgrav.org/security/users) （[md](https://learn.getgrav.org/security/users.md)）

Grav のユーザーも、 Linux のユーザーも、適切な権限に抑えておくべきことが解説されています。

## 開発者向け情報{#developers}

[公式ドキュメント](https://learn.getgrav.org/security/developers) （[md](https://learn.getgrav.org/security/developers.md)）

テーマやプラグインの開発者が、気をつけるべきことの概要が解説されています。

## サーバーサイド{#server-side}

[公式ドキュメント](https://learn.getgrav.org/security/server-side) （[md](https://learn.getgrav.org/security/server-side.md)）

PHP やウェブサーバーソフトウェアの設定で注意すべき項目が例示されています。  
PHP 7.2 時代の記述も混じっており、今もその記述通りで良いか、判断できる知識が必要です。
かなり上級者向けの内容であり、普通の人はデフォルトの設定を優先すべきです。

## 脆弱性レポート{#reports}

[公式ドキュメント](https://learn.getgrav.org/security/reports) （[md](https://learn.getgrav.org/security/reports.md)）

Grav コアと Admin プラグイン（旧版）の Github セキュリティアドバイザリーへのリンク紹介です。


