---
title: Scriptによるアップデート
layout: ../../../layouts/Default.astro
lastmod: '2025-05-26'
---
もしくは： 複数の Grav を一度にアップグレードします

このページは、[Deployer](https://deployer.org/) を使って、複数の Grav を簡単にアップグレードするためのガイドです。ここで「複数」という言葉は、別々の Grav がインストールされている状況を意味し、[マルチサイトがインストールされていること](../../08.advanced/05.multisite-setup/) ではありません。 [Grav のCLI](../02.grav-cli/) を実行するため、各インストールの path を使いますが、それらの path を手打ちするようなことはしません。

Deployer のようなタスクランナーのメリットは、タスクの実行中、実行内容を正確に知らせてくれることです。コマンドが実行されているサーバーからの出力も表示されます。たとえば、次のような出力が Deployer からされます：

```txt
Executing task packages

GPM Releases Configuration: Stable

Found 8 packages installed of which 1 need updating

01. Email           [v2.5.2 -> v2.5.3]

GPM Releases Configuration: Stable

Preparing to install Email [v2.5.3]
  |- Downloading package...   100%
  |- Checking destination...  ok
  |- Installing package...    ok
  '- Success!

Clearing cache

Cleared:  /home/username/public_html/deployer/grav/cache/twig/*
Cleared:  /home/username/public_html/deployer/grav/cache/doctrine/*
Cleared:  /home/username/public_html/deployer/grav/cache/compiled/*

Touched: /home/username/public_html/deployer/grav/user/config/system.yaml
```

これくらい簡単に、 Deployer が Grav にすべてのパッケージをアップグレードするよう指示し、それにより Email プラグインが新しいバージョンにアップグレードされました。

<h2 id="prerequisites">前提条件</h2>

Grav と同様、 PHP **v7.3.6** 以上が必要です。この要件は、コマンドライン（CLI）にも適用されます。よって、複数のバージョンの PHP をインストールしている場合、正しいバージョンを使用してください。デフォルトのバージョンをチェックするには、 `php -v` が使えます。私のは **PHP 7.2.34** でした。

共有サーバー環境では、 CLI でどのコマンドを使えばよいか、サーバー会社に確認してください。わたしのケースでは、 `php74` というコマンドで、 `-v` で実行すると **PHP 7.4.12** が帰ってきました。これはつまり、すべての path に次のように php74 を付ける必要があることを意味します： `php74 vendor/bin/dep list` 。

いくつかのサーバー会社では、 CLI のデフォルトの PHP バージョンを選べるようです。どのようにするかは、サーバー会社に問い合わせてください。

<h2 id="setup">セットアップ</h2>

あなたのサーバーの公開用ルートディレクトリ（ **public_html/** や、 **www/** ）に、 `deployer` という名前のフォルダを作り、そこに入ってください。このフォルダをベースにプロジェクトを進めていきます。このディレクトリはパスワード保護する必要があります（手動の方法については [DigitalOcean Guide](https://www.digitalocean.com/community/tutorials/how-to-set-up-password-authentication-with-apache-on-ubuntu-14-04) をご覧ください。もし利用可能であれば [CPanel](https://www.siteground.com/tutorials/cpanel/pass_protected_directories.htm) を使ってください）。

機能している Grav をインストールしておく必要があります。また、[Composer](https://getcomposer.org/) も必要です。サーバー会社によっては、 Composer がすでにインストールされていることもあります。その場合、 `composer -v` によりインストールされているかチェック可能です。インストールされていなければ、 SSH を使って（ルートディレクトリから）次のようにインストールできます：

```bash
export COMPOSERDIR=~/bin;mkdir bin
curl -sS https://getcomposer.org/installer | php -- --install-dir=$COMPOSERDIR --filename=composer
```

もしくは、[ローカルへのインストール](https://getcomposer.org/download/) を望む場合、`public_html/deployer` フォルダで、以下のようなコマンドを実行してください：

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

ローカルへのインストールでは、ただの `composer` ではなく、 `composer.phar` で実行します。それでは、 `public_html/deployer` フォルダにいる状態で、次のコマンドを実行して [Deployer](https://deployer.org/docs/installation) をインストールします：

```bash
composer require deployer/deployer
```

次に、同じフォルダのままで、 `deploy.php` ファイルを作成してください。このファイルを利用して Deployer のそれぞれのタスクを実行します。そのファイルに、以下のコードをコピーアンドペーストしてください：

```php
<?php
namespace Deployer;
require 'vendor/autoload.php';

// Configuration
set('default_stage', 'production');
set(php, 'php56');

// Servers
localServer('site1')
	->stage('production')
	->set('deploy_path', '/home/username/public_html/deployer/grav');

desc('Backup Grav installations');
task('backup', function () {
	$backup = run('{{php}} bin/grav backup');
	writeln($backup);
});
desc('Upgrade Grav Core');
task('core', function () {
	$self_upgrade = run('{{php}} bin/gpm self-upgrade -y');
	writeln($self_upgrade);
});
desc('Upgrade Grav Packages');
task('packages', function () {
	$upgrade = run('{{php}} bin/gpm update -y');
	writeln($upgrade);
});
?>
```

<h2 id="configuration">設定</h2>

Deployer には、明示的にステージング環境が必要なので、 `production` に設定します。さらに、特定の PHP バージョンを利用可能にするため、デフォルトで実行可能な PHP を設定します。これは、実行可能な名前とできるほか、特定の PHP のバージョンを指定することもできます。今回の設定では、次のようにしています：

```php
// Configuration
set('default_stage', 'production');
set(php, 'php56');
```

デフォルトの PHP CLI バージョンが **5.6** 以上なら、これを `set(php, 'php');` に変更します。

<h3 id="servers">サーバー</h3>

必要な分だけ、いくつでもサーバー/サイトのセットアップができます。スクリプトは、それぞれ順番に実行されていきます。ローカルインストールでも可能ですし、外部サーバーでも可能ですが、これはローカルセットアップなので、ここでは `localServer` を使います（より詳しい設定は、 [Deployer/servers](https://deployer.org/docs/servers) をご覧ください）。以下が、複数サイトの場合の具体例です：

```php
// Servers
localServer('site1')
	->stage('production')
	->set('deploy_path', '/home/username/public_html/deployer/grav1');
localServer('site2')
	->stage('production')
	->set('deploy_path', '/home/username/public_html/deployer/grav2');
localServer('site3')
	->stage('production')
	->set('deploy_path', 'C:\caddy\grav1');
localServer('site4')
	->stage('production')
	->set('deploy_path', 'C:\caddy\grav2');
```

ここでは、インストール先の絶対パスを使用していることに注目してください。SSH での接続先でパスがどのように解釈されるかは、場合によりけりです。これはつまり、フルパスなら Deployer が正しく解釈してくれるので、サーバー上ではフルパスを利用するのが良いですが、 HOMEDIR が設定されていれば、チルダを使って次のように表現することもできます： `~/public_html/deployer/grav1`

<h3 id="tasks">タスク</h3>

3つのタスクが現在設定されています： `backup`, `core`, そして `packages` です。 `php vendor/bin/dep backup` を実行すると、それぞれのサイトのバックアップが作成されます。それらは、 **deploy_path/backup/BACKUP.zip** で利用でき、この `deploy_path` は、サーバー用に設定したパスです。

`php vendor/bin/dep core` を実行すると、 Grav 自身を `--all-yes` オプションで Yes/No の質問をすべてスキップしながらアップグレードします。`php vendor/bin/dep packages` の実行も同じで、すべてのプラグインとテーマをアップグレードします。

<h2 id="conclusion">まとめ</h2>

これで、すべての定義したサイトを、順番にタスクを実行することで、簡単にアップグレードできるようになりました。最初に `public_html/deployer/` フォルダに入り、それから必要なコマンドを実行します：

```bash
php vendor/bin/dep backup
php vendor/bin/dep core
php vendor/bin/dep packages
```

これにより、それぞれのサイトのバックアップを作り、 Grav 自身のアップグレードをし、さらにプラグインとテーマのアップグレードもできます。

