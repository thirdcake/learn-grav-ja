---
title: ページ・コレクション
layout: ../../../layouts/Default.astro
lastmod: '2025-07-24'
description: 'Grav では、さまざまな方法でページのコレクションを定義できます。コレクションは、ページに並べて表示したり、繰り返し処理したりできます。'
---

Grav で最も一般的なコレクションは、ページを集めたリストです。それらは、ページのフロントマターか、もしくは twig で定義されます。  
より一般的なのは、ページのフロントマターで定義される方です。  
コレクションを定義すると、 Twig で自由に利用できます。  
ページコレクションのメソッドを使用したり、各 [ページのオブジェクト](../../03.themes/06.theme-vars/#page-object) をループで繰り返しながら、ページのメソッドやプロパティを使用することで、パワフルな使い方ができます。  
よくある利用例としては、ブログ記事のリストを表示したり、複雑なページデザインをレンダリングするためにモジュラーのサブページを表示したりすることができます。

<h2 id="collection-object">コレクション・オブジェクト</h2>

ページのフロントマターでコレクションを定義すると、 Twig で利用できる [Grav コレクション](https://github.com/getgrav/grav/blob/develop/system/src/Grav/Common/Page/Collection.php) が作られます。  
コレクション・オブジェクトは、 **iterable（反復可能）** であり、**array（配列）** のように扱えます。  
たとえば、次のように：

```twig
{{ dump(page.collection[page.path]) }}
```

<h2 id="example-collection-definition">コレクションの定義例</h2>

ページのフロントマターで定義されたコレクションの例：

```yaml
content:
    items: '@self.children'
    order:
        by: date
        dir: desc
    limit: 10
    pagination: true
```

`content.items` の値から、 Grav はアイテムを収集します。  
ここに定義される情報から、コレクションをどのように構成するかが決まります。

上記の定義の場合、すべての **子ページ** を要素として持ち、 **date（日付）** の **desc（降順）** で、 **10** ページごとの **pagenation（ページ割り）をする** コレクションを作ります。

ページネーションのリンクは、[Pagination plugin](https://github.com/getgrav/grav-plugin-pagination) がインストールされ、有効化されているときのみ利用できます。

<h2 id="accessing-collections-in-twig">Twigでコレクションにアクセス</h2>

フロントマターでコレクションが定義されると、 twig のテンプレートで利用できる **page.collection** というコレクションが作られます：

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

Grav に、特定のページはリストページで、子ページを持っていることを知らせるため、以下のような、たくさんの変数が用意されています：

<h3 id="summary-of-collection-options">コレクションの設定値の概要</h3>

| 文字列 | 結果 |
| ------ | ---- |
| `'@root.pages'`  | トップレベルにあるページを取得する |
| `'@root.descendants'`  | サイトのすべてのページを取得する |
| `'@root.all'` | サイトのすべてのページとモジュールを取得する |
| | |
| `'@self.page'`  | 現在のページのみのコレクションを取得する |
| `'@self.parent'` | 現在ページの親ページのみのコレクションを取得する |
| `'@self.siblings'` | 現在ページの兄弟ページを取得する |
| `'@self.children'` | 現在ページの子ページを取得する |
| `'@self.modules'`  | 現在ページのモジュールを取得する |
| `'@self.all'`  | 現在ページの子ページとモジュールページ両方を取得する |
| `'@self.descendants'` | 現在ページの子ページ以下を再帰的にすべて取得する |
|  |  |
| `'@page.page': '/fruit'`  | `/fruit` ページのみのコレクションを取得する |
| `'@page.parent': '/fruit'`  | `/fruit` ページの親ページのみのコレクションを取得する |
| `'@page.siblings': '/fruit'`  | `/fruit` ページの兄弟ページを取得する |
| `'@page.children': '/fruit'`  | `/fruit` ページの子ページを取得する |
| `'@page.modules': '/fruit'`  | `/fruit` ページのモジュールを取得する |
| `'@page.all': '/fruit'`  | `/fruit` ページの子ページとモジュールページ両方を取得する |
| `'@page.descendants': '/fruit'`  | `/fruit` ページの子ページ以下を再帰的にすべて取得する |
|  |  |
| `'@taxonomy.tag': photography`  | タクソノミーが、タグ=`photography` であるもの |
| `'@taxonomy': {tag: birds, category: blog}`   | タクソノミーが、 タグ=`birds` かつ カテゴリー=`blog` であるもの |

> [!Note]  
> このドキュメントでは、`@page`や、`@taxonomy.category` などを使いますが、より YAML 安全な書き方は、`page@`や、`taxonomy@.category` です。すべての`@` コマンドは、前か後に置く必要があります。

> [!Info]  
> コレクションの設定は、**Grav 1.6** から進化し、変わっています。古いバージョンもまだ動くかもしれませんが、非推奨です。

より詳しく見ていきましょう。

<h2 id="root-collections">Root コレクション</h2>

<h5 id="atroot-pages-top-level-pages">@root.pages トップレベルのページ</h5>

これは、サイトのトップ（root）レベルの **公開ページ** を取得します。  
特に、メインのナビゲーションメニューを作成するようなときに便利です：

```yaml
content:
    items: '@root.pages'
```

エイリアス（別名）で、`@root.children` も有効です。  
`@root` の使用は、将来的に意味が変わる可能性があり、非推奨となりました。

<h5 id="atroot-descendants-all-the-pages">@root.descendants すべてのページ</h5>

これは、すべてのページを取得します。  
再帰的に、トップページ以下、すべてのサブページ、さらにそのサブページへたどり、サイト上の **すべての** **公開ページ** のコレクションを作ります：

```yaml
content:
    items: '@root.descendants'
```

<h5 id="atroot-all-all-the-pages-and-modules">@root.all すべてのページとモジュール</h5>

これもまた、上と同じ動きをしますが、サイト上の **すべての** **公開ページ 及び モジュール** を含みます。

```yaml
content:
    items: '@root.all'
```

<h2 id="self-collections">Self コレクション</h2>

<h5 id="atself-page-current-page-only">@self.page 現在のページのみ</h5>

これは、現在のページのみを持つコレクションを返します。

```yaml
content:
    items: '@self.page'
```

エイリアスの `@self.self` もまた、有効です。

> [!Note]  
> そのページが公開されていない場合、空のコレクションとなります。

<h5 id="atself-parent-the-parent-page-of-the-current-page">@self.parent 現在のページの親ページ</h5>

これは、特別なケースのコレクションです。  
常に、現在ページの **親ページ** のみを返すからです。

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

エイリアスで、`@self.pages` も有効です。  
`@self` を使うことは、将来的に意味が変わりうるため、非推奨となりました。

<h5 id="atself-modules-modules-of-the-current-page">@self.modules 現在ページのモジュール</h5>

このメソッドは、現在ページの **公開されたモジュール** のみを取得します。（たとえば、`_features`や、`_showcase` など）

```yaml
content:
    items: '@self.modules'
```

エイリアスの `@self.modular` は非推奨です。

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

エイリアスの `'@page.pages': '/blog'` も、有効です。  
`'@page': '/blog'` は、将来的に意味が変わる可能性があり、非推奨となりました。

<h5 id="atpage-modules-modules-of-a-specific-page">@page.modules そのページのモジュール</h5>

このコレクションは、スラッグにより指定したページが持つすべての **公開されたモジュール** を返します。

```yaml
content:
    items:
      '@page.modules': '/blog'
```

エイリアスの `'@page.modular': '/blog'` は非推奨です。

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

`@taxonomy` オプションを使うと、 Grav の強力なタクソノミー機能を利用できます。  
ここで、 [サイト設定](../../01.basics/05.grav-configuration/#site-configuration) での `@taxonomy` 設定が登場します。  
Grav が、適切にそのページ参照を解釈するためには、この設定ファイルで、タクソノミーが定義 **されていなければなりません** 。

`@taxonomy.tag: foo` という設定によって、 Grav は、 `/user/pages` フォルダ内にあり、そのタクソノミー変数が `tag: foo` となっているすべての **公開ページ** を探しにいけます。

```yaml
content:
    items:
       '@taxonomy.tag': [foo, bar]
```

`content.items` 変数は、複数のタクソノミーの配列を取得でき、すべてを満たすページを集めます。  
`foo` **及び** `bar` タグ **両方**を持つ公開ページのコレクションとなります。  
[タクソノミー](../08.taxonomy) の章では、より詳しくその概念を解説します。

> [!Info]  
> もし複数の変数を一行で取得したい場合は、サブの変数を `{}` 波カッコで区切らなければいけません。その後、それぞれの変数は、コンマで区切ります。たとえば： `'@taxonomy': {category: [blog, featured], tag: [foo, bar]}` この例では、 `category` と `tag` というサブ変数が、 `@taxonomy` の中にあります。それぞれは、 `[]` 角カッコのリストを持ちます。これら **すべて** の条件を満たすページを探します。

もし、複数の変数で探すなら、一行で書くのではなく、次のような、より標準的な書き方を推奨します：

```yaml
content:
  items:
    '@taxonomy':
      category: [blog, featured]
      tag: [foo, bar]
```

ヒエラルキーのそれぞれの階層には、2つの空白が頭につけられています。  
YAML は、いくつのスペースで階層分けをしてもよいのですが、2つというのが標準的なやり方です。  
上記の例では、`カテゴリー` と `タグ` 変数が、 `@taxonomy` 変数の下に吊り下がっています。

ページコレクションは、タクソノミーが設定されると、（ `/archive/category:news` のような） URL によって、自動的にフィルタリングされます。  
これにより、ひとつのブログを立ち上げるだけで、 URL を使って動的にフィルタリングすることができます。  
もしタクソノミーを無視して欲しい場合は、 `url_taxonomy_filters:false` というフラグを使うと、この機能は無効になります。

<h3 id="complex-collections">複雑なコレクション</h3>

複数の、複雑なコレクションを定義することもできます。  
結果的に、それらのコレクションの合計となるコレクションが得られます。たとえば：

```yaml
content:
  items:
    - '@self.children'
    - '@taxonomy':
         category: [blog, featured]
```

これに加えて、 `filter: page_type: value` を使って、コレクションをフィルタリングできます。  
`page_type` には、次のいずれかが使えます： `published`, `visible`, `page`, `module`, `routable` 。  
これらは、 [コレクション特有のメソッド](#collection-object-methods) に対応し、いくつかのフィルターをコレクションに適用できます。  
これらはすべて、 `true` もしくは `false` の値を取ります。  
さらに加えて、テンプレート名でフィルタリングできる `type` や、複数のテンプレート名を要素に持つ配列でフィルタリングできる `types` 、そして、アクセスレベルの配列でフィルタリングできる `acccess` もあります。
具体例：

```yaml
 content:
  items: '@self.siblings'
  filter:
    visible: true
    type: 'blog'
    access: ['site.login']
```

`page_type` には、否定形の `non-published`, `non-visible`, `non-page` (=module), `non-module` (=page) and `non-routable`, も可能ですが、通常バージョンにして、値を `false` にした方がわかりやすいでしょう。

```yaml
 content:
  items: '@self.children'
  filter:
    published: false
```

> [!Info]  
> コレクションのフィルタ機能は、 **Grav 1.6** からシンプルになりました。以前の `modular` や `non-modular` 変数は、まだ機能するものの、使わないことをおすすめします。代わりに、 `module` や `page` を使ってください。

<h2 id="ordering-options">表示順の設定</h2>

```yaml
content:
    order:
        by: date
        dir: desc
    limit: 5
    pagination: true
```

サブページの表示順は、フォルダの表示順のルールに従います。  
利用できる選択肢は、次の通りです：

| 順序 | 詳細 |
| :--- | :--- |
| `default` | ファイルシステムの順。例： `01.home` の後に `02.advark` |
| `title`      | 各ページで定義されたページタイトルの順 |
| `basename`   | PHP 関数の `basename()` で処理された後のフォルダ名のアルファベット順 |
| `date`       | 各ページで定義された日付順 |
| `modified`   | ページの最新修正日順 |
| `folder`     | 数字の接頭辞（例えば `01.` ）が取り除かれた後のフォルダ名順 |
| `header.x`   | ページで定義されたヘッダーフィールド（例えば `header.taxonomy.year` ）順。また、パイプによって追加できます（例： `header.taxonomy.year\|2015` |
| `random`     | ランダム順 |
| `custom`     | `content.order.custom` 変数順（下記の例参照） |
| `manual`     | `order_manual` 変数順。 **非推奨** |
| `sort_flags` | ページヘッダーベースの、もしくはデフォルト順序のソートフラグを上書きできます。もし `intl` PHP 拡張が読み込まれていれば、 [これらのフラグ](https://www.php.net/manual/ja/collator.asort.php) のみ使えます。そうでない場合、 [標準的なソートフラグ](https://www.php.net/manual/ja/array.constants.php) が使えます。 |

`content.order.dir` 変数は、表示の方向を設定します。  
`desc` （降順）か `asc` （昇順） を設定してください。

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

上記の設定では、 `content.order.custom` 設定により、 **カスタムで手動の順番** を定義できます。  
これにより、 **showcase** が最初に、 **highlights** が次に、といった順番が作れます。  
もしこのリストからページが漏れていた場合、 Grav は、その漏れたページを `content.order.by` による順番で代替します。

ページにカスタムのスラッグがある場合、`content.order.custom` リストでは、スラッグを使わなければいけません。

`content.pagenation` は、 true か false の設定で、プラグインなどに **ページネーション** が初期化されるべきかどうかを知らせます。  
`content.limit` は、1ページごとにいくつのアイテムを表示させるかを示します。

<h3 id="date-range">日付の範囲</h3>

日付の範囲で、ページをフィルタリングすることもできます：

```yaml
content:
    items: '@self.children'
    dateRange:
        start: 1/1/2014
        end: 1/1/2015
```

[PHP の strtotime 関数](https://php.net/manual/en/function.strtotime.php) で使えるフォーマットであれば、どんな文字列でも使えます。  
たとえば、 `-6 weeks` や、 `last Monday` などの書式が、伝統的な日付である `01/23/2014` や `23 January 2014` と同じように使えます。  
content.dateRange は、その範囲外の日付を持つページを省きます。  
**start** と **end** は、どちらもオプションです。  
しかしどちらか一方は、定義されていなければいけません。

<h3 id="multiple-collections">複数のコレクション</h3>

`content: items:` によってコレクションを作った場合、いくつかの条件のもと、1つのコレクションを作ったということです。  
しかし、 Grav では、ページごとに、任意の数のコレクションを作れます。  
ただ、次のものを作るだけで良いのです：

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

上記の例では、 **2つのコレクション** を作成しています。  
1つ目は、通常の `content` コレクションですが、2つ目は、タクソノミーベースのコレクションで、 `fruit` と名付けられています。  
Twig から、これら2つのコレクションにアクセスするには、次のような構文を使います：

```yaml
{% set default_collection = page.collection %}
{% set fruit_collection = page.collection('fruit') %}
```

<h2 id="collection-object-methods">コレクションオブジェクトのメソッド</h2>

Iterable のメソッドは含まれます：

| プロパティ | 説明 |
| -------- | ------ |
| `Collection::append($items)` | 別のコレクションや配列を追加 |
| `Collection::first()` | コレクション内の最初のアイテムを取得 |
| `Collection::last()` | コレクション内の最後のアイテムを取得 |
| `Collection::random($num)` | Pick `$num` random items from the collection |
| `Collection::reverse()` | コレクションを逆順にする |
| `Collection::shuffle()` | コレクション全体をシャッフルする |
| `Collection::slice($offset, $length)` | リストをスライスする |

コレクション特有の便利なメソッドもいくつかあります：

| プロパティ | 説明 |
| -------- | ------ |
| `Collection::addPage($page)` | このコレクションに別のページを追加する |
| `Collection::copy()` | 現在のコレクションのコピーを作成する |
| `Collection::current()` | コレクション内の現在のアイテムを取得 |
| `Collection::key()` | 現在のアイテムのスラッグを返す |
| `Collection::remove($path)` | コレクション内の特定のページを削除。もしくは、 `$path=null` の場合、現在のページを削除 |
| `Collection::order($by, $dir, $manual)` | 現在のコレクションを並べる |
| `Collection::intersect($collection2)` | 2つのコレクション両方にあるアイテムをまとめる（ "かつ" 条件のように）  |
| `Collection::merge($collection2)` | 2つのコレクションいずれかにあるアイテムをまとめる（"または" 条件のように） |
| `Collection::isFirst($path)` | 最初のページかどうか判断 |
| `Collection::isLast($path)` | 最後のページかどうか判断 |
| `Collection::prevSibling($path)` | 可能であれば、前のページを返す |
| `Collection::nextSibling($path)` | 可能であれば、次のページを返す |
| `Collection::currentPosition($path)` | 現在のインデックスを返す |
| `Collection::dateRange($startDate, $endDate, $field)` | コレクションを日付でフィルタリングする |
| `Collection::visible()` | コレクションを visible （ナビゲーションメニューなどに表示）のページのみにフィルタリングする |
| `Collection::nonVisible()` | コレクションを non-visible のページのみにフィルタリングする |
| `Collection::pages()` | コレクションをページのみ（モジュールは除く）にフィルタリングする |
| `Collection::modules()` | コレクションをモジュールのみ（ページは除く）にフィルタリングする |
| `Collection::published()` | コレクションを公開ページのみにフィルタリングする |
| `Collection::nonPublished()` | コレクションを非公開ページのみにフィルタリングする |
| `Collection::routable()` | コレクションをルーティング可能なページのみにフィルタリングする |
| `Collection::nonRoutable()` | コレクションをルーティングしないページのみにフィルタリングする |
| `Collection::ofType($type)` | テンプレートが = `$type` であるページのみにコレクションをフィルタリングする |
| `Collection::ofOneOfTheseTypes($types)` | テンプレートが `$types` 配列に含まれているページのみにコレクションをフィルタリングする |
| `Collection::ofOneOfTheseAccessLevels($levels)` | コレクションを `$levels` 配列に含まれるアクセスレベルのページのみにフィルタリングする |

> [!Info]  
> 次のメソッドは、**Grav 1.7** で非推奨となりました： `Collection::modular()` 及び `Collection::nonModular()` 。次のものを使ってください： `Collection::modules()` 及び `Collection::pages()` 。

以下の例は、 **Learn2** テーマの **docs.html.twig** によるものです。  
タクソノミーベースの（もしあればタグも設定可能な）コレクションが定義され、 `Collection::isFirst` と `Collection::isLast` メソッドはページのナビゲーションに条件として利用されています：


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

`nextSibling()` は、リストの次のものを指し、`prevSibling()` は、リストの前のものを指します。  
どのように動くかの例です。

次のようなページがあるとします：

```txt
Project A
Project B
Project C
```

Project Aにいるとき、前のページはProject Bです。  
もしProject Bにいるとき、前のページは Project Cであり、次のページは Project A です。  

<h2 id="programmatic-collections">プログラミングによるコレクション</h2>

Grav のプラグインから、もしくはテーマや、 Twig からでも、 PHP を利用して直接コレクションを制御できます。  
これはページのフロントマターで定義する方法に比べると、ハードコーディングではありますが、より複雑で柔軟なコレクションのロジックを作成できます。

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

`order()` 関数は、 `by` や `dir` パラメータに加えて、 `manual` や `sort_flags` パラメータも使えます。  
[上記のドキュメント](#ordering-options) を参照してください。  
フロントマターで定義するものと同じ `evaluate()` メソッドを使うこともできます。

```php
$page = Grav::instance()['page'];
$collection = $page->evaluate(['@page.children' => '/blog', '@taxonomy.tag' => 'photography']);
$ordered_collection = $collection->order('date', 'desc');
```

カスタム順序の別の例です：

```php
$ordered_collection = $collection->order('header.price','asc',null,SORT_NUMERIC);
```

**Twig テンプレート** で、同様のことを直接することもできます：

```twig
{% set collection = page.evaluate([{'@page.children':'/blog', '@taxonomy.tag':'photography'}]) %}
{% set ordered_collection = collection.order('date','desc') %}
```

<h4 id="advanced-collections">発展的なコレクション</h4>

デフォルトでは、ページヘッダーでコレクションを定義した場合に、そのページの Twig で `page.collection()` 関数を呼び出すと、 Grav は、 `content` と呼ばれるコレクションを探します。  
これにより、 [複数のコレクション](#multiple-collections) が定義できますが、さらにもう一歩深く進むこともできます。

プログラム処理によりコレクションを生成する必要がある場合、 `page.collection()` を呼び出し、ページヘッダーでコレクションを定義したときと同じフォーマットの配列を渡すことで、それが可能になります。  
たとえば：

```twig
{% set options = { items: {'@page.children': '/my/pages'}, 'limit': 5, 'order': {'by': 'date', 'dir': 'desc'}, 'pagination': true } %}
{% set my_collection = page.collection(options) %}

<ul>
{% for p in my_collection %}
<li>{{ p.title|e }}</li>
{% endfor %}
</ul>
```

サイト全体のメニューが生成されます（ページのフロントマターに *menu* プロパティを設定しておく必要はあります）：


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

カスタムコレクションにページネーション（ページ割）をするやり方について、よく質問を受けます。  
ページネーションは、 `pagination` という名前で GPM 経由でインストールできるプラグインがあります。  
インストールし、コレクションを設定するだけで、 "箱から開けてすぐに" 機能しますが、 Twig で作成されたカスタムコレクションについては、何も解説がありません。  
この処理を簡単にするため、ページネーションプラグインには、 `paginate()` という Twig 関数があり、必要なページ割り機能を提供してくれます。

`paginate()` 関数に、コレクションと1ページ上限数を渡した後、適切にレンダリングされるためには、ページ割り情報を直接 `partials/pagination.html.twig` テンプレートに渡す必要があります。

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

イベントオプションが不十分であるときもあります。  
コレクションが欲しいけれど、何かカスタマイズされたものをベースとしてコレクションを操作したいようなときです。  
想像してみてください： ありふれたブログ一覧ページがあって、しかしあなたのクライアントは、一覧に何を表示するのか、細かくコントロールしたいと思っているようなケースを。  
クライアントは、一覧に入れるか入れないかを制御するカスタムスイッチをブログアイテムすべてに置いてほしいと望んでいますが、そのブログアイテムは、一覧には載らないだけで、公開したいし、直接リンクによって利用可能としておきたいような場合です。

このようなことを実現するには、アイテムのページヘッダーに `display_in_listing: false` というカスタムオプションを追加するだけでできます：

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

問題は、このフィルタを定義したり含めたりする方法が、一覧ページのコレクションを定義する際に無いということです。  
おそらく、次のように定義されます：

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

コレクションは、 `self@.children` ディレクティブによって簡単に定義し、現在ページの子ページで公開されているものをすべて取得します。  
それでは、 `display_in_listing: false` が設定されているページについてはどうしましょうか？  
追加の機能を実行する必要があります。返される前のコレクションで、一覧表示させたくないあらゆるアイテムを確実に取り除く機能です。  
これを行うため、カスタムプラグインで `onCollectionProcessed()` イベントを使うことができます。  
イベントリスナーを追加する必要があります：

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

それから、メソッドを定義し、コレクションアイテムをループさせ、 `display_in_listing:` フィールドが設定されたページをすべて探し出し、もしそれが `false` であれば削除します：

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

これで、コレクションは正しいアイテムを持ちます。このコレクションを利用する他のすべてのプラグインと Twig テンプレートは、この修正後のコレクションを使うため、ページネーションのようなものも期待通り動きます。

