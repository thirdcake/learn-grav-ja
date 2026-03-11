---
title: フロントエンドのフォーム
layout: ../../../layouts/Default.astro
lastmod: '2025-08-30'
description: 'Grav では、サイトのフロントエンドに、メールフォームなどを設置できます。その概要と、基本的な使い方を解説します。'
---

**Form** プラグインによって、フロントエンドに表示するどんなフォームでも作れます。  
これは、ページで利用できる、フォーム制作キットです。  
先へ進む前に、もしまだなら、 [**Form** プラグイン](https://github.com/getgrav/grav-plugin-form) を忘れずにインストールしておいてください。  
`bin/gpm install form` により実行可能です。

どのように **Form** プラグインが機能するかを理解するため、シンプルなフォームの制作から始めましょう。

> [!Warning]  
> **From 2.0** のリリース以降、 hiden フィールドとして **フォームの名前** を渡す必要があります。 form プラグインが提供する `forms.html.twig` を使っている場合は、自動的に適用されていますが、テーマや別プラグインでデフォルトの `forms.html.twig` を上書きしている場合は、レンダリングする Twig ファイルに、手作業で `{% include "forms/fields/formname/formname.html.twig" %}` を追記する必要があります。

<h2 id="create-a-simple-single-form">シンプルな1つのフォームを作る</h2>

ページにフォームを追加するには、ページを作り、ページファイルに "フォーム" を設定します。  
これは管理パネルからできますし、 `form.md` という名前のページで、ファイルシステムから直接設定することもできます。

では、具体例として、 `user/pages/03.your-form/form.md` を考えてみましょう。

このページのコンテンツは、次のようになります：

```yaml
---
title: "フォーム例付きのページ"
form:
    name: contact-form
    fields:
        name:
          label: Name
          placeholder: Enter your name
          autofocus: on
          autocomplete: on
          type: text
          validate:
            required: true

        email:
          label: Email
          placeholder: Enter your email address
          type: email
          validate:
            required: true

    buttons:
        submit:
          type: submit
          value: Submit
        reset:
          type: reset
          value: Reset

    process:
        email:
          from: "{{ config.plugins.email.from }}"
          to:
            - "{{ config.plugins.email.to }}"
            - "{{ form.value.email }}"
          subject: "[Feedback] {{ form.value.name|e }}"
          body: "{% include 'forms/data.html.twig' %}"
        save:
          fileprefix: feedback-
          dateformat: Ymd-His-u
          extension: txt
          body: "{% include 'forms/data.txt.twig' %}"
        message: Thank you for your feedback!
        display: thankyou

---

# 私のフォーム

通常の **マークダウン** コンテンツは、ここに書いてください...
```

> [!Tip]  
> この例は、ファイルシステムから見たときの `form.md` ファイルのコンテンツです。管理パネルから設定する場合は、 **Expert Mode** でページを開き、3つのダッシュ `---` の間の部分をコピーし、フロントマターに貼り付けてください。

これで、ページコンテンツの後に、フォームが表示されます。  
名前と、 Eメールフィールドと、2つのボタン (1つは送信用、もう1つはリセット用) を持つシンプルなフォームです。  
利用可能なフィールドに関するより詳しい情報は、 [この後のセクションを見てください](./02.fields-available/)。

`送信` ボタンを押すと、何が起こるでしょう？  
`process` に書いたアクションを、順番に実行します。  
この他のアクションについては、 [アクションのリファレンス](./04.reference-form-actions) を見ていただくか、 [自身で作ることもできます](./04.reference-form-actions/#custom-actions) 。

1. `[Feedback] {{ フォームに入力された名前 }}` という題名のメールを送信します。メール本文は、利用中のテーマの `forms/data.html.twig` ファイルで定義されます。
2. `user/data` フォルダに、インプットされたデータを保存するファイルが作成されます。テンプレートは、利用中のテーマの `forms/data.txt.twg` ファイルで定義されます。
3. メッセージがパスすると、 `thankyou` サブページが表示されます。この `tyankyou` ページは、フォームページのサブページでなければいけません。

> [!Tip]  
> **Email** プラグインを、メールが正しく送信されるように、設定しておいてください。

<h2 id="multiple-forms">複数のフォーム</h2>

**Form プラグイン v2.0** がリリースされ、ひとつのページに複数のフォームを設置できるようになりました。  
構文は似ていますが、それぞれのフォームは、フォーム名を別にしなければいけません。  
以下の例では、 `contact-form` と、 `newsletter-form` となっています：

```yaml
forms:
    contact-form:
        fields:
            ...
        buttons:
            ...
        process:
            ...

    newsletter-form:
        fields:
            ...
        buttons:
            ...
        process:
            ...
```

このフォーマットは、ひとつのフォームでも使えます。  
`forms:` に続けて、1つのフォームを定義するだけでできます：

```yaml
forms:
    contact-form:
        fields:
            ...
        buttons:
            ...
        process:
            ...
```

<h2 id="displaying-forms-from-twig">Twig からフォームを表示する</h2>

フォームを追加する最も簡単な方法は、フォームが定義されているページをレンダリングするテンプレートに、Twig ファイルを含めることです。  
たとえば：

```twig
{% include "forms/form.html.twig" %}
```

上記は、 Form プラグインそれ自体から提供される Twig テンプレートを使っています。  
そして、ページのフロントマターで定義したとおりにフォームをレンダリングし、フォームが送信されたときに、成功メッセージやエラーを表示します。

一方で、複数のフォームに対応した、より強力なフォーム表示方法があります。  
以下の方法で、 `form: ` パラメータをフォームを表示したい Twig テンプレートに渡します：

```twig
{% include "forms/form.html.twig" with { form: forms('contact-form') } %}
```

上記の方法を使えば、表示したいフォーム名を選択できます。  
別ページで定義したフォーム名さえ使うことができます。  
サイト全体で、そのフォーム名が単一である限り、 Grav はそのフォームを見つけ出し、レンダリングします！

ひとつのページに、複数のフォームを表示することもできます：

```twig
# Contact Form
{% include "forms/form.html.twig" with { form: forms('contact-form') } %}

# Newsletter Signup
{% include "forms/form.html.twig" with { form: forms('newsletter-form') } %}
```

また別の方法では、フォーム名ではなくページのルーティングを参照して、フォームを表示できます。  
たとえば：

```twig
# Contact Form
{% include "forms/form.html.twig" with { form: forms( {route:'/forms/contact'} ) } %}
```

上記の例では、 `/forms/contact` のルーティングでたどり着くページの最初のフォームが見つかります。

<h2 id="displaying-forms-in-page-content">ページコンテンツ中にフォームを表示する</h2>

ページコンテンツ (たとえば： `default.md` ) の中から、そのページで定義されていないフォームを表示することもできます。  
単に、フォーム名やルーティングをフォームに渡すだけです。

> [!Info]  
> フォーム制御が発火するときに、フォームが動的に処理され、静的にキャッシュされないために、 **Twig プロセス** が有効化されており、**ページキャッシュ** が無効化されている必要があります。

```twig
---
title: Page with Forms
process:
  twig: true
cache_enable: false
---

# Contact Form
{% include "forms/form.html.twig" with {form: forms('contact-form')} %}

# Newsletter Signup
{% include "forms/form.html.twig" with {form: forms( {route: '/newsletter-signup'} ) } %}
```

<h2 id="modular-forms">モジュラーフォーム</h2>

以前のバージョンの Form プラグインでは、 **モジュラー** ページのサブページでフォームを表示するには、**トップレベルのモジュラーページ** でフォームを定義しなければいけませんでした。  
この方法により、フォームが処理され、モジュラーのサブページで表示されました。

**From プラグイン v2.0** からは、他のフォームページと同様、直接、モジュラーのサブページにフォームを定義できます。  
しかし、そこにフォームが見つからなければ、 form プラグインは、'現在のページ' つまりトップレベルのモジュラーページに、フォームが無いか探します。  
つまり、1.0 と完全な後方互換性があります。

ここまでに書かれた具体例のように、モジュラーのサブページでの Twig テンプレートでも、フォームの設定ができます。

> [!Note]  
> モジュラーのサブページに定義したフォームを使う時、 **action:** に親モジュラーページを設定し、 **redirect:** や、 **display:** action を設定してください。 モジュラーのサブページは、 **ルーティング外** であり、そのためブラウザからは到達できないので、送信後に読み込まれるページとしては適切ではありません。

以下が、`form/modular/_form/form.md` での例です。

```yaml
---
title: Modular Form

form:
  action: '/form/modular'
  inline_errors: true
  fields:
    person.name:
      type: text
      label: Name
      validate:
        required: true
        
  buttons:
    submit:
      type: submit
      value: Submit
      
  process:
    message: "Thank you from your submission <b>{{ form.value('person.name') }}</b>!"
    reset: true
    display: '/form/modular'  
---

## Modular Form
```

