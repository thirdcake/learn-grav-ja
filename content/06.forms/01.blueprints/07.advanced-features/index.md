---
title: 高度なブループリントの機能
layout: ../../../../layouts/Default.astro
lastmod: '2025-10-30'
description: 'ブループリント設定ファイルを、拡張したり、動的に編集する方法を解説します。'
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

デフォルトでは、 `blueprints://` は、 `/user/plugins/admin/blueprints/` を指し示します。  
このため、テーマのコンテキスト内で作業する場合は、インポート文を調整する必要があることに注意してください:

```yaml
form:
  fields:
    images:
        type: section
        title: Images
        underline: true
        import@:
          type: partials/gallery
          context: theme://blueprints
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

Grav config 設定から、デフォルト値を得たい場合があります。  
たとえば、サイトの著者をデフォルト著者として使いたい場合です:

```yaml
form:
  fields:
    author:
      type: text
      label: Author
      config-default@: site.author.name
```

サイトの著者名が `John Doe` だったら、フォームは次と同等です:

```yaml
form:
  fields:
    author:
      type: text
      label: Author
      default: "John Doe"
```

`config-*@` は、どの入力フィールドでも使えます; たとえば、 `type` フィールドを変更したい場合、 `config-type@: site.forms.author.type` とするだけで、入力フィールドの type を config 設定から変更できます。

<h2 id="using-function-calls-data-at">関数呼び出しを利用する (data-*@)</h2>

ブループリントから引数とともに関数を呼び出して、動的にフィールド内の任意のプロパティ値を取得できます。  
これを行うには `data-*@` 表記をキーとして使います。  
`*` は、関数呼び出しの結果を入力するフィールド名です。 

具体例として、ページを編集している時に、そのページの親を変更できる入力フィールド (別の言い方をすれば、ページを別の場所に移動するフィールド) が欲しいとします。  
そのためには、現在の位置であるデフォルト値とともに、すべての可能な移動先の選択肢が必要です。  
そのため、 Grav に尋ねる必要があります:

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

チームメンバーのページを編集中に、フォームの結果が、次のようになるかもしれません:

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

`data-default@:` と `data-options@:` が、よく使われる動的なフィールドプロパティと思われますが、これらだけに限定する必要はありません。  
取得できるプロパティに制限はありません。 `type` や、 `label`, `validation`, そして現在のフィールド下にある `fields` さえも含まれます。

さらに、関数呼び出しには引数を使うこともできます。最初の値を関数名とし、それ以降引数続けた配列を使うだけです:

```yaml
  data-default@: ['\Grav\Theme\ImaginaryClass::getMyDefault', 'default', false]
```

<h2 id="changing-field-ordering">フィールドの順番を変更する</h2>

ブループリントを拡張したり、ファイルをインポートしたとき、デフォルトでは新しいフィールドはリストの最後に追加されます。  
場合によっては、そういうことがやりたいのではないこともあります。  
アイテムを最初に追加したかったり、とある既存のフィールドの後に入れたかったりするかもしれません。

フィールドを作成したい場合、 `ordering@` プロパティを使って順番を指定できます。  
このフィールドには、フィールド名か、整数を入れられます (-1 = 最初のアイテム) 。

具体例です:

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

このようにすることで、 route フィールドはフォームで最初に現れるフィールドになります。  
これにより、既存のフィールドをインポートしたり、拡張したりするのが簡単になり、追加のフィールドを置きたい場所に置きやすくなります。

次も、具体例です:

```yaml
form:
  fields:
    author:
      ordering@: header.title
      type: text
      label: Author
      default: "John Doe"
```

上記の例では、別のフィールドの名前を使って、順番を設定しています。  
この例では、 `author` フィールドがフォーム内の `title` フィールドの後に現れるように設定しました。

> [!Info]  
> ページのブループリントでフィールドを順序付ける場合、順序付けが機能するためには、フィールド名に `header` 接頭辞を参照する必要があります。例: `header.title`

<h2 id="creating-new-form-field-type">新しいフォームフィールドタイプを作成する</h2>

ブループリントで特別な制御を必要とする、特別なフォームフィールドタイプを作成する場合に、利用できるプラグインの関数があります。

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

この関数は、登録する必要はありません。  
これは実際のイベントではなく、プラグインオブジェクトが construct されたときに発火するからです。  
この関数の目的は、フィールドの処理方法に追加の指示を与えることです。  
たとえば、上記のコードでは、 display 型と spacer 型を仮想的に設定し、本当のデータとしては存在しないことを意味します。

フィールドに自動的に追加される `data-options@` のような動的なプロパティを含め、あらゆる `key: value` ペアを追加できます。

<h2 id="override-or-extend-a-plugin-s-blueprint">プラグインのブループリントを上書きもしくは拡張する</h2>

プラグインで提供されているブループリントについて、変更を加えたいこともあります； そこにあるオプションに付け加えたり、移動させたり、削除したい場合です。  
これは、単純ではありません: プラグインのブループリントは、ただの `form`-プロパティ以上のものを含み、暗黙的に拡張可能なものではないからです。  
しかしながら、プラグインを作成する際には、 [ユーザーのブループリント](../../../01.basics/06.folder-structure/#userblueprints) 向けに変更しやすくする価値があります。

- まず、その PHP-ファイル内で public-プロパティを追加することにより、ブループリントをサポートしていることを宣言する必要があります: `public $features = ['blueprints' => 10];`
- 次に、プラグインは、ファイルから form-フィールドを `import@` する必要があります。たとえば:

```yaml
form:
  validation: strict
  fields:
    tabs:
      type: tabs
      active: 1
      fields:
        import@:
          type: options
          context: blueprints://plugins/yourpluginname
```

この例では、 `user/plugins/yourpluginname/blueprints/plugins/yourpluginname/options.yaml` をインポートしています。

- 3番目に、このファイルは、デフォルトの form-パーツを宣言しておく必要があります:

```yaml
form:
  options:
    type: tab
    title: PLUGIN_ADMIN.OPTIONS
    fields:
      enabled:
        type: toggle
        label: PLUGIN_ADMIN.PLUGIN_STATUS
        default: 1
        options:
          1: PLUGIN_ADMIN.ENABLED
          0: PLUGIN_ADMIN.DISABLED
        validate:
          type: bool
```

> [!Info]  
> `context` と `type` は、このフォーム内で、潜在的なファイルの衝突や、名前の衝突を避けるべきです。そして簡単に識別できるようにしておき、上記のような冗長に見える長いパスを利用するべきです。

それにより、ユーザーは `user/blueprints/plugins/yourpluginname/options.yaml` 内にユーザー定義の変更を加えられるようになります:

```yaml
form:
  options:
    fields:
      category:
        type: selectize
        label: Category
        validate:
          type: commalist
```

そして、これはプラグインの configuration-ページでピックアップされるでしょう。

<h2 id="onblueprintcreated-or-accessing-blueprint-data">onBlueprintCreated もしくはブループリントデータへのアクセス</h2>

ブループリントは、ドット付きのフィールドで構成されるため、ブループリントからネストされたフィールドを取得するには、 `.` 記法ではなく、 `/` 記法を使います。

```php
$tabs = $blueprint->get('form/fields/tabs');
```

これにより、次のような特別なデータフィールドにアクセスできます:

```php
$name = $blueprint->get('form/fields/content.name');
$name = $blueprint->get('form/fields/content/fields/.name');
```

後方互換性のため、 `set()` と `get()` の最後の (3番目の) 引数に、特別な分割記号を使うことができます

```php
$tabs = $blueprint->get('form/fields/tabs', null, '/');
```

