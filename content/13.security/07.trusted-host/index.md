---
title: '信頼できるホスト名'
lastmod: '2026-06-23T19:12:51+09:00'
description: 'パスワードリセットや EMail 有効化のために信頼できるホスト名が設定されていない場合に、Grav が警告を表示する理由を説明し、いろいろな web サーバーでの解決方法を解説します'
weight: 70
params:
    srcPath: '/security/trusted-host'
---


Admin パネルで、以下のような警告を見た場合：

> Email links are not yet pinned to a trusted host.

このページでは、この警告の意味と、問題の理由、及びそれを解消する設定変更方法について解説します。  
これは強く推奨される設定であり、サイトが壊れていることを警告するものではありません。

## 警告の意味{#what-the-warning-means}

Grav が、 **パスワードリセット** メールや、 **アカウント有効化** メール、 **magic-login** メールを送信するとき、メール内のリンクに完全な web アドレスを書く必要があります。 例：`https://www.example.com/reset/...`  
このアドレスを組み立てるため、 Grav はサイトのホスト名（`www.example.com`） を知る必要があります。

Grav にホスト名を設定しなかった場合、 Grav は、受け取った `Host` リクエストヘッダーを読取ります。このヘッダーは、利用者のブラウザーから送られるもので、悪意のある訪問者は、これを好きなように変更できてしまいます。

このため、攻撃者が、サイト利用者のひとりにパスワードリセットメールをリクエストし、偽造 `Host` ヘッダーを送信した場合、メール中のリセットリンクは、本来のサイトではなく、よく似たフィッシングサイトへ誘導できてしまいます。  
利用者がこれをクリックし、攻撃者がワンタイムトークンをキャプチャすると、攻撃者はアカウントを乗っ取ることができます。  
[GHSA-46jp-rc59-w2gc](https://github.com/getgrav/grav-plugin-login/security/advisories) で追跡できます。

この警告を、シンプルに言うと： **EMail リンクに信頼できるホストが設定されていないので、 Grav がリクエストヘッダーのホストを使います**

## 修正方法{#the-fix}

どのホストを使っているか、 Grav に設定します。  
以下の2つのオプションから、 **いずれか** を設定するだけで十分です。  
いずれかを設定すると、全てのセキュリティメールリンクにホスト名が設定され、警告が解消されます。

### オプション1: カスタムベースURL（推奨）{#option-1-custom-base-url-recommended}

これが最も包括的な修正方法です。  
信頼できるベースアドレスを、メールに限らずサイト全体に設定するので、他の絶対リンクも一貫して修正されます。

Admin パネルで、 **Configuration → System → Advanced → Custom Base URL** と進み、完全なサイトアドレスを次のように入力してください：

```txt
https://www.example.com
```

もしくは、直接 `user/config/system.yaml` に設定してください：

```yaml
custom_base_url: 'https://www.example.com'
```

### Option 2: Site Host in the Login plugin

If you only want to pin the email links and leave everything else alone, use the Login plugin's own setting instead.

In the admin, go to **Plugins → Login → Site Host** and enter your full site address, for example:

```txt
https://www.example.com
```

Or set it directly in `user/config/plugins/login.yaml`:

```yaml
site_host: 'https://www.example.com'
```

> [!NOTE]
> If you run the same site under more than one address (for example a staging and a production domain, or multiple languages on separate domains), pick the canonical public address your users should always see in their email.

## Refusing to send when no host is set

For sites where account security is critical, the Login plugin can go a step further and **refuse to send** password reset emails at all unless a trusted host is configured, rather than falling back to the request host.

Enable **Plugins → Login → Require Trusted Host**, or in `user/config/plugins/login.yaml`:

```yaml
require_trusted_host: true
```

With this on, a misconfigured site fails safe (no email is sent) instead of sending a potentially spoofable link.

## Defense in depth: validate the host at the web server

Setting a Custom Base URL fixes the email links, but it is good practice to also reject forged `Host` headers at the web server before they ever reach Grav. That way every part of your stack agrees on which host names are legitimate.

The examples below reject any request whose `Host` header is not one you expect.

### Apache

In your virtual host or `.htaccess`, allow only your known hosts:

```apache
RewriteEngine On
RewriteCond %{HTTP_HOST} !^(www\.example\.com|example\.com)$ [NC]
RewriteRule ^ - [F]
```

Better still, define an explicit `ServerName` and `ServerAlias` in the virtual host and let Apache serve unknown hosts from a separate default virtual host that returns an error.

### Nginx

Give your real `server` block an exact `server_name`, then add a catch-all default that rejects everything else:

```nginx
server {
    listen 80 default_server;
    server_name _;
    return 444;            # close the connection on an unknown host
}

server {
    listen 80;
    server_name www.example.com example.com;
    # ... your normal Grav configuration ...
}
```

### Caddy

Caddy only answers for the host names listed in a site block, so an exact address already rejects forged hosts:

```caddy
www.example.com, example.com {
    root * /var/www/grav
    php_fastcgi unix//run/php/php-fpm.sock
    file_server
}
```

### Behind a proxy or CDN

If Grav runs behind a load balancer, reverse proxy, or CDN (such as Cloudflare), make sure the proxy forwards the real, validated host and that Grav is configured to trust it. See the `reverse_proxy_setup` and `http_x_forwarded` options in [Grav Configuration](/basics/grav-configuration) for the related settings.

## Confirming it is resolved

after you save a Custom Base URL or Site Host, the admin warning disappears on the next page load. You can also send yourself a password reset and confirm the link in the email points at your real domain.
