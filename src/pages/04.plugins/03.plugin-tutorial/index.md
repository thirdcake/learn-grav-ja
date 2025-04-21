---
title: "プラグインのチュートリアル"
layout: ../../../layouts/Default.astro
---

プラグインは通常、Gravのコア機能では実現できないタスクを使うために開発されます。

このチュートリアルでは、ランダムにページを表示するプラグインを作ります。おそらく、ブログサイトなどで似たような機能をみたことがあるかもしれません。ボタンをクリックすると、ランダムにブログ投稿を表示してくれるという機能です。

> [!Note]  
> この機能を使える `Random` というプラグインはすでにあるので、今回作るテストプラグインは `Randomizer` という名前にしましょう。

この機能は、**すぐに使える** 機能ではありませんが、プラグインを使えば **かんたんに** 提供できます。Gravの多くの場面でそうであったように、これを実行する方法が _ひとつしかない_ ということはありません。代わりに、たくさんのオプションがあります。わたしたちがカバーできるのは、そのうちのひとつです...

<h2 id="randomizer-plugin-overview">Randomizerプラグインの概要</h2>

プラグイン作成に向けて、次のようなアプローチを取ります：

1. 'トリガーURL' （例： `/random` ）にURIが一致したときに、プラグインが実行されるようにする

2. ランダムページの対象として、特定のタクソノミーだけがフィルタリングされるようにする（例： `category: blog` ）

3. フィルタリングされたページから、ランダムに1ページを選び、Gravにそのページをページコンテンツとして採用するように伝える。

OK！ 十分にシンプルですね？ さっそく始めましょう！


<h2 id="step-1-install-devtools-plugin">ステップ1 - DevToolsプラグインのインストール</h2>

> [!Info]  
> 以前のバージョンのチュートリアルでは、手作業でプラグインを作成していました。新しい **DevToolsプラグイン** によって、これらのプロセスがスキップできます

新しいプラグインを作る最初のステップは、 **DevToolsプラグインのインストール** です。これには2つの方法があります。

<h4 id="install-via-cli-gpm">CLI GPMによるインストール</h4>

* Gravをインストールしたルートディレクトリに、コマンドラインを移動させてください

```bash
bin/gpm install devtools
```

<h4 id="install-via-admin-plugin">管理パネルからのインストール</h4>

* ログイン後、サイドバーから **Plugins** セクションに移動してください。
* 画面右上の **Add** ボタンをクリックしてください。
* 一覧から **DevTools** を見つけ、**Install** ボタンをクリックしてください。

<h2 id="step-2-create-randomizer-plugin">ステップ2 - Randomizerプラグインの作成</h2>

このステップでは、[コマンドライン](../../07.cli-console/01.command-line-intro/) を使う必要があります。これにより、DevToolsが提供するいくつかのCLIコマンドが使えて、新しいプラグインをかなりかんたんに作ることができます！

Gravをインストールしたルートディレクトリで、以下のコマンドを入力してください：

```bash
bin/plugin devtools new-plugin
```

このプロセスでは、プラグイン作成に必要な、いくつかの質問が聞かれます：

```bash
bin/plugin devtools new-plugin
Enter Plugin Name: Randomizer
Enter Plugin Description: Sends the user to a random page
Enter Developer Name: Acme Corp
Enter Developer Email: contact@acme.co

SUCCESS plugin Randomizer -> Created Successfully

Path: /www/user/plugins/randomizer

Make sure to run `composer update` to initialize the autoloader
```

> [!訳注]  
> プラグイン名を入力： Randomizer  
> プラグイン説明を入力： ユーザをランダムページに送る  
> 開発者名を入力： Acme Corp  
> 開発者Emailを入力： contact@acme.co  
> 2025年時点のGravでは、これらの他にGitHub IDや、Flex-objectsを使うかどうかも聞かれました。GitHub IDは空欄で回答し、Flex-objectsは、blankを答えておくと、以降のチュートリアルを進められると思います。次のNoteにある `composer update` は必須です。

> [!Note]  
> ここで、`composer update` を、新しく作ったプラグインフォルダで **実行する必要があります** 

DevToolsコマンドは、どこにプラグインを作ったかを報告してくれます。ここで作成されたプラグインは完全に機能しますが、ロジックがまだ書かれていません。必要に応じて、修正していく必要があります。

<h2 id="step-3-plugin-basics">ステップ3 - プラグインの基本</h2>

新しいプラグインができましたので、開発していきましょう。細分化しながら、どのようにプラグインが出来上がっているのか見ていきます。`user/plugins/randomizer` フォルダを見てください：

```txt
.
├── CHANGELOG.md
├── LICENSE
├── README.md
├── blueprints.yaml
├── randomizer.php
└── randomizer.yaml
```

シンプルな構成ですが、いくつかのものは必須です：

<h3 id="required-items-to-function">機能に必要なアイテム</h3>

以下のアイテムは必須です。もしなければ、機能しないでしょう。

* **`blueprints.yaml`** - Gravがプラグインの情報を得るための設定ファイルです。管理パネルでプラグイン詳細画面に表示するフォームも定義できます。このフォームから、プラグインへの設定を保存できます。[フォームの章を参照してください](../../06.forms/01.blueprints/)
* **`randomizer.php`** - このファイル名は、プラグイン名から名付けられます。プラグインに必要なロジックは何でも入れられます。[プラグインのイベントフック](../04.event-hooks/) を使えば、[Gravのライフサイクル](../05.grav-lifecycle/) 内のどこでもロジックを実行できます。
* **`randomizer.yaml`** - プラグインにオプションを使いたいときの設定ファイルです。プラグインで使われるものです。このファイル名は、 `.php` ファイルと同じ方法で名付けられます。

<h3 id="required-items-for-release">リリースに必要なアイテム</h3>

以下のアイテムは、GPMからリリースするときに必要です。

* **`CHANGELOG.md`** - A file that follows the [Grav Changelog Format](/advanced/grav-development#changelog-format) to show changes in releases.
* **`LICENSE`** - a license file, should probably be MIT unless you have a specific need for something else.
* **`README.md`** - A 'Readme' that should contain any documentation for the plugin.  How to install it, configure it, and use it.

<h2 id="step-4-plugin-configuration">ステップ4 - プラグイン設定</h2>

[**プラグインの概要**](#randomizer-plugin-overview) で説明したとおり、このプラグインには、いくつかの設定が必要です。`randomizer.yaml` ファイルは、次のようになるはずです：

```yaml
enabled: true
active: true
route: /random
filters:
    category: blog
```

もし望むなら、複数のフィルタ設定が可能ですが、今は、タクソノミーに `category: blog` となっているものだけを対象としましょう。

すべてのプラグインには、 `enabled` オプションが必要です。もしこれがサイト全体に対する設定で `false` だった場合、そのプラグインは初期化されません。また、すべてのプラグインには `active` オプションがあります。もしこれがサイト全体に対する設定で `false` だった場合、それぞれのページでそのプラグインを有効化（activate）しなければいけません。
Note that multiple plugins also support `enabled`/`active` in page frontmatter by using `mergeConfig`, detailed below.

> [!Warning]  
> 通常のGravのインストールでは、タクソノミーとして `category` と `tag` が使えます。この設定は、`user/config/site.yaml` ファイルで修正できます。

もちろん、他のすべてのGrav設定と同じように、日々の運用でこの設定を触ることはおすすめしません。それよりも、`/user/config/plugins/randomizer.yaml` ファイルを作り、カスタム設定を上書きしたほうが良いです。このプラグインが提供する `randomizer.yaml` には、デフォルト値だけを設定してください。

<h2 id="step-5-base-plugin-structure">ステップ5 - ベースとなるプラグイン構造</h2>

ベースとなるプラグインのclass構造は、すでに以下のようになっています：

```php
<?php
namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class RandomizerPlugin
 * @package Grav\Plugin
 */
class RandomizerPlugin extends Plugin
{
    /**
     * Composer autoload.
     *
     * @return ClassLoader
     */
    public function autoload(): ClassLoader
    {
        return require __DIR__ . '/vendor/autoload.php';
    }
}
```

いくつかの `user` 文を付け加えなければならないのは、use対象のclassを、このプラグインで使うためであると同時に、各クラスの名前空間全体をインラインで書く必要がなくなり、無駄なスペースを省き、コードが読みやすくなるためです。

`user` 文を、次のように修正してください：

```php
use Composer\Autoload\ClassLoader;
use Grav\Common\Plugin;
use Grav\Common\Page\Collection;
use Grav\Common\Uri;
use Grav\Common\Taxonomy;
```

このclass構造には、2つのカギとなる部分があります：

1. プラグインには、PHPファイルのトップに `namespace Grav\Plugin` が必要です。
2. プラグインは、**titlecase** で名付けられ、`Plugin` が最後に付きます。そして、`Plugin` の拡張(exted）でなければいけません。つまり、今回のチュートリアルでのclass名は、`RandomizerPlugin` となります。

<h2 id="step-6-subscribed-events">ステップ6 - イベントの登録</h2>

Gravでは、洗練されたイベントシステムを使います。最適化されたパフォーマンスを確保するため、すべてのプラグインはどのイベントに登録しているか、Gravにチェックされます。

```php
public static function getSubscribedEvents(): array
{
    return [
        'onPluginsInitialized' => [
            ['autoload', 100000], // TODO: Remove when plugin requires Grav >=1.7
            ['onPluginsInitialized', 0]
        ]
    ];
}
```

このプラグインは、`onPluginInitialized` イベントに登録しています。
This way we can use that event (which is the first event available to plugins) to determine if we should subscribe to other events.

> [!Tip]  
> **Note:** 最初の `autoload` イベントリスナーは、Grav 1.6 でのみ必要です。Grav 1.7 からは、自動で呼び出されます。

<h2 id="step-7-determine-if-the-plugin-should-run">ステップ7 - プラグインを実行するべきか決定</h2>

このステップでは、`RandomizerPlugin` class に、メソッドを追加します。このメソッドは、`onPluginInitialized` イベントで実行されるものです。よって、`randomizer.yaml` ファイルで設定したルーティング（/random）にユーザーが来たときだけ、有効化されるようにします。現在の 'sample' プラグインのロジックを、次のように置き換えてください：


```php
public function onPluginsInitialized(): void
{
    // Don't proceed if we are in the admin plugin
    if ($this->isAdmin()) {
        return;
    }

    /** @var Uri $uri */
    $uri = $this->grav['uri'];
    $config = $this->config();

    $route = $config['route'] ?? null;
    if ($route && $route == $uri->path()) {
        $this->enable([
            'onPageInitialized' => ['onPageInitialized', 0]
        ]);
    }
}
```

まず、**Uriオブジェクト** を **DIコンテナ** から受け取ります。このオブジェクトは、現在のURIに関するすべての情報を持つため、ルーティングの情報も持っています。

**config() メソッド** は、ベース **Plugin** の一部です。よって、 `route` として設定した値を取得し、かんたんに使えます。

次に、設定した route の値と、現在のURIのパスを比べます。もし等しいなら、このプラグインも、新しいイベント（`onPageInitialized`）に対応することを、Event Dispatcher に指示します。

このようなアプローチで、必要ない場合にコードを走らせないことができます。このような書き方により、可能な限りサイトの処理が速くなります。

<h2 id="step-8-display-the-random-page">ステップ8 - ランダムページを表示</h2>

最後のステップは、ランダムページを表示することです。以下のようなメソッドを追加することで可能となります：

```php
/**
 * Send user to a random page
 */
public function onPageInitialized(): void
{
    /** @var Taxonomy $uri */
    $taxonomy_map = $this->grav['taxonomy'];
    $config = $this->config();

    $filters = (array)($config['filters'] ?? []);
    $operator = $config['filter_combinator'] ?? 'and';

    if (count($filters) > 0) {
        $collection = new Collection();
        $collection->append($taxonomy_map->findTaxonomy($filters, $operator)->toArray());
        if (count($collection) > 0) {
            unset($this->grav['page']);
            $this->grav['page'] = $collection->random()->current();
        }
    }
}
```

このメソッドは、多少ややこしいです。何が起こっているのか、見ていきましょう：

1. まず、**Grav DIコンテナ** から、**タクソノミーオブジェクト** を取得し、`$taxonomy_map` 変数に代入します。

2. プラグイン設定から、filters の配列を受け取ります。今回の設定では、1つだけの配列になります： ['category' => 'blog']

3. フィルタがあるかどうかチェックし、ページを格納する新しい `コレクション` を、`$collection` 変数に作ります。

4. フィルタと一致するすべてのページを、 `$collection` 変数に追加します。

5. 現在の `page` オブジェクトを削除します。

6. 現在の `page` にコレクションの中からランダムなアイテムをセットします。


<h2 id="step-9-cleanup">ステップ9 - きれいにする</h2>

**DevTools** プラグインにより作られたプラグイン例は、`onPageContentRaw()` というイベントで使われるようになっています。このイベントは、新しいプラグインでは使いません。よって、関数全体を安全に消してください。

<h2 id="step-10-final-plugin-class">ステップ10 - 最終的なプラグインclass</h2>

これで最後です！ プラグインが完全にできました。プラグインclassは、次のようになっているはずです：

```php
<?php
namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Plugin;
use Grav\Common\Page\Collection;
use Grav\Common\Uri;
use Grav\Common\Taxonomy;

/**
 * Class RandomizerPlugin
 * @package Grav\Plugin
 */
class RandomizerPlugin extends Plugin
{
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents(): array
    {
    return [
        'onPluginsInitialized' => [
            ['autoload', 100000], // TODO: Remove when plugin requires Grav >=1.7
            ['onPluginsInitialized', 0]
        ]
    ];
    }

    /**
     * Composer autoload.
     *
     * @return ClassLoader
     */
    public function autoload(): ClassLoader
    {
        return require __DIR__ . '/vendor/autoload.php';
    }

    public function onPluginsInitialized(): void
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        /** @var Uri $uri */
        $uri = $this->grav['uri'];
        $config = $this->config();

        $route = $config['route'] ?? null;
        if ($route && $route == $uri->path()) {
            $this->enable([
                'onPageInitialized' => ['onPageInitialized', 0]
            ]);
        }
    }

    /**
     * Send user to a random page
     */
    public function onPageInitialized(): void
    {
        /** @var Taxonomy $uri */
        $taxonomy_map = $this->grav['taxonomy'];
        $config = $this->config();

        $filters = (array)($config['filters'] ?? []);
        $operator = $config['filter_combinator'] ?? 'and';

        if (count($filters) > 0) {
            $collection = new Collection();
            $collection->append($taxonomy_map->findTaxonomy($filters, $operator)->toArray());
            if (count($collection) > 0) {
                unset($this->grav['page']);
                $this->grav['page'] = $collection->random()->current();
            }
        }
    }
}
```

ここまでやっていただければ、あなたのサイトには、完全に機能する **Randomizer** プラグインが有効になっています。`http://yoursite.com/random` にブラウザからアクセスするだけで、ランダムページが表示されます。オリジナルの **Random** プラグインを、直接、[getgrav.org](https://getgrav.org/downloads/plugins) サイトの [プラグインダウンロード](https://getgrav.org/downloads/plugins) セクションからダウンロードすることもできます。

<h2 id="extending-blueprints">ブループリントの拡張</h2>

If your plugin needs to extend blueprints, e.g. default.yaml from /system/blueprints/pages/default.yaml there's no need to register your blueprint via hooks if you respect the folder structure placing your extending blueprint inside of [your-plugin-directory]/blueprints/pages/default.yaml. Grav will merge your extended blueprint definitions while themes can do the same lateron.

> system inheritance if you create this folder structure inside your plugin
> > - blueprints
> > - - pages
> > - - - default.yaml

> admin inheritance if you create this folder structure inside your plugin
> > - blueprints
> > - - admin
> > - - - pages
> > - - - - raw.yaml
> 
> From admin you have to inherit raw or others and theme blueprints extending default won't make it into your configuration.

If it's not pages, do it the same way for other inheritances...
This way you can keep extending changes at a minimum, that's what extending is all about :-).

<h2 id="merging-plugin-and-page-configuration">プラグイン設定とページ設定のマージ</h2>

多くのプラグインで使われる、よくあるテクニックのひとつとして、プラグイン設定（デフォルトのものでも、ユーザーconfigで上書きされたものでも）を、ページレベルの設定とマージするというアイディアがあります。つまり、 **サイト全体の** 設定を、デフォルトとして設定でき、ページごとに特定の設定を反映させることができます。このアイディアは、プラグインを強力にし、柔軟にしてくれます。

最近のバージョンのGravでは、この機能を自動で処理するヘルパーメソッドが追加されており、独自のコードを書く必要はありません。**SmartyPants** プラグインは、この機能を説明する良い例です。

```php
public function onPageContentProcessed(Event $event): void
{
    $page = $event['page'];
    $config = $this->mergeConfig($page);

    if ($config->get('process_content')) {
        $page->setRawContent(\Michelf\SmartyPants::defaultTransform(
            $page->getRawContent(),
            $config->get('options')
        ));
    }
}
```

<h2 id="implementing-cli-in-your-plugin">プラグインでCLIを実装</h2>

プラグインには、`bin/plugin` コマンドラインで、タスクを実行できる機能もあります。そのような機能を実装したい場合は、[プラグインCLIドキュメント](../../07.cli-console/03.grav-cli-plugin/#developers-integrate-the-cli-in-plugin) をお読みください。

