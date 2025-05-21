---
title: ページ・コレクション
layout: ../../../layouts/Default.astro
lastmod: '2025-04-13'
---
Gravで最も一般的なコレクションは、ページを集めたリストです。それらはページのフロントマターか、もしくはtwigで定義されるもので、ページのフロントマターで定義される方がより一般的です。コレクションを定義することで、Twigで好きなように使えます。ページコレクションメソッドを使用したり、各[ページのオブジェクト](../../03.themes/06.theme-vars/#page-object)をループで繰り返しながら、ページメソッドやプロパティを使用することで、パワフルな使い方ができます。よくある例としては、ブログ記事のリストを表示したり、複雑なページデザインをレンダリングするためにモジュラーのサブページを表示したりすることができます。

<h2 id="collection-object">コレクション・オブジェクト</h2>

ページのフロントマターでコレクションを定義すると、Twigで利用できる [Grav コレクション](https://github.com/getgrav/grav/blob/develop/system/src/Grav/Common/Page/Collection.php) が作られます。コレクション・オブジェクトは、 **iterable（反復可能）** であり、**array（配列）** のように扱えます。たとえば、次のように：

```twig
{{ dump(page.collection[page.path]) }}
```

<h2 id="example-collection-definition">コレクションの定義例</h2>

ページフロントマターで定義されたコレクションの例：

```yaml
content:
    items: '@self.children'
    order:
        by: date
        dir: desc
    limit: 10
    pagination: true
```

`content.items` の値から、Gravはアイテムを収集します。ここに定義される情報から、コレクションをどのように構成するかが決まります。

上記の定義の場合、すべての **子ページ** を持ち、**date（日付）** の **desc（降順）** で、**10** ページごとの **pagenation（ページ割り）をする** コレクションを作ります。

ページネーションのリンクは、[Pagination plugin](https://github.com/getgrav/grav-plugin-pagination) がインストールされ、有効化されているときのみ利用できます。

<h2 id="accessing-collections-in-twig">Twigでコレクションにアクセス</h2>

フロントマターでコレクションが定義されると、twigのテンプレートで利用できる **page.collection** というコレクションが作られます：

```twig
{% for p in page.collection %}
<h2>{{ p.title|e }}</h2>
{{ p.summary|raw }}
{% endfor %}
```

上記の例は、コレクション内の [ページ](../../03.themes/06.theme-vars/#page-object) について、それぞれのタイトル（title）と要約（summary）を表示していく単純なループです。

また、デフォルトの表示順から変えて、順序のパラメータを含めることもできます。

```twig
{% for p in page.collection.order('folder','asc') %}
<h2>{{ p.title|e }}</h2>
{{ p.summary|raw }}
{% endfor %}
```

<h2 id="collection-headers">コレクションヘッダ</h2>

Gravに、特定のページはリストページで、子ページを持っていることを知らせるため、以下のような、たくさんの変数が用意されています：

<h3 id="summary-of-collection-options">コレクションの設定値の概要</h3>

| 文字列 | 結果 |
|-----------------------------------------------|-----------------------------------------------------------|
| `'@root.pages'`                               | Get the top level pages                                   |
| `'@root.descendants'`                         | Get all the pages of the site                             |
| `'@root.all'`                                 | Get all the pages and modules of the site                 |
|                                               |                                                           |
| `'@self.page'`                                | Get a collection with only the current page               |
| `'@self.parent'`                              | Get a collection with only the parent of the current page |
| `'@self.siblings'`                            | Get siblings of the current page                          |
| `'@self.children'`                            | Get children of the current page                          |
| `'@self.modules'`                             | Get modules of the current page                           |
| `'@self.all'`                                 | Get both children and modules of the current page         |
| `'@self.descendants'`                         | Recurse through all the children of the current page      |
|                                               |                                                           |
| `'@page.page': '/fruit'`                      | Get a collection with only the page `/fruit`              |
| `'@page.parent': '/fruit'`                    | Get a collection with only the parent of the page `/fruit`|
| `'@page.siblings': '/fruit'`                  | Get siblings of the page `/fruit`                         |
| `'@page.children': '/fruit'`                  | Get children of the page `/fruit`                         |
| `'@page.modules': '/fruit'`                   | Get modules of the page `/fruit`                          |
| `'@page.all': '/fruit'`                       | Get both children and modules of the page `/fruit`        |
| `'@page.descendants': '/fruit'`               | Get and recurse through all the children of page `/fruit` |
|                                               |                                                           |
| `'@taxonomy.tag': photography`                | taxonomy with tag=`photography`                           |
| `'@taxonomy': {tag: birds, category: blog}`   | taxonomy with tag=`birds` && category=`blog`              |

> [!Note]  
> このドキュメントでは、`@page`や、`@taxonomy.category` などを使いますが、よりYAML安全な書き方は、`page@`や、`taxonomy@.category` です。すべての`@` コマンドは、前か後に置く必要があります。

> [!Info]  
> コレクションの設定は、**Grav 1.6** から進化し、変わっています。古いバージョンもまだ動くかもしれませんが、非推奨です。

より詳しく見ていきましょう。

<h2 id="root-collections">Root コレクション</h2>

<h5 id="atroot-pages-top-level-pages">@root.pages トップレベルのページ</h5>

これは、サイトのトップ（root）レベルの **公開ページ** を取得します。特に、主要な（グローバル）ナビゲーションを作成するようなときに便利です：

```yaml
content:
    items: '@root.pages'
```

エイリアス（別名）で、`@root.children` も有効です。`@root` の使用は、将来的に意味が変わる可能性があり、非推奨となりました。

<h5 id="atroot-descendants-all-the-pages">@root.descendants すべてのページ</h5>

これは、すべてのページを取得します。再帰的に、トップページ以下、すべてのサブページ、さらにそのサブページへたどり、サイト上の **すべての** **公開ページ** のコレクションを作ります：

```yaml
content:
    items: '@root.descendants'
```

<h5 id="atroot-all-all-the-pages-and-modules">@root.all すべてのページとモジュール</h5>
##### @root.all - All the pages and modules

これもまた、上と同じ動きをしますが、サイト上の **すべての** **公開ページ 及び モジュール** を含みます。

```yaml
content:
    items: '@root.all'
```

<h2 id="self-collections">Self コレクション</h2>

##### @self.page - Current page only
<h5 id="atself-page-current-page-only">@self.page 現在のページのみ</h5>

これは、現在のページのみを持つコレクションを返します。

```yaml
content:
    items: '@self.page'
```

別名の`@self.self` もまた、有効です。

> [!Note]  
> そのページが公開されていない場合、空のコレクションとなります。

<h5 id="atself-parent-the-parent-page-of-the-current-page">@self.parent 現在のページの親ページ</h5>

これは、特別なケースのコレクションです。常に、現在ページの **親ページ** のみを返すからです。

```yaml
content:
    items: '@self.parent'
```

> [!Note]  
> そのページがトップレベルにあるときは、空のコレクションとなります。

<h5 id="atself-siblings-siblings-of-the-current-page">@self.siblings 現在ページの兄弟ページ</h5>

このコレクションは、現在ページと同じレベルで、現在ページ以外のすべての **公開ページ** のコレクションです。

```yaml
content:
    items: '@self.siblings'
```

<h5 id="atself-children-children-of-the-current-page">@self.children 現在ページの子ページ</h5>

これは、現在ページの **公開された子ページ** のリストに使います。


```yaml
content:
    items: '@self.children'
```

別名で、`@self.pages` も有効です。`@self` を使うことは、将来的に意味が変わりうるため、非推奨となりました。

<h5 id="atself-modules-modules-of-the-current-page">@self.modules 現在ページのモジュール</h5>

このメソッドは、現在ページの **公開されたモジュール** のみを取得します。（たとえば、`_features`や、`_showcase` など）

```yaml
content:
    items: '@self.modules'
```

別名の `@self.modular` は非推奨です。

<h5 id="atself-all-children-and-modules-of-the-current-pag">@self.all 現在ページの子ページ及びモジュール</h5>

このメソッドは、現在ページの **公開された子ページ及びモジュール** のみを取得します。

```yaml
content:
    items: '@self.all'
```

<h5 id="atself-descendants-children-all-descendants-of-the">@self.descendants 現在ページの子ページ及び、すべての子孫ページ</h5>

`.children` に似て、`.descendants` コレクションは、**公開された子ページ** だけでなく、さらにそれぞれの子ページと続けて再帰的に取得します。

```yaml
content:
    items: '@self.descendants'
```

<h2 id="page-collections">ページコレクション</h2>

<h5 id="atpage-page-collection-of-just-the-specific-page">@page.page ある特定のページのみのコレクション</h5>

このコレクションは、スラッグを使って、そのページのコレクションを返します。（ただし、**公開ページ** である場合）

```yaml
content:
    items:
      '@page.page': '/blog'
```

別名の`'@page.self': '/blog'` も有効です。

> [!Note]  
> そのページが公開されていない場合、空のコレクションとなります。

<h5 id="atpage-parent-the-parent-page-of-a-specific-page">@page.parent そのページの親ページ</h5>

これは、特殊ケースのコレクションです。その特定のページの **親ページ** のみを返すコレクションだからです。

```yaml
content:
    items:
      '@page.parent': '/blog'
```

> [!Note]  
> そのページがトップレベルだった場合、空のコレクションとなります。

<h5 id="atpage-siblings-siblings-of-a-specific-page">@page.siblings そのページの兄弟ページ</h5>

このコレクションは、そのページ自身を除く、同じレベルのすべての **公開ページ** を集めます。

```yaml
content:
    items:
        '@page.siblings': '/blog'
```

<h5 id="atpage-children-children-of-a-specific-page">@page.children そのページの子ページ</h5>

このコレクションは、スラッグを使って指定したページのすべての **公開されている子ページ** を返します。

```yaml
content:
    items:
      '@page.children': '/blog'
```

別名の`'@page.pages': '/blog'` も、有効です。`'@page': '/blog'` は、将来的に意味が変わる可能性があり、非推奨となりました。

<h5 id="atpage-modules-modules-of-a-specific-page">@page.modules そのページのモジュール</h5>

このコレクションは、スラッグにより指定したページが持つすべての **公開されたモジュール** を返します。

```yaml
content:
    items:
      '@page.modules': '/blog'
```

別名の `'@page.modular': '/blog'` は非推奨です。

<h5 id="atpage-all-children-and-modules-of-a-specific-page">@page.all そのページの子ページ及びモジュール</h5>

このメソッドは、そのページの **公開された子ページ及びモジュール** のみを取得します。

```yaml
content:
    items:
      '@page.all': '/blog'
```

<h5 id="atpage-descendants-collection-of-children-all-desc">@page.descendants そのページの子ページ及びすべての子孫ページのコレクション</h5>

このコレクションは、スラッグで指定したページの **公開された子ページ** 及びすべてのそれらの子孫ページを返します。

```yaml
content:
    items:
      '@page.descendants': '/blog'
```


<h2 id="taxonomy-collections">タクソノミー・コレクション</h2>

```yaml
content:
   items:
      '@taxonomy.tag': foo
```

`@taxonomy` オプションを使うと、Gravの強力なタクソノミー機能を利用できます。ここで、[サイト設定](../../01.basics/05.grav-configuration/#site-configuration) での`@taxonomy` 設定が登場します。Gravが、適切にそのページ参照を解釈するためには、この設定ファイルで、タクソノミーが定義 **されていなければなりません** 。

`@taxonomy.tag: foo` という設定によって、Gravは、`/user/pages` フォルダ内にあり、そのタクソノミー変数が `tag: foo` となっているすべての **公開ページ** を探しにいけます。

```yaml
content:
    items:
       '@taxonomy.tag': [foo, bar]
```

`content.items` 変数は、複数のタクソノミーの配列を取得でき、すべてを満たすページを集めます。`foo` **及び** `bar` タグ **両方**を持つ公開ページのコレクションとなります。[タクソノミー](../08.taxonomy) の章では、より詳しくその概念を解説します。

> [!Info]  
> もし複数の変数を一行で取得したい場合は、サブの変数を `{}` 波カッコで区切らなければいけません。その後、それぞれの変数は、コンマで区切ります。たとえば：`'@taxonomy': {category: [blog, featured], tag: [foo, bar]}` この例では、`category` と `tag` というサブ変数が、`@taxonomy` の中にあります。それぞれは、`[]` 角カッコのリストを持ちます。これら **すべて** の条件を満たすページを探します。

もし、複数の変数で探すとき、一行で書くのではなく、より標準的な書き方を推奨します。次のように：

```yaml
content:
  items:
    '@taxonomy':
      category: [blog, featured]
      tag: [foo, bar]
```

ヒエラルキーのそれぞれの階層には、2つの空白が頭につけられています。YAMLは、いくつのスペースで階層分けをしてもよいのですが、2つというのが標準的なやり方です。上記の例では、`カテゴリー` と `タグ` 変数が、`@taxonomy` 変数の下に吊り下がっています。

ページコレクションは、タクソノミーが設定されると、（/archive/category:news のような）URLによって、自動的にフィルタリングされます。これにより、ひとつのブログを立ち上げるだけで、URLを使って動的にフィルタリングすることができます。もしタクソノミーを無視して欲しい場合は、`url_taxonomy_filters:false` というフラグを使うと、この機能は無効になります。

<h3 id="complex-collections">複雑なコレクション</h3>

複数の、複雑なコレクションを定義することもできます。結果的に、それらのコレクションの合計となるコレクションが得られます。たとえば：

```yaml
content:
  items:
    - '@self.children'
    - '@taxonomy':
         category: [blog, featured]
```

Additionally, you can filter the collection by using `filter: type: value`. The type can be any of the following: `published`, `visible`, `page`, `module`, `routable`. These correspond to the [Collection-specific methods](#collection-object-methods), and you can use several to filter your collection. They are all either `true` or `false`. Additionally, there is `type` which takes a single template-name, `types` which takes an array of template-names, and `access` which takes an array of access-levels. For example:

```yaml
 content:
  items: '@self.siblings'
  filter:
    visible: true
    type: 'blog'
    access: ['site.login']
```

The type can also be negative: `non-published`, `non-visible`, `non-page` (=module), `non-module` (=page) and `non-routable`, but it preferred if you use the positive version with the value `false`.

```yaml
 content:
  items: '@self.children'
  filter:
    published: false
```

!! Collection filters have been simplified since **Grav 1.6**. The old `modular` and `non-modular` variants will still work, but are not recommended to use. Use `module` and `page` instead.

<h2 id="ordering-options">表示順の設定</h2>

```yaml
content:
    order:
        by: date
        dir: desc
    limit: 5
    pagination: true
```

サブページの表示順は、フォルダの表示順のルールに従います。利用できる選択肢は、次の通りです：

| 順序     | 詳細                                                                                                                                            |
| :----------  | :----------                                                                                                                                        |
| `default`    | The order is based on the file system, i.e. `01.home` before `02.advark`                                                                              |
| `title`      | The order is based on the title as defined in each page                                                                                            |
| `basename`   | The order is based on the alphabetic folder name after it has been processed by the `basename()` PHP function                                      |
| `date`       | The order is based on the date as defined in each page                                                                                                |
| `modified`   | The order is based on the modified timestamp of the page                                                                                              |
| `folder`     | The order is based on the folder name with any numerical prefix, i.e. `01.`, removed                                                                  |
| `header.x`   | The order is based on any page header field. i.e. `header.taxonomy.year`. Also a default can be added via a pipe. i.e. `header.taxonomy.year|2015`    |
| `random`     | The order is randomized                                                                                                                            |
| `custom`     | The order is based on the `content.order.custom` variable                                                                                                                             |
| `manual`     | The order is based on the `order_manual` variable. **DEPRECATED**                                                                                                    |
| `sort_flags` | Allow to override sorting flags for page header-based or default ordering. If the `intl` PHP extension is loaded, only [these flags](https://secure.php.net/manual/en/collator.asort.php) are available. Otherwise, you can use the PHP [standard sorting flags](https://secure.php.net/manual/en/array.constants.php). |

`content.order.dir` 変数は、表示の方向を設定します。`desc` （降順）か `asc` （昇順） を設定してください。

```yaml
content:
    order:
        by: default
        custom:
            - _showcase
            - _highlights
            - _callout
            - _features
    limit: 5
    pagination: true
```

上記の設定では、`content.order.custom` 設定により、**カスタムで手動の順番** を定義できます。これにより、**showcase** が最初に、**highlights** が次に、といった順番が作れます。もしこのリストからページが漏れていた場合、Gravは、その漏れたページを `content.order.by` による順番で代替します。

ページにカスタムのスラッグがある場合、`content.order.custom` リストでは、スラッグを使わなければいけません。

`content.pagenation` は、trueかfalseの設定で、プラグインなどに **ページネーション** が初期化されるべきかどうかを知らせます。`content.limit` は、1ページごとにいくつのアイテムを表示させるかを示します。

<h3 id="date-range">日付の範囲</h3>

日付の範囲で、ページをフィルタリングすることもできます：

```yaml
content:
    items: '@self.children'
    dateRange:
        start: 1/1/2014
        end: 1/1/2015
```

[PHPのstrtotime関数](https://php.net/manual/en/function.strtotime.php) で使えるフォーマットであれば、どんな文字列でも使えます。たとえば、`-6 weeks` や、`last Monday` などの書式が、伝統的な日付である `01/23/2014` や `23 January 2014` と同じように使えます。content.dateRangeは、その範囲外の日付を持つページを省きます。**start** と **end** はどちらも、オプショナルです。しかしどちらか一方は、定義されていなければいけません。

<h3 id="multiple-collections">複数のコレクション</h3>

`content: items:` によってコレクションを作った場合、いくつかの条件のもと、1つのコレクションを作ったということです。しかし、Gravでは、ページごとに、任意の数のコレクションを作れます。ただ、次のものを作るだけで良いのです：

```yaml
content:
    items: '@self.children'
    order:
        by: date
        dir: desc
    limit: 10
    pagination: true

fruit:
    items:
       '@taxonomy.tag': [fruit]
```

上記の例では、**2つのコレクション** を作成しています。1つ目は、通常の `content` コレクションですが、2つ目は、タクソノミーベースのコレクションで、`fruit` と名付けられています。Twigから、これら2つのコレクションにアクセスするには、次のような構文を使います：

```yaml
{% set default_collection = page.collection %}
{% set fruit_collection = page.collection('fruit') %}
```

<h2 id="collection-object-methods">コレクションオブジェクトのメソッド</h2>

Iterable methods include:

| プロパティ | 説明 |
| -------- | ----------- |
| `Collection::append($items)` | Add another collection or array |
| `Collection::first()` | Get the first item in the collection |
| `Collection::last()` | Get the last item in the collection |
| `Collection::random($num)` | Pick `$num` random items from the collection |
| `Collection::reverse()` | Reverse the order of the collection |
| `Collection::shuffle()` | Randomize the entire collection |
| `Collection::slice($offset, $length)` | Slice the list |

Also has several useful Collection-specific methods:

| プロパティ | 説明 |
| -------- | ----------- |
| `Collection::addPage($page)` | You can append another page to this collection |
| `Collection::copy()` | Creates a copy of the current collection |
| `Collection::current()` | Gets the current item in the collection |
| `Collection::key()` | Returns the slug of the current item |
| `Collection::remove($path)` | Removes a specific page in the collection, or current if `$path = null` |
| `Collection::order($by, $dir, $manual)` | Orders the current collection |
| `Collection::intersect($collection2)` | Merge two collections, keeping items that occur in both collections (like an "AND" condition) |
| `Collection::merge($collection2)` | Merge two collections, keeping items that occur in either collection (like an "OR" condition) |
| `Collection::isFirst($path)` | Determines if the page identified by path is first |
| `Collection::isLast($path)` | Determines if the page identified by path is last |
| `Collection::prevSibling($path)` | Returns the previous sibling page if possible |
| `Collection::nextSibling($path)` | Returns the next sibling page if possible |
| `Collection::currentPosition($path)` | Returns the current index |
| `Collection::dateRange($startDate, $endDate, $field)` | Filters the current collection with dates |
| `Collection::visible()` | Filters the current collection to include only visible pages |
| `Collection::nonVisible()` | Filters the current collection to include only non-visible pages |
| `Collection::pages()` | Filters the current collection to include only pages (and not modules) |
| `Collection::modules()` | Filters the current collection to include only modules (and not pages) |
| `Collection::published()` | Filters the current collection to include only published pages |
| `Collection::nonPublished()` | Filters the current collection to include only non-published pages |
| `Collection::routable()` | Filters the current collection to include only routable pages |
| `Collection::nonRoutable()` | Filters the current collection to include only non-routable pages |
| `Collection::ofType($type)` | Filters the current collection to include only pages where template = `$type` |
| `Collection::ofOneOfTheseTypes($types)` | Filters the current collection to include only pages where template is in the array `$types` |
| `Collection::ofOneOfTheseAccessLevels($levels)` | Filters the current collection to include only pages where page access is in the array of `$levels` |

> [!Info]  
> 次のメソッドは、**Grav 1.7** で非推奨となりました： `Collection::modular()` 及び `Collection::nonModular()` 。次のものを使ってください： `Collection::modules()` 及び `Collection::pages()` 。

以下の例は、**Learn2** テーマの **docs.html.twig** によるものです。タクソノミーベースの（もしあればタグも設定可能な）コレクションが定義され、`Collection::isFirst` と `Collection::isLast` メソッドはページのナビゲーションに条件として利用されています：


```twig
{% set tags = page.taxonomy.tag %}
{% if tags %}
    {% set progress = page.collection({'items':{'@taxonomy':{'category': 'docs', 'tag': tags}},'order': {'by': 'default', 'dir': 'asc'}}) %}
{% else %}
    {% set progress = page.collection({'items':{'@taxonomy':{'category': 'docs'}},'order': {'by': 'default', 'dir': 'asc'}}) %}
{% endif %}

{% block navigation %}
        <div id="navigation">
        {% if not progress.isFirst(page.path) %}
            <a class="nav nav-prev" href="{{ progress.nextSibling(page.path).url|e }}"> <i class="fa fa-chevron-left"></i></a>
        {% endif %}

        {% if not progress.isLast(page.path) %}
            <a class="nav nav-next" href="{{ progress.prevSibling(page.path).url|e }}"><i class="fa fa-chevron-right"></i></a>
        {% endif %}
        </div>
{% endblock %}
```

`nextSibling()` は、リストの次のものを指し、`prevSibling()` は、リストの前のものを指します。どのように動くかの例です。

次のようなページがあるとします：

```txt
Project A
Project B
Project C
```

Project Aにいるとき、前のページはProject Bです。
もしProject Bにいるとき、前のページは Project Cであり、次のページは Project A です。
You are on Project A, the previous page is Project B.

<h2 id="programmatic-collections">プログラミングによるコレクション</h2>

Gravのプラグインから、もしくはテーマや、Twigからでも、PHPを利用して直接コレクションを制御できます。これはページのフロントマターで定義する方法に比べると、ハードコーディングではありますが、より複雑で柔軟なコレクションのロジックを作成できます。

<h3 id="php-collections">PHPによるコレクション</h3>

より発展的なコレクションのロジックを、PHPにより提供できます。次の例のように：

```php
$collection = new Collection($pages);
$collection->setParams(['taxonomies' => ['tag' => ['dog', 'cat']]])->dateRange('01/01/2016', '12/31/2016')->published()->ofType('blog-item')->order('date', 'desc');

$titles = [];

foreach ($collection as $page) {
    $titles[] = $page->title();
}
```

The `order()`-function can also, in addition to the `by`- and `dir`-parameters, take a `manual`- and `sort_flags`-parameter. These are [documented above](#ordering-options). You can also use the same `evaluate()` method that the frontmatter-based page collections make use of:

```php
$page = Grav::instance()['page'];
$collection = $page->evaluate(['@page.children' => '/blog', '@taxonomy.tag' => 'photography']);
$ordered_collection = $collection->order('date', 'desc');
```

And another example of custom ordering would be:

```php
$ordered_collection = $collection->order('header.price','asc',null,SORT_NUMERIC);
```

You can also do similar directly in **Twig Templates**:

```twig
{% set collection = page.evaluate([{'@page.children':'/blog', '@taxonomy.tag':'photography'}]) %}
{% set ordered_collection = collection.order('date','desc') %}
```

<h4 id="advanced-collections">発展的なコレクション</h4>

By default when you call `page.collection()` in the Twig of a page that has a collection defined in the header, Grav looks for a collection called `content`.  This allows the ability to define [multiple collections](#multiple-collections), but you can even take this a step further.

If you need to programmatically generate a collection, you can do so by calling `page.collection()` and passing in an array in the same format as the page header collection definition.  For example:

```twig
{% set options = { items: {'@page.children': '/my/pages'}, 'limit': 5, 'order': {'by': 'date', 'dir': 'desc'}, 'pagination': true } %}
{% set my_collection = page.collection(options) %}

<ul>
{% for p in my_collection %}
<li>{{ p.title|e }}</li>
{% endfor %}
</ul>
```

Generating menu for the whole site (you need to set *menu* property in the page's frontmatter):


```yaml
---
title: Home
menu: Home
---
```

```twig
{% set options = { items: {'@root.descendants':''}, 'order': {'by': 'folder', 'dir': 'asc'}} %}
{% set my_collection = page.collection(options) %}

{% for p in my_collection %}
{% if p.header.menu %}
	<ul>
	{% if page.slug == p.slug %}
		<li class="{{ p.slug|e }} active"><span>{{ p.menu|e }}</span></li>
	{% else %}
		<li class="{{ p.slug|e }}"><a href="{{ p.url|e }}">{{ p.menu|e }}</a></li>
	{% endif %}
	</ul>
{% endif %}
{% endfor %}
```

<h4 id="pagination-with-advanced-collections">発展的なコレクションでのページネーション</h4>

A common question we hear is regarding how to enable pagiation for custom collections.  Pagination is a plugin that can be installed via GPM with the name `pagination`.  Once installed it works "out-of-the-box" with page configured collections, but knows nothing about custom collections created in Twig.  To make this process easier, pagination comes with it's own Twig function called `paginate()` that will provide the pagination functionality we need.

After we pass the collection and the limit to the `paginate()` function, we also need to pass the pagination information directly to the `partials/pagination.html.twig` template to render properly.

```twig
{% set options = { items: {'@root.descendants':''}, 'order': {'by': 'folder', 'dir': 'asc'}} %}
{% set my_collection = page.collection(options) %}
{% do paginate( my_collection, 5 ) %}

{% for p in my_collection %}
    <ul>
        {% if page.slug == p.slug %}
            <li class="{{ p.slug|e }} active"><span>{{ p.menu|e }}</span></li>
        {% else %}
            <li class="{{ p.slug|e }}"><a href="{{ p.url|e }}">{{ p.menu|e }}</a></li>
        {% endif %}
    </ul>
{% endfor %}

{% include 'partials/pagination.html.twig' with {'base_url':page.url, 'pagination':my_collection.params.pagination} %}
```


<h4 id="custom-collection-handling-with-oncollectionproces">`onCollectionProcessed()` イベントによるカスタムコレクションの制御</h4>

There are times when the event options are just not enough.  Times when you want to get a collection but then further manipulate the collection based on something very custom.  Imagine if you will, a use case where you have what seems like a rather bog-standard blog listing, but your client wants to have fine grain control over what displays in the listing.  They want to have a custom toggle on every blog item that lets them remove it from the listing, but still have it published and available via a direct link.

To make this happen, we can simply add a custom `display_in_listing: false` option in the page header for the item:

```yaml
---
title: 'My Story'
date: '13:34 04/14/2020'
taxonomy:
    tag:
        - journal
display_in_listing: false
---
...
```

The problem is that there is no way to define or include this filter when defining a collection in the listing page.  It probably is defined something like this:

```yaml
---
menu: News
title: 'My Blog'
content:
    items:
        - self@.children
    order:
        by: date
        dir: desc
    limit: 8
    pagination: true
    url_taxonomy_filters: true
---
...
```

So the collection is simply defined by the `self@.children` directive to get all the published children of the current page. So what about those pages that have the `display_in_listing: false` set? We need to do some extra work on that collection before it is returned to ensure we remove any items that we don't want to see.  To do this we can use the `onCollectionProcessed()` event in a custom plugin.  We need to add the listener:

```php
    public static function getSubscribedEvents(): array
    {
        return [
            ['autoload', 100000],
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
            'onCollectionProcessed' => ['onCollectionProcessed', 10]
        ];
    }
```

Then, we need to define the method and loop over the collection items, looking for any pages with that `display_in_listing:` field set, then remove it if it is `false`:

```php
    /**
     * Remove any page from collection with display_in_listing: false|0
     *
     * @param Event $event
     */
    public function onCollectionProcessed(Event $event): void
    {
        /** @var Collection $collection */
        $collection = $event['collection'];

        foreach ($collection as $item) {
            $display_in_listing = $item->header()->display_in_listing ?? true;
            if ((bool) $display_in_listing === false) {
                $collection->remove($item->path());
            }
        }

    }
```

Now your collection has the correct items, and all other plugins or Twig templates that rely on that collection will see this modified collection so things like pagination will work as expected.
