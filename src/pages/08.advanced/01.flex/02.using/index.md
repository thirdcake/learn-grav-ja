---
title: "Flex オブジェクトを使う"
layout: ../../../../layouts/Default.astro
---

**Flex オブジェクト** は、使いやすく設計されています。ほとんど Twig テンプレートだけで、コレクションやグループをページへ表示できます。

> [!Note]  
> **TIP:** Flex ディレクトリを有効化し、表示するためには、 [ディレクトリの有効化](../01.administration/01.introduction/) をお読みください。

## Using `flex-objects` Page Type

`directories/flex-objects.md` ページで複数の Flex ディレクトリを表示するには：

```text
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

```text
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

```text
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

```text
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

## Rendering Collections and Objects

Both Collections and Objects support rendering their output in HTML. Output can be customized with two parameters: layout and context. Layout allows you to set custom looks, for example you can have list of cards and then more detailed output for details. Context allows you to pass variables to be used in the template files.

```twig
{% render collection layout: 'custom' with { context_variable: true } %}

{% render object layout: 'custom' with { context_variable: true } %}
```

See more detailed documentation: [Render Collection](03.collection/#render) and [Render Object](04.object/#render).

## Templating Basics

Flex templates are located in `templates/flex` folder:

```text
templates/
  flex/
    contacts/
      collection/
        default.html.twig
      object/
        default.html.twig
```

Each type has two folders, one for rendering collection and one for rendering object. The files inside are layouts, named after the filename. In our example, we have `default` layout for both the collection and the object.

### Collection Template

Collection template `flex/contacts/collection/default.html.twig` is responsible for rendering all the objects in the collection. Rendered output is cached by default. Cache key is defined by the collection and the context passed to the `render()` method.

!! **WARNING:** If context contains non-scalar values, caching will be turned off. Try to keep the context as simple as possible!

Here is an example from Contacts Type:
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

! **TIP:** If the rendered HTML has dynamic content, render cache can be disabled from the Twig template by `{% do block.disableCache() %}`.

### Object Template

Object template `flex/contacts/object/default.html.twig` is responsible for rendering a single object. Rendered output is cached by default. Cache key is defined by the object and the context passed to the `render()` method.

!! **WARNING:** If context contains non-scalar values, caching will be turned off. Try to keep the context as simple as possible!

Here is an example from Contacts Type:
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

! **TIP:** If the rendered HTML has dynamic content, render cache can be disabled from the Twig template by `{% do block.disableCache() %}`.

### Custom Layouts

By using custom layouts, you can create an infinite amount of different views into both of your collections and objects.

You can create your custom layouts by just adding a new file next to `default.html.twig` file. The basename of the file is the same as your layout name.

! **TIP:** In collection layouts, it is recommended to call `{% render object layout: 'xxx' %}` instead of outputting the object variables directly into the collection template.

