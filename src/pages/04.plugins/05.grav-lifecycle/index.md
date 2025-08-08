---
title: Gravのライフサイクル
layout: ../../../layouts/Default.astro
lastmod: '2025-08-07'
description: 'Grav 処理の開始から終わりまでの、主要な処理内容と、そこで発火するイベントについて解説します。'
---

> [!訳注]  
> このページの内容は、色分けが無いととても見にくいのですが、追加 CSS の処理がうまくいかず、現状このような見た目になっています。基本的には、 [翻訳元のページ](https://learn.getgrav.org/plugins/grav-lifecycle) を参照してください。

プラグインから Grav を拡張するベストな方法を理解するために、 Grav のプロセスを知っておくことはしばしば有益です。  
Grav のライフサイクルは、以下のとおりです：

### index.php
1. PHP バージョンをチェックし、 **7.1.3** 以上で実行されていることを確認
1. Class loader 初期化
1. Grav インスタンスを取得
    ### Grav.php
    1. インスタンスが存在しないので、 `load()` 呼び出し
    1. 追加: `loader`
    1. 追加し、初期化: `debugger`
    1. 追加: `grav` (廃止)
    1. 登録: Default Services
    1. 登録: Service Providers
        1. Accounts Service Provider
            1. 追加: `permissions` (1.7)
            1. 追加: `accounts` (1.6)
            1. 追加: `user_groups` (1.7)
            1. 追加: `users` *(deprecated)*
        1. Assets Service Provider
            1. 追加: `assets`
        1. Backups Service Provider
            1. 追加: `backups` (1.6)
        1. Config Service Provider
            1. 追加: `setup`
            1. 追加: `blueprints`
            1. 追加: `config`
            1. 追加: `languages`
            1. 追加: `language`
        1. Error Service Provider
            1. 追加: `error`
        1. Filesystem Service Provider
            1. 追加: `filesystem`
        1. Flex Service Provider
            1. 追加: `flex` (1.7)
        1. Inflector Service Provider
            1. 追加: `inflector`
        1. Logger Service Provider
            1. 追加: `log`
        1. 出力: Service Provider
            1. 追加: `output`
        1. Pages Service Provider
            1. 追加: `pages`
            1. 追加: `page`
        1. Request Service Provider
            1. 追加: `request` (1.7)
        1. Scheduler Service Provider
            1. 追加: `scheduler` (1.6)
        1. Session Service Provider
            1. 追加: `session`
            1. 追加: `messages`
        1. Streams Service Provider
            1. 追加: `locator`
            1. 追加: `streams`
        1. Task Service Provider
            1. 追加: `task`
            1. 追加: `action`
        1. Simple Service Providers
            1. 追加: `browser`
            1. 追加: `cache`
            1. 追加: `events`
            1. 追加: `exif`
            1. 追加: `plugins`
            1. 追加: `taxonomy`
            1. 追加: `themes`
            1. 追加: `twig`
            1. 追加: `uri`
1. `Grav::process()` を呼び出す
    ### Grav.php
    1. 実行: 初期化: Processor
        1. Configuration
            1. 初期化: `$grav['config']`
            1. 初期化: `$grav['plugins']`
        1. Logger
            1. 初期化: `$grav['log']`
        1. Errors
            1. 初期化: `$grav['errors']`
            1. 登録: PHP error handlers
        1. Debugger
            1. 初期化: `$grav['debugger']`
        1. Handle debugger requests
        1. output buffering を開始
        1. Localization
            1. ロケールとタイムゾーンを設定
        1. Plugins
            1. 初期化: `$grav['plugins']`
        1. Pages
            1. 初期化: `$grav['pages']`
        1. Uri
            1. 初期化: `$grav['uri']`
            1. 追加: `$grav['base_url_absolute']`
            1. 追加: `$grav['base_url_relative']`
            1. 追加: `$grav['base_url']`
        1. リダイレクト制御
            1. リダイレクト時 `system.pages.redirect_trailing_slash` が true であれば最後のスラッシュを URL に追加
        1. Accounts
            1. 初期化: `$grav['accounts']`
        1. Session
            1. 初期化: `$grav['session']` もし `system.session.initialize` が `true` の場合
    1. 実行: Plugins Processor
        1. 発火: **onPluginsInitialized** イベント
    1. 実行: Themes Processor
        1. 初期化: `$grav['themes']`
        1. 発火: **onThemeInitialized** イベント
    1. 実行: Request Processor
        1. 初期化: `$grav['request']`
        1. 発火: **onRequestHandlerInit** イベント。以下も一緒に [request, handler]
        1. もし response がイベント内に設定されていれば、それ以上の処理を止め、 response を出力
    1. 実行: Tasks Processor
        1. もし request に次の属性があるとき (_controller.class_) 及び (_task_ または _action_):
            1. 実行: そのコントローラー
            1. もし `NotFoundException`: continue (task と action をチェックする)
            1. もし response code 418: continue (task と action を無視する)
            1. それ以外の場合: それ以上の処理を止め、 response を出力
        1. もし _task_:
            1. 発火: **onTask** イベント
            1. 発火: **onTask.[TASK]** イベント
        1. もし _action_:
            1. 発火: **onAction** イベント
            1. 発火: **onAction.[ACTION]** イベント
    1. 実行: Backups Processor
        1. 初期化: `$grav['backups']`
        1. 発火: **onBackupsInitialized** イベント
    1. 実行: Scheduler Processor
        1. 初期化: `$grav['scheduler']`
        1. 発火: **onSchedulerInitialized** イベント
    1. 実行: Assets Processor
        1. 初期化: `$grav['assets']`
        1. 発火: **onAssetsInitialized** イベント
    1. 実行: Twig Processor
        1. 初期化: `$grav['twig']`
            ### Twig.php
            1. Twig テンプレートパスを config 設定をもとに設定
            1. もし利用可能であれば言語テンプレートを制御
            1. 発火: **onTwigTemplatePaths** イベント
            1. 発火: **onTwigLoader** イベント
            1. Twig config 及び loader chain を読み込み
            1. 発火: **onTwigInitialized** イベント
            1. Twig extensions を読み込み
            1. 発火: **onTwigExtensions** イベント
            1. 標準 Twig 変数 (config, uri, taxonomy, assets, browser, etc) を設定
    1. 実行: Pages Processor
        1. 初期化: `$grav['pages']`
            ### Pages.php
            1. 呼び出し: `buildPages()`
            1. (Flex Pages の場合、少しロジックが違いますが、考え方は同じです)
            1. cache が good かチェック:
            1. もし **cache が good** load pages date from
            1. もし **cache is not good**  `recurse()` を呼び出す
            1.  `recurse()` のとき発火: **onBuildPagesInitialized** イベント
            1. もし `.md` ファイルが見つかったとき:
                ### Page.php
                1. 呼び出し: `init()` ファイルの詳細を読み込む
                1. 以下を設定: `filePath`, `modified`, `id`
                1. 呼び出し: `header()` header の変数を初期化する
                1. 呼び出し: `slug()` URL スラッグを設定する
                1. 呼び出し: `visible()` 変数状態を設定する
                1. 設定: `modularTwig()` フォルダ名が `_` で始まるかどうかを基にしたステータス
            1. 発火: **onPageProcessed** イベント
            1. もし `folder` が見つかったらフォルダ内を `recurse()`
            1. 発火: **onFolderProcessed** イベント
            1. 呼び出し: `buildRoutes()`
            1. すべてのページに対して初期化: `taxonomy`
            1. 検索スピードアップのため `route` テーブルを作成
        1. 発火: **onPagesInitialized** イベント with [pages]
        1. 発火: **onPageInitialized** イベント with [page]
        1. もし page の routable が false:
            1. 発火: **onPageNotFound** イベント with [page]
        1. もし _task_:
            1. 発火: **onPageTask** イベント with [task, page]
            1. 発火: **onPageTask.[TASK]** イベント with [task, page]
        1. もし _action_:
            1. 発火: **onPageAction** イベント with [action, page]
            1. 発火: **onPageAction.[ACTION]** イベント with [action, page]
    1. 実行: Debugger Assets Processor
        1. Debugbar のときのみ: 追加: デバッグ用 CSS/JS を assets に
    1. 実行: レンダリング: Processor
        1. 初期化: `$grav['output']`
        1. もし `output` instanceof `ResponseInterface`:
            1. それ以上の処理を止め、 response を出力
        1. そうでない時:
            1. Twig の `processSite()` メソッドでそのページをレンダリング
                ### Twig.php
                1. 発火: **onTwigSiteVariables** イベント
                1. ページの出力を取得
                1. 発火: **onTwigPageVariables**, 及び、各モジュラーサブページへ呼び出し
                1. もしページが見つからない、もしくはルーティング不可の場合、まず **onPageFallBackUrl** イベントを発火。メディアアセットについてはフォールバックがあるか確認し、無ければ **onPageNotFound** を発火
                1. Twig オブジェクトにすべての Twig 変数を設定
                1. テンプレート名を file/header/extension 情報をもとに設定
                1. 呼び出し: `render()` メソッド
                1. 結果の HTML を返す
            1. 発火: **onOutputGenerated** イベント
            1. 出力バッファに出力を echo
            1. 発火: **onOutputRendered** イベント
            1. _Response_ オブジェクトを作成
            1. それ以上の処理を止め、 response を出力
    1. 発火: **onPageHeaders** イベント ページヘッダーを処理する
    1. 出力: HTTP ヘッダと HTTP ボディ
    1. レンダリング: debugger (有効化されているとき)
    1. シャットダウン
        1. session を閉じる
        1. クライアントとの接続を閉じる
        1. 発火: **onShutdown** イベント

呼び出される `content()` メソッドがあるページではいつでも、以下のライフサイクルが起こります：

### Page.php
1. もし content がキャッシュ **されていない** 場合:
    1. 発火: **onPageContentRaw** イベント
    1. マークダウンと Twig の設定をもとにページを処理。 発火: **onMarkdownInitialized** イベント
    1. 発火: **onPageContentProcessed** イベント
1. 発火: **onPageContent** イベント

