---
title: 推奨するセキュリティ設定
layout: ../../../layouts/Default.astro
lastmod: '2025-05-14'
---
他のすべてのアプリケーションと同様、サイトを保護し、最適化するために config 設定をチェックするのは重要です。

<h2 id="production-site">本番サイト</h2>

config 設定の強化により、本番環境のサイトのセキュリティを強化するのは重要です。これを実行するには、 `user/config/` にメインの config 設定を設定し、本番環境で利用したいデフォルト設定をします。そのうえで、開発環境ではこれらを上書きすることをおすすめします。 たとえば、 `user/env/localhost` もしくは `user/env/site.local` に上書きします。また、環境変数により、本番サイト設定を上書きすることもできます。たとえば、複数のドメインにまたがるマルチサイトで可能です。

<h3 id="system-configuration-user-config-system-yaml">システム設定（`user/config/system.yaml`）</h3>

```yaml
force_ssl: true       # Use HTTPS only (redirect from HTTP -> HTTPS)

cache:
  enabled: true       # Greatly speeds up the site
  check:
    method: hash      # Optimization, disables file modification checks for pages

twig:
  cache: true         # Greatly speeds up the site
  debug: false        # We do not want to display debug messages
  auto_reload: false  # Optimization, disables file modification checks for twig files
  autoescape: true    # Protects from many XSS attacks, but requires twig updates if used in older sites/themes/plugins

errors:
  display: 0          # Display only a simple error
  log: true           # Log errors for later inspection

debugger:
  enabled: false      # Never keep debugger enabled in a live site.
  censored: true      # In case if you happen to enable debugger, avoid displaying sensitive information

session:
  enabled: true       # NOTE: Disable sessions if you do not use user login and/or forms.
  secure: true        # Use this as your site should be using HTTPS only
  httponly: true      # Protects session cookies against client side scripts and XSS
  samesite: Strict    # Prevent all cross-site scripting attacks
  split: true         # Separate admin session from the site session for added security

strict_mode:          # Test your site before changing these. Removes backward compatibility and improves site security.
  yaml_compat: false
  twig_compat: false
  blueprint_compat: false
```

<h2 id="development-site">開発サイト</h2>

開発サーバーのために、サイトの更新をより便利にする設定がいくつかあります。

<h3 id="system-configuration-user-env-localhost-config-sys">システム設定（`user/env/localhost/config/system.yaml`）</h3>

> [!Tip]  
> localhost をあなたのローカルサーバー名に書きかえてください。

```yaml
force_ssl: false      # If the development site doesn't use SSL

cache:
  enabled: true       # Still keep cache enabled
  check:
    method: file      # Allow updating pages without clearing cache

twig:
  cache: true         # Still keep cache enabled
  debug: true         # We want to display debug messages
  auto_reload: true   # We may be editing twig files

errors:
  display: 1          # Display full backtrace if there are errors

debugger:
  enabled: true       # Debugger is handy to have
  censored: false     # We may want to see secure content in debugger

session:
  secure: false       # If the development site doesn't use SSL
  httponly: false     # If the development site doesn't use SSL

strict_mode:          # These settings help you to keep your site updated to use the latest standards
  yaml_compat: false
  twig_compat: false
  blueprint_compat: false
```
