---
title: 多言語サイト
layout: ../../../layouts/Default.astro
lastmod: '2025-05-27'
---

Grav の多言語対応は、このことを主題とした [コミュニティでの議論](https://github.com/getgrav/grav/issues/170) の成果です。これらを噛み砕き、 Grav での多言語サイトの作り方の例を示します。

<h2 id="single-language-different-than-english">英語以外の1言語を使う</h2>

1言語だけ使う場合は、翻訳を有効化し、言語コードを `user/config/system.yaml` ファイルに追記してください：

```yaml
languages:
  supported:
    - fr
```

もしくは、管理パネルでシステム設定をしてください：

![Admin Translations Settings](translations-settings.png)

これにより、 Grav は正しい言語をフロントエンドで設定します。  
また、テーマが対応していれば、 HTML タグに言語コードを追記します。

<h2 id="multi-language-basics">多言語化の基本</h2>

Grav がフォルダ中のマークダウンファイルでサイト構造を決定したり、ページのオプションやコンテンツを設定したりしていることは、これまで説明してきましたので、そのメカニズムまでは入っていきません。しかし、ページを表現するのに、 Grav は **ひとつの** `.md` ファイルを探すことに注目してください。この原則について不安なところがありましたら、これを読み進める前に、[基本のチュートリアル](../../01.basics/04.basic-tutorial) を参照してください。多言語サポートを有効化すると、 Grav は、適切な言語ベースファイルを探します。たとえば、`default.en.md` や、`default.fr.md` のような。

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

> [!訳注]  
> 「フォールバック」とは、問題があったときに次善策を講じるような意味あいの言葉で、ここでは、ある言語のページが無かった場合に、別言語のページを代替表示することを指します。

`languages` ブロックに `supported` 言語のリストを書くことで、効率よく Grav 内に多言語サポートを有効化できます。

上記の例では、2つのサポート言語が定義されている（ `en` と `fr` ）ことが分かります。これらによって、 **英語** と **フランス語** をサポートできます。

（ URL やコードから）明示的に言語が指定されなかった場合、 Grav は言語が提供された順番に、適切な言語を選択します。このため、上記の例では、 **デフォルトの** 言語は `en` の英語です。もし `fr` の方を先に書いていれば、フランス語がデフォルトの言語になります。

デフォルトでは、すべての言語はデフォルト言語にフォールバックします。そうしたくない場合は、 `content_fallback` にキーとしてその言語を指定し、値として言語の配列を渡すことで、フォールバック言語を上書きできます。

> [!Info]  
> もちろん、好きなだけ多くの言語を提供できます。ロケールタイプのコードを使うこともできます。たとえば、 `en-GB`, `en-US` そして `fr-FR` などです。このようなロケールベースの名前を使う場合、短い言語コードをすべてロケール版に置き換えなければなりません。

> [!訳注]  
> 上記の `en-GB` はグレートブリテン（イギリス英語）、`en-US` はユナイテッドステイツ（アメリカ英語）です。 日本語の場合は、言語コードは `ja` で、ロケールは `ja-JP` しか使わないと思われます。

<h3 id="multiple-language-pages">多言語ページ</h3>

Grav のデフォルトでは、各ページはマークダウンファイルで表現されます。たとえば、 `default.md` のように。多言語サポートを有効化すると、 Grav は適切な名前のマークダウンファイルを探そうとします。
たとえば、英語がデフォルト言語のとき、最初に `default.en.md` を探します。

そのファイルが見つからない場合、 Grav のデフォルトにフォールバックし、ページの情報を提供するために `default.md` を探します。

> [!Info]  
> このデフォルトの挙動は、 **Grav 1.7** で変更されました。以前は、デフォルト言語である英語版のページが存在しない場合、デフォルト言語でないフランス語が表示されることもありました。しかし今は、 `contact_fallback` で別の方法を指定しない限り、すべての言語がデフォルト言語にのみフォールバックします。
よって、フォールバックする言語でページが見つからない場合、替わりに **404 エラーページ** が表示されます。

1つの `01.home/default.md` ファイルを持つ、最も基本的な Grav サイトでは、 `default.md` を `default.en.md` にファイル名変更するところから始めることができ、コンテンツは以下のようになります：

```markdown
---
title: Homepage
---

This is my Grav-powered homepage!
```

そして、 `default.fr.md` という新しいページを、同じ `01.home/` フォルダに作り、コンテンツは以下のようにできます：

```markdown
---
title: Page d'accueil
---

Ceci est ma page d'accueil générée par Grav !
```

これで、現在のホームページに対して、多言語で、2つのページが定義できました。

> [!Note]  
> 既存サイトを多言語化する場合、替わりに `include_default_lang_file_extension: false` を設定することで、プレーンな `.md` 拡張子のファイルを主要言語用として使い続けることができます。[詳しくは...](#default-file-extension) 

<h3 id="active-language-via-url">URLによる言語</h3>

英語がデフォルト言語のときに、ブラウザで言語を指定せずにページを表示すると、 `default.en.md` ファイルにかかれているコンテンツが表示されます。しかし、ブラウザで明示的に英語ページをリクエストすることもできます：

```txt
http://yoursite.com/en
```

フランス語版にアクセスするには、もちろん、こちらを使います：

```txt
http://yoursite.com/fr
```

> [!Note]  
> デフォルト言語のプレフィックスを使いたくない場合は、 `include_default_lang: false` を設定します。[詳しくは...](#default-language-prefix) 。


<h3 id="active-language-via-browser">ブラウザでの言語設定</h3>

ほとんどのブラウザでは、コンテンツを表示する言語を設定できます。Grav はこの `http_accept_language` の値を読み取り、現在のサポート対象言語と比べることができ、もし特定の言語が見つからなければ、お好みの言語でコンテンツを表示します。

これを機能させるには、 `user/system.yaml` ファイルの `languages:` セクションでオプションを有効化させなければいけません：

```yaml
languages:
  http_accept_language: true
```

<h3 id="session-based-active-language">セッションベースの有効化言語</h3>

URL からすぐに有効な言語を記憶しておきたいなら、有効な言語を **セッションベースの** ストレージに保存する設定を有効化できます。この設定を有効化するには、[system.yaml](../../01.basics/05.grav-configuration/) の中で、 `session: enabled: true` となっていなければいけません。その後、言語設定を以下のようにする必要があります：

```yaml
languages:
  session_store_active: true
```

これにより、有効な言語がセッションに保存されます。

<h3 id="set-locale-to-the-active-language">有効化言語にロケールを設定する</h3>

この真偽値を設定すると、 PHP の `setlocale()` 関数を設定します。このメソッドでは、金額や日付、文字列の比較、文字の分類、その他のロケール特有の設定を行います。これはデフォルトでは `false` で、その場合システムのロケールを使用します。この値を `true` にすると、現在有効な言語のロケールに上書きされます。

```yaml
languages:
   override_locale: false
```

<h3 id="default-language-prefix">デフォルトの言語プレフィックス</h3>

初期設定では、デフォルトの言語コードがすべての URL に接頭辞（プレフィックス）が付け加えられます。たとえば、英語とフランス語をサポートする場合（ `en` と `fr` ）で、デフォルト言語が英語の場合です。ページのルーティングは、英語で `/en/my-page` となり、フランス語では `/fr/ma-page` となります。しかし、デフォルト言語のときは接頭辞が無い方が良いときもあります。その場合、このオプションを `false` にするだけで、英語ページは `/my-page` で表示されます。

```yaml
languages:
    include_default_lang: false
```

<h3 id="default-file-extension">デフォルトのファイル拡張子</h3>

既存サイトを多言語化しようとしているとき、すべての既存ページを新しく（英語を使うならば） `.en.md` ファイル拡張子に変更するのは、大変な作業になります。このような場合は、オリジナル言語のときに、言語の拡張子を無効化したいと思うかもしれません。

```yaml
languages:
    include_default_lang_file_extension: false
```

<h3 id="multi-language-routing">多言語ルーティング</h3>

Grav では、ページのルーティングをするためにフォルダ名を利用します。これにより、サイト構造が分かりやすくなり、入れ子になったフォルダの組み合わせで実装されます。しかし、多言語サイトでは、特定の言語で、意味を持たせた URL を使いたいかもしれません。

次のようなフォルダ構造を持っているとします：

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

上記により、 URL はたとえば、 `http://yoursite.com/animals/mammals/bears` となります。これは英語サイトではすばらしい URL ですが、フランス語サイトでは、適切に翻訳された URL が好ましいかもしれません。これを解決する最も簡単な方法は、カスタムの [スラッグ](../02.headers/#slug) を、各 `fr.md` ページファイルに追加することです。たとえば、mammal （英語で哺乳類）ページは、（フランス語で哺乳類は `Mammifères` なので）次のようになるでしょう：

```markdown
---
title: Mammifères
slug: mammiferes
---

Les mammifères (classe des Mammalia) forment un taxon inclus dans les vertébrés, traditionnellement une classe, définie dès la classification de Linné. Ce taxon est considéré comme monophylétique...
```

他のファイルとも、適切な **スラッグの上書き** を組み合わせることで、 `http://yoursite.com/animaux/mammiferes/ours` よりフランス語の見た目をした URL ができます。

別の選択肢としては、 [ページレベルのルーティング](../02.headers/#routes) を使うことで、完全なルーティングのエイリアスをページに提供できます。

<h3 id="language-based-homepage">言語ベースのホームページ</h3>

ホームページに対するルーティング/スラッグを上書きしている場合、 Grav は `system.yaml` の `home.alias` で定義されたホームページを見つけることができません。`/homepage` を探そうとし、フランス語のホームページは `/page-d-accueil` のルーティングであってほしいです。

多言語サイトでのホームページをサポートするため、 Grav では新しいオプションにより、 `home.alias` ではなく、 `home.aliases` を使うことができます。これは、次のようになります：

```yaml
home:
  aliases:
    en: /homepage
    fr: /page-d-accueil
```

この方法で、 Grav は有効言語が 英語やフランス語だった場合のホームページへのルーティングを検知します。

<h3 id="language-based-twig-templates">言語をもとにしたのtwigテンプレート</h3>

デフォルトでは、 Grav はマークダウンファイルを使って、それをレンダリングする Twig テンプレートを決定します。多言語サイトでも、同じ方法で機能します。
たとえば、 `default.fr.md` ファイルがあれば、現在のテーマやプラグインにより Twig テンプレートのパスとして登録されている適切なパスから `default.html.twig` という Twig ファイルを探します。
また Grav は、現在有効になっている言語をパス構造に追加します。これが意味するところは、言語固有の Twig ファイルが必要になったら、言語フォルダのルートレベルにそれらを置くだけで良いということです。たとえば、現在のテーマが `templates/default.html.twig` に置かれている場合、 `templates/fr/` フォルダを作成でき、フランス語固有の Twig ファイルは、ここに置けます： `templates/fr/default.html.twig`

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

