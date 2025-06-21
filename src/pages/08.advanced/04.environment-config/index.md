---
title: 環境設定
layout: ../../../layouts/Default.astro
lastmod: '2025-06-21'
---

Grav では、 **開発環境** や、 **ステージング環境** 、 **本番環境** のような、異なる環境に異なる設定をサポートするため、 [強力な設定機能](../../01.basics/05.grav-configuration) を拡張できます。

> [!Info]  
> Grav 1.6 までは、環境変数は `user/` フォルダに保存していました。 Grav 1.7 では、環境変数を `user/env/` に移し、メンテナンスしやすくしました。既存のサイトでは、この新しい場所に環境変数を移し替えることを強く推奨します。

<h3 id="automatic-environment-configuration">自動的な環境設定</h3>

ここでの意味は、環境ごとに、必要な分だけ設定の変更を提供できるということです。この良い例として、[デバッグバー](../03.debugging/) があります。デフォルトでは、新しいデバッグバーは、コアの `system/config/system.yaml` ファイルでは無効化されており、ユーザーファイルでの上書きも同様です：

```bash
user/config/system.yaml
```

もしこれを有効化したい場合は、 `user/config/system.yaml` ファイルで簡単に有効にすることができます。しかし、より良い解決策は、 **localhost** 経由でのアクセス時の開発環境で _有効_ となり、 **本番** サーバーでは _無効_ になることかもしれません。

これは、簡単に実現可能です。次のファイルに、上書き設定をします：

```bash
user/env/localhost/config/system.yaml
```

`localhost` が環境のホスト名（ブラウザで入るホスト、たとえば `http://localhost/your-site`）であり、設定ファイルには次のように書いてください：

```yaml
debugger:
  enabled: true
```

同様に、 CSS や Link, JS と JS モジュールなどのアセットパイプライン（結合+ミニファイ）を本番サイトでのみ有効化したいと思うかもしれません： 

```yaml
assets:
  css_pipeline: true
  js_pipeline: true
  js_module_pipeline: true
```

本番サーバーが `http://www.mysite.com` でアクセスできる場合、 `user/env/www.mysite.com/config/system.yaml` にあるファイルで、本番サイト固有の設定を提供できます。

もちろん、`system.yaml` への変更に限ったものではありません。 `site.yaml` ファイルへの **あらゆる** Grav 設定も上書きできますし、あらゆる [プラグイン設定](../../04.plugins/01.plugin-basics/) さえ可能です！

> [!Info]  
> Grav の [スケジューラー](../06.scheduler/) を利用している場合、 `localhost` 環境を使うため、その設定に注意してください。

> [!訳注]  
> スケジューラーは、ホスト名を持たないため、 `localhost` 環境として実行されるそうです。

<h4 id="plugin-overrides">プラグインの上書き</h4>

プラグインの config 設定の YAML ファイルを上書きするには、通常ファイルを上書きするのと同じ処理です。標準的な設定ファイルが、次の場所に置かれている場合：

```bash
user/config/plugins/email.yaml
```

その時は、ローカルテストでのみ上書きする設定を、次の場所で上書きできます：

```bash
user/env/localhost/config/plugins/email.yaml
```

設定は、以下のようにします：

```yaml
mailer:
  engine: smtp
  smtp:
    server: smtp.mailtrap.io
    port: 2525
    encryption: none
    user: '9a320798e65135'
    password: 'a13e6e27bc7205'
```

<h4 id="theme-overrides">テーマの上書き</h4>

テーマについても、同じ方法で上書きできます：

```bash
user/config/themes/antimatter.yaml
```

あらゆる環境に対して上書き可能です。たとえば、本番サイトが `http://www.mysite.com` のとき：

```bash
user/env/www.mysite.com/config/themes/antimatter.yaml
```

<h3 id="server-based-environment-configuration">サーバーベースの環境設定</h3>

Grav 1.7 以降、サーバーの設定を使って環境を設定することができるようになりました。想定される利用場面としては、サーバーから、もしくはスクリプトから環境変数を設定します。ここでのスクリプトというのは、 Grav よりも前に実行され、Grav が使う環境を選択するためのものです。

環境を設定する最も簡単な方法は、 `GRAV_ENVIRONMENT` を使うことです。 `GRAV_ENVIRONMENT` の値は、ドメイン付きもしくはドメイン無しの適切なサーバー名でなければいけません。

以下の例は、localhost 向けに **development** 環境を選択します：

```txt
<VirtualHost 127.0.0.1:80>
    ...

    SetEnv GRAV_ENVIRONMENT development
</VirtualHost>
```

```nginx
location / {
    ...

   fastcgi_param GRAV_ENVIRONMENT development;
}
```

```nginx
location / {
   ...

    env[GRAV_ENVIRONMENT] = development
}
```

```yaml
web:
  environment:
    - GRAV_ENVIRONMENT=development
```

```php
// Set environment in setup.php or make sure it runs before Grav.
define('GRAV_ENVIRONMENT', 'development');
```

<h3 id="custom-environment-paths">カスタム環境パス</h3>

Starting from Grav 1.7, you can also change the location of the environments. There are two possibilities: either you configure a common location for all the environments or you define them one by one.

#### Custom location for all the environments

If for some reason you are not happy with the default `user/env` location for your environments, it can be changed by using `GRAV_ENVIRONMENTS_PATH` environment variable.

Value of `GRAV_ENVIRONMENTS_PATH` has to be existing path under `GRAV_ROOT`. Do not use trailing slash.

In the next example, all the environments will be located in `user/sites/GRAV_ENVIRONMENT`, where `GRAV_ENVIRONMENT` is either automatically detected or manually set in the server configuration:

```txt
<VirtualHost 127.0.0.1:80>
...

    SetEnv GRAV_ENVIRONMENTS_PATH user://sites
</VirtualHost>
```

```nginx
location / {
    ...

fastcgi_param GRAV_ENVIRONMENTS_PATH user://sites;
}
```

```nginx
location / {
...

    env[GRAV_ENVIRONMENTS_PATH] = user://sites
}
```

```yaml
web:
  environment:
    - GRAV_ENVIRONMENTS_PATH=user://sites
```

```php
// Set environments path in setup.php or make sure that the following code runs before Grav.
define('GRAV_ENVIRONMENTS_PATH', 'user://sites');
```

#### Custom location for the current environment

Sometimes it may be useful to have a custom location for your environment

Value of `GRAV_ENVIRONMENT_PATH` has to be existing path under `GRAV_ROOT`. Do not use trailing slash.

In the next example, only the current environment will be located in `user/development`:



```txt
<VirtualHost 127.0.0.1:80>
...

    SetEnv GRAV_ENVIRONMENT_PATH user://development
</VirtualHost>
```

```nginx
location / {
    ...

fastcgi_param GRAV_ENVIRONMENT_PATH user://development;
}
```

```nginx
location / {
...

    env[GRAV_ENVIRONMENT_PATH] = user://development
}
```

```yaml
web:
  environment:
    - GRAV_ENVIRONMENT_PATH=user://development
```

```php
// Set environment path in setup.php or make sure that the following code runs before Grav.
define('GRAV_ENVIRONMENT_PATH', 'user://development');
```



Note that `GRAV_ENVIRONMENT_PATH` is separate from `GRAV_ENVIRONMENT`, so you may also want to set the environment name if you don't want it to be automatically set to match the current domain name.

### Further Customization

Environments can be customized far further than described in this page.

For more information, please continue to the next page: [Multisite Setup](../05.multisite-setup/).


