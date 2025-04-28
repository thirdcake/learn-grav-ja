---
title: "高度なブループリントの機能"
layout: ../../../../layouts/Default.astro
---

ブループリントには、それらを拡張し、動的にフィールドを使える高度な機能があります。

<h2 id="defining-validation-rules">バリデーションルールを決める</h2>

If you need the same validation rules multiple times, you can create your own custom rule for it.

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

Above example creates rule `slug`, which is then used in the folder field of the form.

## Extending Base Type (extends@)

You can extend existing blueprint, which allows you to add new fields as well as modify existing ones from the base blueprint.

```yaml
extends@: default
```

In the extended format you can specify a lookup context for your base file:

```yaml
extends@:
  type: default
  context: blueprints://pages
```

You can also extend the blueprint itself, if there are multiple versions of the same blueprint.

```yaml
extends@: parent@
```

There is no limit on how many blueprints you can extend. Fields defined in the first blueprint will be replaced by any later blueprints in the list.

```yaml
extends@:
  - parent@
  - type: default
    context: blueprints://pages
```

### Understanding the type- and context-properties

In the examples above, `type` is referencing a file and `context` a path. The `context`-property uses [Streams](https://learn.getgrav.org/advanced/multisite-setup#streams), which means that it resolves to a physical location.

`context: blueprints://` by default will yield `/user/plugins/admin/blueprints`, Admin's blueprints-folder. `type: default` will yield `default.yaml`, when looking up files. Because these two properties are used together, they yield a full path that Grav can understand: `/user/plugins/admin/blueprints/default.yaml`.

Whenever you see the `://`-syntax in these docs, you can be pretty sure it's referring to a stream. And when using `context`, this stream must resolve to an existing folder to work.

## Embedding Form (import@)

Sometimes you may want to share some fields or sub-forms between multiple forms.

Let's create `blueprints://partials/gallery.yaml` which we want to embed to our form:

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

Our form then has a section where we would like to embed the gallery images:

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

While YAML does not allow using the same `import@` key multiple times, you can still import multiple blueprints by appending a unique number after `@`, e.g. `import@1`, `import@2` and so on. The number has no other meaning than preventing YAML parser from erroring out:

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

## Removing Fields / Properties (unset-*@)

If you want to remove a field, you can add `unset@: true` inside of it.
If you want to remove a property of field, you just append property name, eg: `unset-options@` removes all options.

## Replacing Fields / Properties (replace-*@)

By default blueprints use deep merging of its properties. Sometimes instead of merging the content of the field, you want to start from a clean table.
If you want to replace the whole field, your new field needs to start with `replace@`:

```yaml
author.name:
  replace@: true
  type: text
  label: Author name
```


As the result `author.name` will have only two properties: `type` and `label` regardless of what the form had before.
You can do the same for individual properties:

```yaml
summary.enabled:
  replace-options@: true
  options:
    0: Yeah
    1: Nope
    2: Do not care
```

Note: `replace-*@` is alias for `unset-*@`.

## Using Configuration (config-*@)

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

