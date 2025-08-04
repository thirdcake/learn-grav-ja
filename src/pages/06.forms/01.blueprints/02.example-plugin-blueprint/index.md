---
title: 具体例：プラグインのブループリント
layout: ../../../../layouts/Default.astro
lastmod: '2025-08-04'
description: 'Grav のプラグインで利用されるブループリントの例を示し、各プロパティの概要を解説します。'
---

プラグインのブループリントは、そのプラグインがどのようなものかを Grav に知らせます。  
ソースや、サポート、作成者などの情報のほか、依存関係、管理パネルで使うフォームの設定なども知らせます。

具体例として、次のようなプラグインのブループリントを使います：

```yaml
name: Assets
slug: assets
type: plugin
version: 1.0.4
description: "This plugin provides a convenient way to add CSS and JS assets directly from your pages."
icon: list-alt
author:
  name: Team Grav
  email: devs@getgrav.org
  url: https://getgrav.org
homepage: https://github.com/getgrav/grav-plugin-assets
demo: https://learn.getgrav.org
keywords: assets, javascript, css, inline
bugs: https://github.com/getgrav/grav-plugin-assets/issues
license: MIT

dependencies:
  - { name: afterburner2 }
  - { name: github }
  - { name: email, version: '~2.0' }
```

リソースの識別に使える異なるプロパティがあります。  
いくつかは **必須** で、それ以外は _オプション_ です。

| プロパティ         | 説明  |
| :-----           | :-----  |
| __name*__        | リソース名です。Plugin か Theme かを付ける必要はありません。 |
| __slug*__        | リソースに対する一意の識別子です。また、リソースが保存されているフォルダ名を決定するためにも使われます（例： `user/plugins/slug` ） |
| __type*__        | リソースのタイプです。 `plugin` か、 `theme` のいずれかです。 |
| __version*__     | リソースのバージョンです。この値は、リリースごとに、常に増加すべきです。また、標準的な [セマンティックバージョニング](http://semver.org/) に従うべきです。 |
| __description*__ | リソースの説明です。 **200** 文字を超えないでください。説明は短く、的を射たものとしてください。必要であれば markdown 構文が使えます。クオテーションマークで囲むのも良い考えです。 |
| __icon*__        |  Icon は、 [getgrav.org](https://getgrav.org) で使われます。現時点では、 [Font Awesome](https://fontawesome.com/icons) ライブラリのアイコンを使っており、もし新たなプラグインやテーマを開発するなら、これまで使われていないアイコンを選んでください。そうしないと、私達がかわりに変更しなければならなくなります。 |
|  _screenshot_     | _（オプション）_ スクリーンショットは、 _テーマ_ のときのみ有効化され、 _プラグイン_ では無視されます。テーマの場合、これはテーマに付属するスクリーンショットのファイル名（デフォルト： `screenshot.jpg` ）となります。テーマのルートフォルダに、 `screenshot.jpg` 画像があるとき、このプロパティを省略できます。わたしたちのリポジトリが、自動的にそれをピックアップします。 |
| __author.name*__ | 開発者のフルネーム |
| _author.email_   | _（オプション）_ 開発者のeメール |
| _author.url_     | _（オプション）_ 開発者のホームページ |
| _homepage_       | _（オプション）_ リソース専用のホームページがある場合は、ここに入力してください。 |
| _docs_           | _（オプション）_ リソースのドキュメントがある場合は、ここにリンクを張ってください。 |
| _demo_           | _（オプション）_ リソースのデモサイトがある場合は、ここにリンクを張ってください。 |
| _guide_          | _（オプション）_ リソースのチュートリアルやハウツーガイドがある場合は、ここにリンクを張ってください |
| _keywords_       | _（オプション）_ まだキーワードが使われるところはありませんが、リソースに関係するキーワードをここにカンマ区切りで列挙してください。 |
| _bugs_           | _（オプション）_ バグ報告先の URL です。しばしば、 [GitHub issues](https://guides.github.com/features/issues/) リンクとなります。 |
| _license_        | _（オプション）_ リソースのライセンスのタイプ（MIT, GPL, など）です。リソースには、常に `LICENSE` ファイルを提供することをおすすめします。 |
| _dependencies_   | _（オプション）_ リソースの依存関係のリストです。デフォルトの処理では、 GPM を使ってインストールしますが、GIT リポジトリの URL をオプションで設定すると、リポジトリから直接インストールすることもできます。また、配列を使うときは、 [Composerスタイルのパッケージバージョン](https://getcomposer.org/doc/articles/versions.md) を使用して、名前とバージョンを明示的に定義することができます。 |
| _gpm_            | _（オプション）_ GPM からアップデートするかどうかです。 `false` とすると、non-GPM リソースとして、GPM アップデートできません |

> [!Info]  
> dependencies では、プラグインやテーマの `blueprints.yaml` にある `slug` プロパティで定義された名前を使わなければいけないことに注意してください。

以下は、 [GitHub plugin](https://github.com/getgrav/grav-plugin-github) のブループリントの識別子部分の例です：

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

テーマのブループリントも、プラグインと同じように機能します。

