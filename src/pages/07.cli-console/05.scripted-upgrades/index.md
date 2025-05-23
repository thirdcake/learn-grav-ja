---
title: Scriptによるアップデート
layout: ../../../layouts/Default.astro
lastmod: '2025-05-09'
---
もしくは： 複数の Grav を一度にアップグレードします

このページは、[Deployer](https://deployer.org/) を使って、複数の Grav を簡単にアップグレードするためのガイドです。「複数」という意味は、別々の Grav がインストールされている状況であり、[複数サイトインストール](../../08.advanced/05.multisite-setup/) のことではありません。[Grav のCLI](../02.grav-cli/) を実行するため、各インストールの path を使いますが、それらの path を手打ちするようなことはしません。

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


And as simple as that, Deployer told Grav to upgrade all packages, which upgraded the Email-plugin to its newest version.

## Prerequisites

Like with Grav, you need PHP **v7.3.6** or above. This also applies for the command line (CLI), so if you have multiple versions installed use the one which refers to the right version. Use the command `php -v` to check your default version, mine is **PHP 7.2.34**.

On shared environments, check with your host which command to use for CLI. In my case, this is `php74` which with `-v` returns **PHP 7.4.12**. This also means prepending every path like this: `php74 vendor/bin/dep list`.

Some hosts also allow you to select your default PHP version to use for CLI, check with your host how to do this.

## Setup

In your servers public root (like **public_html** or **www**), create a folder named `deployer` and enter it. We'll use this as a basis for the project. You'll want to password-protect this directory (see [DigitalOcean Guide](https://www.digitalocean.com/community/tutorials/how-to-set-up-password-authentication-with-apache-on-ubuntu-14-04) for a manual approach, or use [CPanel](https://www.siteground.com/tutorials/cpanel/pass_protected_directories.htm) if available).

You need to have a working installation of Grav, as well as [Composer](https://getcomposer.org/). Some hosts have Composer installed already, which you can check by running `composer -v`. If it is not installed you can install it through SSH -- from the root directory -- with the following:

```bash
export COMPOSERDIR=~/bin;mkdir bin
curl -sS https://getcomposer.org/installer | php -- --install-dir=$COMPOSERDIR --filename=composer
```

Or, if you prefer a [local installation](https://getcomposer.org/download/), run the following in the `public_html/deployer/`-folder:

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

With a local installation, composer is ran through `composer.phar` rather than just `composer`. Now, while still in the `public_html/deployer/`-folder, run the following to install [Deployer](https://deployer.org/docs/installation):

```bash
composer require deployer/deployer
```

Now, still in the same folder, create a file named `deploy.php`. We'll use this to run each task with Deployer. Copy and paste the following into the file:

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

## Configuration

Because Deployer needs an explicit staging-environment, we set it to `production`. Further, to allow for specific php version we set a default executable to be used. This can be a named executable or the path to a specific version of PHP. Our configuration now looks like this:

```php
// Configuration
set('default_stage', 'production');
set(php, 'php56');
```

If your default PHP CLI version is **5.6\*** or higher, you change this to `set(php, 'php');`.

### Servers

We can set up as many servers/sites as needed, the script will be ran for each of them in order. They can be local installations or on external servers, but since this is a local setup we use `localServer` (see [Deployer/servers](https://deployer.org/docs/servers) for more configurations). Here's an example with multiple sites:

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

Note that we use absolute paths to the installations, but they are relative to how the path is interpreted by SSH. This means that on the server, we want the full path because Deployer interprets this correctly, but we could also use the tilde-key if a HOMEDIR is set, like this: `~/public_html/deployer/grav1`.

### Tasks

Three tasks are currently set up: `backup`, `core`, and `packages`. Running `php vendor/bin/dep backup` creates a backup of each installation, available in **deploy_path/backup/BACKUP.zip**, where `deploy_path` is the paths you configured for the servers.

Running `php vendor/bin/dep core` upgrades Grav itself, and does this with the `--all-yes` option to skip all Yes/No questions asked. The same applies when running `php vendor/bin/dep packages`, which upgrades all plugins and themes.

## Conclusion

We can now upgrade all your defined sites easily by running the tasks in order. First we enter the `public_html/deployer/`-folder, and then we run each command as needed:

```bash
php vendor/bin/dep backup
php vendor/bin/dep core
php vendor/bin/dep packages
```

We will now have made a backup of each site, upgraded Grav itself, as well as upgraded all plugins and themes.

