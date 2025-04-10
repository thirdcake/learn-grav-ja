---
title: '設定'
layout: ../../../layouts/Default.astro
---

すべてのGravの設定ファイルは、[YAML構文](/advanced/yaml/) で書かれており、拡張子は、`.yaml` です。YAMLは、非常に直感的なので、読み書きともにかんたんですが、利用可能な構文を完全に理解するには、[上級の章のYAMLページ](/advanced/yaml/)をチェックしてください。

> [!TIPS]
> **TIP:** 本番サイトを、安全にし、かつ最適するクイックガイドとして、[Security > Configuration](/security/configuration/)を参照してください。

## System Configuration

Gravは、ユーザにとってものごとを可能な限りかんたんにすることにフォーカスしており、設定においても同様です。Gravには、賢明な初期設定のオプションが用意されており、これらは `system/config/system.yaml` ファイルに含まれています。

しかしながら、**絶対にこのファイルを変更しないでください** 。替わりに、あらゆる設定変更は、`user/config/system.yaml` というファイルに保存してください。このファイルに、同じ構造で、同じ名前の設定をすれば、すべて初期設定から上書きされます。

> [!CAUTION]
> 一般的に言って、`system/` フォルダ内のどんなことでも **決して** 変更するべきではありません。ユーザがすること（コンテンツを作る、プラグインをインストールする、設定を編集するなど）は、 `user/` フォルダ内で行ってください。こうすることで、アップグレードがかんたんになりますし、バックアップや同期のために、変更内容をひとつの場所にまとめておくことができるようになります。 

これが、デフォルトの`system/config/system.yaml` ファイルにある変数です：

### Basic Options

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

これらの設定オプションは、それぞれの子セクションには表示されません。これらはサイトの運営方法、タイムゾーン、ベースURLに影響する一般的なオプションです。

| Property | Description |
| -------- | ----------- |
| **absolute_urls:** | Absolute or relative URLs for `base_url` |
| **timezone:** | Valid values can be found [here](https://php.net/manual/en/timezones.php) |
| **default_locale:** | Default locale (defaults to system) |
| **param_sep:** | This is used for Grav parameters in the URL.  Don't change this unless you know what you are doing.  Grav automatically sets this to `;` for users running Apache web server on Windows |
| **wrapped_site:** | For themes/plugins to know if Grav is wrapped by another platform. Can be `true` or `false` |
| **reverse_proxy_setup:** | Running in a reverse proxy scenario with different webserver ports than proxy. Can be `true` or `false` |
| **force_ssl:** | If enabled, Grav forces to be accessed via HTTPS (NOTE: Not an ideal solution). Can be `true` or `false` |
| **force_lowercase_urls:** |If you want to support mixed cased URLs set this to `false` |
| **custom_base_url:** | Manually set the base\_url here |
| **username_regex:** | Only lowercase chars, digits, dashes, underscores. 3 - 16 chars |
| **pwd_regex:** | At least one number, one uppercase and lowercase letter, and be at least 8+ chars |
| **intl_enabled:** | Special logic for PHP International Extension (mod\_intl) |
| **http_x_forwarded:** | Configuration options for the various HTTP\_X\_FORWARD headers (**Grav 1.7.0+**) |

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

**Languages** 部分は、サイトの言語を設定します。どんな言語をサポートするか、URLのデフォルトの言語は何か、翻訳の設定などが含まれます。**Languages** 部分の内訳は、以下の通りです：

| Property | Description |
| -------- | ----------- |
| **supported:** | List of languages supported. eg: `[en, fr, de]` |
| **default_lang:** | Default is the first supported language. Must be one of the supported languages |
| **include_default_lang:** | Include the default lang prefix in all URLs. Can be `true` or `false` |
| **include_default_lang_file_extension:** | If enabled, saving a page will prepend the default language to the file extension (eg. `.en.md`). Disable it to keep the default language using `.md` file extension. Can be `true` or `false` (**Grav 1.7.0+**) |
| **pages_fallback_only:** | Only fallback to find page content through supported languages. Can be `true` or `false` |
| **translations:** | Enable translations by default. Can be `true` or `false` |
| **translations_fallback:** | Fallback through supported translations if active lang doesn't exist. Can be `true` or `false` |
| **session_store_active:** | Store active language in session. Can be `true` or `false` |
| **http_accept_language:** | Attempt to set the language based on http\_accept\_language header in the browser. Can be `true` or `false` |
| **override_locale:** | Override the default or system locale with language specific one. Can be `true` or `false` |
| **content_fallback:** | By default if the content isn't translated, Grav will display the content in the default language. Use this setting to override that behavior per language basis. (**Grav 1.7.0+**) |

### Home

```yaml
home:
  alias: '/home'
  hide_in_urls: false
```

**Home** セクションでは、サイトのトップページのデフォルトのルートを設定します。URLのホームルートを非表示にすることもできます。

| Property | Description |
| -------- | ----------- |
| **alias:** | Default path for home, ie: `/home` or `/` |
| **hide_in_urls:** | Hide the home route in URLs. Can be `true` or `false` |

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

| Property | Description |
| -------- | ----------- |
| **type:** | Experimental setting to enable **Flex Pages** in frontend. Use `flex` to enable, `regular` otherwise. This defaults to `regular` (**Grav 1.7+**) |
| **theme:** | This is where you set the default theme. This defaults to `quark` |
| **order:** | |
| ... **by:** | Order pages by `default`, `alpha` or `date` |
| ... **dir:** | Default ordering direction, `asc` or `desc` |
| **list:** | |
| ... **count:** | Default item count per page |
| **dateformat:** | |
| ... **default:** | The default date format Grav expects in the `date: ` field |
| ... **short:** | Short date format. Example: `'jS M Y'` |
| ... **long:** | Long date format. Example: `'F jS \a\t g:ia'` |
| **publish_dates:** | Automatically publish/unpublish based on dates. Can be set `true` or `false` |
| **process:** | |
| ... **markdown:** | Enable or disable the processing of markdown on the front end. Can be set `true` or `false` |
| ... **twig:** | Enable or disable the processing of twig on the front end. Can be set `true` or `false` |
| **twig_first:** | Process Twig before markdown when processing both on a page. Can be set `true` or `false` |
| **never_cache_twig:** | Enabling this will allow you to add a processing logic that can change dynamically on each page load, rather than caching the results and storing it for each page load. This can be enabled/disabled site-wide in the **system.yaml**, or on a specific page. Can be set `true` or `false` |
| **events:** | |
| ... **page:** | Enable page-level events. Can be set `true` or `false` |
| ... **twig:** | Enable Twig-level events. Can be set `true` or `false` |
| **markdown:** | |
| ... **extra:** | Enable support for Markdown Extra support (GitHub-flavored Markdown (GFM) by default). Can be set `true` or `false` |
| ... **auto_line_breaks:** | Enable automatic line breaks. Can be set `true` or `false` |
| ... **auto_url_links:** | Enable automatic HTML links. Can be set `true` or `false` |
| ... **escape_markup:** | Escape markup tags into entities. Can be set `true` or `false` |
| ... **special_chars:** | List of special characters to automatically convert to entities. Each character consumes a line below this variable. Example: `'>': 'gt'` |
| ... **valid_link_attributes:** | Valid attributes to pass through via markdown links (**Grav 1.7+**) |
| **types:** | List of valid page types. For example: `[txt,xml,html,htm,json,rss,atom]` |
| **append_url_extension:** | Append page's extension in Page URLs (e.g. `.html` results in **/path/page.html**) |
| **expires:** | Page expires time in seconds (604800 seconds = 7 days) (`no cache` is also possible) |
| **cache_control:** | Can be blank for no setting, or a [valid](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cache-Control) `cache-control` text value |
| **last_modified:** | Set the last modified date header based on file modification timestamp. Can be set `true` or `false` |
| **etag:** | Set the etag header tag. Can be set to `true` or `false` |
| **vary_accept_encoding:** | Add `Vary: Accept-Encoding` header. Can be set to `true` or `false` |
| **redirect_default_route:** | Automatically redirect to a page's default route. Can be set to `true` or `false` |
| **redirect_default_code:** | Default code to use for redirects. For example: `302` |
| **redirect_trailing_slash:** | Handle automatically or 302 redirect a trailing / URL |
| **ignore_files:** | Files to ignore in Pages. Example: `[.DS_Store] ` |
| **ignore_folders:** | Folders to ignore in Pages. Example: `[.git, .idea]` |
| **ignore_hidden:** | Ignore all Hidden files and folders. Can be set to `true` or `false` |
| **hide_empty_folders:** | If folder has no .md file, should it be hidden. Can be set to `true` or `false` |
| **url_taxonomy_filters:** | Enable auto-magic URL-based taxonomy filters for page collections. Can be set to `true` or `false` |
| **frontmatter:** | |
| ... **process_twig:** | Should the frontmatter be processed to replace Twig variables? Can be set to `true` or `false` |
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

| Property | Description |
| -------- | ----------- |
| **enabled:** | Set to `true` to enable caching. Can be set to `true` or `false` |
| **check:** | |
| ... **method:** | Method to check for updates in pages. Options: `file`, `folder`, `hash` and `none`. [more details](../../advanced/performance-and-caching#grav-core-caching) |
| **driver:** | Select a cache driver. Options are: `auto`, `file`, `apcu`, `redis`, `memcache`, and `wincache` |
| **prefix:** | Cache prefix string (prevents cache conflicts). Example: `g` |
| **purge_at:** | Scheduler: How often to purge old cache using cron `at` syntax |
| **clear_at:** | Scheduler: How often to clear the cache using cron `at` syntax |
| **clear_job_type:** | Type to clear when processing the scheduled clear job. Options: `standard` \| `all` |
| **clear_images_by_default:** | By default grav does not include processed images when cache clears, this can be enabled by setting this to `true` |
| **cli_compatibility:** | Ensures only non-volatile drivers are used (file, redis, memcache, etc.) |
| **lifetime:** | Lifetime of cached data in seconds (`0` = infinite). `604800` is 7 days |
| **gzip:** | GZip compress the page output. Can be set to `true` or `false` |
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

| Property | Description |
| -------- | ----------- |
| **cache:** | Set to `true` to enable Twig caching. Can be set to `true` or `false` |
| **debug:** | Enable Twig debug. Can be set to `true` or `false` |
| **auto_reload:** | Refresh cache on changes. Can be set to `true` or `false` |
| **autoescape:** | Autoescape Twig vars. Can be set to `true` or `false` |
| **undefined_functions:** | Allow undefined functions. Can be set to `true` or `false` |
| **undefined_filters:** | Allow undefined filters. Can be set to `true` or `false` |
| **umask_fix:** | By default Twig creates cached files as 755, fix switches this to 775. Can be set to `true` or `false` |

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

| Property | Description |
| -------- | ----------- |
| **css_pipeline:** | The CSS pipeline is the unification of multiple CSS resources into one file. Can be set to `true` or `false` |
| **css_pipeline_include_externals:** | Include external URLs in the pipeline by default. Can be set to `true` or `false` |
| **css_pipeline_before_excludes:** | Render the pipeline before any excluded files. Can be set to `true` or `false` |
| **css_minify:** | Minify the CSS during pipelining. Can be set to `true` or `false` |
| **css_minify_windows:** | Minify Override for Windows platforms. `false` by default due to ThreadStackSize. Can be set to `true` or `false` |
| **css_rewrite:** | Rewrite any CSS relative URLs during pipelining. Can be set to `true` or `false` |
| **js_pipeline:** | The JS pipeline is the unification of multiple JS resources into one file. Can be set to `true` or `false` |
| **js_pipeline_include_externals:** | Include external URLs in the pipeline by default. Can be set to `true` or `false` |
| **js_pipeline_before_excludes:** | Render the pipeline before any excluded files. Can be set to `true` or `false` |
| **js_module_pipeline** | The JS Module pipeline is the unification of multiple JS Module resources into one file. Can be set to `true` or `false` |
| **js_module_pipeline_include_externals** | Include external URLs in the pipeline by default. Can be set to `true` or `false` |
| **js_module_pipeline_before_excludes** | Render the pipeline before any excluded files. Can be set to `true` or `false` |
| **js_minify:** | Minify the JS during pipelining. Can be set to `true` or `false` |
| **enable_asset_timestamp:** | Enable asset timestamps. Can be set to `true` or `false` |
| **enable_asset_sri:** | Enable asset SRI. Can be set to `true` or `false` |
| **collections:** | This contains collections, designated as sub-items. For example: `jquery: system://assets/jquery/jquery-3.x.min.js`. [Read more about this](/themes/asset-manager#collections-and-attributes) |

### Errors

```yaml
errors:
  display: 0
  log: true
```

**Errors** セクションは、Gravでエラーをどのように表示したりログに残したりするかを決定します。

| Property | Description |
| -------- | ----------- |
| **display:** | Determines how errors are displayed. Enter either `1` for the full backtrace, `0` for Simple Error, or `-1` for System Error |
| **log:** | Log errors to `/logs` folder. Can be set to `true` or `false` |

### Log

```yaml
log:
  handler: file
  syslog:
    facility: local6
```

**log** セクションは、Gravの代替ログ機能を設定できます。

| Property | Description |
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

| Property | Description |
| -------- | ----------- |
| **enabled:** | Enable Grav debugger and following settings. Can be set to `true` or `false` |
| **provider:** | Debugger provider: Can be set to `debugbar` or `clockwork` (**Grav 1.7+**) |
| **censored:** | Censor potentially sensitive information (POST parameters, cookies, files, configuration and most array/object data in log messages). Can be set to `true` or `false` (**Grav 1.7+**) |
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

| Property | Description |
| -------- | ----------- |
| **default_image_quality:** | Default image quality to use when resampling images. For example: `85` = 85% |
| **cache_all:** | Cache all images by default. Can be set to `true` or `false` |
| **cache_perms:** | **Must be in quotes!** Default cache folder perms. Usually `'0755'` or `'0775'` |
| **debug:** | Show an overlay over images indicating the pixel depth of the image when working with retina, for example. Can be set to `true` or `false` |
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

| Property | Description |
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

| Property | Description |
| -------- | ----------- |
| **enabled:** | Enable Session support. Can be set to `true` or `false` |
| **initialize:** | Initialize session from Grav (if `false`, plugin needs to start the session) |
| **timeout:** | Timeout in seconds. For example: `1800` |
| **name:** | Name prefix of the session cookie. Use alphanumeric, dashes or underscores only. Do not use dots in the session name. For example: `grav-site` |
| **uniqueness:** | Should sessions be `path` based or `security.salt` based |
| **secure:** | Set session secure. If `true`, indicates that communication for this cookie must be over an encrypted transmission. Enable this only on sites that run exclusively on HTTPS. Can be set to `true` or `false` |
| **httponly:** | Set session HTTP only. If `true`, indicates that cookies should be used only over HTTP, and JavaScript modification is not allowed. Can be set to `true` or `false` |
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

**GPM** セクションの選択肢は、GravのGPM（Grav・パッケージ・マネージャー）を制御します。たとえば、GPMが公式のソースを使用するように制限したり、パッケージを取得する方法を選択したりできます。また、安定版リリースやテストリリースを選択したり、プロキシURLの設定もできます。

| Property | Description |
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

| Property | Description |
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

| Property | Description |
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

| Property | Description |
| -------- | ----------- |
| **yaml_compat:** | Enables YAML backwards compatibility |
| **twig_compat:** | Enables deprecated Twig autoescape setting |
| **blueprint_compat:** | Enables backward compatible strict support for blueprints |

> [!TIPS]
> **すべての** 設定ファイルをコピーして上書きする必要はありません。最小限の上書きでも、最大限でも、好きなようにできます。ただし、上書きしたい特定の設定については、**正確に同じ名前の構造** となるように確認してください。

## Site Configuration

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

| Property | Description |
| -------- | ----------- |
| **title:** | The title is a simple string variable that can be referenced whenever you want to display the name of this site |
| **author:** | |
| ... **name:** | The name of the author of the site, that can be referenced whenever you need it |
| ... **email:** | A default email for use in your site |
| **taxonomies:** | An arbitrary list of high-level types that you can use to organize your content.  You can assign content to specific taxonomy types, for example, categories or tags. Feel free to edit, or add your own |
| **metadata:** | Set default metadata for all your pages, see the [content page headers](../../content/headers) section for more details |
| **summary:** | |
| ... **size:** | A variable to override the default number of characters that can be used to set the summary size when displaying a portion of content |
| **routes:** | This is a basic map that can provide simple URL alias capabilities in Grav.  If you browse to `/something/else` you will actually be sent to `/blog/sample-3`. Feel free to edit, or add your own as needed. **Regex Replacements** (`(.*) - $1`) are now supported at the end of route aliases.  You should put these at the bottom of the list for optimal performance |
| **(custom options)** | You can create any option you like in this file and a good example is the `blog: route: '/blog'` option that is accessible in your Twig templates with `site.blog.route` |

> [!TIPS]
> ほとんど場合、このファイルの最も重要な要素は、 `Taxonomy` リストです。コンテンツでタグやカテゴリーを使いたいならば、ここでタクソノミーのリストを **定義しなければなりません。**

## Security

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

## Other Configuration Settings and Files

ユーザー設定は、完全にオプションです。デフォルトの設定を、好きなように上書きできます。この上書きは、サイト上のシステム、サイト、そしていかなるプラグインにも適用されます。

また、上記で解説した `user/config/system.yaml` や、`user/config/site.yaml` ファイルにも制限はありません。ほかの `.yaml` 設定ファイルを `user/config/` フォルダに作成できますし、Gravは自動的にそれらをピックアップするでしょう。

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

> [!TIPS]
> また、カスタムファイルを管理プラグインで編集できるようにするために、カスタムのブループリントファイルを提供することもできます。関連する[Admin クックブックセクションのレシピ](/cookbook/admin-recipes#add-a-custom-yaml-file)を確認してください。

### Config Variable Namespacing

設定ファイルへのパスは、設定オプションの **名前空間(namespace)** として使われます。

かわりに、すべてのオプションをひとつのファイルに詰めて、YAML構造を使い、設定オプションの階層を指定することもできます。この名前空間は、**パス + ファイル名 + オプション名** の組み合わせで作られます。

たとえば：あるオプション `author: "フランク スミス"` が、`plugins/myplugin.yaml` にあったとして、これは次のようにアクセスできます： `plugins.myplugin.author` 。しかしながら、`plugins.yaml` というファイルもあった場合で、そのファイルに `myplugin: author: "フランク スミス"` という名前のオプションがあった場合に、同じ `plugins.myplugin.author` によりリーチ可能です。

設定ファイルの構造を、いくつか例示します：

| File | Description |
| -------- | ----------- |
| **user/config/system.yaml**           | Global system configuration file                  |
| **user/config/site.yaml**             | A site-specific configuration file                |
| **user/config/plugins/myplugin.yaml** | Individual configuration file for myplugin plugin |
| **user/config/themes/mytheme.yaml**   | Individual configuration file for mytheme theme   |

> [!TIPS]
> 名前空間を持つ設定ファイルは、デフォルトの設定ファイルの同じパスを持つオプションをすべて上書きもしくはマスクするでしょう。

### Plugins Configuration

ほとんどの **プラグイン** は、それぞれ独自のYAML設定ファイルを持ちます。このファイルを直接編集するよりは、 `user/config/plugins/` ディレクトリにコピーすることを推奨します。これにより、プラグインのアップデートにより、設定が上書きされないことを確認できますし、設定変更可能なオプションをひとつの便利な場所に留めることができます。

もし、`user/plugins/myplugin` というプラグインがあり、設定ファイルが `user/plugins/myplugin/myplugin.yaml` にあるとき、このファイルをコピーして、 `user/config/plugins/myplugin.yaml` そのファイルをそこで編集できます。

プラグインの主ディレクトリ内に存在するYAMLファイルは、二番目に働きます。そこに書かれている設定はすべて、ユーザーフォルダのコピーに無いとしても、Gravはピックアップし、利用します。

### Themes Configuration

**テーマ** においても、プラグインと同じルールが適用されます。このため、 `user/themes/mytheme` というファイルに、`user/themes/mytheme/mytheme.yaml` という設定ファイルがあった場合、このファイルを `user/config/themes/mytheme.yaml` にコピーし、そこで編集できます。

