---
title: '共通事項 Ubuntu 24.04 LTS VPS インストール'
description: 'Ubuntu 24.04 の VPS に Grav CMS をインストールする方法を解説します。'
lastmod: '2026-03-27T19:18:05+09:00'
weight: 50
---

> [!訳注]  
> このページは、他の VPS のページの一部として動的に挿入される [モジュールページ](../../../02.content/09.modular/#modules) です。

このガイドは、 Nginx と PHP 8.3 を持つ Ubuntu 24.04 LTS (Noble Numbat) VPS に Grav をインストールする方法を解説します。

### 最初のサーバーのセットアップ{#initial-server-setup}

最初に、 ローカルの `/etc/hosts` にエントリーをセットアップして、サーバー IP に親しみやすい名前（たとえば、 `myserver.local` のような）をつけます。これにより、サーバーに SSH 接続しやすくなります：

```bash
ssh root@myserver.local -p 0000
```

> [!NOTE]  
> 上記の `-p 0000` オプションは、非標準の（22以外の） SSH ポート番号の VPS サービスを利用する場合に必要です。

### システムのパッケージを更新{#update-system-packages}

**root** 権限で接続後、すべてのインストールパッケージをアップデートしてください：

```bash
apt update && apt upgrade -y
```

### 必要なパッケージをインストール{#install-required-packages}

Nginx, PHP 8.3, そして Grav に必要な拡張機能をインストールしてください：

```bash
apt install -y vim zip unzip nginx git \
    php8.3-fpm php8.3-cli php8.3-gd php8.3-curl \
    php8.3-mbstring php8.3-xml php8.3-zip php8.3-intl php8.3-apcu
```

ここでインストールしているのは：
- **Nginx** - 高パフォーマンスの web サーバー
- **PHP 8.3-FPM** - PHP の FastCGI 処理マネージャー
- **PHP Extensions** - Grav の画像処理やキャッシュなどに必要なもの

### PHP-FPM を設定{#configure-php-fpm}

セキュリティ向上のため、PHP 設定を編集します：

```bash
vim /etc/php/8.3/fpm/php.ini
```

`sgi.fix_pathinfo` を探して（vim では、 `/cgi.fix_pathinfo` で検索できます）、コメントを外し、 `0` を設定してください：

```ini
cgi.fix_pathinfo=0
```

> [!WARNING]  
> この設定により、リクエストされたファイルが見つからない場合に、 PHP が最も近いファイルを実行することを防ぎます。 - 有効にしたままにすると重大なセキュリティリスクになります。

### 専用ユーザーを作成{#create-a-dedicated-user}

サイトを実行する `grav` というユーザーを作成してください。（root 権限で web アプリを実行しないでください）：

```bash
adduser grav
```

パスワードは強力なものを使ってください。

### PHP-FPM プールを設定{#configure-php-fpm-pool}

PHP-FPM プールを設定します。

grav ユーザー専用の PHP-FPM プールを作成します：

```bash
cd /etc/php/8.3/fpm/pool.d
mv www.conf www.conf.bak
vim grav.conf
```

以下の設定を追記してください：

```ini
[grav]
user = grav
group = grav

listen = /run/php/php8.3-fpm-grav.sock

listen.owner = www-data
listen.group = www-data

pm = dynamic
pm.max_children = 10
pm.start_servers = 3
pm.min_spare_servers = 2
pm.max_spare_servers = 5

chdir = /
```

### Web ディレクトリを作成{#create-web-directory}

grav ユーザーへ変換し、 web ディレクトリを作成してください：

```bash
su - grav
mkdir -p ~/www/html
```

セットアップを確認するためのテストファイルを作成してください：

```bash
echo '<?php phpinfo();' > ~/www/html/info.php
exit
```

### Nginx の設定{#configure-nginx}

Nginx サーバーブロックを作成してください：

```bash
vim /etc/nginx/sites-available/grav
```

以下の設定を追記してください：

```nginx
server {
    listen 80;
    index index.html index.php;

    ## Begin - Server Info
    root /home/grav/www/html;
    server_name _;
    ## End - Server Info

    ## Begin - Index
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
        fastcgi_pass unix:/run/php/php8.3-fpm-grav.sock;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root/$fastcgi_script_name;
    }
    ## End - PHP
}
```

サイトを有効化し、 default を削除してください：

```bash
ln -s /etc/nginx/sites-available/grav /etc/nginx/sites-enabled/
rm /etc/nginx/sites-enabled/default
```

設定をテストしてください：

```bash
nginx -t
```

こんなふうになるはずです：

```txt
nginx: the configuration file /etc/nginx/nginx.conf syntax is ok
nginx: configuration file /etc/nginx/nginx.conf test is successful
```

### サービスをスタートさせる{#start-services}

Nginx 及び PHP-FPM をリスタートさせます：

```bash
systemctl restart nginx
systemctl restart php8.3-fpm
```

`http://YOUR_SERVER_IP/info.php` にアクセスし、 PHP が動いているか確認してください。
PHP 8.3 及び APCu が列挙された PHP info ページが表示されるはずです。

> [!CAUTION]  
> テスト後は、 info.php ファイルを削除してください： `rm /home/grav/www/html/info.php`

### Grav をインストール{#install-grav}

grav ユーザーに変換し、 Grav をダウンロードしてください：

```bash
su - grav
cd ~/www
wget -O grav.zip https://getgrav.org/download/core/grav/latest
unzip grav.zip
rm -rf html
mv grav html
```

### インストールの確認{#verify-installation}

`http://YOUR_SERVER_IP` にアクセスしてください。 **Grav is Running!** ページが表示されるはずです。

### CLI ツールのテスト{#test-cli-tools}

`grav` ユーザーとして実行したため、 CLI ツールはそのままで動かせます：

```bash
cd ~/www/html
bin/grav clear
```

出力：

```txt
Clearing cache

Cleared:  cache/twig/*
Cleared:  cache/compiled/*

Touched: /home/grav/www/html/user/config/system.yaml
```

GPM コマンドも、同様に動きます：

```bash
bin/gpm index
```

### オプション： Admin プラグインのインストール{#optional-install-admin-plugin}

Grav Admin パネルをインストールするには：

```bash
bin/gpm install admin
```

その後、 `http://YOUR_SERVER_IP/admin` にアクセスし、管理者アカウントを作成してください。

### オプション： Let's Encrypt での HTTPS 有効化{#optional-enable-https-with-let-s-encrypt}

本番サイトでは、 Certbot を使った HTTPS を有効化します：

```bash
apt install -y certbot python3-certbot-nginx
certbot --nginx -d yourdomain.com
```

Certbot は、自動的に Nginx を SSL に設定し、自動更新します。

### 次のステップ{#next-steps}

- [サイトの config 設定](../../../01.basics/05.grav-configuration/)
- [テーマのインストール](../../../03.themes/)
- [プラグインの追加](../../../04.plugins/)
- [コンテンツ作成](../../../02.content/)

