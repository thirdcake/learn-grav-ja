---
title: "Gravコマンド"
layout: ../../../layouts/Default.astro
---

Grav には、組み込みで `bin/grav` というコマンドラインインターフェース（CLI） があります。繰り返しのタスク実行時に、CLIはとても便利です。たとえば、**キャッシュをクリアする** ときや、**バックアップ** を取るときなどです。

CLIにアクセスするのは、シンプルな作業ですが、**ターミナル** を使う必要があります。MacOS では、`Terminal` と呼ばれ、Windowsでは、`cmd` と呼ばれ、Linuxでは、単にシェルと呼ばれます。Windows の cmd では、UNIX スタイルのコマンドは、そのままでは使えません。Windows マシンに [msysgit](http://msysgit.github.io/) をインストールし、[Git](https://git-scm.com/) と Git BASH という代替のコマンドプロンプトを追加することで、UNIX コマンドが利用可能になります。リモートでサーバーにアクセスするとき、**SSH** を使うでしょう。[SSHの素晴らしいチュートリアル](http://code.tutsplus.com/tutorials/ssh-what-and-how--net-25138) をチェックしてください。

いくつかの処理を、手作業でやるのではなく、CLI に _頼る_ なら、それらの処理は、毎日の _cronjobs_ により自動化できます。

Grav で利用できるすべてのコマンドの一覧は、次のコマンドで表示できます：

```bash
bin/grav list
```

次のように表示されるでしょう：

```txt
Available commands:
  backup       Creates a backup of the Grav instance
  cache        [clearcache|cache-clear] Clears Grav cache
  clean        Handles cleaning chores for Grav distribution
  composer     Updates the composer vendor dependencies needed by Grav.
  help         Displays help for a command
  install      Installs the dependencies needed by Grav. Optionally can create symbolic links
  list         Lists commands
  logviewer    Display the last few entries of Grav log
  new-project  [newproject] Creates a new Grav project with all the dependencies installed
  sandbox      Setup of a base Grav system in your webroot, good for development, playing around or starting fresh
  scheduler    Run the Grav Scheduler.  Best when integrated with system cron
  security     Capable of running various Security checks
```

特定のコマンドのヘルプを見たいときは、コマンドの前に help を入れてください：

```bash
bin/grav help install
```

## Backup

Grav のバックアップシステムは、Grav 1.6 で完全に刷新され、複数のバックアップ・プロファイルに対応しました。これらのプロファイルは、`user/config/backups.yaml` に設定があります。もしこのカスタム設定ファイルが無ければ、`system/config/backups.yaml` にある、デフォルトの設定ファイルを利用します。

Grav が複数のバックアッププロファイルを見つけたとき、CLI コマンドは、どのプロファイルでバックアップするかを選ぶよう、プロンプトで促します。

```bash
cd ~/workspace/portfolio
bin/grav backup

Grav Backup
===========

Choose a backup?
  [0] Default Site Backup
  [1] Pages Backup
```

もしくは、直接プロファイルのインデックスを渡すこともできます：

```bash
$ cd ~/workspace/portfolio
bin/grav backup 1

Archiving 36 files [===================================================] 100% < 1 sec Done...

 [OK] Backup Successfully Created: /users/joe/workspace/portfolio/backup/pages_backup--20190227120510.zip
```

バックアップ機能に関する、より詳しい情報は、 [応用的なこと -> バックアップ](../../08.advanced/07.backups/) をご覧ください。

## Clean

主に、パッケージをビルド処理する最中に使います。無関係なファイルやフォルダを Grav から取り除きます。独自の Grav パッケージをビルド処理するときに使うのでない限り、**このコマンドは使わない** ことを強く推奨します。

```bash
bin/grav clean
```

## Clear-Cache

`cache/` フォルダ下のファイルやフォルダをすべて削除することで、キャッシュをクリアできます。

対応する CLI コマンドは：

```bash
$ cd ~/webroot/my-grav-project
bin/grav cache
```

同じ内容の別名がいくつかあります（`cache`, `cache-clear`, `clearcache`, `clear`）。

デフォルトでは、標準的なキャッシュクリア処理を行いますが、以下のオプションを付けると、より詳しく制御できます：

```txt
--purge           古いキャッシュを削除
--all             すべてを削除します。コンパイルされたもの、twig、doctrine caches も含みます
--assets-only     assets/* のみ削除します
--images-only     images/* のみ削除します
--cache-only      cache/* のみ削除します
--tmp-only        tmp/* のみ削除します
```

## Composer

もし Grav をGitHub からインストールし、コンポーザーベースの vendor パッケージをインストールしていた場合、次のように、簡単にアップデートできます：

```bash
bin/grav composer
```

composer に `install` のようなオプションを渡すこともできます：

```bash
bin/grav composer --install
```

もしくは

```bash
bin/grav composer --update
```

> [!Info]  
> これらはすべて、 composer オプションの `--no-dev` を利用します。よって、パフォーマンステストのためには、composer を直接使った方が良いです： `bin/composer.phar`

## Install

Grav が依存するプログラム（ **error** プラグイン、 **problems** プラグイン、 **antimatter** テーマ）をインストールするには、 **ターミナル** もしくは **コンソール** を立ち上げ、その依存関係をインストールしたい Grav フォルダに移動し、CLI コマンドを実行します。

```bash
$ cd ~/webroot/my-grav-project
bin/grav install
```

依存関係は、以下にインストールされます：
* `~/webroot/my-grav-project/user/plugins/error`
* `~/webroot/my-grav-project/user/plugins/problems`
* `~/webroot/my-grav-project/user/themes/antimatter`

## Log Viewer

Grav 1.6 の途中から、 CLI コマンドの新しいログのビューワが作成され、 Grav のログがすばやく見られるようになりました。

このコマンドを使う最も単純な方法は、次のように入力するだけです：

```bash
cd ~/webroot/my-grav-project
bin/grav logviewer
```

これにより、 `logs/grav.log` ファイルの直近の 20 ログエントリーを表示します。これには、いくつかのオプションがあります：

```txt
-f, --file[=FILE]     custom log file location (default = grav.log)
-l, --lines[=LINES]   number of lines (default = 10)
-v, --verbose         verbose output including a stack trace if available
```

たとえば：

```bash
bin/grav logviewer --lines=4                                                                           [12:27:20]

Log Viewer
==========

viewing last 4 entries in grav.log

2019-02-27 12:00:30 [WARNING] Plugin 'foo-plugin' enabled but not found! Try clearing cache with `bin/grav cache`
2019-02-27 12:04:57 [NOTICE] Backup Created: /Users/joe/my-grav-project/backup/default_site_backup--20190227120450.zip
2019-02-27 12:05:10 [NOTICE] Backup Created: /Users/joe/my-grav-project/backup/pages_backup--20190227120510.zip
2019-02-27 12:26:00 [NOTICE] Backup Created: /Users/joe/my-grav-project/backup/pages_backup--20190227122600.zip
```

そして、スタックトレースを含んだ出力もできます：

```bash
bin/grav logviewer -v                                                                                                       [16:12:12]

Log Viewer
==========

viewing last 20 entries in grav.log

2019-03-14 05:52:44 [WARNING] Plugin 'simplesearch.bak' enabled but not found! Try clearing cache with `bin/grav clear-cache`
2019-03-14 05:52:44 [CRITICAL] A function must be an instance of \Twig_FunctionInterface or \Twig_SimpleFunction.
0 /Users/joe/my-grav-project/plugins/acme-twig-filters/acme-twig-filters.php(52): Twig\Environment->addFunction(Object(Twig\TwigFilter))
1 /Users/joe/my-grav-project/vendor/symfony/event-dispatcher/EventDispatcher.php(212): Grav\Plugin\ACMETwigFiltersPlugin->onTwigInitialized(Object(RocketTheme\Toolbox\Event\Event), 'onTwigInitializ...', Object(RocketTheme\Toolbox\Event\EventDispatcher))
2 /Users/joe/my-grav-project/vendor/symfony/event-dispatcher/EventDispatcher.php(44): Symfony\Component\EventDispatcher\EventDispatcher->doDispatch(Array, 'onTwigInitializ...', Object(RocketTheme\Toolbox\Event\Event))
3 /Users/joe/my-grav-project/vendor/rockettheme/toolbox/Event/src/EventDispatcher.php(23): Symfony\Component\EventDispatcher\EventDispatcher->dispatch('onTwigInitializ...', Object(RocketTheme\Toolbox\Event\Event))
4 /Users/joe/my-grav-project/system/src/Grav/Common/Grav.php(365): RocketTheme\Toolbox\Event\EventDispatcher->dispatch('onTwigInitializ...', Object(RocketTheme\Toolbox\Event\Event))
5 /Users/joe/my-grav-project/system/src/Grav/Common/Twig/Twig.php(175): Grav\Common\Grav->fireEvent('onTwigInitializ...')
6 /Users/joe/my-grav-project/system/src/Grav/Common/Processors/TwigProcessor.php(24): Grav\Common\Twig\Twig->init()
7 /Users/joe/my-grav-project/system/src/Grav/Framework/RequestHandler/Traits/RequestHandlerTrait.php(45): Grav\Common\Processors\TwigProcessor->process(Object(Nyholm\Psr7\ServerRequest), Object(Grav\Framework\RequestHandler\RequestHandler))
8 /Users/joe/my-grav-project/system/src/Grav/Framework/RequestHandler/Traits/RequestHandlerTrait.php(57): Grav\Framework\RequestHandler\RequestHandler->handle(Object(Nyholm\Psr7\ServerRequest))
9 /Users/joe/my-grav-project/system/src/Grav/Common/Processors/AssetsProcessor.php(28): Grav\Framework\RequestHandler\RequestHandler->handle(Object(Nyholm\Psr7\ServerRequest))

2019-03-14 05:52:46 [WARNING] Plugin 'simplesearch.bak' enabled but not found! Try clearing cache with `bin/grav clear-cache`
...
```

## New Project

Grav で新しいプロジェクトを始めるときはいつでも、クリーンな Grav インスタンスで始める必要があります。CLI によって、この処理がとても簡単になり、数秒で終わります。

1. **ターミナル** または **コンソール** を立ち上げ、 _grav_ フォルダに移動します（このドキュメントでは `~/Projects/grav` 下にあるものとします）

```bash
cd ~/Projects/grav
```

2. Grav CLI を実行し、新しいプロジェクトを作成します。プロジェクトを置く場所を指定します（一般的には、あなたのサーバーの [webroot](http://en.wikipedia.org/wiki/Webroot) です）。ここでの例では、 **ポートフォリオ** を作成するとしましょう。 `~/Webroot/portfolio` にそれを置きます。

```bash
bin/grav new-project ~/webroot/portfolio
```

これにより、新しい Grav インスタンスが作成され、必要な依存関係もすべてダウンロードされました。

> [!訳注]  
> Grav 1.7.48 で試してみましたが、Grav は新しくインストールされるものの、テーマやプラグインはダウンロードされず、別途作業が必要になりました。

## Sandbox

Grav には、 `sandbox` という気の利いたツールがあります。 sandbox は、 [シムリンクされた](../01.command-line-intro#symbolic-links) Grav のコピーをすばやく作成します。 `bin/grav sandbox -s DESTINATION` （ "DESTINATION" は、 Grav のコピーを作りたいフォルダのパス）を実行すると、そのフォルダに　Grav のインストールが再作成されます。

たとえば：

```bash
bin/grav sandbox -s ../copy
```

現在の Grav のフォルダから、 `copy` という名前の兄弟フォルダが作成され、そこに仮想のコピーが続きます：  `/bin, /system, /vendor, /webserver-configs` 及び、 Grav のルートフォルダにある典型的な標準ファイルも含まれます。 /user フォルダ内のすべてのコンテンツは、シムリンクのコピーではなく、実コピーされ、そのため、コアファイルのオーバーヘッドの必要なく、新しいインストールのカスタマイズを簡単に始められます。

## Scheduler

[スケジューラー](../../08.advanced/06.scheduler) セクションで解説したように、CLI コマンドでスケジューラをモニターすることができます。

以下の基本コマンドは、期限が来たスケジューラータスクを手動で実行します：

```bash
bin/grav scheduler
```

詳細情報を取得するには、 `-v` オプションを付けて実行することもできます：

```bash
bin/grav scheduler -v

Running Scheduled Jobs
======================

[2019-02-27T12:34:07-07:00] Success: Grav\Common\Cache::purgeJob
[2019-02-27T12:34:07-07:00] Success: Grav\Common\Cache::clearJob
[2019-02-27T12:34:07-07:00] Success: ls -lah
```

他にもオプションはあります：

```txt
-i, --install         Show Install Command
-j, --jobs            Show Jobs Summary
-d, --details         Show Job Details
```

[スケジューラー](../../08.advanced/06.scheduler/) セクションをぜひ参照してください。これらのオプションに関するより詳しい情報が書かれています。

## Security

Grav 1.5 で追加された、セキュリティスキャン機能の CLI コマンドです。 [セキュリティ設定での設定内容](../../01.basics/05.grav-configuration/#security) について、コンテンツのスキャンを実行します。

```bash
bin/grav security                                                                                       [12:34:12]

Grav Security Check
===================

Scanning 11 pages [===================================================] 100% < 1 sec

[OK] Security Scan complete: No issues found...
```

<h4 id="php-cgi-fcgi-imformation">PHP CGI-FCGI 情報</h4>

コマンドラインで あなたのサーバーが `cgi-fcgi` で動いているかどうかを知るには、次の入力をしてください：

```bash
$ php -v
PHP 5.5.17 (cgi-fcgi) (built: Sep 19 2014 09:49:55)
Copyright (c) 1997-2014 The PHP Group
Zend Engine v2.5.0, Copyright (c) 1998-2014 Zend Technologies
    with the ionCube PHP Loader v4.6.1, Copyright (c) 2002-2014, by ionCube Ltd.
```

`(cgi-fcgi)` と表示されていたら、すべての `bin/grav` コマンドの前に `php-cli` が必要です。もしくは、  `alias php="php-cli"` のようなエイリアスをシェルに設定することもできます。これにより、コマンドラインでは **CLI** バージョンの PHP で実行することができます。


