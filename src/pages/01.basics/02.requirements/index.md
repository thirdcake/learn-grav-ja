---
title: "システム要件"
layout: ../../../layouts/Default.astro
---

Grav は、意図的に少ない要件で設計されています。Grav は、あなたのローカルコンピュータや、99% の web ホスティングプロバイダでかんたんに動作します。手元にペンがあれば、以下のシステム要件を書き留めてください。

1. Web サーバ（Apache, Nginx, LiteSpeed, Lightly, IIS など）
2. PHP 7.3.6 以上
3. ええと... これだけです、本当に！ （ただし、スムーズに動かすために [PHP要件](#php-requirements) をご覧ください）

Grav は、コンテンツをプレーンなテキストファイルとして構築されます。データベースは不要です。

> [!Info]  
> パフォーマンスを最適化するため、 APCu や、 Memcached 、 Redis のような PHP ユーザキャッシュの使用を強く推奨します。しかし心配はいりません。ホスティングサービスにすでにパッケージされていることがほとんどです！

<h2 id="web-servers">Web サーバ</h2>

Grav は、とてもシンプルで汎用的なので、動かすだけなら web サーバーさえ不要です。直接、 PHP 7.3.6 以上のビルトイン・サーバで動かせます。

ビルトイン・サーバによる動作テストは、 Grav のインストール確認や、手短かな開発作業には便利ですが、本番環境のサイトや、高度な開発作業には **推奨されません** 。 [インストールガイド](../03.installation/#running-grav-with-the-built-in-php-webserver) で、方法を説明しています。

技術的には、スタンドアロンのwebサーバが不要とはいえ、ローカル環境であっても、webサーバを稼働させた方が良いです。素晴らしいオプションがたくさんあります：

### Mac

* MacOS 10.14 Mojave はすでに、Apache Web サーバと PHP 7.1 が同梱されています！
* [MAMP/MAMP Pro](https://www.mamp.info/en/mac/) は、 Apache と MySQL 、そしてもちろん PHP が入っています。PHP のバージョンを変えたり、仮想ホストを立ち上げたり、動的 DNS を自動で制御するというような便利な機能をコントロールできるのは素晴らしい方法です。
* [DDEV](https://ddev.com/) Docker ベースの PHP 開発環境です
* [AMPPS](https://www.ampps.com/downloads) は、 Apache や、 PHP 、 Perl 、 Python ... などを可能にする Softaculous のソフトウェアスタックです。この方法は、 Grav 開発に必要なもの（と、それ以上）を含みます。
* [Brew Apache/PHP](https://getgrav.org/blog/macos-mojave-apache-multiple-php-versions) は、さまざまな PHP バージョンのインストールを完全に設定できる代替手段です。

### Windows

* [Laragon](https://laragon.org/) ポータブルで、独立しており、処理が速く、パワフルな開発環境で、 PHP, Node.js, その他が動きます。処理が速く、軽く、簡単に使えて、拡張も容易です。
* [XAMPP](https://www.apachefriends.org/index.html) ひとつのシンプルなパッケージで、 Apache と、 PHP 、 MySQL が提供されます。
* [EasyPHP](https://www.easyphp.org/) よりパワフルな開発者バージョンとともに、パーソナルな web ホスティングパッケージを提供します。
* [MAMP for Windows](https://www.mamp.info/en/windows/) 長い間 Mac 専用でしたが、今では Windows でも利用可能です。
* [IIS with PHP](https://php.iis.net/) Windows 上で PHP を速く動かせます。
* [DDEV](https://ddev.com/) Docker ベースの PHP 開発環境です。
* [AMPPS](https://www.ampps.com/downloads) Softaculous による Apache, PHP, Perl, Python, などのソフトウェアスタックです。Grav 開発に必要なものはすべて入っています。
* [Linux Subsystem](https://medium.freecodecamp.org/setup-a-php-development-environment-on-windows-subsystem-for-linux-wsl-9193ff28ae83) Windows 上で、 Linux ライクな環境を実行できる素晴らしい方法です。

### Linux

* Linux の多くのディストリビューションには、すでに Apache と PHP が組み込まれています。そうでない場合、ディストリビューションは通常パッケージマネージャを提供しており、それを使ってそれほど大変でなくインストールすることができます。より高度な設定は、優れた検索エンジンの助けを借りて調べてください。

<h3 id="apache-requirements">Apache の要件</h3>

Apache のほとんどのディストリビューションには必要なものがすべて入っていますが、完全なものにするために、ここに必要な Apache モジュールのリストを示します：

* `mod_rewrite`
* `mod_ssl` （もし Grav を SSL 下で動かしたい場合）
* `mod_mpm_itk_module` （もし Grav を自身のユーザーアカウント下で動かしたい場合）

また、 `.htaccess` ファイルが正しく処理され、 rewrite ルールが有効になるように、 `<Directory>` や `<VirtualHost>` ブロックで `AllowOverride All` が設定されていることを確認してください。

<h3 id="iis-requirements">IIS の要件</h3>

IIS は、 'すぐ使える' web サーバーだと思われていますが、おそらくいくつかの設定が必要です。

IIS サーバーで **Grav** を動かすには、 **URL Rewrite** をインストールしてください。これは、 IIS 内の **Microsoft Web Platform Installer** を使って利用できます。もしくは、 [iis.net](https://www.iis.net/downloads/microsoft/url-rewrite) からも URL Rewrite はインストール可能です。

<h3 id="php-requirements">PHP の要件</h3>

ほとんどのホスティングプロバイダや、ローカルの LAMP でさえ、 Grav を 'すぐに' 動かすために必要なすべてが事前に設定されています。とはいえ、一部の Windows や、 Linux ディストリビューションのローカル環境や VPS （ Debian を想定！）では、 PHP のコンパイルが最小限の状態となっていることがあります。よって、もしかすると以下の PHP モジュールをインストールし、有効化する必要があるかもしれません：

* `curl` （ GPM で使用される URL 処理用のクライアント）
* `ctype` （ symfony/yaml/Inline で使用）
* `dom` （ grav/admin のニュースフィードで使用）
* `gd` （画像操作に使用されるグラフィック・ライブラリ）
* `json` （ Symfony/Composer/GPM で使用）
* `mbstring` （マルチバイト文字列のサポート）
* `openssl` （GPM で使うセキュアなソケットライブラリ）
* `session` （ toolbox で利用）
* `simplexml` （ grav/admin のニュースフィードで使用）
* `xml` （XML サポート）
* `zip` zip 展開のサポート （GPM で使用）

`openssl` と、 (un)zip サポートを有効化するには、 Linux ディストリビューションの `php.ini` ファイルの以下のような行を見つける必要があります。

```bash
;extension=openssl.so
;extension=zip.so
```

行頭のセミコロンを削除してください。

<h5 id="optional-modules">追加するとより良いモジュール</h5>

* `apcu` キャッシュパフォーマンスを向上させるため
* `opcache` PHP パフォーマンスを向上させるため
* `yaml` PECL Yaml により、ネイティブの yaml 処理ができ、パフォーマンスがドラマチックに良くなります
* `xdebug` 開発環境でのデバッグに便利です

<h3 id="permissions">パーミッション</h3>

Gravが正しく機能するためには、Webサーバにログやキャッシュを書き込むための適切な **ファイル・パーミッション** が必要です。 [CLI](../../07.cli-console/02.grav-cli) （コマンドライン・インターフェース）や [GPM](../../07.cli-console/04.grav-cli-gpm) （Gravのパッケージマネージャ）を利用する場合、そのユーザがコマンドラインから実行する PHP もまた、ファイルの変更に適切なパーミッションが必要になります。

デフォルトでは、 Grav は、ファイルとフォルダをそれぞれ `644` と `755` のパーミッションとしてインストールします。ほとんどのホスティングプロバイダは、利用者のユーザアカウントの範囲で、ファイルを作成したり修正したりできるように設定しています。このことにより、 Grav は、大半のホスティングプロバイダ上で **すぐに** 動きます。

しかしながら、専用サーバやローカル環境で動かすときは、 **ユーザ** と **web サーバー** がファイルを修正できるように、パーミッションを調整する必要があるかもしれません。そのための方法がいくつかあります。

1. 利用環境が **ローカル開発環境** の場合、通常は、ユーザ自身のプロファイルのもと web サーバを実行するよう設定できます。この設定方法により、 web サーバーはいつでもファイルの作成や修正を許可します。

2. すべてのファイルとフォルダの **グループ・パーミッション** を変えることで、標準的なパーミッションを維持したまま、 web サーバーのグループは、書き込み権限を持ちます。この方法は、少しのコマンドの実行が必要です。

まず、どのユーザが Apache を実行しているのかを、以下のコマンドで探します：

```bash
ps aux | grep -v root | grep apache | cut -d\  -f1 | sort | uniq
```

そして、このユーザがどこのグループに属しているか、次のコマンドを実行して調べてください。（注： USERNAME は、前のコマンドで見つけた apache のユーザ名に書き換えてください）

```bash
groups USERNAME
```

（注： GROUP は、前のコマンドで見つけた apache を実行するグループ名に書き換えて下さい。 [`www-data`, `apache`, `nobody` など]）：

```bash
chgrp -R GROUP .
find . -type f | xargs chmod 664
find . /bin -type f | xargs chmod 775
find . -type d | xargs chmod 775
find . -type d | xargs chmod +s
umask 0002
```

スーパーユーザーのパーミッションで実行するなら、かわりに次のように実行してください： `find … | sudo xargs chmod …`

<h2 id="recommended-tools">おすすめツール</h2>

### PhpStorm

Grav は、 [PhpStorm](https://www.jetbrains.com/phpstorm/) を使って開発されています。PhpStorm は、 Grav にとって最高の IDE です。ただし、無料ではありません。

PhpStorm は、複雑な Grav プラグインを書く人を含む PHP 開発者に最適です。（ Grav と利用するすべてのプラグインを includes に付け加えるだけで、）自動で Grav のためにコードをコンパイルしてくれて、コード開発を支援する他の多くのツールを提供してくれます。また、 twig, yaml, html, js, scss, そして tailwind のフォーマットにも対応しています。

<h3 id="text-editors">テキスト・エディタ</h3>

メモ帳、aTextedit 、 Vi など、お使いのプラットフォームに付属しているデフォルトのテキストエディタでも問題ありませんが、作業効率を上げるために、シンタックス・ハイライト機能を備えた優れたテキスト・エディタを使用することをおすすめします。おすすめの選択肢は以下の通りです：

1. [Visual Studio Code](https://code.visualstudio.com/) - Atomと同様に、Electronと、Node、HTML/CSSによって作られています。非常に軽量で、PHPとJavaScriptをとてもよくサポートしてくれる非常に多くのプラグインがあります。現在、Gravの開発に推奨されているエディタです。
2. [SublimeText](https://www.sublimetext.com/) - MacOS/Windows/Linux - 商用的な開発者向けのエディタですが、値段通りの価値があります。特にプラグイン（ [Markdown Extended](https://sublime.wbond.net/packages/Markdown%20Extended), [Pretty YAML](https://sublime.wbond.net/packages/Pretty%20YAML), そして [PHP-Twig](https://sublime.wbond.net/packages/PHP-Twig) ）と組み合わせると強力です。
3. [Notepad++](https://notepad-plus-plus.org/) - Windows - 無料でとても人気の Windows 向け開発者用エディタです。
4. [Bluefish](https://bluefish.openoffice.nl/index.html) - MacOS/Windows/Linux - 無料で、オープンソースのプラグラマと web 開発者向けテキストエディタです。
5. [Kate](https://kate-editor.org/about-kate/) - MacOS/Windows/Linux - 軽量だが強力で多機能なオープンソースのテキストエディタであり、プログラミングツールです。（ Markcdown を含む） 300 以上の言語のハイライトをサポートします。

<h3 id="markdown-editors">マークダウン・エディタ</h3>

コンテンツ制作のみを主な作業とするのであれば、専用の **マークダウン・エディタ** を使うという選択肢もあります。これらは、しばしばコンテンツづくりに特化しており、コンテンツを HTML としてレンダリングする **ライブプレビュー** 機能を提供します。これらのエディタは、文字通り何百種類もありますが、良い選択肢は次の通りです：

1. [MacDown](https://macdown.uranusjr.com/) - MacOS - 無料で、シンプルで、軽量なオープンソースの Markdown エディタ。
2. [LightPaper](https://getlightpaper.com/) - MacOS - $17.99 で、クリーンで、強力です。わたしたちが Mac 上で選択した markdown エディタです。 **ディスカウントコード（ GET_GRAV_25 ）で 25 % オフになります**
3. [MarkDrop](https://culturezoo.com/markdrop/) - MacOS - $5 ですが、クリーンで Droplr サポートが組み込まれています。
4. [MarkdownPad](https://markdownpad.com/) - Windows - 無料で Pro バージョン。YAML フロントマターサポートまでも付いています。Windows ユーザーにとって素晴らしいソリューションです。
5. [Mark Text](https://github.com/marktext/marktext) - 無料で、オープンソースの Markdown エディタです。 Windows / Linux / MacOS 対応。

<h3 id="ftp-clients">FTPクライアント</h3>

**Grav** をデプロイする方法はいくつもありますが、基本的には、ローカル環境のサイトをホスティングプロバイダにコピーするだけです。その最も基本的な方法は、[FTPクライアント](https://ja.wikipedia.org/wiki/FTP%E3%82%AF%E3%83%A9%E3%82%A4%E3%82%A2%E3%83%B3%E3%83%88) を使用することです。たくさんの種類がありますが、おすすめは次のとおりです：


1. [Transmit](https://panic.com/transmit/) - MacOS - MacOS でデファクトの FTP/SFTP クライアントです。簡単に使えて、処理が速くて、フォルダ同期してくれて、必要なことすべてに素晴らしく適合します。
2. [FileZilla](https://filezilla-project.org/) - MacOS/Windows/Linux - おそらく、 Windows ユーザーと Linux ユーザーには最適な選択肢です。無料でとてもパワフルです（が、 Mac 上では見にくいです！）
3. [Cyberduck](https://cyberduck.io/) - MacOS/Windows - MacOS ユーザーと Windows ユーザー両方に適した無料の選択肢。他のものほど機能は充実していません。
4. [ForkLift](https://www.binarynights.com/forklift/) - MacOS - Transmit の堅実な代替品で、しかも若干安いです。

### Git

開発環境とサーバ環境で、 [Git](https://git-scm.com/) 分散バージョン管理システムを利用している場合、 [Github](https://github.com) や、 [GitLab](https://about.gitlab.com/) のようなホスティングされた Git を使って、シンプルなワークフローを構築できます。構築の手間は多少増えますが、よりクリーンで堅牢なワークフローが提供され、バックアップも行ってくれます。もし Git とそのクライアントツールに慣れている場合は、この方法をお試しください。

> [!Tip]  
> ワークフローにおけるGitの使い方は、のちほど、 [Webサーバとホスティング](../../09.webservers-hosting/) の章の [Gitによるデプロイ](../../09.webservers-hosting/05.deploying-with-git/) にて詳しく説明します。

