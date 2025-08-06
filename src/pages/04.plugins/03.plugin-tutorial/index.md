---
title: プラグインのチュートリアル
layout: ../../../layouts/Default.astro
lastmod: '2025-08-06'
description: '簡単なプラグインをステップごとに作成しながら、プラグインの仕組みや便利機能、拡張方法などを解説します'
---

プラグインは通常、 Grav のコア機能では実現できないタスクを使うために開発されます。

このチュートリアルでは、ランダムにページを表示するプラグインを作ります。  
おそらく、ブログサイトなどで似たような機能をみたことがあるかもしれません。  
ボタンをクリックすると、ランダムにブログ投稿を表示してくれるという機能です。

> [!Note]  
> この機能を使える `Random` というプラグインがすでにあるので、今回作るテストプラグインは `Randomizer` という名前にしましょう。

この機能は、 **標準に搭載された** 機能ではありませんが、プラグインを使えば **簡単に** 追加できるものです。  
Grav の多くの場面でそうであったように、これを実行する方法が _ひとつしかない_ ということはありません。  
代わりに、たくさんのオプションがあります。  
わたしたちがカバーできるのは、そのうちのひとつです...

<h2 id="randomizer-plugin-overview">Randomizer プラグインの概要</h2>

プラグイン作成に向けて、次のようなアプローチを取ります：

1. 'トリガーURL' （例： `/random` ）に URI が一致したときに、プラグインが実行されるようにする

2. ランダムページの対象として、特定のタクソノミーだけがフィルタリングされるようにする（例： `category: blog` ）

3. フィルタリングされたページから、ランダムに1ページを選び、 Grav にそのページをページコンテンツとして採用するように伝える。

OK！  
十分にシンプルですね？  
さっそく始めましょう！


<h2 id="step-1-install-devtools-plugin">ステップ1 - DevTools プラグインのインストール</h2>

> [!Info]  
> 以前のバージョンのチュートリアルでは、手作業でプラグインを作成していました。新しい **DevTools プラグイン** によって、これらのプロセスがスキップできます

新しいプラグインを作る最初のステップは、 **DevTools プラグインのインストール** です。  
これには2つの方法があります。

<h4 id="install-via-cli-gpm">CLI GPM によるインストール</h4>

* Grav をインストールしたルートディレクトリに、コマンドラインを移動させてください

```bash
bin/gpm install devtools
```

<h4 id="install-via-admin-plugin">管理パネルからのインストール</h4>

* ログイン後、サイドバーから **Plugins** セクションに移動してください。
* 画面右上の **Add** ボタンをクリックしてください。
* 一覧から **DevTools** を見つけ、 **Install** ボタンをクリックしてください。

<h2 id="step-2-create-randomizer-plugin">ステップ2 - Randomizer プラグインの作成</h2>

このステップでは、 [コマンドライン](../../07.cli-console/01.command-line-intro/) を使う必要があります。  
これにより、 DevTools が提供するいくつかの CLI コマンドが使えて、新しいプラグインをかなり簡単に作ることができます！

Grav をインストールしたルートディレクトリで、以下のコマンドを入力してください：

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
> 成功 プラグイン Randomizer -> 作成に成功しました
> パス: /www/user/plugins/randomizer
> autoloader を初期化するために `composer update` を確実に実行してください
>  
> 2025年時点の Grav では、これらの他に GitHub ID や、 Flex-objects を使うかどうかも聞かれました。 GitHub ID は空欄で回答し、 Flex-objects は、 blank を答えておくと、以降のチュートリアルを進められると思います。次の Note にある `composer update` は必須です。

> [!Note]  
> ここで、`composer update` を、新しく作ったプラグインフォルダで **実行する必要があります** 

DevTools コマンドは、どこにプラグインを作ったかを報告してくれます。  
ここで作成されたプラグインは完全に機能しますが、ロジックがまだ書かれていません。  
必要に応じて、修正していく必要があります。

<h2 id="step-3-plugin-basics">ステップ3 - プラグインの基本</h2>

新しいプラグインができましたので、開発していきましょう。  
細分化しながら、どのようにプラグインが出来上がっているのか見ていきます。  
`user/plugins/randomizer` フォルダを見てください：

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

以下のアイテムは必須です。  
もしなければ、機能しないでしょう。

* **`blueprints.yaml`** - Grav がプラグインの情報を得るための設定ファイルです。管理パネルでプラグイン詳細画面に表示するフォームも定義できます。このフォームから、プラグインへの設定を保存できます。 [フォームの章を参照してください](../../06.forms/01.blueprints/)
* **`randomizer.php`** - このファイル名は、プラグイン名から名付けられます。プラグインに必要なロジックは何でも入れられます。 [プラグインのイベントフック](../04.event-hooks/) を使えば、 [Gravのライフサイクル](../05.grav-lifecycle/) 内のどこでもロジックを実行できます。
* **`randomizer.yaml`** - プラグインにオプションを使いたいときの設定ファイルです。プラグインで使われるものです。このファイル名は、 `.php` ファイルと同じやり方で名付けられます。

<h3 id="required-items-for-release">リリースに必要なアイテム</h3>

以下のアイテムは、 GPM からリリースするときに必要です。

* **`CHANGELOG.md`** - [Grav 変更ログのフォーマット](../../08.advanced/09.grav-development/#changelog-format) に従って、リリースごとの変更を表示するファイル
* **`LICENSE`** - ライセンスファイル。特に理由が無ければ MIT ライセンスになるでしょう。
* **`README.md`** - リードミーファイル。プラグインに関するドキュメントを含んでいるべきです。インストール方法、設定方法、使用方法など。

<h2 id="step-4-plugin-configuration">ステップ4 - プラグイン設定</h2>

[**プラグインの概要**](#randomizer-plugin-overview) で説明したとおり、このプラグインには、いくつかの設定が必要です。  
`randomizer.yaml` ファイルは、次のようになるはずです：

```yaml
enabled: true
active: true
route: /random
filters:
    category: blog
```

もし望むなら、複数のフィルタ設定が可能ですが、今は、タクソノミーに `category: blog` となっているものだけを対象としましょう。

すべてのプラグインには、 `enabled` オプションが必要です。  
もしこれがサイト全体に対する設定で `false` だった場合、そのプラグインは初期化されません。  
また、すべてのプラグインには `active` オプションがあります。  
もしこれがサイト全体に対する設定で `false` だった場合、それぞれのページでそのプラグインを有効化（activate）しなければいけません。  
複数のプラグインも `enabled`/`active` をページフロントマターで `mergeConfig` を使ってサポートすることに注意してください。詳細は後述します。

> [!Warning]  
> 通常の Grav のインストールでは、タクソノミーとして `category` と `tag` が使えます。この設定は、 `user/config/site.yaml` ファイルで修正できます。

もちろん、他のすべての Grav 設定と同じように、日々の運用でこの設定を触ることはおすすめしません。  
それよりも、 `/user/config/plugins/randomizer.yaml` ファイルを作り、カスタム設定を上書きしたほうが良いです。  
このプラグインが提供する `randomizer.yaml` には、デフォルト値だけを設定してください。

<h2 id="step-5-base-plugin-structure">ステップ5 - ベースとなるプラグイン構造</h2>

ベースとなるプラグインの class 構造は、すでに以下のようになっています：

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

いくつかの `use` 文を付け加えなければならないのは、 use 文の class を、このプラグインで使うときに、各クラスの名前空間全体をインラインで書く必要がなくなり、無駄なスペースを省き、コードが読みやすくなるためです。

`use` 文を、次のように修正してください：

```php
use Composer\Autoload\ClassLoader;
use Grav\Common\Plugin;
use Grav\Common\Page\Collection;
use Grav\Common\Uri;
use Grav\Common\Taxonomy;
```

この class 構造には、2つのカギとなる部分があります：

1. プラグインには、 PHP ファイルのトップに `namespace Grav\Plugin` が必要です。
2. プラグインは、 **titlecase** で名付けられ、`Plugin` が最後に付きます。そして、`Plugin` の拡張(exted）でなければいけません。つまり、今回のチュートリアルでの class 名は、`RandomizerPlugin` となります。

<h2 id="step-6-subscribed-events">ステップ6 - イベントの登録</h2>

Grav では、洗練されたイベントシステムを使います。  
最適化されたパフォーマンスを確保するため、すべてのプラグインはどのイベントに登録しているか、 Grav にチェックされます。

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

このプラグインは、 `onPluginInitialized` イベントに登録しています。  
このようにして、イベント（プラグインで利用できる最初のもの）を利用し、他のイベントに登録するかどうかを決定します。

> [!Note]  
> 最初の `autoload` イベントリスナーは、 Grav 1.6 でのみ必要です。Grav 1.7 からは、自動で呼び出されます。

<h2 id="step-7-determine-if-the-plugin-should-run">ステップ7 - プラグインを実行するべきか決定</h2>

このステップでは、 `RandomizerPlugin` class に、メソッドを追加します。  
このメソッドは、 `onPluginInitialized` イベントで実行されるものです。  
よって、 `randomizer.yaml` ファイルで設定したルーティング（/random）にユーザーが来たときだけ、有効化されるようにします。  
現在のサンプルプラグインのロジックを、次のように置き換えてください：


```php
public function onPluginsInitialized(): void
{
    // Don't proceed if we are in the admin plugin
    // 管理パネルプラグインにいるときは処理しない
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

まず、 **Uriオブジェクト** を **DIコンテナ** から受け取ります。  
このオブジェクトは、現在の URI に関するすべての情報を持つため、ルーティングの情報も持っています。

**config() メソッド** は、ベース **Plugin** の一部です。  
よって、 `route` として設定した値を取得し、簡単に使えます。

次に、設定したルーティングの値と、現在の URI のパスを比較します。  
もし等しいなら、このプラグインも、新しいイベント（`onPageInitialized`）に対応することを、Event Dispatcher に指示します。

このようなアプローチで、必要ない場合にコードを走らせないことができます。  
このような書き方により、可能な限りサイトの処理が速くなります。

<h2 id="step-8-display-the-random-page">ステップ8 - ランダムページを表示</h2>

最後のステップは、ランダムページを表示することです。  
以下のようなメソッドを追加することで可能となります：

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

このメソッドは、多少ややこしいです。  
何が起こっているのか、見ていきましょう：

1. まず、**Grav DIコンテナ** から、**タクソノミーオブジェクト** を取得し、`$taxonomy_map` 変数に代入します。

2. プラグイン設定から、filters の配列を受け取ります。今回の設定では、1つだけの配列になります： ['category' => 'blog']

3. フィルタがあるかどうかチェックし、ページを格納する新しい `コレクション` を、`$collection` 変数に作ります。

4. フィルタと一致するすべてのページを、 `$collection` 変数に追加します。

5. 現在の `page` オブジェクトを削除します。

6. 現在の `page` にコレクションの中からランダムなアイテムをセットします。


<h2 id="step-9-cleanup">ステップ9 - きれいにする</h2>

**DevTools** プラグインにより作られたプラグイン例は、`onPageContentRaw()` というイベントで使われるようになっています。  
このイベントは、新しいプラグインでは使いません。  
よって、関数全体を安全に消してください。

> [!訳注]  
> 2025年時点で plugin を作るとき、そのようなメソッドは見つからないので、もしかしたら古いバージョンのときの情報かもしれません。ステップ10の class になっていれば、問題ないです。

<h2 id="step-10-final-plugin-class">ステップ10 - 最終的なプラグインclass</h2>

これで最後です！  
プラグインが完全にできました。  
プラグイン class は、次のようになっているはずです：

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

ここまでやっていただければ、あなたのサイトには、完全に機能する **Randomizer** プラグインが有効になっています。  
`http://yoursite.com/random` にブラウザからアクセスするだけで、ランダムページが表示されます。  
オリジナルの **Random** プラグインを、直接 [getgrav.org](https://getgrav.org/downloads/plugins) サイトの [プラグインダウンロード](https://getgrav.org/downloads/plugins) セクションからダウンロードすることもできます。

<h2 id="extending-blueprints">ブループリントの拡張</h2>

プラグインがブループリントを拡張する必要があるとき、たとえば `/system/blueprints/pages/default.yaml` から `default.yaml` を拡張するとき、`[your-plugin-directory]/blueprints/pages/default.yaml` 内に、拡張したブループリントを置けば、イベントフックを通じてブループリントを登録する必要はありません。  
Grav は、拡張されたブループリントをマージします。

> プラグインフォルダに、以下のようなフォルダ構造を作ったら、システムが継承されます
> > - blueprints
> > - - pages
> > - - - default.yaml
> 
> プラグインフォルダに、以下のようなフォルダ構造を作ったら、管理パネルプラグインが継承されます
> > - blueprints
> > - - admin
> > - - - pages
> > - - - - raw.yaml
> 
> 管理パネルプラグインでは、 raw.yaml などの管理パネルプラグイン用の blueprint を継承しなければいけません。テーマの blueprints が system の default.yaml を継承しても、管理パネルの設定には反映されません。

もし pages でなかったとしても、他の継承を同じようにできます...   
このようにして、拡張による変更を最小限にできます。  
拡張とはこのようなものです :-)

<h2 id="merging-plugin-and-page-configuration">プラグイン設定とページ設定のマージ</h2>

多くのプラグインで使われる、よくあるテクニックのひとつとして、プラグイン設定（デフォルトのものでも、ユーザー config で上書きされたものでも）を、ページレベルの設定とマージするというアイディアがあります。  
つまり、 **サイト全体の** 設定を、デフォルトとして設定でき、ページごとに特定の設定を反映させることができます。  
このアイディアは、プラグインを強力にし、柔軟にしてくれます。

最近のバージョンの Grav では、この機能を自動で処理するヘルパーメソッドが追加されており、独自のコードを書く必要はありません。  
**SmartyPants** プラグインは、この機能を説明する良い例です。

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

<h2 id="implementing-cli-in-your-plugin">プラグインで CLI を実装</h2>

プラグインには、 `bin/plugin` コマンドラインで、タスクを実行できる機能もあります。  
そのような機能を実装したい場合は、 [プラグイン CLI ドキュメント](../../07.cli-console/03.grav-cli-plugin/#developers-integrate-the-cli-in-plugin) をお読みください。

