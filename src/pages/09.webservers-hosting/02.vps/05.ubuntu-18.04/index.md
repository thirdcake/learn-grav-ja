---
title: '共通事項 ubuntu 18'
layout: ../../../../layouts/Default.astro
lastmod: '2025-07-14'
---

> [!訳注]  
> このページは、他の VPS のページの一部として動的に挿入される [モジュールページ](../../../02.content/09.modular/#modules) です。 Ubuntu 18.04 LTS に関する共通事項ですが、古い情報だと思われるので、もし読む必要がある場合は、適宜読み替えてください。

<h3 id="update-and-upgrade-packages">パッケージのアップデートとアップグレード</h3>

この時点で、ローカルの `/etc/hosts` エントリーを設定し、提供された IP にナイスでフレンドリーな名前（たとえば `（VPS業者ごとのローカル名）` ）を付けたくなるかもしれません。そうすれば、より簡単にサーバーに SSH 接続できます。 `ssh root@（ローカル名） -p（ポート番号）` のように。

> [!訳注]  
> `.ssh/config` を使うことが多いと思います。

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

### Configure Nginx Connection Pool

Nginx has already been installed, but you should configure is so that it uses a user-specific PHP connection pool.  This will ensure you are secure and avoid any potential file permissions when working on the files as your user account, and via the web server.

Navigate to the pool directory and create a new `grav` configuration:


```bash
# cd /etc/php/7.2/fpm/pool.d
# mv www.conf www.conf.bak
# vim grav.conf
```

In Vim, you can paste the following pool configuration:

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

The key things here are the `user` and `group` being set to a user called `grav`, and the listen socket having a unique name from the standard socket.  Save and exit this file.

We need to create the dedicated `grav` user now:

```bash
# adduser grav
```

Provide a strong password, and leave the other values as default. We need to next create an appropriate location for Nginx to serve files from, so let's switch user and create those folder, and create a couple of test files:

```bash
# su - grav
$ mkdir -p www/html
$ cd www/html
```

Create a simple `index.html` with the contents of:

```html
 <h1>Working!</h1>
```

..and a file called `info.php` with the contents of:

```php
<?php phpinfo();
```

Now we can exit out of this user and return to root in order to setup the Nginx server configuration:

```bash
$ exit
# cd /etc/nginx/sites-available/
# vim grav
```

Then simply paste in this configuration:

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

This is the stock `nginx.conf` file that comes with Grav with 2 changes. 1) the `root` has been adapted to our user/folder we just created and the `fastcgi_pass` option has been set to the socket we defined in our `grav` pool. Now we just need to link this file appropriately so that it's **enabled**:

```bash
# cd ../sites-enabled
# ln -s ../sites-available/grav
# rm default
```

You can test the configuration with the command `nginx -t`. It should return the following.

```bash
nginx: the configuration file /etc/nginx/nginx.conf syntax is ok
nginx: configuration file /etc/nginx/nginx.conf test is successful
```

Now all we have to do is restart Nginx and the php7-fpm process and test to ensure we have configured Nginx and the PHP connection pool correctly:

```bash
# systemctl restart nginx 
# systemctl restart php7.2-fpm
```

Now point your browser at your server: `http://{{ page.header.localname }}` and you should see the text: **Working!**

You can also test to ensure that PHP is installed and working correctly by pointing your browser to: `http://{{ page.header.localname }}/info.php`.  You should see a standard PHP info page with APCu, Opcache, etc listed.

### Installing Grav

This is the easy part!  First we need to jump back over to the Grav user, so either SSH as `grav@{{ page.header.localname }}` or `su - grav` from the root login. then follow these steps:

```bash
$ cd ~/www
$ wget -O grav.zip https://getgrav.org/download/core/grav/latest
$ unzip grav.zip
$ rm -Rf html
$ mv grav html
```

Now That's done you can confirm Grav is installed by pointing your browser to `http://{{ page.header.localname }}` and you should be greeted with the **Grav is Running!** page.

Because you have followed these instructions diligently, you will also be able to use the [Grav CLI](../../07.advanced/02.grav-cli/) and [Grav GPM](../../07.advanced/04.grav-gpm/) commands such as:

```bash
$ cd ~/www/html
$ bin/grav clear

Clearing cache

Cleared:  cache/twig/*
Cleared:  cache/compiled/*

Touched: /home/grav/www/html/user/config/system.yaml
```

and GPM commands:

```bash
$ bin/gpm index
```

