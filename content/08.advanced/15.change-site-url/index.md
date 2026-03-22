---
title: 'サイト URL の変更'
lastmod: 2026-03-22T17:33:10+09:00
description: 'Grav が実際にはインストールされていないディレクトリで、 Grav を実行する方法を解説します。 Grav 側の system 設定と、web サーバー側のリダイレクト設定を組み合わせます。'
weight: 150
params:
    srcPath: /advanced/change-site-url
---

system.yaml の `custom_base_url` を設定することで（もしくは、管理パネルの System 設定の Custom Base URL を設定することで）、フォルダ内の Grav をドメインルートにあるものとして実行できます。

## シナリオ1：ドメインルートディレクトリで実行{#scenario-1-run-in-the-domain-root-folder}

Grav は、 `http://localhost:8080/grav-develop` にインストールされているが、 `http://localhost:8080` で実行したい場合

system.yaml で、次のように設定してください：

```yaml
custom_base_url: 'http://localhost:8080'
```

そして、制作中の Grav サイトのパスに session path を設定してください：

```yaml
session:
  path: /
```

そして、ドメインのルートディレクトリで、リダイレクトを設定してください。例： .htaccess に、次のように：

```txt
RewriteEngine On
RewriteCond %{REQUEST_URI} !^/grav-develop/
RewriteRule ^(.*)$ /grav-develop/$1
```

上記の `grav-develop` とは、 Grav の存在するサブディレクトリを指します。

## シナリオ2：異なるサブディレクトリで実行{#scenario-2-run-in-a-different-subfolder}

Grav は `http://localhost:8080/grav-develop` にインストールされているが、 `http://localhost:8080/xxxxx` で実行したい場合：

system.yaml に、次のように設定してください：

```yaml
custom_base_url: 'http://localhost:8080/xxxxx'
```

そして、制作中の Grav のサイトパスに session path を設定してください：

```yaml
session:
  path: /xxxxx
```

そして、新しいルートフォルダ（`/xxxxx`）にて、リダイレクトを設定してください。例： .htaccess に、次のように設定：

```txt
RewriteEngine On
RewriteCond %{REQUEST_URI} !^/grav-develop/
RewriteRule ^(.*)$ /grav-develop/$1
```

`grav-develop` とは、 Grav の存在する姉妹サブディレクトリを指します。

