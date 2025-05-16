---
title: "プラグインレシピ"
layout: ../../../layouts/Default.astro
---

このページでは、Grav プラグインに関係する様々な問題とその解決策を紹介します。

<h2 id="output-some-php-code-result-in-a-twig-template">Twig テンプレートに PHP コードの結果を出力する</h2>

<h4 id="goal">目標：</h4>

カスタム PHP コードを処理したい。そして、ページでその結果を利用したい。

<h4 id="solution">解決策：</h4>

Twig 拡張機能を作成する新しいプラグインを作成し、 Twig テンプレートで利用できる PHP コンテンツを作成します。

`user/plugins/example` に、新しいプラグインフォルダを作ってください。そして、以下のファイルを追加してください：

```txt
user/plugins/example/example.php
user/plugins/example/example.yaml
user/plugins/example/twig/ExampleTwigExtension.php
```

`twig/ExampleTwigExtension.php` では、カスタム処理をします。そして `exampleFunction()` で文字列として結果を返します。

次に、 Twig テンプレートファイル（もしくは、ページで Twig 処理を有効化しているなら、ページのマークダウンファイル）で、 `{{ example() }}` を使って出力をレンダリングします。

概要は以上です。具体的なコードを見ていきましょう：

`example.php`:

```php
<?php
namespace Grav\Plugin;
use \Grav\Common\Plugin;
class ExamplePlugin extends Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            'onTwigExtensions' => ['onTwigExtensions', 0]
        ];
    }
    public function onTwigExtensions()
    {
        require_once(__DIR__ . '/twig/ExampleTwigExtension.php');
        $this->grav['twig']->twig->addExtension(new ExampleTwigExtension());
    }
}
```

`ExampleTwigExtension.php`:

```php
<?php
namespace Grav\Plugin;
use Grav\Common\Twig\Extension\GravExtension;

class ExampleTwigExtension extends GravExtension
{
    public function getName()
    {
        return 'ExampleTwigExtension';
    }
    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('example', [$this, 'exampleFunction'])
        ];
    }
    public function exampleFunction()
    {
        return 'something';
    }
}
```

`example.yaml`:

```yaml
enabled: true
```

プラグインは、これでインストール・有効化され、機能します。

<h2 id="filter-taxonomies-using-the-taxonomylist-plugin">taxonomylist プラグインでタクソノミーをフィルタリングする</h2>

<h4 id="goal-1">目標：</h4>

[taxonomy list Grav プラグイン](https://github.com/getgrav/grav-plugin-taxonomylist) を使って、ブログ投稿で使われているタグのリストを作りたい。ただし、それらすべてをリスト化するのではなく、最も良く使われているタグだけをリスト化したい。（たとえば、トップ5 のタグだけを表示するなど）

<h4 id="solution-1">解決策：</h4>

これは、 Grav プラグインの柔軟性が本当に扱いやすいことを示す具体例です。
最初のステップとして、 [taxonomy list Grav プラグイン](https://github.com/getgrav/grav-plugin-taxonomylist) を Grav にインストールしているか確認してください。
インストールされていたら、 `/yoursite/user/plugins/taxonomylist/templates/partials/taxonomylist.html.twig` を `/yoursite/user/themes/yourtheme/templates/partials/taxonomylist.html.twig` へコピーしてください。このコピーしたファイルに修正を加えていきます。

この作業のために、新しい3つの変数を導入します： `filter`, `filterstart` そして `filterend` です。これらは、

 * **filter** は、真偽値です。 `true` にすると、トップのいくつかのタグだけのリストが利用できます。（もしくは、あなたが使いたいと思っている他のタクソノミーなら何でも）
 *  **filterstart** は、任意の整数です。通常はゼロを設定します。タクソノミーの配列の中で、始めのインデックスです。
 * **filterend** も，任意の整数です。タクソノミーの配列で、そこで終わりとするインデックスです。注意してほしいのは、5つのタクソノミーのリストにしたい場合、5 を設定しなければいけません。ループは、 `filterend - 1` まで繰り返すからです。

次のステップは、タクソノミーのトップリストを表示したいテンプレートの中で、 `taxonomylist.html.twig` を呼び出すことです。
通常は、以下のようなスニペット例に見られるように、 `{% include %}` を使います。

```twig
{% if config.plugins.taxonomylist.enabled %}
<div class="sidebar-content">
    <h4>Popular Tags</h4>
    {% include 'partials/taxonomylist.html.twig' with {'taxonomy':'tag', filter: true, filterstart: 0, filterend: 5} %}
</div>
{% endif %}
```

この例では、トップ5 のタグをリスト表示します。

次に、 `taxonomylist.html.twig` に目を向けてみましょう。参考に、これは、最初にインストールしたときの、このファイルのデフォルトコードです：

```twig
{% set taxlist = taxonomylist.get() %}

{% if taxlist %}
    <span class="tags">
        {% for tax,value in taxlist[taxonomy] %}
            <a href="{{ base_url }}/{{ taxonomy }}{{ config.system.param_sep }}{{ tax|e('url') }}">{{ tax }}</a>
        {% endfor %}
    </span>
{% endif %}
```

この機能を新しい変数（つまり `filter`, `filterstart` そして `filterend` ）で作るため、このファイルに、次のように変数を含める必要があります：

```twig
{% set taxlist = taxonomylist.get %}

{% if taxlist %}
    {% set taxlist_taxonomy = taxlist[taxonomy] %}

    {% if filter %}
        {% set taxlist_taxonomy = taxlist_taxonomy|slice(filterstart,filterend) %}
    {% endif %}

    <span class="tags">
        {% for tax,value in taxlist_taxonomy %}
            <a href="{{ base_url }}/{{ taxonomy }}{{ config.system.param_sep }}{{ tax|e('url') }}">{{ tax }}</a>
        {% endfor %}
    </span>
{% endif %}
```

ここでは、デフォルトでタクソノミーのすべてのアイテムを、変数 `taxlist_taxonomy` に集めます。

`filter` が設定されていれば、タクソノミーは Twig フィルターの `slice` が使用されます。
このフィルターは、このケースでは、開始インデックス（このケースでは `filterstart` ）から、終了インデックス（このケースでは `filterend` ）までの配列の部分を取り出します。

オリジナルの `taxonomylist.html.twig` で、フィルターの有無に関わらず、 `taxlist_taxonomy` のコンテンツに対する処理とちょうど同じように `for` ループが実行されます。

<h2 id="adding-a-search-button-to-the-simplesearch-plugin">SimpleSearch プラグインに検索ボタンを追加する</h2>

<h4 id="goal-2">目標：</h4>

[Grav SimpleSearch プラグイン](https://github.com/getgrav/grav-plugin-simplesearch) は本当に便利ですが、 text フィールドに検索ボタンを追加したいです。
このボタンを追加する理由のひとつは、検索リクエストを始めるために、 `エンター` キーを押さなければならないことが、ユーザーにわかりにくいかもしれないからです。

<h4 id="solution-2">解決策：</h4>

まず、 [Grav SimpleSearch プラグイン](https://github.com/getgrav/grav-plugin-simplesearch) がインストール済みであることを確認してください。
次に、 `/yoursite/user/plugins/simplesearch/templates/partials/simplesearch-searchbox.html.twig` を `/yoursite/user/themes/yourtheme/templates/partials/simplesearch-searchbox.html.twig` にコピーしてください。このコピーファイル修正していきます。

先に進む前に、このファイルが何をするのか見てみましょう：

```twig
<input type="text" placeholder="Search..." value="{{ query }}" data-search-input="{{ base_url }}{{ config.plugins.simplesearch.route}}/query" />
<script>
jQuery(document).ready(function($){
    var input = $('[data-search-input]');
    input.on('keypress', function(event) {
        if (event.which == 13 && input.val().length > 3) {
            event.preventDefault();
            window.location.href = input.data('search-input') + '{{ config.system.param_sep }}' + input.val();
        }
    });
});
</script>
```

最初の行は単純に、 text 入力フィールドを Twig テンプレートに組み込んでいます。
`data-search-input` 属性が、検索結果ページのベースとなる URL を保存します。
デフォルトでは、 `http://yoursite/search/query` です。

その後の jQuery の行に移動しましょう。
ここでは、 `data-search-input` 属性を持つタグが `input` 変数に代入されます。
次に、 jQuery `.on()` メソッドが `input` に対して実行されます。
`.on()` メソッドは、選択された要素（このケースでは、 `<input>` text フィールド）にイベントハンドラーを適用します。
よって、ユーザーがキーを押し（ `keypress` ）て検索を始めたとき、 `if` 文で、以下の内容が `true` であるかチェックします：

1. `Enter` キーが押されたか： `event.which == 13` この 13 は、 `Enter` キーのキーボード上での数字の値です。
2. 検索ボックスに入力されている文字数が、3文字より大きいか。これは調整しても良いかもしれません。あなたの所属に3文字以下の略語が多いかもしれないので。

これらが true だったとき、 `event.preventDefault();` により、`Enter` キーが押されたときのブラウザのデフォルトアクションが無視されます。ブラウザのデフォルトアクションは、プラグインの検索機能を妨げてしまうためです。
最後に、検索クエリーの完全な URL が構築されます。
デフォルトでは、 `http://yoursite/search/query:yourquery` になります。
ここから、 `/yoursite/user/plugins/simplesearch/simplesearch.php` が実際の検索を処理し、プラグインの Twig ファイルが結果をリスト表示します。

わたしたちの解決策に戻ることはありません！ もし検索ボタンを追加したいなら、わたしたちのやるべきことは：

1. ボタンを追加する
2. そのボタンに `.on()` メソッドを適用する。ただし、今回は `keypress` ではなく `click` を使います。

これは、 [Turret CSS フレームワーク](http://bigfishtv.github.io/turret/docs/index.html) を使って以下のようなコードで実現できます。
他のフレームワーク向けのコードスニペットは、最後にリスト化します。

```html
<div class="input-group input-group-search">
	<input type="search" placeholder="Search" value="{{ query }}" data-search-input="{{ base_url }}{{ config.plugins.simplesearch.route}}/query" >
	<span class="input-group-button">
		<button class="button" type="submit">Search</button>
	</span>
</div>

<script>
jQuery(document).ready(function($){
    var input = $('[data-search-input]');
    var searchButton = $('.button.search');

    input.on('keypress', function(event) {
        if (event.which == 13 && input.val().length > 3) {
            event.preventDefault();
            window.location.href = input.data('search-input') + '{{ config.system.param_sep }}' + input.val();
        }
    });

    searchButton.on('click', function(event) {
        if (input.val().length > 3) {
            event.preventDefault();
            window.location.href = input.data('search-input') + '{{ config.system.param_sep }}' + input.val();
        }
    });
});
</script>
```

HTML と class 属性は、 Turret に特有のものですが、最後の結果は、 [このようになります](http://bigfishtv.github.io/turret/docs/index.html#input-group) 。
また、 `.on()` メソッドが検索ボタンに適用されたこともわかりますが、 `if` 文の中では、3文字以上かどうかの文字数のチェックだけを行っています。

ここで、他のいくつかのフレームワークでの、 text フィールドと検索ボタンについて、デフォルトの HTML を示します。

[**Bootstrap**](http://getbootstrap.com/)

```html
<div class="input-group">
    <input type="text" class="form-control" placeholder="Search for...">
    <span class="input-group-btn">
        <button class="btn btn-default" type="button">Go!</button>
    </span>
</div>
```

[**Materialize**](http://materializecss.com/)

```html
<div class="input-field">
    <input id="search" type="search" required>
    <label for="search"><i class="material-icons">search</i></label>
</div>
```

[**Pure CSS**](http://purecss.io)

```html
<form class="pure-form">
    <input type="text" class="pure-input-rounded">
    <button type="submit" class="pure-button">Search</button>
</form>
```

[**Semantic UI**](http://semantic-ui.com/)

```html
<div class="ui action input">
  <input type="text" placeholder="Search...">
  <button class="ui button">Search</button>
</div>
```

<h2 id="iterating-through-pages-and-media">ページとメディアの繰り返し</h2>

<h4 id="goal-3">目標：</h4>

PHP や Twig を通して、全てのページとそれぞれのページに関連するメディアにアクセスし、プラグインでループしたり、その他の操作したりしたい。

<h4 id="solution-3">解決策：</h4>

Grav のコレクション機能を使って、再帰的に全てのページのインデックスを構築します。また、それぞれのページをインデックスする際に、メディアファイルも収集します。 
[DirectoryListing](https://github.com/OleVik/grav-plugin-directorylisting/blob/v2.0.0-rc.2/Utilities.php#L64-L105) プラグインはまさにこれを行い、生成された木構造を使って HTML リストをビルドします。
これを実行するには、再帰関数 - もしくはプラグインの class の中であれば再帰メソッド - を作成し、1つ1つのページを走査し、配列に保存します。
メソッドは再帰しなければいけません。それぞれのページに子ページがあれば、メソッド内でメソッド自信を呼び出すからです。

まず最初に、メソッドには3つのパラメータが必要です：　最初がページの `$route` で、 Grav にそのページがどこにあるかを知らせます。2つ目が `$mode` で、メソッドにそのページ自信を繰り返すか、それとも子ページについてかを知らせます。3つ目が `$depth` で、ページがあるレベルを記録します。
The method initially instantiates the Page-object, then deals with depth and mode, and constructs the collection.
デフォルトでは、ページは日付降順で並べられますが、これは設定できます。
それから、各ページを保持する `$paths` 配列を構築します。ルーティングは Grav で一意なので、各ページを識別するためにこの配列をキーとして使用されます。

Now we iterate over the pages, adding depth, title, and route (also kept as a value for ease-of-access). Within the foreach-loop, we also try to retrieve child-pages, and add them if found. Also, we find all media associated with the page, and add them. Because the method is recursive, it will continue looking for pages and child-pages until no more can be found.

The returned data is a tree-structure, or multidimensional-array in PHP's parlance, containing all pages and their media. This can be passed into Twig, or used within the plugin itself. Note that with very large folder-structures PHP might time out or fail because of recursion-limits, eg. folders 100 or more levels deep.

```php
/**
 * Creates page-structure recursively
 * @param string $route Route to page
 * @param integer $depth Reserved placeholder for recursion depth
 * @return array Page-structure with children and media
 */
public function buildTree($route, $mode = false, $depth = 0)
{
    $page = Grav::instance()['page'];
    $depth++;
    $mode = '@page.self';
    if ($depth > 1) {
        $mode = '@page.children';
    }
    $pages = $page->evaluate([$mode => $route]);
    $pages = $pages->published()->order('date', 'desc');
    $paths = array();
    foreach ($pages as $page) {
        $route = $page->rawRoute();
        $path = $page->path();
        $title = $page->title();
        $paths[$route]['depth'] = $depth;
        $paths[$route]['title'] = $title;
        $paths[$route]['route'] = $route;
        if (!empty($paths[$route])) {
            $children = $this->buildTree($route, $mode, $depth);
            if (!empty($children)) {
                $paths[$route]['children'] = $children;
            }
        }
        $media = new Media($path);
        foreach ($media->all() as $filename => $file) {
            $paths[$route]['media'][$filename] = $file->items()['type'];
        }
    }
    if (!empty($paths)) {
        return $paths;
    } else {
        return null;
    }
}
```

## Custom Twig templates plugin

<h4 id="goal-4">目標：</h4>

Rather than using theme inheritance, it's possible to create a very simple plugin that allows you to use a custom location to provide customized Twig templates. 

<h4 id="solution-4">解決策：</h4>

The only thing you need in this plugin is an event to provide a location for your templates.  The simplest way to create the plugin is to use the `devtools` plugin.  So install that with:

```bash
$ bin/gpm install devtools
```

After that's installed, create a new plugin with the command:

```bash
$ bin/plugin devtools newplugin
```

Fill in the details for the name, author, etc.  Say we call it `Custom Templates`, and the plugin will be created in `/user/plugins/custom-templates`.  All you need to do now is edit the `custom-templates.php` file and put this code:

```php
<?php
namespace Grav\Plugin;

use \Grav\Common\Plugin;

class CustomTemplatesPlugin extends Plugin
{
    /**
     * Subscribe to required events
     * 
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0]
        ];
    }

    /**
     * Add current directory to twig lookup paths.
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }
}
```

This plugin simple subscribes to the `onTwigTemplatePaths()` event, and then in that event method, it adds the `user/plugins/custom-templates/templates` folder to this of paths that Twig will check.

This allows you to drop in a Twig template called `foo.html.twig` and then any page called `foo.md` will be able to use this template.

! NOTE: This will add the plugin's custom template path to the **end** of the Twig template path array. This means the theme (which is always first), will have precedence over the plugin's templates of the same name.  To resolve this, simply put the plugin's template path in the front of the array by modifying the event method:

```twig
    /**
     * Add current directory to twig lookup paths.
     */
    public function onTwigTemplatePaths()
    {
        array_unshift($this->grav['twig']->twig_paths, __DIR__ . '/templates');
    }
```

## Using Cache in your own plugins

<h4 id="goal-5">目標：</h4>

When developing your own plugins, it's often useful to use Grav's cache to cache data to improve performance.  Luckily it's a very simple process to use cache in your own code.

<h4 id="solution-5">解決策：</h4>

This is some basic code that shows you how caching works:

```php
    $cache = Grav::instance()['cache'];
    $id = 'myplugin-data'
    $list = [];

    if ($data = $cache->fetch($id)) {
        return $data;
    } else {
        $data = $this->gatherData();
        $cache->save($hash, $data);
        return $data;
    }
```

First, we get Grav's cache object, and we then try to see if our data already exists in the cache (`$data = $cache->fetch($id)`).  If `$data` exists, simply return it with no extra work needed.

However, if the cache fetch returns null, meaning it's not cached, do some _work_ and get the data (`$data = $this->gatherData()`), and then simply save the data for next time (`$cache->save($hash, $data)`).



## Learning by Example

With the abundance of plugins currently available, chances are that you will find your answers somewhere in their source code. The problem is knowing which ones to look at. This page attempts to list common plugin issues and then lists specific plugins that demonstrate how to tackle them.

Before you proceed, be sure you've familiarized yourself with [the core documentation](../../04.plugins/), especially the [Grav Lifecycle](../../04.plugins/05.grav-lifecycle/)!

### How do I read from and write data to the file system?

Grav might be flat file, but flat file &#8800; static! There are numerous ways read and write data to the file system.

  * If you just need read access to YAML data, check out the [Import plugin](https://github.com/Deester4x4jr/grav-plugin-import).

  * The preferred interface is via the built-in [RocketTheme\Toolbox\File](https://learn.getgrav.org/api#class-RocketThemeToolboxFile) interface.

  * There's nothing stopping you from using [SQLite](https://sqlite.org/) either.

  * The simplest example is probably the [Comments](https://github.com/getgrav/grav-plugin-comments) plugin.

  * Others include

    * [Table Importer](https://github.com/Perlkonig/grav-plugin-table-importer)

    * [Thumb Ratings](https://github.com/iusvar/grav-plugin-thumb-ratings)

    * [Webmention](https://github.com/Perlkonig/grav-plugin-webmention)

### How do I make data from a plugin available to Twig?

One way is via the `config.plugins.X` namespace. Simply do a `$this->config->set()` as seen in the following examples:

  * [ipLocate](https://github.com/Perlkonig/grav-plugin-iplocate/blob/master/iplocate.php#L82)
  * [Count Views](https://github.com/Perlkonig/grav-plugin-count-views/blob/master/count-views.php#L88)

You can then access that in a Twig template via `{{ config.plugins.X.whatever.variable }}`.

Alternatively, you can pass variables via `grav['twig']`:

  * [Blogroll](https://github.com/Perlkonig/grav-plugin-blogroll/blob/master/blogroll.php#L43), which you can then access directly [in your template](https://github.com/Perlkonig/grav-plugin-blogroll/blob/master/templates/partials/blogroll.html.twig#L32).

Finally, you can inject data directly into the page header, as seen in [the Import plugin](https://github.com/Deester4x4jr/grav-plugin-import).

### How do I inject Markdown into a page?

According to the [Grav Lifecycle](../../04.plugins/05.grav-lifecycle/), the latest event hook where you can inject raw Markdown is `onPageContentRaw`. The earliest is probably `onPageInitialized`. You can just grab `$this->grav['page']->rawMarkdown()`, munge it, and then write it back out with `$this->grav['page']->setRawContent()`. The following plugins demonstrate this:

  * [Page Inject](https://github.com/getgrav/grav-plugin-page-inject)

  * [Table Importer](https://github.com/Perlkonig/grav-plugin-table-importer)

### How do I inject HTML into the final output?

The latest you can inject HTML, and still have your output cached, is during the `onOutputGenerated` event. You can just grab and modify `$this->grav->output`.

  * Many common tasks can be accomplished using the [Shortcode Core](https://github.com/getgrav/grav-plugin-shortcode-core) infrastructure.

  * The [Pubmed](https://github.com/Perlkonig/grav-plugin-pubmed) and [Tablesorter](https://github.com/Perlkonig/grav-plugin-tablesorter) plugins take a more brute force approach.

### How do I inject assets like JavaScript and CSS files?

This is done through the [Grav\Common\Assets](https://learn.getgrav.org/api#class-gravcommonassets) interface.

  * [Google Analytics](https://github.com/escopecz/grav-ganalytics)

  * [Bootstrapper](https://github.com/getgrav/grav-plugin-bootstrapper)

  * [Gravstrap](https://github.com/giansi/gravstrap)

  * [Tablesorter](https://github.com/Perlkonig/grav-plugin-tablesorter)

### How do I affect the response headers and response codes?

You can use PHP's `header()` command to set response headers. The latest you can do this is during the `onOutputGenerated` event, after which output is actually sent to the client. The response code itself can only be set in the YAML header of the page in question (`http_response_code`).

  * The [Graveyard](https://github.com/Perlkonig/grav-plugin-graveyard) plugin replaces `404 NOT FOUND` with `410 GONE` responses via the YAML header.

  * The [Webmention](https://github.com/Perlkonig/grav-plugin-webmention) sets the `Location` header on a `201 CREATED` response.

### How do I incorporate third-party libraries into my plugin?

Usually, you'd incorporate other complete libraries into a `vendor` subfolder and `require` its `autoload.php` where appropriate in your plugin. (If you're using Git, consider using [subtrees](https://help.github.com/articles/about-git-subtree-merges/).)

  * [Shortcode Core](https://github.com/getgrav/grav-plugin-shortcode-core)

  * [Table Importer](https://github.com/Perlkonig/grav-plugin-table-importer)

### How do I extend Twig?

The simplest way is to follow the [Custom Twig Filter/Function](../../10.cookbook/02.twig-recipes/#custom-twig-filter-function) example in the **Twig Recipes** section.

Also, [read the Twig docs](https://twig.symfony.com/) and develop your extension. Then look at the [TwigPCRE](https://github.com/kesslernetworks/grav-plugin-twigpcre) plugin to learn how to incorporate it into Grav.

### How do I interact with external APIs?

Grav provides the [Grav\Common\GPM\Response](https://learn.getgrav.org/api#class-grav-common-gpm-response) object, but there's nothing stopping you from doing it directly if you so wish.

  * [ipLocate](https://github.com/Perlkonig/grav-plugin-iplocate)

  * [Pubmed](https://github.com/Perlkonig/grav-plugin-pubmed)



