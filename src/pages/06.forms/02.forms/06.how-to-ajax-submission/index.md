---
title: ハウツー：Ajax送信
layout: ../../../../layouts/Default.astro
lastmod: '2025-05-02'
---
<h2 id="submitting-forms-via-xhr-ajax">XGR/Ajax でフォームを送信</h2>

フォーム処理のデフォルトのメカニズムは、 HTML 標準のフォーム送信によっており、 HTML フォームは、 `POST` または `GET` （デフォルトは `POST` ）でサーバーに送られます。送られたフォームは、 [バリデーション](../02.fields-available/) され、 [処理](../04.reference-form-actions/) された後に、結果がフォームに送り返され（もしくは、 [リダイレクトされたページへ遷移し](../04.reference-form-actions/#redirect) ）、メッセージが表示されたり、必要に応じて再送信するための編集ができたりします。

これは、ページのリロードを伴うため、ときどき、望ましくないこともあります。このような場合、 Ajax や XHR を使った JavaScript 経由で、フォームを送信することがより良い選択になります。幸運なことに、 Grav のフォーム機能は、このタスクに対応しています。

<h2 id="automatic-approach-form-plugin-v7-3-0">自動的なアプローチ（From プラグイン `v7.3.0` 以上）</h2>

Form プラグインのバージョン `7.3.0` のリリースにより、XHR によるフォーム送信機能が、素早いセットアップオプションで利用可能になりました。XHR により、フォームのその場所での処理となり、ページ全体のリロードは不要になります。

これを有効にするには、単純に、このオプションを Form のブループリントに追記するだけです：

```yaml
xhr_submit: true
```

`action:` や、 `template:` 、 `id:` さえ設定する必要はありません。1ページに複数の ajax フォームがあったとしても、プラグインは '機能' します。これは、新しい `form-xhr.html.twig` テンプレートを使い、 vanilla JS（ライブラリを利用しないJavaScript）コードでリクエストを行います。

> [!Info]  
> このアプローチでは、 XHR を使って、フォーム全体を送信し、フォームの HTML 全体をレスポンスで書き換えます。これはシンプルなアプローチですが、必要に応じて独自の高度なソリューションを作成できます。

> [!Tip]  
> XHR リクエストに使用される JavaScript コードは、 `form/layouts/xhr.html.twig` にあります。必要であれば、これを あなたのテーマの `templates` フォルダに（パス構造を維持しながら）コピーし、必要な修正をほどこしてください。

<h2 id="manual-approach-required-for-form-plugin-v7-3-0">手動のアプローチ（Form プラグイン `v7.3.0` 未満）</h2>

<h3 id="creating-the-form">フォームを作成する</h3>

You can create any standard form you like, so for this example, we'll keep the form as simple as possible to focus on the Ajax handling parts. First, we'll create a form in a page called: `forms/ajax-test/` and create a form page called `form.md`:

```yaml
---
title: Ajax Test-Form
form:
    name: ajax-test-form
    action: '/forms/ajax-test'
    template: form-messages
    refresh_prevention: true

    fields:
        name:
            label: Your Name
            type: text

    buttons:
        submit:
            type: submit
            value: Submit

    process:
        message: 'Thank you for your submission!'
---
```

As you can see this is a very basic form that simply asks for your name and provides a submit button.  The only thing that stands out is the `template: form-messages` part.  As outlined in the [Frontend Forms](../) section, you can provide a custom Twig template with which to display the result of the form processing.  This is a great way for us to process the form, and then simply return the messages via Ajax and inject them into the page.  There is already a `form-messages.html.twig` template provided with the forms plugin that does just this.

> [!Info]  
> NOTE: We use a hard-coded `action: '/forms/ajax-test'` so the ajax has a consistent URL rather than letting the form set the action to the current page route. This resolves an issue with the Ajax request not handling redirects properly. This can otherwise cause issues on the 'home' page. It doesn't have to be the current form page, it just needs to be a consistent, reachable route.

![](simple-form.png)

### The page content

In this same page, we need to put a little HTML and JavaScript:


```twig
<div id="form-result"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#ajax-test-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        
        const result = document.querySelector('#form-result');
        const action = form.getAttribute('action');
        const method = form.getAttribute('method');
        
        fetch(action, {
            method: method,
            body: new FormData(form)
        })
        .then(function(response) {
            if (response.ok) {
                return response.text();
            } else {
                return response.json();
            }
        })
        .then(function(output) {
            if (result) {
                result.innerHTML = output;
            }
        })
        .catch(function(error) {
            if (result) {
                result.innerHTML = 'Error: ' + error;
            }
                
            throw new Error(error);
        });
    });
});
</script>
```

```twig
<div id="form-result"></div>

<script>
$(document).ready(function(){

    var form = $('#ajax-test-form');
    form.submit(function(e) {
        // prevent form submission
        e.preventDefault();

        // submit the form via Ajax
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            dataType: 'html',
            data: form.serialize(),
            success: function(result) {
                // Inject the result in the HTML
                $('#form-result').html(result);
            }
        });
    });
});
</script>
```

First we define a div placeholder with the ID `#form-result` to use as a location to inject the form results.

We are using JQuery syntax here for simplicity but obviously, you can use whatever JavaScript you like as long as it performs a similar function.  We first stop the default submit action of the form and make an Ajax call to the form's action with the form's data serialized.  The result of this call is then set back on that div we created earlier.

![](submitted-form.png)

