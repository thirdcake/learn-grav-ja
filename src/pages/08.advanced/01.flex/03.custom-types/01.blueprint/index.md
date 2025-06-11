---
title: ブループリント
layout: ../../../../../layouts/Default.astro
lastmod: '2025-05-11'
---
**Flex ブループリント** の基本構造は、flex タイプを説明する `title` と、 `description` と、 `type` があり、 Flex タイプのそれぞれ異なる側面を説明する3つのセクション（ `config` と、 `blueprints` と `form` ）もあります。

`contacts.yaml` の主要構造は、次のようになっています：

```yaml
title: Contacts
description: Simple contact directory with tags.
type: flex-objects  # do not change

# Flex Configuration
config: {}

# Flex Directory Forms
blueprints: {}

# Flex Object Form
form: {}
```

ご自身でカスタムディレクトリを作成するには、まず `type` （ファイル名）を名付けて、 `title` と `description` を入力します。

ファイルを作成し、基本情報が入力できたら、次のステップとして、ファイルに既存のフォームをコピーするか、フィールドを追加します。

> [!Note]  
> **TIP:** ここでは、オリジナルの **[フォームとブループリント](../../../../06.forms/)** の作成方法を知っているという前提で、話を進めます。

> [!Info]  
> **WARNING:** **[シンプルな1つのフォームを作成](../../../../06.forms/02.forms/#create-a-simple-single-form)** で説明したような、シンプルなリストフォーマットは使わない方が良いでしょう。また、 `process` セクションは、このファイルの form には渡さないでください。 Flex では利用できません。

## Form

contacts の例では、form セクションは次のようになっています：

```yaml
# Flex Object Form
form:
  validation: loose

  fields:
    published:
      type: toggle
      label: Published
      highlight: 1
      default: 1
      options:
        1: PLUGIN_ADMIN.YES
        0: PLUGIN_ADMIN.NO
      validate:
        type: bool
        required: true

    last_name:
      type: text
      label: Last Name
      validate:
        required: true

    first_name:
      type: text
      label: First Name
      validate:
        required: true

    email:
      type: email
      label: Email Address
      validate:
        required: true

    website:
      type: url
      label: Website URL

    tags:
      type: selectize
      size: large
      label: Tags
      classes: fancy
      validate:
        type: commalist
```

form の見た目は、ページから取り出されたものも、config 設定や、プラグインやテーマのブループリントファイルから取り出されたものも、同じです。これが、この flex ディレクトリ内のすべての flex オブジェクトに適用されるメインのブループリントであり、オブジェクトに定義されるフィールドはすべて含まれている必要があります。管理パネルに表示されるフォームのように考えてください。

> [!Info]  
> **WARNING:** 既存の Flex タイプのブループリントを修正するときは気をつけてください。保存済みのオブジェクトが、新しいバージョンのブループリントに適合することを確認してください。 - つまり、古いオブジェクトも保存や表示できるようにすべきという意味です。

まだ終わっていません。あと2項目ほど、 flex ディレクトリを機能させるために必要です：データストレージレイヤーの設定と、管理パネルでの一覧画面に表示するフィールドの定義が必要です。これらはどちらも、 `config` セクションでできます。

## Config

Config セクションは、 Flex ブループリントで最も複雑な部分です。しかしその多くは、ただのカスタム用です。セクションには、 `data` と、 `admin` と、`site` があります。

```yaml
# Flex Configuration
config:

  # Data Settings
  data: {}

  # Admin Settings
  admin: {}

  # Site Settings
  site: {}
```

最小の config 設定は、次のようになります：

```yaml
# Flex Configuration
config:

  # Data Settings
  data:
    storage: user-data://flex-objects/contacts.json

  # Admin Settings
  admin:
    # List view
    list:
      # List of fields to display
      fields:
        last_name:
          link: edit # Edit link
        first_name:
          link: edit # Edit link
        email:
        website:
```

config 設定には、2つの必須セクションがあります： `config.data.storage` と、 `config.admin.list.fields` です。後者は、管理パネルでのリスト表示画面で表示されるフィールドを定義します。前者のデータストレージは、データをどのように保存するかを定義します。

### Config > Data

**Flex ディレクトリ** は、柔軟にカスタマイズできます。 `object` と、 `collection` と、 `index` の3つの PHP class に、独自のふるまいを追加できます。さらに、`storage` レイヤーを好きなところに設定できます。 flex ディレクトリは、デフォルトの `ordering` （順序）と、 `search` （検索）機能を付けることもできます。

```yaml
config:
  data:
    # Flex Object Class
    object: CLASSNAME
    # Flex Collection Class
    collection: CLASSNAME
    # Flex Index Class
    index: CLASSNAME
    # Storage Options
    storage: {}
    # Ordering Options
    ordering: {}
    # Search Options
    search: {}
```

Object と、 collection と、 index は、class 名を使います。これらを入力しなかった場合、 Grav は以下のデフォルトの config 設定を使います：

```yaml
config:
  data:
    object: 'Grav\Common\Flex\Types\Generic\GenericObject'
    collection: 'Grav\Common\Flex\Types\Generic\GenericCollection'
    index: 'Grav\Common\Flex\Types\Generic\GenericIndex'
```

これらの class は、一緒に flex type へふるまいを定義します。独自の flex type をカスタマイズしたい場合、これらの class を拡張し、独自の class をここで渡すことで可能になります。

最も重要な部分のひとつは、データをどこに、どのように保存するかを定義するところです：

```yaml
config:
  data:
    storage:
      class: 'Grav\Framework\Flex\Storage\SimpleStorage'
      options:
        formatter:
          class: 'Grav\Framework\File\Formatter\JsonFormatter'
        folder: user-data://flex-objects/contacts.json
```

上記は、短いフォームで書かれる場合の、特別なケースです。

```yaml
config:
  data:
    storage: user-data://flex-objects/contacts.json
```

Grav 1.7 では、3つの異なるストレージ戦略がありますが、独自のものも、簡単に作れます：

| 名前 | Class名 | 説明 |
|------|-------|-------------|
| Simple Storage | Grav\Framework\Flex\Storage\SimpleStorage | すべてのオブジェクトが1つのファイルに保存されます。メディアファイルはサポートしません |
| File Storage | Grav\Framework\Flex\Storage\FileStorage | オブジェクトは、1つのフォルダに、それぞれファイルに分かれて保存されます。 |
| Folder Storage | Grav\Framework\Flex\Storage\FolderStorage | すべてのオブジェクトは、それぞれ別のフォルダに保存されます。 |

さらに、 `options.formatter.class` により、ファイルフォーマットも提供できます：

| 名前 | Class名 | 説明 |
|------|-------|-------------|
| JSON | Grav\Framework\File\Formatter\JsonFormatter | JSON ファイルフォーマットを使用 |
| YAML | Grav\Framework\File\Formatter\YamlFormatter | YAML ファイルフォーマットを使用 |
| Markdown | Grav\Framework\File\Formatter\MarkdownFormatter | Grav のマークダウンファイルフォーマットと YAML フロントマターを使用 |
| Serialize | Grav\Framework\File\Formatter\SerializeFormatter | PHP のシリアライザを使用。速いですが、人間に読める形式ではありません |
| INI | Grav\Framework\File\Formatter\IniFormatter | INI ファイルフォーマットを使用。非推奨。 |
| CSV | Grav\Framework\File\Formatter\CsvFormatter | CSV ファイルフォーマットを使用。非推奨。 |

デフォルトのフォーマッタの（デフォルトでの）オプションは、以下のタブ内にあります：

```yaml
# JSON
formatter:
  class: 'Grav\Framework\File\Formatter\JsonFormatter'
  options:
    file_extension: '.json'
    encode_options: '' # See https://www.php.net/manual/en/function.json-encode.php (separate options with space)
    decode_assoc: true # Decode objects as arrays
    decode_depth: 512  # Decode up to 512 levels
    decode_options: '' # See https://www.php.net/manual/en/function.json-decode.php (separate options with space)
```

```yaml
# YAML
formatter:
  class: 'Grav\Framework\File\Formatter\YamlFormatter'
  options:
    file_extension: '.yaml'
    inline: 5           # Save with up to 4 expanded levels
    indent: 2           # Indent with 2 spaces
    native: true        # Use native YAML decoder if available
    compat: true        # If YAML cannot be decoded, use compatibility mode (SLOW)
```

```yaml
# Markdown
formatter:
  class: 'Grav\Framework\File\Formatter\MarkdownFormatter'
  options:
    file_extension: '.md'
    header: 'header'    # Header variable eg. header.title
    body: 'markdown'    # Body variable
    raw: 'frontmatter'  # RAW YAML variable
    yaml:
      inline: 20        # YAML options, see YAML formatter from above
```

```yaml
# PHP Serialize
formatter:
  class: 'Grav\Framework\File\Formatter\SerializeFormatter'
  options:
    file_extension: '.ser'
    decode_options:
      allowed_classes: ['stdClass'] # List of allowed / safe classes during unserialize
```

```yaml
# INI
formatter:
  class: 'Grav\Framework\File\Formatter\IniFormatter'
  options:
    file_extension: '.ini'
```

```yaml
# CSV
formatter:
  class: 'Grav\Framework\File\Formatter\CsvFormatter'
  options:
    file_extension: ['.csv', '.tsv']
    delimiter: ','      # Delimiter to separate the values
    mime: 'text/x-csv'  # MIME type for downloading file
```

デフォルトの順序を設定することもできます。 `key: ASC|DESC` ペアで定義されます：

```yaml
config:
  data:
    # Ordering Options
    ordering:
      key: ASC
      timestamp: ASC
```

最後に、検索フィールドを追加できます。 `collection.search()` で呼び出すことにより表示できます：

```yaml
config:
  data:
    search:
      # Fields to be searched
      fields:
        - last_name
        - first_name
        - email
      # Search Options
      options:
        - contains: 1   # If field contains the search string, assign weight 1 to the object
```

**Fields** は、検索対象フィールドのリストです。

Search のオプションは、次のとおりです：

| 名前 | 値 | 説明 |
|------|-------|-------------|
| case_sensitive | `true` or `false` | true にすると、大文字・小文字を区別します。デフォルトは false |
| same_as | 0 ... 1 | Value of the field must be identical to the search string |
| starts_with | 0 ... 1 | Value of the field must start with the search string |
| ends_with | 0 ... 1 | Value of the field must end with the search string |
| contains | 0 ... 1 | Value of the field must contain the search string |

検索機能は、マッチしなかった場合、0 を返します。マッチした場合は、 0 から 1 の重みを付けます。重みは、検索結果の順序付けに利用されます。最も高いスコアを得たオブジェクトが、それより低いスコアのオブジェクトよりも、よくマッチしています。

> [!Tip]  
> 現在、検索機能は、フィールドごとの重み付けや戦略には対応していません。

### Config > Admin

Admin section contains various configuration options to customize directory administration. It contains a few main sections: `router`, `actions`, `permissions`, `menu`, `template` and `views`.

```yaml
config:
  # Admin Settings
  admin:
    # Admin router
    router: {}
    # Allowed admin actions
    actions: {}
    # Permissions
    permissions: {}
    # Admin menu
    menu: {}
    # Admin template type
    template: pages
    # Admin views
    views: {}
```

Optional `router` section can be used to customize the **Flex Directory** admin routes. Routing supports a base path, customizable routes for every action as well as redirects to handle for example backwards compatibility. All the paths are relative to admin base URL.

```yaml
config:
  admin:
    # Admin router
    router:
      path: '/contacts' # Custom path to the directory
      actions:
        configure: # Action name
          path: '/contacts/configure' # New path to the action.
      redirects: # List of redirects (from: to)
        '/flex-objects/contacts': '/contacts'
```

Sometimes you want to restrict the administration to only display the entries or for example to only allow editing the existing ones. For this `actions` is where you can tweak the allowed CRUD operations to fit better your needs.

```yaml
config:
  admin:
    # Allowed admin actions (for all users, including super user)
    actions:
      list: true   # Needs to be true (may change in the future)
      create: true # Set to false to disable creating new objects
      read: true   # Set to false to disable link to edit / details of the objects
      update: false # Set to false to disable saving existing objects
      delete: false # Set to false to disable deleting objects
```

Above example will prevent saving existing objects and deleting them for every user, including Super Admin.

Permissions section allows you to add new permission rules for Grav. These rules will appear in user/group admin. You can create as many permission rules as you wish, but you need to add your own logic or `authorize` rules in this file to use them.

```yaml
config:
  admin:
    # Permissions
    permissions:
      # Primary permissions (used for the objects)
      admin.contacts:
        type: crudl # Create, Read, Update, Delete, List
        label: Contacts Directory
      # Secondary permissions (you need to assign these to a view, otherwise these will not be used)
      admin.configuration.contacts:
        type: default # Simple permission
        label: Contacts Configuration
```

If you do not want to display your directory in `Flex Objects` administration, one option is to display menu item in the main navigation. You can do just that in the `menu` section of the configuration.

```yaml
config:
  admin:
    # Admin Menu
    menu:
      list:
        hidden: false # If true, hide the menu item.
        route: '/contacts' # Alias to `config.admin.router.path` if router path is not set.
        title: Contacts
        icon: fa-address-card-o
        authorize: ['admin.contacts.list', 'admin.super'] # Authorization needed to access the menu item.
        priority: 2 # Priority -10 .. 10 (highest goes up)
```

Above example creates **Contacts** menu item pointing to `/admin/contacts`.

When you're creating your own Flex Directories, you may sometimes want to share the same templates between all of your custom directories. You can do this with `template`:

```yaml
config:
  admin:
    # Admin template type (folder)
    template: contacts
```

**Flex Admin** has multiple views to the objects. By default, following views are supported: `list`, `edit`, `configure` and optionally `preview` & `export`. It is possible to add your own views as well.

```yaml
config:
  admin:
    views:
      # List view
      list: {}
      # Edit view
      edit: {}
      # Configure view
      configure: {}
      # Preview
      preview: {}
      # Data Export
      export: {}
```

#### List View

The first view you will need, is the one which lists all of your objects. By default `list` view uses *VueTable* and *AJAX* to paginate the objects. It needs a list of `fields` to display as well as `options` to define how many items are being shown at once as well as the default field used for ordering.

```yaml
config:
  admin:
    views:
      # List view
      list:
        icon: fa-address-card-o
        title: Site Contacts
        fields: {}        # See below
        options:
          per_page: 20    # Default number of items per page
          order:
            by: last_name # Default field used for ordering
            dir: asc      # Default ordering direction
```

**Icon** and **title** are used to customize the icon and title of the listing page. **Title** supports also using Twig template by using following format:

```yaml
        title:
          template: "{{ 'PLUGIN_CONTACTS.CONTACTS_TITLE'|tu }}"
```

**Fields** contains the fields you want to display in the directory listing. Each field has a key, which is the name of the field. Value can be omitted or it can contain the following configuration options:

| 名前 | 値 | 具体例 | 説明 |
|------|----|--------|------|
| width | `integer` | 8 | Width of the field in pixels |
| alias | `string` | 'header.published' | Name of the form field to use. VueTable doesn't like dots in the names, so set alias for nested variables. |
| field | `array` |  | Form field override. Written just like any form field, but just without a key. |
| link | `string` | 'edit' | Adds edit link to the text. |
| search | `boolean` | true | Makes the field searchable in admin list. |
| sort | `array` | field: 'first_name' | You can specify different value if you use different field name when querying data on the server side, e.g. first_name. |
| title_class | `string` | 'center' | CSS classes used in titles. |
| data_class | `string` |  'left' | CSS classes used in data columns. |

#### Edit View

Edit view has the same basic configuration options as list view:

```yaml
config:
  admin:
    views:
      # Edit view
      edit:
        icon: fa-address-card-o
        title:
          template: '{{ object.last_name ?? ''Last'' }}, {{ object.first_name ?? ''First Name'' }}'
```

#### Configure view

Configure view allows you to add directory wide configuration options, which can then be used in the template files.

```yaml
config:
  admin:
    views:
      # Configure view
      configure:
        hidden: false # Configuration button can be hidden, for example if you have custom tab to replace it, like in Accounts.
        authorize: 'admin.configuration.contacts' # Optional custom authorize rule for this view.
        file: 'config://flex/contacts.yaml' # Optional file where the configuration is saved.

        icon: fa-cog
        title:
          template: "{{ directory.title }} {{ 'PLUGIN_ADMIN.CONFIGURATION'|tu }}"
```

#### Preview view

Flex also supports preview, though right now it works by rendering a page from the frontend, which can be defined in the blueprint file.

```yaml
    # Preview View
    preview:
      enabled: true
      route:
        template: '/contacts' # Twig template to create URL. In this case we use the list view

       icon: fa-address-card-o
        title:
          template: "{{ object.form.getValue('title') ?? object.title ?? key }}"
```

#### Export view

All objects can be exported into a single file, here is an example configuration how to export data into YAML file:

```yaml
    # Data Export
    export:
      enabled: true
      method: 'jsonSerialize'
      formatter:
        class: 'Grav\Framework\File\Formatter\YamlFormatter'
      filename: 'contacts'
```

### Config > Site

```yaml
config:
  # Site Settings
  site:
    templates:
      collection:
        # Lookup for the template layout files for collections of objects
        paths:
          - 'flex/{TYPE}/collection/{LAYOUT}{EXT}'
      object:
        # Lookup for the template layout files for objects
        paths:
          - 'flex/{TYPE}/object/{LAYOUT}{EXT}'
      defaults:
        # Default template variable {TYPE}; overridden by filename of this blueprint if template folder exists
        type: contacts
        # Default template variable {LAYOUT}; can be overridden in render calls (usually Twig in templates)
        layout: default
```

Template settings allow you to customize the template lookup paths and set the default type and layout name in the frontend.

## Blueprints

Blueprints section defines the common configuration options for the whole Directory. The options allow you to customize a common directory to better suit the needs of the site without requiring manual editing of the files.

```yaml
blueprints:
  # Blueprint for configure view.
  configure:
    # We are inside TABS field.
    fields:
      # Add our own tab
      compatibility:
        type: tab
        title: Compatibility
        fields:
          # Fields should be prefixed with object, collection etc..
          object.compat.events:
            type: toggle
            toggleable: true
            label: Admin event compatibility
            help: Enables onAdminSave and onAdminAfterSave events for plugins
            highlight: 1
            default: 1
            options:
              1: PLUGIN_ADMIN.ENABLED
              0: PLUGIN_ADMIN.DISABLED
            validate:
              type: bool
```

> [!Note]  
> **TIP:** These configuration options can be modified in **[Configuration](../../01.administration/03.configuration/)** section of the **[Flex Directory Administration](../../advanced/flex/administration/)**.

> [!Tip]  
> **NOTE:** Currently the only used configuration options are inside the cache section. For your custom settings, you need to add logic to use them by yourself.

