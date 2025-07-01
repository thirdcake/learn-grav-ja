---
title: 'Grav の開発'
layout: ../../../layouts/Default.astro
lastmod: '2025-07-01'
---

Grav を使った開発では、通常の Grav ユーザーに必要なセットアップよりも洗練されたセットアップを使うことで、メリットが得られます。これには、次のようなあらゆるタイプの開発が含まれます：**Grav Core** 、**Grav プラグイン** 、 **Grav スケルトン** そして **Grav テーマ** 。

まず、さまざまな開発のタイプを分類しましょう：

<h2 id="grav-core">Grav コア</h2>

**Grav Core** という時は、特に `system` フォルダ内のものごとを指しています。このフォルダは、 Grav のすべてを制御し、 [Grav のワークフローとライフサイクル](../../04.plugins/05.grav-lifecycle/) において本当に重要な中心部分です。

Grav は、ページを効率的に連携させることに、意図的に集中しています。ページの加工や広範な機能は、しばしばプラグインを作成することが最善です。わたしたちは、コミュニティに対し、バグ修正に貢献することはもとより、Grav のコア内の適切な機能開発を提案することさえも強く推奨しています。

<h2 id="running-tests">テスト実行</h2>

まず、Grav のルートフォルダで、composer install を実行することにより、開発用の依存関係をインストールしてください。

```bash
composer install
```

次に、テストを実行できます：

```bash
composer test
```

これにより、すべてのテストが実行されます。どのサイトでも、正常に実行される、既存のテストです。

また、ひとつのユニットテストを実行することもできます。たとえば：

```bash
composer test tests/unit/Grav/Common/Markdown/ParsedownTest::testAttributeLinks
```

これらのテストを呼び出す他の方法として、次のものがあります：

```bash
./vendor/bin/codecept run
./vendor/bin/codecept run tests/unit/Grav/Common/Markdown/ParsedownTest::testAttributeLinks
```

<h2 id="grav-plugins">Grav プラグイン</h2>

開発における努力のほとんどは、おそらく **Grav プラグイン** に注がれるでしょう。Grav には多くの [イベントフック](../../04.plugins/04.event-hooks/) があるので、プラグインの作成によって特定の拡張機能を提供するのは、とても簡単です。私たちは、この機能の力強さを示すため、すでに多くのプラグインを開発してきています。それらプラグインは、多くの異なるイベントを使って、多様な方法で機能します。

プラグインで機能を提供することには、多くのメリットがありますが、特にいくつかの重要なメリットがあります：

1. Grav コアを最小限のままにできる :  特定のサイトには、必要なプラグインを追加するだけで済みます。たとえば、シンプルなランディングページよりも、ブログには多くのプラグインが必要でしょう。
2. サードパーティによる新機能開発 : Grav が欲しい機能を追加するまで待つ必要はありません。Grav にやってほしいことがあるなら、その機能を拡張するプラグインを作るだけで済みます。

<h4 id="plugin-requirements">プラグインの要件</h4>

適切な Grav プラグインには、次のような理由から、特定のファイルが必要です： 適切な機能のため、Grav のリポジトリの一覧に掲載されるため、そして Grav の管理プラグインに表示されるためです。以下のすべてのファイルが、プラグインに含まれていることを確認してください：

* **yourplugin.php** - プラグインの PHP ファイルで、フォルダと同じ名前です
* **yourplugin.yaml** - プラグインの設定ファイルで、オプションとストリーム継承情報を含みます
* **blueprints.yaml** - プラグインの定義ファイルであり、フォーム定義ファイルです
* **CHANGELOG.md** - 変更ログファイルで、一貫したレンダリングのために適切な Grav フォーマットで書かれます
* **README.md** - プラグインを説明し、プレビューするために必要なファイルです
* **LICENSE** - ライセンスファイルです。Grav コアに沿ったものであれば、おそらく MIT です
* **languages.yaml** （オプション） - 言語定義ファイルです

<h2 id="grav-skeletons">Grav スケルトン</h2>

**Grav スケルトン** とは、事実上、 **オールインワンのサンプルサイト** のことです。各スケルトンには、 **Grav コア** と、必要な **プラグイン** とともに、適切なコンテンツ **ページ** や、**テーマ** が、すべてまとめて含まれています。

Grav は、サイト制作処理が可能な限り簡単になるように設計されました。このことから、サイトに必要なすべてが、 `user` フォルダに含まれることとなりました。現在利用可能な各スケルトンは、 GitHub 上の単純な `user` フォルダです。そのフォルダは、多様な依存関係（必要なプラグインやテーマ）をひとつのパッケージに収めたものであり、 zip 展開するだけで具体例を機能させることができるようにしたものです。

これらのスケルトンは、あなたのサイトを素早く、効率的に育てる基礎となるものです。特定の機能にロックインされることはありません。他の Grav インストールと同様にフレキシブルです。

<h4 id="skeleton-requirements">スケルトンの要件</h4>

適切な Grav スケルトンには、次のような理由から、特定のファイルが必要です： 適切な機能のため、Grav のリポジトリの一覧に掲載されるため、そして Grav の管理プラグインに表示されるためです。以下のすべてのファイルが、スケルトンに含まれていることを確認してください：

* **.dependencies** - このスケルトンのテーマとプラグインの依存関係を定義したファイル
* **blueprints.yaml** - スケルトンの定義ファイルであり、フォーム定義ファイル
* **CHANGELOG.md** - 変更ログファイルで、一貫したレンダリングのために適切な Grav フォーマットで書かれます
* **README.md** - プラグインを説明し、プレビューするために必要なファイルです
* **LICENSE** - ライセンスファイルです。Grav コアに沿ったものであれば、おそらく MIT です
* **screenshot.jpg** - 縦横比 1:1 のテーマのプレビューです。最小でも 800px x 800px 必要です

<h2 id="grav-themes">Grav テーマ</h2>

Grav のページとテーマは密接に連携しているので、 **Grav テーマ** は Grav サイトに不可欠でとても重要な部分です。つまり、各 Grav ページはテーマ内のテンプレートを参照するので、テーマは、ページが利用する適切な **Twig テンプレート** を提供しなければいけません。

Twig テンプレートエンジンは、とてもパワフルなシステムです。 Grav 自体から Twig に対する制約は無いので、いかなるデザインでも自由に制作可能です。これは、コンテンツとデザインの連携がゆるやかな従来の CMS と一線を画する Grav の大きな特徴のひとつです。

<h4 id="theme-requirements">テーマの要件</h4>

適切な Grav テーマには、次のような理由から、特定のファイルが必要です： 適切な機能のため、Grav のリポジトリの一覧に掲載されるため、そして Grav の管理プラグインに表示されるためです。以下のすべてのファイルが、スケルトンに含まれていることを確認してください：

* **yourtheme.php** - テーマの PHP ファイルで、フォルダと同じ名前です
* **yourtheme.yaml** - テーマの設定ファイルで、オプションとストリーム継承情報を含みます
* **blueprints.yaml** - テーマの定義ファイルであり、フォーム定義ファイルです
* **CHANGELOG.md** - 変更ログファイルで、一貫したレンダリングのために適切な Grav フォーマットで書かれます
* **README.md** - テーマを説明し、プレビューするために必要なファイルです
* **LICENSE** - ライセンスファイルです。Grav コアに沿ったものであれば、おそらく MIT です
* **screenshot.jpg** - 縦横比 1:1 のテーマのプレビューです。最小でも 800px x 800px 必要です
* **thumbnail.jpg** - 小さいサムネイル画像で、管理パネルプラグインで利用されます。縦横比 1:1 で、最小でも 300px x 300px 必要です
* **languages.yaml** （オプション） - 言語定義ファイルです

<h2 id="demo-content">コンテンツのデモ</h2>

プラグインやテーマのパッケージの一部として、コンテンツのデモを提供可能です。つまり、 `_demo/` というフォルダ内にあるものはすべて、インストール処理の最中に `user/` フォルダへコピーされます。さらに言うと、 `user/` フォルダ内の **pages** や **configuration** や、その他あらゆるものを提供可能です。利用者は、このことがプロンプト表示されますが、それを選択するかどうかは純粋にオプションです。

_`管理` プラグインからプラグインやテーマをインストールした場合、コンテンツのデモはコピーされないので気をつけてください_

<h2 id="theme-plugin-release-process">テーマ・プラグインのリリース手順</h2>

新しくテーマやプラグインを作成し、それを [Grav のリポジトリ](https://getgrav.org/downloads) に追加したいときは、気をつけなければならない基本事項がいくつかあります：

1. それはオープンソースであり、 [MIT](http://en.wikipedia.org/wiki/MIT_License) 互換ライセンスを提供する `LICENSE` ファイルを含めてください。[具体例はこちら](https://github.com/getgrav/grav-theme-antimatter/blob/develop/LICENSE)
2. `README.md` ファイルを含めてください。このファイルには、機能の概要及びインストール方法や設定方法を書いてください。 [具体例はこちら](https://github.com/getgrav/grav-theme-antimatter/blob/develop/README.md)
3. `blueprints.yaml` ファイルを含めてください。このファイルには、 [必要なフィールドをすべて](../../06.forms/01.blueprints/) 含めてください。 [具体例はこちら](https://github.com/getgrav/grav-theme-antimatter/blob/develop/blueprints.yaml)
4. [正しいフォーマットで](#changelog-format) `CHANGELOG.md` ファイルを含めてください。 [具体例はこちら](https://github.com/getgrav/grav-theme-antimatter/blob/develop/CHANGELOG.md)
5. 他のライブラリやスクリプト、コードを利用する場合、適切な帰属表示を提供してください。
6. 完成したプラグイン・テーマに対して、 [リリースを作成してください](https://help.github.com/articles/creating-releases) 。Grav のリポジトリシステムには、リリースが必要です。上記のすべてを含むリリースが無ければ、 Grav のリポジトリシステムはあなたのプラグイン・テーマを見つけられません。
7. [Grav の issues トラッカーにイシューを追加してください](https://github.com/getgrav/grav/issues/new?title=[add-resource]%20New%20Plugin/Theme&body=I%20would%20like%20to%20add%20my%20new%20plugin/theme%20to%20the%20Grav%20Repository.%0AHere%20are%20the%20project%20details:%20**user/repository**) 。イシューにはあなたのプラグインの説明を書いてください。わたしたちは、その機能を確認する簡単なテストを実施し、それを追加します。すでにリポジトリにあるプラグインやテーマを新しいバージョンにしてリリースした際には、この手順は不要です。その場合は、自動的にピックアップされます。

> [!Note]  
> **各タグの名前が一貫していること** を確認してください。 GPM は、この情報を利用して、あなたのプラグイン・テーマが前のものよりも新しくなったかどうか判断します。 [セマンティックバージョン番号](http://semver.org/) を使うことを推奨します。たとえば： `1.2.4` 。すべてのタグに一貫性があることが重要です！

<h2 id="changelog-format">変更ログのフォーマット</h2>

GetGrav.org サイトでは、カスタムの変更ログフォーマットを利用しています。このフォーマットは、標準的なマークダウン形式で書かれますが、シンプルな CSS で加工可能であり、 [魅力的なフォーマットで表示されます](https://getgrav.org/downloads#changelog) 。変更ログがパースされ、適切にフォーマットできることを確認するため、次の構文を利用してください：

```markdown
# vX.Y.Z
## 01/01/2015

1. [](#new)
    * New features added
    * Another new feature
2. [](#improved)
    * Improvement made
    * Another improvement
3. [](#bugfix)
     * Bugfix implemented
     * Another bugfix

...repeat...
```

各セクションの `#new, #improved, #bugfix` はオプションです。必要なセクションのみ含めてください。

> [!Note]  
> 日付は、 **アメリカ形式の** `m/d/y` [日付フォーマット](../../02.content/02.headers/#date) も、 **ヨーロッパ形式の** `d-m-y` フォーマットも、どちらも利用可能です。また、見出し（バージョンと日付）と、リスト（new, improved, bugfix）の間に、空白行を入れてください。

<h2 id="github-setup">GitHub 設定</h2>

最近の傾向として、 GitHub は、 Grav 開発における最良の友人です。 Grav チームは、できる限り簡単に開発できるようにするためのツールを開発してきましたが、処理をシンプルにするために、参考にした方が良い、いくつかの開発パターンがあります。

コンピュータ上の1つのフォルダ（ `Projects` フォルダや `Development` フォルダ）内に、作業しようとしているすべてのリポジトリをクローンしてください。これにより、提供ツールが、それぞれ必要なリポジトリを探せるようになります。

> [!Info]  
> Grav 開発では、 [GitFlow](http://nvie.com/posts/a-successful-git-branching-model/) ブランチモデルが使われています。 GitFlow メソッドの中心的なアイディアは、開発は `develop` ブランチで行われ、新機能はそれとは別の `feature` ブランチで作成され、機能が完成したときに `develop` ブランチにマージされる、ということです。リリース時に、 `develop` が `master` にマージされ、リリース処理中に必要に応じて `hotfix` ブランチを適用できます。多くのモダンな Git クライアントは、これをサポートしています。特に [Atlassian SourceTree](https://www.atlassian.com/software/sourcetree/overview) をおすすめします。無料で、クロスプラットフォームで、使いやすいからです。

Grav にはまた、依存性がいくつかあります（ `.dependencies` ファイルによって決定されます）。 **Error** プラグインや、 **Problems** プラグインの他、 **Antimatter** テーマなどです。これらをあなたのコンピュータにクローンするには、次の手順に従ってください。

> [!Warning]  
> `getgrav` リポジトリ内にあるものに追加したり、変更したりしたい場合、適切なリポジトリを **フォーク** する必要があります。そして、 `getgrav` リポジトリを直接クローンするのではなく、 **フォークした URL** をクローンしてください。以下の例では、直接 `getgrav` リポジトリを使用していますが、これは例だからです。

```bash
cd
mkdir Projects
cd Projects
mkdir Grav
cd Grav
git clone https://github.com/getgrav/grav.git
git clone https://github.com/getgrav/grav-plugin-error.git
git clone https://github.com/getgrav/grav-plugin-problems.git
git clone https://github.com/getgrav/grav-theme-antimatter.git
```

これにより、 **全部で4つのリポジトリ** が、 `~/Projects/Grav` フォルダにクローンされます。

通常、 Grav のテストサイトを作成する普通の方法は、 `bin/grav new-project` コマンドを実行することです。これは開発においても正しい方法ですが、ひとつ重要な違いがあります。web root から開発したいが、変更はクローンされたコードで行われるので、関係する部分を **シンボリックリンク** する必要があります。これを行うには、 `bin/grav new-project` コマンドに、 `-s` フラグを渡してください。

もうひとつ、必要な手順があります。コマンドに、どこにリポジトリがあるかを伝えなくてはいけません。よって、以下の手順により、 config 設定ファイルを新しい `.grav/` フォルダに作成してください。 `.grav/` フォルダは、 **ホームディレクトリのルート** に作成する必要があります：

```bash
cd
mkdir .grav
vi .grav/config
```

このファイルで、関係ファイルの場所を簡単に示すマップを提供します：

```
github_repos: /Users/your_user/Projects/Grav/
```

このファイルは、確実に読み取りできるように **保存** してください。これにより、 **シンボリックリンクされた** サイトが設定できます。そこでは `~/www` がウェブルートであり、 `~/www/grav` に新しい Grav サイトが作成されます：

```bash
cd ~/Projects/Grav/grav
bin/grav new-project -s ~/www/grav
```

以下のような出力が見られるでしょう：

```txt
rhukster@gibblets:~/Projects/Grav/grav(develop○) » bin/grav new-project -s ~/www/grav

Creating Directories
    /cache
    /logs
    /images
    /assets
    /user/accounts
    /user/config
    /user/pages
    /user/data
    /user/plugins
    /user/themes

Resetting Symbolic Links
    /index.php -> /Users/rhuk/www/grav/index.php
    /composer.json -> /Users/rhuk/www/grav/composer.json
    /bin -> /Users/rhuk/www/grav/bin
    /system -> /Users/rhuk/www/grav/system

Pages Initializing
    /Users/rhuk/Projects/Grav/grav/user/pages -> Created

File Initializing
    /.dependencies -> Created
    /.htaccess -> Created
    /user/config/site.yaml -> Created
    /user/config/system.yaml -> Created

Permissions Initializing
    bin/grav permissions reset to 755

read local config from /Users/rhuk/.grav/config

Symlinking Bits
===============

SUCCESS symlinked grav-plugin-problems -> user/plugins/problems

SUCCESS symlinked grav-plugin-error -> user/plugins/error

SUCCESS symlinked grav-theme-antimatter -> user/themes/antimatter
```

上記の通り、たくさんのデフォルトディレクトリが作成されました。また、初期の `pages` フォルダも作成されました。ベースがセットアップされた後で、その他の依存関係がシンボリックリンクされます。

ブラウザで `http://localhost/grav` を表示すると、たった今作成したテストサイトが表示されます。これで、 `~/www/grav` フォルダ内の変更のすべてが、クローンされたリポジトリにコミットし、プッシュできます。

<h2 id="abandoned-resource-protocol">放棄されたリソースのプロトコル</h2>

ひとびとの移り変わりにより、ユーザー作成のプラグインやテーマなどのコンテンツは放棄されるかもしれません。既存のテーマやプラグインのメンテナンスを引き継ぎたい場合は、以下のプロトコルに従ってください：

1. 整えられ、テストされたプルリクエストを、オリジナルのリポジトリに送ってください。

2. メンテナから、30日間、 **まったく** 反応が無かった場合、もしくは、メンテナがリソースを放棄し、他の誰かに書き込み権限を与えるつもりがないことを表明している場合、次のステップに進みます。

3. 以下の詳細を添えて、 [Grav の GitHub リポジトリに、新しいイシューを送ってください](https://github.com/getgrav/grav/issues/new?title=%5Bchange-resource%5D%20Take%20over%20Plugin%2FTheme&body=I%20would%20like%20to%20take%20over%20an%20existing%20plugin%2Ftheme.%0AHere%20are%20the%20project%20details%3A%20%2A%2Auser%2Frepository%2A%2A) 。
    * タイトル： `[change-resource] Take over plugin/theme`
    * プラグイン名と、オリジナルのリポジトリを提供。
    * 反応の無かったプルリクエストのリンクもしくは、メンテナがリソースを放棄した会話へのリンク。

4. Grav のメンテナは、その事態をレビューし、引き継ぎが承認されるかを知らせます。引き継ぎできる場合、次のステップに進みます。

5. 新しいリリースに向けて、フォークされたリポジトリを準備してください。

6. README ファイルに、このリポジトリは、新しいマスターであることと、古いリポジトリへのリンクを貼ってください。

7. イシューに、メンテナにプラグインの新しい URL が与えられたことを返信してください。

8. メンテナは、 GPM をアップデートします。新しくアップデートされたインストールが、あなたのフォークされたリポジトリから配信されます。

