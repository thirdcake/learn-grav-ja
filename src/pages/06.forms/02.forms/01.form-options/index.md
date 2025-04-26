---
title: "リファレンス：フォームオプション"
layout: ../../../../layouts/Default.astro
---


### Name

フォームに、必須のオプションはありません。しかし、 [フロントエンドのフォーム](../) で概要を解説したように、最低でも name を付けることは、強く推奨します。

```yaml
form:
    name: my-form
```

この名前は、 Grav サイト全体で **単一でなければいけません** 。なぜなら、システムは、このフォーム名によって、フォームを一意に識別するからです。フォームは、他のページからでも、このフォーム名により参照することができます。

### Method

フォームの送信を、`POST` か、 `GET` か制御します。デフォルトは `POST` です。注意点として、`file` フィールドが入力欄にあるとき、 `enctype="multipart/form-data"` をmethod に追加してください。

```yaml
form:
    method: GET
```


### Action

デフォルトの action は、現在のページにルーティングします。フォームは、フォームがあるページで処理される必要があるので、ほとんどの場合、これは理にかなっています。しかし、異なる拡張子のファイル（もしかすると `.json` ）や、特定のページのアンカーに上書きしたい場合もあるでしょう：

```yaml
form:
    action: '/contact-us#contact-form'
```

フォームを定義したページとは別ページで、フォームの結果を処理したい場合に、その別ページでフォーム処理をすることもできます。これは、オリジナルのフォームで使用されているテンプレートから、レスポンスのテンプレートを変更するテクニックとして使われます：

```yaml
form:
    action: /contact-us/ajax-process
```

`form-messages.html.twig` というファイルがあり、メッセージデータだけを返します。あるいは、これから見ていくような方法もあります...

### Template

通常、フォームを表示するページのテンプレートは、成功/失敗のメッセージ表示や、インラインのバリデーションのレスポンスを完全に処理できます。しかし、ときには、別の Twig テンプレートを使ってレスポンスをお繰り返したほうが便利なこともあります。この良い例として、Ajax で処理したい場合です。成功/失敗のメッセージだけのHTMLをテンプレートで返したいでしょう。そのようなとき、JavaScript によってこれらを注入することができます：

```yaml
form:
    template: form-messages
```

### ID

CSS の `id` を設定できます。もし設定されなければ、フォーム名が使われます。

```yaml
form:
    id: my-form-id
```

### Classes

id と同じく、フォームに class を設定します。この場合、デフォルト値はありません。

```yaml
form:
    classes: 'form-style form-surround'
```

### Attributes

フォーム要素に、カスタムの属性を付け加えます。以下の例では、`key` を属性とし、`value` をその属性に対する値とします。

```yaml
form:
    attributes:
        key: value
```

### Inline Errors

インラインのエラーを表示するかどうかを決定します。トラブルシューティングで重要なツールです。

```yaml
form:
    inline_errors: true
```

### Client-side Validation

クライアントサイドのバリデーションを false にすると、HTML5 のクライアント再度のバリデーションを超えたインラインエラーや詳細なサーバーサイドのバリデーションが見られるようになります。form.yaml ファイルや、フォーム定義によって、このクライアントサイドのバリデーションを無効化できます。

```yaml
form:
    client_side_validation: false
```

### XSS Checks

Grav 1.7 以降では、デフォルトで、すべてのフォームでさまざまな XSS 対策ができます。デフォルト設定は、[セキュリティ設定](../../../01.basics/05.grav-configuration/#security) に書かれています。しかし、フォームごとに、もしくは入力欄ごとに、これらの設定を上書きできます。たとえば、そのフォーム全体でXSS チェックを無効にすることもできます：

```yaml
form:
    xss_check: false
```

> [!Info]  
> **WARNING** XSS チェックの無効化は、推奨しません。入力欄ごとに、特定のルールを上書きしてください。ここでの例は、すべてフォームの入力欄でも機能します。

メインの設定を上書きすることで、個別のルールを有効化したり、無効化したりできます。上書きされていないルールについては、デフォルトの設定が適用されます：

```yaml
form:
    xss_check:
        enabled_rules:
            on_events: false
            invalid_protocols: false
            moz_binding: false
            html_inline_styles: false
            dangerous_tags: false
```

さらに良いことに、特定のプロトコルやタグを許容することもできます：

```yaml
form:
    xss_check:
        safe_protocols:
            - javascript
        safe_tags:
            - iframe
```

### Keep Alive

`keep_alive` を有効化すると、セッションが切れても、フォームの送信に失敗しません。これを有効化することにより、セッションが切れる前にAJAX リクエストが行われ、セッションが 'フレッシュ' に保たれます：

```yaml
form:
    keep_alive: true
```

### Fieldsets

`<fieldset></fieldset>` タグを、フォームの入力欄に設定します。

```yaml
form:
    name: Example Form
    fields:
        example:
            type: fieldset
            id: my-fieldset
            legend: 'Test Fieldset'
            fields:
                first_field: { type: text, label: 'First Field' }
                second_field: { type: text, label: 'Second Field' }
```

上記のフォームは、以下のように出力されます：

```html
<form action="/grav/example/forms" class="" id="my-example-form" method="post" name="Example Form">
  <fieldset id="my-fieldset">
    <legend>Test Fieldset</legend>
    <div class="form-group">
      <div class="form-label-wrapper">
        <label class="form-label">First Field</label>
      </div>
      <div class="form-data" data-grav-default="null" data-grav-disabled="true" data-grav-field="text">
        <div class="form-input-wrapper">
          <input class="form-input" name="data[first_field]" type="text" value="">
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="form-label-wrapper">
        <label class="form-label">Second Field</label>
      </div>
      <div class="form-data" data-grav-default="null" data-grav-disabled="true" data-grav-field="text">
        <div class="form-input-wrapper">
          <input class="form-input" name="data[second_field]" type="text" value="">
        </div>
      </div>
    </div>
  </fieldset>
</form>
```

上記の例では、`my-fieldset` をidとするフィールドセット内に入力欄が表示されます。また、`<legend></legend>` タグが、`legend:` オプションによりサポートされています。

