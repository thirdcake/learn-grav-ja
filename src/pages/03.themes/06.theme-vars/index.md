---
title: テーマ変数
layout: ../../../layouts/Default.astro
lastmod: '2025-04-19'
---
テーマをデザインするとき、Twigのテンプレート内のすべての種類のオブジェクトや変数が使えます。Twigテンプレートエンジンは、これらオブジェクトや変数を強力に読み取り、計算します。このことは、[Twigドキュメントに書かれています](https://twig.symfony.com/doc/1.x/templates.html) し、[私たちのドキュメントでも概要を説明しました](../03.twig-primer/)

> [!Warning]  
> Twigでは、引数の必要がない場合、カッコ `()` を除いても、メソッド名のみでそのメソッドを呼び出せます。引数を渡す必要があるときは、メソッド名の後にカッコが必要です。`page.content` は、`page.content()` と同じです。

<h2 id="core-objects">コア・オブジェクト</h2>

Twigテンプレートで使える、いくつかの **コア・オブジェクト** があります。それぞれのオブジェクトは、 **変数** や **関数** を持ちます。

<h3 id="base-dir-variable">`base_dir` 変数</h3>

Gravがインストールされているベースのディレクトリを返します。

<h3 id="base-url-variable">`base_url` 変数</h3>

GravサイトのベースのURLを返します。絶対URLを返すかどうかは、 [system.yamlの設定](../../01.basics/05.grav-configuration/#system-configuration) で、`absolute_urls` をどのように設定しているかによります。

<h3 id="base-url-relative-variable">`base_url_relative` 変数</h3>

GravサイトのベースURLを、ホスト情報無しで返します。

<h3 id="base-url-absolute-variable">`base_url_absolute` 変数</h3>

GravサイトのベースURLを、ホスト情報を含んで返します。

<h3 id="base-url-simple-variable">`base_url_simple` 変数</h3>

GravサイトのベースURLを、言語コード無しで返します。

<h3 id="home-url-variable">`home_url` 変数</h3>

サイト上で、ホームに戻るリンクを使うときに便利です。[`base_url`](#base-url-variable) に似ていますが、こちらは、その時アクティブになっている言語を考慮に入れたURLが返ります。

<h3 id="html-lang-variable">`html_lang` 変数</h3>

その時点でアクティブになっている言語が返ります。もし無ければ、`site.default_lang` の設定値が返ります。それもなければ、`en` を返します。

<h3 id="theme-dir-variable">`theme_dir` 変数</h3>

現在有効化されているテーマのディレクトリを返します。

<h3 id="theme-url-variable">`theme_url` 変数</h3>

現在有効化されているテーマの相対URLを返します。

> [!Info]  
> 画像や、JavaScript、CSSファイルにリンクしたい時、おすすめの方法は、`url()` 関数を `theme://` ストリームと組み合わせて使うことです。[カスタム関数](../04.twig-tags-filters-functions/03.functions/#url) で解説しています。JavaScriptとCSSについては、[アセット管理](../07.asset-manager/) の方が、かんたんに使えます。ただし、動的に（もしくは条件付きで）読み込みたいような場合は、機能しないこともあります。

<h3 id="language-codes-variable">`language_codes` 変数</h3>

サイトで利用できる言語コードのリストを返します。

<h3 id="assets-object">`assets` オブジェクト</h3>

**アセット管理** は、あなたのサイトでCSSやJavaScriptを管理するかんたんな方法です。

```twig
{% do assets.addCss('theme://css/foo.css') %}
{% do assets.addInlineCss('a { color: red; }') %}
{% do assets.addJs('theme://js/something.js') %}
{% do assets.addInlineJs('alert("Warming!");') %}
```

くわしくは、 [アセット管理](../07.asset-manager/) をお読みください。

> [!Note]  
> **TIP:** 代わりに、**[stylesタグ](../04.twig-tags-filters-functions/01.tags/#style)** や、**[scriptタグ](../04.twig-tags-filters-functions/01.tags/#script)** の使用をおすすめします。

<h3 id="config-object">`config` オブジェクト</h3>

これを使えば、`/user/config` ディレクトリ内のYAMLファイルに設定されているすべての設定にアクセス可能です。

```twig
{{ config.system.pages.theme }}{# returns the currently configured theme #}
```

<h3 id="site-object">`site` オブジェクト</h3>

`config.site` オブジェクトの別名です。`site.yaml` ファイル内に設定した内容にアクセスできます。

<h3 id="system-object">`system` オブジェクト</h3>

`config.system` オブジェクトの別名です。`system.yaml` ファイル内に設定した内容にアクセスできます。

<h3 id="theme-object">`theme` オブジェクト</h3>

`config.theme` オブジェクトの別名です。現在有効になっているテーマで設定した内容にアクセスできます。プラグインに設定したものは、`config.plugins` から取得できます。

<h3 id="page-object">`page` オブジェクト</h3>

Gravでは、`pages/` フォルダ内のフォルダ構造を使うので、それぞれのページは、**pageオブジェクト** として利用できます。

**page オブジェクト** は、おそらく _一番_ 重要なオブジェクトで、現在のページのすべての情報を持っています。

> [!Info]  
> The whole list of the Page object methods is available on the [API site](https://learn.getgrav.org/api#class-gravcommonpagepage). Here's a list of the methods you'll find most useful.

##### summary([size])

コンテンツの概要を返します。`size` を引数に渡すと、それを最大文字数とする概要になります。代わりに、何も引数を渡さないときは、 `site.yaml` 設定の `summary.size` 変数が適用されます。

```twig
{{ page.summary|raw }}
```

もしくは

```twig
{{ page.summary(50)|raw }}
```

3つ目のオプションは、コンテンツ中を `===` で区切ることです。この区切り文字の前にあるものが、概要として使われます。

##### content()

ページのHTMLコンテンツ全体を返します。

```twig
{{ page.content|raw }}
```

##### header()

ページのフロントマターに定義したものが返ります。たとえば、以下のようなフロントマターを書いたとします。

```yaml
title: My Page
author: Joe Bloggs
```

次のように使えます：

```twig
The author of this page is: {{ page.header.author|e }}
```

##### media()

ページに関連するすべてのメディアを含む **Media** オブジェクトを返します。これらには、**画像** や、 **動画** や、 その他の **ファイル** が、含まれます。[メディアのドキュメント](../../02.content/07.media/) で解説したように、メディアにアクセス可能です。配列としてふるまうので、Twigのフィルタや関数が使えます。注意点：SVG画像は、ファイルとして扱われます。画像ではありません。Twigの画像フィルタで計算できないためです。

特定のファイルや画像を取得します：

```twig
{% set my_pdf = page.media['myfile.pdf'] %}
```

最初の画像を取得します：

```twig
{% set first_image = page.media.images|first %}
```

すべての画像をループし、HTMLタグで表示します：

```twig
{% for image in page.media.images %}
   {{ image.html|raw }}
{% endfor %}
```

##### title()

ページのタイトルを返します。ページのフロントマターで、`title` 変数として設定したものです。

```yaml
title: My Page
```

##### menu()

ページのフロントマターで、`menu` 変数として設定した値が返ります。もしなければ、デフォルトでは `title` が返ります。

```yaml
title: My Page
menu: my-page
```

##### visible()

ページが公開かどうかを返します。デフォルトでは、数字とピリオドが最初にあるページ（`01.somefolder1`）は公開され、無いページ（`subfolder2`）は公開とは認識されません。この設定は、ページのフロントマターで上書きできます。

```yaml
title: My Page
visible: true
```

##### routable()

Gravが、そのページをルーティング対象とするかどうかを返します。つまり、ブラウザから呼ばれて、そのコンテンツを表示するかどうかです。ルーティング外のページは、テンプレートや、プラグインなどに使われますが、直接は表示されません。これは、ページのフロントマターで設定できます：

```yaml
title: My Page
routable: true
```

##### slug()

そのページのURLに表示される名前を返します。たとえば、`my-blog-post` などです。

##### url([include_host = false])

そのページのURLを返します。たとえば：

```twig
{{ page.url|e }} {# could return /my-section/my-category/my-blog-post #}
```

もしくは、

```twig
{{ page.url(true)|e }} {# could return http://mysite.com/my-section/my-category/my-blog-post #}
```

##### permalink()

ホスト情報を含んだURLを返します。どこからでもアクセス可能なリンクが必要なときに、とくに便利です。

##### canonical()

そのページの '望ましい' バージョンもしくはリンクのURLを返します。この値は、ページのフロントマターで `canonical` で上書きしていなければ、通常のURLです。

##### route()

This returns the internal routing for a page.  This is primarily used for internal routing and dispatching of pages.

##### home()

そのページが、**ホーム** かどうかを返します。`system.yaml` ファイルで、ホームとなるページを設定できます。

##### root()

そのページが、ツリー階層のルート（root）ページかどうかを返します。

##### active()

そのページが、ブラウザでアクセスしているページと同じかどうかを返します。ナビゲーションで、そのページがアクティブかどうかを知りたいときに、特に便利です。

##### modular()

そのページが、モジュラーページかどうかを返します。

##### activeChild()

そのURIのURLに、アクティブページのURLを含んでいるかどうかを返します。別の言い方をすると、このページのURLに、現在ページのURLが含まれているかどうかです。これもまた、ナビゲーションで、そのページがアクティブな子ページの親ページかどうかを知りたいときに便利です。

##### find(url)

そのURLのページオブジェクトを返します。

```twig
{% include 'modular/author-detail.html.twig' with {'page': page.find('/authors/billy-bloggs')} %}
```

##### collection()

[ページのフロントマターのコレクション定義](../../02.content/03.collections/) で設定したページのコレクションを返します。

```twig
{% for child in page.collection %}
    {% include 'partials/blog_item.html.twig' with {'page':child, 'truncate':true} %}
{% endfor %}
```

##### currentPosition()

兄弟ページのあいだで、そのページのインデックス（何番目か）を返します。

##### isFirst()

そのページが、兄弟ページの中で一番最初のページかどうかを返します。

##### isLast()

そのページが、兄弟ページの中で一番最後のページかどうかを返します。

##### nextSibling()

現在の場所に対して、次の兄弟のページを返します。

##### prevSibling()

現在の場所に対して、前の兄弟のページを返します。

> [!Info]  
> nextSibling() と、prevSibling() は、スタック（後入れ先出し）形式でページを並べます。これは、ブログなどで最も機能するもので、一番始めに並ぶブログ投稿について、nextSibling は null で、prevSiblig は過去のブログ投稿となります。もしこの順番付けが難しいと感じるなら、 nextSibling の代わりに、 page.adjacentSibling(-1) を使ってください。また、テーマ内で使う定数を決めることもできます。`page.adjacentSibling(NEXT_PAGE)` のようにすれば、より読みやすくなります。

##### children()

ページのディレクトリ構造で決まる子ページの配列を返します。

##### orderBy()

そのページの子ページの並べ方を返します。値は、 `default`, `title`, `date` そして `folder` のいずれかです。この値は、一般的にページのフロントマターで設定されます。

##### orderDir()

そのページの子ページの並び順の方向を返します。値は、昇順ならば `asc` で、降順なら `desc` です。この値は、一般的にページのフロントマターで設定されます。

##### orderManual()

マニュアルに並べた順序で子ページの配列を返します。この値は、一般的にページのフロントマターで設定されます。

##### maxCount()

コレクションとして返せる子ページの数の最大数を返します。この値は、一般的にページのフロントマターで設定されます。

##### children.count()

そのページの子ページの数を返します。

##### children.current()

現在の子ページを返します。子ページの繰り返しの中で使えます。

##### children.next()

子ページの配列で、次の子ページを返します。

##### children.prev()

子ページの配列で、前の子ページを返します。

##### children.nth(position)

子ページの配列の中から、1つの `position` を持つ子ページを返します。この `position` は、 `0` から、 `children.count() - 1` までの間の整数値です。

##### children.sort(orderBy, orderDir)


**orderBy** (`default`, `title`, `date` そして `folder`) と、 **orderDir** (`asc` もしくは `desc`) によって、子ページを並べかえます。

##### parent()

そのページの親ページのオブジェクトを返します。木構造になったページで、上にナビゲーションで移動したいときにとても便利です。


##### isPage()

そのページが、ただルーティングのためのフォルダではなく、 `.md` ファイルを持ったページであるかどうかを返します。

##### isDir()

そのページが、ルーティングのためだけのフォルダーかどうかを返します。

##### id()

そのページのユニークな識別子を返します。

##### modified()

そのページが更新されたタイムスタンプを返します。

##### date()

ページのタイムスタンプを返します。これは一般的に、ページや投稿を表すためにフロントマターに設定された値です。明示的に定義されていない場合は、ファイルの更新日のタイムスタンプが使われます。

##### template()

`.md` 拡張子を除いた、そのページのテンプレート名が返ります。たとえば： `default`

##### filePath()

ページのフルファイルパスが返ります。たとえば、 `/Users/yourname/sites/grav/user/pages/01.home/default.md`

##### filePathClean()

Gravのルートディレクトリからの相対パスが返ります。たとえば、 `user/pages/01.home/default.md`

##### path()

そのページを持つディレクトリのフルパスが返ります。たとえば、 `/Users/yourname/sites/grav/user/pages/01.home`

##### folder()

ページのフォルダ名が返ります。たとえば、 `01.home`

##### taxonomy()

ページに関係するタクソノミーの配列を返します。繰り返し可能です。タグを表示するときに特に便利です。

```twig
{% for tag in page.taxonomy.tag %}
    <a href="search/tag:{{ tag }}">{{ tag }}</a>
{% endfor %}
```

<h3 id="pages-object">`pages` オブジェクト</h3>

**pages** オブジェクトは、Gravが確認できるすべての **page** オブジェクトのツリー構造を表したルートページです。サイトマップや、ナビゲーション、特定の **page** を探すようなときに、特に便利です。

> [!Info]  
> このオブジェクトは、`Pages` クラスのインスタンスである `grav.pages` とは違うものです。

<h5 id="children-method">children メソッド</h5>

**page objects** の配列から、直接の子ページを返します。pages オブジェクトは、すべての木構造を表すので、再帰的にすべての pages/ フォルダ内のページを取得可能です。

シンプルなメニューを作るため、トップレベルのページを取得してみます：

```twig
<ul class="navigation">
    {% for page in pages.children %}
        {% if page.visible %}
            <li><a href="{{ page.url }}">{{ page.menu }}</a></li>
        {% endif %}
    {% endfor %}
</ul>
```

<h3 id="media-object">`media` オブジェクト</h3>

ページオブジェクトの外にある[メディア](../../02.content/07.media/) に、TwigからPHPストリームを使ってアクセスできるオブジェクトです。これは、[画像リンク](../../02.content/06.image-linking/#php-stream) と似たような方法で機能します。ストリームを使って画像やメディアにアクセスし、テーマを操作します。

```twig
{{ media['user://media/bird.png'].resize(50, 50).rotate(90).html()|raw }}
```

<h3 id="uri-object">`uri` オブジェクト</h3>

> [!Info]  
> Uriオブジェクトのすべてのメソッドは [API site](https://learn.getgrav.org/api#class-gravcommonuri) を参照してください。ここでは、特に便利なものを列挙します。

Uriオブジェクトには、現在のURIの部分にアクセスするメソッドがあります。フルのURIは、 `http://mysite.com/grav/section/category/page.json/param1:foo/param2:bar/?query1=baz&query2=qux` とします:

##### path()

URLのパス部分を返します： (例 `uri.path` = `/section/category/page`)

##### paths()

パスの要素の配列を返します： (例 `uri.paths` = `[section, category, page]`)

##### route([absolute = false][, domain = false])

絶対URLもしくは相対URLのルーティングを返します。  (例 `uri.route(true)` = `http://mysite.com/grav/section/category/page` or `uri.route()` = `/section/category/page`)

##### params()

This returns the params portion of the URL: (e.g. `uri.params` = `/param1:foo/param2:bar`)

##### param(id)

This returns the value of a particular param.  (e.g. `uri.param('param1')` = `foo`)

##### query()

This returns the query portion of the URL: (e.g. `uri.query` = `query1=bar&query2=qux`)

##### query(id)

You can also retrieve specific query items: (e.g. `uri.query('query1')` = `bar`)

##### url([include_host = true])

This returns the full URL with or without the host.  (e.g. `uri.url(false)` = `grav/section/category/page/param:foo?query=bar`)

##### extension()

This returns the extension, or will return `html` if not provided: (e.g. `uri.extension` = `json`)

##### host()

This returns the host portion of the URL. (e.g. `uri.host` = `mysite.com`)

##### base()

This returns the base portion of the URL. (e.g. `uri.base` = `http://mysite.com`)

##### rootUrl([include_host = false])

This returns the root url to the grav instance.  (e.g. `uri.rootUrl()` = `http://mysite.com/grav`)

##### referrer()

This returns the referrer information for this page.

<h3 id="header-object">`header` オブジェクト</h3>

The header object is an alias for `page.header()` of the original page.  It's a convenient way to access the original page headers when you are looping through other `page` objects of child pages or collections.

<h3 id="content-string">`content` 文字列</h3>

content オブジェクトは、オリジナルページの `page.content()` の別名（エイリアス）です。

ページのコンテンツを表示するには、次のようにします：

```twig
{{ content|raw }}
```

<h3 id="taxonomy-object">`taxonomy` オブジェクト</h3>

グローバルのタクソノミーオブジェクトは、サイトのタクソノミー情報をすべて持っています。より詳しくは、[タクソノミー](../../02.content/08.taxonomy/) をご覧ください。

<h3 id="browser-object">`browser` オブジェクト</h3>

> [!Info]  
> ブラウザオブジェクトのすべてのメソッドは、 [API site](https://learn.getgrav.org/api#class-grav-common-browser) をご覧ください。ここでは、最も便利なメソッドを列挙します。

Gravは組み込みで、ユーザーのプラットフォームや、ブラウザ、バージョンを調べるようにプログラムされています。

```twig
{{ browser.platform|e }}   # macintosh
{{ browser.browser|e }}    # chrome
{{ browser.version|e }}    # 41
```

<h3 id="user-object">`user` オブジェクト</h3>

You can access the current logged in user object indirectly via the Grav object.  This allows you to access such data as `username`, `fullname`, `title`, and `email`:

```twig
{{ grav.user.username|e }}  # admin
{{ grav.user.fullname|e }}  # Billy Bloggs
{{ grav.user.title|e }}     # Administrator
{{ grav.user.email|e }}     # billy@bloggs.com
```

<h2 id="adding-custom-variables">カスタム変数を追加</h2>

カスタム変数を追加するのは、さまざまな方法で、かんたんにできます。サイト全体で使いたい変数なら、`user/config/site.yaml` ファイルに追加できます。そして、以下のようにアクセスできます。

```twig
{{ site.my_variable|e }}
```

一方、特定のページでのみ必要な変数であれば、ページのYAMLフロントマターで変数を追加できます。 `page.header` オブジェクトでアクセスできます。たとえば：

```twig
title: My Page
author: Joe Bloggs
```

上記のように定義したとき、以下のように使えます：

```twig
The author of this page is: {{ page.header.author|e }}
```

<h2 id="adding-custom-objects">カスタムオブジェクトを追加</h2>

Twigオブジェクトにカスタムオブジェクトを追加するのは、発展的な方法で、プラグインを使います。これは発展的なトピックで、より詳しい内容は、[プラグインの章](../../04.plugins/04.event-fooks/) で解説します。

