---
title: 'Grav 1.7 へのアップデート'
layout: ../../../../layouts/Default.astro
lastmod: '2025-07-09'
---

Grav 1.7 では、いくつかの新機能追加、改善、バグ修正がなされ、そして Grav 2.0 への道を開くたくさんのアーキテクチャの変更が提供されています。以下は、そのうちの重要部分です：

* **Flex Objects**: 独自のデータタイプを作成する新しい方法
* **Symfony Server**: web サーバーをインストールせずに Grav を実行
* **Improved Multi-Language**: 言語フォールバックの改良、管理パネルサポートの改善
* **Improved Multi-Site**: マルチサイトサポートに向けた管理パネル改善
* **Improved Admin ACL**: ユーザーとページに対して完全な CRUD をサポート
* **Improved Media Support**: `webp` 画像フォーマットのサポート、遅延読み込み、その他
* **Improved Caching**: 新しい `{% cache %}` タグ、及び特に管理パネルにおけるパフォーマンス改善
* **XSS Detection in Forms**: XSS が疑われる場合、フォームは送信されません。チェックを無効化する方法は [フォームオプションのドキュメント](../../../06.forms/02.forms/01.form-options/#xss-checks) をご覧ください。
* **Better Debugging Tools**: [Clockwork](https://underground.works/clockwork/) の統合、 Twig プロファイル、そしてパフォーマンスプロファイルのための [Tideways XHProf](https://github.com/tideways/php-xhprof-extension) PHP 拡張のサポート

> [!Warning]  
> **重要：** 多くの人にとって、 Grav 1.7 は何の問題もなく簡単にアップグレードできるものです。しかし、あらゆるアップグレードがそうであるように、サイトのアップグレードの前には、サイトの **バックアップを取り** 、 **テスト環境でアップグレードのテストをしてください** 。

<h3 id="most-common-issues">最もよくある問題</h3>

1.  ###### HTML が、意図した通り **レンダリングされた** HTML としてではなく、 **コード** として表示されてしまう
    この振る舞いは、 Grav 1.7 で **オートエスケープ** がデフォルトで有効となった結果です。これはセキュリティ強化策であり、 1.7 以前のバージョンからアップグレードした場合、 system config 設定の **Twig 互換** 設定が自動的に有効化します。古い Twig コードが機能し続けるためのものです。もし手動で 1.7 にアップデートしたり、 GPM のセルフアップグレード処理ではない方法でアップグレードした場合、この設定はあなた自身で行う必要があります。  
    このガイドの [Twig セクション](#twig) に完全な詳細があります。確認してください。

2.  ###### invalid(妥当でない) YAML エラーが出現する
    Symfony フレームワークのバージョンを上げたため、 YAML パーサーが 1.7 以前のものよりも厳密になりました。これを制御するため、 **YAML 互換** 設定を有効化したときに利用するための古いバージョンのパーサーが用意されています。Grav 1.7 に GPM でアップグレードした場合、これは自動的に制御されますが、手動でアップグレードした場合は、あなた自身で設定を行う必要があります。  
    このガイドの [YAML セクション](#yaml) に完全な詳細があります。確認してください。

3. ###### 管理パネルに翻訳されない文字列が表示される
    管理パネルのインターフェースに翻訳されていない文字列が表示される場合、最もあり得る理由は、以前 **言語翻訳** を無効化していたからです。これは、以前のバージョンの Grav にあったバグで、これを無効化しても、管理画面上では、翻訳が実質的には無効になっていませんでした。これは、 Grav 1.7 で **修正され** 、この設定は、意図した通りに機能します。翻訳文字列そのものではなく、翻訳コードが大文字で表示されます。  
    修正のため、 [トラブルシューティング](#troubleshooting-issues) セクションを確認してください。

4. ###### 管理パネルで保存時エラーもしくは保存ができない
    Grav 1.7 では、 **Flex Pages** が導入され、新しいデフォルトのページ管理 UI となりました。また、パフォーマンス最適化のため、毎回の管理パネル呼び出しごとにページを初期化することをやめました。通常の **Grav Pages** に戻すことにより、一時的にこの問題を解決することができます。 **FlexObjects** プラグインを編集し、 **Pages (Admin)** を無効化することにより、これが完了します。  
    問題を適切に指し示すため、カスタムプラグインは `PageInterface` を使って、  **Grav Pages** と **Flex Pages** の両方をサポートスべきです。そして、必要な場合は、 Pages を明示すべきです。  
    このガイドの [Pages セクション](#pages-1) と [Admin セクション](#admin) に完全な詳細があります。確認してください。  
    また、プラグイン特有の既知の問題もあります。プラグインに特有の問題については、このページの [トラブルシューティング](#troubleshooting-issues) を確認してください。

5. ###### ページブループリントの機能が止まるもしくはループに関するエラーが出る
    **Grav 1.7.8** で、テーマ内の **ブループリント** を定義するためのサポートが追加されました。これはつまり、もし `blueprints/pages/` フォルダ内のページのブループリントがあった場合に、標準的なブループリントの場所が、プラグインと同じように使われるということです。残念ながら、古いテーマは、場合によって `blueprints/` フォルダと、 `blueprints/pages/` フォルダでファイルが混同していることがあり、その場合は検出されず、ページを編集するときに管理パネルの入力フィールドが見えなくなったり、 `Loop detected while extending blueprint file` という致命的エラーが発生します。  
    もしいずれかのエラーが発生した場合は、 [トラブルシューティング](#troubleshooting-issues) セクションを確認して修正してください。

<h3 id="quick-update-guide">アップデートのクイックガイド</h3>

> [!Info]  
> **Grav 1.7** では、 **PHP 7.3.6** 以上が必要です。推奨バージョンは、最新リリースの **PHP 7.4** です。

### YAML

> [!Warning]  
> **重要：** Grav 1.7 YAML パーサーは、より厳格になり、config ファイルやページヘッダーに構文エラーがある場合、サイトが壊れるかもしれません。しかし、 `bin/gpm` や `管理パネルプラグイン` を使って既存のサイトをアップデートした場合、壊れた YAML 構文が機能し続けるようにアップグレード処理されます。

古い動作に戻すには、 `user/config/system.yaml` ファイルの以下の設定を確認する必要があります：

```yaml
strict_mode:
  yaml_compat: true
```

もしくは、管理パネルプラグインで、 **Configuration** -> **Advanced** -> **YAML Compatibility** を確認してください。

![Yaml Compatibility](yaml-compat.png)

> [!Tip]  
> **Grav 1.6 アップグレードガイド** には、専用の **[YAML パース](../02.grav-16-upgrade-guide/#yaml-parsing)** セクションがあり、これらの問題を修正するのに役立ちます。

デフォルトでは、 Grav 1.7 は **Symfony 4.4 YAML パーサー** を使います。これは、古い Grav のバージョンよりも [YAML 標準仕様](https://yaml.org/spec) に準拠しています。これはつまり、以前は正しく機能していた YAML ファイルが、妥当でない YAML によりエラーを引き起こす可能性があるということです。しかし、 Grav はデフォルトで、古い 3.4 バージョンのパーサーへフォールバックし、サイトを運用し続けます。

> [!Tip]  
> アップグレードの前後に、 **CLI コマンドで** `bin/grav yamllinter` を実行するか、もしくは管理パネルで **Admin** > **Tools** > **Reports** を表示してください。warning もしくは error になっている YAML がすべて修正されます。

### Twig

> [!Warning]  
> **重要：** Grav 1.7 は、 **Twig オートエスケープ** をデフォルトで有効化します。しかし、既存サイトを `bin/gpm` もしくは `管理パネルプラグイン` でアップデートした場合は、アップグレードの処理中に既存のオートエスケープ設定を残します。

古いやり方に戻すためには、 `user/config/system.yaml` ファイル中で、以下の設定を確認してください：

```yaml
twig:
  autoescape: false
strict_mode:
  twig_compat: true
```

もしくは、管理パネルプラグインで、 **Configuration** -> **Advanced** -> **Twig Compatibility** を表示してください。

そして、これを実行後は、忘れずにキャッシュをクリアしてください！

![Twig Compatibility](twig-compat.png)

> [!Tip]  
> **Grav 1.6 アップグレードガイド** には、専用の **[Twig](../02.grav-16-upgrade-guide/#twig)** セクションがあります。先にここを読んでおくことがとても重要です！

Twig テンプレートエンジンは、 1.43 にアップデートされましたが、 Twig 2.13 もサポートされます。この新しいバージョンの Twig をサポートするため、既存サイトの Twig テンプレートにある古い構文をアップデートする必要があります。 **Grav 1.6 アップグレードガイド** は、これを行う際に役立ちます。

テンプレートに関する他の変更は：

* `{% cache %}` Twig タグが新しく追加され、 `twigcache` 拡張が不要になりました。
* `array_diff()` twig 関数が追加されました
* `template_from_string()` twig 関数が追加されました。
* 新しく `svg_image()` twig 関数が追加され、 Twig 内で SVG の 'include' が簡単になりました。
* `url()` twig 関数が改善され、3つ目の引数 (`true`) により、false を返す代わりに存在しないファイルの URL を返します。
* `|array` twig フィルタが改善され、イテレータ及び `toArray()` メソッドを持つオブジェクトに対して機能するようになりました。
* `authorize()` twig 関数が改善され、ネストされたルールパラメータでうまく機能するようになりました。
* `|yaml_serialize` twig フィルタが改善されました。 `JsonSerializable` オブジェクトと、その他の配列に似たオブジェクトをサポートします。
* `external.html.twig`, `default.html.twig`, そして `modular.html.twig` について、デフォルトのテンプレートが追加されました。
* **後方互換性の破壊** : `{% script 'file.js' in 'bottom' %}` では動かなくなるため、 `in` ではなく、 `{% script 'file.js' at 'bottom' %}` のように使ってください。

## Forms

> [!Warning]  
> **重要：** Grav 1.7 では、 **Strict Validation** （厳密なバリデーション）のふるまいに変更があります。しかし、既存サイトを `bin/gpm` や `管理パネルプラグイン` でアップデートした場合、アップグレードプロセスにより、既存の strict モードの振る舞いが使い続けられます。

**Strict モードの改善** : form 内で、 `validation: strict` を定義しても、バグにより望んだような厳密さになっていませんでした。strict モードでは、フォームから余分なフィールドを送信しないようにするべきで、 Grav 1.7 から修正されました。残念ながら、古いフォームの多くは、余分なデータが入っていても、厳密だと宣言していました。

古いやり方に戻すためには、 `user/config/system.yaml` ファイル内の以下の設定を確認してください：

```yaml
strict_mode:
  blueprint_compat: true
```

**XSS インジェクション検出** が、すべてのフロントエンドフォームでデフォルトで有効化されました。フォームごと・フィールドごとに無効化やカスタマイズする方法については、 [ドキュメント](../../../06.forms/02.forms/01.form-options/#xss-checks) を参照してください。

このため、古い `validation: strict` のやり方をメンテナンスするために、新しく `system.strict_mode.blueprint_compat: true` という config オプションを追加しました。この設定は、 `validation: strict` 機能を利用する際には、 無効化しておくことを推奨します。もしこの機能を利用する場合、行を削除するか、フォームが機能するかテストしてください。

> [!Note]  
> この後方互換のメカニズムは、 Grav 2.0 で削除予定です。

<h3 id="environments-and-multi-site">環境変数とマルチサイト</h3>

> [!Warning]  
> **重要：** Grav 1.7 では、 [環境変数](../../04.environment-config/) を `user/env/` フォルダに移しました。古いフォルダでも動きますが、将来的に機能が新しいフォルダを利用する可能性があるので、環境変数をひとつのフォルダに移動しておくことをおすすめします。

Grav 1.7 では、 [サーバー設定をもとにした環境設定](../../04.environment-config/#server-based-environment-configuration) や、 [サーバー設定をもとにしたマルチサイト設定](../../05.multisite-setup/#server-based-multi-site-configuration) もサポート対象に追加しました。この機能は、たとえば Docker コンテナを利用する場合や、それらを独立させておきたい場合に便利です。もしくは、config 設定内に機密情報を保存しておきたくない場合に、サーバーの設定に保存したい場合に便利です。

`setup.php` ファイルに加えて、 `GRAV_ROOT/setup.php` や、 `GRAV_ROOT/GRAV_USER_PATH/setup.php` も利用可能です。2つ目の例は、 Git リポジトリで user フォルダのみを含めたい環境での利用のときに便利です。

<h3 id="user-accounts">ユーザーアカウント</h3>

管理パネルプラグインは、新たに **Flex Users** を使用して [アカウントを管理](../../../05.admin-panel/03.accounts/) するようになりました：

* [ユーザーアカウントマネージャー](../../../05.admin-panel/03.accounts/01.users/)
* [ユーザーグループマネージャー](../../../05.admin-panel/03.accounts/02.groups/)

> [!Note]  
> Flex Users 機能は、サイトのフロントエンドでは、まだ使われていません。

<h3 id="pages">ページ</h3>

既存の [ページ管理](../../../05.admin-panel/03.page/) が、 **Flex Pages** により大幅に改善しました：

* ページ一覧リストを再構築：ページ数のよりずっと多いサイトでも対応できるようになりました
* アクセス制御の改善： [CRUD ACL](../../../05.admin-panel/03.page/06.permissions/) がページ所有者をサポートします
* 多言語サポートの改善

> [!Info]  
> **後方互換性の破壊** ： ルーティング可能かつ公開されている子ページを持つ、ルーティングできないページを訪れた時の 404 エラーページの取り扱いを修正しました。新しい取り扱いは、最初のルーティング可能で公開されている子ページへリダイレクトされます。これはおそらく、もっとも望ましいページでしょう。

> [!Note]  
> Flex Pages 機能は、サイトのフロントエンドでは、まだ使われていません。

<h3 id="multi-language">多言語</h3>

Grav 1.7 では、多言語サイトでのページのフォールバック機能のふるまい方が変更されました。

以前は、リクエストされた言語のページが存在しない場合、次にサポートされている言語を探す実装となっていました。これはつまり、翻訳されていないページが常に表示されることになり、読み手にとって知らない言語を使ったページである可能性がありました。

新しいふるまいでは、サイトのデフォルト言語にのみフォールバックします。このデフォルトのふるまいは、 `system.languages.content_fallback` 設定オプションを使うことで、言語ごとにフォールバック言語の設定を上書きできます。

フォールバック言語のいずれも、ページが存在しない場合、 **404 Not Found** エラーが代わりに表示されます。

> [!Info]  
> **後方互換性の破壊** ： `system.yaml` ファイルもしくは管理パネルプラグイン（ **Configuration** > **System** > **Languages** > Content Language Fallback** ）で、ページコンテンツの現在のフォールバック言語を追加してください。

<h3 id="media">メディア</h3>

メディア制御は、 Grav 1.7 で大幅に改善されました。特筆すべき点としては：

* `webp` 画像フォーマットをサポート
* マークダウン： 画像にネイティブの `loading=lazy` 属性をサポート。 `system.images.defaults` もしくは、画像のマークダウン記述ごとに `?loading=lazy` を付けることで設定できます。
* `noprocess` 機能を追加し、リンクや画像の抜粋において、特定のアイテムについて処理をしないことができます。例： `http://foo.com/page?id=foo&target=_blank&noprocess=id`

### CLI

いくつかの特筆すべき点は：

* すべての CLI コマンドで `--env` と `--lang` パラメータを受け付けます。環境変数を設定したり、利用言語を設定できるようになりました。（`-e` は、現在機能しません）
* `bin/grav server` CLI コマンドが追加され、簡単に Symfony もしくは PHP ビルトインサーバーが実行できるようになりました
* `Scheduler` cron コマンドチェックが改善され、より便利な CLI 情報も改善されました
* `-r <job-id>` オプションが新しく追加され、 Scheduler CLI コマンドで job の強制実行ができるようになりました
* `bin/grav yamllinter` CLI コマンドが改善され、サイト全体や特定のフォルダのみについか YAML リントの問題を探すオプションが追加されました
* CLI/GPM コマンドが失敗したとき、非ゼロコードを返します（コマンドが失敗するときエラーが検出されます）

<h3 id="configuration">config 設定</h3>

新しく config オプションが追加され、 `false` を設定するとデフォルト言語が `.md` ファイルのままにできるようになりました
* system.yaml ファイル： `languages.include_default_lang_file_extension`: **true**|false
* 管理パネルプラグイン： **Configuration** > **System** > **Languages** > **Include default language in file extension**

新しく config オプションが追加され、すべての言語に対して、個別にコンテンツが無かった場合のフォールバック言語を設定できるようになりました
* system.yaml ファイル： `languages.content_fallback` ： [言語設定](../../../02.content/11.multi-language/#language-configuration) を参照してください
* 管理パネルプラグイン： **Configuration** > **System** > **Languages** > **Content Language Fallback**

新しく config オプションが追加され、デバッガと clockwork が選べるようになりました
* system.yaml ファイル： `debugger.provider`: **clockwork**|debugbar
* 管理パネルプラグイン： **Configuration** > **System** > **Debugger** > **Debugger Provider**

新しく config オプションが追加され、潜在的なセンシティブ情報をかくせるようになりました
* system.yaml ファイル： `debugger.censored`: **false**|true
* 管理パネルプラグイン： **Configuration** > **System** > **Debugger** > **Censor Sensitive Data**

新しく config オプションが追加され、 `validation: strict` のふるまいをメンテナンス（過去のままに）できるようになりました
* system.yaml ファイル： `strict_mode.blueprint_compat`: **true**|false
* 管理パネルプラグイン： **Configuration** > **System** > **Advanced** > **Blueprint Compatibility**

新しく config オプションが追加され、 `HTTP_X_FORWARDED` ヘッダーをサポートしました（デフォルトで無効です）
* system.yaml ファイル： `http_x_forwarded.protocol`: **true**|false
* 管理パネルプラグイン： **Configuration** > **System** > **Advanced** > **HTTP_X_FORWARDED_PROTO Enabled**
* system.yaml ファイル： `http_x_forwarded.host`: true|**false**
* 管理パネルプラグイン： **Configuration** > **System** > **Advanced** > **HTTP_X_FORWARDED_HOST Enabled**
* system.yaml ファイル： `http_x_forwarded.port`: **true**|false
* 管理パネルプラグイン： **Configuration** > **System** > **Advanced** > **HTTP_X_FORWARDED_PORT Enabled**
* system.yaml ファイル： `http_x_forwarded.ip`: true|**false**
* 管理パネルプラグイン： **Configuration** > **System** > **Advanced** > **HTTP_X_FORWARDED IP Enabled**

新しく config オプションが追加され、 `security.sanitize_svg` により SVG ファイルから潜在的に危険なコードを削除できるようになりました
* security.yaml ファイル： `sanitize_svg`: **true**|false
* 管理パネルプラグイン： **Configuration** > **Security** > **Sanitize SVG**

<h2 id="developers">開発者向け</h2>

<h3 id="debugging">デバッグ</h3>

* [Clockwork](https://underground.works/clockwork) ディベロッパーツールをサポートしました（現在のデフォルトのデバッガです）
* [Tideways XHProf](https://github.com/tideways/php-xhprof-extension) PHP 拡張をサポートし、メソッド呼び出しのプロファイリングができるようになりました
* Clockwork デバッガのため Twig プロファイリングを追加しました

<h3 id="use-composer-autoloader">composer オートローダーを使う</h3>

* `bin/composer.phar` を `2.0.2` にアップグレードし、新しく、処理が速くなりました
* `composer.json` ファイルをプラグインに追加してください。そして `composer update --no-dev` を実行してください（そして、アップデートを忘れずに続けてください）：
    composer.json
    ```json
    {
        "name": "getgrav/grav-plugin-example",
        "type": "grav-plugin",
        "description": "Example plugin for Grav CMS",
        "keywords": ["example", "plugin"],
        "homepage": "https://github.com/getgrav/grav-plugin-example",
        "license": "MIT",
        "authors": [
            {
                "name": "...",
                "email": "...",
                "homepage": "...",
                "role": "Developer"
            }
        ],
        "support": {
            "issues": "https://github.com/getgrav/grav-plugin-example/issues",
            "docs": "https://github.com/getgrav/grav-plugin-example/blob/master/README.md"
        },
        "require": {
            "php": ">=7.1.3"
        },
        "autoload": {
            "psr-4": {
                "Grav\\Plugin\\Example\\": "classes/",
                "Grav\\Plugin\\Console\\": "cli/"
            },
            "classmap":  [
                "example.php"
            ]
        },
        "config": {
            "platform": {
                "php": "7.1.3"
            }
        }
    }
    ```
    [Composer スキーマ](https://getcomposer.org/doc/04-schema.md) を参照してください
* コード内で、 `require` ではなく、オートローダーを使ってください：
    example.php
    ```php
      /**
       * @return array
       */
      public static function getSubscribedEvents(): array
      {
          return [
              'onPluginsInitialized' => [
                  // This is only required in Grav 1.6. Grav 1.7 automatically calls $plugin->autolaod() method.
                  ['autoload', 100000],
              ]
          ];
      }
    
      /**
       * Composer autoload.
       *
       * @return \Composer\Autoload\ClassLoader
       */
      public function autoload(): \Composer\Autoload\ClassLoader
      {
          return require __DIR__ . '/vendor/autoload.php';
      }
    ```
* プラグインとテーマ： オブジェクトが初期化される際に、 `$plugin->autoload()` と `$theme->autoload()` が自動で呼び出されます
* カスタムコードで、 class を読み込む際に `require` や `include` を使わないように気をつけてください

<h3 id="plugin-theme-blueprints-blueprints-yaml">プラグイン・テーマのブループリント(`blueprints.yaml`)</h3>

* 以下を追記して下さい：
    ```yaml
    slug: folder-name
    type: plugin|theme
    ```
* 依存関係のアップデートを行ってください。 Grav を 1.6 か 1.7 に設定し、code/vendor を PHP 7.1 にアップデートすることを推奨します
    ```yaml
    dependencies:
        - { name: grav, version: '>=1.6.0' }
    ```
* `themes` をキャッシュされたブループリント設定と、 config 設定に追加してください
* **Grav 1.7.8** では、テーマのあらゆる **ブループリント** がサポートされました。 `blueprints/` フォルダ内のすべてのファイルとフォルダを、 `blueprints/pages/` へ移動し、テーマの互換性を維持してください。また、依存する Grav のバージョンを `1.7.8` 以上に忘れずにアップデートしてください。

<h3 id="sessions">セッション</h3>

* セッション ID は、セッション固定化攻撃への対策としてログイン時に変更されます
* `Session::regenerateId()` メソッドが追加され、適切にセッション固定化攻撃から守ります

### ACL

* `user.authorize()` メソッドは、ルールに `login` の名前が含まれていない限り、ユーザーに認証（2FA 検証の通過）を要求します。
* より高度な ACL (CRUD) をサポートしました

* **後方互換性の破壊** `user.authorize()` メソッドと Flex の `object.isAuthorized()` メソッドは、2つの否定状態を持ちます： `false` と `null` です。

    false に対して厳密なチェックをしないように気をつけてください： `$user->authorize($action) === false` もしくは、 `user.authorize(action) is same as(false)` (Twig) 。

    否定チェックについては、 `!user->authorize($action)` (PHP) もしくは `not user.authorize(action)` (Twig) を使用してください。

    この変更は、前の評価がマッチしなかった場合に、連続して評価するための強い否定ルールを実現するためになされました： `user.authorize(action1) ?? user.authorize(action2) ?? user.authorize(action3)`

    Twig 関数の `authorize()` は、まだ古いふるまいを **残している** ので注意してください！

> [!訳注]  
> null 合体演算子 `??` では、 `true === null ?? true` ですが、 `false === false ?? true` になるために、 false の否定と、 null の否定を分けた、ということのようです。

<h3 id="pages-1">ページ</h3>

* デフォルトテンプレートとして  `external.html.twig` と `default.html.twig` と `modular.html.twig` が追加されました
* 管理パネルプラグインは、デフォルトで `Flex Pages` を使います（ `Flex-Objects` プラグインで無効化できます）
    ![Disable Flex Pages](disable-flex-pages.png)
* `Flex Pages` についてページ固有の管理パネルパーミッションがサポートされました
* `Flex Pages` についてルートページがサポートされました
* 本当は `Pages::find()` を呼び出したい場合に、間違って `Pages::dispatch()` が（リダイレクトを伴って）呼び出される問題が修正されました
* `Pages::getCollection()` メソッドが追加されました
* `collection()` と `evaluate()` ロジックが、 `Page` クラスから `Pages` クラスへ移動しました
* **非推奨** `$page->modular()` を廃止し `$page->isModule()` を使用してください
* **非推奨** `PageCollectionInterface::nonModular()` を廃止し `PageCollectionInterface::pages()` を使用してください
* **非推奨** `PageCollectionInterface::modular()` を廃止し `PageCollectionInterface::modules()` を使用してください
* **後方互換性の破壊** `Page::modular()` と `Page::modularTwig()` がフォルダや初期化されていないページに対して `null` を返す問題を修正しました。 `false` や `null` をチェックするのでない限り、コードに影響は無いはずです
* **後方互換性の破壊** 関数シグネチャにおいて、常に `\Grav\Common\Page\Interfaces\PageInterface` を使ってください。 `\Grav\Common\Page\Page` は使わないでください
* 管理パネルプラグインは、デフォルトで `Flex Pages` を使います。 collection は、少し違ったふるまいになります
* **後方互換性の破壊** `$page->topParent()` メソッドは、null ではなく、ページ自身を返すかもしれません
* **後方互換性の破壊** `$page->header()` は、 `stdClass` ではなく、 `\Grav\Common\Page\Header` オブジェクトを返すかもしれません。 Flex ページと標準ページ両方を制御する必要があります

<h3 id="media-1">メディア</h3>

* `MediaTrait::freeMedia()` メソッドが追加され、メディア（とメモリ）を解放できるようになりました
* PSR-7 を使って `Media` 内に直接画像をアップロードや削除できるようになりました
* アセットタイプを調整し、クラスでアセットを拡張できるようになりました
* **後方互換性の破壊** Media は、もはや `Getters` を拡張していません。 `$media->$filename` にアクセスしても動かないので、 `$media[$filename]` を代わりに使ってください！

### Markdown

* **後方互換性の破壊** Parsedown-Extra 0.8 へ Parsedown をアップグレードしました。 Parsedown を拡張したプラグインは、HTML としてレンダリングするために修正が必要かもしれません
* 新しく `Excerpts::processLinkHtml()` メソッドが追加されました

### Users

* フロントエンドでの `Flex Users` のサポートを実験的に追加（まだ使用は推奨されません）
* 管理パネルプラグインで、デフォルトで `Flex Users` を使用（ `Flex-Objects` プラグインで無効化できます）
* `Flex Users` の改善： ブループリントに従い、管理パネルでのみ Flex を利用可能にします
* `Flex Users` の改善： ユーザー・グループ ACL で拒否のパーミッションをサポートしました
* `UserInterface::authorize()` メソッドを変更し、マッチするルールが無いためにアクセスが拒否される場合に、 `false` と同じ意味で `null` を返します
* **非推奨** `\Grav\Common\User\Group` を廃止し、 `$grav['user_groups']` を使用してください。 Flex UserGroup コレクションは、ここに含まれます
* **後方互換性の破壊** 関数シグネチャにおいて常に `\Grav\Common\User\Interfaces\UserInterface` を使ってください。 `\Grav\Common\User\User` は使用しないでください

### Flex

* `Framework` Flex クラスは直接使用しないでください。 `Grav\Common\Flex\Types\Generic` 名前空間内のクラスを使用するか拡張することを推奨します
* 登録されたすべての Flex Directories にアクセスする `$grav['flex']` を追加しました
    * `$grav['flex']` が最初にアクセスされたときに発火する `FlexRegisterEvent` を追加しました
* `hasFlexFeature()` メソッドを追加し、 `FlexObject` や `FlexCollection` が渡された機能を実装しているかテストできるようになりました
* `getFlexFeatures()` メソッドを追加し、 `FlexObject` や `FlexCollection` が実装しているすべての機能を返すようになりました
* `FlexObject::refresh()` メソッドを追加し、オブジェクトを確実に最新にできるようになりました
* `FlexStorage::getMetaData()` メソッドを追加し、オブジェクトの最新のメタ情報をリストキーで取得できるようになりました
* `FlexDirectoryInterface` インターフェースが追加されました
* Flex Objects に `same_as` 検索オプションが追加されました
* `Flex Pages` のメソッド `$page->header()` は `\Grav\Common\Page\Header` オブジェクトを返します。古い `Page` クラスは、まだ `stdClass` を返します
* `PageCollectionInterface::nonModular()` から `PageCollectionInterface::pages()` に名前が変更され、古いメソッドは非推奨となりました
* `PageCollectionInterface::modular()` から `PageCollectionInterface::modules()` に名前が変更され、古いメソッドは非推奨となりました
* `FlexDirectory::getObject()` を新しいオブジェクトを作成するためにパラメータを何も渡さずに呼び出せるようになりました
* flex directory タイプごとにカスタマイズ可能な config 設定を実装しました
* **非推奨** `FlexDirectory::update()` 及び `FlexDirectory::remove()`
* **後方互換性の破壊** `Grav\Common\Flex` の下にすべての Flex タイプクラスを移動しました
* **後方互換性の破壊** `FlexStorageInterface::getStoragePath()` 及び `getMediaPath()` は null を返せるようになりました
* **後方互換性の破壊** Flex オブジェクトは、キーを持たない場合に一時的なキーを返しません。代わりに空のキーが返されます
* **後方互換性の破壊** `FlexStorageInterface::getMetaData()` に reload 引数が追加されました
* リスト表示で見た目をカスタマイズするためにフォームフィールドに `edit_list.html.twig` ファイルを追加できるようになりました

<h3 id="multi-language-1">多言語</h3>

* `Route` クラスの言語サポートが改善されました
* 翻訳： すべての場所で MODULAR を MODULE に名前を変更しました
* `Language::getPageExtensions()` メソッドを追加し、サポートされているページの言語拡張子の全リストを取得します
* **後方互換性の破壊** `Language::getFallbackPageExtensions()` を修正し、すべての言語ではなくデフォルトの言語にのみフォールバックするようになりました

<h3 id="multi-site">マルチサイト</h3>

* `user/env` フォルダ下にすべてのサイト・環境変数を置くようになりました

<h3 id="serialization">シリアル化</h3>

* すべてのクラスで PHP 7.4 のシリアル化を使用します。古い `Serializable` メソッドは final になり、オーバーライドできなくなります。

<h3 id="blueprints">ブループリント</h3>

* フォームフィールドのバリデーションに `flatten_array` フィルタが追加されました
* `security@: or: [admin.super, admin.pages]` のサポートがブループリントに追加されました（ネストされた AND/OR モードをサポートします）
* ブループリントのバリデーション： `validate: value_type: bool|int|float|string|trim` が `array` に追加され、配列内のすべての値にフィルターできるようになりました
* プラグインに blueprints フォルダがある場合に、イベントで初期化するととても遅くなります。代わりに、次のようにしてください：
    ```php
    class MyPlugin extends Plugin
    {
        /** @var array */
        public $features = [
            'blueprints' => 0, // Use priority 0
        ];
    }
    ```

<h3 id="events">イベント</h3>

* `rockettheme/toolbox` ラッパーではなく、 `Symfony EventDispatcher` を直接使います。
* PSR-14 イベントへ `$grav->dispatchEvent()` メソッドを追加しました
* `PluginsLoadedEvent` を追加しました。このイベントはプラグインが読み込まれたあと、初期化される前に発火します
* `SessionStartEvent` を追加かしました。このイベントはセッションがスタートするときに発火します
* `FlexRegisterEvent` を追加しました。このイベントは、 `$grav['flex']` に最初にアクセスされたときに発火します
* `PermissionsRegisterEvent` を追加しました。このイベントは、 `$grav['permissions']` に最初にアクセスされたときに発火します
* `onAfterCacheClear` イベントが追加されました
* `onAdminTwigTemplatePaths` イベントの確認をしてください。次のようにはしないでください：
    ```php
    public function onAdminTwigTemplatePaths($event)
    {
        // This code breaks all the other plugins in admin, including Flex Objects
        $event['paths'] = [__DIR__ . '/admin/themes/grav/templates'];
    }
    ```
    次のようにしてください：
    ```php
    public function onAdminTwigTemplatePaths($event)
    {
        // Add plugin template path for admin.
        $paths = $event['paths'];
        $paths[] = __DIR__ . '/admin/themes/grav/templates';
        $event['paths'] = $paths;
    }
    ```

### JavaScript

* バンドルしている JQuery を最新のバージョン `3.5.1` にアップデートしています

### Misc

* `Utils::functionExists()` を追加しました： PHP 8 の `function_exists()` 互換
* ヘルパーメソッドとして `Utils::isAssoc()` と `Utils::isNegative()` を追加しました
* とてもシンプルな変数テンプレート用に `Utils::simpleTemplate()` メソッドを追加しました
* `Utils::fullPath()` を追加し、ストリーム内や相対パスなどのファイルへのフルパスを取得できるようになりました
* `CSVFormatter::decode()` で、null 文字置換のカスタマイズをサポートしました
* `Security::sanitizeSVG()` 関数を新規追加しました
* `$grav->close()` メソッドを追加し、レスポンスを伴う適切なリクエストの終了をできるようになりました
* `Folder::countChildren()` メソッドを追加し、フォルダに子フォルダがある場合に検出できるようになりました
* `File` 保存時にシムリンクをサポートしました
* `Route::getBase()` メソッドを追加しました
* **後方互換性の破壊** `Route` オブジェクトをイミュータブルにしました。つまり、アップデートバージョンで（すべての `withX` メソッドに対して）次のようにする必要があります： `{% set route = route.withExtension('.html') %}` 
* コンテンツ圧縮が無効の場合に Apache サーバーでの `Content-Encoding` 制御がより良くなりました
* `Uri::getAllHeaders()` 互換関数を追加しました
* `JsonFormatter` オプションを文字列として渡せるようになりました

### CLI

* **後方互換性の破壊** 多くのプラグインで Grav の初期化を間違えています。プラグインとテーマの初期化処理を自身で行うのは安全ではありません
    * Grav 1.6.21 以上では、以下の呼び出しが必要です。 Grav のバージョンへの依存関係を設定しておくことをおすすめします
    * 内部の `serve()` メソッド：
    * 言語設定をしたい場合は、他の何よりも先に `$this->setLanguage($langCode);` を呼び出してください。（もしくは、デフォルトを利用してください）
    * 以下のうちのひとつを呼び出してください：
        * `$this->initializeGrav();` `bin/plugin` にいる場合は、すでに呼び出しています。そうでなければ、これを呼び出す必要があります
        * `$this->initializePlugins();` これは Grav とプラグインを（`onPluginsInitialized` に）初期化します
        * `$this->initializeThemes();` これは Grav, プラグイン そして テーマを初期化します
        * `$this->initializePages();` これは Grav, プラグイン, テーマ そしてページに必要なすべてを初期化します
* プラグイン名を CLI コマンドのクラスのプレフィックスにしておくのは良いアイディアです。そうでなければ、名前衝突を起こすかもしれません（私たちもやったことがあります！）

<h3 id="used-libraries">ライブラリの利用</h3>

* Symfony Components 4.0 にアップデートしました。あなたのカスタムコード内の非推奨機能をアップデートしておいてください
* **後方互換性の破壊** `bin/grav yamllinter` を実行してください。（プラグインやテーマを含めた）サイト内の YAML パースエラーが見つかります。

<h2 id="plugins">プラグイン</h2>

<h3 id="admin">管理パネルプラグイン</h3>

* ユーザーアカウントブループリントに `Content Editor` オプションを追加しました

* **後方互換性の破壊** 管理パネルプラグインはフロントエンドのページをこれ以上初期化しません。これにより管理プラグインを著しくスピードアップさせます。  
    フロントエンドページにアクセスする必要がある場合、 `$grav['admin']->enablePages()` もしくは `{% do admin.enablePages() %}` を呼び出してください。この呼び出しは複数回行われても安全です  
    `Flex Pages` を利用している場合は、 `$grav['admin']` の代わりに Flex ディレクトリを使ってください。コードをとても速くできます。

* 管理パネルプラグインは、 `Accounts` や `Pages` を編集する際に Flex を利用するようになりました。これらいずれかにプラグインからフックする場合、それらが機能し続けるように確認してください。

* 管理パネルのキャッシュは、デフォルトで有効化されています。必要な場合は、プラグインが確実にキャッシュクリアするようにしてください。ただし、すべてのキャッシュをクリアするのはやめてください！

### Shortcode Core

* **非推奨** すべてのショートコードには `init()` メソッドが必要です。これが無いクラスは、将来的に機能が停止します。

<h2 id=tTroubleshooting-issues">問題のトラブルシューティング</h2>

#### `ERROR: flex-objects.html.twig template not found for page`

Grav 1.7 にアップグレード後にこのエラーが表示された場合、プラグインが `content-edit` を呼び出していることに関係しているかもしれません。このプラグインを無効化すると、エラーが解決するでしょう。 [Grav Issue #3169](https://github.com/getgrav/grav/issues/3169)

<h4 id="untranslated-admin">管理パネルが翻訳されない</h4>

もし管理パネルが次のような見た目になっていた場合：

![Untranslated Admin](untranslated.png)

修正はとても簡単ですし、完全に翻訳されていなくても修正できます。 `PLUGIN_ADMIN.CONFIGURATION` に移動し、 `PLUGIN_ADMIN.LANGUAGES` 内で `PLUGIN_ADMIN.LANGUAGE_TRANLATIONS` を `PLUGIN_ADMIN.YES` に設定してください：

![Fix translations](fix-translations.png)

<h4 id="page-blueprints-stop-working-in-admin">ページブループリントが管理パネル内で機能しない</h4>

ページを編集中にカスタムフィールドが表示されない場合、テーマがページブループリントとして2つの衝突した場所を利用しています。

テーマをあなた自身で作成していない場合は、テーマの作者にバグ報告をしてください。

バグ修正には、テーマ内のすべてのファイルとフォルダを `blueprints/` から `blueprints/pages/` へ移動させる必要があります（ **Grav 1.7.8 以上** で必要です）。もしくは、テーマが古いバージョンの Grav をサポートしなければならない場合は、逆のことをしてください。

#### Error: Loop detected while extending blueprint file

ループエラーを修正する最も簡単な方法は、ファイルを適切な場所に移動させることです。上記の問題を見てください。

もしくは、壊れたページのブループリントを変更することで修正できます：

```yaml
extends@:
    type: [NAME]
    context: 'blueprints://pages'
```

`[NAME] の場所は、ブループリントのファイル名（ファイル拡張子は除く）です。
上記を、以下のように変更してください：`

```yaml
extends@: self@
```

<h4 id="missing-css-styling-in-admin">管理パネル内で CSS スタイルが消える</h4>

これは、最新の Grav 1.7 及び 管理パネルプラグイン 1.10 にアップグレードした後に報告された問題です。管理パネルのページが、壊れて表示され、完全なスタイルが当たらなくなります。これは、 `imagecreate` プラグインに関係しています。このプラグインを無効化するだけでは不十分で、プラグインを **完全に削除** することで解決します。 [Admin Issue #2035](https://github.com/getgrav/grav-plugin-admin/issues/2035)

<h2 id="reverting-back-to-latest-grav-1-6">Grav 1.6 の最新バージョンへ戻す</h2>

Grav 1.7 以上のアップグレードが簡単に行えるように、問題を解決することをおすすめしますが、カスタムプラグインを機能させ続ける必要があったり、開発者のリソースが不十分だったり、今すぐに Grav 1.6 に戻さなければならない理由がある場合もありえます。

サイトへの CLI アクセスがあるなら、 **Grav 1.7 のルートディレクトリ** から以下のコマンドを実行することで、戻すことができます：

```bash
wget -q https://getgrav.org/download/core/grav-update/1.6.31 -O tmp/grav-update-v1.6.31.zip
wget -q https://getgrav.org/download/plugins/admin/1.9.19 -O tmp/grav-plugin-admin-v1.9.19.zip
unzip tmp/grav-update-v1.6.31.zip -d tmp
unzip tmp/grav-plugin-admin-v1.9.19.zip -d tmp
cp -rf tmp/getgrav-grav-plugin-admin-5d86394/* user/plugins/admin/
cp -rf tmp/grav-update/* ./
```

基本的に、 Grav 1.6 と管理パネルプラグイン 1.9 の **ダイレクトインストール** を現在のインストール環境のトップで行います。 `user/` フォルダには触らないので、コンテンツとプラグインには影響がありません。

CLI アクセスができない方は、 [grav-update-v1.6.31.zip](https://github.com/getgrav/grav/releases/download/1.6.31/grav-update-v1.6.31.zip) と [grav-plugin-admin-1.9.19.zip](https://github.com/getgrav/grav-plugin-admin/archive/1.9.19.zip) ファイルをここのリンクからダウンロードしてください。これらの zip ファイルをファイルシステムに展開してください。そして、お好みの FTP/SFTP クライアントで Grav ファイルを `WEBROOT` へコピーし、 管理パネルプラグインのファイルを `WEBROOT/user/plugins/admin` へコピーしてください。

