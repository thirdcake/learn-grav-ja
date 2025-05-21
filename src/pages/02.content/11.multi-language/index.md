---
title: 多言語サイト
layout: ../../../layouts/Default.astro
lastmod: '2025-05-13'
---
Gravの多言語対応は、このことを主題とした [コミュニティでの議論](https://github.com/getgrav/grav/issues/170) の成果です。これらを噛み砕き、Gravでの多言語サイトの作り方の例を示します。

<h2 id="single-language-different-than-english">英語以外の1言語を使う</h2>

1言語だけ使う場合は、翻訳を有効化し、言語コードを `user/config/system.yaml` ファイルに追記してください：

```yaml
languages:
  supported:
    - fr
```

もしくは、管理パネルでシステム設定をしてください：

![Admin Translations Settings](translations-settings.png)

これにより、Gravは正しい言語をフロントエンドで設定します。  
また、テーマが対応していれば、HTMLタグに言語コードを追記します。

<h2 id="multi-language-basics">多言語化の基本</h2>

Gravがフォルダ中のマークダウンファイルでサイト構造を決定したり、ページのオプションやコンテンツを設定したりしていることは、これまで説明してきましたので、そのメカニズムまでは入っていきません。しかし、ページを表現するのに、Gravは **ひとつの** `.md` ファイルを探すことに注目してください。この原則について不安なところがありましたら、これを読み進める前に、[基本のチュートリアル](../../01.basics/04.basic-tutorial) を参照してください。多言語サポートを有効化すると、Gravは、適切な言語ベースファイルを探します。たとえば、`default.en.md` や、`default.fr.md` のような。

<h3 id="language-configuration">言語設定</h3>

`user/config/system.yaml` ファイルで、基本言語の設定が必要です。（読みやすいようにコメントをつけています）：

```yaml
languages:
  supported: # サポート対象の言語:
    - en # 英語
    - fr # フランス語（日本語なら ja）
  default_lang: en # デフォルトの言語を英語に設定
  include_default_lang: true # true にすると、'/path' の代わりにデフォルト言語である英語を使った '/en/path' を使う
  include_default_lang_file_extension: true # true にすると、デフォルト言語として拡張子が '.md' のファイルの代わりに '.en.md' ファイルを使う
  content_fallback:
    en: ['en'] # 英語の場合は、フォールバックしない
    fr: ['fr', 'en'] #  フランス語の場合は、必要な場合は英語にフォールバックする
```

By providing a `languages` block with a list of `supported` languages, you have effectively enabled multi-language support within Grav.

In this example you can see that two supported languages have been described (`en` and `fr`). These will allow you to support **English** and **French** languages.

If no language is explicitly asked for (via the URL or by code), Grav will use the order of the languages provided to select the correct language.  So in the example above, the **default** language is `en` or English. If you had `fr` first, French would be the default language.

By default, all languages fall back to default language. If you do not want to do that, you can override language fallbacks by using `content_fallback`, where key is the language and value is array of languages.

> [!Info]  
> You can of course provide as many languages as you like and you may even use locale type codes such as `en-GB`, `en-US` and `fr-FR`.  If you use this locale based naming, you will have to replace all the short language codes with the locale versions.

<h3 id="multiple-language-pages">多言語ページ</h3>

By default in Grav, each page is represented by a markdown file, for example `default.md`. When you enable multi-language support, Grav will look for the appropriately named markdown file.  For example as English is our default language, it will first look for `default.en.md`.

If that file is not found, it will fall-back to the Grav default and look for `default.md` to provide information for the page.

> [!Info]  
> This default behavior has changed in **Grav 1.7**. In the past Grav displayed non-existing English page in French, now all languages fall back only to default language if not specified otherwise in `content_fallback`. So if the page cannot be found in any fallback languages, **404 Error Page** is displayed instead.

If we had the most basic of Grav sites, with a single `01.home/default.md` file, we could start by renaming `default.md` to `default.en.md`, and its contents might look like this:

```markdown
---
title: Homepage
---

This is my Grav-powered homepage!
```

Then you could create a new page located in the same `01.home/` folder called `default.fr.md` with the contents:

```markdown
---
title: Page d'accueil
---

Ceci est ma page d'accueil générée par Grav !
```

Now you have defined two pages for your current homepage in multiple languages.

> [!Note]  
> If you are converting existing site to use multi-language, you can alternatively set `include_default_lang_file_extension: false` to keep on using the plain `.md` file extension for your primary language. [Read More...](#default-file-extension).

<h3 id="active-language-via-url">URLによる言語</h3>

As English is the default language, if you were to point your browser without specifying a language you would get the content as described in the `default.en.md` file, but you could also explicitly request English by pointing your browser to

```txt
http://yoursite.com/en
```

To access the French version, you would of course, use

```txt
http://yoursite.com/fr
```

> [!Note]  
> If you prefer not to use language prefix for the default language, set `include_default_lang: false`. [Read More...](#default-language-prefix).

<h3 id="active-language-via-browser">ブラウザでの言語設定</h3>

Most browsers allow you to configure which languages you prefer to see content in. Grav has the ability to read this `http_accept_language` values and compare them to the current supported languages for the site, and if no specific language has been detected, show you content in your preferred language.

For this to function you must enable the option in your `user/system.yaml` file in the `languages:` section:

```yaml
languages:
  http_accept_language: true
```


<h3 id="session-based-active-language">セッションベースのアクティブ言語</h3>

If you wish to remember the active language independently from the URL, you can activate **session-based** storage of the active language.  To enable this, you must ensure you have `session: enabled: true` in [the system.yaml](../../01.basics/05.grav-configuration).  Then you need to enable the language setting:

```yaml
languages:
  session_store_active: true
```

This will then store the active language in the session.

<h3 id="set-locale-to-the-active-language">アクティブ言語にsetlocaleする</h3>

The boolean setting will set the PHP `setlocale()` method that controls things such as monetary values, dates, string comparisons, character classifications and other locale-specific settings to that of the active language.  This defaults to `false`, and then it will use the system locale, if you set this value to `true` it will override the locale with the current active language.

```yaml
languages:
   override_locale: false
```

### Default Language Prefix

By default, the default language code is prefixed in all URLs.  For example if you have support for English and French (`en` and `fr`), and the default is English.  A page route might look like `/en/my-page` in English and `/fr/ma-page` in French. However it's often preferrable to have the default language without the prefix, so you can just set this option to `false` and the English page would appear as `/my-page`.

```yaml
languages:
    include_default_lang: false
```

### Default File Extension

If you are converting existing site to use multi-language, it may be daunting task to convert all the existing pages to use the new `.en.md` language file extension (if using English). In this case, you may want to disable language extension on your original language.

```yaml
languages:
    include_default_lang_file_extension: false
```

<h3 id="multi-language-routing">多言語ルーティング</h3>

Grav typically uses the names of the folders to produce a URL route for a particular page.  This allows for the site architecture to be easily understood and implemented as a nested set of folders.  However with a multi-language site you may wish to use a URL that makes more sense in that particular language.

If we had the following folder structure:

```yaml
- 01.animals
  - 01.mammals
    - 01.bats
    - 02.bears
    - 03.foxes
    - 04.cats
  - 02.reptiles
  - 03.birds
  - 04.insets
  - 05.aquatic
```

This would produce URLs such as `http://yoursite.com/animals/mammals/bears`.  This is great for an English site, but if you wished to have a French version you would prefer these to be translated appropriately. The easiest way to achieve this is to add a custom [slug](../02.headers/#slug) for each of the `fr.md` page files.  for example, the mammal page might look something like:

```markdown
---
title: Mammifères
slug: mammiferes
---

Les mammifères (classe des Mammalia) forment un taxon inclus dans les vertébrés, traditionnellement une classe, définie dès la classification de Linné. Ce taxon est considéré comme monophylétique...
```

This combined with appropriate **slug-overrides** in the other files should result in a URL of `http://yoursite.com/animaux/mammiferes/ours` which is much more French looking!

Another option is to make use of the [page-level routes](../02.headers/#routes) support and provide a full route alias for the page.

<h3 id="language-based-homepage">言語ベースのホームページ</h3>

If you override the route/slug for the homepage, Grav won't be able to find the homepage as defined by your `home.alias` option in your `system.yaml`. It will be looking for `/homepage` and your French homepage might have a route of `/page-d-accueil`.

In order to support multi-language homepages Grav has a new option that can be used instead of `home.alias` and that is simple `home.aliases` and it could look something like this:

```yaml
home:
  aliases:
    en: /homepage
    fr: /page-d-accueil
```

This way Grav knows how to route your to the homepage if the active language is English or French.

<h3 id="language-based-twig-templates">言語ベースのtwigテンプレート</h3>

By default, Grav uses the markdown filename to determine the Twig template to use to render.  This works with multi-language the same way.  For example, `default.fr.md` would look for a Twig file called `default.html.twig` in the appropriate Twig template paths of the current theme and any plugins that register Twig template paths.  With multi-language, Grav also adds the current active language to the path structure.  What this means is that if you need to have a language-specific Twig file, you can just put those into a root level language folder.  For example if your current theme is using a template located at `templates/default.html.twig` you can create an `templates/fr/` folder, and put your French-specific Twig file in there: `templates/fr/default.html.twig`.

Another option which requires manual setup is to override the `template:` setting in the page headers. For example:

```yaml
template: default.fr
```

This will look for a template located at `templates/default.fr.html.twig`

This provides you with two options for providing language specific Twig overrides.

> [!Info]  
> If no language-specific Twig template is provided, the default one will be used.



<h3 id="translation-via-twig">twigを使った翻訳</h3>

The simplest way to use these translation strings in your Twig templates is to use the `|t` Twig filter.  You can also use the `t()` Twig function, but frankly the filter is cleaner and does the same thing:

```twig
<h1 id="site-name">{{ "SITE_NAME"|t|e }}</h1>
<section id="header">
    <h2>{{ "HEADER.MAIN_TEXT"|t|e }}</h2>
    <h3>{{ "HEADER.SUB_TEXT"|t|e }}</h3>
</section>
```

Using the Twig function `t()` the solution is similar:

```twig
<h1 id="site-name">{{ t("SITE_NAME")|e }}</h1>
<section id="header">
    <h2>{{ t("HEADER.MAIN_TEXT")|e }}</h2>
    <h3>{{ t("HEADER.SUB_TEXT")|e }}</h3>
</section>
```

Another new Twig filter/function allows you to translate from an array.  This is particularly useful if you have a list of values such as months of the year, or days of the week.  For example, say you have this translation:

```yaml
en:
  GRAV:
    MONTHS_OF_THE_YEAR: [January, February, March, April, May, June, July, August, September, October, November, December]
```

You could get the appropriate translation for a post's month with the following:

```twig
{{ 'GRAV.MONTHS_OF_THE_YEAR'|ta(post.date|date('n') - 1)|e }}
```

You can also use this as a Twig function with `ta()`.

<h3 id="translations-with-variables">変数による翻訳</h3>

You can also use variables in your Twig translations by using [PHP's sprintf](https://php.net/sprintf) syntax:

```yaml
SIMPLE_TEXT: There are %d monkeys in the %s
```

And then you can populate those variables with the Twig:

```twig
{{ "SIMPLE_TEXT"|t(12, "London Zoo")|e }}
```

resulting in the translation:

```txt
There are 12 monkeys in the London Zoo
```

<h3 id="complex-translations">複雑な翻訳</h3>

Sometimes it's required to perform complex translations with replacement in specific languages.  You can utilize the full power of the Language objects `translate()` method with the `tl` filter/function.  For example:

```twig
{{ ["SIMPLE_TEXT", 12, 'London Zoo']|tl(['fr'])|e }}
```

Will translate the `SIMPLE_TEXT` string and replace the placeholders with `12` and `London Zoo` respectively.  Also there's an array passed with language translations to try in first-find-first-used order.  This will output the result in french:


```txt
Il y a 12 singes dans le Zoo de Londres
```

<h3 id="php-translations">PHPによる翻訳</h3>

As well as the Twig filter and functions you can use the same approach within your Grav plugin:

```php
$translation = $this->grav['language']->translate(['HEADER.MAIN_TEXT']);
```

You can also specify a language:

```php
$translation = $this->grav['language']->translate(['HEADER.MAIN_TEXT'], ['fr']);
```

To translate a specific item in an array use:

```php
$translation = $this->grav['language']->translateArray('GRAV.MONTHS_OF_THE_YEAR', 3);
```

### Plugin and Theme Language Translations

You can also provide your own translations in plugins and themes.  This is done by creating a `languages.yaml` file in the root of your plugin or theme (e.g. `/user/plugins/error/languages.yaml`, or `user/themes/antimatter/languages.yaml`), and should contain all the supported languages prefixed by the language or locale code:

```yaml
en:
  PLUGIN_ERROR:
    TITLE: Error Plugin
    DESCRIPTION: The error plugin provides a simple mechanism for handling error pages within Grav.
fr:
  PLUGIN_ERROR:
    TITLE: Plugin d'Erreur
    DESCRIPTION: Le plugin d'erreur fournit un mécanisme simple de manipulation des pages d'erreur au sein de Grav.
```

> [!Note]  
> The convention for plugins is to use PLUGIN_PLUGINNAME.* as a prefix for all language strings, to avoid any name conflict. Themes are less likely to introduce language strings conflicts, but it's a good idea to prefix strings added in themes with THEME_THEMENAME.*

### Translation Overrides

If you wish to override a particular translation, simply put the modified key/value pair in an appropriate language file in your `user/languages/` folder.  For example a file called `user/languages/en.yaml` could contain:

```yaml
PLUGIN_ERROR:
  TITLE: My Error Plugin
```


This will ensure that you can always override a translation string without messing around with the plugins or themes themselves, and also will avoid overwriting a custom translation when updating them.

## Advanced

### Environment-Based Language Handling

You can take advantage of [Grav's Environment Configuration](../../08.advanced/04.environment-config) to automatically route users to the correct version of your site based on URL.  For example, if you had a URL such as `http://french.mysite.com` that was an alias for your standard `http://www.mysite.com`, you could setup an environment configuration:

`/user/french.mysite.com/config/system.yaml`

```yaml
languages:
  supported:
    - fr
    - en
```

This uses an **inverted language order** so the default language is now `fr` so the French language will show by default.

### Language Alias Routes

Because each page can have its own custom route, it would be hard to switch between different language versions of the same page.  However, there is a new **Page.rawRoute()** method on the Page object that will get the same raw route for any of the various language translations of a single page.  All you would need to do is to put the lang code in front to get the proper route to a specific language version of a page.

For example, say you are on a page in English with a custom route of:

```txt
/my-custom-english-page
```

The French page has the custom route of:

```txt
/ma-page-francaise-personnalisee
```

You could get the raw page of the English page and that might be:

```txt
/blog/custom/my-page
```

Then just add the language you want and that is your new URL;

```txt
/fr/blog/custom/my-page
```

This will retrieve the same page as `/ma-page-francaise-personnalisee`.

## Translation Support

Grav provides a simple yet powerful mechanism for providing translations in Twig and also via PHP for use in themes and plugins. This is enabled by default, and will use `en` language if no languages are defined.  To manually enable or disable translations, there is a setting in your `system.yaml`:

```yaml
languages:
  translations: true
```

The translations use the same list of languages as defined by the `languages: supported:` in your `system.yaml`.

The translation system works in a similar fashion to Grav configuration and there are several places and ways you can provide translations.

The first place Grav looks for translation files is in the `system/languages` folder. Files are expected to be created in the format: `en.yaml`, `fr.yaml`, etc.  Each yaml file should contain an array or nested arrays of key/values pairs:

```yaml
SITE_NAME: My Blog Site
HEADER:
    MAIN_TEXT: Welcome to my new blog site
    SUB_TEXT: Check back daily for the latest news
```

For ease of identification, Grav prefers the use of capitalized language strings as this helps to determine untranslated strings and also makes it clearer when used in Twig templates.

Grav has the ability to fall-back through the supported languages to find a translation if one for the active language is not found.  This is enabled by default but can be disabled via the `translations_fallback` option:

```yaml
languages:
  translations_fallback: true
```

!!! Help Grav reach a wider community of users by providing translations in **your language**. We use the [Crowdin Translation Platform](https://crowdin.com/) to facilitate translating the [Grav Core](https://crowdin.com/project/grav-core) and [Grav Admin Plugin](https://crowdin.com/project/grav-admin). [Sign-up](https://crowdin.com/join) and get started translating today!

<h3 id="language-switcher">言語の変換</h3>

You can download a simple **Language Switching** plugin via the Admin plugin, or through the GPM with:

```bash
bin/gpm install langswitcher
```

The [documentation for configuration and implementation can be found on GitHub](https://github.com/getgrav/grav-plugin-langswitcher).


<h3 id="setup-with-language-specific-domains">特定のドメインでの設定</h3>

Configure your site with [Environment-Based Language Handling](#environment-based-language-handling) to assign default languages (the first language) to domains.


Make sure the option

```yaml
pages.redirect_default_route: true
```

is set to `true` in your `system.yaml`.

Add the following to your **.htaccess** file and adopt the language slugs and domain names to your needs:

```txt
# http://www.cheat-sheets.org/saved-copy/mod_rewrite_cheat_sheet.pdf
# http://www.workingwith.me.uk/articles/scripting/mod_rewrite

# handle top level e.g. http://grav-site.com/de
RewriteRule ^en/?$ "http://grav-site.com" [R=302,L]
RewriteRule ^de/?$ "http://grav-site.de" [R=302,L]

# handle sub pages, exclude admin path
RewriteCond %{REQUEST_URI} !(admin) [NC]
RewriteRule ^en/(.*)$ "http://grav-site.com/$1" [R=302,L]
RewriteCond %{REQUEST_URI} !(admin) [NC]
RewriteRule ^de/(.*)$ "http://grav-site.de/$1" [R=302,L]
```

If you know how to simplify the rewrite rules, please edit this page on GitHub by clicking the **Edit** link at the top of the page.

Here's a simplified version of the rule set:

```txt
# http://www.cheat-sheets.org/saved-copy/mod_rewrite_cheat_sheet.pdf
# http://www.workingwith.me.uk/articles/scripting/mod_rewrite

# Redirect top-level URLs
RewriteRule ^en/?$ "http://grav-site.com" [R=302,L]
RewriteRule ^de/?$ "http://grav-site.de" [R=302,L]

# Redirect sub-pages, excluding the admin path
RewriteCond %{REQUEST_URI} !^/admin [NC]
RewriteRule ^(en|de)/(.*)$ "http://grav-site.$1/$2" [R=302,L]
```

This simplified version combines the rewrite rules for redirecting sub-pages for "en" and "de" into a single rule using grouping. Additionally, it consolidates the RewriteCond for the admin path to reduce duplication.

> [!Note]  
> Make sure to add these rules before the default rules that come with Grav CMS.

<h3 id="language-logic-in-twig-templates">twigテンプレート中の言語ロジック</h3>

There is often a need to access Language state and logic from Twig templates.  For example if you need to access a certain image file that is different for a particular language and is named differently (`myimage.en.jpg` and `myimage.fr.jpg`).

To display the correct version of the image you would need to know the current active language.  This is possible in Grav by accessing the `Language` object via the `Grav` object, and calling the appropriate method. In the example above this could be achieved with the following Twig code:

```twig
{{ page.media.images['myimage.'~grav.language.getActive~'.jpg'].html()|raw }}
```

The `getActive` call in the Twig is effectively calling `Language->getActive()` to return the current active language code.  A few useful Language methods include:

* `getLanguages()` - Returns an array of all supported languages
* `getLanguage()` - Returns current active, else returns default language
* `getActive()` - Returns current active language
* `getDefault()` - Returns the default (first) language

For a complete list of available methods, you can look in the `<grav root>/system/src/Grav/Common/Language/Language.php` file.

