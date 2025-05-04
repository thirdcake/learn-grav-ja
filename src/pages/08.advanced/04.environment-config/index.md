---
title: "環境設定"
layout: ../../../layouts/Default.astro
---

Grav では、 **開発環境** や、 **ステージング環境** 、 **本番環境** のような、異なる環境に異なる設定をサポートするため、 [強力な設定機能](../../01.basics/05.grav-configuration) を拡張できます。

> [!Info]  
> Grav 1.6 までは、環境変数は `user/` フォルダに保存していました。 Grav 1.7 では、環境変数を `user/env/` に移し、メンテナンスしやすくしました。既存のサイトでは、この新しい場所に環境変数を移し替えることを強く推奨します。

<h3 id="automatic-environment-configuration">自動的な環境設定</h3>


What this means is that you can provide as little or as much configuration changes per environment as needed.  A good example of this is the [Debug Bar](../debugging).  By default, the new Debug Bar is disabled in the core `system/config/system.yaml` file, and also in the user override file:

```bash
user/config/system.yaml
```

If you wanted to turn it on, you can easily enable it in your `user/config/system.yaml` file, however a better solution might be to have it _enabled_ for your development environment when accessing via **localhost**, but _disabled_ on your **production** server.

This can be easily accomplished by providing an override of that setting in the file:

```bash
user/env/localhost/config/system.yaml
```

where `localhost` is the hostname of the environment (this is what the host you enter in your browser, e.g. http://localhost/your-site) and your configuration file contains:

```yaml
debugger:
  enabled: true
```

Similarly, you may want to enable CSS, Link, JS and JS Module Asset Pipelining (combining + minification) for your production site only
(`user/env/www.mysite.com/config/system.yaml`):

```yaml
assets:
  css_pipeline: true
  js_pipeline: true
  js_module_pipeline: true
```

If your production server was reachable via `http://www.mysite.com` then you could also provide configuration specific for that production site with a file located at
`user/env/www.mysite.com/config/system.yaml`.

Of course, you are not limited to changes to `system.yaml`, you can actually provide overrides for **any** Grav setting in the `site.yaml` or even in any [plugin configuration](../../04.plugins/01.plugin-basics/)!

!! If you are using the Grav [Scheduler](../06.scheduler/), be aware of it using the `localhost` environment and therefore its configuration.

#### Plugin Overrides

To override a plugin configuration YAML file is simply the same process as overriding a regular file.   If the standard configuration file is located in:

```bash
user/config/plugins/email.yaml
```

Then you can override this with a setting that only overrides specific options that you want to use for local testing:

```bash
user/env/localhost/config/plugins/email.yaml
```

With the configuration:

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

#### Theme Overrides

You can override themes in much the same way:

```bash
user/config/themes/antimatter.yaml
```

Can be overridden for any environment, say some production site (`http://www.mysite.com`):

```bash
user/env/www.mysite.com/config/themes/antimatter.yaml
```

### Server Based Environment Configuration

Starting from Grav 1.7, it is possible to set the environment by using server configuration. In this use scenario, you set environment variables from the server or from a script that runs before Grav to select the environment to be used.

The simplest way to set environment is by using `GRAV_ENVIRONMENT`. Value of `GRAV_ENVIRONMENT` has to be a valid server name with or without domain.

The following example selects **development** environment for the localhost:

```apacheconf
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

### Custom Environment Paths

Starting from Grav 1.7, you can also change the location of the environments. There are two possibilities: either you configure a common location for all the environments or you define them one by one.

#### Custom location for all the environments

If for some reason you are not happy with the default `user/env` location for your environments, it can be changed by using `GRAV_ENVIRONMENTS_PATH` environment variable.

Value of `GRAV_ENVIRONMENTS_PATH` has to be existing path under `GRAV_ROOT`. Do not use trailing slash.

In the next example, all the environments will be located in `user/sites/GRAV_ENVIRONMENT`, where `GRAV_ENVIRONMENT` is either automatically detected or manually set in the server configuration:

```apacheconf
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



```apacheconf
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


