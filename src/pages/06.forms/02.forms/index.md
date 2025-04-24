---
title: "フロントエンドのフォーム"
layout: ../../../layouts/Default.astro
---

**Form** プラグインによって、フロントエンドに表示するどんなフォームでも作れます。これは、ページで利用できる、フォーム制作キットです。先へ進む前に、もしまだなら、 [**Form** プラグイン](https://github.com/getgrav/grav-plugin-form) を忘れずにインストールしておいてください。`bin/gpm install form` により実行可能です。

どのように **Form** プラグインが機能するかを理解するため、シンプルなフォームの制作から始めましょう。

> [!Warning]  
> **From 2.0** のリリース以降、hidenフィールドとして **フォームの名前** をパスする必要があります。form プラグインが提供する `forms.html.twig` を使っている場合は、自動的に適用されていますが、テーマや別プラグインでデフォルトの `forms.html.twig` を上書きしている場合は、レンダリングする Twig ファイルに、手作業で `{% include "forms/fields/formname/formname.html.twig" %}` を追記する必要があります。

<h2 id="create-a-simple-single-form">シンプルな1つのフォームを作る</h2>

To add a form to a page of your site, create a page, and set its Page File to "Form". You can do it via the Admin Panel, or via filesystem directly by naming the page `form.md`.

So, for example, `user/pages/03.your-form/form.md`.

The contents of this page will be:

```yaml
---
title: A Page with an Example Form
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

# My Form

Regular **markdown** content goes here...
```

!!! This is the content of the `form.md` file, when viewed via file-system. To do this via Admin Plugin, open the page in **Expert Mode**, copy the part between the triple dashes `---`, and paste it in the Frontmatter field.

This is enough to show a form in the page, below the page's content. It is a simple form with a name, email field, two buttons: one to submit the form and one to reset the fields. For more information on the available fields that are provided by the Form plugin, [check out the next section](fields-available).

What happens when you press the `Submit` button?  It executes the `process` actions in series. To find out about other actions, [check out the available options](reference-form-actions) or [create your own](reference-form-actions#custom-actions).

1. An email is sent to the email entered, with the subject `[Feedback] [name entered]`. The body of the email is defined in the `forms/data.html.twig` file of the theme in use.

2. A file is created in `user/data` to store the form input data. The template is defined in `forms/data.txt.twig` of the theme in use.

3. The `thankyou` subpage is shown, along with the passed message. The `thankyou` page must be a subpage of the page containing the form.

!!! Make sure you configured the **Email** plugin to ensure it has the correct configuration in order to send email successfully.

<h2 id="multiple-forms">複数のフォーム</h2>

With the release of **Form Plugin v2.0**, you are now able to define multiple forms in a single page.  The syntax is similar but each form is differentiated by the name of the form, in this case `contact-form` and `newsletter-form`:

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

You can even use this format for single forms, by just providing one form under `forms:`:

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

<h2 id="displaying-forms-from-twig">Twigからフォームを表示する</h2>

The easiest way to include a form is to simply include a Twig file in the template that renders the page where the form is defined.  For example:

```twig
{% include "forms/form.html.twig" %}
```

This will use the Twig template provided by the Form plugin itself.  In turn, it will render the form as you have defined in the page, and handle displaying a success message, or errors, when the form is submitted.

There is however a more powerful method of displaying forms that can take advantage of the new multi-forms support.  With this method you actually pass a `form:` parameter to the Twig template specifying the form you wish to display:

```twig
{% include "forms/form.html.twig" with { form: forms('contact-form') } %}
```

Using this method, you can choose a specific name of a form to display.  You can even provide the name of a form defined in other pages.  As long as all your form names are unique throughout your site, Grav will find and render the correct form!

You can even display multiple forms in one page:

```twig
# Contact Form
{% include "forms/form.html.twig" with { form: forms('contact-form') } %}

# Newsletter Signup
{% include "forms/form.html.twig" with { form: forms('newsletter-form') } %}
```

An alternative way to display a form is to reference the page route rather than the form name using an array, for example:

```twig
# Contact Form
{% include "forms/form.html.twig" with { form: forms( {route:'/forms/contact'} ) } %}
```

This will find the first form from the page with route `/forms/contact`

<h2 id="displaying-forms-in-page-content">ページコンテンツ中にフォームを表示する</h2>

You can also display a form from within your page content (for example `default.md`) directly without that page even having a form defined within it. Simply pass the name or route to the form.

!!  **Twig processing** should be enabled and **page cache** should be disabled to ensure the form is dynamically processed on the page and not statically cached and form handling can occur.

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

With previous versions of the Form plugin, to get a form to display in a modular sub-page of your overall **modular** page, you had to define the form in the **top-level modular page**.  This way the form would be processed and available to display in the modular sub-page.

In **Form v2.0**, you can now define the form directly in the modular sub-page just like any other form.  However, if not found, the form plugin will look in the 'current page', i.e. the top-level modular page for a form, so it's fully backwards compatible with the 1.0 way of doing things.

You can also configure your Modular sub-page's Twig template to use a form from another page, like the examples above.

! When using a form defined in a modular sub-page you should set the **action:** to the parent modular page and configure your form with a **redirect:** or **display:** action, as this modular sub-page is not a suitable page to load on form submission because it is **not routable**, and therefore not reachable by a browser.  

Here's an example that exists at `form/modular/_form/form.md`:

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

