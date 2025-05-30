---
title: 拡張
layout: ../../../layouts/Default.astro
lastmod: '2025-05-28'
---
このページでは、管理パネルの拡張方法や、その際のベストプラクティスをガイドします。

<h3 id="understanding-admin-themes">管理パネルテーマを理解する</h3>

普通の Grav テーマを拡張したり、修正したりするのとちょうど同じように、管理パネルの構造や見た目をテンプレートで上書きできます。つまり、デフォルトのテンプレートの代わりに、あなたのプラグインで定義したテンプレートで、管理パネルのテーマを表示できます。たとえば、左側にあるサイドバーナビゲーションのアバターを変更したいと思ったら、 `nav-user-avatar.html.twig` を変更することで可能です。

管理パネルプラグインでは、テンプレートへの path は： `user/plugins/admin/themes/grav/templates` のフォルダ以下に、 *ADMIN_TEMPLATES* として参照されます。見つけたいファイルは、 `ADMIN_TEMPLATES/partials/nav-user-avatar.html.twig` にある、 `<img src="https://www.gravatar.com/avatar/{{ admin.user.email|md5 }}?s=47" />` です。

あなたのプラグインの中では、テンプレートへの path は： `user/plugins/myadminplugin/admin/themes/grav/templates` のフォルダ以下に、 *PLUGIN_TEMPLATES* として参照されます。対応ファイルは、 `PLUGIN_TEMPLATES/partials/nav-user-avatar.html.twig` であり、 `<img src="{{ myadminplugin_avatar_image_path }}" />` のような内容になります。

このように、テンプレートの path を、非破壊的に上書きします。関連するテンプレートだけを対象とします。不要なテンプレートを上書きしてしまったり、他の管理テーマが同じ用途で代替テンプレートを登録してしまうのを防ぎます。そのため、次のようにプラグインに path を登録します：

```php
public static function getSubscribedEvents(): array
{
    return [
        'onAdminTwigTemplatePaths' => ['onAdminTwigTemplatePaths', 0]
    ];
}

public function onAdminTwigTemplatePaths($event): void
{
    $paths = $event['paths'];
    $paths[] = __DIR__ . '/admin/themes/grav/templates';
    $event['paths'] = $paths;
}
```

重要なことなので忘れないでほしいのですが、管理プラグインで使われるテーマは、利用可能なテンプレートに強く影響されます。一般論として、テンプレート修正は *インパクトの少ない* 変更に留めるべきで、あなたのプラグインをインストールしたユーザーのインターフェースを壊すことの無いようにしてください。
この意味では、 `nav.html.twig` よりも `nav-user-avatar.html.twig` を上書きした方が良いでしょう。 `nav.html.twig` の方が機能が豊富ですが、 `{% include 'partials/nav-user-details.html.twig' %}` を使って、 `nav-user-avatar.html.twig` をインクルードしているからです。

> [!訳注]  
> `nav-user-avatar.html.twig` は、 `nav-user-details.html.twig` のタイポかな？ と思います。

> [!Tip]  
> 管理パネルのテンプレートファイルは、自動エスケープが有効になっています。HTML コンテンツのエスケープのために `|e` フィルターを追加する必要はありません。しかし、適正な HTML を入力するには `|raw` を追加する必要はあります。

<h3 id="adding-a-custom-field">カスタムフィールドを追加</h3>

カスタムフィールドを作成するために、 `PLUGIN_TEMPLATES/forms/fields/myfield` へ追加します。
*myfield* フォルダには、その入力欄の処理方法を決める Twig テンプレートが必要です。
フィールドを追加する最も簡単な方法は、 `ADMIN_TEMPLATES/forms/fields` から似ているフィールドを探し、それをコピーし、その構造を理解することです。
たとえば、 HTML range スライダーを追加するには、 `PLUGIN_TEMPLATES/forms/fields/range/range.html.twig` を作成します。このファイルに、以下を記入します：

```twig
{% extends "forms/field.html.twig" %}

{% block input_attributes %}
    type="range"
    {% if field.validate.min %}min="{{ field.validate.min }}"{% endif %}
    {% if field.validate.max %}max="{{ field.validate.max }}"{% endif %}
    {% if field.validate.step %}step="{{ field.validate.step }}"{% endif %}
    {{ parent() }}
{% endblock %}
```

ここでは、 "range" という名前のフィールドタイプを、 *range* の input type で追加しています。これにより、ユーザーは [ボタンをスライドすることで](http://www.html5tutorial.info/html5-range.php) 値を選択できます。
ブループリントで新しいフィールドを使うには、 [*blueprints.yaml*](../../04.plugins/03.plugin-tutorial/#required-items-to-function) に以下を追加するだけです：

```yaml
form:
  fields:
    radius:
      type: range
      label: Radius
      id: radius
      default: 100
      validate:
        min: 0
        max: 100
        step: 10
```

これにより、デフォルト値が100で、0から100の値を取りうる、ステップが10のスライダーが提供されます。

このフィールドは、 `prepend` や `append` ブロック利用して、さらに拡張できます。たとえば、選択した値について、視覚的に見えるインジケーターを追加することができます。
次のように、 `range.html.twig` を変更します：

```twig
{% extends "forms/field.html.twig" %}

{% block input_attributes %}
    type="range"
    style="display: inline-block;vertical-align: middle;"
    {% if field.id is defined %}
        oninput="{{ field.id }}_output.value = {{ field.id }}.value"
    {% endif %}
    {% if field.validate.min %}min="{{ field.validate.min }}"{% endif %}
    {% if field.validate.max %}max="{{ field.validate.max }}"{% endif %}
    {% if field.validate.step %}step="{{ field.validate.step }}"{% endif %}
    {{ parent() }}
{% endblock %}
{% block append %}
  {% if field.id is defined %}
    <output
        name="{{ (scope ~ field.name)|fieldName }}"
        id="{{ field.id }}_output"
        style="display: inline-block;vertical-align: baseline;padding: 0 0.5em 5px 0.5em;"
    >
    {{ value|join(', ')|e('html_attr') }}
    </output>
  {% endif %}
{% endblock append %}
```

このように、 `<output>` タグを追加し、選択した値を保持します。そしてこのタグとフィールド自体に、それらをきれいに並べるための、シンプルなスタイルを追加します。また、フィールドに `oninput` 属性を追加することで、値が変化すると自動で `<output>` タグ内の値が更新されるようになります。このためには、 range-slider を利用する各フィールドに、一意の `id` プロパティを持つ必要があります。上記の例で `id: radius` としたように。これは競合を避けるため、 `id: myadminplugin_radius` のようにすべきです。

> [!Info]  
> この新しいテンプレートがフロントエンドと管理パネル（たとえば、 `PLUGIN_TEMPLATES` フォルダで利用）で共有される場合、すべての変数を `|e` でエスケープする必要があります。かわりに、 `Configuration` > `Twig Templating` > `Autoescape variables` で、 `Yes` にすることもできます。

<h3 id="creating-custom-page-templates">カスタムページテンプレートを作成</h3>

[テーマの基本](../../03.themes/01.theme-basics/#content-pages-twig-templa) で言及したとおり、 Grav 内の **pages** 間には直接的な結びつきがあり、 **Twig テンプレートファイル** はテーマやプラグインで提供されます。
カスタムページテンプレートを作成するには、管理パネルプラグイン用のフィールドを定義したブループリントファイルと、コンテンツをレンダリングするテンプレートファイルを用意する必要があります。

<h4 id="adding-a-custom-page-template-to-a-theme-plugin">テーマやプラグインにカスタムページテンプレートを追加</h4>

In the root of the theme/ plugin folder, create a folder named `templates`.  Inside this folder, create a new mypage.html.twig file.  This will be the Twig template for the new page template "mypage".

mypage.html.twig の具体例:

```twig
{% extends 'partials/base.html.twig' %}

{% block content %}
    {{ page.header.newTextField|e }}
    {{ page.content|raw }}
{% endblock %}
```

There is more information about Twig theming in the [Twig Primer](../../03.themes/03.twig-primer/) section.

Themes automatically find template files within the theme's `templates` folder.  If the template is being added via a plugin, you'll need to add the template via the event `onTwigTemplatePaths`:

```php
public function onPluginsInitialized(): void
{
    // If in an Admin page.
    if ($this->isAdmin()) {
        return;
    }
    // If not in an Admin page.
    $this->enable([
        'onTwigTemplatePaths' => ['onTwigTemplatePaths', 1],
    ]);
}

/**
 * Add templates directory to twig lookup paths.
 */
public function onTwigTemplatePaths(): void
{
    $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
}
```


#### Adding a custom page blueprint to a theme/ plugin

In order for the Admin plugin to provide a new `mypage` page option, create a folder named `blueprints` in the root of the theme/ plugin. Inside this folder, create a new mypage.yaml file.  This is where you will define custom fields for the Admin plugin to display when creating a new page.  Available form fields can be found in the [Forms](../../06.forms/) chapter.

The example blueprint `mypage.yaml` below extends the default page template, and then adds header.newTextField under the content tab.:

```yaml
title: My Page Blueprint
'@extends':
    type: default
    context: blueprints://pages

form:
  fields:
    tabs:
      type: tabs
      active: 1
      fields:
        content:
          type: tab
          fields:
             header.newTextField:
              type: text
              label: 'New Text Field'

```

Similarly to the `templates` folder, a theme will automatically add any blueprint yaml files found within the `blueprints` folder.  If the blueprint is being added via a plugin, you'll need to add the blueprint via the event `onGetPageTemplates`:

```php
public function onPluginsInitialized(): void
{
    // If in an Admin page.
    if ($this->isAdmin()) {
        $this->enable([
            'onGetPageBlueprints' => ['onGetPageBlueprints', 0],
            'onGetPageTemplates' => ['onGetPageTemplates', 0],
        ]);
    }
}

/**
 * Add blueprint directory.
 */
public function onGetPageBlueprints(Event $event): void
{
    $types = $event->types;
    $types->scanBlueprints('plugin://' . $this->name . '/blueprints');
}

/**
 * Add templates directory.
 */
public function onGetPageTemplates(Event $event): void
{
    $types = $event->types;
    $types->scanTemplates('plugin://' . $this->name . '/templates');
}
```

#### Creating a new page

After defining the blueprint and template files, create a new page within the admin panel by clicking on **Add** and then selecting "Mypage":
![myPage.jpg](myPage.jpg)

The Admin edit form now displays the new custom field "New Text Field":
![myPage-customField.jpg](myPage-customField.jpg)

