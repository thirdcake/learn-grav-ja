---
title: "ブログを構築する"
layout: ../../../../layouts/Default.astro
---

> [!Info]  
> [https://getgrav.org/downloads/skeletons](https://getgrav.org/downloads/skeletons) からブログサイト用のスケルトンをダウンロードし、インストールするか、最低でも [https://github.com/getgrav/grav-skeleton-blog-site](https://github.com/getgrav/grav-skeleton-blog-site) リポジトリをチェックしておいてください。このサンプルサイトは、Antimatter テーマを使います。すでにブログ構造で機能している Grav サイトがあれば、行き詰まったときや、次に何をすれば良いかわからなくなったときに、助けになるでしょう。

<h2 id="check-your-theme-provides-the-blog-and-item-page-templates">テーマがブログとアイテムページのテンプレートを提供しているかチェックしましょう</h2>

シンプルに始めましょう：ブログページテンプレートをすでに提供しているテーマを選んでください。たとえば、 Antimatter, TwentyFifteen, Deliver, Lingonberry, Afterburner2, など他にも多数あります。  
そのテーマがブログページテンプレートをすでに提供しているか、どうやってチェックしたら良いでしょうか？ `/user/themes/[あなたのテーマ]/templates` フォルダで、`blog.html.twig` ファイルや、 `item.html.twig` ファイルが存在しているか、チェックしてください。

テーマをすでに選んでおり、これらのファイルを含んでいなければ、そのときは Antimatter テーマからそれらをコピーしてください： [https://github.com/getgrav/grav-theme-antimatter/tree/develop/templates](https://github.com/getgrav/grav-theme-antimatter/tree/develop/templates) （このテーマからコピーするときは、2つの部分的なテンプレートも必要になります。名前は、`blog_item.html.twig` と、 `sidebar.html.twig` です。これらの部分的なテンプレートは、`templates/partials` フォルダにあります。）

あなたのテーマに合わせて、マークアップを微調整する必要があるかもしれません。これから始めるのであれば、最良の方法はすでにこれらのテンプレートが提供されているテーマを使うことです。

<h2 id="create-the-blog-pages-structure">ブログのページ構造を作りましょう</h2>

ページ構造を作る方法は、いくつかあります。デフォルトでよりシンプルなの方法は、Blog タイプの親ページを作り、ブログ投稿ページをその子ページとして作ります。

<h3 id="with-the-admin-plugin">管理パネルプラグインで</h3>

Blog タイプのページを作ってください。そのページは、ブログの "ホームページ" になり、ブログ投稿の一覧が表示されます。

`Item` タイプの子ページを1つ以上作ってください。これらが、ブログの投稿ページです。

<h3 id="manually">手作業で</h3>

pages/ フォルダに入ってください。`01.blog` ページを作ってください（あなたのメニュー構造によって、数字部分は変更してください）。そして、`blog.md` ファイルをそこに入れてください。  
このファイルは、次のようなコンテンツになります：

```yaml
---
content:
    items: '@self.children'
---
```

これにより、 Grav はサブページ（ブログの投稿ページ）を繰り返します。

付け加えたい投稿数分のサブフォルダを作ってください。そして、そのそれぞれのフォルダに `item.md` ファイルを追加し、そこにブログ投稿のコンテンツを書いてください。

## URLs

上記で説明したフォルダ構造だと、ブログ投稿の URL に `/blog/` が入ります。これはもしかしたら、不要かもしれません。たとえば：あなたのサイトにブログだけがあれば良く、ブログ投稿の一覧がホームページである場合です。このようなケースでは、ルートドメインだけがそのコンテンツに欲しいもので、子ディレクトリは閲覧者にとって不要です。

この場合、 system.yaml （管理パネルでは System 設定）で、 `home.hide_in_urls` オプション（管理パネルでは URLs の Hide Home ）を true にしてください。

## The inner workings

どのように機能するか、知りたいかもしれません。 テーマの `templates/` フォルダにある `blog.html.twig` ファイルのコンテンツが Blog のテンプレートで、これは単純に子ページを繰り返します。

もっとの簡単な方法は：

```twig
{% set collection = page.collection() %}

{% for child in collection %}
        {% include 'partials/blog_item.html.twig' with {'blog':page, 'page':child, 'truncate':true} %}
{% endfor %}
```

デフォルトの page.collection() は、ページの YAML フロントマターから `content.items` プロパティをピックアップします。そして、その定義に合う要素の配列を返します。

もしページに次のようなフロントマターがあれば：

```yaml
---
content:
    items: '@self.children'
---
```

このときは、 `collection` は、当該ページのサブページの配列となります。

今回の場合では、テーマは、ひとつのブログ投稿をレンダリングする部分的なテンプレート `partials/blog_item.html.twig` を含み、レンダリングする実際のブログ投稿を含む `子` オブジェクトを渡します。

<h3 id="to-learn-more">より詳しく学ぶには</h3>

- コレクション： [/content/collections/](../../../02.content/03.collections/)
- リストページ： [/content/content-pages/#listing-page](../../../02.content/01.content-pages/#listing-page)
- フォルダ： [/content/content-pages/#folders](../../../02.content/01.content-pages/#folders)
- タクソノミー： [/content/taxonomy/#taxonomy-example](../../../02.content/08.taxonomy/#taxonomy-example)

