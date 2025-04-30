---
title: "リファレンス：フォームのアクション"
layout: ../../../../layouts/Default.astro
---

<h2 id="form-actions">フォームアクション</h2>

前回の具体例で、いくつかのフォームアクションを見てきました。利用できるアクションの詳細を見ていきましょう。

### Email

特定のオプションとともに、Eメールを送ります。

具体例：

```yaml
process:
    - email:
        from: "{{ config.plugins.email.from }}"
        to: "{{ config.plugins.email.to }}"
        subject: "Contact by {{ form.value.name|e }}"
        body: "{% include 'forms/data.html.twig' %}"
```

Email プラグインの設定で指定されたメールアドレスから、同じメールアドレスに、メールを送信します。（これはお問い合わせフォームなので、自分自身に送信します）
他の値を使用したいのでなければ、これらがデフォルト値なので、 from と to の値は、省略できます。
メールには、件名と本文が設定できます。
上記のケースでは、本文は、その時有効化されているテーマの `forms/data.html.twig` ファイルで生成されます（Antimatter と、その他の主要なテーマにはこのテンプレートがありますが、すべてのテーマにあるとは限りません）。

Antimatter では次のように設定します

```twig
{% for field in form.fields %}
    <div><strong>{{ field.label }}</strong>: {{ string(form.value(field.name)|e) }}</div>
{% endfor %}
```

つまり、値をループして、Eメール本文に出力するだけです。

> [!Info]  
> [重要なフォームのオプション](https://github.com/getgrav/grav-plugin-email#emails-sent-with-forms) については、email プラグインのドキュメントを参照してください。 [マルチパートのメッセージ本文](https://github.com/getgrav/grav-plugin-email#multi-part-mime-messages) （アンチ-スパム・スコアに良いです）や、`reply_to` や、[添付ファイル](https://github.com/getgrav/grav-plugin-email#sending-attachments) などがあります。

<h5 id="dynamic-email-attribute">動的なメール属性</h5>

`email.from` フィールドを、Form の input から設定する具体例は、コンテンツを取得して、次のように使います：

`from: "{{ form.value.email|e }}"`

このケースでは、フォームから "email" フィールドを取得し、 "from" 属性に使用します。このようにして、サイトオーナーは、メールを受信し、フォームに入力されたメールアドレスに直接返信できるようになります。

### Redirect

ユーザーを、他のページへリダイレクトします。このアクションは、即時実行されるので、これを使用するときは、おそらくアクションリストの最後に置く必要があります。

```yaml
process:
    - redirect: '/forms/landing-page'
```

フォーム入力や、 hidden フィールドから、 `redirect` フィールドのすべての一部を設定したい場合もあるかもしれません。内容を取得して利用するには、次のようにします：

`redirect: "/path to/location/{{ form.value.hiddenfield }}"`

上記のケースでは、フォームの "hiddenfield" フィールドを取得し、リダイレクト先の最後の部分に利用しています。これは、たとえば、完了時にダウンロードにリダイレクトするフォームを作る時に便利です。 

### Message

フォーム送信後に表示されるメッセージを設定できます。

```yaml
process:
    - message: Thank you for your feedback!
```

デフォルトでは、メッセージは `form` 要素の最初にレンダリングされます。

ただし、オプションで `display` か、 `redirect` により、表示を修正できます。

<h4 id="validation-message">バリデーション メッセージ</h4>

バリデーションに失敗したときに、メッセージを表示することができます。たとえば：

```yaml
username:
   type: text
   label: Username
   validate:
     required: true
     message: My custom message when validation fails!
```

これにより、バリデーションが失敗したことを、ユーザーに知らせるためのカスタムのメッセージを作成できます。

### Display

フォームの送信後、フォームの表示がサブページに更新されます。なので、たとえば、フォームが `/form` にあり、サブページが `/form/thankyou` にあるとき、次のようなコードが使えます。

```yaml
process:
    - display: thankyou
```

絶対パスを埋め込みたい場合、 `/` を先頭に使ってください。
たとえば：  `site.com/thankyou` のときは、 `display: /thankyou` としてください。

Form プラグインは、 `formdata` テンプレートを提供します。フォームが送信された結果を出力するときに、目的のページへ処理するのに適切なテンプレートです。上記の例では、 `pages/form/thankyou/formdata.md` 作成できます。

Antimatter や、それと互換性のあるテーマでは、`formdata.html.twig` テンプレートを提供します。それは以下のようなものです：

```twig
{% extends 'partials/base.html.twig' %}

{% block content %}

    {{ content|raw }}

    <div class="alert">{{ form.message|e }}</div>
    <p>Here is the summary of what you wrote to us:</p>

    {% include "forms/data.html.twig" %}

{% endblock %}
```

もし `thankyou/formdata.md` ページが、以下のようであれば、

```yaml
---
title: Email sent
cache_enable: false
process:
    twig: true
---

## Email sent!
```

"Email sent!" というタイトルのページが出力され、続けて、確認メッセージと前のページで入力された内容が表示されます。

あらゆるページタイプを、遷移先のページとして利用できます。独自のページを作成し、遷移先のページタイプを適切に設定してください。

### Save

フォームデータをファイルに保存します。ファイルは、 `user/data` フォルダの `form.name` パラメータから名付けられたサブフォルダに保存されます。この処理を成功させるのに、フォームには、名前が **必要です** 。また、データの保存前に、サブフォルダは、適切なパーミッションのもと、作成されていなければいけません。サブフォルダが存在しないとき、新しいディレクトリを自動で作ってくれるわけではありません。たとえば：

> [!Info]  
> `fileprefix` 及び `body` には、Twig のマークアップを含めることができます。

```yaml
process:
    - save:
        fileprefix: feedback-
        dateformat: Ymd-His-u
        extension: txt
        body: "{% include 'forms/data.txt.twig' %}"
        operation: create
```

本文は、Antimatter や、更新されたテーマによる `templates/forms/data.html.twig` ファイルを使って表示されます。

> [!Note]  
> `operation` は、 `create` （デフォルト）か、 `add` です。 `create` は、新しいファイルを、フォームの送信ごとに作成し、 `add` は、ひとつのファイルに追記します。

> [!Note]  
> `add` オペレーションでは、静的なファイル名を使用することに注意してください：以下の例を参考に定義してください。

```yaml
process:
    - save:
        filename: feedback.txt
        body: "{% include 'forms/data.txt.twig' %}"
        operation: add
```

### Captcha

サーバーサイドでも captcha を検証するには、captcha プロセスアクションを追加してください。

```yaml
    process:
        - captcha:
            recaptcha_secret: ENTER_YOUR_CAPTCHA_SECRET_KEY
```

> [!Info]  
> `recaptcha_secret` はオプションです。もし Form プラグインを設定済みだった場合に、その設定値を使います。

<h3 id="user-ip-address">ユーザーの IP アドレス</h3>

ユーザーの IP アドレスを出力に表示します。出力プロセスで確実に使われるように、 'form.md' 内の email / save プロセスの上に配置してください。

```yaml
process:
    - ip:
        label: User IP Address
```

### Timestamp

フォーム送信のタイムスタンプを、出力に追加します。出力プロセスで確実に使われるように、 'form.md' 内の email / save プロセスの上に配置してください。

```yaml
process:
    - timestamp:
        label: Submission Timestamp
```

<h3 id="reset-the-form-after-submit">送信後にフォームをリセット</h3>

デフォルトでは、送信後にフォームはクリアされません。そのため、 `display` アクションを指定しない状態で、ユーザーがフォームページに戻った場合、入力されたデータがそのまま残ります。
これを回避するなら、 `reset` アクションを追加してください：

```yaml
process:
    - reset: true
```

<h3 id="remember-field-values">フィールド値を記憶する</h3>

`remember` アクションを使うと、ユーザーがフォームを最後に送信した際に設定した _いくつかの_ フィールド値を "呼び出す" ことができます。
これは、繰り返し送信するときに便利です。たとえば、送信者に関する情報が必要な匿名の送信のような場合です。

> [!Note]  
> [HTML5](https://developer.mozilla.org/en-US/docs/Web/HTML/Attributes/autocomplete) と、 [Grav の　Form プラグイン](../02.fields-available/#common-field-attributes) では、ブラウザ経由の限定的な方法で、すでに提供されているので、ぜひ利用してください。ただし、一部のユーザーやフィールドでは、確実に機能しない場合があります。

> [!Note]  
> `remember` アクションでは、最後の値を保存するために **cookie を利用します** 。そのため、特定のデバイスや、サイトからの Cookie を許可しているブラウザでのみ機能します。

このアクションを使うには、記憶しておきたいフィールドの名前をリストするだけです。

たとえば、オンラインの医療紹介フォームは、良いユースケースです。これらは、同じコンピュータから入力され、いくつかのフィールド値が変更されることは稀です。そして、何度も入力するのは面倒です。

```yaml
process:
    - remember:
        - referrer-name
        - referrer-address
        - referrer-specialty
        - preferred-practitioner
```

<h2 id="custom-actions">カスタムアクション</h2>

You can "hook" into a form processing and perform any kind of operation. Perform custom processing, add data for an online web application, even save to a database.

To do this, in the form process field add your own processing action name, for example 'yourAction'.

```yaml
process:
    yourAction: true
```

Then, create a simple plugin.

In its main PHP file, register for the event `onFormProcessed`

```php
namespace Grav\Plugin;
use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

class EmailPlugin extends Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            'onFormProcessed' => ['onFormProcessed', 0]
        ];
    }
}
```

Then provide a handler for the saveToDatabase action:

```php
    public function onFormProcessed(Event $event)
    {
        $form = $event['form'];
        $action = $event['action'];
        $params = $event['params'];

        switch ($action) {
            case 'yourAction':
                //do what you want
        }
    }
```

If your processing might go wrong and you want to stop the next form actions, which are executed in series, you can stop the processing by calling `stopPropagation` on the $event object:

```php
$event->stopPropagation();
return;
```

Sample code with form handling is available in the Form plugin, and in the Email plugin repositories.

#### An example of custom form handling

The Form plugin offers this ability of sending emails, saving files, setting status messages and it’s really handy.
Sometimes however you need total control. That’s for example what the Login plugin does.

It defines the `login.md` page frontmatter:

```yaml
title: Login
template: form

form:
    name: login

    fields:
        - name: username
          type: text
          placeholder: Username
          autofocus: true

        - name: password
          type: password
          placeholder: Password
```

The Forms plugin correctly generates and shows the form. Notice there’s no `process` defined.

The form `buttons` are missing too, since they’re manually added in `templates/login.html.twig`. That’s where the form `action` and `task` are defined too.

In this case, `task` is `login.login`, and `action` is set to the page url.

When a user presses 'Login' in the form, Grav calls the `onTask.login.login` event.

`user/plugins/login/login.php` hooks up to `onTask.login.login` to its `classes/controller.php` file, and that's where the authentication happens.

