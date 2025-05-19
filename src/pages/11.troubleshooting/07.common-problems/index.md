---
title: "一般的な問題"
layout: ../../../layouts/Default.astro
---

ここでは、 [Grav forum](https://getgrav.org/forum) や [Discord Chat server](https://chat.getgrav.org) であがった、よくある問題について情報提供します。問題をリスト化し、関係する解決策を1つの場所にまとめることで、時間の節約になります。

<h2 id="cannot-connect-to-the-gpm">GPMにつながらない</h2>

**問題：** GPM にたどり着かない、または管理パネルでそのエラーが出る

**解決策：**

まず、 PHP に cURL と OpenSSL がインストールされていることを確認します。
これは、管理パネルからでも Configuration -> Info でチェック可能です。
"OpenSSL" セクションで、 `OpenSSL support: enabled` になっていることを確認します。
cURL についても同様で、 `cURL support: enabled` になっていることを確認してください。

これが OK であれば、プロキシサーバーを経由していないか確認します。
もしそうなら、 Grav System 設定で [それを設定します](../../01.basics/05.grav-configuration/#system-configuration) 。そして、[接続に問題が無いか確認してください](../06.proxy/)

次に、 [パーミッションをチェックしてください](../05.permissions/) 。

上記をすべて実行したあとにも、まだ GPM との接続に問題がある場合は、いくつかのサーバーで（ほとんどの場合、 Windows 上で実行されるローカルマシンで）、getgrav.org の SSL 証明書が [A レーティング](https://www.ssllabs.com/ssltest/analyze.html?d=getgrav.org&hideResults=on) であるにもかかわらず、検証に問題があることがわかっています。
この問題に対処するには、新しい system config として `system.gpm.verify_peer` を追加します。このファイルは、デフォルトで有効化されます。これを false に設定し、もう一度試してみてください。

この時点でも、まだ機能しない場合は、チャットやフォーラムから、連絡をとったり、報告を送ってください。

また、 CLI コマンドが機能するかもチェックしてください。サーバーに SSH 接続をして、 `bin/gmp index` を実行し、このエラーが管理パネル内だけの問題なのか、それともコマンドラインでも起こることなのかをチェックしてください。

<h2 id="admin-interface-won-t-scroll">管理パネル画面がスクロールできない</h2>

**問題：** 管理パネルのインターフェースにアクセスしたとき、ページがスクロールしない

**解決策：** いくつかの原因が報告されていますが、最も一般的な解決策は、以下のとおりです。

- ページをハードリロードします。ブラウザーのキャッシュをクリアし、それから再読込みしてください。
- 最新バージョンの Grav を使っていることを確認してください。そして、デフォルト言語を英語にしてください。これによってスクロール問題が解決する場合、問題の出た言語を [issue として](https://github.com/getgrav/grav-plugin-admin/issues/) 報告してください。
- HTTPS や、 CDN として CloudFlare を利用している場合、当該サービスの JS-最適化（デフォルトで有効になっている機能）をレンダリングからブロックしてください。これを無効化するには、 CloudFlare にログインし、関係するドメインを選択し、以下のいずれかを実行します：
    1. この最適化をすべて無効化するには、 "Speed" に移動し、 "Rocket Loader" へスクロールダウンしてください。
        - これを "Off" に設定すると、 CloudFlare は script をブロックしませんが、当該サービスの最適化のメリットは得られなくなります。
    2. Grav の管理パネルインターフェースのみ最適化を無効にするには、 "Page Rules" に移動し、 "Create Page Rule" ボタンをクリックします。
        - "If the URL matches" フィールドに、ドメイン名に続けて `/admin` を入力します。たとえば： `example.com/admin`
        - "Add a Setting" をクリックし、ドロップダウンで "Rocket Loader" を探します。選択したとき、 "Select Value" の値が **off** に変更されます。
        - "Order" フィールドはそのままに残します。デフォルトでは、 **First** が設定されています。
        - 最後に、 "Save ans Deploy" ボタンをクリックします

上記すべてで機能しない場合、ブラウザのコンソールに、何か JavaScript エラーが報告されていないかチェックしてください。Chrome や Firefox の場合、 F12 キーもしくは Ctrl+Shift+i を押した後、 "Console" タブをクリックすることで表示されます。エラーを [issue として](https://github.com/getgrav/grav-plugin-admin/issues/) 報告してください。

<h2 id="fetch-failed">Fetch Faildというエラーが表示される</h2>

管理パネル内では、ときどき "Fetch Failed" という赤いポップアップが表示されるかもしれません。
もしこれが表示されても、それほど気にしないでください。ただの接続問題を意味するものだからです。

しかし、毎回表示される場合は、 `mod_security` が Grav のネットワークリクエストをブロックしているという問題に遭遇しているかもしれません。

この問題は、それをあげているルールを探し、無効化することで解決できます。そのルールは、ケースごとに違うかもしれませんが、 `mod_security` の設定によります。

自サーバーで実行している場合、この方法へのガイドは、 [http://www.inmotionhosting.com/support/website/modsecurity/find-and-disable-specific-modsecurity-rules](http://www.inmotionhosting.com/support/website/modsecurity/find-and-disable-specific-modsecurity-rules) に見つかります。そうでない場合、ホスティング会社に連絡して、問題を説明してください。

類似の問題： [admin#951](https://github.com/getgrav/grav-plugin-admin/issues/951)

<h2 id="zend-opcache-api-is-restricted">Zend OPcache API が制限されている</h2>

PHP を Zend Opcache で実行していて、このエラーが表示されたら、あなたの現状の OPCache 設定は、 [OPcache API 関数へのアクセスが、特定の文字列のみのスクリプトに制限されています。](https://php.net/manual/en/opcache.configuration.php) 。
これに対する最も簡単な解決策は、このディレクティブの場所を `php.ini` ファイルか、`php.ini` ファイルに追加される特別な `opcache.ini` ファイルから探して、以下の値になにも設定しないことです：

```txt
opcache.restrict_api=
```

これは、 [ServerPilot](https://serverpilot.io) マネージドホスティングで PHP 7.2 を有効にしたときに起こる問題です。
This is an issue with any [ServerPilot](https://serverpilot.io) managed hosting with PHP 7.2 enabled.  A ticket has been submitted to resolve this on their end.

<h2 id="linkedin-sharing-and-wayback-machine-indexing-not-">LinkedIn シェアと Wayback Machine インデックスが機能しない</h2>

**問題：** LinkedIn でページをシェアしても、ページデータが広がっていかない。Wayback Machine が適切にわたしの web サイトのページをインデックスしない。

**解決策：** WebServer の Gzip もしくは Gzip compression を有効化してください。両方が使われますが、最低でも片方が有効化されていないと、それらの特定の関数は機能しません。

この [問題](https://github.com/getgrav/grav/issues/1639) は、特定のサーバー環境のユーザーが取り上げています。特に、 AWS クラウドベースのサーバーでは、ユーザーは、 LinkedIn で Grav サイトがシェアされるときや、 Wayback Machine によって適切にインデックスされるときの問題を経験しています。
この問題は、 WebServer の Gzip か Gzip compression をオンにすることで解決されます。

<h2 id="cannot-scroll-in-admin-on-cloudflare">CloudFlare で管理パネルがスクロールできない</h2>

CloudFlare ユーザーにとって、管理パネルでスクロールができなくなる可能性があります。これに対する解決策は、以下のとおりです：

CloudFlare のインターフェースで、 **Speed** に移動し、 **Rocket Loader** （もしくは page-rule） を無効化します。

（デフォルトの） 'automatic' モードをスクリプトの **data-attribute** で無効化することもできます： `<script data-cfasync="false" src="/javascript.js"></script>`

page-rule の具体例は、 `example.com/staging/*/admin` にマッチする URL になるでしょう。この `*` は、ワイルドカードで、あらゆるフォルダ名を意味します。設定では、 `Rocket Loader` を追加し、 **Off** を選択してください。

