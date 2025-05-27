---
title: Nginx
layout: ../../../../layouts/Default.astro
lastmod: '2025-05-27'
---

*Nginx* とは、 HTTP サーバーソフトウェアで、 web サーバーのコア機能と、プロキシー機能に特化しています。
資源効率が良く、負荷がかかる中での応答性の良さから、とても人気です。
Nginx は、worker process を生成し、それぞれが大量の接続を処理することができます。
worker によって処理されるそれぞれの接続は、他の接続とともに存在するイベントループ内に置かれます。
ループ内では、イベントは非同期に処理され、ノン・ブロッキングで処理されます。接続が閉じたら、ループから削除されます。このような接続処理の方法により、 Nginx は限られた資源でも驚異的にスケールします。

<!-- source: https://www.digitalocean.com/community/tutorials/apache-vs-nginx-practical-considerations -->

<h2 id="requirements">システム要件</h2>

このページでは、HTTP サーバーとして *Nginx* を使い、 PHP スクリプトの実行に *PHP-FPM* （FastCGI Process Manager）を使って、 Grav を実行する方法を解説します。このため、これらのパッケージをあなたのサーバーにインストールしておいてください：

* `nginx`
* `php-fpm`

<h2 id="configuration">設定</h2>

もしあなたが Nginx の初心者で、block directives/context に関する基本的な理解がまだであれば、 Nginx の [ビギナーズガイド](http://nginx.org/en/docs/beginners_guide.html) を読むことをおすすめします。とくに、 [Configuration File’s Structure](http://nginx.org/en/docs/beginners_guide.html#conf_structure) と [Serving Static Content](http://nginx.org/en/docs/beginners_guide.html#static) のセクションが大事です。

今回の例では、あなたの Nginx 設定は `/etc/nginx/` にあり、 Grav は `/var/www/grav/` にインストールされているものとします。
設定の構造は、 `http` ブロックと、 1つもしくは複数の `server` ブロックからなります。 `http` ブロックは、 Nginx から提供されるすべてのページに関係する一般的なディレクティブを持ち、 `server` ブロックは、サイト特有のディレクティブを持ちます。
メインのサーバー設定ファイルは、 `nginx.conf` で、 `http` ブロックに保存されます。サイトに特有の設定（ `server` ブロック）は、 `sites-available` とシムリンクされた `sites-enabled` に保存されます。

<h3 id="file-permissions">ファイルのパーミッション</h3>

`/var/www` ディレクトリとそこに含まれるすべてのファイルやフォルダは、 `$USER:www-data` （もしくは Nginx の user/group 名）の所有になっているべきです。 [トラブルシューティングのパーミッション](../../../11.troubleshooting/05.permissions/) のセクションで、 Grav 向けのファイルとディレクトリのパーミッションのセットアップ方法を解説しています。このケースでは、 Group 共有を使っています。
基本的に、ディレクトリには `775` が、 Grav ディレクトリ内のファイルには `664` が必要です。これにより、 Grav がコンテンツを修正したり、自身をアップグレードできたりします。
あなたのユーザーを `www-data` グループに追加し、 Grav/Nginx によって作成されたファイルにアクセスできるようにしてください。

<h3 id="example-nginx-conf">nginx.conf の例</h3>

以下に示す設定は、デフォルトの `/etc/nginx/nginx.conf` ファイルを改良したもので、主に [github.com/h5bp/server-configs-nginx](https://github.com/h5bp/server-configs-nginx) から改良しています。
これらの設定についての解説は、彼らのリポジトリをご覧いただき、特定のディレクティブを探すには、 Nginx の [core module](http://nginx.org/en/docs/ngx_core_module.html) と [http module](http://nginx.org/en/docs/http/ngx_http_core_module.html) にあるドキュメントをご覧ください。

> [!Info]  
> [github.com/h5bp/server-configs-nginx](https://github.com/h5bp/server-configs-nginx) から、MIME タイプの定義ファイル（ `mime.types` ）をアップデートすることをおすすめします。これは gzip 圧縮でタイプを正しく設定するようにしてくれます。

**nginx.conf**:

```nginx
user www-data;
worker_processes auto;
worker_rlimit_nofile 8192; # should be bigger than worker_connections
pid /run/nginx.pid;

events {
    use epoll;
    worker_connections 8000;
    multi_accept on;
}

http {
    sendfile on;
    tcp_nopush on;
    tcp_nodelay on;

    keepalive_timeout 30; # longer values are better for each ssl client, but take up a worker connection longer
    types_hash_max_size 2048;
    server_tokens off;

    # maximum file upload size
    # update 'upload_max_filesize' & 'post_max_size' in /etc/php/fpm/php.ini accordingly
    client_max_body_size 32m;
    # client_body_timeout 60s; # increase for very long file uploads

    # set default index file (can be overwritten for each site individually)
    index index.html;

    # load MIME types
    include mime.types; # get this file from https://github.com/h5bp/server-configs-nginx
    default_type application/octet-stream; # set default MIME type

    # logging
    access_log /var/log/nginx/access.log;
    error_log /var/log/nginx/error.log;

    # turn on gzip compression
    gzip on;
    gzip_disable "msie6";
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 5;
    gzip_buffers 16 8k;
    gzip_http_version 1.1;
    gzip_min_length 256;
    gzip_types
        application/atom+xml
        application/javascript
        application/json
        application/ld+json
        application/manifest+json
        application/rss+xml
        application/vnd.geo+json
        application/vnd.ms-fontobject
        application/x-font-ttf
        application/x-web-app-manifest+json
        application/xhtml+xml
        application/xml
        font/opentype
        image/bmp
        image/svg+xml
        image/x-icon
        text/cache-manifest
        text/css
        text/javascript
        text/plain
        text/vcard
        text/vnd.rim.location.xloc
        text/vtt
        text/x-component
        text/x-cross-domain-policy;

    # disable content type sniffing for more security
    add_header "X-Content-Type-Options" "nosniff";

    # force the latest IE version
    add_header "X-UA-Compatible" "IE=Edge";

    # enable anti-cross-site scripting filter built into IE 8+
    add_header "X-XSS-Protection" "1; mode=block";

    # include virtual host configs
    include sites-enabled/*;
}
```

<h3 id="grav-site-configuration">Grav サイト設定</h3>

ダウンロードした Grav には、Grav インストールの `webserver-configs` ディレクトリにサイト設定ファイルがあります。このファイルを nginx config ディレクトリにコピーできます：

```bash
cp /var/www/grav/webserver-configs/nginx.conf /etc/nginx/sites-available/grav-site
```

そのファイルをエディタで開き、 "example.com" をあなたのドメイン/IP（もしくはローカルで実行するなら "localhost"）に書き換えてください。さらに、 "root" 行を "root /var/www/grav/;" に書き換え、その後 `sites-enabled` にある site-config のシンボリックリンクを作成してください：

```bash
ln -s /etc/nginx/sites-available/grav-site /etc/nginx/sites-enabled/grav-site
```

<!--
!! It is recommended to use the file `expires.conf` from [github.com/h5bp/server-configs-nginx](https://github.com/h5bp/server-configs-nginx) (in the directory `h5bp/location/`). It will set "Expires" headers for different file types, so the browser can cache them. Save the file somewhere in your `/etc/nginx/` directory and include it in your site config, e.g. before the first location directive in `/etc/nginx/sites-available/grav-site`.
-->

最後に、 Nginx を新しい設定でリロードしてください：

```bash
nginx -s reload
```

<h3 id="fix-against-httpoxy-vulnerability">httpoxy 脆弱性の修正</h3>

> httpoxy is a set of vulnerabilities that affect application code running in CGI, or CGI-like environments.  
> (Source: [httpoxy.org](https://httpoxy.org))  

（httpoxy とは、CGI や CGI に似た環境で実行されるアプリケーションコードに影響を与える脆弱性一式です。）

この脆弱性からサイトを守るには、 `Proxy` ヘッダーをブロックするべきです。これは、 FastCGI パラメータを設定に追加することで可能です。
`/etc/nginx/fastcgi.conf` ファイルを開き、末尾に次の行を追記するだけです：

```nginx
fastcgi_param  HTTP_PROXY         "";
```

<h3 id="">（既存の証明書で） SSL を使う</h3>

既存の SSL 証明書で、web サイトのトラフィックを暗号化したい場合、そのための Nginx 設定の修正方法について、このセクションで必要な手順を解説します。

まず、 `/etc/nginx/ssl.conf` ファイルを作成してください。内容を以下に示すように書き、パスなどはあなたの証明書やキーファイルに調整してください。
最後のセクションは、 OSCP stapling に関するもので、chain+root 証明書を提供する必要があります。
もしそれを望まない場合、 `# OCSP stapling` 以下のすべてをコメントアウトするか、削除してください。
もしサイトが（サブドメインも含めて） SSL のみであれば、ブラウザでのプレローディング用に <https://hstspreload.appspot.com> にあなたのサイトを送信できます。そうでない場合は、 "Strict-Transport-Security" を追加する行から、 `preload;` を削除できます。
これらのオプションがすべてあなたのセットアップで機能するかどうか、確認してください。

**ssl.conf**:

```nginx
# set the paths to your cert and key files here
ssl_certificate /etc/ssl/certs/example.com.crt;
ssl_certificate_key /etc/ssl/private/example.com.key;

ssl_protocols TLSv1 TLSv1.1 TLSv1.2;

ssl_ciphers ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-AES256-GCM-SHA384:DHE-RSA-AES128-GCM-SHA256:DHE-DSS-AES128-GCM-SHA256:kEDH+AESGCM:ECDHE-RSA-AES128-SHA256:ECDHE-ECDSA-AES128-SHA256:ECDHE-RSA-AES128-SHA:ECDHE-ECDSA-AES128-SHA:ECDHE-RSA-AES256-SHA384:ECDHE-ECDSA-AES256-SHA384:ECDHE-RSA-AES256-SHA:ECDHE-ECDSA-AES256-SHA:DHE-RSA-AES128-SHA256:DHE-RSA-AES128-SHA:DHE-DSS-AES128-SHA256:DHE-RSA-AES256-SHA256:DHE-DSS-AES256-SHA:DHE-RSA-AES256-SHA:ECDHE-RSA-DES-CBC3-SHA:ECDHE-ECDSA-DES-CBC3-SHA:AES128-GCM-SHA256:AES256-GCM-SHA384:AES128-SHA256:AES256-SHA256:AES128-SHA:AES256-SHA:AES:CAMELLIA:DES-CBC3-SHA:!aNULL:!eNULL:!EXPORT:!DES:!RC4:!MD5:!PSK:!aECDH:!EDH-DSS-DES-CBC3-SHA:!EDH-RSA-DES-CBC3-SHA:!KRB5-DES-CBC3-SHA;
ssl_prefer_server_ciphers on;

ssl_session_cache shared:SSL:10m; # a 1mb cache can hold about 4000 sessions, so we can hold 40000 sessions
ssl_session_timeout 24h;

# Use a higher keepalive timeout to reduce the need for repeated handshakes
keepalive_timeout 300s; # up from 75 secs default

# submit domain for preloading in browsers at: https://hstspreload.appspot.com
add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload;";

# OCSP stapling
# nginx will poll the CA for signed OCSP responses, and send them to clients so clients don't make their own OCSP calls.
# see https://sslmate.com/blog/post/ocsp_stapling_in_apache_and_nginx on how to create the chain+root
ssl_stapling on;
ssl_stapling_verify on;
ssl_trusted_certificate /etc/ssl/certs/example.com.chain+root.crt;
resolver 198.51.100.1 198.51.100.2 203.0.113.66 203.0.113.67 valid=60s;
resolver_timeout 2s;
```

次に、 Grav 特有の設定 `/etc/nginx/sites-available/grav-site` のコンテンツを変更します。これは暗号化されていない HTTP リクエストを HTTPS にリダイレクトするための変更で、 `server` ブロックが 443 ポートをリッスンし、（"example.com" をあなたのドメイン/IP に置き換えた） `ssl.conf` を含めることを意味します。
同時に、あなたのドメインの www でないバージョンから、www へリダイレクトする変更もできます。

**grav-site**:

```nginx
# redirect http to non-www https
server {
    listen [::]:80;
    listen 80;
    server_name example.com www.example.com;

    return 302 https://example.com$request_uri;
}

# redirect www https to non-www https
server {
    listen [::]:443 ssl;
    listen 443 ssl;
    server_name www.example.com;

    # add ssl cert & options
    include ssl.conf;

    return 302 https://example.com$request_uri;
}

# serve website
server {
    listen [::]:443 ssl;
    listen 443 ssl;
    server_name example.com;

    # add ssl cert & options
    include ssl.conf;

    root /var/www/example.com;

    index index.html index.php;

    # ...
    # the rest of this server block (location directives) is identical to the one from the shipped config
}
```

最後に、 Nginx の設定をリロードします：

```bash
nginx -s reload
```

<!-- TODO: ### Using a Let's Encrypt SSL certificate -->

<h2 id="nginx-cache-headers-for-assets">アセット用の Nginx キャッシュヘッダー</h2>

本番環境では、キャッシュの有効化もおすすめします。
以下の内容を設定ファイルに追加すると、キャッシュの制御ができるようになります。
'expires' はキャッシュの有効期限を定義し、この例では 30 日です。
Nginx の HTTP ヘッダーに関する完全なドキュメントをお読みください。
[`https://nginx.org/en/docs/http/ngx_http_headers_module.html`](https://nginx.org/en/docs/http/ngx_http_headers_module.html)

```nginx
        location ~* ^/forms-basic-captcha-image.jpg$ {
                try_files $uri $uri/ /index.php$is_args$args;
        }

        location ~* \.(?:ico|css|js|gif|jpe?g|png)$ {
                expires 30d;
                add_header Vary Accept-Encoding;
                log_not_found off;
        }

        location ~* ^.+\.(?:css|cur|js|jpe?g|gif|htc|ico|png|html|xml|otf|ttf|eot|woff|woff2|svg)$ {
                access_log off;
                expires 30d;
                add_header Cache-Control public;

## No need to bleed constant updates. Send the all shebang in one
## fell swoop.
                tcp_nodelay off;

## Set the OS file cache.
                open_file_cache max=3000 inactive=120s;
                open_file_cache_valid 45s;
                open_file_cache_min_uses 2;
                open_file_cache_errors off;
        }
```

