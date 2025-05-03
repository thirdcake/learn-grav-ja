---
title: "一般的なレシピ"
layout: ../../../layouts/Default.astro
---

このページでは、一般的な Grav に関するさまざまな問題と、それぞれの解決策を紹介します。

<h2 id="change-the-php-cli-version">PHP CLI のバージョンを変更</h2>

ターミナルで、ときどき、web サーバーの PHP バージョンと異なる PHP が使われていることがあります。

CLI で動いている PHP のバージョンをチェックするには、`php -v` コマンドを実行できます。
PHP バージョンが 5.5.9 未満だったら、Grav は少なくとも PHP 5.5.9 以上がシステム要件であるため、動かないでしょう。

> [!訳注]  
> おそらく、上記のバージョンは古い情報で、Grav 1.7 は PHP 7.3.6 以上、Grav 1.8 は、PHP 8.2 以上になると思われます。

どのように直せるでしょう？

ユーザーのホームフォルダにある `.bashrc` や、 `.bash_profile` ファイルに、いくつかの設定を書く必要があります。ユーザーフォルダに、これらのファイルがまだ無い場合は、ファイルを作成してください。これらは隠しファイルなので、表示するためには `ls -al` コマンドをする必要があるかもしれません。一度設定を追記すれば、新しくターミナルのセッションをスタートするだけで、それらの設定が適用されています。

設定例は、以下のとおりです：

```bash
alias php="/usr/local/bin/php53"
export PHP_PATH = /usr/local/bin/php53
```

別の方法では、以下のように追加してください：

```bash
# .bash_profile

# Get the aliases and functions
if [ -f ~/.bashrc ]; then
        . ~/.bashrc
fi

# User specific environment and startup programs

PATH=/usr/local/lib/php-5.5/bin:$PATH:$HOME/bin

export PATH
```

より最近の PHP バージョンのバイナリがある場所の正確な path は、あなたのシステムのセットアップがどのようになっているか次第です。ホスティングのドキュメントに見つかるかもしれませんし、どこにも見つからなければ、ホスティングのセットアップに尋ねることもできます。

または、`/usr/local/bin` フォルダや `/usr/local/lib` フォルダ下の `php-何か` ファイルやフォルダに見つかるかもしれません。`ls -la /usr/local/lib/ |grep -i php` で探せます。

<h2 id="creating-a-simple-gallery">シンプルなギャラリーを作る</h2>

##### Problem:

よくある web デザインで、ページ内に、いくつかの方法でレンダリングされるギャラリーが欲しいということがあります。新しく家族となったペットの写真や、これまでのデザインワークのポートフォリオ、閲覧者に向けてディスプレイし、販売したい製品のカタログなどです。この例では、下にキャプションの付いたたくさんの写真を表示するしたいだけだと仮定します。もちろん、この例は、他の利用方法にも応用できます。

##### Solution:

この課題への最もシンプルな解決策は、 Grav の [メディア機能](../../02.content/07.media/) を使うことです。この機能により、ページから、そのフォルダ内の画像が利用可能となります。

Let's assume you have a page you've called `gallery.md` and also you have a variety of images in the same directory. The filenames themselves are not important as we will just iterate over each of the images.  Because we want to have extra data associated with each image, we will include a `meta.yaml` file for each image.  For example, we have a few images:

```yaml
- fido-playing.jpg
- fido-playing.jpg.meta.yaml
- fido-sleeping.jpg
- fido-sleeping.jpg.meta.yaml
- fido-eating.jpg
- fido-eating.jpg.meta.yaml
- fido-growling.jpg
- fido-growling.jpg.meta.yaml
```

Each of the `.jpg` files are a relatively good size that is appropriate for a full-size version, 1280px x 720px in size. Each of the `meta.yaml` files contain a few key entries, let's look at `fido-playing.jpg.meta.yaml`:

```yaml
title: Fido Playing with his Bone
description: The other day, Fido got a new bone, and he became really captivated by it.
```

You have **complete control** over what you put in these meta files, they can be absolutely anything you need.

Now we need to display these images in reverse chronological order so the newest images are shown first and display them.  Because our page is called `gallery.md` we should create an appropriate `templates/gallery.html.twig` to contain the rendering logic we need:

```twig
{% extends 'partials/base.html.twig' %}

{% block content %}
    {{ page.content|raw }}

    <ul>
    {% for image in page.media.images %}
    <li>
        <div class="image-surround">
            {{ image.cropResize(300,200).html|raw }}
        </div>
        <div class="image-info">
            <h2>{{ image.meta.title }}</h2>
            <p>{{ image.meta.description }}</p>
        </div>
    </li>
    {% endfor %}
    </ul>

{% endblock %}
```

For a modular gallery to be displayed inside another page, remove the following code from the Twig file in order to make it work:

```twig
{% extends 'partials/base.html.twig' %}

{% block content %}
    {{ page.content|raw }}
```

and

```twig
{% endblock %}
```

Basically, this extends the standard `partials/base.html.twig` (assuming your theme has this file), it then defines the `content` block and provides the content for it.  The first thing we do is echo out any `page.content`.  This would be the content of the `gallery.md` file, so it could contain a title, and a description of this page.

The next section simply loops over all the media of the page that are **images**.  We are outputting these in an unordered list to make the output semantic, and easy to style with CSS.  we are assigning each image the variable name `image` and then we are able to perform a simple `cropResize()` method to resize the image to something suitable, and then below it, we provide an information section with the `title` and `description`.

You could make a more advanced gallery-implementation by using creating filters for camera-data, with the [EXIF](../../03.themes/04.twig-tags-filters-functions/03.functions/#exif)-function.

## Render content in columns

##### Problem:

A question that has come up several times is how to quickly render a single page in multiple columns.

##### Solution:

There are many potential solutions, but one simple solution is to divide up your content into logical sections using a delimiter such as the HTML `<hr />` or *thematic break* tag.  In markdown, this is represented by 3 or more dashes or `---`.  We simply create our content and separate our sections of content with these dashes:

**columns.md**
```md
---
title: 'Columns Page Test'
---

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas arcu leo, hendrerit ut rhoncus eu, dictum vitae ligula. Suspendisse interdum at purus eget congue. Aliquam erat volutpat. Proin ultrices ligula vitae nisi congue sagittis. Nulla mollis, libero id maximus elementum, ante dolor auctor sem, sed volutpat mauris nisl non quam.

---
Phasellus id eleifend risus. In dui tellus, dignissim id viverra non, convallis sed ante. Suspendisse dignissim, felis vitae faucibus dictum, dui mi tempor lectus, non porta elit libero quis orci. Morbi porta neque quis magna imperdiet hendrerit.

---
Praesent eleifend commodo purus, sit amet viverra nunc dictum nec. Mauris vehicula, purus sed convallis blandit, massa sem egestas ex, a congue odio lacus non quam. Donec vitae metus vitae enim imperdiet tempus vitae sit amet quam. Nam sed aliquam justo, in semper eros. Suspendisse magna turpis, mollis quis dictum sit amet, luctus id tellus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aenean eu rutrum mi.
```
!! Note: the extra line after the column and before the `---`.  This is because if you put a triple dash right underneath text, it's actually interpreted as a header.

Then we simply need to render this content with a `columns.html.twig` template (as the page file was named `columns.md`):

```twig
{% extends 'partials/base.html.twig' %}

{% block content %}
    <table>
        <tr>
            {% for column in page.content|split('<hr />') %}
            <td>{{ column|raw }}</td>
            {% endfor %}
        </tr>
    </table>
{% endblock %}
```

You can see how the content is being **split** by the `<hr />` tag and converted into an array of 3 columns which we loop over and render.  In this example we are using a simple HTML table tag, but you could use anything you wish.

!! Note: When using plugin page-toc, you'll need use `|split('<hr>')` since the page-toc plugin cleanses the HTML code.

## Really simple css image slider

##### Problem:

You need an image slider without any overhead.

##### Solution:

This recipe is for 4 images and a page called `slider.md`! Simply put the images where the .md file is. Next, create a new Twig template and extend `base.html.twig`.


```twig
{% extends 'partials/base.html.twig' %}

{% block content %}

    <div id="slider">
        <figure>
        {% for image in page.media.images %}
            {{ image.html|raw }}
        {% endfor %}
        </figure>
    </div>

    {{ page.content|raw }}
{% endblock %}
```

For modular slider, please remove the
```twig
{% extends 'partials/base.html.twig' %}

{% block content %}
```

and

```twig
{% endblock %}
```

from the previous Twig file.

Time for css stuff. Add this to your _custom.scss

```scss
@keyframes slidy {
    0% { left: 0%; }
    20% { left: 0%; }
    25% { left: -100%; }
    45% { left: -100%; }
    50% { left: -200%; }
    70% { left: -200%; }
    75% { left: -300%; }
    95% { left: -300%; }
    100% { left: -400%; }
}
body { margin: 0; }
div#slider {
    overflow: hidden;
    margin-top: -3rem;
    max-height: 30rem;
}
div#slider figure img { width: 20%; float: left; }
div#slider figure {
    position: relative;
    width: 500%;
    margin: 0;
    left: 0;
    animation: 30s slidy infinite;
}
```

That's all.

<h2 id="wrapping-markdown-into-html">マークダウンを HTML に入れる</h2>

On some pages you might want to wrap parts of the markdown content into some custom html code instead of creating a new Twig template.

To achieve this you follow these steps:

in your system configuration file `user/config/system.yaml` make sure to activate the markdown extra option:

```yaml
pages:
  markdown:
    extra: true
```

in your wrapper tag make sure to add the parameter `markdown="1"` to activate processing of markdown content:

```md
<div class="myWrapper" markdown="1">
# my markdown content

this content is wrapped into a div with class "myWrapper"
</div>
```

done.

<h2 id="add-a-recent-post-widget-to-your-sidebar">サイドバーに最近の投稿ウィジェットを追加する</h2>

#### Problem:

You want to create a recent post widget on the sidebar

#### Solution:

It's always possible to create a partial template extending `partials/base.html.twig` (see other solutions on this page), but here you're going to create a full template instead. The final code for your Twig template is shown below:

```twig
<div class="sidebar-content recent-posts">
    <h3>Recent Posts</h3>
    {% for p in page.find('/blog').children.order('date', 'desc').slice(0, 5) %}
        {% set bannerimage = p.media['banner.jpg'] %}
        <div class="recent-post">
            {% if bannerimage %}
                <div class="recent-post-image">{{ bannerimage.cropZoom(60,60).quality(60) }}</div>
            {% else %}
                <div class="recent-post-image"><img src="{{ url('theme://images/logo.png') }}" width="60" height="60"></div>
            {% endif %}
            <div class="recent-post-text">
                <h4><a href="{{p.url}}">{{ p.title }}</a></h4>
                <p>{{ p.date|date("M j, Y")}}</p>
            </div>
        </div>
    {% endfor %}
</div>
```

All this code does is sort the children (blog posts) of the `/blog` page by decending date order. It then takes the first five blog posts using the `slice` Twig filter. By the way, `slice(n,m)` takes elements from `n` to `m-1`. In this example, any blog posts that have a banner image have been named `banner.jpg`. This is set in a variable `bannerimage`. If `bannerimage` exists, it is shrunk down to a `60px x 60px` box and will appear to the left of the post title text and date. If it does not exist, the website logo is resized to `60px x 60px` and placed to the left of the title and date text instead.

The CSS for this widget is listed below:

```css
.sidebar-content .recent-post {
    margin-bottom: 25px;
    padding-bottom: 25px;
    border-bottom: 1px solid #F0F0F0;
    float: left;
    clear: both;
    width: 100%;
}

.sidebar-content [class~='recent-post']:last-of-type {
    border-bottom: none;
}

.sidebar-content .recent-post .recent-post-image,
.sidebar-content .recent-post .recent-post-text {
    float: left;
}

.sidebar-content .recent-post .recent-post-image {
    margin-right: 10px;
}

.sidebar-content .recent-post .recent-post-text h4 {
    font-family: serif;
    margin-bottom: 10px;
}

.sidebar-content .recent-post .recent-post-text h4 a {
    color: #193441;
}

.sidebar-content .recent-post .recent-post-text p {
    font-family: Arial, sans-serif;
    font-size: 1.5rem;
    color: #737373;
    margin: 0;
}
```
Adjust the spacing between recent post items, font-family, font-size and font-weight to taste.

<h2 id="create-a-private-area">プライベートエリアを作る</h2>

Grav makes it very easy to create a private area on a website.
It all works thanks to the Login Plugin.

<h2 id="require-users-to-login-prior-to-access-a-part-of-the-site">サイトの一部にアクセスするためにユーザーにログインを要求する</h2>

If you don’t have it already, install it through the Admin Panel or using the GPM command line utility.

Next, open a page in Admin, switch to expert mode and in the FrontMatter section add

```yaml
access:
    site.login: true
```

Users accessing the page will need to login prior to see the page content.

Notice that the permission does not extend by default to subpages. To do so, from the Login plugin configuration enable "Use parent access rules".

This option allows you to create extended private areas without worrying further about access level. Just put all under a page which has a restriction on access.

<h2 id="require-special-permissions-to-view-one-or-more-pages">ページの閲覧に特別なパーミッションを要求する</h2>

Similarly to the above process, you can assign any permission you want to a page. You can even come up with your own permission names.

For example:

```yaml
access:
    site.onlybob: true
```

Next, add the `site.onlybob` permission to Bob, in its `bob.yaml` user file under the `user/accounts` folder:

```yaml
access:
    site.onlybob: true
```

<h2 id="use-group-based-permissions">グループ単位のパーミッションを使う</h2>

You can also assign users to a group, and assign permissions to the group instead of to individual users. Users will inherit the group permissions.

Add a `user/config/groups.yaml` file, for example with this content:

```yaml
registered:
  readableName: 'Registered Users'
  description: 'The group of registered users'
  access:
    site:
      login: true
premium:
  readableName: 'Premium Members'
  description: 'The group of premium members'
  access:
    site:
      login: true
      paid: true
```

Now assign users to a group by adding

```yaml
groups:
      - premium
```

to their yaml user file, under `user/accounts`

Now users belonging to the `premium` group will be allowed to access pages with a `site.paid` permission.

<h2 id="add-javascript-to-the-footer">フッターにJavaScript を追加する</h2>

In many cases you'd want "some" javascript to be added to the footer instead of the page header, to be loaded after the content has been rendered.

A good example of doing this is to check the Antimatter theme.

Antimatter's `templates/partials/base.html.twig` defines a bottom block for js by calling `{{ assets.js('bottom') }}`

```twig
{% block bottom %}
    {{ assets.js('bottom') }}
{% endblock %}
```

You can add assets in that block in Twig for example by calling

`{% do assets.addJs('theme://js/slidebars.min.js', {group: 'bottom'}) %}`

or in PHP by calling

`$this->grav['assets']->addJs($this->grav['base_url'] . '/user/plugins/yourplugin/js/somefile.js', ['group' => 'bottom']);`

<h2 id="override-the-default-logs-folder-location">デフォルトのログフォルダの場所を上書きする</h2>

The default location for the logs output of Grav is simply called `logs/`.  Unfortunately, there are instances where that `logs/` folder is already used or is off-limits.  Grav's flexible stream system allows the ability to customize the locations of these folders.

First, you need to create your new folder.  In this example, we'll create a new folder in the root of your Grav install called `grav-logs/`.  Then create a new root-level file called `setup.php` and paste the following code:

```php
<?php
use Grav\Common\Utils;


return [
    'streams' => [
        'schemes' => [
            'log' => [
               'type' => 'ReadOnlyStream',
               'prefixes' => [
                   '' => ["grav-logs"],
               ]
            ]
        ]
    ]
];
```

This basically overrides the `log` stream with the `grav-logs/` folder rather than the default `logs/` folder as defined in `system/src/Grav/Common/Config/Setup.php`.

<h2 id="split-vertical-menu-system">メニューシステムを垂直に分ける</h2>

To create a vertical, collapsible, hierarchical menu of pages you need a Twig-loop, a bit of CSS, and a bit of JavaScript. The final result will, when using the Antimatter-theme, look like this:

![Vertical Menu](vertical_menu.png)

Let's start with Twig:

```twig
<ol class="tree">
    {% for page in pages.children.visible %}
        {% if page.children.visible is empty %}
            <li class="item">
            <a href="{{ page.url }}">{{ page.title }}</a>
        {% else %}
            <li class="parent">
            <a href="javascript:void(0);">{{ page.title }}</a>
            <ol>
                {% for child in page.children.visible %}
                    {% if child.children.visible is empty %}
                        <li class="item">
                        <a href="{{ child.url }}">{{ child.title }}</a>
                    {% else %}
                        <li class="parent">
                        <a href="javascript:void(0);">{{ child.title }}</a>
                        <ol>
                            {% for subchild in child.children.visible %}
                                <li><a href="{{ subchild.url }}">{{ subchild.title }}</a></li>
                            {% endfor %}
                        </ol>
                    {% endif %}
                    </li>
                {% endfor %}
            </ol>
        {% endif %}
        </li>
    {% endfor %}
</ol>
```

This creates an ordered list which iterates over all visible pages within Grav, going three levels deep to create a structure for each level. The list wrapped around the entire structure has the class *tree*, and each list-item has the class *parent* if it contains children or *item* if it does not.

Clicking on a parent opens the list, whilst regular items link to the page itself. You could add this to virtually any Twig-template in a Grav theme, provided that Grav can access the visible pages.

To add some style, we add some CSS:

```css
<style>
ol.tree li {
    position: relative;
}
ol.tree li ol {
    display: none;
}
ol.tree li.open > ol {
    display: block;
}
ol.tree li.parent:after {
    content: '[+]';
}
ol.tree li.parent.open:after {
    content: '';
}
</style>
```

This should generally be placed before the Twig-structure, or ideally be streamed into the [Asset Manager](../../03.themes/07.asset-manager/) in your theme. The effect is to add **[+]** after each parent-item, indicating that it can be opened, which disappears when opened.

Finally, let's add a bit of JavaScript to [handle toggling](https://stackoverflow.com/a/36297446/603387) the *open*-class:

```js
<script type="text/javascript">
var tree = document.querySelectorAll('ol.tree a:not(:last-child)');
for(var i = 0; i < tree.length; i++){
    tree[i].addEventListener('click', function(e) {
        var parent = e.target.parentElement;
        var classList = parent.classList;
        if(classList.contains("open")) {
            classList.remove('open');
            var opensubs = parent.querySelectorAll(':scope .open');
            for(var i = 0; i < opensubs.length; i++){
                opensubs[i].classList.remove('open');
            }
        } else {
            classList.add('open');
        }
    });
}
</script>
```

This should always be placed **after** the Twig-structure, also ideally in the [Asset Manager](../../03.themes/07.asset-manager/).

## Dynamically style one or more pages
You can dynamically style different pages/posts in your Grav site (independent of template file assignment) by customizing a Theme's Twig file to apply a CSS class passed as a variable in a page's FrontMatter.

You can style different posts/pages in your Grav site by two methods:

1. If you are using the Antimatter theme, you can use the existing `body_classes` header property to set your custom CSS class for that page
2. If you are using a theme not based on Antimatter (or not implementing `body_classes` as it does), you can customize a Theme's Twig file to apply a CSS class passed as a variable in a page's header property

For example, in your theme's `base.html.twig` file or a more specific template such as `page.html.twig` file you could add a class to the display of page content, such as:

```html
<div class="{{ page.header.body_classes }}">
...
</div>
```

Then, for each page you wish to have a unique style, you would add the following header property (assuming you have defined a CSS class for `featurepost`):

```yaml
body_classes: featurepost
```

Note: This is how the Antimatter theme applies page-specific classes, and so it's a good standard to follow.

## Migrate an HTML theme to Grav

Migrating an HTML theme to Grav is a common task. Here is a hands-on step-by-step process that can be used to achieve this goal.

You probably have downloaded the theme, and it's composed of several HTML files. Let's start with simply making Grav load the home page. No custom content, just replicate the HTML theme, but within a Grav structure.

First, [use the Grav Devtools plugin](../../03.themes/02.theme-tutorial/) to create a blank theme, and set Grav to use it in the System settings.

Create a `templates/home.html.twig` Twig template inside the theme’s templates folder. This will represent a template specific for the home page. Usually, the home is a unique page on the site, so it probably deserves a dedicated Twig file.

Copy the HTML code from the template's home page, starting at `<html>` and ending at `</html>` to your new `home.html.twig` file.

Now, move all the HTML theme assets (images, CSS, JS) into your theme folder. You can keep the existing theme folder structure, or change it.

Create a `pages/01.home/home.md` empty file. Now point your browser to yoursite.com/home: it should show up the content, but the CSS, JS and images will not be loaded, probably because the theme has them hardcoded as `/img/*` or `/css/*` links.

#### Adding the correct asset links

In Grav the links are broken because they point to the home route, so instead of pointing to `/user/themes/mytheme/img`, they point to `/img` in the Grav root. Since it's best to keep all theme-related assets inside the theme, we need to point Grav to the correct location.

Search within the page for assets and change the images references from `img/*.*` to `<img src="{{ url('theme://img/*.*', true) }}" />`.

Stylesheets require a bit more thought as there’s an asset pipeline we’ll want to enable at some point, so we move them to a stylesheets block within the `<head>` tag.

Example:
```twig
{% block stylesheets %}
    {% do assets.addCss('theme://css/styles.min.css', 100) %}
{% endblock %}
{{ assets.css()|raw }}
```

The same applies to JavaScript files, with the additional requirement that some JS is loaded in the footer.

Example:
```twig
{% block javascripts %}
    {% do assets.addJs('theme://js/custom.js') %}
    {% do assets.addJs('jquery', 101) %}
{% endblock %}
{{ assets.js()|raw }}
```

The page changes should now be shown in your Browser. If not, make sure that the pages cache and the twig cache are disabled in the Grav system configuration settings.

This is just the start. Now you might need to add more pages, and come up with better ways to present the content of your pages using the header FrontMatter, and custom Twig that processes usual building blocks need: the home page testimonials, reviews, the product features and so on.

#### Adding another page

To add another page, the process is similar. For example, let's say you want to next create the blog page.
Repeat the process to add a `templates/blog.html.twig` file, paste the HTML source, and create a `pages/02.blog/blog.md` page.

Now, while images links inside the pages still need to be migrated to Grav's assets syntax (or simply change the path), you don't want to repeat the same work you did above for CSS and JS assets. This should be reused across the site.

#### Shared Elements

Identify the common parts of the pages (header and footer), and move them to the `templates/partials/base.html.twig` file.

Each page template then needs to extend `partials/base.html.twig` (https://github.com/getgrav/grav-theme-antimatter/blob/develop/templates/default.html.twig#L1) and just add their unique content.

## Add an asset to a specific page

#### Problem

You need to add an asset to a specific template on your theme.

#### Solution

Most of the time, your assets will be added inside a twig block in your base template like below.

```twig
{% block javascripts %}
    {% do assets.addJs('theme://js/jquery.js', 91) %}
{% endblock %}
{{ assets.js()|raw }}
```

In order to add your asset, you have to extend this block in your template and call `{{ parent() }}` which will get the assets already added in your base template.
Let's say you want to add a "gallery.js" file on your "Portfolio Gallery" page.
Edit your template and add your asset with the `{{ parent() }}`.

```twig
{% block javascripts %}
  {% do assets.addJs('theme://js/gallery.js', 100) %}
  {{ parent() }}
{% endblock %}
```

## Reuse page or modular content on another page

#### Problem:
You have many pages or modules and would like to share the same content block on more than one page without having to maintain multiple separate instances of the same text.

#### Solution:

This is a very simple straightforward method which does not require a plugin and can be used within the admin panel.

**Note:** There is also plugin [Grav Page Inject Plugin](https://github.com/getgrav/grav-plugin-page-inject) for this functionality which may be suitable for more advanced scenarios.

First, create a new template file to act as a placeholder for the content - it can have any name, this one is named "modular_reuse" and will be in the stored in your theme's templates/modular_ folder for this example but can be stored anywhere in the templates folder.


`modular_reuse.html.twig` contains only one line:
```twig
{{ page.content|raw }}
```
Next, create a new modular page in the admin panel where this content should be displayed using this new "modular reuse" template. The new page name can be anything you like as it will not be displayed - the original page title will be output.

The content of the page is just one line:
Page:
```twig
{% include 'modular_reuse.html.twig' with {'page': page.find('/test-page/amazing-offers')} %}
```
Modular:
```twig
{% include 'modular/modular_reuse.html.twig' with {'page': page.find('/test-page/_amazing-offers')} %}
```

What comes after "include" is where the template from step one is stored, probably in the `templates` folder for pages in the `templates/modular` folder for modulars.

After page.find should come the actual link to the original content that you want to reuse. Modular content starts with an _ but pages do not. The easiest way to find the correct link is to open the page in the admin panel and copy the url after the word admin.

The final page should look like this:
```twig
---
title: 'Modular Reuse Example'
---

{% include 'modular/modular_reuse.html.twig' with {'page': page.find('/test-page/_amazing-offers')} %}
```

Now "amazing offers" can be displayed in multiple places but only needs to be updated once.

## Make a custom anti-spam field for your contact form

#### Problem:

Normal methods of spam-prevention, like the honeypot-field, is bypassed by certain spam-bots.

#### Solution:

Make it harder for the bot to guess what it can and can't fill it in, when filling out the contact form. Put simply, ask a question the user won't fail to answer, but whose answers are hard for a bot to understand the significance of. In your Markdown-file with [Form-data](../../06.forms/02.forms/03.example-form/), add this field:

```yaml
    - name: personality
      type: radio
      label: What is five times eight?
      options:
        alaska: 32
        oklahoma: 40
        california: 48
      validate:
        required: true
        pattern: "^oklahoma$"
        message: Not quite, try that math again.
```

The question should be something simple, but with multiple simple wrong answers accompanying it. What matters is the order of the answers. The right answer should never be the first one; aim for somewhere in the middle. It's important to randomize the values behind the answers (labels) yourself, so a database of associated values and answers won't help in answering.

Bots get smarter all the time, but they tend to forego trying to answer the same question several times if the first attempt fails. Also, even the smartest of them rely on dictionaries of known data to guess at an answer. We ask a simple question, "What is five times eight?", and give three options, "32", "40", and "48". The right answer is obviously "40", but instead of checking the bot's math-skills, we're assigning the values "alaska", "oklahoma", and "california" to these numbers, respectively. Because bots look at the possible values, rather than their label, the answers bears no relation to the question. You could even add an answer "Pineapple" with the value "mississippi" and validate against that, and just tell your users to choose that as their answer. The point is to personalize the randomization of data.


## Display different robots.txt contents for different environments

#### Problem:

You've setup a subdomain, `dev.yourdomain.com`, as a development site to preview what you're working on before publishing changes to `yourdomain.com`, and want to disallow search indexers from crawling it while keeping the production site visible in search results.

#### Solution:

While you should password-protect your development site to really keep it private, sometimes it's sufficient, and just more practical, to simply disallow search engine indexers from crawling your site. Luckily, Grav can handle pages in txt format just as it does html, so we can use [environment configurations](../../08.advanced/04.environment-config/) and twig templates to complete the job.

First, let's create a configuration file `site.yaml` that will tell our template that `dev.yourdomain.com` is a development environment.

`/user/[dev.yourdomain.com]/config/site.yaml`:

    environment: dev


Then, create a `robots.txt.twig` page template that checks if Grav is currently running on our development site, and displays different contents if it is.

`/user/themes/[yourtheme]/templates/robots.txt.twig`:

```twig
{% if config.site.environment == 'dev' %}
{% for rule in page.header.dev %}
{{ rule }}
{% endfor %}

{% else %}
{{ page.content|raw }}

{% endif %}
```

Finally, create a page routed at `/robots.txt` with the default `robots.txt` rules in the page content, and our alternative development version rules in the page frontmatter. To render the page contents as raw text instead of html, we'll also disable markdown rendering.

`/user/pages/robots/robots.md`:

```md
---
routes:
  default: /robots.txt
process:
  markdown: false

dev:
  - 'User-agent: *'
  - 'Disallow: /'
---

User-agent: *
Disallow: /backup/
Disallow: /bin/
Disallow: /cache/
Disallow: /grav/
Disallow: /logs/
Disallow: /system/
Disallow: /vendor/
Disallow: /user/
Allow: /user/pages/
Allow: /user/themes/
Allow: /user/images/
Allow: /user/plugins/*.css$
Allow: /user/plugins/*.js$
```

Now you should have a `robots.txt` file placed at your site's root with dynamic contents, also editable with the Admin plugin.

Note: make sure your production site won't display `Disallow: /`, as that will completely obliterate your search engine visibility.

