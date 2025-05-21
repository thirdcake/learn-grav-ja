---
title: 'サイト URL の変更'
layout: ../../../layouts/Default.astro
lastmod: '2025-05-08'
---
system.yaml の `custom_base_url` を設定することで（もしくは、管理パネルの System 設定の Custom Base URL を設定することで）、フォルダ内の Grav をドメインルートにあるものとして実行できます。

## Scenario 1, run in the domain root folder

Grav is installed in `http://localhost:8080/grav-develop` but you want it to respond on `http://localhost:8080`

In system.yaml, set

```yaml
custom_base_url: 'http://localhost:8080'
```

and set the session path to the new Grav site path,

```yaml
session:
  path: /
```

And in the domain root, set the redirect, e.g. with .htaccess:

```txt
RewriteEngine On
RewriteCond %{REQUEST_URI} !^/grav-develop/
RewriteRule ^(.*)$ /grav-develop/$1
```

where `grav-develop` is the subfolder where Grav is.

## Scenario 2, run in a different subfolder

Grav is installed in `http://localhost:8080/grav-develop` but you want it to respond on `http://localhost:8080/xxxxx`

In system.yaml, set

```yaml
custom_base_url: 'http://localhost:8080/xxxxx'
```

and set the session path to the new Grav site path,

```yaml
session:
  path: /xxxxx
```

And in the new root folder, /xxxxx, set the redirect, e.g. with .htaccess:

```txt
RewriteEngine On
RewriteCond %{REQUEST_URI} !^/grav-develop/
RewriteRule ^(.*)$ /grav-develop/$1
```

where `grav-develop` is the sister subfolder where Grav is.

