---
title: "マルチサイト設定"
layout: ../../../layouts/Default.astro
---

> [!Info]  
> Grav は暫定的にマルチサイトをサポートしています。 しかし、マルチサイト設定を完全にサポートするには、管理パネルプラグインに、さらなるアップデートが必要です。Grav の今後のリリースで、引き続き取り組んでいきます。

<h3 id="what-is-a-multisite-setup">マルチサイト設定とは？</h3>

マルチサイト設定とは、1つの Grav のインストールで、複数の web サイトネットワークを作成したり、管理したりできるようにする設定です。

Grav は、組み込みでマルチサイトをサポートしています。この機能は、本番サイトと開発サイトでカスタム環境変数を定義できる [基本的な環境設定](../04.environment-config/) を拡張しています。

完全なマルチサイト設定により、そのファイルすべてをどこから、どのように読み込むかを変更することができます。

<h3 id="requirements-for-a-grav-multisite-setup">Grav マルチサイト設定の要件</h3>

Grav で複数のサイトネットワーク運営するために最も重要なのは、良い web サイトホスティング環境です。多くのサイトを作成しようと考えていなければ、そして多くの閲覧者を想定していなければ、レンタルサーバーでも大丈夫です。しかし、マルチサイトの性質上、サイトが増えたり、成長するにつれて、 VPS や専用サーバーが必要になるでしょう。

<h3 id="setup-and-installation">セットアップとインストール</h3>

始める前に、あなたの web サーバーが、複数の web サイトを運用できるかどうか確認をしてください。つまり、 Grav の root ディレクトリへのアクセス権が必要です。

1つの同じインストールから、複数の web サイトを提供するのは、 Grav の root ディレクトリに置いた `setup.php` ファイルをベースとして行われるので、これは必須です。

#### Quickstart (for Beginners)

一度作成すれば、 `setup.php` は、ユーザーがページをリクエストするたびに呼ばれます。1つのインストールから複数の web サイトを提供するために、（大まかに言えば）このスクリプトは、 Grav に特定のサブサイトのためのファイル（設定ファイル、テーマファイル、プラグインファイル、ページファイル、その他のファイル）が、どこに置かれているのかを伝えなければいけません。

以下のスニペットは、左のようなリクエストが来たときに、右の対応方法をするように Grav のインストールにセットアップします。

```txt
https://<subsite>.example.com   -->   user/env/<subsite>.example.com
```

もしくは

```txt
https://example.com/<subsite>   -->   user/env/<subsite>
```

`user` ディレクトリではなく、その中の `user/env` ディレクトリを使ってください。

サブサイトに、サブディレクトリや path ベースの URL を選んだ場合、 `user/env` ディレクトリの中に、サブサイトごとにディレクトリを作ることだけが必要です。そのディレクトリには、少なくとも  `config`, `pages`, `plugins`, そして `themes` フォルダが必要です。

web サイトネットワーク構造にサブドメインを選んだ場合、 `user/env` ディレクトリにサブサイトの設定をした上で、サーバー上に（ワイルドカード）サブドメインを設定しなければいけません。

どちらの方法でも、あなたのベストなセットアップを選んでください。

<h5 id="snippets">スニペット</h5>

For subsites accessible via sub-domains copy the `setup_subdomain.php` file, otherwise for subsites accessible via sub-directories the `setup_subdirectory.php` file into your `setup.php`.

!!! The `setup.php` file must be put in the Grav root folder, the same folder where you can find `index.php`, `README.md` and the other Grav files.

**setup_subdomain.php**:

```php
<?php
/**
 * Multisite setup for subsites accessible via sub-domains.
 *
 * DO NOT EDIT UNLESS YOU KNOW WHAT YOU ARE DOING!
 */

use Grav\Common\Utils;

// Get subsite name from sub-domain
$environment = isset($_SERVER['HTTP_HOST'])
    ? $_SERVER['HTTP_HOST']
    : (isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost');
// Remove port from HTTP_HOST generated $environment
$environment = strtolower(Utils::substrToString($environment, ':'));
$folder = "env/{$environment}";

if ($environment === 'localhost' || !is_dir(ROOT_DIR . "user/{$folder}")) {
    return [];
}

return [
    'environment' => $environment,
    'streams' => [
        'schemes' => [
            'user' => [
               'type' => 'ReadOnlyStream',
               'prefixes' => [
                   '' => ["user/{$folder}"],
               ]
            ]
        ]
    ]
];
```

**setup_subdirectory.php**:

```php
<?php
/**
 * Multisite setup for sub-directories or path based
 * URLs for subsites.
 *
 * DO NOT EDIT UNLESS YOU KNOW WHAT YOU ARE DOING!
 */

use Grav\Common\Filesystem\Folder;

// Get relative path from Grav root.
$path = isset($_SERVER['PATH_INFO'])
   ? $_SERVER['PATH_INFO']
   : Folder::getRelativePath($_SERVER['REQUEST_URI'], ROOT_DIR);

// Extract name of subsite from path
$name = Folder::shift($path);
$folder = "env/{$name}";
$prefix = "/{$name}";

if (!$name || !is_dir(ROOT_DIR . "user/{$folder}")) {
    return [];
}

// Prefix all pages with the name of the subsite
$container['pages']->base($prefix);

return [
    'environment' => $name,
    'streams' => [
        'schemes' => [
            'user' => [
               'type' => 'ReadOnlyStream',
               'prefixes' => [
                   '' => ["user/{$folder}"],
               ]
            ]
        ]
    ]
];
```

When using subdirectories to switch language contexts you might need to load different configs depending on the language.
You can place your language specific configs in `config/<lang-context>/site.yaml` using the example for `setup_subdir_config_switch.php` below.
This way `yoursite.com/de-AT/index.html` would load `config/de-AT/site.yaml`, `yoursite.com/de-CH/index.html` would load `config/de-CH/site.yaml` and so on.

**setup_subdir_config_switch.php**:

```php
<?php
/**
 * Switch config based on the language context subdir
 *
 * DO NOT EDIT UNLESS YOU KNOW WHAT YOU ARE DOING!
 */

use Grav\Common\Filesystem\Folder;

$languageContexts = [
    'de-AT',
    'de-CH',
    'de-DE',
];

// Get relative path from Grav root.
$path = isset($_SERVER['PATH_INFO'])
    ? $_SERVER['PATH_INFO']
    : Folder::getRelativePath($_SERVER['REQUEST_URI'], ROOT_DIR);

// Extract name of subdir from path
$name = Folder::shift($path);

if (in_array($name, $languageContexts)) {
    return [
        'streams' => [
            'schemes' => [
                'config' => [
                    'type' => 'ReadOnlyStream',
                    'prefixes' => [
                        '' => [
                            'environment://config',
                            'user://config/' . $name,
                            'user://config',
                            'system/config',
                        ],
                    ],
                ],
            ],
        ],
    ];
}

return [];
```

##### GPM (Grav Package Manager) and multiple setups

Should you need to manage your subsites' plugins and themes with the [GPM](https://learn.getgrav.org/17/cli-console/grav-cli-gpm), 
Keep both `user/themes` + `user/plugins`, so that the GPM fetches and updates them in a single location. Then symlink the needed items under `user/env/my.site.com/themes` or `user/env/my.site.com/plugins`. Then setup individual yaml configurations `user/env/my.site.com/config/plugins` for each subsites.

#### Advanced configuration (for Experts)

Once created a `setup.php` have access to two important variables: (i) `$container`, which is the yet not properly initialized [Grav instance](https://github.com/getgrav/grav/blob/develop/system/src/Grav/Common/Grav.php) and (ii) `$self`, which is an instance of the [ConfigServiceProvider class](https://github.com/getgrav/grav/blob/develop/system/src/Grav/Common/Service/ConfigServiceProvider.php).

Inside this script, you can do anything, but please be aware that the `setup.php` is called every time a user requests a page. This means that memory critical or time-consuming initializations operations lead to a slow-down of your whole system and should therefore be avoided.

In the end, the `setup.php` has to return an associative array with the optional environment name **environment** and a stream collection **streams**
(for more informations and in order to set them up correctly, see the section [Streams](#streams)):

```php
return [
  'environment' => '<name>',            // A name for the environment
  'streams' => [
    'schemes' => [
      '<stream_name>' => [              // The name of the stream
        'type' => 'ReadOnlyStream',     // Stream object e.g. 'ReadOnlyStream' or 'Stream'
        'prefixes' => [
          '<prefix>' => [
            '<path1>',
            '<path2>',
            '<etc>'
          ]
        ],
        'paths' => [                    // Paths (optional)
          '<paths1>',
          '<paths2>',
          '<etc>'
        ]
      ]
    ]
  ]
]

```

!!!! Please be aware that a this very early stage you neither have access to the configuration nor to the URI instance and thus any call to a non-initialized class might end in a freeze of the system, in unexpected errors or in (complete) data loss.

#### Streams

Grav uses URI like streams to define all the file paths in Grav. Using streams makes it really easy to customize lookup paths for any file.

By default, streams have been configured like this:

* `user://` - user folder. e.g. `user/`
* `page://` - pages folder. e.g. `user://pages/`
* `image://` - images folder. e.g. `user://images/`, `system://images/`
* `account://` - accounts folder. e.g. `user://accounts/`
* `environment://` - current multi-site location.
* `asset://` - compiled JS/CSS folder. e.g. `assets/`
* `blueprints://` - blueprints folder. e.g. `environment://blueprints/`, `user://blueprints/`, `system://blueprints/`
* `config://` - configuration folder. e.g. `environment://config/`, `user://config/`, `system://config/`
* `plugins://` - plugins folder.  e.g. `user://plugins/`
* `themes://` - current theme.  e.g. `user://themes/`
* `theme://` - current theme.  e.g. `themes://antimatter/`
* `languages://` - languages folder. e.g. `environment://languages/`, `user://languages/`, `system://languages/`
* `user-data://` - data folder.  e.g. `user/data/`
* `system://` - system folder. e.g. `system/`
* `cache://` - cache folder. e.g. `cache/`, `images/`
* `log://` - log folder. e.g. `logs/`
* `backup://` - backup folder. e.g. `backups/`
* `tmp://` - temporary folder. e.g. `tmp/`

In multi-site setups, some of these default settings may not be what you want. Grav provides easy way to customize streams from the environment configuration using `config/streams.yaml`. Additionally you can create and use your own streams when needed.

Mapping physical directories to a logical device can be done by setting up `prefixes`. Here is an example where we separate pages, images, accounts, data, cache and logs from the rest of the sites, but make everything else to look up from the default locations:

`user/env/domain.com/config/streams.yaml`:

```yaml
schemes:
  account:
    type: ReadOnlyStream
    prefixes:
      '': ['environment://accounts']
  page:
    type: ReadOnlyStream
    prefixes:
      '': ['environment://user']
  image:
    type: Stream
    prefixes:
      '': ['environment://images', 'system://images/']
  'user-data':
    type: Stream
    prefixes:
      '': ['environment://data']
  cache:
    type: Stream
    prefixes:
      '': ['cache/domain.com']
      images: ['images/domain.com']
  log:
    type: Stream
    prefixes:
      '': ['logs/domain.com']
```

In Grav streams are objects, mapping a set of physical directories of the system to a logical device. They are classified via their `type` attribute. For read-only streams that's the `ReadOnlyStream` type and for read-writeable streams that's the `Stream` type.

For example, if you use `image://mountain.jpg` stream, Grav looks up `environment://images` (`user/env/domain.com/images`) and `system://images` (`system/images`). This means that streams can be used to define other streams.


Prefixes allows you to combine several physical paths into one logical stream. If you look carefully at `cache` stream definition, it is a bit different. In this case `cache://` resolves to `cache`, but `cache://images` resolves to `images`.

### Server Based Multi-Site Configuration

Grav 1.7 adds support to customize initial environment from your server configuration.

This feature comes handy if you want to use for example docker containers and you want to make them independent of the domain you happen to use. Or if do not want to store secrets in the configuration, but to store them in your server setup.

The following environment variables can be used to customize the default paths which Grav uses to setup the environment. After initialization the streams may point to different location.

!!! **Note:** You can use either environment variables or PHP constants, but they need to be set before Grav runs.

| Variable | Default | Description |
| -------- | ------- | ----------- |
| **GRAV_SETUP_PATH** | AUTO DETECT | A custom path to `setup.php` file including the filename. By default Grav looks the file from `GRAV_ROOT/setup.php` and `GRAV_ROOT/GRAV_USER_PATH/setup.php`. |
| **GRAV_USER_PATH** | `user` | A relative path for `user://` stream. |
| **GRAV_CACHE_PATH** | `cache` | A relative path for `cache://` stream. |
| **GRAV_LOG_PATH** | `logs` | A relative path for `log://` stream. |
| **GRAV_TMP_PATH** | `tmp` | A relative path for `tmp://` stream. |
| **GRAV_BACKUP_PATH** | `backup` | A relative path for `backup://` stream. |

In addition there are variables to customize the environments. Better documentation for these can be found in [Server Based Environment Configuration](../04.environment-config#server-based-environment-configuration).

!!! **Note:** These work also from `setup.php` file. You can either make them constants by using `define()` or environment variables with `putenv()`. Constants are preferred over environment variables.

| Variable | Default | Description |
| -------- | ------- | ----------- |
| **GRAV_ENVIRONMENT** | DOMAIN NAME | Environment name. Can be used for example in docker containers to set a custom environment which does not rely domain name, such as `production` and `develop`. |
| **GRAV_ENVIRONMENTS_PATH** | `user://env` | Lookup path for all environments if you do prefer something like `user://sites`. Can be either a stream or relative path from `GRAV_ROOT`. |
| **GRAV_ENVIRONMENT_PATH** | `user://env/ENVIRONMENT` | Sometimes it may be useful to have a custom location for your environment. |

#### Server Based Configuration Overrides

If you do not wish to store secret credentials inside the configuration, you can also provide them by using environment variables from your server.

As environmental variables have strict naming requirements (they can only contain A-Z, a-z, 0-9 and _), some tricks are needed to get the configuration overrides to work.

Here is an example of a simple configuration override using YAML format for presentation:

```yaml
GRAV_CONFIG: true                           # If false, the configuration here will be ignored.

GRAV_CONFIG_ALIAS__GITHUB: plugins.github   # Create alias GITHUB='plugins.github' to shorten the variable names below

GRAV_CONFIG__GITHUB__auth__method: api      # Override config.plugins.github.auth.method = api
GRAV_CONFIG__GITHUB__auth__token: xxxxxxxx  # Override config.plugins.github.auth.token = xxxxxxxx
```

In above example `__` (double underscore) represents nested variable, which in twig is represented with `.` (dot).

You can also use environment variables in `setup.php`. This allows you for example to store secrets outside the configuration:

`user/setup.php`:

```php
<?php

// Use following environment variables in your server configuration:
//
// DYNAMODB_SESSION_KEY: DynamoDb server key for the PHP session storage
// DYNAMODB_SESSION_SECRET: DynamoDb server secret
// DYNAMODB_SESSION_REGION: DynamoDb server region
// GOOGLE_MAPS_KEY: Google Maps secret key

return [
    'plugins' => [
        // This plugin does not exist
        'dynamodb_session' => [
            'credentials' => [
                'key' => getenv('DYNAMODB_SESSION_KEY') ?: null,
                'secret' => getenv('DYNAMODB_SESSION_SECRET') ?: null
            ],
            'region' => getenv('DYNAMODB_SESSION_REGION') ?: null
        ],
        // This plugin does not exist
        'google_maps' => [
            'key' => getenv('GOOGLE_MAPS_KEY') ?: null
        ]
    ]
];
```

!! **WARNING:** `setup.php` is used to set initial configuration. If the plugin or your configuration later override these settings, the initial values get lost.

After defining the variables in `setup.php`, you can then set those in your server:

```txt
<VirtualHost 127.0.0.1:80>
    ...

    SetEnv GRAV_SETUP_PATH         user/setup.php
    SetEnv GRAV_ENVIRONMENT        production
    SetEnv DYNAMODB_SESSION_KEY    JBGARDQ06UNJV00DL0R9
    SetEnv DYNAMODB_SESSION_SECRET CVjwH+QkfnPhKgVvJvrG24s0ABi343cJ7WTPxvb7
    SetEnv DYNAMODB_SESSION_REGION us-east-1
    SetEnv GOOGLE_MAPS_KEY         XWIozB2R2GmYInTqZ6jnKuUrdELounUb4BIxYmp
</VirtualHost>
```

```nginx
location / {
    ...

    fastcgi_param GRAV_SETUP_PATH         user/setup.php;
    fastcgi_param GRAV_ENVIRONMENT        production;
    fastcgi_param DYNAMODB_SESSION_KEY    JBGARDQ06UNJV00DL0R9;
    fastcgi_param DYNAMODB_SESSION_SECRET CVjwH+QkfnPhKgVvJvrG24s0ABi343cJ7WTPxvb7;
    fastcgi_param DYNAMODB_SESSION_REGION us-east-1;
    fastcgi_param GOOGLE_MAPS_KEY         XWIozB2R2GmYInTqZ6jnKuUrdELounUb4BIxYmp;
}
```

```nginx
location / {
...

    env[GRAV_SETUP_PATH]          = user/setup.php
    env[GRAV_ENVIRONMENT]         = production
    env[DYNAMODB_SESSION_KEY]     = JBGARDQ06UNJV00DL0R9
    env[DYNAMODB_SESSION_SECRET]  = CVjwH+QkfnPhKgVvJvrG24s0ABi343cJ7WTPxvb7
    env[GDYNAMODB_SESSION_REGION] = us-east-1
    env[GGOOGLE_MAPS_KEY]         = XWIozB2R2GmYInTqZ6jnKuUrdELounUb4BIxYmp
}
```

```yaml
web:
  environment:
    - GRAV_SETUP_PATH=user/setup.php
    - GRAV_ENVIRONMENT=production
    - DYNAMODB_SESSION_KEY=JBGARDQ06UNJV00DL0R9
    - DYNAMODB_SESSION_SECRET=CVjwH+QkfnPhKgVvJvrG24s0ABi343cJ7WTPxvb7
    - DYNAMODB_SESSION_REGION=us-east-1
    - GOOGLE_MAPS_KEY=XWIozB2R2GmYInTqZ6jnKuUrdELounUb4BIxYmp
```

```php
putenv('GRAV_SETUP_PATH', 'user/setup.php');
putenv('GRAV_ENVIRONMENT', 'production');
putenv('DYNAMODB_SESSION_KEY', 'JBGARDQ06UNJV00DL0R9');
putenv('DYNAMODB_SESSION_SECRET', 'CVjwH+QkfnPhKgVvJvrG24s0ABi343cJ7WTPxvb7');
putenv('DYNAMODB_SESSION_REGION', 'us-east-1');
putenv('GOOGLE_MAPS_KEY', 'XWIozB2R2GmYInTqZ6jnKuUrdELounUb4BIxYmp');
```

In this example, server will also use `production` environment stored in `user/env/production` folder.

