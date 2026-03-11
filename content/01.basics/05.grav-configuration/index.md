---
title: 'config 設定'
layout: ../../../layouts/Default.astro
lastmod: '2025-11-08'
description: 'Grav の設定は YAML 形式で簡単に管理できます。 system.yaml を中心に各種設定ファイルを紹介します。'
---

> [!訳注]  
> 「config 設定」というタイトルは、 config 自体に「設定」の意味があるので、変な言葉なのですが、 Grav の設定ファイルとしては blueprint もあるため、それとの区別を分かりやすくするために 「config 設定」と訳しました。ここで説明されているのは、 blueprint 以外の設定ファイルのことであるとイメージしてもらえたらと思います。

すべての Grav の設定ファイルは、 [YAML 構文](../../08.advanced/11.yaml/) で書かれており、拡張子は、 `.yaml` です。  
YAML は、非常に直感的なので、読み書きともに簡単ですが、利用可能な構文を完全に理解するには、 [高度な設定の章の YAML ページ](../../08.advanced/11.yaml/) をチェックしてください。

> [!Tip]  
> 本番サイトを、安全に最適化するクイックガイドとして、 [セキュリティ > 設定](../../13.security/02.configuration/) の章を参照してください。

<h2 id="system-configuration">システム設定</h2>

Grav は、ユーザーができるだけ簡単に扱えることを念頭に置いており、 config の設定も簡単です。  
Grav には、ベターなオプションが初期設定されており、これらは `system/config/system.yaml` ファイルに含まれています。

しかしながら、 **絶対にこのファイルを変更しないでください** 。  
かわりに、あらゆる設定変更は、 `user/config/system.yaml` というファイルに保存してください。  
このファイルに、同じ構造で、同じ名前の設定をすれば、すべて初期設定から上書きされます。

> [!Warning]  
> 一般的に、 `system/` フォルダ内のどんな内容も、 **決して** 変更するべきではありません。ユーザーがすること（コンテンツを作る、プラグインをインストールする、設定を編集するなど）は、 `user/` フォルダ内で行ってください。こうすることで、アップグレードが簡単になりますし、バックアップや同期のために、変更内容をひとつの場所にまとめておくことができるようになります。 

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
| -------- | ------ |
| **absolute_urls:** | [テーマ変数](../../03.themes/06.theme-vars/#base-url-variable) の `base_url` を絶対 URL にするか、相対 URL にするか|
| **timezone:** | 受け取れる値は、 [こちら](https://www.php.net/manual/ja/timezones.php) （日本の場合は `Asia/Tokyo` ） |
| **default_locale:** | デフォルトのロケール（システムにとってのデフォルト） |
| **param_sep:** | Grav での URL のパラメータに使います。変更の影響が分からないうちは触らないでください。 Windows 上の Apache web サーバーでの運用中は、 Grav は自動的に `;` を設定します。 |
| **wrapped_site:** | テーマやプラグインに、 Grav が他のプラットフォームに含まれているかを知らせます。 `true` もしくは `false` が使えます |
| **reverse_proxy_setup:** |  リバースプロキシで運用している場合で、プロキシとは異なるポート番号の場合。 `true` または `false` の値 |
| **force_ssl:** | 有効化すると、 Grav は強制的に HTTPS でアクセスします（注意：理想的な解決策ではありません）。 `true` か `false` が使えます |
| **force_lowercase_urls:** | 大文字/小文字を区別した URL をサポートしたい場合、これを `false` にしてください |
| **custom_base_url:** | `base_url` を手動で設定するならここでしてください |
| **username_regex:** | ユーザー名として使える文字列の制限。上記の例は小文字、数字、ダッシュ、アンダースコアで、 3-16 文字 |
| **pwd_regex:** | パスワードとして使える文字列の制限。上記の例は1つ以上の数字、1つ以上の大文字と小文字で、8文字以上 |
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

**Languages** エリアは、サイトの言語を設定します。  
サポート対象とする言語の種類、 URL のデフォルトの言語の指定、翻訳の設定などが含まれます。  
**Languages** エリアの内訳は、以下の通りです：

| プロパティ | 説明 |
| -------- | ----------- |
| **supported:** | サポート対象言語のリスト。例： `[en, fr, de]` |
| **default_lang:** | 入力がない場合、上記の supported に最初に書いた言語になります。サポート対象言語のリスト中から選ばなければいけません |
| **include_default_lang:** | URL すべてに、default lang の接頭辞を追加する。 `true` もしくは `false` |
| **include_default_lang_file_extension:** | 有効化すると、ページを保存するときに、デフォルトの言語をファイル拡張子に追加します（例： `.en.md` ）デフォルト言語で `.md` ファイル拡張子を使い続けたい場合は、無効化してください。 `true` または `false` の値 (**Grav 1.7.0 以上**) |
| **pages_fallback_only:** | サポートされている言語からのみページコンテンツを探してフォールバックします。 `true` または `false` の値 |
| **translations:** | デフォルトで翻訳を有効化します。 `true` または `false` の値 |
| **translations_fallback:** | 有効言語が存在しない場合に、サポートされた翻訳でフォールバックします `true` または `false` の値 |
| **session_store_active:** | セッションに有効言語を保存します `true` または `false` の値 |
| **http_accept_language:** |  ブラウザの http\_accept\_language ヘッダをもとに言語設定を試みます。 `true` または `false` の値 |
| **override_locale:** | デフォルトのもしくはシステムのロケールを言語特有のものに上書きします。 `true` または `false` の値 |
| **content_fallback:** | デフォルトでは、コンテンツが翻訳されていない場合、 Grav はデフォルト言語のコンテンツを表示します。言語ごとにこの挙動を上書きする設定です。(**Grav 1.7.0 以上**) |

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
| **hide_in_urls:** | [home ページへのルーティングを隠す](../../02.content/10.routing/#hiding-the-home-route) 。 `true` または `false` の値 |

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

`system/config/system.yaml` ファイルの **Pages** セクションでは、多くの主要なテーマに関係した設定を行います。  
たとえば、サイトをレンダリングするテーマを設定したり、ページの表示順や、 twig 、マークダウンプロセスのデフォルト設定、などです。  
このセクションには、ページの表示に影響を与える決定がたくさんあります。

| プロパティ | 説明 |
| -------- | ------ |
| **type:** | フロントエンドで、 **Flex Pages** を有効化するための実験的設定。 `flex` にすると有効化し、そうでない場合は `regular` とします。デフォルトでは `regular` です。(**Grav 1.7+**) |
| **theme:** | ここでデフォルトテーマを設定します。デフォルトは `quark` です。 |
| **order:** | |
| ... **by:** | ページ順。 `default` （`01.` `02.` の順）, `alpha` （アルファベット）もしくは `date` （日付） |
| ... **dir:** | デフォルトのページを並べる方向。 `asc` （昇順）もしくは `desc` （降順） |
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
| **never_cache_twig:** | これを有効化すると、結果をキャッシュ・保存せずに、ページの読み込みごとに動的に変化するロジック処理を追加できます。 **system.yaml** では、サイト全体での有効化/無効化ができます。 [特定のページ単位で設定する方法](../../02.content/02.headers/#never-cache-twig) もあります。 `true` または `false` の値 |
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
| **expires:** | ページの有効期限（秒） (604800 秒 = 7 日間) (`no cache` も可能) |
| **cache_control:** | 設定しない場合は空欄にできます。もしくは、 [有効な](https://developer.mozilla.org/ja/docs/Web/HTTP/Reference/Headers/Cache-Control) `cache-control` テキストの値です |
| **last_modified:** | ファイルの修正タイムスタンプをもとに、最終更新日のヘッダを設定します。 `true` または `false` の値 |
| **etag:** | HTTP レスポンスヘッダの etag を設定。 `true` または `false` の値 |
| **vary_accept_encoding:** | HTTP レスポンスヘッダに `Vary: Accept-Encoding` を追加。 `true` または `false` の値 |
| **redirect_default_route:** | 自動的に、ページのデフォルトのルーティングへリダイレクトします。 `true` または `false` の値 |
| **redirect_default_code:** | リダイレクトするときのデフォルトの HTTP ステータスコード。たとえば： `302` |
| **redirect_trailing_slash:** | URL の末尾にスラッシュが無い場合に、そのまま処理するか、末尾にスラッシュが付いたものへ302リダイレクトするかを制御します。 |
| **ignore_files:** | pages 内で無視するファイル。 具体例： `[.DS_Store] ` |
| **ignore_folders:** | pages 内で無視するフォルダ。具体例： `[.git, .idea]` |
| **ignore_hidden:** | 隠しファイルや隠しフォルダを無視します。 `true` または `false` の値 |
| **hide_empty_folders:** | フォルダに .md ファイルが無い場合、それを隠します。 `true` または `false` の値 |
| **url_taxonomy_filters:** | ページコレクションにおいて、 URL をもとにタクソノミーフィルターを自動で有効化します。 `true` または `false` の値 |
| **frontmatter:** | |
| ... **process_twig:** | フロントマターで Twig 変数を置き換える処理がされるべきかどうか？ `true` または `false` の値 |
| ... **ignore_fields:** | Twig 変数を含みうるフィールドにおいて処理されないようにします。具体例： `['form','forms']` |

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

**キャッシュ** セクションでは、サイトのキャッシュを設定できます。  
メソッドを有効化したり、無効化したり、選んだりできます。

| プロパティ | 説明 |
| -------- | ----------- |
| **enabled:** | `true` にするとキャッシュを有効化します。 `true` または `false` の値 |
| **check:** | |
| ... **method:** | pages 内で、アップデートをチェックする方法。 オプション： `file`, `folder`, `hash` そして `none` 。 [より詳しくはこちら](../../08.advanced/02.performance-and-caching#grav-core-caching) |
| **driver:** | キャッシュドライバーの選択。オプション： `auto`, `file`, `apcu`, `redis`, `memcache`, そして `wincache` |
| **prefix:** | （キャッシュのコンフリクトを防ぐために）キャッシュの接頭辞として利用する文字列。 具体例： `g` |
| **purge_at:** | スケジューラー： cron の `at` 構文を使って、古いキャッシュのパージ（全削除）頻度を設定します。 |
| **clear_at:** | スケジューラー： cron の `at` 構文を使って、古いキャッシュのクリア頻度を設定します。 |
| **clear_job_type:** | スケジュールされたキャッシュクリアジョブの処理中にクリアするタイプ。オプション： `standard` \| `all` |
| **clear_images_by_default:** | デフォルトでは、キャッシュクリア時に処理画像はクリア対象に含まれません。デフォルトでクリアする場合はこれを `true` にしてください。 |
| **cli_compatibility:** | Ensures only non-volatile drivers are used (file, redis, memcache, etc.) |
| **lifetime:** | キャッシュデータの寿命（秒数）。 (`0` = 無限 ). `604800` の場合、7日間 |
| **gzip:** | ページの出力に GZip 圧縮します。 `true` または `false` の値 |
| **allow_webserver_gzip:** | This option will change the header to `Content-Encoding: identity` allowing gzip to be more reliably set by the webserver although this usually breaks the out-of-process `onShutDown()` capability.  The event will still run, but it won't be out of process, and may hold up the page until the event is complete |
| **redis:** | |
| **... socket:** | redis のソケットファイルへのパス |
| **... password:** | オプションのパスワード |
| **... database:** | オプションのデータベース ID |

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
| **cache:** | `true` にすると Twig キャッシュを有効化します。 `true` または `false` の値 |
| **debug:** | Twig デバッグの有効化。 `true` または `false` の値 |
| **auto_reload:** | 変更時キャッシュをリフレッシュ。 `true` または `false` の値 |
| **autoescape:** | Twig 変数をオートエスケープ。 `true` または `false` の値 |
| **undefined_functions:** | 未定義の関数を許容します。 `true` または `false` の値 |
| **undefined_filters:** | 未定義のフィルタを許容します。 `true` または `false` の値 |
| **umask_fix:** | デフォルトでは、 Twig はキャッシュファイルを 755 パーミッションで作成します。これを 775 に変更します。 `true` または `false` の値 |

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
| **css_pipeline:** | CSS pipeline とは、複数の CSS リソースを1つのファイルにまとめることです。 `true` または `false` の値 |
| **css_pipeline_include_externals:** | 外部 URL の CSS をパイプラインにデフォルトで含めます。 `true` または `false` の値 |
| **css_pipeline_before_excludes:** | 除外するファイルよりも先にパイプラインをレンダリングします。 `true` または `false` の値 |
| **css_minify:** | パイプライン中に CSS をミニファイします。 `true` または `false` の値 |
| **css_minify_windows:** | Windows 用にミニファイを上書きします。 ThreadStackSize のため、デフォルトで `false` です。 `true` または `false` の値 |
| **css_rewrite:** | パイプライン中のすべての CSS 相対 URL を書き換えます。 `true` または `false` の値 |
| **js_pipeline:** | JS pipeline とは、複数の JS リソースを1つのファイルにまとめることです。 `true` または `false` の値 |
| **js_pipeline_include_externals:** | 外部 URL の JS をパイプラインにデフォルトで含めます。 `true` または `false` の値 |
| **js_pipeline_before_excludes:** | 除外するファイルより先にパイプラインをレンダリングします。 `true` または `false` の値 |
| **js_module_pipeline** | JS Module pipeline とは、複数の JS モジュールのリソースを1つのファイルにまとめることです。 `true` または `false` の値 |
| **js_module_pipeline_include_externals** | 外部 URL の JS モジュールをパイプラインにデフォルトで含めます。 `true` または `false` の値 |
| **js_module_pipeline_before_excludes** | 除外するファイルより先にパイプラインをレンダリングします。 `true` または `false` の値 |
| **js_minify:** | パイプライン中に JS をミニファイします。 `true` または `false` の値 |
| **enable_asset_timestamp:** | アセットのタイムスタンプを有効化。 `true` または `false` の値 |
| **enable_asset_sri:** | アセットの SRI を有効化。 `true` または `false` の値 |
| **collections:** | サブアイテムとして設計されたコレクションを含めます。 例えば： `jquery: system://assets/jquery/jquery-3.x.min.js` 。 [より詳しくは、こちらを参照](../../03.themes/07.asset-manager/#named-assets-and-collections) |

### Errors

```yaml
errors:
  display: 0
  log: true
```

**Errors** セクションは、Gravでのエラーの表示やログ記録の方法を決定します。

| プロパティ | 説明 |
| -------- | ----------- |
| **display:** | エラーの表示方法を決定します。完全にバックトレースする場合 `1` 、シンプルなエラー表示は `0` 、システムエラーは `-1` 。 |
| **log:** | `/logs` フォルダにエラーログを残します `true` または `false` の値 |

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
| **handler:** | ログハンドラー。現在、以下をサポートします： `file` \| `syslog` |
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
| **enabled:** | Grav のデバッガーを有効化し、以下を設定します。 `true` または `false` の値 |
| **provider:** | デバッガーのプロバイダ： `debugbar` もしくは `clockwork` を設定します (**Grav 1.7 以上**) |
| **censored:** | 潜在的にセンシティブな情報を検閲します。 (POST パラメータ, cookies, ファイル, config 設定 そしてログメッセージ中の多くの array/object データ). `true` または `false` の値 (**Grav 1.7 以上**) |
| **shutdown:** | |
| ... **close_connection:** | `onShutdown()` が呼ばれる前に接続を閉じます。 `false` for debugging |

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
| -------- | ------ |
| **default_image_quality:** | 画像の再サンプリング時に使うデフォルト画質。例えば： `85` = 85% |
| **cache_all:** | デフォルトで全画像をキャッシュします。 `true` または `false` の値 |
| **cache_perms:** | **クオート( `'` )で囲ってください！** キャッシュフォルダのデフォルトのパーミッション。通常は： `'0755'` もしくは `'0775'` |
| **debug:** | 画像のピクセル深度を示すオーバーレイを表示します（たとえば、retina を扱う場合） `true` または `false` の値 |
| **auto_fix_orientation:** | Try to automatically fix images uploaded with non-standard rotation |
| **seofriendly:** | SEO に親和的に処理された画像名 |
| **cls:** | Cumulative Layout Shift. [More details](https://web.dev/optimize-cls/) |
| **... auto_sizes:** | 画像に自動的に height/width を追加します |
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
| **enable_media_timestamp:** | メディアのタイムスタンプを有効化 |
| **unsupported_inline_types:** | インライン表示のサポート対象となるメディアタイプの配列。これらのファイルタイプは、 `[]` 角カッコ内に書いてください |
| **allowed_fallback_types:** | ページルーティング経由でのアクセスがあったときに、見つかった場合に表示して良いファイルのメディアタイプの配列。これらのファイルタイプは、 `[]` 角カッコ内に書いてください |
| **auto_metadata_exif:** | 可能な場合に、 Exif データからメタデータファイルを自動作成 |

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
| **enabled:** | セッションを有効化 `true` または `false` の値 |
| **initialize:** | Grav からセッションを初期化（ `false` の場合、プラグインがセッションをスタートさせる必要があります） |
| **timeout:** | タイムアウトの秒数 例えば： `1800` |
| **name:** | セッション cookie のプレフィックス名。アルファベット、ダッシュ、アンダースコアのみ使えます。セッション名内にドットは使えません。例えば： `grav-site` |
| **uniqueness:** | セッションを `path` ベースとするか `security.salt` ベースとするか |
| **secure:** | セッションを安全に設定。 `true` のとき、暗号化された通信上でのみやりとりされる。この設定は、 HTTPS で実行されているサイトでのみ有効化してください。 `true` または `false` の値 |
| **httponly:** | セッションを HTTP only に設定。 `true` のとき、クッキーは HTTP のときのみ利用でき、 JavaScript による修正は許可しません。 `true` または `false` の値 |
| **samesite:** | セッションを SameSite に設定。設定可能な値は、 Lax, Strict 及び None 。  [こちら](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie/SameSite) を参照してください |
| **domain:** | レスポンスにセッション cookie の domain 属性を明記する。 Docker コンテナのように、サイトドメインを書き換えている場合にのみ利用してください。 |
| **path:** | レスポンスにセッション cookie の path 属性を明記する。Docker コンテナのようにサイトのサブフォルダを書き換えている場合のみ利用してください。 |

### GPM

```yaml
gpm:
  releases: stable
  proxy_url:
  method: 'auto'
  verify_peer: true
  official_gpm_only: true
```

**GPM** セクションの選択肢は、 Grav の GPM （Grav パッケージ・マネージャー）を制御します。  
たとえば、 GPM が公式のソースを使用するように制限したり、パッケージを取得する方法を選択したりできます。  
また、安定版リリースやテストリリースを選択したり、プロキシ URL の設定もできます。

| プロパティ | 説明 |
| -------- | ------ |
| **releases:** | `stable` もしくは `testing` を設定し、ビルドを最新の stable （安定版）にするか、 testing （テスト版）にするか決定します |
| **proxy_url:** | GPM 用の手動のプロキシ URL を設定。例えば： `127.0.0.1:3128` |
| **method:** | 値は、 `'curl'`, `'fopen'` もしくは `'auto'` のいずれか。 `'auto'` は、まず fopen を試し、利用できない場合は cURL を試します |
| **verify_peer:** | 一部のシステム（ほとんど Windows ）では、 SSL 認証が検証できず、 GPM を接続できません。この設定を無効にすると解決することがあります |
| **official_gpm_only:** | デフォルトでは、 GPM の直接インストールは、安全性確保のため、公式の GPM プロキシー経由の URL のみ許可します。他のソースを許可するには、これを無効化してください |

### Accounts

```yaml
accounts:
  type: regular
  storage: file
```

Accounts 設定は、 Flex Users の新しい体験ができます。  
基本的に、ユーザは、より力強くパフォーマンスの良い Flex objects として保存されます。

| プロパティ | 説明 |
| -------- | ------ |
| **type:** | アカウントタイプ： `regular` もしくは `flex` |
| **storage:** | Flex ストレージタイプ： `file` もしくは `folder` |

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

Flex Objects の cache は、**Grav 1.7** で新しく追加されました。  
これは、すべての Flex types のためのデフォルト設定ですが、それぞれの `Flex Directory` で上書き可能です。

| プロパティ | 説明 |
| -------- | ------ |
| **cache:** | (**Grav 1.7+**) |
| **... index:** | (**Grav 1.7+**) |
| **... ... enabled:** | Flex インデックスのキャッシュを有効化するには true を設定。 ファイル内のタイムスタンプをキャッシュするために使われます (**Grav 1.7+**) |
| **... ... lifetime:** | キャッシュされたインデックスの有効秒数 (0 = 無限) (**Grav 1.7+**) |
| **... object:** | (**Grav 1.7+**) |
| **... ... enabled:** | Flex オブジェクトのキャッシュを有効化するには true を設定。 オブジェクトデータをキャッシュするために使われます (**Grav 1.7+**) |
| **... ... lifetime:** | キャッシュされたオブジェクトの有効秒数 (0 = 無限) (**Grav 1.7+**) |
| **... render:** | (**Grav 1.7+**) |
| **... ... enabled:** | Flex レンダリングのキャッシュを有効化するには true を設定。 レンダリングされた出力をキャッシュするために使われます (**Grav 1.7+**) |
| **... ... lifetime:** | キャッシュされた HTML の有効秒数 (0 = 無限) (**Grav 1.7+**) |

### Strict Mode

```yaml
strict_mode:
  yaml_compat: true
  twig_compat: true
  blueprint_compat: false
```

Strict モードは、新しいバージョンの YAML と Twig プロセッサに移行することで、将来のバージョンの Grav への移行がより簡単になります。  
これらは、すべてのサードパーティ製拡張機能と互換性があるとは限りません。

| プロパティ | 説明 |
| -------- | ------ |
| **yaml_compat:** | YAML 後方互換性を有効化する |
| **twig_compat:** | 非推奨の Twig のオートエスケープ設定を有効化する |
| **blueprint_compat:** | ブループリントへの厳密なサポートについて後方互換性を有効化する |

> [!Info]  
> **すべての** 設定ファイルをコピーして上書きする必要はありません。最小限の上書きでも、最大限でも、好きなようにできます。ただし、上書きしたい特定の設定については、**正確に同じ名前の構造** となるように確認してください。

<h2 id="site-configuration">サイト設定</h2>

`system.yaml` と同様に、 Grav には、デフォルトの `site.yaml` という設定ファイルも提供されています。  
これは、フロントエンドの特定の設定、たとえば著者名、著者メールアドレスや、タグ設定なども設定されています。  
system.yaml と同じように、 `user/config/site.yaml` に独自の設定ファイルを作ることで、上書きできます。  
このファイルを使って、コンテンツやテンプレートで参照したい任意の設定オプションが使えます。

Grav が提供するデフォルトの `system/config/site.yaml` ファイルは、次のようなものです：

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
| -------- | ------ |
| **title:** | タイトルとは、シンプルな文字列の変数で、サイト名を表示したいときにいつでも参照されるものです |
| **author:** | |
| ... **name:** | サイトの著者名です。必要な場合にいつでも参照できます |
| ... **email:** | サイトで利用するデフォルトの email |
| **taxonomies:** | コンテンツをタイプごとに分けて整理するために使う任意のリストです。コンテンツを、特定のタクソノミータイプに割り当てられます。たとえば、カテゴリーやタグのようなタイプです。自由に編集し、独自のものを追加してください |
| **metadata:** | 全ページに付いてデフォルトのメタデータを設定。より詳しくは、 [コンテンツページのヘッダー](../../02.content/02.headers/) セクションを参照してください |
| **summary:** | |
| ... **size:** | コンテンツの一部を表示する際の要約文のデフォルトの文字数を上書きする変数 |
| **routes:** | Grav で提供されるシンプルな URL エイリアス（別名）機能を利用できる基本的な対応関係です。上記の例では、 `/something/else` をブラウザ表示すると、実際には `/blog/sample-3` が表示されます。自由に編集し、必要なものを追加してください。 **正規表現による書き換え** (`(.*) - $1`) が、新たにサポートされました。パフォーマンスの最適化のため、これを書く時はリストの最後にしてください |
| **(カスタムオプション)** | このファイルには、お好みのオプションを作成できます。分かりやすい具体例としては： `blog: route: '/blog'` オプションで、これは Twig から `site.blog.route` によりアクセスできます |

> [!Info]  
> ほとんど場合、このファイルの最も重要な要素は、 `Taxonomy` リストです。コンテンツでタグやカテゴリーを使いたいならば、ここでタクソノミーのリストを **定義しなければなりません。**

<h2 id="security">セキュリティ</h2>

セキュリティを強化するために、 `system/config/security.yaml` ファイルがあります。  
これは、いくつかのデフォルト設定が安全側でしてあり、コンテンツを **保存** したり、**ツール** の新しい **Reports** セクションに、管理パネルプラグインで使われます。

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

ユーザー設定は、完全に任意です。  
デフォルトの設定を、好きなように上書きできます。  
この上書きは、サイト上のシステム、サイト、そしていかなるプラグインにも適用されます。

また、上記で解説した `user/config/system.yaml` や、`user/config/site.yaml` ファイルにも制限はありません。  
ほかの `.yaml` 設定ファイルを `user/config/` フォルダに作成できますし、 Grav では、自動的にそれらが読み込まれます。

たとえば、`user/config/data.yaml` という新しい設定ファイルがあったとして、このファイルの yaml 変数で count を呼び出すとします：

```
count: 39
```

この変数は、 Twig テンプレートで、次のような構文を使ってアクセスできます：

```
{{ config.data.count }}
```

また、プラグインからは、 PHP によって、次のようなコードでアクセスできます：

```
$count_var = Grav::instance()['config']->get('data.count');
```

> [!Note]  
> また、カスタムファイルを管理プラグインで編集できるようにするために、カスタムのブループリントファイルを提供することもできます。関連する[管理パネルプラグイン クックブックセクションのレシピ](../../10.cookbook/04.admin-recipes/#add-a-custom-yaml-file) を確認してください。

<h3 id="config-variable-namespacing">設定変数の名前空間</h3>

設定ファイルへのパスは、設定オプションの **名前空間（namespace）** として使われます。

かわりに、すべてのオプションをひとつのファイルに詰めて、 YAML 構造を使い、設定オプションの階層を指定することもできます。この名前空間は、**パス + ファイル名 + オプション名** の組み合わせで作られます。

たとえば：あるオプション `author: "フランク スミス"` が、 `plugins/myplugin.yaml` にあったとして、これは次のようにアクセスできます： `plugins.myplugin.author` 。  
しかしながら、 `plugins.yaml` というファイルもあった場合で、そのファイルに `myplugin: author: "フランク スミス"` という名前のオプションがあった場合に、同じ `plugins.myplugin.author` によりたどりつけます。

設定ファイルの構造を、いくつか例示します：

| ファイル名 | 説明 |
| -------- | ------ |
| **user/config/system.yaml**           | グローバルなシステム設定ファイル |
| **user/config/site.yaml**             | サイト固有の設定ファイル |
| **user/config/plugins/myplugin.yaml** | 「myplugin」プラグインの個別設定ファイル |
| **user/config/themes/mytheme.yaml**   | 「mytheme」テーマの個別設定ファイル |

> [!Info]  
> 名前空間を持つ設定ファイルは、デフォルトの設定ファイルの同じパスを持つオプションをすべて上書きもしくはマスクします。

<h3 id="plugins-configuration">プラグインの設定</h3>

ほとんどの **プラグイン** は、それぞれ独自の YAML 設定ファイルを持ちます。  
このファイルを直接編集するよりは、 `user/config/plugins/` ディレクトリにコピーすることを推奨します。  
これにより、プラグインのアップデートにより、設定が上書きされないことを確認できますし、設定変更可能なオプションをひとつの便利な場所に留めることができます。

もし、`user/plugins/myplugin` というプラグインがあり、設定ファイルが `user/plugins/myplugin/myplugin.yaml` にあるとき、このファイルをコピーして、 `user/config/plugins/myplugin.yaml` そのファイルをそこで編集できます。

プラグインの主ディレクトリ内に存在するYAMLファイルは、二番目に働きます。そこに書かれている設定はすべて、ユーザーフォルダのコピーに無いとしても、Gravはピックアップし、利用します。

<h3 id="themes-configuration">テーマの設定</h3>

**テーマ** においても、プラグインと同じルールが適用されます。  
このため、 `user/themes/mytheme` というファイルに、 `user/themes/mytheme/mytheme.yaml` という設定ファイルがあった場合、このファイルを `user/config/themes/mytheme.yaml` にコピーし、そこで編集できます。

