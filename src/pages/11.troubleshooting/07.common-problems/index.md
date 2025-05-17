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

上記をすべて実行したあとにも、まだ GPM との接続に問題がある場合は、
If after all the above you are still getting issues connecting with GPM, we have noticed that on some servers (mostly local machines running Windows), there are issues verifying the SSL certificate of getgrav.org, even though it is [A Rating](https://www.ssllabs.com/ssltest/analyze.html?d=getgrav.org&hideResults=on).
To work around this problem, we have added a new system config `system.gpm.verify_peer` that is enabled by default. Set it to false and try again.

If at this point it's still not working, get in touch, or report back if you were pointed here via chat/forum.

Also, check the CLI command is working, by opening a SSH connection to the server and running `bin/gpm index` and check if it's just inside Admin that you get this error, or in the command line too.

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

If you are running PHP with Zend OPache and you receive this error, then your current OPCache configuration is [limiting access to OPcache API function to scripts only from a specified string](https://php.net/manual/en/opcache.configuration.php). The simplest solution to this is to find the location of this directive either in your `php.ini` file or in a specialized `opcache.ini` file that is being pulled in to your overall `php.ini` file and set this value to nothing:

```txt
opcache.restrict_api=
```

This is an issue with any [ServerPilot](https://serverpilot.io) managed hosting with PHP 7.2 enabled.  A ticket has been submitted to resolve this on their end.

## LinkedIn Sharing and Wayback Machine Indexing Not Working

**問題：** Sharing pages with LinkedIn and having the page's data propagate is not working. The Wayback Machine is not properly indexing my website's pages.

**解決策：** Enable WebServer Gzip or Gzip compression. Both may be used, but at least one needs to be active for these particular functions to work on some server cases.

This [issue](https://github.com/getgrav/grav/issues/1639) has popped up for users on specific server environments. In particular, with AWS cloud-based servers, users were experiencing issues sharing web pages from their Grav sites on LinkedIn or having them properly indexed by the Wayback Machine. This problem was resolved by turning on either WebServer Gzip or Gzip compression.

## Cannot Scroll in Admin on CloudFlare

For CloudFlare users, the ability to scroll in the Admin can be interrupted. There are solutions to this, as follows:

In CloudFlare's interface, go to **Speed** and disable **Rocket Loader** (or through a page-rule).

It can also be disabled in the (default) 'automatic' mode with a **data-attribute** on scripts: `<script data-cfasync="false" src="/javascript.js"></script>`.

An example of a page-rule would be the URL match `example.com/staging/*/admin`, where the `*` is a wildcard indicating any folder-name. For settings, add `Rocket Loader` and select **Off**.

