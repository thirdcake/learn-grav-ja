---
title: 'Flex オブジェクト'
lastmod: '2026-08-03T22:24:13+09:00'
description: 'Grav で型安全にオブジェクトを管理でき、 CRUD 処理やレンダリング機能までやってくれる Flex オブジェクトの概要を解説します'
weight: 10
params:
    srcPath: /advanced/flex
---

**Flex** は、 Grav のカスタム構造化データ用フレームワークです。
これにより、ページツリーや設定ファイルに置くには合わないデータ（商品、イベント、チームメンバー、住所録、サポートチケット、その他何でも）のコレクションを保存し、問い合わせ、編集し、表示する一貫した方法を提供します。

Flex は、2つの側面からなります：

* コアの **Flex フレームワーク** （ `Grav\Framework\Flex` ）, これは、 Grav に同梱されています。オブジェクトや、コレクション、インデックス、ストレージレイヤー、その他すべてを定義します。
* **Flex Objects** プラグイン, これは、フレームワークを PHP を書くことなく本番環境に導入できるようにします。このプラグインは、カスタムディレクトリの登録や、 CRUD 管理用の **Admin Next** UI や、 `[flex-objects]` ショートコードや、フロントエンドページのルーティング及びテンプレートを提供します。

> [!NOTE]  
> このドキュメント中の **Flex Directories** は、古い **Flex Directories** プラグインとは関係がありません。このプラグインは、 Flex フレームワークと Flex Objects プラグインの継承元でした。

## Flex は、どんな問題を解決するか？{#what-problem-does-flex-solve}

すべての Grav サイトは、最終的にはページもしくは設定のデータが必要です。
ページは、URL と本文とメディアを持つ編集コンテンツに最適です。
設定ファイルは、あまり変更されないオプションを使いやすくします。
しかし、500個の商品リストや、予約システム、メンバーディレクトリなどは、それらに当てはまりません。
それらのデータをページとして扱うと、（レコードごとにフォルダが必要で、実際の問い合わせは無く、構造化入力欄も無いので）扱いにくく、設定ファイルは、レコードデータを保持することを全く意図していません。

Flex は、このギャップを埋めます。
最初に **blueprint** に（入力欄や、フォーム、ストレージ、パーミッション）を書けば、 Flex が編集可能な管理パネルセクションや、 PHP や Twig から問い合わせ可能なコレクション API や、REST API や、フロントエンドレンダリングを与えてくれます。
同じオブジェクト定義で、すべてのアクセスポイントを提供するので、管理パネルのフォームや、 API ペイロードや、 Twig テンプレート のレコード形式は一致しています。

## いつ Flex を使うか{#when-to-use-flex}

Flex は、 Grav 内でデータを保持する方法のひとつです。
やりたいことにマッチするツールを使ってください。


| 使うもの | 使う場面 |
| :------- | :------- |
| **Pages** | The record is editorial content that wants its own URL, body copy, and media (a blog post, a landing page, a documentation article). |
| **Config / YAML** | You have a small, mostly static set of options that a site builder edits occasionally (site title, API keys, feature flags). |
| **Accounts**      | You are storing login users. The accounts system is itself a built-in Flex type, so you get user records for free without defining anything. |
| **Flex**  | You have many structured records of the same kind that need querying, an editing UI, an API, or all three, and that are not primarily URL-addressable content. |


A useful rule of thumb: if you would reach for a database table on another platform, you probably want a Flex type here.

## Built-in types

You are already using Flex even if you have never defined a type. Under the hood, several core systems are Flex directories:

* **Pages** are backed by a Flex type, which is what lets Admin Next list, search, and edit them consistently.
* **User Accounts** are a Flex type. Every account you manage in admin is a Flex object.
* **User Groups** are a Flex type as well.

Because these are real Flex directories, you can query them from Twig and PHP with the same collection API you use for your own custom types. You do not have to enable anything special to read the built-in Flex versions of accounts or pages.

## Concept glossary

Flex introduces a handful of terms. They map cleanly onto each other, so it is worth reading them once before you start.

### Flex

**Felx** とは、すべての **Flex ディレクトリ** が入っているコンテナです。
Flex は、サイト上に登録されたすべてのディレクトリへの唯一のアクセスポイントであり、すべてのデータが、あらゆるページや、プラグインや、テンプレートから利用できるようにしてくれます。

### Flex Type

**Flex Type** とは、Flex ディレクトリのための設計図（ブループリント）です。
データを保存したり、表示したり、修正したりするのに必要なすべてを定義します：データ構造や、フォームの入力欄、パーミッション、テンプレートファイル、及びストレージレイヤーなどです。
Type は、 blurprint YAML ファイルに記述されます。
（[カスタムタイプの作成](./03.custom-types/) をご覧ください）


### Flex Directory

**Flex Directory** は、**Flex Objects** のコレクションを一つ持ちます。すべてのデータが、 **Flex Type** に適合します。
各ディレクトリは、 **Objects** の **Collection** を管理し、**Storage** への問い合わせをスピードアップさせる **Index** をオプションで持ちます。

### Flex Collection

**Flex Collection** は、**Flex Objects** を持つ構造です。
コレクションは、通常、現在のページやアクションに必要なオブジェクトのみを持ちます。
コレクションは、データをフィルタリングしたり、操作したりするツールを提供し、加えて、一度にそれらすべてをレンダリングするメソッドを持ちます。


### Flex Object

**Flex Object** は、1つのレコードを表現する **Flex Type** のひとつのインスタンスです。
そのオブジェクトから、そのプロパティや、 [メディア](../../02.content/07.media/) のような、オブジェクトに関係するデータへのアクセスを提供します。
また、オブジェクトは、自身のレンダリング方法や、編集時に利用するフォームも登録されています。
作成、更新、削除は、すべてオブジェクトで操作されます。

### Flex Index

**Flex Index** は、ディレクトリへの問い合わせを速くします。
これは、オブジェクトに関する軽量な（ソートや、フィルタリング、ページネーションに必要な程度の）メタデータを持っており、メモリにオブジェクト全体を読み込むことなく利用できます。

### Flex Storage

**Flex Storage** は、オブジェクトを永続化するレイヤーです。
それは、1つのファイルでシェアすることもできますし、オブジェクトごとに1つのファイルにすることや、オブジェクトごとに1つのフォルダにすることもできます。
Flex は、データベースのような、カスタムのストレージバックエンドをサポートします。
この保存方法は、オブジェクトごとに type がメディアを持つことができるかどうかに影響します。（[Flex はどのように機能するか](./02.concepts/) をご覧ください。）

### Flex Form

**Flex Form** は、Form プラグインを統合し、 Flex Object を作成したり編集したりできるようにします。
Flex は、複数の view をサポートし、あるオブジェクトの異なる部分を別のフォームから編集できます。

### Flex Administration

**Flex Administration** は、 Flex Objects プラグインから提供されます。
**Admin Next** に、 Flex Objects を管理する場所を付け加えます。
すべてのディレクトリは、 CRUD スタイルのパーミッションとともに提供されるので、リスト表示や、読み取り、作成、更新、削除などを特定のユーザーに制限することができます。
[Admin Next 内での Flex の管理](./05.administration/) をご覧ください。

## 現状の制限事項{#current-limitations}

Flex は、パワフルですが、すべての用途に最適なツールではありません。
利用を検討時には、以下の制約を覚えておいてください：

* Flex は、 **インデックスベース** なので、コンスタントに書き換わるデータには不向きです。1秒間に何度も書き換わるレコード用には、その目的に作られたデータベースを利用する方が適切です。
* **blurprint で表現できる以上の** ふるまいをカスタマイズする（問い合わせや、プロパティの計算、オーダーメイドのストレージ、詳細なバリデーションなど）場合は、 PHP class を書く必要があります。 [PHP で Flex を拡張する](./06.php-and-events/) をご覧ください。 
* **多言語** サポートは、 **Pages** の方が得意です。カスタムのタイプは、翻訳されたフィールドを保存できますが、Grav のページレベルの多言語操作と完全に同等の制御は、まだありません。

これらはいずれも、 Flex を使ったデータ駆動機能のほとんどを構築することを妨げるものではありません。
これらは単に、設計を確定する前に留意すべき注意点に過ぎません。


## 次に読むべき資料{#where-to-go-next}

* [Quickstart](/20/advanced/flex/quickstart) - build and enable your first custom type in a few minutes.
* [How Flex Works](/20/advanced/flex/concepts) - the framework model: objects, collections, indexes, and storage strategies.
* [Creating a Custom Type](/20/advanced/flex/custom-types) - the full blueprint reference, from `config` to `form`.
* [Displaying Flex Content](/20/advanced/flex/frontend) - the `[flex-objects]` shortcode, the flex-objects page type, and Twig rendering.
* [Managing Flex in Admin Next](/20/advanced/flex/administration) - list columns, edit forms, exports, menu placement, and permissions.
* [Extending Flex in PHP](/20/advanced/flex/php-and-events) - custom object, collection, and index classes, plus the Flex events.
* [Twig & PHP Reference](/20/advanced/flex/reference) - the API surface for querying and rendering Flex in code.
* [Troubleshooting & FAQ](/20/advanced/flex/troubleshooting) - common errors, permission gotchas, and how to fix them.
* [REST API](/20/api/endpoints/flex-objects) - the Flex Objects HTTP endpoints, auth, and response envelope.


