---
title: ブループリント
layout: ../../../layouts/Default.astro
lastmod: '2025-04-24'
---
<h2 id="what-is-a-blueprint">ブループリントとは？</h2>

ブループリント（blueprint、青焼き図面、設計図）とは、Gravにとって重要なものです。テーマやプラグインとGravの管理パネルがやりとりするための根底にあるものです。ブループリントによって、Gravはテーマやプラグインが何であるかを知り、その名前や GitHub のどこにあるのかなどを知ります。また、Gravの管理パネル内でテーマやプラグインの設定オプションを生成します。

ブループリントは、YAML ファイルで書かれ、一般的に、form の定義と同じ書かれ方ができます。

ほとんどのGrav ユーザーは、ブループリントを編集することはありません。かんたんに言うと、プラグインやテーマがサイトのバックエンドでどのように現れるかを決定するものです。ほとんどのユーザーにとって、Gravの管理パネルを使ってテーマやプラグインの設定をしたり、主要なYAMLファイルのオプション操作をするのは、ここです。

ブループリントを扱うのは、開発者です。彼らは新しいテーマやプラグインを作り、バックエンドのリソースオプションをカスタマイズします。ブループリントは、強力なツールなので、リソースが何かや、Gravがどこでアップデートを探すか、どんなオプションが設定できるかなどを決定してくれます。

<h2 id="types-of-blueprints">ブループリントのタイプ</h2>

Gravでは、ブループリントを次のように使います：

- テーマとプラグインの情報を定義する
- テーマ/プラグインの設定オプションを管理プラグインに表示する
- 管理パネル画面中のページのフォームを定義する
- 管理パネルの Congiguration セクションのオプションに表示する内容を定義する
- フレックス・ディレクトリ/オブジェクトを定義する

ここで、ブループリントがGravでどのように機能するか、詳細を説明します。

<h4 id="themes-and-plugins">テーマとプラグイン</h4>

テーマやプラグインを使う時、慣例的に、パッケージに **blurprints.yaml** ファイルを置きます。こうすることで、Gravにそのリソースのメタデータが伝わり、管理プラグイン上で表示できるようになります。

**blueprints.yaml** ファイルは、あらゆるテーマやプラグインで重要です。GPM（Gravパッケージマネージャ）システムに不可欠です。GPMは、ブループリントに入っている情報を使って、ユーザーがプラグインを使えるようにします。

[具体例：プラグインのブループリント](./02.example-plugin-blueprint/) では、**Assets** プラグインのブループリントを詳しく見ていきます。このブループリントには、名前や、作者情報、キーワード、ホームページ、バグレポートリンク、その他のメタデータが入力されています。また、どこでプラグインアップデートを探せるかをGravシステムに教えるだけでなく、管理プラグインからアクセス可能で便利なリソースを提供します。

このリソース情報が与えられたら、ブループリントのページのさらに下に、フォームの情報が見えるようになります。この情報は Grav のバックエンドでユーザーがアクセスできる管理フォームを作成します。例えば、プラグインの特定の機能を有効または無効にするトグルを追加したい場合、ここに追加します。

![Admin Forms](blueprints_1.png)

**blurprints.yaml** ファイルは、プラグイン名のYAMLファイル（例：**assets.yaml**）と連携して機能します。ブループリントは、設定可能なオプションを定義します。プラグイン名のYAMLファイルは、それぞれの具体的な値を設定します。このプラグイン名のYAMLファイルは、後で `user/config` にコピーされ、デフォルト値を上書きできます。この複製は、手動でも、管理パネルからでも行えます。

テーマやプラグインで設定オプションに関しては、**blurprint.yaml** ファイルで定義し、プラグイン名（テーマ名）のYAMLファイルでその設定内容を教えてくれます。

<h4 id="pages">ページ</h4>

Gravのページは、何にでもなれます。ブログの一覧ページにもなりえますし、ブログ投稿にも、製品ページにも、イメージギャラリーにも、その他でも。

どのページが何をして、何を表すべきかを決めるのは、**ページのブループリント** です。

Grav では、いくつかの基本的なページのブループリントを提供します：Default と、Modular です。これらは、Gravを構築する主要な2大要素です。

ページのブループリントは、テーマによって追加され、セットアップされます。テーマは、できるだけ多くのページのブループリントを追加するかもしれませんし、特定の用途のためのページのブループリントに焦点を当てるかもしれません。

Grav のテーマは、他のプラットフォームで使われている以上に柔軟でパワフルです。

この点で、テーマは特化させられます。たとえば、次のような目的のひとつに特化できます：

- ドキュメントサイトを作りたい（あなたが今読んでいるような）
- Eコマースサイトを作りたい
- ブログを作りたい
- ポートフォリオサイトを作りたい

テーマは同時に、これらすべてを作ることも許容します。しかし通常は、ひとつの目的に集中してチューニングされたテーマは、何でもこなすテーマよりも、より目標を満たしてくれます。

ページファイルは、`blog.md` や、 `default.md` 、 `form.md` のようなマークダウンファイルを設定することで、ページとして使われます。

それぞれのファイルは、異なるページファイルを使います。[フロントマターのtemplate](../../02.content/02.headers/#template) によってファイルタイプを変えることもできます。

ページで使われるテンプレートは、単にフロントエンドの "見た目や雰囲気" を決定するだけでなく、どのように管理パネルプラグインがレンダリングし、オプションやセレクトボックス、カスタム入力、トグル入力などを追加するかも決定します。。

そのやり方：テーマで、`blueprints/` フォルダを追加し、ページテンプレート名のYAML ファイルを追加します。たとえば、`blog` ページがある場合、`blueprints/blog.yaml` を追加してください。[**Antimatter** テーマの例](https://github.com/getgrav/grav-theme-antimatter/tree/develop/blueprints) があります。

<h2 id="components-of-a-blueprint">ブループリントの構成</h2>

**blueprints.yaml** ファイルに書かれる情報は、2種類あります。1種類目のメタデータ情報は、そのリソースそのもののアイデンティティです。2つ目は、フォームに関する情報です。これらすべてが、ひとつの **blurprints.yaml** ファイルに入力され、それぞれのプラグインやテーマのルートフォルダに置かれています。

以下は、**blueprints.yaml** ファイルのメタデータ部分の例です：

```yaml
name: GitHub
slug: github
type: plugin
version: 1.0.1
description: "This plugin wraps the [GitHub v3 API](https://developer.github.com/v3/) and uses the [php-github-api](https://github.com/KnpLabs/php-github-api/) library to add a nice GitHub touch to your Grav pages."
icon: github
author:
  name: Team Grav
  email: devs@getgrav.org
  url: https://getgrav.org
homepage: https://github.com/getgrav/grav-plugin-github
keywords: github, plugin, api
bugs: https://github.com/getgrav/grav-plugin-github/issues
license: MIT
```

見てのとおり、ここには、プラグインの一般的な識別情報が書かれます。たとえば、プラグイン名や、バージョン番号、説明、作者情報、ライセンス、キーワード、より詳しい情報を得るためやバグレポートするためのURL などです。このセクションは、以下のような管理パネルのスクリーンショットで見られます。

![Admin Forms](blueprints_2.png)

次のセクションは、フォームに関する部分で、上記のデータのすぐ下にあります。この部分は、管理パネルからプラグイン設定をするためのフォームや入力欄を生成します。以下は、**blueprints.yaml** ファイルのこの部分の簡易的な例です。

```yaml
form:
  validation: strict
  fields:
    enabled:
        type: toggle
        label: Plugin status
        highlight: 1
        default: 1
        options:
            1: Enabled
            0: Disabled
        validate:
            type: bool
```

ファイルのこの部分は、管理パネルからアクセス可能な管理オプションを作成します。この例では、管理画面からプラグインを有効または無効にできるシンプルなトグルを作成しています（以下の画像のようになります）

![Admin Forms](blueprints_3.png)

<h2 id="debugging-blueprints">ブループリントのデバッグ</h2>

ブループリントファイルにエラーがあると、予期せぬ結果を引き起こすかもしれません。

> [!Tip]  
> **TIP:** **CLIコマンド** で、 `bin/grav yamllinter` を実行すると、yaml ファイルのエラーレポートが取得できます。YAML ファイルを修正するときには、価値のある情報が得られるかもしれません。

