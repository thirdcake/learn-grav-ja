---
title: "システム要件"
layout: ../../../layouts/Default.astro
---

Gravは、意図的に少ない要件で設計されています。Gravは、あなたのローカルコンピュータや、99%のwebホスティングプロバイダでかんたんに動作します。手元にペンがあれば、以下のシステム要件を書き留めてください。

1. Webサーバ（Apache, Nginx, LiteSpeed, Lightly, IIS など）
2. PHP 7.3.6 以上
3. ええと... これだけです、本当に！（ただし、スムーズに動かすために[PHP要件](#php-requirements)をご覧ください）

Gravは、コンテンツをプレーンなテキストファイルとして構築されます。データベースは不要です。

> [!Info]  
> パフォーマンスを最適化するため、APCuや、Memcached、RedisのようなPHPユーザキャッシュの使用を強く推奨します。しかし心配はいりません。ホスティングサービスにすでにパッケージされていることがほとんどです！

<h2 id="web-servers">Webサーバ</h2>

Gravは、とてもシンプルで汎用的なので、動かすだけならwebサーバさえ不要です。直接、PHP7.3.6以上のビルトイン・サーバで動かせます。

ビルトイン・サーバによる動作テストは、Gravのインストール確認や、手短かな開発作業には便利ですが、本番環境のサイトや、高度な開発作業には **推奨されません** 。[インストールガイド](../03.installation/#running-grav-with-the-built-in-php-webserver)で、方法を説明しています。

技術的には、スタンドアロンのwebサーバが不要とはいえ、ローカル環境であっても、webサーバを稼働させた方が良いです。素晴らしいオプションがたくさんあります：

### Mac

* MacOS 10.14 Mojaveはすでに、Apache WebサーバとPHP 7.1が同梱されています！
* [MAMP/MAMP Pro](https://www.mamp.info/en/mac/) は、ApacheとMySQL、そしてもちろんPHPが入っています。PHPのバージョンを変えたり、仮想ホストを立ち上げたり、動的DNSを自動で制御するというような便利な機能をコントロールできるのは素晴らしい方法です。
* [DDEV](https://ddev.com/) DockerベースのPHP開発環境です
* [AMPPS](https://www.ampps.com/downloads) は、Apacheや、PHP、Perl、Python...などを可能にするSoftaculousのソフトウェアスタックです。この方法は、Grav開発に必要なもの（と、それ以上）を含みます。
* [Brew Apache/PHP](https://getgrav.org/blog/macos-mojave-apache-multiple-php-versions) は、さまざまなPHPバージョンのインストールを完全に設定できる代替手段です。

### Windows

* [Laragon](https://laragon.org/) portable, isolated, fast & powerful universal development environment for PHP, Node.js, and more. It is fast, lightweight, easy-to-use and easy-to-extend.
* [XAMPP](https://www.apachefriends.org/index.html) provides Apache, PHP, and MySQL in one simple package.
* [EasyPHP](https://www.easyphp.org/) provides a personal Web hosting package as well as a more powerful developer version.
* [MAMP for Windows](https://www.mamp.info/en/windows/) is a long-time Mac favorite, but now available for Windows.
* [IIS with PHP](https://php.iis.net/) is a fast way to run PHP on Windows.
* [DDEV](https://ddev.com/) for docker-based PHP development environments.
* [AMPPS](https://www.ampps.com/downloads) is a software stack from Softaculous enabling Apache, PHP, Perl, Python,.. This includes everything you need (and more) for GRAV development.
* [Linux Subsystem](https://medium.freecodecamp.org/setup-a-php-development-environment-on-windows-subsystem-for-linux-wsl-9193ff28ae83) is a great way to Run a linux-like environment on Windows

### Linux

* Linuxの多くのディストリビューションには、すでにApacheとPHPが組み込まれています。そうでない場合、ディストリビューションは通常パッケージマネージャを提供しており、それを使ってそれほど大変でなくインストールすることができます。より高度な設定は、優れた検索エンジンの助けを借りて調べてください。

<h3 id="apache-requirements">Apacheの要件</h3>

Apacheのほとんどのディストリビューションには必要なものがすべて入っていますが、完全なものにするために、ここに必要なApacheモジュールのリストを示します：

* `mod_rewrite`
* `mod_ssl` (もしGravをSSL下で動かしたい場合)
* `mod_mpm_itk_module` (if you wish to run Grav under its own user account)

また、 `.htaccess` ファイルが正しく処理され、rewriteルールが有効になるように、 `<Directory>` や `<VirtualHost>` ブロックで `AllowOverride All` が設定されていることを確認してください。

<h3 id="iis-requirements">IISの要件</h3>

Although IIS is considered a web server ready to run 'out-of-the-box', some changes need to be made.

To get **Grav** running on an IIS server, you need to install **URL Rewrite**. This can be accomplished using **Microsoft Web Platform Installer** from within IIS. You can also install URL Rewrite by going to [iis.net](https://www.iis.net/downloads/microsoft/url-rewrite).

<h2 id="php-requirements">PHPの要件</h2>

ほとんどのホスティングプロバイダや、ローカルのLAMPでさえ、Gravを 'すぐに' 動かすために必要なすべてが事前に設定されています。とはいえ、一部のWindowsや、Linuxディストリビューションのローカル環境やVPS（Debianを想定！）では、PHPのコンパイルが最小限の状態となっていることがあります。よって、もしかすると以下のPHPモジュールをインストールし、有効化する必要があるかもしれません：

* `curl` (GPM で使用される URL 処理用のクライアント)
* `ctype` (symfony/yaml/Inline で使用)
* `dom` (grav/admin のニュースフィードで使用)
* `gd` (画像操作に使用されるグラフィック・ライブラリ)
* `json` (Symfony/Composer/GPM で使用)
* `mbstring` (マルチバイト文字列のサポート)
* `openssl` (secure sockets library used by GPM)
* `session` (used by toolbox)
* `simplexml` (grav/admin のニュースフィードで使用)
* `xml` (XML サポート)
* `zip` extension support (GPM で使用)

`openssl` と、 (un)zipサポートを有効化するには、Linuxディストリビューションの `php.ini` ファイルの以下のような行を見つける必要があります。

```bash
;extension=openssl.so
;extension=zip.so
```

行頭のセミコロンを削除してください。

<h5 id="optional-modules">追加するとより良いモジュール</h5>

* `apcu` for increased cache performance
* `opcache` for increased PHP performance
* `yaml` PECL Yaml provides native yaml processing and can dramatically increase performance
* `xdebug` useful for debugging in a development environment

<h3 id="permissions">パーミッション</h3>
https://github.com/getgrav/grav-learn/blob/develop/pages/07.cli-console/04.grav-cli-gpm/docs.md
Gravが正しく機能するためには、Webサーバにログやキャッシュを書き込むための適切な **ファイル・パーミッション** が必要です。[CLI](../../07.cli-console/02.grav-cli) （コマンドライン・インターフェイス）や[GPM](../../07.cli-console/04.grav-cli-gpm)（Gravのパッケージマネージャ）を利用する場合、そのユーザがコマンドラインから実行するPHPもまた、ファイルの変更に適切なパーミッションが必要になります。

デフォルトでは、Gravは、ファイルとフォルダをそれぞれ `644` と `755` のパーミッションとしてインストールします。ほとんどのホスティングプロバイダは、利用者のユーザアカウントの範囲で、ファイルを作成したり修正したりできるように設定しています。このことにより、Gravは、大半のホスティングプロバイダ上で **すぐに** 動きます。

しかしながら、専用サーバやローカル環境で動かすときは、 **ユーザ** と **webサーバ** がファイルを修正できるように、パーミッションを調整する必要があるかもしれません。そのための方法がいくつかあります。

1. 利用環境が **ローカル開発環境** の場合、通常は、ユーザ自身のプロファイルのもとwebサーバを実行するよう設定できます。この設定方法により、webサーバはいつでもファイルの作成や修正を許可します。

2. すべてのファイルとフォルダの **グループ・パーミッション** を変えることで、標準的なパーミッションを維持したまま、webサーバのグループは、書き込み権限を持ちます。この方法は、少しのコマンドの実行が必要です。

まず、どのユーザがApacheを実行しているのかを、以下のコマンドで探します：

```bash
ps aux | grep -v root | grep apache | cut -d\  -f1 | sort | uniq
```

そして、このユーザがどこのグループに属しているか、次のコマンドを実行して調べてください。（注：USERNAMEは、前のコマンドで見つけたapacheのユーザ名に書き換えてください）

```bash
groups USERNAME
```

（注： GROUPは、前のコマンドで見つけたapacheを実行するグループ名に書き換えて下さい。 [`www-data`, `apache`, `nobody` など]）：

```bash
chgrp -R GROUP .
find . -type f | xargs chmod 664
find . /bin -type f | xargs chmod 775
find . -type d | xargs chmod 775
find . -type d | xargs chmod +s
umask 0002
```

If you need to invoke superuser permissions, you would run `find … | sudo xargs chmod …` instead.

<h2 id="recommended-tools">おすすめツール</h2>

### PhpStorm

Grav is developed using [PhpStorm](https://www.jetbrains.com/phpstorm/), which makes it the best IDE for Grav. However, it does not come for free.

PhpStorm is best suited for PHP developers, including people who write complicated Grav plugins. It offers automated code compilation for Grav (you just need to add Grav and any plugin you use into includes), and many other tools to aid with the code development. It has also good support for formatting twig, yaml, html, js, scss and tailwind.

<h3 id="text-editors">テキスト・エディタ</h3>

メモ帳、Textedit、Viなど、お使いのプラットフォームに付属しているデフォルトのテキストエディタでも問題ありませんが、作業効率を上げるために、シンタックス・ハイライト機能を備えた優れたテキスト・エディタを使用することをおすすめします。おすすめの選択肢は以下の通りです：

1. [Visual Studio Code](https://code.visualstudio.com/) - Atomと同様に、Electronと、Node、HTML/CSSによって作られています。非常に軽量で、PHPとJavaScriptをとてもよくサポートしてくれる非常に多くのプラグインがあります。現在、Gravの開発に推奨されているエディタです。
2. [SublimeText](https://www.sublimetext.com/) - MacOS/Windows/Linux - A commercial developer's editor, but well worth the price. Very powerful especially combined with plugins such as [Markdown Extended](https://sublime.wbond.net/packages/Markdown%20Extended), [Pretty YAML](https://sublime.wbond.net/packages/Pretty%20YAML), and [PHP-Twig](https://sublime.wbond.net/packages/PHP-Twig).
3. [Notepad++](https://notepad-plus-plus.org/) - Windows - A free and very popular developer's editor for Windows.
4. [Bluefish](https://bluefish.openoffice.nl/index.html) - MacOS/Windows/Linux - A free, open source text editor geared towards programmers and web developers.
5. [Kate](https://kate-editor.org/about-kate/) - MacOS/Windows/Linux - A light yet powerfull and versatile opensource text editor and programming tool, supporting highlighting for over 300 languages (including Markdown).

<h3 id="markdown-editors">マークダウン・エディタ</h3>

コンテンツ制作のみを主な作業とするのであれば、専用の **マークダウン・エディタ** を使うという選択肢もあります。これらは、しばしばコンテンツづくりに特化しており、コンテンツをHTMLとしてレンダリングする **ライブプレビュー** 機能を提供します。これらのエディタは、文字通り何百種類もありますが、良い選択肢は次の通りです：

1. [MacDown](https://macdown.uranusjr.com/) - MacOS - Free, a simple, lightweight open source Markdown editor.
2. [LightPaper](https://getlightpaper.com/) - MacOS - $17.99, clean, powerful. Our markdown editor of choice on the Mac. **Get 25% OFF with Discount Code: GET_GRAV_25**
3. [MarkDrop](https://culturezoo.com/markdrop/) - MacOS - $5, but super clean and Droplr support built-in.
4. [MarkdownPad](https://markdownpad.com/) - Windows - Free and Pro versions. Even has YAML front-matter support. An excellent solution for Windows users.
5. [Mark Text](https://github.com/marktext/marktext) - Free, open source Markdown editor for Windows / Linux / MacOS.

<h3 id="ftp-clients">FTPクライアント</h3>

**Grav** をデプロイする方法はいくつもありますが、基本的には、ローカル環境のサイトをホスティングプロバイダにコピーするだけです。その最も基本的な方法は、[FTPクライアント](https://ja.wikipedia.org/wiki/FTP%E3%82%AF%E3%83%A9%E3%82%A4%E3%82%A2%E3%83%B3%E3%83%88) を使用することです。たくさんの種類がありますが、おすすめは次のとおりです：


1. [Transmit](https://panic.com/transmit/) - MacOS - The de facto FTP/SFTP client on MacOS. Easy to use, fast, folder-syncing and pretty much anything else you could ask for.
2. [FileZilla](https://filezilla-project.org/) - MacOS/Windows/Linux - Probably the best option for Windows and Linux users. Free and very powerful (but very ugly on the Mac!).
3. [Cyberduck](https://cyberduck.io/) - MacOS/Windows - A decent free option for both MacOS and Windows users. Not as full-featured as the others.
4. [ForkLift](https://www.binarynights.com/forklift/) - MacOS - A solid alternative to Transmit, and slightly cheaper to boot.

### Git

開発環境とサーバ環境で、[Git](https://git-scm.com/) 分散バージョン管理システムを利用している場合、[Github](https://github.com)や、[GitLab](https://about.gitlab.com/)のようなホスティングされたGitを使って、シンプルなワークフローを構築できます。構築の手間は多少増えますが、よりクリーンで堅牢なワークフローが提供され、バックアップも行ってくれます。もしGitとそのクライアントツールに慣れている場合は、この方法をお試しください。


> [!Tip]  
> ワークフローにおけるGitの使い方は、のちほど、[Webサーバとホスティング](../../09.webservers-hosting)の章の[Gitによるデプロイ](../../09.webservers-hosting/05.deploying-with-git)にて詳しく説明します。
