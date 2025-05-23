---
title: 'Flex オブジェクト'
layout: ../../../layouts/Default.astro
lastmod: '2025-05-10'
---
**Flex オブジェクト** は、Grav 1.7 で登場した新しい概念です。あなたのサイトに、カスタムのデータタイプを、簡単に追加します。 **Flex オブジェクト** は、[**Flex Objects** プラグイン](https://github.com/trilbymedia/grav-plugin-flex-objects) により提供され、 [管理パネルプラグイン](../../05.admin-panel/) で必要になるもので、 [**Grav コア + 管理パネルプラグイン**](https://getgrav.org/downloads) パッケージに含まれています。

> [!Info]  
> このドキュメントの **Flex ディレクトリ** は、旧来の **Flex Directories プラグイン** とは全く関係ありません。実際、その古いプラグインは、 **Flex Objects プラグイン** により取って代わりました。

<h2 id="introduction">導入</h2>

**Flex** は、型のある **Directories** の集まりです。Grav は、自身で組み込まれた型を持ちます。たとえば、 **ユーザーアカウント** や、 **ページ** のような型です。プラグインとテーマでも、それぞれ自身の型を定義でき、Grav に登録できます。


#### Flex

**[Flex](./02.using/01.flex/)** とは、 **Flex ディレクトリ** を持つ箱（コンテナ）です。

Flex は、サイト内の Flex ディレクトリに入ったすべてのデータへ、ひとつのアクセスポイントを提供します。これにより、すべてのオブジェクトが、サイト内のすべてのページやプラグインから、利用可能となり、

> [!Note]  
> **TIP:** Flex *ユーザー アカウント* または Flex *ページ* が有効になっていい場合でも、フロントエンドや管理パネルの両方から、それぞれの Flex バージョンにアクセスすることが可能です。

#### Flex Type

**Flex タイプ** とは、**Flex ディレクトリ** のためのブループリントです。

Flex タイプは、コンテンツを表示したり、修正したりするのに必要なすべてを定義します。たとえば：データ構造、フォームフィールド、パーミッション、テンプレートファイル、ストレージレイヤさえも定義します。

#### Flex Directory

**[Flex ディレクトリ](./02.using/02.directory/)** とは、ひとつの **Flex タイプ** に適合する **Flex Objects** のコレクションをキープします。

各 Flex ディレクトリは、それぞれ **Objects** の **Collection** を持っています。オプションで、クエリーの **Storage** をスピードアップさせる **Indexes** もサポートします。

#### Flex Collection

**[Flex コレクション](./02.using/03.collection/)** とは、 **Flex オブジェクト** を持つ構造です。

Flex コレクションは、一般的に、ページに表示したり、与えられた処理を実行したりするための Flex オブジェクトのみを持ちます。データをさらにフィルタしたり、操作したりする便利なツールと、コレクション全体をレンダリングするメソッドを提供します。

#### Flex Object

**[Flex オブジェクト](./02.using/04.object/)** とは、 **Flex タイプ** のひとつのインスタンスです。

Flex オブジェクトは、ひとつのエンティティを表します。Flex オブジェクトにより、プロパティへアクセスできます。あらゆる関係データ、たとえば **[メディア](../../02.content/07.media/)** にもアクセス可能です。また、Flex オブジェクトは、自身がどのように **レンダリング** されるかや、コンテンツを編集するときに使用される **フォーム** も定義されています。オブジェクトの作成・更新・削除のようなアクションは、オブジェクトそれ自身でサポートされています。

#### Flex Index

**Flex インデックス** は、**Flex ディレクトリ** のクエリーを速くします。

**Flex オブジェクト** のメタデータを含みます。しかし、オブジェクトそのものは含みません。

#### Flex Storage

**Flex ストレージ** は、**Flex オブジェクト** のストレージレイヤーです。

ストレージの形式は、1つのファイルでも、1つのフォルダに複数のファイルでも、複数のフォルダでも良いです。Flex は、カスタムストレージにも対応しており、データベースストレージも可能です。

#### Flex Form

**Flex フォーム** は、**Form プラグイン** と統合し、 **Flex オブジェクト** を作成したり、編集したりできるようにします。

Flex は、オブジェクトの異なる部分を修正できるようにする、複数の views をサポートします。

#### Flex Administration 

**[Flex 管理](./01.administration/)** とは、 **Flex Objects プラグイン** で実装されるものです。

サイト管理者が **Flex オブジェクト** を管理するために、 **管理パネルプラグイン** 上に、新しいセクションを追加します。各 **Flex ディレクトリ** は、 CRUD タイプの ACL （Create, Read, Update, Delete タイプのアクセスコントロールリスト）を用意しており、これを使用することで、管理パネルの一部やその中のアクションを特定のユーザーに制限することができます。

<h2 id="current-limitations">現状の制限事項</h2>

改善点は、まだたくさんあります。以下は、Flex オブジェクトを使用を検討する際の、現状の制限事項です：

* 多言語サポートは、 **Pages**  にのみ実装され、管理パネルも完全には翻訳されていません
* フロントエンドにのみ、基本的なルーティングを持っています。たとえば保存のような、カスタムのタスクを処理するには、別途、独自の実装が必要です
* 管理パネルからのバルクアップデート（一斉アップデート）機能は、まだ実装されていません（コードからするのは、簡単です）
* インデックスの作成に限界があるため、常に更新されるオブジェクトには Flex は推奨されません
* **Flex タイプ** をカスタマイズするには、コーディングの知識が必要になり、独自クラスを作れる必要があります

