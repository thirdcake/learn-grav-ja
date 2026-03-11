---
title: '共通事項 ubuntu 18'
layout: ../../../../layouts/Default.astro
lastmod: '2025-07-15'
---

> [!訳注]  
> このページは、他の VPS のページの一部として動的に挿入される [モジュールページ](../../../02.content/09.modular/#modules) です。 Ubuntu 18.04 LTS に関する共通事項ですが、古い情報だと思われるので、もし読む必要がある場合は、適宜読み替えてください。

<h3 id="update-and-upgrade-packages">パッケージのアップデートとアップグレード</h3>

この時点で、ローカルの `/etc/hosts` エントリーを設定し、提供された IP にナイスでフレンドリーな名前（たとえば `（VPS業者ごとのローカル名）` ）を付けたくなるかもしれません。そうすれば、より簡単にサーバーに SSH 接続できます。 `ssh root@（ローカル名） -p（ポート番号）` のように。

> [!訳注]  
> `.ssh/config` を使うことも多いと思います。

> [!Tip]  
> `-p` 設定オプションは、非標準の SSH ポート番号を使うばために必要です。

サーバに **root** 権限で SSH 接続成功したら、最初にやりたいのは、パッケージのアップデートとアップグレードでしょう。これにより、実行が _最新で最上位_ になります：

```bash
# apt update
# apt upgrade
```

もしプロンプトで尋ねられたら、 `Y` と答えます。

先に進む前に、 **Apache2** を削除します。 **Nginx** に置き換え予定です：

```bash
# apt remove apache2*
# apt autoremove
```

> [!Note]  
> Apache2 がインストールされていないかもしれません。しかし、やっておいたほうが安全です！

次に、必要なパッケージをいくつかインストールします：

```bash
# apt install vim zip unzip nginx git php-fpm php-cli php-gd php-curl php-mbstring php-xml php-zip php-apcu
```

ここで、完全な VIM エディタをインストールします（Ubuntu に入っているミニバージョンではありません）。また、 Nginx web サーバ、 GIT コマンド、そして **PHP 7.2** をインストールしています。

<h3 id="configure-php7-2-fpm">PHP7.2 FPM の設定</h3>

php-fpm がインストールされると、より安全なセットアップに必要な設定変更が少し生じます。

```bash
# vim /etc/php/7.2/fpm/php.ini
```

`cgi.fix_pathinfo` を検索してください。デフォルトではコメントアウトされており、 '1' が設定されています。

これはとても安全でない設定です。というのも、要求された PHP ファイルが見つからなかったときに、 PHP に一番近いファイルの実行を試させる設定だからです。この設定により、実行されるべきでないスクリプトを実行されてしまうかもしれません。

この行のコメントを外し、'1' を '0' に変えてください。次のように：

```bash
cgi.fix_pathinfo=0
```

ファイルを保存し、閉じてください。それからサーバーをリスタートさせてください。

```bash
# systemctl restart php7.2-fpm 
```

<h3 id="configure-nginx-connection-pool">Nginx の接続プールの設定</h3>

Nginx は先ほどインストール済みですが、設定をすることで、ユーザ固有の PHP 接続プールが使えます。これにより、安全になり、ユーザアカウントとして、そして web サーバ経由でファイルを操作する際のパーミッションの問題が起こるのを避けられます。

プールのディレクトリに移動し、新しく `grav` 設定を作成してください：

```bash
# cd /etc/php/7.2/fpm/pool.d
# mv www.conf www.conf.bak
# vim grav.conf
```

Vim で、以下のプール設定を貼り付けてください：

```apache
[grav]

user = grav
group = grav

listen = /var/run/php/php7.2-fpm.sock

listen.owner = www-data
listen.group = www-data

pm = dynamic
pm.max_children = 5
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 3

chdir = /
```

ここでの重要ポイントは、 `user` と `group` を `grav` というユーザに設定することと、標準的なソケットではなくユニークな名前を持つ listen socket を設定することです。このファイルを保存して閉じてください。

ここで、専用の `grav` ユーザーを作成する必要があります：

```bash
# adduser grav
```

強いパスワードを提供してください。そして、他の値はデフォルト値で残してください。次に作成しなければならないのは、ファイルを提供する Nginx の適切な場所です。そこで、ユーザを変更し、これらのフォルダを作成し、いくつかのテストファイルを作成します：

```bash
# su - grav
$ mkdir -p www/html
$ cd www/html
```

次のようなコンテンツを持つシンプルな `index.html` を作成してください：

```html
 <h1>Working!</h1>
```

..そして、次のようなコンテンツを持つ `info.php` ファイルを作成してください：

```php
<?php phpinfo();
```

これで、このユーザを終了し、 Nginx サーバ設定のセットアップのため、ルートユーザに戻ります：

```bash
$ exit
# cd /etc/nginx/sites-available/
# vim grav
```

それから、この設定を貼り付けてください：

```nginx
server {
    #listen 80;
    index index.html index.php;

    ## Begin - Server Info
    root /home/grav/www/html;
    server_name localhost;
    ## End - Server Info

    ## Begin - Index
    # for subfolders, simply adjust:
    # `location /subfolder {`
    # and the rewrite to use `/subfolder/index.php`
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    ## End - Index

    ## Begin - Security
    # deny all direct access for these folders
    location ~* /(\.git|cache|bin|logs|backup|tests)/.*$ { return 403; }
    # deny running scripts inside core system folders
    location ~* /(system|vendor)/.*\.(txt|xml|md|html|yaml|yml|php|pl|py|cgi|twig|sh|bat)$ { return 403; }
    # deny running scripts inside user folder
    location ~* /user/.*\.(txt|md|yaml|yml|php|pl|py|cgi|twig|sh|bat)$ { return 403; }
    # deny access to specific files in the root folder
    location ~ /(LICENSE\.txt|composer\.lock|composer\.json|nginx\.conf|web\.config|htaccess\.txt|\.htaccess) { return 403; }
    ## End - Security

    ## Begin - PHP
    location ~ \.php$ {
        # Choose either a socket or TCP/IP address
        fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
        # fastcgi_pass unix:/var/run/php5-fpm.sock; #legacy
        # fastcgi_pass 127.0.0.1:9000;

        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root/$fastcgi_script_name;
    }
    ## End - PHP
}
```

これは、 Grav に入っているデフォルトの `nginx.conf` ファイルに2つの変更を加えたものです。

1. `root` を、先ほど作成した user/folder に適合させました
1. `fastcgi_pass` オプションを、 `grav` プールで定義したソケットに設定しました

**有効化** のため、このファイルを適切にリンクする必要があります：

```bash
# cd ../sites-enabled
# ln -s ../sites-available/grav
# rm default
```

この設定を `nginx -t` コマンドでテストできます。以下のように返ってくるはずです。

```bash
nginx: the configuration file /etc/nginx/nginx.conf syntax is ok
nginx: configuration file /etc/nginx/nginx.conf test is successful
```

あとは、 Nginx と php7-fpm プロセスを再起動し、 Nginx と PHP 接続プールが正しく設定されたことを確認するテストをしてください：

```bash
# systemctl restart nginx 
# systemctl restart php7.2-fpm
```

サーバで、次の URL をブラウザ表示してください： `http://{{ page.header.localname }}` 。そして **Working!** というテキストを確認してください：

ブラウザで `http://{{ page.header.localname }}/info.php` を表示することで、次のこともテストできます。 PHP がインストールされ、正しく機能していることを。標準的な PHP info ページ（APCu, Opcache, その他の一覧）が表示されるはずです。

<h3 id="installing-grav">Grav のインストール</h3>

ここからは簡単なパートです！ まず、 Grav ユーザに戻ってください。 SSH 接続で `grav@{{ page.header.localname }}` とするか、ルート権限でログインした状態から `su - grav` とします。それから、次のステップに進みます：

```bash
$ cd ~/www
$ wget -O grav.zip https://getgrav.org/download/core/grav/latest
$ unzip grav.zip
$ rm -Rf html
$ mv grav html
```

完了です。ブラウザで `http://{{ page.header.localname }}` を表示すれば、 Grav がインストールされていることが確認できます。 **Grav is Running!** ページが表示されるでしょう。

ここまで進めていただければ、 [Grav CLI](../../../07.advanced/02.grav-cli/) や [Grav GPM](../../../07.advanced/04.grav-gpm/) コマンドも利用可能になっています。次のように：

```bash
$ cd ~/www/html
$ bin/grav clear

Clearing cache

Cleared:  cache/twig/*
Cleared:  cache/compiled/*

Touched: /home/grav/www/html/user/config/system.yaml
```

GPM コマンドは：

```bash
$ bin/gpm index
```

