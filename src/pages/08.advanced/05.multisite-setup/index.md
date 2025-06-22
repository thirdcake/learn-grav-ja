---
title: マルチサイト設定
layout: ../../../layouts/Default.astro
lastmod: '2025-06-22'
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

<h4 id="quickstart-for-beginners">クイックスタート（初心者向け）</h4>

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

> [!訳注]  
> レンタルサーバーでのサブドメイン運用において、ワイルドカードサブドメイン設定が必要かどうかは、そのレンタルサーバーの仕様によるようです。

<h5 id="snippets">スニペット</h5>

サブドメイン経由でサブサイトへサクセスするため、 `setup.php` へ `setup_subdomain.php` ファイルをコピーしてください。サブディレクトリ経由でサブサイトへアクセスするには、 `setup_subdirectory.php` ファイルをコピーしてください。

> [!Tip]  
> `setup.php` ファイルは、 Grav のルートフォルダに置かなければいけません。 `index.php` や、 `README.md` ファイル、その他の Grav ファイルと同じフォルダです。

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

言語の文脈を切り替えるためにサブディレクトリを使う場合、言語によって異なる設定を読み込む必要があるかもしれません。
以下の `setup_subdir_config_switch.php` の例を使って、 `config/<lang-context>/site.yaml` ファイルに言語固有の設定を置くことができます。
このようにして、 `yoursite.com/de-AT/index.html` により `config/de-AT/site.yaml` が読み込まれ、 `yoursite.com/de-CH/index.html` により `config/de-CH/site.yaml` が読み込まれ、以下同様です。

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

<h5 id="gpm-grav-package-manager-and-multiple-setups">GPM（Grav パッケージマネージャー）とマルチサイト設定</h5>

もし [GPM](../../07.cli-console/04.grav-cli-gpm/) によりサブサイトのプラグインやテーマを管理したい場合は、 `user/themes` と `user/plugins`の両方をキープしてください。 GPM が1つの場所で取得や更新するためです。それから、必要なアイテムを `user/env/my.site.com/themes` もしくは `user/env/my.site.com/plugins` のフォルダ下にシムリンクしてください。そして、各サブサイトごとに個々の yaml 設定ファイル `user/env/my.site.com/config/plugins` をセットアップしてください。

<h4 id="advanced-configuration-for-experts">発展的な設定（上級者向け）</h4>

一度 `setup.php` を作成すると、2つの重要な変数にアクセスします。

1. `$container`, これは、まだ適切に初期化されていない[Grav インスタンス](https://github.com/getgrav/grav/blob/develop/system/src/Grav/Common/Grav.php) です。
2. `$self`, これは、 [ConfigServiceProvider class](https://github.com/getgrav/grav/blob/develop/system/src/Grav/Common/Service/ConfigServiceProvider.php) のインスタンスです。

このスクリプト内では、何でもできます。ただし、気をつけてほしいのは、ページがリクエストされるたびに、常に `setup.php` が呼ばれるということです。つまり、メモリが必要だったり、時間のかかる初期化処理は、システム全体のスローダウンにつながるため、避けるべきです。

最終的に、 `setup.php` は、オプショナルな環境名 **environment** と、 stream のコレクションである **streams** を持つ連想配列を返さなければいけません。
（より詳細な情報と正しい設定方法については、 [ストリーム](#streams) セクションをお読みください）

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

> [!Warning]  
> 非常に初期のステージでは、config 設定にも、 URI インスタンスにも、アクセスしていないことに注意してください。このため、初期化されていない class を呼び出すと、システムがフリーズしたり、予期しないエラーが出たり、データが（完全に）消えることが起こりえます。

<h4 id="streams">ストリーム</h4>

Grav は、Grav 内のすべてのファイルパスを定義するため、 URI をストリームのように使います。ストリームを使うことにより、あらゆるファイルのパスのカスタマイズが簡単になります。

デフォルトでは、ストリームは次のように設定されています：

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

マルチサイト設定では、いくつかのデフォルト設定は期待通りになりません。 Grav は`config/streams.yaml` ファイルを使い、環境設定からストリームをカスタマイズする方法を提供します。加えて、必要な場合には、独自のストリームを作成したり使ったりすることもできます。

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

> [!Note]  
> You can use either environment variables or PHP constants, but they need to be set before Grav runs.

| Variable | Default | Description |
| -------- | ------- | ----------- |
| **GRAV_SETUP_PATH** | AUTO DETECT | A custom path to `setup.php` file including the filename. By default Grav looks the file from `GRAV_ROOT/setup.php` and `GRAV_ROOT/GRAV_USER_PATH/setup.php`. |
| **GRAV_USER_PATH** | `user` | A relative path for `user://` stream. |
| **GRAV_CACHE_PATH** | `cache` | A relative path for `cache://` stream. |
| **GRAV_LOG_PATH** | `logs` | A relative path for `log://` stream. |
| **GRAV_TMP_PATH** | `tmp` | A relative path for `tmp://` stream. |
| **GRAV_BACKUP_PATH** | `backup` | A relative path for `backup://` stream. |

In addition there are variables to customize the environments. Better documentation for these can be found in [Server Based Environment Configuration](../04.environment-config#server-based-environment-configuration).

> [!Note]  
> These work also from `setup.php` file. You can either make them constants by using `define()` or environment variables with `putenv()`. Constants are preferred over environment variables.

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

> [!Warning]  
> `setup.php` is used to set initial configuration. If the plugin or your configuration later override these settings, the initial values get lost.

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

