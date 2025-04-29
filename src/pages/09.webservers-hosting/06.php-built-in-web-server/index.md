---
title: "PHP ビルトインサーバー"
layout: ../../../layouts/Default.astro
---

<h2 id="test-hosting-with-the-php-built-in-web-server">PHPのビルトインサーバーでテストホスティング</h2>

PHP コマンドライン（CLI SAPI） には、ビルトインでwebサーバーが付属しえおり、Grav サイトの簡易的なテストやデモにい便利です。これは、フルの web サーバー機能では **ありません** ので、公開されるネットワークでは使わないでください。

<h2 id="using-the-cli-web-server">CLI web サーバを使う</h2>

1. コマンドラインで、[Grav のルート] フォルダに移動します
2. `php -S localhost:8080 system/router.php` を実行し、サーバーをスタートさせます。以下のようなレスポンが表示されます。

		php -S localhost:8080 system/router.php
		PHP 7.3.27 Development Server started at Thu Jun 17 09:24:46 2021
		Listening on http://localhost:8080
		Document root is /Users/somerandom/Desktop/quick-grav-test
		Press Ctrl-C to quit.

3. 対象の URL をブラウザで表示します。例：`http://localhost:8080/`
4. web サーバーをストップするには、Ctrl キーと c を同時押ししてください。

### Address Already in Use Error

`php -S` コマンドを実行したときに、 "Address already in use" というエラーが出た場合、すでにあなたのコンピュータマシンで、特定のポート番号（例： `:8080` ）のサーバーが動いています。コマンド内のポート番号を、（例えば： `:8181` のように）変更することで、解決しますので、試してみてください。

<h2 id="real-time-log-display">リアルタイムのログ表示</h2>

CLI web サーバーは、サイトのブラウザー表示につれて、リアルタイムのログを表示します。これは、素早くテストするときに便利です。

````
PHP 7.3.27 Development Server started at Thu Jun 17 09:24:46 2021
Listening on http://localhost:8080
Document root is /Users/somerandom/Desktop/quick-grav-test
Press Ctrl-C to quit.
[Thu Jun 17 09:26:15 2021] 127.0.0.1:63965 [200]: /
[Thu Jun 17 09:26:15 2021] 127.0.0.1:64007 [200]: /assets/fd2c5827e1f18bb54d20265f4fc56b59.css?g-74e4c5a3
[Thu Jun 17 09:26:15 2021] 127.0.0.1:64008 [200]: /assets/d87a2d24fae663a8c55e144c963a1915.js?g-74e4c5a3
[Thu Jun 17 09:26:15 2021] 127.0.0.1:64014 [200]: /assets/1d8c5ea92966046d4649472f1630a253.js?g-74e4c5a3
[Thu Jun 17 09:26:16 2021] 127.0.0.1:64024 [200]: /user/images/navigation/logo_small.png
[Thu Jun 17 09:26:16 2021] 127.0.0.1:64028 [200]: /user/images/navigation/bgdark.svg
[Thu Jun 17 09:26:16 2021] 127.0.0.1:64030 [200]: /user/images/navigation/bglight_50.png
[Thu Jun 17 09:26:16 2021] 127.0.0.1:64032 [200]: /user/images/navigation/brand.svg
````

<h2 id="learn-more">さらに学ぶには</h2>

[CLI Web サーバー](https://www.php.net/manual/en/features.commandline.webserver.php) に関する PHP のウェブサイトで、より詳しく学べます。

