---
title: 高度なブループリントの機能
layout: ../../../../layouts/Default.astro
lastmod: '2025-08-28'
---

ブループリントには、それらを拡張し、動的にフィールドを使える高度な機能があります。

<h2 id="defining-validation-rules">バリデーションルールの定義</h2>

同じバリデーションルールが複数回必要な場合、独自のカスタムルールを作成できます。

```yaml
rules:
  slug:
    pattern: "[a-z][a-z0-9_\-]+"
    min: 2
    max: 80
form:
  fields:
    folder:
      type: text
      label: Folder Name
      validate:
        rule: slug
```

上記の例では、 `slug` というルールを作り、フォームの folder フィールドで使っています。

<h2 id="extending-base-type-extendsat">ベースタイプの拡張（extends@）</h2>

既存のブループリントを拡張して、新しいフィールドを追加したり、基本となるブループリントから既存のフィールドを変更したりすることができます。

```yaml
extends@: default
```

拡張フォーマットでは、ベースファイルとして見つけるコンテクストを指定できます:

```yaml
extends@:
  type: default
  context: blueprints://pages
```

同じブループリントの複数のバージョンがある場合は、ブループリント自体を拡張することもできます。

```yaml
extends@: parent@
```

拡張できるブループリントの数に制限はありません。  
最初のブループリントで定義されたフィールドは、後に続くブループリントで書き換えられます。

```yaml
extends@:
  - parent@
  - type: default
    context: blueprints://pages
```

<h3 id="understanding-the-type-and-context-properties">type 及び context プロパティを理解する</h3>

上記の例で、 `type` は参照ファイルで、 `context` は path です。  
`context` プロパティは、 [ストリーム](../../../08.advanced/05.multisite-setup/#streams) を使い、これはつまり、物理的なロケーションを解決します。

デフォルトの `context: blueprints://` は、 `/user/plugins/admin/blueprints` つまり、管理パネルのブループリントフォルダを出力します。  
`type: default` は、ファイルを探す時に `default.yaml` を出力します。  
これら2つのプロパティが一緒に使われるので、 Grav は、 `/user/plugins/admin/blueprints/default.yaml` として理解できるフルパスを出力します。

`://` という構文を見た場合はいつでも、ストリームを参照すると確信してもらって構いません。  
そして `context` を使う場合は、このストリームは、機能するフォルダの存在を解決しなければいけません。

<h2 id="embedding-form-importat">埋め込みフォーム(import@)</h2>

複数のフォーム間で、いくつかの入力フィールドや、サブフォームを共有したい場面があるかもしれません。

フォームに埋め込む目的の `blueprints://partials/gallery.yaml` を作成してみましょう:

```yaml
form:
  fields:
    gallery.images:
      type: list
      label: Images
      fields:
        .src:
          type: text
          label: Image
```

そして、このフォームは、ギャラリー画像を埋め込みたい場所としてのセクションを持ちます:

```yaml
form:
  fields:
    images:
        type: section
        title: Images
        underline: true
        import@:
          type: partials/gallery
          context: blueprints://
```

YAML は、同一の `import@` キーを複数回使うことを許容しないものの、たとえば `import@1`, `import@2`, のように、 `@` の後に一意の数字を追加することで、複数のブループリントをインポートできます。  
その数字は、 YAML のパーサーがエラーを出力しないようにするため以上の意味はありません:

```yaml
form:
  fields:
    images:
        type: section
        title: Images
        underline: true
        import@1:
          type: partials/gallery
          context: blueprints://
        import@2:
          type: partials/another-gallery
          context: blueprints://
```

<h2 id="removing-fields-properties-unset-at">フィールド・プロパティの削除(unset-*@)</h2>

フィールドを削除したい場合、その内部に `unset@: true` を追加できます。
フィールドのプロパティを削除したい場合、プロパティ名に、たとえば `unset-options@` を付けるだけで、すべてのオプションを削除できます。

<h2 id="replacing-fields-properties-replace-at">フィールド・プロパティの置き換え(replace-*@)</h2>

デフォルトでは、ブループリントは、プロパティを深くマージ(再帰的に中身まで含めてマージ)します。  
ときには、フィールドのコンテンツをマージするのではなく、クリーンなテーブルから始めたい場合もあります。
フィールド全体を置き換えたい場合、その新しいフィールドは、 `replace@` で始める必要があります:

```yaml
author.name:
  replace@: true
  type: text
  label: Author name
```

`author.name` の結果は、以前のフォームが持っていたものと関係なく、2つのプロパティ (`type` と `label`) だけです。
個々のプロパティにも同じことができます:

```yaml
summary.enabled:
  replace-options@: true
  options:
    0: Yeah
    1: Nope
    2: Do not care
```

注意: `replace-*@` は、 `unset-*@` のエイリアス(別名)です。

<h2 id="using-configuration-config-at">設定を利用する (config-*@)</h2>

There are times when you might want to get default value from Grav configuration. For example you may want to have author field to default to author of the site:

```yaml
form:
  fields:
    author:
      type: text
      label: Author
      config-default@: site.author.name
```

If your site author name is `John Doe`, the form is equivalent to:

```yaml
form:
  fields:
    author:
      type: text
      label: Author
      default: "John Doe"
```

You can use `config-*@` for any field; for example if you want to change the field `type`, you can just have `config-type@: site.forms.author.type` to allow you to change the input field type from your configuration.

## Using Function Calls (data-*@)

You can make function calls with parameters from your blueprints to dynamically fetch a value for any property in your field. You can do this by using `data-*@:` notation as the key, where `*` is the field name you want to fill with the result of the function call.

As an example we are editing a page and we want to have a field that allows us to change its parent or in another words move page into another location. For that we need default value that points to the current location as well as a list of options which consists of all possible locations. For that we need a way to ask Grav

```yaml
form:
  fields:
    route:
      type: select
      label: Parent
      classes: fancy
      data-default@: '\Grav\Plugin\Admin::route'
      data-options@: '\Grav\Common\Page\Pages::parentsRawRoutes'
      options:
        '/': '- Root -'
```

If you were editing team member page, resulting form would look something like this:

```yaml
form:
  fields:
    route:
      type: select
      label: Parent
      classes: fancy
      default: /team
      options:
        '/': '- Root -'
        '/home': 'Home'
        '/team': 'Team'
        '/team/ceo': '  Meet Our CEO'
        ...
```

While `data-default@:` and `data-options@:` are likely the most used dynamic field properties, you are not limited to those. There are no limits on which properties you can fetch, including `type`, `label`, `validation` and even `fields` under the current field.

Additionally you can pass parameters to the function call just by using array where the first value is the function name and parameters follow:

```yaml
  data-default@: ['\Grav\Theme\ImaginaryClass::getMyDefault', 'default', false]
```

## Changing field ordering

When you extend a blueprint or import a file, by default the new fields are added to the end of the list. Sometimes this is not what you want to do, you may want to add item as the first or after some existing field.

If you want to create a field, you can state its ordering using the `ordering@` property. This field can contain either a field name or an integer (-1 = first item).

Here is an example:

```yaml
form:
  fields:
    route:
      ordering@: -1
      type: select
      label: Parent
      classes: fancy
      default: /team
      options:
        '/': '- Root -'
        '/home': 'Home'
        '/team': 'Team'
        '/team/ceo': '  Meet Our CEO'
        ...
```

Doing this ensures that the route field will be the first field to appear in the form. This makes it easy to import and/or extend an existing field and place your additional fields where you would like them to go.

Here is another example:

```yaml
form:
  fields:
    author:
      ordering@: header.title
      type: text
      label: Author
      default: "John Doe"
```

In the example above, we used the name of another field to set the ordering. In this example, we have set it up so that the `author` field appears after the `title` field in the form.

!! When ordering fields in a page blueprint, you still need to reference the field names prefixed with `header.`, eg: `header.title` for the ordering to work.

## Creating new form field type

If you create a special form field type, which needs a special handling in blueprints, there is a plugin function that you can use.

```php
    /**
     * Get list of form field types specified in this plugin. Only special types needs to be listed.
     *
     * @return array
     */
    public function getFormFieldTypes()
    {
        return [
            'display' => [
                'input@' => false
            ],
            'spacer' => [
                'input@' => false
            ]
        ];
    }
```

You do not need to register this function as it's not really an event, but gets fired when plugin object gets constructed.
The purpose of this function is to give extra instructions how to handle the field, for example above code makes display and spacer types to be virtual, meaning that they won't exist in real data.

You can add any `key: value` pairs including dynamic properties like `data-options@` which will automatically get appended to the fields.

## onBlueprintCreated or accessing blueprint data

Because of blueprints consist of fields with dots, getting nested field from blueprint uses `/` notation instead of `.` notation.

```php
$tabs = $blueprint->get('form/fields/tabs');
```

This makes it possible to access special data fields, like:

```php
$name = $blueprint->get('form/fields/content.name');
$name = $blueprint->get('form/fields/content/fields/.name');
```

For backwards compatibility, you can specify divider in the last (3rd) parameter of `set()` and `get()`

```php
$tabs = $blueprint->get('form/fields/tabs', null, '/');
```

