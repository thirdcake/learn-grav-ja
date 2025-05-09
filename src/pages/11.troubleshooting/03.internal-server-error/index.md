---
title: "500 Internal Server Error"
layout: ../../../layouts/Default.astro
---

> The server encountered an internal error or misconfiguration and was unable to complete your request.
>
> Please contact the server administrator at webmaster@localhost to inform them of the time this error occurred, and the actions you performed just before this error.
>
> More information about this error may be available in the server error log.
> <cite>Apache/2.4.7 Server at localhost Port 80</cite>

このエラーは、次のような理由で引き起こされます：

- サーバーの設定ミス（ `httpd.conf` ）
- `.htaccess` の問題
- `mod_security` もしくはこれに類似する問題

<h3 id="test-php-is-working">PHP が動いているかテストする</h3>

最初にやるべきことは、PHP がサーバーで適切に動作しているか、確認することです。Grav はその場合、直接の原因ではありません。テストするには、単純に `info.php` という一時ファイルを作成し、 Grav フォルダのルートに配置します。（セキュリティ上、終わったら削除してください！）このファイルには、次のような PHP コードを書き込んでください。

```php
<?php phpinfo();
```

次に、ブラウザでこのファイルを表示させてください： `http://yoursite.com/your_grav_directory/info.php` PHP バージョンや読み込まれている拡張を含む PHP 設定に関するすべての情報が一覧になったレポートページが表示されます。

<h3 id="check-permissions">パーミッションをチェックする</h3>

500 エラーは、間違ったパーミッションにより起こります。[パーミッションガイド](../permissions/) をチェックしてください。

<h3 id="register-globals-issue">register_globals の問題</h3>

PHP 5.3 や 5.4 から 5.5 に最近アップグレードした場合、 `php.ini` ファイルに古い設定が残っていることがあります。 **500 Internal Server Error** の原因となりうるものの1つとして、 `register_globals` 設定があります。単純にこの行を削除するか、コメントアウトしてください：

```apacheconf
register_global = On
```

その後、Apache サーバーを再起動してください。

<h3 id="threadstacksize-on-windows">Windows の ThreadStackSize</h3>

サーバーが Windows 上で動作している場合、 500 Internal Server Error が **ThreadStackSize** が小さすぎることによって起こることがあります。このコードを `httpd.conf` ファイルの末尾に追加してください。

```apacheconf
<IfModule mpm_winnt_module>
  ThreadStackSize 8388608
</IfModule>
```

その後、Apache サーバーを再起動してください。

<h3 id="options-indexes">-Indexes オプション</h3>

Grav では、フォルダのディレクトリ一覧を表示させないために、 `-Indexes` オプションを使っています。ホスティング会社によっては、Apache の `.htaccess` が `Options` 設定を操作してほしくない場合があります。

このような場合に、Grav の `.htaccess` ファイルの以下の行をコメントアウトすることで、 Internal Server error 問題が解決できたというレポートを見てきました。

```apacheconf
# Prevent file browsing
#Options -Indexes
```

<h3 id="rewritebase-problems">Rewritebase 問題</h3>

1&1 ホスティング（それ以外の会社でも適用可能です）で、RewriteBase を設定しなかったことで 500 Internal Server Errors が発生したと、いくつか報告されています。

```apacheconf
# RewriteBase /
```

上記を、下記のように変更してみてください：

```apacheconf
RewriteBase /
```

(Credit: [http://ahcox.com/webdev/1and1-internal-server-error-grav/](http://ahcox.com/webdev/1and1-internal-server-error-grav/))

<h3 id="admin-panel-navigation">管理パネルのナビゲーション</h3>

Grav の管理パネルのナビゲーションで、画面左上に **Internal Server Error** メッセージが表示されます。これは、 /cache フォルダのパーミッションが正しくないことで起こります。

 ![Internal Server Error](internal-server-error.png)

このエラーが表示される場合、 /cache フォルダのパーミッションが正しく設定されていない可能性があります。ただそのフォルダを書き込み可能にするのではなく、そのフォルダ以下も含めてすべて書き込み可能にする必要があります。以下のコマンドを、 Grav のディレクトリで実行することで、問題が解決するはずです。

```bash
sudo chmod 755 cache/ -R
```

