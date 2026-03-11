---
title: 'Flex オブジェクトを使う'
layout: ../../../../layouts/Default.astro
lastmod: '2025-09-06'
description: 'Flex Objects をサイトに表示する方法について、ページでの設定方法や、レイアウトファイルの配置方法など、使い方の概要を解説します。'
---

**Flex オブジェクト** は、使いやすく設計されています。  
ほとんど Twig テンプレートだけで、コレクションやグループをページへ表示できます。

> [!Tip]  
> Flex ディレクトリを有効化し、表示するためには、 [ディレクトリの有効化](../01.administration/01.introduction/) をお読みください。

<h2 id="using-flex-objects-page-type">flex-objects ページタイプの使用</h2>

`directories/flex-objects.md` という、ひとつのページで、複数の Flex ディレクトリを表示する方法:

```markdown
title: Directories
flex:
  layout: default
  list:
  - contacts
  - services
---

# Directories
```

または、各 Flex ディレクトリにそれぞれのパラメータを渡すこともできます:

```markdown
title: Directories
flex:
  layout: default
  directories:
    contacts:
      collection:
        title: '{{ directory.title }}'
        layout: default
        object:
          layout: list-default
      object:
        title: 'Contact: {{ object.first_name }} {{ object.last_name }}'
        layout: default
    services:
---

# Directories
```

`contacts/flex-objects.md` ページで、ひとつの Flex ディレクトリを表示する方法:

```markdown
title: Contacts
flex:
  directory: contacts
  collection:
    title: '{{ directory.title }}'
    layout: default
    object:
      layout: list-default
  object:
    title: 'Contact: {{ object.first_name }} {{ object.last_name }}'
    layout: default
---

# Contacts
```

`my-contact/flex-objects.md` ページで、ひとつの Flex オブジェクトを表示する方法:

```markdown
title: Contact
flex:
  directory: contacts
  id: ki2ts4cbivggmtlj
  object:
    title: 'Contact: {{ object.first_name }} {{ object.last_name }}'
    layout: default
---

# Contacts
```

> [!訳注]  
> 上記2つは、よく似ていますが、後者は、 URL が `my-contact` になっており、 id が指定されているので、コレクションではなく、いきなり単一のオブジェクトを表示する点が違います。そして、前者も、以下のように URL パラメータで id が指定されれば、(そして、 object.layout が同じなら) 同じ表示になるはずです。

デフォルトでは、 `flex-objects` ページタイプは、2つの URL パラメータ（ **directory** と **id** ）を受け取ります。  
これらは、ディレクトリへ、ナビゲーションするのに使われます。  
たとえば、 URL は次のようになります。

```text
https://www.domain.com/directories/directory:contacts/id:ki2ts4cbivggmtlj

https://www.domain.com/contacts/id:ki2ts4cbivggmtlj
```

> [!Tip]  
> `flex` 内で、独自のパラメータを渡すこともでき、自身のコレクションのテンプレートファイルや、オブジェクトテンプレートファイルで利用できます。

<h2 id="rendering-collections-and-objects">コレクションとオブジェクトのレンダリング</h2>

コレクションとオブジェクトはどちらも、 HTML での出力に対応しています。  
出力は、 レイアウトとコンテキストという、2つのパラメータでカスタマイズされます。  
レイアウトにより、見た目をカスタマイズできます。  
たとえば、カードの見た目で、コレクション一覧を表示し、より詳しくは、詳細ページを出力する、というような使い方ができます。  
そして、 context により、テンプレートファイルで利用する変数を渡すことができます。

```twig
{% render collection layout: 'custom' with { context_variable: true } %}

{% render object layout: 'custom' with { context_variable: true } %}
```

詳しくは、ドキュメントをお読みください： [コレクションのレンダリング](./03.collection/#render) と、 [オブジェクトのレンダリング](./04.object/#render)

<h2 id="templating-basics">テンプレートの基本</h2>

Flex テンプレートは、 `templates/flex` フォルダにあります：

```text
templates/
  flex/
    contacts/
      collection/
        default.html.twig
      object/
        default.html.twig
```

各タイプには、2つのフォルダがあり、1つはコレクションのレンダリング用、もうひとつはオブジェクトのレンダリング用です。  
中のファイルはレイアウトで、ファイル名に基づいて名前が付けられています。  
上記の例では、コレクションとオブジェクトの両方に対する `default` レイアウトがあります。

<h3 id="collection-template">コレクションのテンプレート</h3>

コレクションテンプレートの `flex/contacts/collection/default.html.twig` は、コレクション内のすべてのオブジェクトのレンダリングに対応します。  
レンダリング出力は、デフォルトでキャッシュされます。  
キャッシュキーは、コレクションと `render()` メソッドに渡すコンテキストによって定義されます。

> [!Warning]  
> コンテキストにスカラー値以外の値が含まれている場合、キャッシュは無効になります。コンテキストはできるだけシンプルに保ってください！

以下は、 Contacts タイプの例です:

```twig
<div id="flex-objects">
  <div class="text-center">
    <input class="form-input search" type="text" placeholder="Search by name, email, etc" />
    <button class="button button-primary sort asc" data-sort="name">
      Sort by Name
    </button>
  </div>

  <ul class="list">
    {% for object in collection.filterBy({ published: true }) %}
      <li>
        {% render object layout: layout with { options: options } %}
      </li>
    {% endfor %}
  </ul>
</div>

<script>
    var options = {
        valueNames: [ 'name', 'email', 'website', 'entry-extra' ]
    };
    var flexList = new List('flex-objects', options);
</script>
```

> [!Tip]  
> レンダリングされた HTML に、動的コンテンツを含む場合、 Twig テンプレートで `{% do block.disableCache() %}` を実行することにより、キャッシュを無効にすることができます。

<h3 id="object-template">オブジェクトのテンプレート</h3>

オブジェクトテンプレートの `flex/contacts/object/default.html.twig` は、1つのオブジェクトのレンダリングに対応します。  
レンダリング出力は、デフォルトでキャッシュされます。  
キャッシュキーは、オブジェクトと `render()` メソッドに渡すコンテキストによって定義されます。

> [!Warning]  
> コンテキストにスカラー値以外の値が含まれている場合、キャッシュは無効になります。コンテキストはできるだけシンプルに保ってください！

以下は、 Contacts タイプの例です：

```twig
<div class="entry-details">
    {% if object.website %}
        <a href="{{ object.website|e }}"><span class="name">{{ object.last_name|e }}, {{ object.first_name|e }}</span></a>
    {% else %}
        <span class="name">{{ object.last_name|e }}, {{ object.first_name|e }}</span>
    {% endif %}
    {% if object.email %}
        <p><a href="mailto:{{ object.email|e }}" class="email">{{ object.email|e }}</a></p>
    {% endif %}
</div>
<div class="entry-extra">
    {% for tag in object.tags %}
        <span>{{ tag|e }}</span>
    {% endfor %}
</div>
```

> [!Tip]  
> レンダリングされた HTML に、動的コンテンツを含む場合、 Twig テンプレートで `{% do block.disableCache() %}` を実行することにより、キャッシュを無効にすることができます。

<h3 id="custom-layouts">カスタムレイアウト</h3>

カスタムレイアウトを使用すると、コレクションとオブジェクトの両方に、無限に異なる見た目を作成できます。

カスタムレイアウトを作成するには、ただ新しいファイルを `default.html.twig` と同じフォルダに追加するだけです。  
ファイルのベース名は、レイアウト名と同じです。

> [!Tip]  
> コレクションレイアウトでは、オブジェクト変数をコレクションテンプレートに直接書いて、出力するのではなく、 `{% render object layout: 'xxx' %}` を呼び出して、オブジェクトレイアウトに書いておくことをおすすめします。

