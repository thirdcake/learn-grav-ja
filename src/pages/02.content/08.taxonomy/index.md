---
title: "タクソノミー"
layout: ../../../layouts/Default.astro
---

**Grav** では、**タクソノミー** を使って、ページをグループ化したり、タグ付けしたりできます。

> **Taxonomy (general),** the practice and science (study) of classification of things or concepts, including the principles that underlie such classification.  
>
> <cite>Wikipedia</cite>  
> 分類学（一般）、物事や概念の分類の実践と科学（研究）、そのような分類の根底にある原理を含む。

タクソノミーを使うに当たり、2つのキーポイントがあります：

1. [`site.yaml`](../../01.basics/05.grav-configuration) ファイルに、タクソノミーのリストを定義する
2. 各ページに、適切な `taxonomy` タイプとその値を書く

<h2 id="taxonomy-example">タクソノミーの例</h2>

この概念を説明するために、ひとつの具体例を挙げます。シンプルなブログを例として作ってみましょう。そのブログでは、**タグクラウド** を表示するタグ付きの投稿を作る予定です。また、ブログには数人の投稿者（authors）がいて、投稿者ごとに記事を割り当てられるようにします。

このようなことは、Gravを使えばかんたんにできます。Gravには、`system/config/` フォルダに、`site.yaml` ファイルがあります。デフォルトでは、`category` と `tag` という2つのタクソノミータイプが定義されています。

```yaml
taxonomies: [category,tag]
```

`tag` はすでに設定されていますから、あとは `authors` を追加する必要があります。そのためには、`user/config/` フォルダに、新たに`site.yaml` ファイルを作成し、次のように入力します：

```yaml
taxonomies: [category,tag,author]
```

これにより、タクソノミーが上書きされます。そして、ページは3つのタクソノミーで分類されます。

次のステップでは、これらのタクソノミータイプを使ったページを作成します。たとえば、次のようなページを作ってみましょう：

```markdown
---
title: Post 1
taxonomy:
    tag: [animal, dog]
    author: ksmith
---

Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
```

...そして、他のページは、次のようになっています：

```markdown
---
title: Post 2
taxonomy:
    tag: [animal, cat]
    author: jdoe
---

Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
```

フロントマターのYAML設定部分を見てもらえばわかるとおり、`site.yaml` の設定ファイルで定義した **タクソノミータイプ** に、**値** を代入しています。Gravは、この情報をページ処理中に使い、タクソノミーごとに分類するための内部的な **タクソノミーマップ** を作ります。

> [!Warning]  
> 各ページでは、`site.yaml` に定義したタクソノミーをすべて使う必要はありません。ただし、使いたいタクソノミーは、`site.yaml` で定義されている必要があります。

テーマファイルでは、`ksmith` によって書かれたページの一覧を、かんたんに表示できます。`taxonomy.findTaxonomy()` 関数を使って、対象ページを探し、繰り返し処理をします：

```twig
<h2>Kevin Smith's Posts</h2>
<ul>
{% for post in taxonomy.findTaxonomy({'author':'ksmith'}) %}
    <li>{{ post.title|e }}</li>
{% endfor %}
</ul>
```

また、より複雑な検索もできます。複数のタクソノミーをもとに、array/hashを用いて、次のようにできます：

```twig
{% for post in taxonomy.findTaxonomy({'tag':['animal','cat'],'author':'jdoe'}) %}
```

上記の例は、`tag` に`animal` **及び** `cat` が定義され、**その上で** `author` に `jdoe` が設定されているすべての投稿を探します。先ほど示したブログ投稿の **Post 2** の記事がヒットします。

もし、「ひとつの用語 **もしくは** 別の用語」にヒットするページのコレクションが欲しいときは、ただ `'or'` パラメータを配列の後に追記してください。次のように：

```twig
{% for post in taxonomy.findTaxonomy({'tag':['dog','cat']},'or') %}
```

上記の例では、`tag` が `dog` **もしくは** `cat` になっている投稿が探されます。


<h2 id="taxonomy-based-collections">タクソノミーベースのコレクション</h2>

以前の章で解説したことですが、重要なのでもう一度いいます。[ページのフロントマター](../02.headers) にあるタクソノミーを使って、ページのコレクションをフィルタすることができます。[コレクションページのタクソノミーコレクション](../03.collections/#taxonomy-collections) へ戻ってみてください。

<h2 id="adding-custom-taxonomy-values-in-default-and-options">カスタムタクソノミーの追加</h2>

You can use the format below in blueprints to override the `Default` and/or `Options` taxonomies. An important note here is that if you are using this method to override both of these attributes, you should add `validate: type: commalist`, otherwise it may not function as desired.

```yaml
taxonomies:
  fields:
    header.taxonomy:
      default:
        category: ['blog','page']
        tag: ['test']
      options:
        category: ['grav']
      validate:
        type: commalist
```

