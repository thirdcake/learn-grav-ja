---
title: 'ベーシック'
lastmod: '2026-10-25T21:02:59+09:00'
description: 'Grav の基本的なことを解説します'
weight: 10
layout: 'chapter'
params:
    icon: '#basics'
    srcPath: /basics
    chapter_number: 1
---

Grav のベーシック（基本的）なことを解説するチャプターです。

## Grav とは？{#what-is-grav}

[公式ドキュメント](https://learn.getgrav.org/basics/what-is-grav) （[md](https://learn.getgrav.org/basics/what-is-grav.md)）

Grav とは何か、どんな問題を解決するために、どんな技術で作られているかが書かれています。  
他の CMS のどんなところが不満で、 Grav を作ったのかも書かれています。

機能に関するもう少し詳しい説明は、公式サイトの [Features](https://getgrav.org/features) や、 [About](https://getgrav.org/about) も参考になります。  
WordPress との詳しい比較は、 [ブログ記事](https://getgrav.org/blog/grav-vs-wordpress) もあります。

**プロンプト例**

```txt
会社のドキュメントサイトを作る際に Grav CMS を検討中です。

https://learn.getgrav.org/basics/what-is-grav.md
https://getgrav.org/features.md
https://getgrav.org/about.md

上記のページを参考に教えてください。
- レンタルサーバーで動かしたい
- 管理画面でページ管理したい
- ワンクリックでアップデートしたい
このような用途に、Grav は向いていますか？
```

## システム要件{#requirements}

[公式ドキュメント](https://learn.getgrav.org/basics/requirements) （[md](https://learn.getgrav.org/basics/requirements.md)）

Grav を動かすためのシステム要件が書かれています。

RDBMS（MySQLなど）のお世話をしなくて良く、 PHP 拡張もよくあるものだけ使って作られています。  
レンタルサーバーならファイルを置くだけで動きますし、 VPS やクラウドサービスでも、設定が少なく済み、メンテナンスコストも低いです。

## インストール{#installation}

[公式ドキュメント](https://learn.getgrav.org/basics/installation) （[md](https://learn.getgrav.org/basics/installation.md)）

インストール方法の解説です。  
いくつかの方法が解説されていますが、どの方法でも最終的にはファイルを置くだけです。  
OS や web サーバーの種類によって追加設定が必要な場合は、このページやここからリンクされているトラブルシューティングページに解説があります。

## 基本のチュートリアル

[公式ドキュメント](https://learn.getgrav.org/basics/basic-tutorial) （[md](https://learn.getgrav.org/basics/basic-tutorial.md)）

ファイルシステムでの設定やページ作成の基本が解説されています。  
管理パネルから操作する場合のチュートリアルは、 [管理パネル](https://learn.getgrav.org/admin-panel) を参照してください。

## config 設定

[公式ドキュメント](https://learn.getgrav.org/basics/grav-configuration) （[md](https://learn.getgrav.org/basics/grav-configuration.md)）

`user/config/system.yaml` や `user/config/site.yaml` などの、初期設定で必要なことがリファレンス形式で書かれています。  
Grav を初めて設定するときは、このページを AI に読ませて、自分の環境ごとの設定方法を尋ねると良いです。

なお、セキュリティページに、[ローカル開発環境や、本番環境での推奨設定](https://learn.getgrav.org/security/configuration.md) もあるので、合わせて確認してください。

**プロンプト例**

```txt
はじめて Grav を設定します。
https://learn.getgrav.org/basics/grav-configuration.md
https://learn.getgrav.org/security/configuration.md

上記のページを参考に、`sytem.yaml` の設定教えてください。
- 基本的な本番環境での設定をしたいです
- 日本語対応にしたいです（つまり、プラグインなどからのメッセージを日本語にしたいです）
- その際、 URL に `/jp/...` のような日本語のプレフィックスを入れたくありません
```

## フォルダ構造

[公式ドキュメント](https://learn.getgrav.org/basics/folder-structure) （[md](https://learn.getgrav.org/basics/folder-structure.md)）

Grav は、ファイルベースのシステムなので、ディレクトリ構造を知っておくとトラブル時に便利です。  
`user/` ディレクトリ以外は、サイト制作者、管理者が触ることはないので、ほとんど流し読みで良いです。  
`user/` ディレクトリ以下のどこを、どのように編集可能かを知っておくと、操作が楽になります。

## ヘルプ

[公式ドキュメント](https://learn.getgrav.org/basics/getting-help) （[md](https://learn.getgrav.org/basics/getting-help.md)）

困ったときに、どこを頼れば良いかの一覧です。

- 公式フォーラム: 検索すると似たような解決事例が見つかったりします。回答者も複数人いるようなので、比較的回答も得やすいです
- Twitter: ときどきお知らせがあります
- Blog: 長めのお知らせもあり、バージョンアップ時などにメンテナーの意図がわかったりします
- GitHub Issues: バグ報告などは喜ばれます
- Discord: お知らせや、雑談などが中心です。話題が流れていくのも早いです

何か困ったことがあって、公式ドキュメントを読ませた AI にもわからない場合は、基本的には、フォーラムの過去事例を検索してみて、類似事例を探してみてください。  
それでも無い場合は、 [The art of asking great questions](https://getgrav.org/forum/general/the-art-of-asking-great-questions-t8554) をもとに、フォーラムで質問してみるのが良いと思います。

明らかなバグであれば、 GitHub Issues に送ってあげてください。  
単に Grav を使っている他の人の意見が聞きたい、コミュニケーションを取りたいなら、 Discord がおすすめです。

## Gravとプラグインのアップデート

[公式ドキュメント](https://learn.getgrav.org/basics/updates) （[md](https://learn.getgrav.org/basics/updates.md)）

アップデート方法について解説されています。  
1.x 系から 2.x 系へのアップデートは、 [Migration](https://learn.getgrav.org/migration) というチャプターがあります。

