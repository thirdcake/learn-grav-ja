---
title: 具体例：コンタクトフォーム
layout: ../../../../layouts/Default.astro
lastmod: '2025-09-02'
description: 'Grav サイトで、最も簡単にお問い合わせフォームを作成する方法を解説します。'
---

<h2 id="simple-contact-form">シンプルなコンタクトフォーム</h2>

**Grav の Form プラグイン** は、サイトでフォームを使用する最も簡単な方法です。  
シンプルなお問い合わせフォームの作成方法を見ていきましょう。

<h3 id="a-live-example">試せる実例</h3>

Sora Article スケルトンには、このチュートリアルを読んでいる間に、すぐに分かるフォームページがあります。

[ライブページ](https://demo.getgrav.org/soraarticle-skeleton/contact)

[ページのマークダウンファイル](https://raw.githubusercontent.com/getgrav/grav-skeleton-soraarticle-blog/develop/pages/03.contact/form.md)

<h3 id="setup-the-page">ページのセットアップ</h3>

サイト内のどのページ内にも、フォームを配置できます。  
やるべきことはただ、ページのマークダウンファイル名を `form.md` にするか、 [template](../../../02.content/02.headers/#template) ヘッダーを、フロントマターに追加し、 `form` テンプレートを使えるようにするだけです。

> [!Info]  
> **Grav の Form プラグイン** がページの input をレンダリングするために、ページのテンプレートか、ページの親テンプレートで、`{% block content %}` タグを実装する必要があります。

フォームの fields と process 手順は、ページの YAML フロントマターで定義されなければいけません。  
よって、ページのマークダウンファイルをお好みのエディタで開き、以下のコードを入力してください：

```yaml
---
title: Contact Form

form:
    name: contact

    fields:
        name:
          label: Name
          placeholder: Enter your name
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

        message:
          label: Message
          placeholder: Enter your message
          type: textarea
          validate:
            required: true

        g-recaptcha-response:
          label: Captcha
          type: captcha
          recaptcha_not_validated: 'Captcha not valid!'

    buttons:
        submit:
          type: submit
          value: Submit
        reset:
          type: reset
          value: Reset

    process:
        captcha: true
        save:
            fileprefix: contact-
            dateformat: Ymd-His-u
            extension: txt
            body: "{% include 'forms/data.txt.twig' %}"
        email:
            subject: "[Site Contact Form] {{ form.value.name|e }}"
            body: "{% include 'forms/data.html.twig' %}"
        message: Thank you for getting in touch!
---

# Contact form

Some sample page content
```

> [!Tip]  
> Email プラグインで、 "Email from" と "Email to" のメールアドレスを、あなたのメールアドレスに設定していることを確認してください。

> [!Info]  
> この例では、 Google reCAPTCHA を [captcha フィールド](../02.fields-available/#google-captcha-field-recaptcha) から使っています。これが機能するように、 form プラグインで `site_key` と `secret_key` を設定するようにしてください。 Google reCaptcha を使いたくない場合は、単に `g-recaptcha-response` フィールドと `captcha: true` プロセスを削除してください。

> [!訳注]  
> 上記リンク先のリファレンスによれば、google reCaptcha は recaptcha フィールドになっているので、captcha フィールドが今も有効なのか、分かりません。もし試された方がいらっしゃれば、教えてください。もし新規で作成するなら、cloudflare の turnstile の方が良さそうです。

次に、コンテンツページのフォルダに、 `thankyou/` というフォルダ名のサブフォルダを作り、 `formdata.md` というファイル名の新しいファイルを作ってください。  
そして、そのファイルに以下のコードを貼り付けてください：

```yaml
---
title: Email sent
cache_enable: false
process:
    twig: true
---

## Email sent!
```

これだけです！


<h3 id="live-demo">ライブデモ</h3>

[ライブページ](https://demo.getgrav.org/soraarticle-skeleton/contact)

[ページのマークダウンファイル](https://raw.githubusercontent.com/getgrav/grav-skeleton-soraarticle-blog/develop/pages/03.contact/form.md)

> [!Tip]  
> モジュラーページのフォームは、異なる動作をします。詳細については、 [モジュラーページでフォームを使う](../05.how-to-forms-in-modular-pages/) をお読みください。

ユーザーがフォームを送信するとき、プラグインは（ Grav の Email プラグインで `form` 設定をしているので）あなたにメールを送ります。  
また、入力されたデータを data/ フォルダに保存します。

> [!Note]  
> メールの設定や構成の詳細については、 [Email プラグインのドキュメント](https://github.com/getgrav/grav-plugin-email/blob/develop/README.md) をお読みください。

**Grav データマネージャー** プラグインを有効化すると、 **管理パネルプラグイン** でデータを確認できます。

> [!Tip]  
> 将来的には、Grav が 管理パネルプラグインから動的にフォームを生成できるようにしたいと考えています。

