---
title: 'config 設定'
layout: ../../../layouts/Default.astro
lastmod: '2025-05-30'
description: 'Grav の設定は YAML 形式で簡単に管理できます。 system.yaml を中心に各種設定ファイルを紹介します。'
---

すべての Grav の設定ファイルは、 [YAML 構文](../../08.advanced/11.yaml/) で書かれており、拡張子は、 `.yaml` です。YAML は、非常に直感的なので、読み書きともにかんたんですが、利用可能な構文を完全に理解するには、 [高度な設定の章の YAML ページ](../../08.advanced/11.yaml/) をチェックしてください。

> [!Tip]  
> 本番サイトを、安全にし、かつ最適するクイックガイドとして、 [セキュリティ > 設定](../../13.security/02.configuration/) の章を参照してください。

<h2 id="system-configuration">システム設定</h2>

Grav は、ユーザにとって可能な限りかんたんにすることにフォーカスしており、設定においても同様です。Grav には、賢明な初期設定のオプションが用意されており、これらは `system/config/system.yaml` ファイルに含まれています。

しかしながら、 **絶対にこのファイルを変更しないでください** 。替わりに、あらゆる設定変更は、 `user/config/system.yaml` というファイルに保存してください。このファイルに、同じ構造で、同じ名前の設定をすれば、すべて初期設定から上書きされます。

> [!Warning]  
> 一般的に、 `system/` フォルダ内のどんなことでも **決して** 変更するべきではありません。ユーザがすること（コンテンツを作る、プラグインをインストールする、設定を編集するなど）は、 `user/` フォルダ内で行ってください。こうすることで、アップグレードがかんたんになりますし、バックアップや同期のために、変更内容をひとつの場所にまとめておくことができるようになります。 

デフォルトの `system/config/system.yaml` ファイルにある変数は次のとおりです：

<h3 id="basic-options">基本オプション</h3>

```yaml
absolute_urls: false
timezone: ''
default_locale:
param_sep: ':'
wrapped_site: false
reverse_proxy_setup: false
force_ssl: false
force_lowercase_urls: true
custom_base_url: ''
username_regex: '^[a-z0-9_-]{3,16}$'
pwd_regex: '(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}'
intl_enabled: true
http_x_forwarded:
  protocol: true
  host: false
  port: true
  ip: true
```

これらの設定オプションは、それぞれの子セクションには表示されません。これらはサイトの運用、タイムゾーン、ベース URL に影響する一般的なオプションです。

| プロパティ | 説明 |
| -------- | ----------- |
| **absolute_urls:** | `base_url` を絶対 URL にするか、相対 URL にするか|
| **timezone:** | 受け取れる値は、 [こちら](https://www.php.net/manual/ja/timezones.php) |
| **default_locale:** | デフォルトのロケール（システムにとってのデフォルト） |
| **param_sep:** | Grav での URL のパラメータに使います。変更の影響が分からないうちは触らないでください。 Windows 上の Apache web サーバーでの運用中は、 Grav は自動的に `;` を設定します。 |
| **wrapped_site:** | テーマやプラグインに、 Grav が他のプラットフォームに含まれているかを知らせます。 `true` もしくは `false` が使えます |
| **reverse_proxy_setup:** |  リバースプロキシで運用している場合で、プロキシとは異なるポート番号の場合 `true` または `false` の値 |
| **force_ssl:** | 有効化すると、 Grav は強制的に HTTPS でアクセスします（注意：理想的な解決策ではありません）。 `true` か `false` が使えます |
| **force_lowercase_urls:** | 大文字/小文字に対応した URL をサポートしたい場合、これを `false` にしてください |
| **custom_base_url:** | `base_url` を手作業で設定するならここでしてください |
| **username_regex:** | 上記の例は小文字、数字、ダッシュ、アンダースコアで、 3-16 文字 |
| **pwd_regex:** | 上記の例は1つ以上の数字、1つ以上の大文字と小文字で、8文字以上 |
| **intl_enabled:** | PHP の国際化用拡張モジュールの特別なロジック（ `mod_intl` ） |
| **http_x_forwarded:** | 多様な `HTTP_X_FORWARD` ヘッダのための設定オプション（ **Grav 1.7.0 以上** ） |

### Languages

```yaml
languages:
  supported: []
  default_lang:
  include_default_lang: true
  include_default_lang_file_extension: true
  pages_fallback_only: false
  translations: true
  translations_fallback: true
  session_store_active: false
  http_accept_language: false
  override_locale: false
  content_fallback: {}
```

**Languages** 部分は、サイトの言語を設定します。サポート対象とする言語の種類、 URL のデフォルトの言語の指定、翻訳の設定などが含まれます。 **Languages** 部分の内訳は、以下の通りです：

| プロパティ | 説明 |
| -------- | ----------- |
| **supported:** | サポート対象言語のリスト。例： `[en, fr, de]` |
| **default_lang:** | デフォルトは、上記の supported 言語で最初に書いた言語。 supported 言語の中から選ばなければいけません |
| **include_default_lang:** | URL すべてに、default lang の接頭辞を追加する。 `true` もしくは `false` |
| **include_default_lang_file_extension:** | 有効化すると、ページを保存するときに、デフォルトの言語をファイル拡張子に追加します（例： `.en.md` ）デフォルト言語でも `.md` ファイル拡張子を使い続けたい場合は、無効化してください。 `true` または `false` の値 (**Grav 1.7.0+**) |
| **pages_fallback_only:** | サポートされている言語からページコンテンツを探してフォールバックします。 `true` または `false` の値 |
| **translations:** | デフォルトで翻訳を有効化します。 `true` または `false` の値 |
| **translations_fallback:** | 有効言語が存在しない場合に、サポートされた翻訳でフォールバックします `true` または `false` の値 |
| **session_store_active:** | セッションに有効言語を保存します `true` または `false` の値 |
| **http_accept_language:** |  ブラウザの http\_accept\_language ヘッダをもとに言語設定を試みます。 `true` または `false` の値 |
| **override_locale:** | デフォルトのもしくはシステムのロケールを言語特有のものに上書きします。 `true` または `false` の値 |
| **content_fallback:** | デフォルトでは、コンテンツが翻訳されていない場合、 Grav はデフォルト言語のコンテンツを表示します。言語ごとにこの挙動を上書きする設定です。(**Grav 1.7.0+**) |

### Home

```yaml
home:
  alias: '/home'
  hide_in_urls: false
```

**Home** セクションでは、サイトのトップページのデフォルトのルーティングを設定します。 URL のホームページへのルーティングを非表示にすることもできます。

| プロパティ | 説明 |
| -------- | ----------- |
| **alias:** | home ページへのデフォルト path 。例： `/home` or `/` |
| **hide_in_urls:** | URL で home ページへのルーティングを隠す。 `true` または `false` の値 |

### Pages

```yaml
pages:
  type: regular
  theme: quark
  order:
    by: default
    dir: asc
  list:
    count: 20
  dateformat:
    default:
    short: 'jS M Y'
    long: 'F jS \a\t g:ia'
  publish_dates: true
  process:
    markdown: true
    twig: false
  twig_first: false
  never_cache_twig: false
  events:
    page: true
    twig: true
  markdown:
    extra: false
    auto_line_breaks: false
    auto_url_links: false
    escape_markup: false
    special_chars:
      '>': 'gt'
      '<': 'lt'
    valid_link_attributes:
      - rel
      - target
      - id
      - class
      - classes
  types: [html,htm,xml,txt,json,rss,atom]
  append_url_extension: ''
  expires: 604800
  cache_control:
  last_modified: false
  etag: false
  vary_accept_encoding: false
  redirect_default_route: false
  redirect_default_code: 302
  redirect_trailing_slash: true
  ignore_files: [.DS_Store]
  ignore_folders: [.git, .idea]
  ignore_hidden: true
  hide_empty_folders: false
  url_taxonomy_filters: true
  frontmatter:
    process_twig: false
    ignore_fields: ['form','forms']
```

`system/config/system.yaml` ファイルの **Pages** セクションでは、多くのテーマに関係した設定を行います。たとえば、サイトを表示する際に使うテーマを設定したり、ページの表示順や、twig、マークダウンプロセスのデフォルト設定、などです。ページの表示に影響を与える決定の多くが、この場所です。

| プロパティ | 説明 |
| -------- | ----------- |
| **type:** | フロントエンドで、 **Flex Pages** を有効化するための実験的設定。 `flex` にすると有効化し、そうでない場合は `regular` とします。デフォルトでは `regular` です。(**Grav 1.7+**) |
| **theme:** | ここでデフォルトテーマを設定します。デフォルトは `quark` です。 |
| **order:** | |
| ... **by:** | `default` のページ順です。 `alpha` （アルファベット）もしくは `date` （日付） |
| ... **dir:** | デフォルトのページを並べる方向です。 `asc` （昇順）もしくは `desc` （降順） |
| **list:** | |
| ... **count:** | ページあたりのデフォルトのアイテム数 |
| **dateformat:** | |
| ... **default:** | Grav が `date:` フィールドで期待するデフォルトの日付フォーマット |
| ... **short:** | 短い日付フォーマット。例： `'jS M Y'` |
| ... **long:** | 長い日付フォーマット。例： `'F jS \a\t g:ia'` |
| **publish_dates:** | 日付をもとに公開開始/公開終了を自動化する。 `true` または `false` の値 |
| **process:** | |
| ... **markdown:** | フロントエンドでマークダウン処理を有効化もしくは無効化する。 `true` または `false` の値 |
| ... **twig:** | フロントエンドで Twig 処理を有効化もしくは無効化する。 `true` または `false` の値 |
| **twig_first:** | マークダウンと Twig の両方をページで処理するときに、マークダウン処理よりも前に Twig を処理する。 `true` または `false` の値 |
| **never_cache_twig:** | これを有効化すると、結果をキャッシュ・保存せずに、ページの読み込みごとに動的に変化するロジック処理を追加できます。 **system.yaml** では、サイト全体での有効化/無効化ができます。特定のページ単位で設定する方法もあります。 `true` または `false` の値 |
| **events:** | |
| ... **page:** | ページレベルのイベントの有効化 `true` または `false` の値 |
| ... **twig:** | Twig レベルのイベントの有効化 `true` または `false` の値 |
| **markdown:** | |
| ... **extra:** | Markdown Extra （デフォルトで GitHub-flavord Markdown(GFM)）のサポートの有効化 `true` または `false` の値 |
| ... **auto_line_breaks:** | 自動的に改行を有効化（通常のマークダウンは、空白2個が必要） `true` または `false` の値 |
| ... **auto_url_links:** | HTML を自動リンクする `true` または `false` の値 |
| ... **escape_markup:** | マークアップタグをエンティティにエスケープする `true` または `false` の値 |
| ... **special_chars:** | リストになった特殊文字を自動でエンティティに変換します。各文字を1行ごとにこの変数の下に追加します。例： `'>': 'gt'` |
| ... **valid_link_attributes:** | 適切な属性をマークダウンリンクを通して渡します (**Grav 1.7+**) |
| **types:** | 有効なページタイプのリストです。例： `[txt,xml,html,htm,json,rss,atom]` |
| **append_url_extension:** | ページの拡張子をページ URL に追加します。（例： `.html` の場合 **/path/page.html** になります） |
| **expires:** | ページの有効期限（秒） (604800 seconds = 7 days) (`no cache` も可能) |
| **cache_control:** | 設定しない場合は空欄にできます。もしくは、 [有効な](https://developer.mozilla.org/ja/docs/Web/HTTP/Reference/Headers/Cache-Control) `cache-control` テキストの値です |
| **last_modified:** | ファイルの修正タイムスタンプをもとに、最終更新日のヘッダを設定します。 `true` または `false` の値 |
| **etag:** | Set the etag header tag. `true` または `false` の値 |
| **vary_accept_encoding:** | Add `Vary: Accept-Encoding` header. `true` または `false` の値 |
| **redirect_default_route:** | Automatically redirect to a page's default route. `true` または `false` の値 |
| **redirect_default_code:** | Default code to use for redirects. For example: `302` |
| **redirect_trailing_slash:** | Handle automatically or 302 redirect a trailing / URL |
| **ignore_files:** | Files to ignore in Pages. Example: `[.DS_Store] ` |
| **ignore_folders:** | Folders to ignore in Pages. Example: `[.git, .idea]` |
| **ignore_hidden:** | Ignore all Hidden files and folders. `true` または `false` の値 |
| **hide_empty_folders:** | If folder has no .md file, should it be hidden. `true` または `false` の値 |
| **url_taxonomy_filters:** | Enable auto-magic URL-based taxonomy filters for page collections. `true` または `false` の値 |
| **frontmatter:** | |
| ... **process_twig:** | Should the frontmatter be processed to replace Twig variables? `true` または `false` の値 |
| ... **ignore_fields:** | Fields that might contain Twig variables and should not be processed. Example: `['form','forms']` |

### Cache

```yaml
cache:
  enabled: true
  check:
    method: file
  driver: auto
  prefix: 'g'
  purge_at: '0 4 * * *'
  clear_at: '0 3 * * *'
  clear_job_type: 'standard'
  clear_images_by_default: false
  cli_compatibility: false
  lifetime: 604800
  gzip: false
  allow_webserver_gzip: false
  redis:
    socket: false
    password:
    database:
```

**キャッシュ** セクションでは、サイトのキャッシュを設定できます。メソッドを有効化したり、無効化したり、選んだりできます。

| プロパティ | 説明 |
| -------- | ----------- |
| **enabled:** | Set to `true` to enable caching. `true` または `false` の値 |
| **check:** | |
| ... **method:** | Method to check for updates in pages. Options: `file`, `folder`, `hash` and `none`. [more details](../../08.advanced/02.performance-and-caching#grav-core-caching) |
| **driver:** | Select a cache driver. Options are: `auto`, `file`, `apcu`, `redis`, `memcache`, and `wincache` |
| **prefix:** | Cache prefix string (prevents cache conflicts). Example: `g` |
| **purge_at:** | Scheduler: How often to purge old cache using cron `at` syntax |
| **clear_at:** | Scheduler: How often to clear the cache using cron `at` syntax |
| **clear_job_type:** | Type to clear when processing the scheduled clear job. Options: `standard` \| `all` |
| **clear_images_by_default:** | By default grav does not include processed images when cache clears, this can be enabled by setting this to `true` |
| **cli_compatibility:** | Ensures only non-volatile drivers are used (file, redis, memcache, etc.) |
| **lifetime:** | Lifetime of cached data in seconds (`0` = infinite). `604800` is 7 days |
| **gzip:** | GZip compress the page output. `true` または `false` の値 |
| **allow_webserver_gzip:** | This option will change the header to `Content-Encoding: identity` allowing gzip to be more reliably set by the webserver although this usually breaks the out-of-process `onShutDown()` capability.  The event will still run, but it won't be out of process, and may hold up the page until the event is complete |
| **redis:** | |
| **... socket:** | The path to the redis socket file |
| **... password:** | Optional password |
| **... database:** | Optional database ID |

### Twig

```yaml
twig:
  cache: true
  debug: true
  auto_reload: true
  autoescape: false
  undefined_functions: true
  undefined_filters: true
  umask_fix: false
```

**Twig** セクションでは、サイトのデバッグ、キャッシュ、最適化のためにTwigを設定するツールが用意されています。

| プロパティ | 説明 |
| -------- | ----------- |
| **cache:** | Set to `true` to enable Twig caching. `true` または `false` の値 |
| **debug:** | Enable Twig debug. `true` または `false` の値 |
| **auto_reload:** | Refresh cache on changes. `true` または `false` の値 |
| **autoescape:** | Autoescape Twig vars. `true` または `false` の値 |
| **undefined_functions:** | Allow undefined functions. `true` または `false` の値 |
| **undefined_filters:** | Allow undefined filters. `true` または `false` の値 |
| **umask_fix:** | By default Twig creates cached files as 755, fix switches this to 775. `true` または `false` の値 |

### Assets

```yaml
assets:
  css_pipeline: false
  css_pipeline_include_externals: true
  css_pipeline_before_excludes: true
  css_minify: true
  css_minify_windows: false
  css_rewrite: true
  js_pipeline: false
  js_pipeline_include_externals: true
  js_pipeline_before_excludes: true
  js_module_pipeline: false
  js_module_pipeline_include_externals: true
  js_module_pipeline_before_excludes: true
  js_minify: true
  enable_asset_timestamp: false
  enable_asset_sri: false
  collections:
    jquery: system://assets/jquery/jquery-2.x.min.js
```

**Assets** セクションでは、JavaScriptやCSSなどのアセット管理に関するオプションを設定できます。

| プロパティ | 説明 |
| -------- | ----------- |
| **css_pipeline:** | The CSS pipeline is the unification of multiple CSS resources into one file. `true` または `false` の値 |
| **css_pipeline_include_externals:** | Include external URLs in the pipeline by default. `true` または `false` の値 |
| **css_pipeline_before_excludes:** | Render the pipeline before any excluded files. `true` または `false` の値 |
| **css_minify:** | Minify the CSS during pipelining. `true` または `false` の値 |
| **css_minify_windows:** | Minify Override for Windows platforms. `false` by default due to ThreadStackSize. `true` または `false` の値 |
| **css_rewrite:** | Rewrite any CSS relative URLs during pipelining. `true` または `false` の値 |
| **js_pipeline:** | The JS pipeline is the unification of multiple JS resources into one file. `true` または `false` の値 |
| **js_pipeline_include_externals:** | Include external URLs in the pipeline by default. `true` または `false` の値 |
| **js_pipeline_before_excludes:** | Render the pipeline before any excluded files. `true` または `false` の値 |
| **js_module_pipeline** | The JS Module pipeline is the unification of multiple JS Module resources into one file. `true` または `false` の値 |
| **js_module_pipeline_include_externals** | Include external URLs in the pipeline by default. `true` または `false` の値 |
| **js_module_pipeline_before_excludes** | Render the pipeline before any excluded files. `true` または `false` の値 |
| **js_minify:** | Minify the JS during pipelining. `true` または `false` の値 |
| **enable_asset_timestamp:** | Enable asset timestamps. `true` または `false` の値 |
| **enable_asset_sri:** | Enable asset SRI. `true` または `false` の値 |
| **collections:** | This contains collections, designated as sub-items. For example: `jquery: system://assets/jquery/jquery-3.x.min.js`. [Read more about this](../../03.themes/07.asset-manager/#collections-and-attributes) |

### Errors

```yaml
errors:
  display: 0
  log: true
```

**Errors** セクションは、Gravでのエラーの表示やログ記録の方法を決定します。

| プロパティ | 説明 |
| -------- | ----------- |
| **display:** | Determines how errors are displayed. Enter either `1` for the full backtrace, `0` for Simple Error, or `-1` for System Error |
| **log:** | Log errors to `/logs` folder. `true` または `false` の値 |

### Log

```yaml
log:
  handler: file
  syslog:
    facility: local6
```

**log** セクションは、Gravの代替ログ機能を設定できます。

| プロパティ | 説明 |
| -------- | ----------- |
| **handler:** | Log handler. Currently supported: `file` \| `syslog` |
| **syslog:** | |
| ... **facility:** | Syslog facilities output |

### Debugger

```yaml
debugger:
  enabled: false
  provider: clockwork
  censored: false
  shutdown:
    close_connection: true
```

**Debugger** セクションでは、Gravのデバッガーを有効化できます。開発中に便利なツールです。

| プロパティ | 説明 |
| -------- | ----------- |
| **enabled:** | Enable Grav debugger and following settings. `true` または `false` の値 |
| **provider:** | Debugger provider: Can be set to `debugbar` or `clockwork` (**Grav 1.7+**) |
| **censored:** | Censor potentially sensitive information (POST parameters, cookies, files, configuration and most array/object data in log messages). `true` または `false` の値 (**Grav 1.7+**) |
| **shutdown:** | |
| ... **close_connection:** | Close the connection before calling `onShutdown()`. `false` for debugging |

### Images

```yaml
images:
  default_image_quality: 85
  cache_all: false
  cache_perms: '0755'
  debug: false
  auto_fix_orientation: false
  seofriendly: false
  cls:
    auto_sizes: false
    aspect_ratio: false
    retina_scale: 1
  defaults:
    loading: auto
```

**Images** セクションでは、画像の再サンプリングのデフォルト画質を設定したり、画像のキャッシュやデバッグ機能を制御したりできます。

| プロパティ | 説明 |
| -------- | ----------- |
| **default_image_quality:** | Default image quality to use when resampling images. For example: `85` = 85% |
| **cache_all:** | Cache all images by default. `true` または `false` の値 |
| **cache_perms:** | **Must be in quotes!** Default cache folder perms. Usually `'0755'` or `'0775'` |
| **debug:** | Show an overlay over images indicating the pixel depth of the image when working with retina, for example. `true` または `false` の値 |
| **auto_fix_orientation:** | Try to automatically fix images uploaded with non-standard rotation |
| **seofriendly:** | SEO-friendly processed image names |
| **cls:** | Cumulative Layout Shift. [More details](https://web.dev/optimize-cls/) |
| **... auto_sizes:** | Automatically add height/width to image |
| **... aspect_ratio:** | Reserve space with aspect ratio style |
| **... retina_scale:** | Scale to adjust auto-sizes for better handling of HiDPI resolutions |
| **defaults:** | (**Grav 1.7+**) |
| **... loading:** | Let browser pick: `auto`, `lazy` or `eager` (**Grav 1.7+**) |


### Media

```yaml
media:
  enable_media_timestamp: false
  unsupported_inline_types: []
  allowed_fallback_types: []
  auto_metadata_exif: false
```

**Media** セクションでは、メディアファイルの制御に関する設定ができます。タイムスタンプの表示や、アップロードサイズなども含まれます。

| プロパティ | 説明 |
| -------- | ----------- |
| **enable_media_timestamp:** | Enable media timetsamps |
| **unsupported_inline_types:** | Array of supported media types to try to display inline. These file types are placed within `[]` brackets |
| **allowed_fallback_types:** | Array of allowed media types of files found if accessed via Page route. These file types are placed within `[]` brackets |
| **auto_metadata_exif:** | Automatically create metadata files from Exif data where possible |

### Session

```yaml
session:
  enabled: true
  initialize: true
  timeout: 1800
  name: grav-site
  uniqueness: path
  secure: false
  httponly: true
  samesite: Lax
  split: true
  domain:
  path:
```

これらのオプションは、サイトのセッション変数を決定します。

| プロパティ | 説明 |
| -------- | ----------- |
| **enabled:** | Enable Session support. `true` または `false` の値 |
| **initialize:** | Initialize session from Grav (if `false`, plugin needs to start the session) |
| **timeout:** | Timeout in seconds. For example: `1800` |
| **name:** | Name prefix of the session cookie. Use alphanumeric, dashes or underscores only. Do not use dots in the session name. For example: `grav-site` |
| **uniqueness:** | Should sessions be `path` based or `security.salt` based |
| **secure:** | Set session secure. If `true`, indicates that communication for this cookie must be over an encrypted transmission. Enable this only on sites that run exclusively on HTTPS. `true` または `false` の値 |
| **httponly:** | Set session HTTP only. If `true`, indicates that cookies should be used only over HTTP, and JavaScript modification is not allowed. `true` または `false` の値 |
| **samesite:** | Set session SameSite. Possible values are Lax, Strict and None. See [here](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie/SameSite) |
| **domain:** | The session domain to be used in the responses. Use only if you you rewrite the site domain for example in a Docker Container. |
| **path:** | The session path to be used in the responses. Use only if you you rewrite the site subfolder for example in a Docker Container. |

### GPM

```yaml
gpm:
  releases: stable
  proxy_url:
  method: 'auto'
  verify_peer: true
  official_gpm_only: true
```

**GPM** セクションの選択肢は、GravのGPM（Grav パッケージ・マネージャー）を制御します。たとえば、GPMが公式のソースを使用するように制限したり、パッケージを取得する方法を選択したりできます。また、安定版リリースやテストリリースを選択したり、プロキシURLの設定もできます。

| プロパティ | 説明 |
| -------- | ----------- |
| **releases:** | Set to either `stable` or `testing` to determine if you want to update to the latest stable or testing build |
| **proxy_url:** | Configure a manual proxy URL for GPM. For example: `127.0.0.1:3128` |
| **method:** | Either `'curl'`, `'fopen'` or `'auto'`. `'auto'` will try fopen first and if not available cURL |
| **verify_peer:** | On some systems (Windows mostly) GPM is unable to connect because the SSL certificate cannot be verified. Disabling this setting might help |
| **official_gpm_only:** | By default GPM direct-install will only allow URLs via the official GPM proxy to ensure security, disable this to allow other sources |

### Accounts

```yaml
accounts:
  type: regular
  storage: file
```

Accounts設定は、Flex Usersの新しい体験ができます。基本的に、ユーザは、より力強くパフォーマンスの良いFlex objectsとして保存されます。

| プロパティ | 説明 |
| -------- | ----------- |
| **type:** | Account type: `regular` or `flex` |
| **storage:** | Flex storage type: `file` or `folder` |

### Flex

```yaml
flex:
  cache:
    index:
      enabled: true
      lifetime: 60
    object:
      enabled: true
      lifetime: 600
    render:
      enabled: true
      lifetime: 600
```

Flex Objectsのcacheは、**Grav 1.7** で新しく追加されました。これは、すべてのFlex typesのためのデフォルト設定ですが、それぞれの`Flex Directory` で上書き可能です。

| プロパティ | 説明 |
| -------- | ----------- |
| **cache:** | (**Grav 1.7+**) |
| **... index:** | (**Grav 1.7+**) |
| **... ... enabled:** | Set to true to enable Flex index caching. Is used to cache timestamps in files (**Grav 1.7+**) |
| **... ... lifetime:** | Lifetime of cached index in seconds (0 = infinite) (**Grav 1.7+**) |
| **... object:** | (**Grav 1.7+**) |
| **... ... enabled:** | Set to true to enable Flex object caching. Is used to cache object data (**Grav 1.7+**) |
| **... ... lifetime:** | Lifetime of cached objects in seconds (0 = infinite) (**Grav 1.7+**) |
| **... render:** | (**Grav 1.7+**) |
| **... ... enabled:** | Set to true to enable Flex render caching. Is used to cache rendered output (**Grav 1.7+**) |
| **... ... lifetime:** | Lifetime of cached HTML in seconds (0 = infinite) (**Grav 1.7+**) |

### Strict Mode

```yaml
strict_mode:
  yaml_compat: true
  twig_compat: true
  blueprint_compat: false
```

Strictモードは、新しいバージョンのYAMLとTwigプロセッサに移行することで、将来のバージョンのGravへの移行がよりかんたんになります。これらは、すべてのサードパーティ製拡張機能と互換性があるとは限りません。

| プロパティ | 説明 |
| -------- | ----------- |
| **yaml_compat:** | Enables YAML backwards compatibility |
| **twig_compat:** | Enables deprecated Twig autoescape setting |
| **blueprint_compat:** | Enables backward compatible strict support for blueprints |

> [!Info]  
> **すべての** 設定ファイルをコピーして上書きする必要はありません。最小限の上書きでも、最大限でも、好きなようにできます。ただし、上書きしたい特定の設定については、**正確に同じ名前の構造** となるように確認してください。

<h2 id="site-configuration">サイト設定</h2>

`system.yaml` と同様に、Gravには、デフォルトの `site.yaml` という設定ファイルも提供されています。それは、フロントエンドの特定の設定、たとえば著者名、著者メールアドレスや、タグ設定なども設定されています。system.yamlと同じように、 `user/config/site.yaml` に独自の設定ファイルを作ることで、上書きできます。このファイルを使って、コンテンツやテンプレートで参照したい任意の設定オプションが使えます。

Gravが提供するデフォルトの `system/config/site.yaml` ファイルは、次のようなものです：

```yaml
title: Grav                                 # Name of the site
default_lang: en                            # Default language for site (potentially used by theme)

author:
  name: John Appleseed                      # Default author name
  email: 'john@example.com'                 # Default author email

taxonomies: [category,tag]                  # Arbitrary list of taxonomy types

metadata:
  description: 'My Grav Site'               # Site description

summary:
  enabled: true                             # enable or disable summary of page
  format: short                             # long = summary delimiter will be ignored; short = use the first occurrence of delimiter or size
  size: 300                                 # Maximum length of summary (characters)
  delimiter: ===                            # The summary delimiter

redirects:
#  '/redirect-test': '/'                    # Redirect test goes to home page
#  '/old/(.*)': '/new/$1'                   # Would redirect /old/my-page to /new/my-page

routes:
#  '/something/else': '/blog/sample-3'      # Alias for /blog/sample-3
#  '/new/(.*)': '/blog/$1'                  # Regex any /new/my-page URL to /blog/my-page Route

blog:
  route: '/blog'                            # Custom value added (accessible via site.blog.route)

#menu:                                      # Menu Example
#    - text: Source
#      icon: github
#      url: https://github.com/getgrav/grav
#    - icon: twitter
#      url: http://twitter.com/getgrav
```

このサンプルファイルの要素を詳しく見ていきましょう：

| プロパティ | 説明 |
| -------- | ----------- |
| **title:** | The title is a simple string variable that can be referenced whenever you want to display the name of this site |
| **author:** | |
| ... **name:** | The name of the author of the site, that can be referenced whenever you need it |
| ... **email:** | A default email for use in your site |
| **taxonomies:** | An arbitrary list of high-level types that you can use to organize your content.  You can assign content to specific taxonomy types, for example, categories or tags. Feel free to edit, or add your own |
| **metadata:** | Set default metadata for all your pages, see the [content page headers](../../02.content/02.headers) section for more details |
| **summary:** | |
| ... **size:** | A variable to override the default number of characters that can be used to set the summary size when displaying a portion of content |
| **routes:** | This is a basic map that can provide simple URL alias capabilities in Grav.  If you browse to `/something/else` you will actually be sent to `/blog/sample-3`. Feel free to edit, or add your own as needed. **Regex Replacements** (`(.*) - $1`) are now supported at the end of route aliases.  You should put these at the bottom of the list for optimal performance |
| **(custom options)** | You can create any option you like in this file and a good example is the `blog: route: '/blog'` option that is accessible in your Twig templates with `site.blog.route` |

> [!Info]  
> ほとんど場合、このファイルの最も重要な要素は、 `Taxonomy` リストです。コンテンツでタグやカテゴリーを使いたいならば、ここでタクソノミーのリストを **定義しなければなりません。**

<h2 id="security">セキュリティ</h2>

セキュリティを強化するために、 `system/config/security.yaml` ファイルがあります。これは、いくつかの安全側のデフォルト設定がしてあり、コンテンツを **保存** したり、**ツール** の新しい **Reports** セクションに、Adminプラグインで使われます。

デフォルトの設定は、次のとおりです：

```yaml
xss_whitelist: [admin.super]
xss_enabled:
    on_events: true
    invalid_protocols: true
    moz_binding: true
    html_inline_styles: true
    dangerous_tags: true
xss_invalid_protocols:
    - javascript
    - livescript
    - vbscript
    - mocha
    - feed
    - data
xss_dangerous_tags:
    - applet
    - meta
    - xml
    - blink
    - link
    - style
    - script
    - embed
    - object
    - iframe
    - frame
    - frameset
    - ilayer
    - layer
    - bgsound
    - title
    - base
uploads_dangerous_extensions:
    - php
    - html
    - htm
    - js
    - exe
sanitize_svg: true
```

これらの設定に変更を加えたいと思ったら、このファイルを `user/config/security.yaml` ファイルにコピーし、コピー先を編集してください。

<h2 id="other-configuration-settings-and-files">その他の設定とファイル設定</h2>

ユーザー設定は、完全に任意です。デフォルトの設定を、好きなように上書きできます。この上書きは、サイト上のシステム、サイト、そしていかなるプラグインにも適用されます。

また、上記で解説した `user/config/system.yaml` や、`user/config/site.yaml` ファイルにも制限はありません。ほかの `.yaml` 設定ファイルを `user/config/` フォルダに作成できますし、Gravでは、自動的にそれらを読み込まれます。

たとえば、`user/config/data.yaml` という新しい設定ファイルがあったとして、このファイルのyaml変数でcountを呼び出すとします：

```
count: 39
```

この変数は、Twigテンプレートで、次のような構文を使ってアクセスできます：

```
{{ config.data.count }}
```

また、プラグインからは、PHPによって、次のようなコードでアクセスできます：

```
$count_var = Grav::instance()['config']->get('data.count');
```

> [!Note]  
> また、カスタムファイルを管理プラグインで編集できるようにするために、カスタムのブループリントファイルを提供することもできます。関連する[Admin クックブックセクションのレシピ](../../10.cookbook/04.admin-recipes#add-a-custom-yaml-file)を確認してください。

<h3 id="config-variable-namespacing">設定変数の名前空間</h3>

設定ファイルへのパスは、設定オプションの **名前空間（namespace）** として使われます。

かわりに、すべてのオプションをひとつのファイルに詰めて、YAML構造を使い、設定オプションの階層を指定することもできます。この名前空間は、**パス + ファイル名 + オプション名** の組み合わせで作られます。

たとえば：あるオプション `author: "フランク スミス"` が、`plugins/myplugin.yaml` にあったとして、これは次のようにアクセスできます： `plugins.myplugin.author` 。しかしながら、`plugins.yaml` というファイルもあった場合で、そのファイルに `myplugin: author: "フランク スミス"` という名前のオプションがあった場合に、同じ `plugins.myplugin.author` によりたどりつけます。

設定ファイルの構造を、いくつか例示します：

| ファイル名 | 説明 |
| -------- | ----------- |
| **user/config/system.yaml**           | グローバルなシステム設定ファイル |
| **user/config/site.yaml**             | サイト固有の設定ファイル |
| **user/config/plugins/myplugin.yaml** | 「myplugin」プラグインの個別設定ファイル |
| **user/config/themes/mytheme.yaml**   | 「mytheme」テーマの個別設定ファイル |

> [!Info]  
> 名前空間を持つ設定ファイルは、デフォルトの設定ファイルの同じパスを持つオプションをすべて上書きもしくはマスクします。

<h3 id="plugins-configuration">プラグインの設定</h3>

ほとんどの **プラグイン** は、それぞれ独自のYAML設定ファイルを持ちます。このファイルを直接編集するよりは、 `user/config/plugins/` ディレクトリにコピーすることを推奨します。これにより、プラグインのアップデートにより、設定が上書きされないことを確認できますし、設定変更可能なオプションをひとつの便利な場所に留めることができます。

もし、`user/plugins/myplugin` というプラグインがあり、設定ファイルが `user/plugins/myplugin/myplugin.yaml` にあるとき、このファイルをコピーして、 `user/config/plugins/myplugin.yaml` そのファイルをそこで編集できます。

プラグインの主ディレクトリ内に存在するYAMLファイルは、二番目に働きます。そこに書かれている設定はすべて、ユーザーフォルダのコピーに無いとしても、Gravはピックアップし、利用します。

<h3 id="themes-configuration">テーマの設定</h3>

**テーマ** においても、プラグインと同じルールが適用されます。このため、 `user/themes/mytheme` というファイルに、`user/themes/mytheme/mytheme.yaml` という設定ファイルがあった場合、このファイルを `user/config/themes/mytheme.yaml` にコピーし、そこで編集できます。

