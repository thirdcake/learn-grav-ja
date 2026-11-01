---
title: セキュリティ
lastmod: '2026-11-01T18:56:31+09:00'
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
昨今は、さまざまなセキュリティ問題が起きがちなので、十分に注意してください。

## 概要{#overview}

[公式ドキュメント](https://learn.getgrav.org/security/overview) （[md](https://learn.getgrav.org/security/overview.md)）

Grav にセキュリティ上の問題点を見つけた場合の取るべき行動などが解説されています。  
利用者側はそれほど気にしなくて良いと思います。


## 推奨設定{#configuration}

[公式ドキュメント](https://learn.getgrav.org/security/configuration) （[md](https://learn.getgrav.org/security/configuration.md)）

`system.yaml` の推奨設定について例示されています。  
AI がはじめて Grav を構築する際には、ここを必ず読み込ませてください。

## ユーザーセキュリティ{#users}

[公式ドキュメント](https://learn.getgrav.org/security/users) （[md](https://learn.getgrav.org/security/users.md)）

Grav のユーザーも、 Linux のユーザーも、適切な権限に抑えておくべきことが解説されています。

## 開発者向け情報{#developers}

[公式ドキュメント](https://learn.getgrav.org/security/developers) （[md](https://learn.getgrav.org/security/developers.md)）

テーマやプラグイン開発時のベストプラクティスが解説されています。

## サーバーサイド{#server-side}

[公式ドキュメント](https://learn.getgrav.org/security/server-side) （[md](https://learn.getgrav.org/security/server-side.md)）

サーバーサイドで設定すべき項目を例示されています。  
上級者向けの内容です。

## 脆弱性レポート{#reports}

[公式ドキュメント](https://learn.getgrav.org/security/reports) （[md](https://learn.getgrav.org/security/reports.md)）

Grav コアと Admin プラグイン（旧版）の Github セキュリティアドバイザリーへのリンクがされています。

## Email リンク用の信頼できるホスト{#trusted-host}

[公式ドキュメント](https://learn.getgrav.org/security/trusted-host) （[md](https://learn.getgrav.org/security/trusted-host.md)）

こちらも、Grav を最初に立ち上げるときに読んでおくべき設定です。  
初期設定時に、上記の推奨設定とともに、 AI に読み込ませてください。

## 外部にさらされるユーザーフォルダ{#user-folder-exposure}

[公式ドキュメント](https://learn.getgrav.org/security/user-folder-exposure) （[md](https://learn.getgrav.org/security/user-folder-exposure.md)）

管理パネルのダッシュボードに、 `Your web server is not applying Grav's access rules.` というような表示が出る場合は、本来は外部に公開してはいけないフォルダが公開されているかもしれません。  
このページを参考に、 web サーバーごとの適切な設定をする必要があります。


