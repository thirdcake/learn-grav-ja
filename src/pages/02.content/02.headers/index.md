---
title: "ヘッダー / フロントマター"
layout: ../../../layouts/Default.astro
---

ページの上部にある、ページのヘッダー（フロントマターとして知られているもの）は、完全に付随的なものです。Gravでページを表示するときに、無かったとしても全く問題のないものです。Gravには、3つの主要なページタイプがあります（**標準**、**一覧**、そして**モジュラー** ）が、それぞれはヘッダーと関係しています。

> [!Note]  
> ヘッダは、**ページのフロントマター(Frontmatter)** とも呼ばれ、HTTPヘッダと混同されないように、そちらで呼ばれることがよくあります。

<h2 id="basic-page-headers">基本的なページヘッダー</h2>

たくさんの基本的なヘッダーオプションが使えます。

### Cache Enable

```yaml
cache_enable: false
```

デフォルトでは、Gravは、できるだけ速く動作するため、ページファイルのコンテンツをキャッシュ（一時保存）します。ページをキャッシュしたくない場合の、発展的な場面があります。

この例では、コンテンツの中で、動的なTwig変数を使っています。`cache_enable` 変数は、このふるまいを上書きします。Twigのコンテンツ変数については、後ほどの章で解説予定です。この設定では、`true` もしくは `false` を指定します。

### Date

```yaml
date: 01/01/2020 3:14pm
```

`date` 変数は、このページに関連する特定の日付を設定します。これは、しばしばそのポストが作られた日であり、表示予定日や表示順序を決める目的に使われます。設定しなければ、そのページを最後に **修正した時間** がデフォルトで使われます。

> [!Note]  
> `月/日/年` もしくは `日-月-年` による日付表記は、要素間のセパレータを見ることではっきりさせることができます：もしセパレータがスラッシュ（`/`）だった場合、**アメリカ式** で `月/日/年` が推定され、セパレータがダッシュ（`-`）やドット（`.`）だった場合、**ヨーロッパ式** で `日.月.年` が推定されます。

### Menu

```yaml
menu: My Page
```

`menu` 変数は、ナビゲーションで使われるテキストを設定します。メニューにはいくつかの代替措置があるので、`menu` 変数を設定しそこねたとしても、Gravは代わりに、 `title` 変数を利用します。

### Published

```yaml
published: true
```

デフォルトでは、ページは **表示されます（publishされます）** 。意図的に `published: false` を設定したり、`publish_date` を未来に設定したり、`unpublish_date` を過去に設定しない限りは。この変数の有効な値は、`true` もしくは `false` です。

### Slug

```yaml
slug: my-page-slug
```

`slug` 変数は、ページURLの一部として設定します。たとえば：上記のような`slug` を設定した場合、`http://yoursite.com/my-page-slug` がURLになるでしょう。もし `slug` がページに設定されなかったら、Gravは（先頭の数字を除いた）フォルダ名を代わりにURLとして使います。

[スラッグ](http://en.wikipedia.org/wiki/Semantic_URL#Slug) は、一般的にすべて小文字で、アクセント文字は英語のアルファベットに置き換えられ、ホワイトスペース（半角の空白）文字はダッシュ（`-`）やアンダースコア（`_`）に置き換えられます。将来的には、Gravは、スラッグ中の `spaces` に対応予定ですが、空白や大文字は推奨しません。

たとえば：ブログ投稿のタイトルが `Blog Post Example` だったとき、おすすめのスラッグは、`blog-post-example` です。

### Taxonomy

```yaml
taxonomy:
    category: blog
    tag: [sample, demo, grav]
```

とても便利なヘッダー変数である `taxonomy` は、[サイト設定](../../01.basics/05.grav-configuration/#site-configuration) ファイルで決めた **タクソノミー（タグやカテゴリーのこと）** に、値を設定できます。

もしタクソノミーを設定ファイルで決めていなかった場合、この設定は無視されます。この例では、このページは`blog` カテゴリーの記事であり、`sample`、`demo`、`grav` というタグを持っています。これらタクソノミーは、他のページや、プラグイン、テーマから、そのページを探してもらうために使われます。[タクソノミー](../08.taxonomy) の章では、より詳しい概念を説明予定です。

### Title

ヘッダーがまったく無い場合、ブラウザや検索エンジンで表示されるページタイトルを設定することはできません。そのため、_最低でも_ `title` 変数だけは、ページのヘッダに設定しておくことをおすすめします。

```yaml
title: Title of my Page
```

`title` 変数が設定されていない場合、Gravは、`slug` 変数を代わりに使おうとします。

<h2 id="advanced-headers">応用的なヘッダ</h2>

次のものは、重要ながらあまり一般的ではない使い方です。より応用的な機能をそのページに提供してくれます。

### Append URL extension

```yaml
append_url_extension: '.json'
```

ページがデフォルトの拡張子を上書きし、プログラム的に設定します。同時に、適切なヘッダ属性をレスポンスに付与します。

### Cache-Control

```yaml
cache_control: max-age=604800
```

この変数を入力するときは、[適切な](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cache-Control) `cache-control` テキストを設定してください。

> [!Note]  
> ページのコンテンツ情報が、ユーザーごとに変わる場合は、`no-cache` を使うことを確認してください。そうでなければ、他のユーザーにコンテンツ情報が漏洩するかもしれません。[Expires](#expires) の設定は、`expires: 0` と同じ影響があります。

### Date Format

```yaml
dateformat: 'Y-m-d H:i:s'
```

Gravの日付のデフォルト設定を上書きし、ページ単位で設定できます。[PHPのdate書式](https://www.php.net/manual/en/function.date.php) が使えます。

### Debugger

`system.yaml` 設定ファイルでデバッガを有効にしていた場合、デバッガはすべてのページで表示されます。これが好ましくない場合や、出力と不整合を起こすこともあります。そのような例には、Ajax呼び出しに、レンダリングされたHTMLを返すようなページがあります。このようなときは、結果のデータに、デバッガが差し込まれていてほしくないでしょう。このページだけデバッガを無効にしたいようなときに、`debugger` ページヘッダを使ってください。

```yaml
debugger: false
```

### ETag

```yaml
etag: true
```

ページ単位で、ETag（キャッシュを制御するHTTPヘッダ）をユニークな値で表示するかどうかを、有効化したり無効化したりできます。`system.yaml` で、上書きしない限り、デフォルトでは `false` です。

### Expires

```yaml
expires: 604800
```

ページの有効期限は、604800秒（=7日間）です。

> [!Note]  
> ユーザごとにページのコンテンツ情報が変わるときは、`expires: 0` になっていることを確認してください。そうでなければ、他のユーザに漏洩する可能性があります。[Cache-Control](#cache-control)設定も、あわせて確認してください。

### External Url

```yaml
external_url: https://www.mysite.com/foo/bar
```

動的に生成されたURLに上書きできます。

### HTTP Response Code

```yaml
http_response_code: 404
```

HTTP レスポンスコードを動的に設定できます。

### Language

```yaml
language: fr
```

特定のページに対して、言語を上書きできます。

### LastModified

```yaml
last_modified: true
```

ページ単位で、HTTPのLast-Modifiedヘッダを編集日により表示するかどうかを、有効化したり無効化したりできます。`system.yaml` で上書きしていなければ、デフォルトで `false` です。

### Lightbox

```yaml
lightbox: true
```

厳密に言えば、これはページヘッダとしては標準的ではないのですが、ページに標準のライトボックスのJavaScriptとCSSを読み込みを有効化する一般的な方法です。デフォルトでは、コアの`antimatter` テーマはライトボックスのライブラリを読み込みません。GPMから利用できる **Featherlight** のような、ライトボックスプラグインをインストールしてください。

### Login Redirect Here

```yaml
login_redirect_here: false
```

`login_redirect_here` 設定は、ユーザが[Gravのログイン・プラグイン](https://github.com/getgrav/grav-plugin-login) でログインしたあとに、そのページがまた表示されるかどうかを設定します。この設定が`false` だった場合、その人は、ログインに成功したあと、より優先的なページへ遷移します。

`true` 設定にすると、その人はログイン後もそのページにいられます。もしこの設定をページのフロントマターに書いていなければ、これがデフォルトの設定です。

このデフォルト設定は、上書きできます。ログインプラグインの設定YAMLに、標準的な場所を指定することができます。

```yaml
redirect_after_login: '/profile'
```

上記の設定では、ログインに成功すると、`/profile` ページが表示されます。

### Markdown

```yaml
  markdown:
    extra: false
    auto_line_breaks: false
    auto_url_links: false
    escape_markup: false
    special_chars:
      '>': 'gt'
      '<': 'lt'
```

| プロパティ | 設定 |
| -------- | ----------- |
| **extra:** | Markdown Extraサポートを有効化 (GFM by default) |
| **auto_line_breaks:** | 自動改行を有効化 |
| **auto_url_links:** | HTMLを自動的にリンク（`<a>`タグ）にすることを有効化 |
| **escape_markup:** | マークアップタグをエスケープする |
| **special_chars:** | special characters を自動的に変換するリスト |

`user/config/system.yaml` 設定ファイルにより、サイト全体で有効化することもできます。また、 _ページごとに_ この `markdown` ヘッダオプションにより、全体設定を上書きできます。

### Never Cache Twig

```yaml
never_cache_twig: true
```

この設定を有効化すると、（Twig処理後の）最終結果をキャッシュして保存するのではなく、ページが読み込まれるごとに動的に変化するプロセスロジックを追加できます。サイト全体では **system.yaml** で有効化・無効化できます。`true`もしくは`false` で設定してください。

これは些細な変更ではありますが、特にモジュラページでは便利な変更です。これにより作業中に何度もキャッシュを無効にする必要がなくなります。ページはキャッシュされますが、Twigはキャッシュされません。Twigは、キャッシュされたコンテンツを取得したあとに処理されます。モジュラのフォームでは、モジュラページのキャッシュを無効にしなくても、この設定だけで機能するようになりました。

> [!Info]  
> この設定は、`twig_first: true` とは互換性がありません。なぜなら、すべての処理は、一度のTwig呼び出しで起こるからです。

### Process

```yaml
process:
	markdown: false
	twig: true
```

ページのプロセスは、また別の高度な機能です。デフォルトでは、Gravは、`markdown` を処理（パース）しますが、ページ内の `twig` は **処理しません** 。このようにTwigをデフォルトで処理しない理由は、純粋にパフォーマンス上の理由です。一般的には必要とされない機能です。 `process` 変数で、このふるまいを上書きできます。

ある特定のページでは、`markdown` を無効にしたいことがあるかもしれません。100%HTMLで書き、マークダウンの処理を全く必要としないようなときです。または、プラグインが、まったく別の方法でコンテンツを処理することもあります。この値は、`true` もしくは `false` としてください。

Twigテンプレートの機能をコンテンツの中で使いたいようなシチュエーションでは、`twig` 変数をtrueにしてください。

### Process Twig First

```yaml
twig_first: false
```

`true` に設定すると、Twigプロセスが、マークダウンプロセスより先に行われます。これは、Twigでマークダウン処理が必要なテキストを生成するようなときに便利です。注意点として、`cache_enable: false` と **した上で** `twig_first: true` とすると、ページキャッシュは機能しません。

### Publish Date

```yaml
publish_date: 01/23/2020 13:00
```

オプション設定ですが、自動的に公開する日を設定できます。使える文字列は、 [PHPのstrtotime関数](https://php.net/manual/en/function.strtotime.php) がサポートする日付の値です。

### Redirect

```yaml
redirect: '/some/custom/route'
```

or

```yaml
redirect: 'http://someexternalsite.com'
```

ページヘッダから、サイト内の別ページもしくはサイト外ページへリダイレクトできます。もちろん、そのページは表示されません。しかし、そのページはGrav内にページとしてあるため、collectionや、メニュー、その他にあり続けます。

リダイレクトコードを、角括弧を利用して、URLの最後に付け足すこともできます。

```yaml
redirect: '/some/custom/route[303]'
```

### Routes

```yaml
routes:
  default: '/my/example/page'
  canonical: '/canonical/url/alias'
  aliases:
    - '/some/other/route'
    - '/can-be-any-valid-slug'
```

フォルダ構造で決められた標準的なURL構造を上書きする **デフォルトURL** を使うことができます。

**canonicalのURL** を使うこともできます。これは、テーマで canonicalのリンクタグを出力するのに使われます。

```html
<link rel="canonical" href="https://yoursite/dresses/green-dresses-are-awesome" />
```

最後に、あるページの代替ルートとして使える **別名のURL** の配列を指定できます。

### Routable

```yaml
routable: false
```

デフォルトでは、すべてのページは **アクセス可能** です。つまり、ブラウザからそのページのURLを指し示せば、そのページにたどり着くということです。しかしながら、特定のコンテンツを持つページは作りたいが、プラグインや他のコンテンツ、もしくはそれら同士だけで呼び出せれば良いということもあります。このわかりやすい例として、 `404 エラー` ページがあります。

Gravは、なにかのページが見つからないときに、`/error` ページを自動的に探します。Grav内に存在するページなので、どのように見えるかを完全に制御できます。しかし、ブラウザからこのページへ直接アクセスされることは望まないでしょうから、このページは一般的に `routable` 変数がfalseに設定されます。`true` もしくは `false` で設定します。

### SSL

```yaml
ssl: true
```

特定のページで、SSLの強制を **on** にするか **off**にするかを決められます。`system.yaml` 設定で、`absolute_urls: true` を **選択したときのみ機能します** 。なぜなら、ページのSSLか非SSLかを変更するためには、プロトコルやホストも含めたURL全体が必要だからです。

### Summary

```yaml
summary:
  enabled: true
  format: short | long
  size: int
```

**要約** オプションは、`page.summary()` メソッドの返り値を設定します。これは、しばしばブログ一覧のタイプを作るシナリオで使われますが、ページのコンテンツを要約したいときはいつでも使ってください。利用場面は、次の通りです。

| プロパティ | 説明 |
| -------- | ----------- |
| **enabled:** | Switch off page summary (the summary returns the same as the page content) |
| **format:** | <ul><li>`long` = Any summary delimiter of the content will be ignored<li>`short` = Detect and truncate content up to summary delimiter position</ul> |

`size` 属性は、`short` と `long` の設定によって意味が異なります。

| Short サイズ | 説明 |
| -------- | ----------- |
| **size: 0** | If no summary delimiter is found, the summary equals the page content, otherwise the content will be truncated up to summary delimiter position |
| **size:** `int` | Always truncate the content after **int** chars. If a summary delimiter was found, then truncate content up to summary delimiter position |

| Long サイズ | 説明 |
| -------- | ----------- |
| **size: 0** | Summary equals the entire page content |
| **size:** `int` | The content will be truncated after **int** chars, independent of summary delimiter position |

### Template

```yaml
template: custom
```

[前の章](../01.content-pages) で解説したとおり、ページをレンダリングするテーマのテンプレートは、`.md` ファイルのファイル名を基準にします。

このため、`default.md` ファイルは、有効化されているテーマの `default` テンプレートを使います。もちろん、この設定も上書きできます。単に、ヘッダに `template` 変数を異なるテンプレートに設定するだけです。

上の例では、そのページは `custom` テンプレートが使われます。この変数は、プラグインのプログラムからページのテンプレートを変える必要があるときのために用意されています。

### Template Format

```yaml
template_format: xml
```

伝統的に、あるページを特定のフォーマット（たとえば、xml、json、その他）で出力したいとき、URLにそのフォーマットを付け足す必要がありました。たとえば、`http://example.com/sitemap.xml` にアクセスすると、ブラウザは、`.xml.twig` で終わる `xml` のtwigテンプレートを使ったコンテンツをレンダリングします。これはとても良いことです。なぜならGravでかんたんにできるからです。

`template_format` ページヘッダを使えば、URLに拡張子を使うことなく、ブラウザにどのようにレンダリングするかを伝えることができます。`sitemap` ページに、`template_format: xml` を入力することで、`http://example.com/sitemap` が `.xml` を最後につけなくても機能するようになります。

[Grav サイトマップ・プラグイン](https://github.com/getgrav/grav-plugin-sitemap) では、[このメソッドを使っています。](https://github.com/getgrav/grav-plugin-sitemap/commit/00c23738bdbfe9683627bf0f99bda12eab9505d5#diff-190081f40350c0272970d9171f3437a2) 。 

### Unpublish Date

```yaml
unpublish_date: 05/17/2020 00:32
```

オプション設定ですが、自動的に非公開にする日を設定できます。使える文字列は、 [PHPのstrtotime関数](https://php.net/manual/en/function.strtotime.php) がサポートする日付の値です。

### Visible

```yaml
visible: false
```

デフォルトでは、**ナビゲーション** 内でページは **表示されます** 。フォルダが番号で始まるとき（例 `/01.home`）は表示される一方で、`/error` のようなときは **表示されません** 。このふるまいはヘッダに `visible` 変数を設定することで上書きできます。`true` もしくは `false` で設定してください。

<h2 id="custom-page-headers">カスタム・ページ・ヘッダ</h2>

もちろん、適切なYAML構文を使うことで、独自にカスタムしたページヘッダを作り出せます。これらはそのページに特有のものであり、プラグインやテーマから利用可能です。わかりやすい例として、次のようなサイトマッププラグインで使える変数が設定できます。

```yaml
sitemap:
    changefreq: monthly
    priority: 1.03
```

これらのヘッダの重要な点は、Gravはデフォルトでは利用しないことです。**サイトマップ・プラグイン** でのみ読まれます。そして、このページがどれくらいの頻度で修正され、優先度がどの程度なのかを、そのプラグインは決定します。

このようにあらゆるページのヘッダはドキュメント化されます。そして通常は、ページがヘッダを定義していない場合に備えて、デフォルトの値が用意されています。

次の例では、ページ特有のデータが保存されています。そしてTwigがそのページコンテンツに使えるようになっています。

たとえば、著者参照を紐付けたいと思うかもしれません。次のようなYAML設定をページヘッダに付け加えると：

```yaml
author:
    name: Sandy Johnson
    twitter: @sandyjohnson
    bio: Sandy is a freelance journalist and author of several publications on open source CMS platforms.
```

Twigで、これらのアクセスできます：

```twig
<section id="author-details">
    <h2>{{ page.header.author.name|e }}</h2>
    <p>{{ page.header.author.bio|e }}</p>
    <span>Contact: <a href="https://twitter.com/{{ page.header.author.twitter|e }}"><i class="fa fa-twitter"></i></a></span>
</section>
```


もし変数名に、[dashのような特殊文字を使いたいときは](https://github.com/getgrav/grav/issues/1957#issuecomment-723236844) 、[Twigのattribute関数](https://twig.symfony.com/doc/1.x/functions/attribute.html) を使ってください:

```twig
attribute(page.header, 'plugin-name').active
```


## Meta Page Headers

メタ・ヘッダにより、それぞれのページに [標準的なHTMLの**<meta>タグ**](http://www.w3schools.com/tags/tag_meta.asp) を設定できます。同様に、[OpenGraph](http://ogp.me/), [Facebook](https://developers.facebook.com/docs/sharing/best-practices) や、 [Twitter](https://dev.twitter.com/cards/overview) も設定できます。

<h4 id="standard-metatag-examples">標準的なmetaタグの例</h4>

```yaml
metadata:
    refresh: 30
    generator: 'Grav'
    description: 'Your page description goes here'
    keywords: 'HTML, CSS, XML, JavaScript'
    author: 'John Smith'
    robots: 'noindex, nofollow'
    my_key: 'my_value'
```

上記の例は、以下のようなHTMLになります：

```twig
<meta name="generator" content="Grav" />
<meta name="description" content="Your page description goes here" />
<meta http-equiv="refresh" content="30" />
<meta name="keywords" content="HTML, CSS, XML, JavaScript" />
<meta name="author" content="John Smith" />
<meta name="robots" content="noindex, nofollow" />
<meta name="my_key" content="my_value" />
```

すべてのHTML5のmetaタグがサポートされています。

#### OpenGraph Metatag examples

```yaml
metadata:
    'og:title': The Rock
    'og:type': video.movie
    'og:url': http://www.imdb.com/title/tt0117500/
    'og:image': http://ia.media-imdb.com/images/rock.jpg
```

上記は、次のようなHTMLに変換されます：

```html
<meta name="og:title" property="og:title" content="The Rock" />
<meta name="og:type" property="og:type" content="video.movie" />
<meta name="og:url" property="og:url" content="http://www.imdb.com/title/tt0117500/" />
<meta name="og:image" property="og:image" content="http://ia.media-imdb.com/images/rock.jpg" />
```

OpenGraphのメタタグの全体的な概要については、[公式ドキュメント](http://ogp.me/) を調べてください。

#### Facebook Metatag examples

```yaml
metadata:
    'fb:app_id': your_facebook_app_id
```

This will produce the HTML:

```html
<meta name="fb:app_id" property="fb:app_id" content="your_facebook_app_id" />
```

Facebook mostly uses OpenGraph metatags, but there are some Facebook-specific tags and these are supported automatically by Grav.

#### Twitter Metatag examples

```yaml
metadata:
    'twitter:card' : summary
    'twitter:site' : @flickr
    'twitter:title' : Your Page Title
    'twitter:description' : Your page description can contain summary information
    'twitter:image' : https://farm6.staticflickr.com/5510/14338202952_93595258ff_z.jpg
```

This will produce the HTML:

```twig
<meta name="twitter:card" property="twitter:card" content="summary" />
<meta name="twitter:site" property="twitter:site" content="@flickr" />
<meta name="twitter:title" property="twitter:title" content="Your Page Title" />
<meta name="twitter:description" property="twitter:description" content="Your page description can contain summary information" />
<meta name="twitter:image" property="twitter:image" content="https://farm6.staticflickr.com/5510/14338202952_93595258ff_z.jpg" />
```

For a full outline of all Twitter metatags that can be used, please consult the [official documentation](https://dev.twitter.com/cards/overview).

This really provides a lot of flexibility and power.

## Frontmatter.yaml

一部のヘビーユーザにとって、便利で高度な機能として、ページフォルダにある`frontmatter.yaml` ファイルを通して、共通のfrontmatter値を使用する機能があります。これは、特に多言語サイトで作業するときに便利です。あるページのすべての言語バージョン間で、フロントマターを共有したい場合があります。

これを利用するためには、ページの`.md` ファイル と一緒に`frontmatter.yaml` ファイルを作り、有効なfrontmatterの値を追加するだけです。たとえば：

```yaml
metadata:
    generator: 'Super Grav'
    description: Give your page a powerup with Grav!
```

> [!Note]  
> frontmatter.yamlと、ページのフロントマターの両方でヘッダが決められている場合、frontmatter.yamlの値が上書きされます。

> [!Warning]  
> frontmatter.yaml の利用は、ファイル側の機能であり、管理プラグインでは **サポートされません** 。



