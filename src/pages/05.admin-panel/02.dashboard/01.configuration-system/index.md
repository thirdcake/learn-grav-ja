---
title: "System 設定"
layout: ../../../../layouts/Default.astro
---

![Admin Configuration](configuration.png)

**Configuration** ページでは、サイトの **System** 設定及び **Site** 設定にアクセスできます。加えて、PHP プロパティや、サーバー環境のプロパティ、その他のサイトの運営を決定づけるさまざまなコンポーネントのプロパティを閲覧できます。

> [!Info]  
> Configuration ページには、 `access.admin.super` もしくは `access.admin.configuration` のアクセスレベルが必要です。

**System** タブは、 `/user/config/system.yaml` ファイルで設定できることをカスタマイズできます。
これらの設定は、 Grav が操作する system に関係する機能の数によります。サイトのホームページや、キャッシュの設定、その他について、ここで設定できます。

これらの設定は、いくつかのセクションに分けられ、それぞれ、 Grav 操作の特定の側面を設定できます。

以下は、**System** タブに表示される個々の設定セクションを説明したものです。

### Content

![Admin Configuration](configuration-system-content.png)

このセクションでは、サイト内でのコンテンツ制御の基本的なプロパティを設定します。ホームとなるページや、デフォルトテーマ、その他さまざまなコンテンツ表示オプションを、ここで設定します。

| オプション | 説明 |
| :----- | :----- |
| **Home Page**  | サイトのホームページとして表示させたいページを選びます。 |
| **Default Theme**  | サイトで使うデフォルトテーマを設定します。 |
| **Process**  | どのようにページを処理するかを制御します。サイト全体ではなくページごとにも設定できます。 |
| **Timezone** | サーバーのデフォルトのタイムゾーンを上書きします。|
| **Short Date Format** | テーマで使う日付の短いフォーマットを設定します。 |
| **Long Date Format** | テーマで使う日付の長いフォーマットを設定します。 |
| **Default Ordering** | リストの中のページは、上書きされない限り、ここで設定した順序でレンダリングされます。 |
| **Default Order Direction** | リストページの方向です。 |
| **Default Page Count** | リストページの1ページあたりの最大表示数のデフォルト値。|
| **Date-based Publishing**   | 日付をもとに、自動で投稿を公開・非公開します |
| **Events** | 特定のイベントの有効化・無効化。無効化するとプラグインにフックさせないことができます。 |
| **Redirect Default Route**  | 自動でページのデフォルトのルーティングにリダイレクトします。 |

### Languages

![Admin Configuration](configuration-system-languages.png)

このセクションでは、多言語機能を設定します。

| オプション | 説明 |
| :----- | :----- |
| **Supported**  | 2文字の言語コードのカンマ区切りのリスト（たとえば、`en,fr,de`）  |
| **Translations Enabled** | Gravや、プラグイン、拡張機能で翻訳をサポートする |
| **Translations Fallback**  |  Fallback through supported translations if active language doesn't exist. |
| **Active Language in Section**     | Store the active language in the session.                                 |
| **Home Redirect Include Language** | Include language in home redirect (/en).                                  |
| **Home Redirect Include Route**    | Home redirect include route.                                              |

### HTTP Headers

![Admin Configuration](configuration-system-http.png)

HTTP ヘッダオプションは、このセクションで設定できます。ブラウザベースのキャッシュと、最適化に便利です。

| オプション | 説明 |
| :----- | :----- |
| **Expires**  | expires ヘッダを設定します。 値は秒数です。  |
| **Last Modified**  | last modified ヘッダを設定します。プロキシとブラウザキャッシュの最適化を助けます。 |
| **ETag**  | etag ヘッダを設定します。ページがいつ修正されたかの識別を助けます。 |
| **Vary Accept Encoding** |  *Vary: Accept Encoding* ヘッダ設定します。プロキシと CDN キャッシュを助けます。 |

### Markdown

![Admin Configuration](configuration-system-markdown.png)

マークダウンは、Grav のページコンテンツの大部分を占めています。このセクションでは、Markdown Extra の有効化オプションや、Grav がどのようにマークダウンを制御するかの設定ができます。

| オプション | 説明 |
| :----- | :----- |
| **Markdown Extra**   |  [Markdown Extra](https://michelf.ca/projects/php-markdown/extra/) のデフォルトでのサポートを有効化する |
| **Auto Line Breaks** | マークダウンでの自動改行を有効化する |
| **Auto URL Links**   | URL を HTML ハイパーリンクに自動変換する機能を有効化する |
| **Escape Markup**    | マークアップタグを HTML エンティティにエスケープする |

### Caching

![Admin Configuration](configuration-system-caching.png)

Grav の統合されたキャッシュ機能のおかげで、Grav は最速のフラットファイル CMS のひとつとなっています。このセクションで、サイトの主要なキャッシュ機能の設定ができます。

| オプション | 説明 |
| :----- | :----- |
| **Caching** | Grav のキャッシュの有効化/無効化をグローバルに設定 |
| **Cache Check Method** | キャッシュのチェック方法の設定。選択肢は、 **File**, **Folder**, そして **None** |
| **Cache Driver** | Grav が使用すべきキャッシュドライバの選択。 'Auto Detect' で、最適な方法を探します |
| **Cache Prefix**  | Grav キー部分の識別子。内容が分からない場合は変更しないでください。 |
| **Lifetime** | キャッシュのライフタイム秒数。  0 = 無限 |
| **Gzip Compression**   | パフォーマンス向上のために Grav ページの GZip 圧縮を有効化するかどうか |

### Twig Templating

![Admin Configuration](configuration-system-twig.png)

This section focuses on Grav's Twig templating feature. You can set Twig caching, debug, and change detection settings here.

| オプション | 説明 |
| :----- | :----- |
| **Twig Caching**         | Control the Twig caching mechanism. Leave this enabled for best performance.                  |
| **Twig Debug**           | Allows the option of not loading the Twig Debugger extension.                                 |
| **Detect Changes**       | Twig will automatically recompile the Twig cache if it detects any changes in Twig templates. |
| **Autoescape Variables** | Autoescapes all variables. This will break your site most likely.                             |

### Assets

![Admin Configuration](configuration-system-assets.png)

This section deals with assets handling, including CSS and JavaScript assets.

| オプション | 説明 |
| :----- | :----- |
| **CSS Pipeline**                | The CSS pipeline is the unification of multiple CSS resources into one file.    |
| **CSS Minify**                  | Minify the CSS during pipelining.                                               |
| **CSS Minify Windows Override** | Minify Override for Windows platforms. False by default due to ThreadStackSize. |
| **CSS Rewrite**                 | Rewrite any CSS relative URLs during pipelining.                                |
| **JavaScript Pipeline**         | The JS pipeline is the unification of multiple JS resources into one file.      |
| **JavaScript Minify**           | Minify the JS during pipelining.                                                |
| **Enable Timestamps on Assets** | Enable asset timestamps.                                                        |
| **Collections**                 | Add individual asset collections.                                               |

### Error Handler

![Admin Configuration](configuration-system-error.png)

You can set how Grav handles error reporting and display here. This is a useful tool to have during site development.

| オプション | 説明 |
| :----- | :----- |
| **Display Error** | Display full backtrace-style error page. |
| **Log Errors**    | Log errors to /logs folder.              |

### Debugger

![Admin Configuration](configuration-system-debugger.png)

Like error handling, Grav's integrated debugging tools give you the ability to locate and troubleshoot issues. This is especially useful during development.

| オプション | 説明 |
| :----- | :----- |
| **Debugger**                  | Enable Grav debugger and following settings.                           |
| **Debug Twig**                | Enable debugging of Twig templates.                                    |
| **Shutdown Close Connection** | Close the connection before calling onShutdown(). false for debugging. |

### Media

![Admin Configuration](configuration-system-media.png)

This section determines how Grav handles media content. Image quality and other media handling options are configured here.

| オプション | 説明 |
| :----- | :----- |
| **Default Image Quality**      | Default image quality to use when resampling or caching images (85%).                                     |
| **Cache All Images**           | Run all images through Grav's cache system even if they have no media manipulations.                      |
| **Image Debug Watermark**      | Show an overlay over images indicating the pixel depth of the image when working with Retina for example. |
| **Enable Timestamps on Media** | Appends a timestamp based on last modified date to each media item.                                       |

!! Caching images that have already been optimised (outside of Grav) could result in the output file being a much larger filesize than the original. This is due to a bug in the Gregwar image library and not directly related to Grav (see this [open issue](https://github.com/Gregwar/Image/issues/115) for more information). The alternative is to set "Cache All Images" to No

### Session

![Admin Configuration](configuration-system-session.png)

This section gives you the ability to enable session support, set timeout limits, and the name of the session cookie used to handle this information.

| オプション | 説明 |
| :----- | :----- |
| **Enable**  | Enable session support within Grav.                                                                                                          |
| **Timeout** | Sets the session timeout in seconds.                                                                                                         |
| **Name**    | An identifier used to form the name of the session cookie. Use alphanumeric, dashes or underscores only. Do not use dots in the session name |

### Advanced

![Admin Configuration](configuration-system-advanced.png)

This section contains advanced system options.

| オプション | 説明 |
| :----- | :----- |
| **Absolute URLs**       | Absolute or relative URLs for `base_url`.                                  |
| **Parameter Separator** | Separater for passed parameters that can be changed for Apache on Windows. |

