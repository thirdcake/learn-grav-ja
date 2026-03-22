---
title: サーバーサイド
lastmod: 2026-03-22T19:12:18+09:00
description: 'Grav でサイトを運営するにあたり、サーバーサイドのセキュリティ対策として、 Grav や web サーバーソフトウェア、 OS などの設定に関するテクニックや参考資料をまとめています。'
weight: 50
params:
    srcPath: /security/server-side
---

Grav をサーバーサイドで防御するには、サーバーと PHP に適切なオプションを使用します。  
このガイドでは、 Grav が実行されるサーバーの設定方法や、理想的な条件について解説するのではなく、 Grav を安全にするテクニックやベストプラクティスを説明し、さらに、サーバーを安全にする方法について詳しく書かれたリソースへのリンクを紹介します。  
**これは、本番サーバーに関するガイドであり、ローカル環境は対象にしていません。また、初心者ユーザーにはおすすめしない内容です**

## Grav とデフォルトの config{#grav-and-default-configuration}

Grav の利用に際して、利用中のサーバーに適した、ディレクトリ特有の最新の構成を、常に使ってください。  
これらの設定は、 [GitHub リポジトリ](https://github.com/getgrav/grav/tree/develop/webserver-configs) にあります。  
さらに、新しいセキュリティパッチが実装されるたびに、 Grav を定期アップデートしてください - 詳細については、 [CHANGELOG](https://github.com/getgrav/grav/blob/develop/CHANGELOG.md) を参照してください。

## PHP の config{#php-configuration}

PHP の設定を変更する前に、ほとんどのレンタルサーバーでは、すでに適切で安全なデフォルト設定がセットアップされている可能性が高いことを留意してください。  
また、多くの場合、ユーザーが自身で設定を編集することを許可していません。  
あらゆる設定を無効化したり、変更したりする前に、 [PHP 拡張を含めた Grav のシステム要件](https://github.com/getgrav/grav/blob/develop/composer.json) と、設定変更がそれら要件に与える影響について、詳しくなっておく必要があります。

一般的に、 PHP の設定は、 `php.ini` により変更します。  
このファイルの場所は、 `php --ini` コマンドによりコマンドラインから調べられる他、直接コマンドラインにアクセスできない場合は、 `phpinfo.php` というファイルを web サーバーの公開ルートフォルダに作成し、 `<?php phpinfo(); ?>` と書き、そしてブラウザで開くことでできます。  
ファイルパスは、 "Loaded Configuration File" の下に書かれています。  
見つかったら、 `phpinfo.php` ファイルは削除してください。

いくつかの一般的な推奨事項：

- **常に PHP バージョンは最新を保つこと** ： PHP の [supported version](https://php.net/supported-versions.php) を使って、 active かつ stable のバージョンを選んでください。たとえば、 PHP 5.6 と PHP 7.0 は、 2018年冬まででセキュリティ Fix を終えます。 PHP 7.1 は、 PHP 7.2 と同様に active サポートの状況です。（訳注：2026年現在は、8.4 と 8.5 が active support でした。）
- エラーを画面表示させず、 PHP バージョンを公開しない： [PHP.earth 記事](https://docs.php.earth/security/intro/#php-configuration)
- Grav での PHP 実行には、権限の制限された別ユーザーを利用： [パーミッションに関するドキュメント](../../11.troubleshooting/05.permissions/)
- [高度な PHP 防衛](https://suhosin.org/) のため Suhosin を利用

## web サーバーの config{#webserver-configuration}

一般的な web サーバーもしくは HTTP サーバーソフトウェアとして、 Nginx や、 Apache があり、よりモダンな代替手段として LiteSpeed や、 Caddy サーバーもあります。  
前述の [web サーバー設定](https://github.com/getgrav/grav/tree/develop/webserver-configs) には、 Grav に必要なデフォルト設定が含まれますが、サーバー設定次第で、さらに安全にすることも可能です。  
いくつかの関係資料はこちら：

- DigitalOcean の [How To Secure Nginx](https://www.digitalocean.com/community/tutorials/how-to-secure-nginx-on-ubuntu-14-04) および nixCraft の [Nginx WebServer Best Security Practices](https://www.cyberciti.biz/tips/linux-unix-bsd-nginx-webserver-security.html)
- Geek Flare の [Apache Web Server Hardening & Security Guide](https://geekflare.com/apache-web-server-hardening-security/) および Tecmint の [Apache Web Server Security and Hardening Tips](https://www.tecmint.com/apache-security-tips/)
- Bobcares の [Ways of improving security in Litespeed](https://bobcares.com/blog/ways-of-improving-security-in-litespeed/)

## サーバーの config{#server-configuration}

**常にオペレーティングシステム（OS）は最新に** してください。  
OS は、 PHP 以上に悪用や侵入の標的になるため、できるだけ頻繁にアップデートするべきです。  
また、 **その他のソフトウェアも最新に保つ** 必要があります：インストールされているのは、 OS, PHP, Grav だけではありません。  
他のソフトウェアパッケージもまた、リスク要因であり、頻繁にアップデートするべきです。

サイトへのユーザー接続を守るため、 [SSL 認証の HTTPS](https://docs.php.earth/security/ssl/) を有効化すべきです。  
これは、サーバーとブラウザ間のすべての通信をプライベートかつ暗号化してくれます。  
無料の認証とサービスは、たとえば [Let's Encrypt](https://letsencrypt.org/about/) や [CloudFlare](https://www.cloudflare.com/ssl/) で利用できます。

もしあなたのサーバーが Linux 上で動いているなら、 [Security Enhanced Linux](https://selinuxproject.org/page/Main_Page) を有効化してください。  
SELinux は、通常デフォルトで有効になっており、 [その価値があります](http://www.computerworld.com/article/2717423/security/why-selinux-is-more-work--but-well-worth-the-trouble.html)  
システム管理者向けのさらなる推奨事項は、 [nixCraft](https://www.cyberciti.biz/tips/php-security-best-practices-tutorial.html) にあります。

