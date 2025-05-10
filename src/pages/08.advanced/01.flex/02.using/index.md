---
title: "Flex オブジェクトを使う"
layout: ../../../../layouts/Default.astro
---

**Flex オブジェクト** は、使いやすく設計されています。ほとんど Twig テンプレートだけで、コレクションやグループをページへ表示できます。

> [!Note]  
> **TIP:** Flex ディレクトリを有効化し、表示するためには、 [ディレクトリの有効化](../01.administration/01.introduction/) をお読みください。

<h2 id="using-flex-objects-page-type"> flex-objects ページタイプの使用</h2>

`directories/flex-objects.md` ページで複数の Flex ディレクトリを表示するには：

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

または、各 Flex ディレクトリにそれぞれのパラメータを渡すこともできます：

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

`contacts/flex-objects.md` ページで、ひとつの Flex ディレクトリを表示するには：

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

`my-contact/flex-objects.md` ページで、ひとつの Flex オブジェクトを表示するには：

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

デフォルトでは、 `flex-objects` ページタイプは、2つの URL パラメータ（ **directory** と **id** ）を取ります。これらは、ディレクトリへナビゲートするのに使われます。たとえば、 URL は次のようになります。

```text
https://www.domain.com/directories/directory:contacts/id:ki2ts4cbivggmtlj

https://www.domain.com/contacts/id:ki2ts4cbivggmtlj
```

> [!Note]  
> **TIP:** `flex` 内で独自のパラメータを渡すことができ、自身のコレクションのテンプレートファイルや、オブジェクトテンプレートファイルで利用できます。

<h2 id="rendering-collections-and-objects">コレクションとオブジェクトのレンダリング</h2>

コレクションとオブジェクトはどちらも、 HTML での出力に対応しています。出力は、 レイアウトとコンテキストという2つのパラメータでカスタマイズされます。レイアウトにより、見た目をカスタマイズできます。たとえば、カードのリストを表示し、より詳しくは詳細ページを出力する、というように。そして、コンテキストにより、テンプレートファイルで利用する変数を渡すことができます。

```twig
{% render collection layout: 'custom' with { context_variable: true } %}

{% render object layout: 'custom' with { context_variable: true } %}
```

詳しくは、ドキュメントをお読みください： [コレクションのレンダリング](03.collection/#render) と、 [オブジェクトのレンダリング](04.object/#render)

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

各タイプには、2つのフォルダがあり、1つはコレクションのレンダリング用、もうひとつはオブジェクトのレンダリング用です。中のファイルはレイアウトで、ファイル名に基づいて名前が付けられています。上記の例では、コレクションとオブジェクトの両方に対する `default` レイアウトがあります。

<h3 id="collection-template">コレクションのテンプレート</h3>

コレクションテンプレートの `flex/contacts/collection/default.html.twig` は、コレクション内のすべてのオブジェクトのレンダリングに対応します。レンダリング出力は、デフォルトでキャッシュされます。キャッシュキーは、コレクションと `render()` メソッドに渡すコンテキストによって定義されます。

> [!Info]  
> **WARNING:** コンテキストにスカラー値以外の値が含まれている場合、キャッシュは無効になります。コンテキストはできるだけシンプルに保ってください！

以下は、 Contacts タイプの例です：

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

> [!Note]  
> **TIP:** レンダリングされた HTML に動的コンテンツを含む場合、Twig テンプレートで `{% do block.disableCache() %}` により、キャッシュを無効にすることができます。

<h3 id="object-template">オブジェクトのテンプレート</h3>

オブジェクトテンプレートの `flex/contacts/object/default.html.twig` は、1つのオブジェクトのレンダリングに対応します。レンダリング出力は、デフォルトでキャッシュされます。キャッシュキーは、オブジェクトと `render()` メソッドに渡すコンテキストによって定義されます。

> [!Info]  
> **WARNING:** コンテキストにスカラー値以外の値が含まれている場合、キャッシュは無効になります。コンテキストはできるだけシンプルに保ってください！

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

> [!Note]  
> **TIP:** レンダリングされた HTML に動的コンテンツを含む場合、Twig テンプレートで `{% do block.disableCache() %}` により、キャッシュを無効にすることができます。

<h3 id="custom-layouts">カスタムレイアウト</h3>

カスタムレイアウトを使用すると、コレクションとオブジェクトの両方に、無限に異なる見た目を作成できます。

カスタムレイアウトを作成するには、ただ新しいファイルを `default.html.twig` と同じフォルダに追加するだけです。ファイルのベース名は、レイアウト名と同じです。

> [!Note]  
> **TIP:** コレクションレイアウトでは、オブジェクト変数をコレクションテンプレートに直接出力するのではなく、 `{% render object layout: 'xxx' %}` を呼び出すことをおすすめします。

