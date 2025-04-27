---
title: "Twigレシピ"
layout: ../../../layouts/Default.astro
---

This page contains an assortment of problems and their respective solutions related to Twig templating.

<h2 id="list-the-last-5-recent-blog-posts">ブログ投稿で最近の5つのリスト</h2>

##### Problem:

You want to display the last 5 blog posts in a sidebar of your site so a reader can see recent blog activity.

##### Solution:

Simply find the `/blog` page, obtain its children, order them by date in a descending order, and then get the first 5 to display in a list:

```twig
<ul>
{% for post in page.find('/blog').children.published.order('date', 'desc').slice(0, 5) %}
    <li class="recent-posts">
        <strong><a href="{{ post.url }}">{{ post.title }}</a></strong>
    </li>
{% endfor %}
</ul>
```

when using within pages make sure you add following configuration to the page header:

```yaml
twig_first: true
process:
    twig: true
```


<h2 id="add-non-modular-navigation-links">モジュラーページのモジュール以外のナビゲーションリンク</h2>

##### Problem:

You want to display navigation links from non modular pages.

##### Solution

```twig
<div class="desktop-nav__navigation">
    {% for page in pages.children %}
        {% if page.visible %}
            {% set current_page = (page.active or page.activeChild) ? 'active' : '' %}
            <a class="desktop-nav__nav-link {{ current_page }}" href="{{ page.url }}">
                {{ page.menu }}
            </a>
        {% endif %}
    {% endfor %}
</div>
```

<h2 id="list-the-blog-posts-for-the-year">今年のブログ投稿のリスト</h2>

##### Problem:

You want to display all the blog posts that have occurred in this calendar year.

##### Solution:

Simply find the `/blog` page, obtain it's children, filter by appropriate `dateRange()`, and order them by date in a descending order:

```twig
<ul>
{% set this_year = "now"|date('Y') %}
{% for post in page.find('/blog').children.dateRange('01/01/' ~ this_year, '12/31/' ~ this_year).order('date', 'desc') %}
    <li class="recent-posts">
        <strong><a href="{{ post.url }}">{{ post.title }}</a></strong>
    </li>
{% endfor %}
</ul>
```

## Displaying a translated month

##### Problem:

In some page templates, the Twig `date` filter is used, and it does not handle locales / multilanguage. So even if your page is in a language different than english, it could show the month in english, if the template chooses to show the month name.

##### Solution:

There are two solutions to this problem.

###### First approach

The first involves the use of the Twig intl extension.

Install https://github.com/Perlkonig/grav-plugin-twig-extensions. Make sure you have the PHP intl extension installed.

In your twig template, instead of for example (like in the Antimatter theme) `{{ page.date|date("M") }}` to `{{ page.date|localizeddate('long', 'none', 'it', 'Europe/Rome', 'MMM') }}` (add your language and timezone here)

###### Second approach

Let's assume you have some language translations setup in your `user/languages/` folder called `en.yaml` that contains the entry:

```yaml
MONTHS_OF_THE_YEAR: [January, February, March, April, May, June, July, August, September, October, November, December]
```

And in `fr.yaml`:

```yaml
MONTHS_OF_THE_YEAR: [Janvier, Février, Mars, Avril, Mai, Juin, Juillet, Août, Septembre, Octobre, Novembre, Décembre]
```

Then you have your Twig:

```html
<li>
    <a href='{{ post.url }}'><aside class="dates">{{ 'GRAV.MONTHS_OF_THE_YEAR'|ta(post.date|date('n') - 1) }} {{ post.date|date('d') }}</aside></a>
    <a href='{{ post.url }}'>{{ post.title }}</a>
</li>
```

This makes use of the Grav custom Twig filter `|ta` that stands for **Translate Array**.  In the English version, the output might be something like:

```txt
An Example Post  July 2015
```

And the French:

```txt
Un exemple d’article Juillet 2015
```

## Displaying page content without summary

##### Problem:

You want to display the content of a page without the summary at top.

##### Solution:

Use the  `slice` filter to remove the summary from the page content:

```twig
{% set content = page.content|slice(page.summary|length) %}
{{ content|raw }}
```


## Hiding the email to spam bots

##### Problem:

You want to hide the email from spam bots

##### Solution:

Enable Twig processing in the page header:

```yaml
process:
    twig: true
```

Then use the `safe_email` Twig filter:

```html
<a href="mailto:{{'your.email@server.com'|safe_email}}">
  Email me
</a>
```

## Picking a random item from a translated array

##### Problem:

You want to pick a random item from an array translated in a particular language.  For this to work, it's assumed you have your [multi-language site setup and configured](../../02.content/11.multi-language/) as outlined in the documentation.

##### Solution:

Let's also assume you have some language translations setup in your `user/languages/` folder called `en.yaml` that contains the entry:

```txt
FRUITS: [Banana, Cherry, Lemon, Lime, Strawberry, Raspberry]
```

And in `fr.yaml`:

```txt
FRUITS: [Banane, Cerise, Citron, Citron Vert, Fraise, Framboise]
```

Then you have your Twig:

```twig
{% set langobj  = grav['language'] %}
{% set curlang  = langobj.getLanguage() %}
{% set fruits   = langobj.getTranslation(curlang,'FRUITS',true) %}
<span data-ticker="{{ fruits|join(',') }}">{{ random(fruits) }}</span>
```

## Displaying an image uploaded in a file field

##### Problem

You added a `file` field in your custom blueprint, and you want to display an image added in this field.

##### Solution

As the `file` field allows for multiple images to be uploaded, it generates two nested objects in your frontmatter, the first object is the list of the uploaded images, the nested object within is a group of property/value for the given image.

_Note that in the case where you would want your user to only select a single image, it could be easier to use the `filepicker` field, that store a single object with the selected images properties._

If you have a single image, you can display it in your template by using:

```twig
{{ page.media[header.yourfilefield|first.name] }}
```

If you allowed your user to upload multiple images, your twig could look like this:

```twig
{% for imagesuploaded in page.header.yourfilefield %}
{{ page.media[imagesuploaded.name] }}
{% endfor %}
```

## Displaying an image picked in a mediapicker field

##### Problem

You added a `mediapicker` field in your custom blueprint, and you want to display the image selected.

##### Solution

A `mediapicker` field can be added to your blueprint like below:

```yaml
header.myimage:
  type: mediapicker
  folder: 'self@'
  label: Select a file
  preview_images: true
```

The `mediapicker` field store the path to the image as a string such as `/home/background.jpg`
In order to access this image with the page media functionality, you have to split this string and get:
 - the path to the page where this image is stored
 - the name of the image.

You can do this via twig by using the snippet below:

```twig
{% set image_parts = pathinfo(header.myimage) %}
{% set image_basename = image_parts.basename %}
{% set image_page = image_parts.dirname == '.' ? page : page.find(image_parts.dirname) %}

{{ image_page.media[image_basename].html()|raw }}
```

## Custom Twig Filter/Function

##### Problem

Sometimes you need some logic in Twig that can only be done in PHP, so the best solution is to create a custom Twig Filter or Function.  A filter is usually appended to a string in the format: `"some string"|custom_filter` and a function can take a string, or any other variable type: `custom_function("some string")`, but essentially they are very similar.

You can also pass extra parameters like: `"some string"|custom_filter('foo', 'bar')`, where the extra parameters can be used inside the filter. And the function variation would be: `custom_function("some string", 'foo', 'bar')`.

For this example we'll create a simple Twig filter to count the takes a string and splits it into chunks separated by a delimiter. This is particular useful for things like credit card numbers, license keys etc.

##### Solution

The best way to add this extra functionality is to add the logic in your custom plugin, although adding it in your theme's php file is also an option.  We'll use a plugin in this example for simplicity.  First you need to install the devtools plugin to make creating a plugin a simple wizard-based process:

```bash
bin/gpm install devtools
```

Next you need to create your new custom plugin, then fill in your details when prompted.

```bash
bin/plugin devtools new-plugin

Enter Plugin Name: ACME Twig Filters
Enter Plugin Description: Plugin for custom Twig filters
Enter Developer Name: ACME, Inc.
Enter GitHub ID (can be blank):
Enter Developer Email: hello@acme.com

SUCCESS plugin ACME Twig Filters -> Created Successfully

Path: /Users/joe/grav/user/plugins/acme-twig-filters
```

By default this skeleton framework for a new plugin will add some dummy test to your page via the `onPageContentRaw()` event.  You will first need to replace this functionality with code that listens to the `onTwigInitialized()` event:

```php
    public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        // Enable the main event we are interested in
        $this->enable([
            'onTwigInitialized' => ['onTwigInitialized', 0]
        ]);
    }

    /**
     * @param Event $e
     */
    public function onTwigInitialized(Event $e)
    {

    }
```

First we need to register the filter in the `onTwigInitialized()` method:

```php
    /**
     * @param Event $e
     */
    public function onTwigInitialized(Event $e)
    {
        $this->grav['twig']->twig()->addFilter(
            new \Twig_SimpleFilter('chunker', [$this, 'chunkString'])
        );
    }
```

The first parameter to the method registers the `chunker` as the filter name, and the `chunkString` as the PHP method where the logic occurs.  So we need to create this next:

```php
    /**
     * Break a string up into chunks
     */
    public function chunkString($string, $chunksize = 4, $delimiter = '-')
    {
        return (trim(chunk_split($string, $chunksize, $delimiter), $delimiter));
    }
```

Now you can try it out in your Twig templates like this:

```twig
{{ "ER27XV3OCCDPRJK5IVSDME6D6OT6QHK5"|chunker }}
```

Which would produce:

```txt
ER27-XV3O-CCDP-RJK5-IVSD-ME6D-6OT6-QHK5
```

or you can pass extra params:

```twig
{{ "ER27XV3OCCDPRJK5IVSDME6D6OT6QHK5"|chunker(8, '|') }}
```

which would produce:

```txt
ER27XV3O|CCDPRJK5|IVSDME6D|6OT6QHK5
```

Lastly if you want this to be available via a function and not just a filter, you can simply register a Twig function with the same name in the `onTwigInitialized()` method:

```php
    /**
     * @param Event $e
     */
    public function onTwigInitialized(Event $e)
    {
        $this->grav['twig']->twig()->addFilter(
            new \Twig_SimpleFilter('chunker', [$this, 'chunkString'])
        );
        $this->grav['twig']->twig()->addFunction(
            new \Twig_SimpleFunction('chunker', [$this, 'chunkString'])
        );
    }
```

And then you can use the function syntax:

```twig
{{ chunker("ER27XV3OCCDPRJK5IVSDME6D6OT6QHK5", 8, '|') }}
```

## Extend base template of inherited theme

##### Problem

Sometimes you need to extend the base template itself. This might happen when there's no easy and obvious way to extend blocks already present in a template. Lets use Quark as an example for parent theme and you want to extend `themes/quark/templates/partials/base.html.twig` to your `myTheme` theme. 

##### Solution

You can add Quark as a Twig namespace by using the theme's `my-theme.php` to listen on the `onTwigLoader` event and adding the Quark theme template directory. The contents of the class should be something like this:

    <?php
```php
    namespace Grav\Theme;
    
    use Grav\Common\Grav;
    use Grav\Common\Theme;
    
    class MyTheme extends Quark {
        public static function getSubscribedEvents() {
            return [
                'onTwigLoader' => ['onTwigLoader', 10]
            ];
        }
    
        public function onTwigLoader() {
            parent::onTwigLoader();
    
            // add quark theme as namespace to twig
            $quark_path = Grav::instance()['locator']->findResource('themes://quark');
            $this->grav['twig']->addPath($quark_path . DIRECTORY_SEPARATOR . 'templates', 'quark');
        }
    }
```

Now in `themes/my-theme/templates/partials/base.html.twig` you can extend Quarks base template like this:

```twig
    {% extends '@quark/partials/base.html.twig' %}
    
    {% block header %}
    This is a new extended header.
    {% endblock %}
```

