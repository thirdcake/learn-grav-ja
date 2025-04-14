---
title: "タクソノミー"
layout: ../../../layouts/Default.astro
---

**Grav** では、**タクソノミー** を使って、ページをグループ化したり、タグ付けしたりできます。

> **Taxonomy (general),** the practice and science (study) of classification of things or concepts, including the principles that underlie such classification.  
>
> <cite>Wikipedia</cite>

タクソノミーを使うに当たり、2つのキーポイントがあります：

1. [`site.yaml`](../../01.basics/05.grav-configuration) ファイルに、タクソノミーのリストを定義する
2. 各ページに、適切な `taxonomy` タイプとその値を書く

<h2 id="taxonomy-example">タクソノミーの例</h2>

この概念を説明するために、ひとつの例を挙げます。シンプルなブログを作ってみましょう。そのブログでは、**タグクラウド**を表示するタグ付きの投稿を作る予定です。また、ブログには数人の投稿者（authors）がいて、投稿者ごとに記事を割り当てられるようにします。

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


As you can see in the YAML configuration, each page is assigning **values** to the **taxonomy types** we defined in our user `site.yaml` configuration. This information is used by Grav when the pages are processed and creates an internal **taxonomy map** which can be used to find pages based on the taxonomy you defined.

> [!Warning]  
>  Your pages do not have to use every taxonomy you define in your `site.yaml`, but you must define any taxonomy you use.

In your theme, you can easily display a list of pages that are written by `ksmith` by using `taxonomy.findTaxonomy()` to find them and iterate over them:

```twig
<h2>Kevin Smith's Posts</h2>
<ul>
{% for post in taxonomy.findTaxonomy({'author':'ksmith'}) %}
    <li>{{ post.title|e }}</li>
{% endfor %}
</ul>
```

You can also do sophisticated searches based on multiple taxonomies by using arrays/hashes, for example:

```twig
{% for post in taxonomy.findTaxonomy({'tag':['animal','cat'],'author':'jdoe'}) %}
```

This will find all posts with `tag` set to `animal` **and** `cat` **and** `author` set to `jdoe`. Basically, this will specifically find **Post 2**.

If you need a collection which includes one term **or** the other, just add the `'or'` parameter after the array, example:

```twig
{% for post in taxonomy.findTaxonomy({'tag':['dog','cat']},'or') %}
```

This will find all posts with `tag` set to `dog` **or** `cat`.


## Taxonomy based Collections

We covered this in an earlier chapter, but it is important to remember that you can also use taxonomies in the [page headers](../02.headers) to filter a collection of pages associated with a parent page. If you need a refresher on this subject, please refer back to that [chapter on taxonomy collection headers](../03.collections).

## Adding Custom Taxonomy Values in Default and Options

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

