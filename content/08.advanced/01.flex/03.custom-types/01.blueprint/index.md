---
title: ブループリント
layout: ../../../../../layouts/Default.astro
lastmod: '2025-10-26'
description: 'Flex ディレクトリの設定ファイルであるブループリントの書き方について解説します。'
---

**Flex ブループリント** の基本構造には、その Flex タイプがどんなものかを説明する `title` と、 `description` と、 `type` が含まれています。  
さらに、 Flex ディレクトリを、異なる観点から設定する3つのセクション（ `config` と、 `blueprints` と `form` ）があります。

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

独自に、カスタムディレクトリを作成するには、まず `type` （ファイル名）を名付けて、 `title` と `description` を入力します。

ファイルを作成し、基本情報が入力できたら、次のステップとして、ファイルに既存のフォームをコピーするか、フィールドを追加します。

> [!Tip]  
> ここからの文章では、 **[フォームとブループリント](../../../../06.forms/)** のカスタマイズ方法を知っているという前提で、話を進めます。

> [!Warning]  
> **[シンプルな1つのフォームを作成](../../../../06.forms/02.forms/#create-a-simple-single-form)** で説明したような、シンプルなリストフォーマットは使わない方が良いでしょう。また、 `process` セクションは、このファイルの form には渡さないでください。 Flex では利用できません。

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

form の見た目は、ページから取得するものも、config 設定や、プラグインやテーマのブループリントファイルから取得するものも、同じ見た目です。  
上記が、この flex ディレクトリ内のすべての flex オブジェクトに適用されるメインのブループリントであり、 flex オブジェクトに定義されるフィールドは、すべてここに含まれている必要があります。  
管理パネルに表示されるフォームだと考えてください。

> [!Warning]  
> 既存の Flex タイプのブループリントを修正するときは気をつけてください。保存済みのオブジェクトが、新しいバージョンのブループリントに、必ず適合するように確認してください。 - つまり、古いオブジェクトも保存や表示できるようにすべきという意味です。

ここで終わりではありません。  
あと2項目ほど、 flex ディレクトリを機能させるために必要です: データストレージレイヤーの設定と、管理パネルでの一覧画面に表示するフィールドの定義が必要です。  
これらはどちらも、 `config` セクションでできます。

## Config

Config セクションは、 Flex ブループリントで最も複雑な部分です。  
しかしその多くは、ただのカスタム用です。  
セクションには、 `data` と、 `admin` と、`site` があります。

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

config 設定には、2つの必須セクションがあります: `config.data.storage` と、 `config.admin.list.fields` です。  
後者は、管理パネルでのリスト表示画面で表示されるフィールドを定義します。  
前者のデータストレージは、データをどのように保存するかを定義します。

### Config > Data

**Flex ディレクトリ** は、柔軟にカスタマイズできます。  
`object` と、 `collection` と、 `index` の3つの PHP class に、独自のふるまいを追加できます。  
さらに、`storage` レイヤーを好きなところに設定できます。  
flex ディレクトリは、デフォルトの `ordering` （順序）と、 `search` （検索）機能を付けることもできます。

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

Object と、 collection と、 index は、class 名を使います。  
これらを入力しなかった場合、 Grav は以下のデフォルトの config 設定を使います：

```yaml
config:
  data:
    object: 'Grav\Common\Flex\Types\Generic\GenericObject'
    collection: 'Grav\Common\Flex\Types\Generic\GenericCollection'
    index: 'Grav\Common\Flex\Types\Generic\GenericIndex'
```

これらの class は、一緒に flex type へふるまいを定義します。  
独自の flex type をカスタマイズしたい場合、これらの class を拡張し、独自の class をここで渡すことで可能になります。

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
| same_as | 0 ... 1 | フィールドの値は、検索文字列と完全に一致する必要があります |
| starts_with | 0 ... 1 | フィールドの値は、検索文字列で始まる必要があります |
| ends_with | 0 ... 1 | フィールドの値は、検索文字列で終わる必要があります |
| contains | 0 ... 1 | フィールドの値は、検索文字列を含む必要があります |

検索機能は、マッチしなかった場合、0 を返します。  
マッチした場合は、 0 から 1 の重みを付けます。  
重みは、検索結果の順序付けに利用されます。  
最も高いスコアを得たオブジェクトが、それより低いスコアのオブジェクトよりも、よくマッチしています。

> [!Tip]  
> 現在、検索機能は、フィールドごとの重み付けや戦略には対応していません。

### Config > Admin

Admin セクションには、 flex ディレクトリ管理をカスタマイズする多様なオプションが含まれます。  
メインのセクションは、次の通りです:  `router`, `actions`, `permissions`, `menu`, `template` そして `views` 。

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

オプションの `router` セクションでは、 **Flex Directory** 管理ルーティングをカスタマイズするのに使えます。  
ルーティングは、ベースパスや、すべてのアクションに対するカスタマイズ可能なルーティング、そして、後方互換性などの制御のためのリダイレクトをサポートします。
すべてのパスは、管理パネルのベース URL からの相対パスです。

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

場合によっては、管理パネルで、入力欄のみが表示されるように制限したり、たとえば、既存の入力エントリーの編集のみを許可したいこともあります。  
このような場合のため、 `actions` で、許可する CRUD 操作を調整したりして、よりやりたいことに合わせることができます。

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

上記の例では、管理者を含むすべてのユーザーに対して、既存のオブジェクトを保存したり、削除したりすることができないようにしています。

Permissions セクションでは、 Grav に新しいパーミッションルールを追加できます。  
これらのルールは、 user/group 管理に現れます。  
必要ならいくらでもたくさんのパーミッションを作成できます。ただし、それぞれを利用するための独自ロジックや `authorize` ルールをこのファイルに追加する必要があります。

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

もし、管理パネルの `Flex Objects` にあなたの flex ディレクトリを表示させたくない場合、オプションで、メインナビ内のメニューアイテムに表示させることができます。  
設定の `menu` セクションに、次のように書くだけです。

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

上記の例では、 **Contacts** メニューアイテムを `/admin/contacts` にリンクさせて作成しています。

独自の Flex ディレクトリを作成したとき、すべてのカスタムディレクトリ間で同じテンプレートを共有したい場合があるかもしれません。
そのようなときは、 `template` を使ってできます:

```yaml
config:
  admin:
    # Admin template type (folder)
    template: contacts
```

**Flex Admin** は、 Flex オブジェクトをいくつかのやり方で表示します。  
デフォルトでは、以下のような表示をサポートします: `list`, `edit`, `configure` そして、オプションで次をサポートします: `preview`, `export` 。  
独自の表示方法を追加することもできます。

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

最初の表示は、すべての Flex オブジェクトをリスト表示するものです。  
デフォルトでは、 `list` 表示は、 *VueTable* と *AJAX* を使い、 Flex オブジェクトをページ分けします。  
この表示には、一覧に表示する `fields` と、1ページにいくつのアイテムを表示させるかや、デフォルトの表示順を定義する `options` が必要です。

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

**Icon** と **title** は、リストページのアイコンとタイトルをカスタマイズします。  
**Title** は、以下のようなフォーマットを使うことで、 Twig テンプレートもサポートします:

```yaml
        title:
          template: "{{ 'PLUGIN_CONTACTS.CONTACTS_TITLE'|tu }}"
```

**Fields** には、一覧に表示したいフィールドを含めます。  
各フィールドは、フィールド名をキーとして持ちます。  
値は、省略するか、以下のような設定オプションを含めることができます:

| 名前 | 値 | 具体例 | 説明 |
|------|----|--------|------|
| width | `integer` | 8 | ピクセル単位のフィールドの幅 |
| alias | `string` | 'header.published' | 利用するフォームフィールド名。 VueTable は、名前にドット `.` を使えないため、ネストされた変数にはエイリアスを設定してください。 |
| field | `array` |  | フォームフィールドの上書き。通常のフォームフィールドと同様に記述しますが、キーを指定しません。 |
| link | `string` | 'edit' | テキストに編集画面へのリンクを追加する。 |
| search | `boolean` | true | 管理画面リストでそのフィールドを検索対象とするか。 |
| sort | `array` | field: 'first_name' |  You can specify different value if you use different field name when querying data on the server side, e.g. first_name. |
| title_class | `string` | 'center' | タイトルで使われる CSS クラス。 |
| data_class | `string` |  'left' | データ列で使われる CSS クラス。 |

#### Edit View

Edit 表示では、リスト表示と同じく基本的な設定オプションが使えます:

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

Configure 表示では、さまざまな設定オプションを Flex ディレクトリに追加し、それらをテンプレートファイルで使うことができます。

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

Flex では、プレビューもサポートします。  
ただし、現状では、フロントエンドからページをレンダリングすることで機能しています。  
この方法は、ブループリントファイルで定義可能です。

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

すべての Flex オブジェクトをひとつのファイルにエクスポートすることができます。  
以下は、 YAML ファイルにデータをエクスポートする設定方法の例です。

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

テンプレートを設定することで、探すテンプレートのパスをカスタマイズでき、デフォルトのタイプとフロントエンドでのレイアウト名を設定できます。

## Blueprints

blueprints セクションでは、 Flex ディレクトリ全体に対する一般的な設定オプションを定義します。  
オプションの設定により、一般的な Flex ディレクトリを、手作業のファイル編集をせずに、よりサイトのニーズに合ったものへカスタマイズできます。

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

> [!Tip]  
> これらの設定オプションは、 [**Flex ディレクトリ管理パネル**](../../01.administration/) の [**Configuration**](../../01.administration/03.configuration/) セクション で修正できます。

> [!Note]  
> 現在使用されている設定オプションは、キャッシュセクション内にのみ存在します。カスタム設定を使用するには、それらを利用するロジックを独自に追加する必要があります。

