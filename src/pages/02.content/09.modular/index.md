---
title: "モジュラーページ"
layout: ../../../layouts/Default.astro
---

**モジュラーページ** の概念は、最初のうちは多少トリッキーに映るかもしれません。しかし実際に使ってみると、その便利さを理解するでしょう。 **モジュラーページ** は、統一されたひとつのページを構成するために、個々のモジュールページのコレクションを上から積み重ねたものです。これにより、複雑な構造のページも **LEGOブロック** のように作れます。LEGOが嫌いな人なんていないでしょう?!

<h2 id="what-are-modular-pages-and-what-are-they-not">モジュラーページとは何か？ そして何でないか？</h2>

Gravでは、[ページ](../01.content-pages) という概念は幅広いもので、webサイトで考えうることのほとんど全てを網羅しうるものです。モジュラーページは、このページ概念のひとつですが、通常ページとは違うものです。通常ページは、他のコンテンツ（他のページや子ページ）を利用せずにレンダリングされ、表示されるという意味で、独立しています。しかしながら、モジュラーページは、子ページを持ちません。これはシンプルなページ構造がイメージされるでしょう。

`domain.com/books` という通常ページがあったとして、その詳細に、売出し中の本の情報があったとします。このページには、いくつかの子ページがあり、たとえば、`domain.com/books/gullivers-travels` とか、 `domain.com/books/the-hobbit` などだったとします。これらのフォルダは、Grav内では、`pages/books` であり、 `pages/books/gullivers-travels` であり、 `pages/books/the-hobbit` です。このような構造は、モジュラーページではありません。

モジュラーページは、子ページを持たないというより、ページを構成する **モジュール** を持っているのです。トップレベルのページの下に、さまざまな本があるというより、 **同じページ** にモジュールを表示します。Gulliver's Travels も、The Hobbit も、`pages/books/_gullivers-travels` や `pages/books/_the-hobbit` にありながら、`domain.com/books` に表示されます。よって、モジュラーページは、直接的には通常ページと互換性がありません。モジュラーページ独自の構造を持ちます。

<h2 id="example-folder-structure">フォルダ構造の例</h2>

Using our **One-Page Skeleton** as an example, we will explain how Modular Pages work in greater detail.

The **Modular Page** itself is assembled from pages that exist in subfolders found under the page's primary folder. In the case of our One-Page Skeleton, this page is located in the `01.home` folder. Within this folder is a single `modular.md` file which tells Grav which subpages to pull in to assemble the Modular Page, and which order to display them in. The name of this file is important because it instructs Grav to use the `modular.html.twig`-template from the current theme to render the page.

These subpages are in folders with names that begin with an underscore (`_`). By using an underscore, you are telling Grav that these are **Modules**, not standalone pages. For example, subpage-folders can be named `_features` or `_showcase`. These pages are **not routable** - they cannot be pointed to directly in a browser, and they are **not visible** - they do not show up in a menu.

In the case of our One-Page Skeleton, we have created the folder structure pictured below.

![Listing Page](modular-explainer-2.jpg)

Each subfolder contains a Markdown-file which acts as a page.

The data within these Module-folders - including Markdown-files, images, etc. - is then pulled and displayed on the Modular page. This is accomplished by creating a primary page, defining a [Page Collection](../03.collections) in the primary page's YAML FrontMatter, then iterating over this Collection in a Twig-template to generate the combined HTML page. A theme should already have a `modular.html.twig` template that will do this and is used when you create a Modular Page type. Here's a simple example from a `modular.html.twig`:

```twig
{% for module in page.collection() %}
    {{ module.content|raw }}
{% endfor %}
```

Here is an example of the resulting modular page, highlighting the different modular folders which are used.

![Listing Page](modular-explainer-1.jpg)

<h2 id="setting-up-the-primary-page">主ページのセットアップ</h2>

As you can see, each section pulls content from a different Module-folder. Determining which Module-folders are used, and in what order, happens in the primary Markdown-file in the parent folder of the Module. Here is the content of the `modular.md` file in the `01.home` folder.

```yaml
---
title: One Page Demo Site
menu: Home
onpage_menu: true
body_classes: "modular header-image fullwidth"

content:
    items: '@self.modular'
    order:
        by: default
        dir: asc
        custom:
            - _showcase
            - _highlights
            - _callout
            - _features
---
```

As you can see, there is no actual content in this file. Everything is handled in the YAML FrontMatter in the header. The page's **Title**, **Menu** assignment, and other settings you would find in a typical page are found here. The [Content](../02.headers#ordering-options) instructs Grav to create the content based on a Collection of modular pages, and even provides a custom manual order for them to render.

<h2 id="modules">モジュール</h2>

![Listing Page](modular-explainer-3.jpg)

The Markdown-file for each Module can have its own template, settings, etc. For all intents and purposes, it has most of the features and settings of a regular page, it just isn't rendered as one. We recommend page-wide settings, such as **taxonomy**, be placed in the main Markdown-file that controls the whole page.

The Modular Pages themselves are handled just like regular Pages. Here is an example using the `text.md` file in the `_callout` page which appears in the middle of the Modular page.

```markdown
---
title: Homepage Callout
image_align: right
---

## Content Unchained

No longer are you a _slave to your CMS_. Grav **empowers** you to create anything from a [simple one-page site](#), a [beautiful blog](#), a powerful and feature-rich [product site](#), or pretty much anything you can dream up!
```

As you can see, the header of the page contains basic information you might find on a regular page. It has its own title that can be referenced, and [custom page options](../02.headers#custom-page-headers), such as the alignment of the image can be set here, just as it would on any other page.

The template file for the `text.md` file should be located in the `/templates/modular`-folder of your theme, and should be named `text.html.twig`. This file, like any Twig-template file for any other page, defines the settings, as well as any styling-differences between it and the base page.

```twig
<div class="modular-row callout">
    {% set image = page.media.images|first %}
    {% if image %}
        {{ image.cropResize(400,400).html('','','align-'~page.header.image_align)|raw }}
    {% endif %}
{{ content|raw }}
</div>
```

Generally, Modular Pages are very simple. You just have to get used to the idea that each section in your page is defined in a Module that has its own folder below the actual page. They are displayed all at once to your visitors, but organized slightly differently than regular pages. Feel free to experiment and discover just how much you can accomplish with a Modular Page in Grav.
