---
title: プラグインレシピ
layout: ../../../layouts/Default.astro
lastmod: '2025-05-16'
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
これを実行するには、再帰関数（もしくはプラグインの class の中であれば再帰メソッド）を作成し、1つ1つのページを走査し、配列に保存します。
メソッドは再帰しなければいけません。それぞれのページに子ページがあれば、メソッド内でメソッド自身を呼び出すからです。

まず最初に、メソッドには3つのパラメータが必要です：　最初がページの `$route` で、 Grav にそのページがどこにあるかを知らせます。2つ目が `$mode` で、メソッドにそのページ自身を繰り返すか、それとも子ページについてかを知らせます。3つ目が `$depth` で、ページがあるレベルを記録します。
メソッドは、ページオブジェクトを最初にインスタンス化し、それから depth や mode を取り扱い、コレクションを構築します。
デフォルトでは、ページは日付降順で並べられますが、これは設定変更できます。
それから、各ページを保持する `$paths` 配列を構築します。ルーティングは Grav 内で一意なので、各ページを識別するためにこの配列をキーとして使用します。

次に、ページを繰り返しながら、depth や、タイトル、route を追加していきます（また、アクセスしやすいように値としても保持しておきます）。
foreach ループの中で、子ページの取得も試み、見つかれば追加します。
また、ページに関係するメディアを見つけたら、それらも追加します。
メソッドは再帰するので、ページや子ページが見つからなくなるまで、それらを探し続けます。

返り値のデータは、木構造または PHP での多次元配列で、すべてのページとそれぞれのメディアを含みます。
これは Twig に渡すことができるほか、プラグイン自身の中で使うこともできます。とても大きなフォルダ構造の場合、 PHP は再帰制限によりタイムアウトや、失敗するかもしれないことに注意してください。たとえば、100以上の深さのあるフォルダーの場合などです。

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

<h2 id="custom-twig-templates-plugin">Twig テンプレートプラグインをカスタムする</h2>

<h4 id="goal-4">目標：</h4>

テーマを継承するよりも、とてもシンプルなプラグインを作成して、カスタマイズされた Twig テンプレートを提供するためのカスタムの場所を作りたい。

<h4 id="solution-4">解決策：</h4>

このプラグインで必要なことは、テンプレートに場所を提供するためのイベントだけです。プラグインを作成する最も簡単な方法は、 `devtools` プラグインを使うことです。そこで、まずは次のようにインストールしましょう：

```bash
$ bin/gpm install devtools
```

インストールが終わったら、このコマンドで新しいプラグインを作成します：

```bash
$ bin/plugin devtools newplugin
```

詳細を入力してください。プラグイン名や、作者、など。今回は、 `Custom Templates` としましょう。プラグインは、 `/user/plugins/custom-templates` に作成されます。
次にやるべきは、 `custom-templates.php` ファイルの編集です。以下のコードを書いてください：

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

このプラグインは、シンプルに `onTwigTemplatePaths()` イベントに登録し、そのイベントメソッドで、 Twig がチェックするパスに `user/plugins/custom-templates/templates` フォルダを追加します。

これにより、 `foo.html.twig` という Twig テンプレートを追加でき、 `foo.md` というページはすべてこのテンプレートを利用して表示されます。

> [!Note]  
> これは、プラグインのカスタムテンプレートパスを、 Twig テンプレートパス配列の **最後** に追加します。これはつまり、テーマ（常に最初に呼ばれる）が、同じ名前のプラグインのテンプレートよりも優先されるということです。これを解決するには、単純にプラグインのテンプレートパスを配列の最初に置くだけです。イベントメソッドを修正することでできます：

```twig
    /**
     * Add current directory to twig lookup paths.
     */
    public function onTwigTemplatePaths()
    {
        array_unshift($this->grav['twig']->twig_paths, __DIR__ . '/templates');
    }
```

<h2 id="using-cache-in-your-own-plugins">自身のプラグインでキャッシュを使う</h2>

<h4 id="goal-5">目標：</h4>

プラグイン開発をしているときに、パフォーマンスを向上させるため、データをキャッシュするために Grav のキャッシュを利用するのは、とても便利です。
幸運なことに、自身のコードでキャッシュを利用するのは、とても簡単な処理です。

<h4 id="solution-5">解決策：</h4>

以下は、キャッシュが機能する方法について紹介する基本的なコードです：

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

まず、 Grav のキャッシュオブジェクトを取得します。キャッシュにデータが存在するかを調べます（ `$data = $cache->fetch($id)` ） もし `$data` が存在すれば、単純にそれを返します。追加の作業は不要です。

しかし、 cache fetch が null を返したら、キャッシュが無いことを意味し、何か _作業_ が必要です。データ（ `$data = $this->gatherData()` ）を取得し、次回のためにデータを保存するだけです（ `$cache->save($hash, $data)` ）。

<h2 id="learning-by-example">具体例に学ぶ</h2>

現在利用可能なプラグインは豊富にあり、それらのソースコードにあなたの疑問の答えが見つかる可能性があります。
ただ問題は、どのプラグインを見れば良いかです。
このページでは、一般的なプラグインの問題を一覧化し、それに対処する方法を明らかにする特定のプラグインを一覧にします。

先へ進む前に、 [コアのドキュメント](../../04.plugins/) を学び、特に [Grav のライフサイクル](../../04.plugins/05.grav-lifecycle/) を知っておいてください！

<h3 id="how-do-i-read-from-and-write-data-to-the-file-syst">ファイルシステムへの読み書きはどうしたら良い？</h3>

Grav はフラットファイルかもしれませんが、しかしフラットファイルだから静的とは限りません！ ファイルシステムへの読み書き方法は、たくさんあります。

- YAML データにアクセスして読み込むだけなら、 [Import plugin](https://github.com/Deester4x4jr/grav-plugin-import) をチェックしてください。
- インターフェースを好む場合は、組み込みの [RocketTheme\Toolbox\File](https://learn.getgrav.org/api#class-RocketThemeToolboxFile) インターフェースが使えます。
- あるいは、 [SQLite](https://sqlite.org/) の使用を止めるものはありません。
- もっとも単純な具体例は、おそらく [Comments](https://github.com/getgrav/grav-plugin-comments) です。
- 他には：
  - [Table Importer](https://github.com/Perlkonig/grav-plugin-table-importer)
  - [Thumb Ratings](https://github.com/iusvar/grav-plugin-thumb-ratings)
  - [Webmention](https://github.com/Perlkonig/grav-plugin-webmention)

<h3 id="how-do-i-make-data-from-a-plugin-available-to-twig">どうすれば Twig で使えるデータをプラグインから作成できる？</h3>

ひとつの方法として、 `config.plugins.X` 名前空間による方法があります。以下の例に見られるように、シンプルに `$this->config->set()` するだけです：

- [ipLocate](https://github.com/Perlkonig/grav-plugin-iplocate/blob/master/iplocate.php#L82)
- [Count Views](https://github.com/Perlkonig/grav-plugin-count-views/blob/master/count-views.php#L88)

その後、 Twig テンプレートでアクセスするには、 `{{ config.plugins.X.whatever.variable }}` を使います。

もしくは、変数を `grav['twig']` により渡すことも可能です：

- [Blogroll](https://github.com/Perlkonig/grav-plugin-blogroll/blob/master/blogroll.php#L43), which you can then access directly [in your template](https://github.com/Perlkonig/grav-plugin-blogroll/blob/master/templates/partials/blogroll.html.twig#L32).

最後の方法として、データを直接ページのフロントマターに挿入することもできます。 [the Import plugin](https://github.com/Deester4x4jr/grav-plugin-import) に見られます。

<h3 id="how-do-i-inject-markdown-into-a-page">ページにマークダウンを注入する方法は？</h3>

[Grav ライフサイクル](../../04.plugins/05.grav-lifecycle/) によると、生のマークダウンを注入する最後のイベントフックは、 `onPageContentRaw` です。最初のものは、おそらく `onPageInitialized` です。
`$this->grav['page']->rawMarkdown()` で取得し、それをいじって、それから `$this->grav['page']->setRawContent()` で書き戻すことができます。
以下のプラグインで、これを使っています：

- [Page Inject](https://github.com/getgrav/grav-plugin-page-inject)
- [Table Importer](https://github.com/Perlkonig/grav-plugin-table-importer)

<h3 id="how-do-i-inject-html-into-the-final-output">最終出力に、 HTML を注入する方法は？</h3>

HTML を注入でき、しかもキャッシュ出力もできる最後のイベントは、 `onOutputGenerated` イベントです。 `$this->grav->output` を取得して修正するだけでできます。

- 一般的なタスクの多くは、 [Shortcode Core](https://github.com/getgrav/grav-plugin-shortcode-core) を使うことでできます。
- [Pubmed](https://github.com/Perlkonig/grav-plugin-pubmed) プラグインと [Tablesorter](https://github.com/Perlkonig/grav-plugin-tablesorter) プラグインでは、よりブルートフォースな方法を取っています。

<h3 id="how-do-i-inject-assets-like-javascript-and-css-fil">JavaScript や CSS ファイルのようなアセットを注入する方法は？</h3>

これは、 [Grav\Common\Assets](https://learn.getgrav.org/api#class-gravcommonassets) インターフェースによってできます。

- [Google Analytics](https://github.com/escopecz/grav-ganalytics)
- [Bootstrapper](https://github.com/getgrav/grav-plugin-bootstrapper)
- [Gravstrap](https://github.com/giansi/gravstrap)
- [Tablesorter](https://github.com/Perlkonig/grav-plugin-tablesorter)

<h3 id="how-do-i-affect-the-response-headers-and-response-">レスポンスヘッダーやレスポンスコードを編集する方法は？</h3>

PHP の `header()` コマンドを使って、レスポンスヘッダーを設定できます。最後にそれができるイベントは、 `onOutputGenerated` イベントです。その後に、実際に出力がクライアントに送信されます。
レスポンスコード自体は、ページの YAML フロントマターで設定できるだけです（ `http_response_code` ）。

- [Graveyard](https://github.com/Perlkonig/grav-plugin-graveyard) プラグインは、 YAML フロントマターにより `404 NOT FOUND` を `410 GONE` に置き換えてレスポンスします。
- [Webmention](https://github.com/Perlkonig/grav-plugin-webmention) プラグインは、 `201 CREATED` レスポンス時に、`Location` ヘッダーを設定します。

<h3 id="how-do-i-incorporate-third-party-libraries-into-my">サードパーティー製ライブラリをプラグインに組み込む方法は？</h3>

通常、他のライブラリは、 `vendor` サブフォルダに組み込み、 プラグインの適切なところでその `autoload.php` を `require` します（ Git を使っているなら、 [subtrees](https://help.github.com/articles/about-git-subtree-merges/) を検討してください）。

- [Shortcode Core](https://github.com/getgrav/grav-plugin-shortcode-core)
- [Table Importer](https://github.com/Perlkonig/grav-plugin-table-importer)

<h3 id="how-do-i-extend-twig">Twig を拡張する方法は？</h3>

最も簡単な方法は、 **Twig レシピ** セクションにある  [Custom Twig Filter/Function](../../10.cookbook/02.twig-recipes/#custom-twig-filter-function) の具体例に従うことです。

また、 [この Twig ドキュメントを読んでください](https://twig.symfony.com/) 。そして extension を開発してください。それから、[TwigPCRE](https://github.com/kesslernetworks/grav-plugin-twigpcre) プラグインを見て Grav への組み込み方を学んでください。

<h3 id="how-do-i-interact-with-external-apis">外部 API とやりとりする方法は？</h3>

Grav では、 [Grav\Common\GPM\Response](https://learn.getgrav.org/api#class-grav-common-gpm-response) オブジェクトを提供していますが、直接やりとりしたい場合は、それを阻害するものはありません。

- [ipLocate](https://github.com/Perlkonig/grav-plugin-iplocate)
- [Pubmed](https://github.com/Perlkonig/grav-plugin-pubmed)

