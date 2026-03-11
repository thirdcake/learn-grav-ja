---
title: 'Grav ビルトイン Web サーバ'
layout: ../../../../layouts/Default.astro
lastmod: '2025-05-09'
---
Grav を起動する最速の方法として、 PHP をインストールしていれば使える PHP のビルトインサーバーで、ターミナル / コマンドプロンプトから Grav を実行することができます。

Grav をインストールしたディレクトリにターミナルもしくはコマンドプロンプトで移動し、 `bin/grav server` を実行するだけです。

> [!Info]  
> 技術的には PHP がインストールされてさえいれば良いですが、もし [Symfony CLI アプリケーション](https://symfony.com/download) をインストールすれば、サーバーで SSL 認証を提供できます。それにより、 `https://` を使えて、PHP-FPM の利用によりパフォーマンスが向上します。

コマンドを実行すると、以下のような表示になります：

```bash
➜ bin/grav server

Grav Web Server
===============

Tailing Web Server log file (/Users/joeblow/.symfony/log/96e710135f52930318e745e901e4010d0907cec3.log)
Tailing PHP-FPM log file (/Users/joeblow/.symfony/log/96e710135f52930318e745e901e4010d0907cec3/53fb8ec204547646acb3461995e4da5a54cc7575.log)
Tailing PHP-FPM log file (/Users/joeblow/.symfony/log/96e710135f52930318e745e901e4010d0907cec3/53fb8ec204547646acb3461995e4da5a54cc7575.log)

[OK] Web server listening
The Web server is using PHP FPM 8.0.8
https://127.0.0.1:8000


[Web Server ] Jul 30 14:54:53 |DEBUG  | PHP    Reloading PHP versions
[Web Server ] Jul 30 14:54:54 |DEBUG  | PHP    Using PHP version 8.0.8 (from default version in $PATH)
[PHP-FPM    ] Jul  6 14:40:17 |NOTICE | FPM    fpm is running, pid 64992
[PHP-FPM    ] Jul  6 14:40:17 |NOTICE | FPM    ready to handle connections
[PHP-FPM    ] Jul  6 14:40:17 |NOTICE | FPM    fpm is running, pid 64992
[PHP-FPM    ] Jul  6 14:40:17 |NOTICE | FPM    ready to handle connections
[Web Server ] Jul 30 14:54:54 |INFO   | PHP    listening path="/usr/local/Cellar/php/8.0.8_2/sbin/php-fpm" php="8.0.8" port=65140
[PHP-FPM    ] Jul 30 14:54:54 |NOTICE | FPM    fpm is running, pid 73709
[PHP-FPM    ] Jul 30 14:54:54 |NOTICE | FPM    ready to handle connections
[PHP-FPM    ] Jul 30 14:54:54 |NOTICE | FPM    fpm is running, pid 73709
[PHP-FPM    ] Jul 30 14:54:54 |NOTICE | FPM    ready to handle connections
```

ターミナルも、このアドホックなサーバーでのあらゆるアクティビティをリアルタイムで更新します。 `[OK] Web server listening`  行の URL をコピーし、ブラウザに貼り付けると、管理画面を含むサイトにアクセスできます。

```
https://127.0.0.1:8000
```

> [!Warning]  
> これはちょっとした開発には便利なツールですが、Apache や Nginx のような専用 web サーバーの代わりには **なりません** 。

ポート番号をデフォルトの 8000 から変えて指定したい場合、 -p オプションを使います。たとえば、ポート番号を 8001 に設定するには：

```bash
➜ bin/grav server -p 8001
```

