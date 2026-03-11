---
title: 'Microsoft Azure'
layout: ../../../../layouts/Default.astro
lastmod: '2025-07-17'
---

[Microsoft Azure](https://azure.microsoft.com) は、エンタープライズレベルのクラウドコンピューティングプラットフォームで、オープンであり柔軟です。 Azure で Grav をデプロイする方法は複数ありますが、このチュートリアルでは、 Azure の Web App (Paas) を使います。

<h2 id="things-you-ll-need">必要事項</h2>

* Azure のアカウント
* GitHub のアカウント
* Grav のコピー

![Azure Logo](Azure.png)

<h2 id="signing-up-on-azure">Azure に登録</h2>

まず Azure に [アカウントを登録](https://azure.microsoft.com/en-gb/free/) します。最初の 30 日間使える £150 (UK) のクレジットと無料サービスへのアクセス権を得ます。

<h2 id="signing-up-on-github">GitHub に登録</h2>

GitHub アカウントを持っていなければ、 [それにも登録してください](https://github.com/join?source=header-home) 。無料プランで十分です。

<h2 id="clone-grav-source-code">Grav のソースコードを複製</h2>

このチュートリアルを進めるため、 Grav のコピーが必要です。ベースとなる Grav と、管理パネルプラグインファイルのセットをダウンロードし、これらのファイルによる GitHub リポジトリを作っておくことをおすすめします。

これで、 Azure 内で Grav のコピーをデプロイするのに必要なものをすべて手に入れました。

<h2 id="web-config-file">web.config ファイル</h2>

Grav コードに加えて、 web.config ファイルも必要です。web.config ファイルとは、 XML ファイルで、Web アプリのルートフォルダに置かれ、一般的に Web アプリの主要設定や構成を含むファイルのことです。

web.config ファイルの具体例は、 [ここで](https://github.com/getgrav/grav-learn/blob/develop/pages/09.webservers-hosting/03.paas/04.azure/web.config) 手に入ります。この web.config ファイルは、その Web アプリが何をすべきかを網羅し、最新の [Font Awesome パック](https://fontawesome.com) の一部にあるような *.woff* フォーマットや *.woff2* フォーマットのようなファイルフォーマットをしています。

Grav には、そのソースファイルに web.config ファイルの具体例を備えています。 *webserver-configs* フォルダに見つかります。

web.config をセットアップしたら、 Grav の GitHub リポジトリにこれをアップロードします。それはルートディレクトリのレベルである必要があります。

<h2 id="installing-and-running-grav-on-azure">Azure で Grav をインストールし実行</h2>

<h3 id="setting-up-your-web-app">Web アプリをセットアップ</h3>

+ 最初の手順は、 [Azure ポータルにログイン](https://portal.azure.com) し、左側のサイドメニューにある *Create a Resource* をクリックします。

![Step 1](step1.png)

+ *web app* を検索し、サービスを選択します。

![Step 2](step2.png)

+ web アプリサービスの概要を説明する新しいブレードが開きます。ページの下部に *create* ボタンがありますので、これをクリックすると別のブレードが開きます。いくつかの質問が尋ねられます。
    - アプリ名は、あなたのウェブサイトが最初に作成されるときの公開 URL の一部となります
    - サブスクリプションは、web アプリがホスティングされるプランであり、サービスの支払いがここから行われます
    - Azure 内のリソースグループは、サービスを論理的なグループに分ける方法で、グループ名は公開されず、あなただけが見られるものです
    - Azure Web アプリは Windows, Linux もしくは Docker プラットフォームで実行できます。 Grav 向けに Windows を選択します。
    - アプリサービスのプランとロケーションは、web アプリが Azure 内のどのデータセンターに置かれるかと、そのコストを決定します。
    - アプリケーションインサイトは、 Azure のサービスで、 web アプリの問題を監視し、エンドユーザがどのように web アプリとやりとりしているのかを理解するのに役立ちます。

アプリサービスのプランに関する私のおすすめは、テスト目的のために Dev/Test F1 プランを選択することです。このプランにはいくつか制限がありますが、コストの発生がなく Azure に最初の Grav サイトをデプロイできます。ロケーションについては、あなたの場所に近いところを選択します。またこの例では、アプリケーションインサイトのデプロイは避けます。 Grav との統合のためにコードが必要になるからです。

![Step 3](step3.png)

web アプリは、数分でデプロイできます。

<h3 id="install-composer">Composer のインストール</h3>

Composer は、 PHP の依存関係を管理します。 Composer はプロジェクトを基準にプロジェクトに必要な依存関係を管理します。つまり、 Composer は必要なライブラリ、依存関係、そしてアプリケーションをすべてインストールします。 Grav は、 PHP アプリケーションなので、 Grav が適切に実行されるには、 Composer がその web アプリにインストールされていることを確認する必要があります。

これを行うために、以下の手順を踏みます：

    - Web アプリを開く
    - Extensions 設定をクリック
    - Add をクリック
    - Composer を選択
    - OK をクリック

Composer が web アプリにインストールされたら、次にコードをデプロイします。

<h3 id="deploying-your-code">あなたのコードをデプロイ</h3>

web アプリは、起動され実行中です。そしてコードがあります。いよいよデプロイの時間です。これを行うため、 Azure ポータル内の web アプリを開いてください。

+ *Deployment Options* ブレードに移動

![Step 4](step4.png)

+ ソースとして GitHub を選択

+ GitHub アカウントへのクレデンシャルを尋ねられます。それから、 pull するリポジトリとブランチに関する選択肢が現れます。関係する選択肢を選んでください

+ *Azure は GitHub からコードを pull し始め、数分以内にサイトが動きます*

<h2 id="additional-information">追加情報</h2>

<h3 id="custom-domain">カスタムドメイン</h3>

独自の web サイト URL を使いたいなら、 [公式ドキュメント](https://docs.microsoft.com/en-gb/azure/app-service/app-service-web-tutorial-custom-domain) に従ってください。

### Always On

デフォルトでは、すべての Azure web アプリは、一定の時間でアイドル状態になると、読み込まれなくなります。リソースの節約のためです。 Basic もしくは Standard プランを選択していれば、 *Always On* モードを有効化できます。アプリがいつでも読み込まれるようにするモードです。 Always On 設定は、 Web アプリの *Application Settings* ブレード内にあります。

### Quotas

デプロイ用に Free もしくは Shared プランを選択した場合、利用可能なストレージ容量と計算リソースが制限されます。これらの設定を監視するには、 *Quotas* ブレードを監視してください。

