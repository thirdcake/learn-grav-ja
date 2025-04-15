---
title: "ルーティング"
layout: ../../../layouts/Default.astro
---

[ページ -> フォルダのセクション](../01.content-pages/#folders) の最初の方で説明したとおり、Gravの **ルーティング** は、主にサイトのコンテンツを作る際のフォルダ構造によって決まります。

しかしもう少し柔軟性が欲しいときもあります。この点について、より便利になるように、Gravは多様なツールと設定オプションが用意されています。

ここで、他のCMSからGravへサイト移転する状況を思い浮かべてください。新しいサイトを立ち上げるのに、いくつかの選択肢があります。

1. フォルダ構造を一致させることで、古いサイトと同じURLを複製する
2. 新サイトは好きなように作成し、webサーバーの「リライト」機能で、古いURLから新しいURLへリダイレクトする 
3. 新サイトは好きなように作成し、Gravの設定で、古いURLから新しいURLへリダイレクトする

フォルダ構造とは違うURLに対応させたい状況は、ほかにもたくさんあるでしょう。Gravでは、その目的を達成するための以下のような機能があります。

<h2 id="page-level-route-and-redirect-overrides">ページレベルのルーティングとリダイレクト</h2>

[ヘッダー -> Routesのセクション](../02.headers/#routes) で概要を示したとおり、**default ルーティング設定** や、**ルーティングのaliases** の配列により、 ルーティングのオプションを明示することができます。

```yaml
routes:
  default: '/my/example/page'
  canonical: '/canonical/url/alias'
  aliases:
    - '/some/other/route'
    - '/can-be-any-valid-slug'
```

これらは、ページごとに処理され、キャッシュされます。そしてこれらは、 **素のルーティング** と一緒に扱われます。素のルーティングとは、Gravがデフォルトで機能する、ページ階層をもとにした **スラッグ** を基礎とするルーティングのことです。よって、カスタムのルーティングを提供したとしても、**素のルーティング** はそのまま利用可能です。

ページレベルのルーティングと似ていますが、Gravではページのフロントマターに対象ページを指定することで、ページレベルのリダイレクトに対応しています。より詳しくは、[ヘッダー -> Redirectのセクション](../02/headers/#redirect) をご覧ください。

```yaml
redirect: '/some/custom/route[303]'
```

<h2 id="site-level-routes-and-redirects">サイトレベルのルーティングとリダイレクト</h2>

Gravには、強力な正規表現ベースの **別名ルーティング** 機能と、あるページから別のページへの **リダイレクト** 機能が備わっています。この機能は、Gravへサイトを引っ越しするような場面で、古いURLを新サイトでも使わなくては行けない場合に、特に便利です。これはしばしば、webサーバの **rewrite rules** によって達成されることですが、ときには、Gravの制御でやってしまったほうが、便利で柔軟であることがあります。

これらは、[サイト設定](../../01.basics/05.grav-configuration/#site-configuration) によって設定します。Gravは、サンプル設定として `system/config/site.yaml` を提供しますが、`user/config/site.yaml` を編集することで、これらを上書きし、独自の設定を付け加えることができます。

> [!Info]  
> （多言語サイトとする場合）すべてのリダイレクトするルールは、言語部分の後から始まるスラッグ部分に適用されます。

> [!Warning]  
> 特定の文字は、あらゆるルーティングにおいて、エスケープされなければいけません。このことは、サイトを引っ越すときに、古いサイトが伝統的なファイル拡張子を利用していた場合（たとえば`.php`）や、URLパラメータを利用していた場合（たとえば`?foo=bar`）に、とくに重要です。これらの例では、ピリオドやクエスチョンマークは、`/index\.php\?foo=bar: '/new/location'` というように、**エスケープしなければいけません** 。

<h3 id="route-aliases">ルーティングの別名</h3>

<h4 id="simple-aliases">シンプルな別名</h4>

最も基本的な別名の種類は、1対1になるように対応させるものです。`site.yaml` ファイルの `routes: ` セクションでは、別名と、実際に使われるルーティングを対応させたリストを作成できます。

> [!Info]  
> これらの別名は、そのルーティングファイルが見つからなかったときのみ利用されることに注意してください。

```yaml
routes:
  /something/else: '/blog/focus-and-blur'
```

もし、`http://mysite.com/something/else` にリクエストが来て、そしてそれにデフォルトで対応するページが無い場合に、このルーティングは、`/blog/focus-and-blur` にあるページに決定されます。これは実質的には閲覧者をこのページへ **リダイレクトした** のではありません。単に、別名のページを表示しただけです。

> [!Info]  
> The indentation is key here, without it the route redirect will not work. 

<h4 id="regex-based-aliases">正規表現による別名</h4>

別名によるリダイレクトのより高度な種類では、別名の一部にシンプルな **正規表現** を使うことができます。たとえば：

```yaml
routes:
   /another/(.*): '/blog/$1'
```

これは別名からワイルドカードでルーティングします。よって、`http://mysite.com/another/focus-and-blur` というリクエストのとき、実際は`/blog/focus-and-blur` にあるページが表示されます。これはひとつのURLの集合を別のところへ組み合わせるのに、パワフルな方法です。WordPressからGravへの移動にもとても良いでしょう:)

すべての別名を集めて、特定のルーティングに対応させることもできます：

```yaml
routes:
  /one-ring/(.*): '/blog/sunshine-in-the-hills'
```

上記の別名ルーティングでは、`/one-ring/to-rule-them-all` や `/one-ring/is-mine.html` のようなワイルドカードにマッチするすべてのURLについて、`/blog/sunshine-in-the-hills` のルーティングのページからコンテンツが表示されます。

よりクリエイティブに、複数対応させたりや、任意の正規表現の構文を使うこともできます。

```yaml
routes:
  /complex/(category|section)/(.*): /blog/$1/folder/$2
```

これは、次のように書き換えられます：

```txt
/complex/category/article-1      -> /blog/category/folder/article-1
/complex/section/article-2.html  -> /blog/section/folder/article-2.html
```

このルーティングは、`complex/category` や、`complex/section` で始まっていないものには適合しません。より詳しい情報は、[Regexr.com](https://regexr.com/) で、正規表現を学び、テストする素晴らしい方法を提供しています。

<h3 id="redirects">リダイレクト</h3>

**別名によるルーティング** の別系統の選択肢としては、**リダイレクト** によるものがあります。これらは似ていますが、URLがそのままでコンテンツのみ別名のページから持ってくるのではなく、ブラウザを対応したページへリダイレクトさせる点が違います。

リダイレクトするため、システムレベルでは3つの設定オプションがあります：

```yaml
pages:
  redirect_default_route: false
  redirect_default_code: 302
  redirect_trailing_slash: true
```

* `redirect_default_route` enables Grav to automatically redirect to the page's default route.
* `redirect_default_code` デフォルトのHTTPリダイレクトコードを指定できます：
    * **301**: 恒久的なリダイレクトです。 Clients making subsequent requests for this resource should use the new URI. Clients should not follow the redirect automatically for POST/PUT/DELETE requests.
    * **302**: Redirect for undefined reason. Clients making subsequent requests for this resource should not use the new URI. Clients should not follow the redirect automatically for POST/PUT/DELETE requests.
    * **303**: Redirect for undefined reason. Typically, 'Operation has completed, continue elsewhere.' Clients making subsequent requests for this resource should not use the new URI. Clients should follow the redirect for POST/PUT/DELETE requests.
    * **307**: 一時的なリダイレクトです。 Resource may return to this location at a later point. Clients making subsequent requests for this resource should use the old URI. Clients should not follow the redirect automatically for POST/PUT/DELETE requests.
* `redirect_trailing_slash` option lets you redirect to a non-trailing slash version of the current URL

たとえば：

```yaml
redirects:
    /jungle: '/blog/the-urban-jungle'
```

URL部分に各カッコ`[]` で囲むことで、リダイレクトコードを指定することもできます：

```yaml
redirects:
    /jungle: '/blog/the-urban-jungle[303]'
```

If you were to point your browser to `http://mysite.com/jungle`, you would actually get redirected and end up on the page: `http://mysite.com/blog/the-urban-jungle`.

The same regular expression capabilities that exist for Route Aliases, also exist for Redirects.  For example:

```yaml
redirects:
    /redirect-test/(.*): /$1
    /complex/(category|section)/(.*): /blog/$1/folder/$2
```

These look almost identical to the Route Alias version, but instead of transparently showing the new page, Grav actually redirects the browser and loads the new page specifically.

<h2 id="hiding-the-home-route">ホームへのルーティングを隠す</h2>

`system.yaml` ファイルに、サイトのホームとしたいページをセットできます：

```yaml
home:
  alias: '/home'
```

`/` へのルーティングを、このページの別名として設定しています。つまり、`/` へのリクエストがあったとき、Gravはそのページを表示します。

しかしながら、Gravはこのホームページ以下のページについては、何もしません。このため、ブログ投稿の一覧を表示するページとして、`/blog` というページがあり、このページをホームページに設定したとき、これは期待通り動きます。しかし、ブログ投稿のリンクをクリックすると、URLは `/blog/my-blog-post` になります。これは期待される通りのふるまいですが、あなたが意図するところとは違うかもしれません。`system.yaml` によって、このURLから、トップレベルの`/blog` を隠す選択ができます。

このようなふるまいは、次のように変更できます：

```yaml
home:
  hide_in_urls: true
```

