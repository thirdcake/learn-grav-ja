---
title: "パーミッション"
layout: ../../../layouts/Default.astro
---

ホスティング環境次第で、パーミッションは、問題の種になったり、ならなかったりします。
理解しておくべき重要な点は、ファイルシステムでファイルを編集するユーザーと、 PHP を実行するユーザー（通常、それは web サーバーアプリケーションです）が、異なる場合、潜在的な問題があるということです。また、少なくとも、この2つのユーザーに、ファイルへの **読み/書き** 権限がなければ、問題になります。

まず、どのユーザーが Apache や Nginx を実行しているのか、以下のコマンドで明らかにします。

Apache に対しては：

```bash
ps aux | grep -v root | grep apache | cut -d\  -f1 | sort | uniq
```

Nginx に対しては：

```bash
ps aux | grep -v root | grep nginx | cut -d\  -f1 | sort | uniq
```

そして、 Grav ディレクトリのファイルを所有しているユーザーを明らかにします。
次を実行します：

```bash
ls -l
```

Grav は、ファイルベースの CMS なので、キャッシュファイルやログファイルを作成するために、ファイルシステムへの書き込み権限が必要です。3つの主要なシナリオがあります：

1. <h5 id="php-webserver-runs-with-the-same-user-that-edits-t">ファイルを編集するユーザーと同一のユーザーが PHP/Webserver を実行する（推奨）</h5>
   これは、ほとんどの **レンタルサーバー** 設定で使われるアプローチで、ローカル環境でもよく機能します。 [MacOS Yosemite, Apache, and PHP](https://getgrav.org/blog/mac-os-x-apache-setup-multiple-php-versions) について書いたブログ記事で、 Apache をあなたのパーソナルなユーザーアカウントで動かす方法の概要を説明しています。このアプローチは、専用 web ホスティングで使う場合は、十分な安全性が考慮されていませんので、2つ目、3つ目のオプションを使ってください。

2. <h5 id="php-webserver-runs-with-different-accounts-but-sam">PHP/Webserver を別ユーザーで実行するが、同じグループにする</h5>
   あなたのユーザーと、PHP/Webserver アカウントの共有グループを `775` や `664` パーミッションで使うことで、異なるアカウントであっても、両方のアカウントがファイルに **読み/書き** できるようになります。おそらく、ルートフォルダに `umask 0002` も設定するべきです。それにより、新しいファイルが、適切なパーミッションで作成されます。

3. <h5 id="different-accounts-fix-permissions-manually">異なるアカウントで、パーミッションを手作業で修正する</h5>
   最後のアプローチは、完全に異なるアカウントで、編集後に、ファイルの所有とパーミッションを PHP/Webserver ユーザーが適切に **読み/書き** できるように更新するというものです。

シンプルな **パーミッション修正** シェルスクリプトは、次のように使われます：

```bash
#!/bin/sh
chown -R joeblow:staff .
find . -type f -exec chmod 664 {} \;
find ./bin -type f -exec chmod 775 {} \;
find . -type d -exec chmod 775 {} \;
find . -type d -exec chmod +s {} \;
```

このファイルを使って、あなたの設定に合う適切なユーザーとグループのための必要な編集をすることができます。
このスクリプトが基本的にやっていることは：

1. 現在のディレクトリで、すべてのファイルとサブフォルダを `joeblow` の `staff` の所有権に変更します
2. 現在のディレクトリからすべてのファイルを探し、パーミッションを `664` に設定します。これにより、ユーザーとグループに `RW` 権限があり、他は `R` 権限となります
3. 現在のディレクトリからすべてのディレクトリを探し、パーミッションを `775` に設定します。これにより、ユーザーとグループに `RWX` 権限があり、ほかは `RX` 権限となります
4. すべてのディレクトリの **所有権** を設定し、ユーザーとグループの変更が維持されるようにします

<h3 id="">画像キャッシュフォルダのパーミッション</h3>

キャッシュフォルダの画像が間違ったパーミッションで書き込まれている場合、 `user/config/system.yaml` ファイルに以下を設定してみてください,

```yaml
images:
  cache_perms: '0775'
```

`画像` プロパティがすでに存在する場合、最後に `cache_perms: '0775'` を追加するだけです。

これでもまだ機能しない場合は、 Grav のルートフォルダ（ `index.php` のあるフォルダ）に `setup.php` ファイルを作成してください。そしてそこに、以下を記入してください：

```php
<?php
umask(0002);
```

すでに `setup.php` ファイルがある場合は、単純に、この行を一番上に追記するだけです。
この `setup.php` ファイルは、基本的にマルチサイト設定で使われるものですが、すべての Grav の呼び出しで呼ばれるもので、他の使い方のために使うこともできます。

<h3 id="co-hosting-with-a-wordpress-site">WordPress サイトと一緒にホスティングする</h3>

一般的に、 Grav は WordPress サイトが存在するルートレベルのフォルダにインストールすることができ、2つの CMS はうまく共存できます（Grav フォルダの htaccess に Base Rewrite を設定するのを忘れないでください）。
管理パネルや、 Grav のページを見ているときに、キャッシュファイルのパーミッションエラーが表示される場合は、 WP-Engine がその WordPress サイトにインストールされていないかチェックしてください。
もしそうなら、 WP-Engine のサポートに連絡して、彼らのアグレッシブな分散キャッシュサービスから Grav フォルダの例外を作成してもらう必要があります。

<h3 id="selinux-specific-advice">SELinux に特有のアドバイス</h3>

もし、以上の提案をしてもまだ動かない場合は、以下を Grav のルートフォルダで実行してください。

```txt
chcon -Rv system_u:object_r:httpd_sys_rw_content_t:s0 ./
```

参考：

- [https://unix.stackexchange.com/questions/337704/selinux-is-preventing-nginx-from-writing-via-php-fpm](https://unix.stackexchange.com/questions/337704/selinux-is-preventing-nginx-from-writing-via-php-fpm)
- [https://github.com/getgrav/grav/issues/912#issuecomment-227627196](https://github.com/getgrav/grav/issues/912#issuecomment-227627196)
- [http://stopdisablingselinux.com](http://stopdisablingselinux.com/)
- [http://stackoverflow.com/questions/28786047/failed-to-open-stream-on-file-put-contents-in-php-on-centos-7](http://stackoverflow.com/questions/28786047/failed-to-open-stream-on-file-put-contents-in-php-on-centos-7)
- [http://www.serverlab.ca/tutorials/linux/web-servers-linux/configuring-selinux-policies-for-apache-web-servers/](http://www.serverlab.ca/tutorials/linux/web-servers-linux/configuring-selinux-policies-for-apache-web-servers/)

