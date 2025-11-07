---
title: 多言語サイト
layout: ../../../layouts/Default.astro
lastmod: '2025-07-19'
description: 'Grav では、サイトを多言語化することができます。多言語サイトの設定方法や、複数の言語ページの配置方法、翻訳ファイルの準備方法などを解説します。'
---

Grav の多言語対応は、このことを主題とした [コミュニティでの議論](https://github.com/getgrav/grav/issues/170) の成果です。  
これらを噛み砕き、 Grav での多言語サイトの作り方の例を示します。

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

> [!訳注]  
> 多言語サイトではなく、日本語のみのサイトを作りたいだけなら、 `system.yaml` ではなく、 `site.yaml` に `default_lang: ja` を設定した方が楽です。 [詳しくはこちら](../../01.basics/05.grav-configuration/#site-configuration) 。管理パネルプラグインの場合、左側のメニューにある `Configuration（設定）` => `Site（サイト）` タブ => `Default language（既定の言語）` を `ja` にしてください。

<h2 id="multi-language-basics">多言語化の基本</h2>

Grav がフォルダ中のマークダウンファイルでサイト構造を決定したり、ページのオプションやコンテンツを設定したりしていることは、これまで説明してきましたので、そのメカニズムまでは深入りしません。
ここでは、ページを表現するのに、 Grav は **ひとつの** `.md` ファイルを探すことに注目してください。  
この原則について、まだ理解があやふやな場合は、この解説を読み進めるより先に、 [基本のチュートリアル](../../01.basics/04.basic-tutorial) を参照してください。  
多言語サポートを有効化すると、 Grav は、適切な言語ベースファイルを探します。  
たとえば、`default.en.md` や、`default.fr.md` のようなものです。

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
> 「フォールバック」とは、問題があったときに次善策を講じるような意味あいの言葉で、ここでは、ある言語用のページが無かった場合に、別言語のページで代替表示することを指します。

`languages` ブロックに `supported` 言語のリストを書くことで、効率よく Grav 内に多言語サポートを有効化できます。

上記の例では、2つのサポート言語が定義されている（ `en` と `fr` ）ことが分かります。これらによって、 **英語** と **フランス語** をサポートできます。

（ URL やコードから）明示的に言語が指定されなかった場合、 Grav はサポート言語の優先順に、適切な言語を選択します。このため、上記の例では、 **デフォルトの** 言語は `en` の英語です。もし `fr` の方を先に書いていれば、フランス語がデフォルトの言語になります。

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
> 既存サイトを多言語化する場合、替わりに `include_default_lang_file_extension: false` を設定することで、プレーンな `.md` 拡張子のファイルを主要言語用として使い続けることができます。[詳しくはこちら](#default-file-extension) 

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
> デフォルト言語のプレフィックスを使いたくない場合は、 `include_default_lang: false` を設定します。[詳しくはこちら](#default-language-prefix) 。


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

初期設定では、デフォルトの言語コードがすべての URL に接頭辞（プレフィックス）が付け加えられます。  
たとえば、英語とフランス語をサポートする場合（ `en` と `fr` ）で、デフォルト言語が英語の場合です。  
ページのルーティングは、英語で `/en/my-page` となり、フランス語では `/fr/ma-page` となります。  
しかし、デフォルト言語のときは接頭辞が無い方が良いときもあります。  
その場合、このオプションを `false` にするだけで、英語ページは `/my-page` で表示されます。

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

<h3 id="language-based-twig-templates">言語ベースの Twig テンプレート</h3>

デフォルトでは、 Grav はマークダウンファイルを使って、それをレンダリングする Twig テンプレートを決定します。多言語サイトでも、同じ方法で機能します。
たとえば、 `default.fr.md` ファイルがあれば、現在のテーマやプラグインにより Twig テンプレートのパスとして登録されている適切なパスから `default.html.twig` という Twig ファイルを探します。
また Grav は、現在有効になっている言語をパス構造に追加します。これが意味するところは、言語固有の Twig ファイルが必要になったら、言語フォルダのルートレベルにそれらを置くだけで良いということです。たとえば、現在のテーマが `templates/default.html.twig` に置かれている場合、 `templates/fr/` フォルダを作成でき、フランス語固有の Twig ファイルは、ここに置けます： `templates/fr/default.html.twig`

もうひとつの選択肢は、ページのフロントマターにある `template:` 設定を手動で上書きする必要があります。具体的には：

```yaml
template: default.fr
```

この場合、`templates/default.fr.html.twig` にあるテンプレートを探します。

これにより、言語固有の Twig テンプレートを上書きするための手段が2つ用意されています。

> [!Info]  
> 特定言語の Twig テンプレートが提供されない場合、デフォルトのテンプレートが使われます。

<h3 id="translation-via-twig">Twig を使った翻訳</h3>

Twig テンプレートで、翻訳文字列を使う最も簡単な方法は、 `|t` Twig フィルターを使うことです。もしくは、 `t()` Twig 関数を使うこともできます。ただし、率直に言うと、フィルターの方がクリーンで、同じことができます。

```twig
<h1 id="site-name">{{ "SITE_NAME"|t|e }}</h1>
<section id="header">
    <h2>{{ "HEADER.MAIN_TEXT"|t|e }}</h2>
    <h3>{{ "HEADER.SUB_TEXT"|t|e }}</h3>
</section>
```

Twig 関数の `t()` を使う方法も、同じようにできます：

```twig
<h1 id="site-name">{{ t("SITE_NAME")|e }}</h1>
<section id="header">
    <h2>{{ t("HEADER.MAIN_TEXT")|e }}</h2>
    <h3>{{ t("HEADER.SUB_TEXT")|e }}</h3>
</section>
```

他の新しい Twig フィルターや関数により、配列から翻訳ができます。これは、1年の中の月や、1週間の中の曜日のような値のリストの場合に、特に便利です。たとえば、このような翻訳ができます：

```yaml
en:
  GRAV:
    MONTHS_OF_THE_YEAR: [January, February, March, April, May, June, July, August, September, October, November, December]
```

以下のようにして、投稿した月の適切な翻訳が得られます：

```twig
{{ 'GRAV.MONTHS_OF_THE_YEAR'|ta(post.date|date('n') - 1)|e }}
```

Twig 関数の `ta()` を使うことも可能です。

<h3 id="translations-with-variables">変数による翻訳</h3>

[PHP の sprintf](https://www.php.net/manual/ja/function.sprintf.php) 関数を使うことで、 Twig 翻訳で変数を使うこともできます：

```yaml
SIMPLE_TEXT: There are %d monkeys in the %s
```

それから、これらの変数を Twig に移動します：

```twig
{{ "SIMPLE_TEXT"|t(12, "London Zoo")|e }}
```

翻訳の結果はこのようになります：

```txt
There are 12 monkeys in the London Zoo
```

<h3 id="complex-translations">複雑な翻訳</h3>

ときには、特定の言語で置き換えを伴う複雑な翻訳を処理する必要があることもあります。 `tl` フィルター・関数を使って、 Language オブジェクトの `translate()` メソッドをフル活用できます。
たとえば：

```twig
{{ ["SIMPLE_TEXT", 12, 'London Zoo']|tl(['fr'])|e }}
```

上記は、 `SIMPLE_TEXT` 文字列を翻訳し、プレースホルダーをそれぞれ `12` と `London Zoo` に置き換えます。
また、翻訳言語を渡す配列があり、最初に見つかった言語から順に試されます。
フランス語での結果は、こうなります：

```txt
Il y a 12 singes dans le Zoo de Londres
```

> [!訳注]  
> この部分は、前提としてフランス語翻訳ファイルに `SIMPLE_TEXT: Il y a %d singes dans le %s` が定義されているところに、上記を実行したときの挙動を示していると思うのですが、もしそうだとすると、 `Il y a 12 singes dans le London Zoo` になるはずで、どうして London Zoo のところまで翻訳されるのか、よく分かりません。

<h3 id="php-translations">PHPによる翻訳</h3>

Twig フィルターや関数と同様、 Grav プラグイン内で同じアプローチが使えます：

```php
$translation = $this->grav['language']->translate(['HEADER.MAIN_TEXT']);
```

言語を指定することもできます：

```php
$translation = $this->grav['language']->translate(['HEADER.MAIN_TEXT'], ['fr']);
```

配列に入った特定のアイテムを翻訳するためには、次を使ってください：

```php
$translation = $this->grav['language']->translateArray('GRAV.MONTHS_OF_THE_YEAR', 3);
```

<h3 id="plugin-and-theme-language-translations">プラグインとテーマの言語翻訳</h3>

プラグインやテーマで、独自の翻訳を提供することもできます。これは、プラグインやテーマのルートディレクトリに `languages.yaml` ファイルを作成することでできます（例： `/user/plugins/error/languages.yaml` もしくは `user/themes/antimatter/languages.yaml` ）。そして、すべてのサポート言語の接頭辞を、言語コードもしくはロケールコードで、含む必要があります：

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
> プラグインの規約では、名前衝突を防ぐため、すべての言語文字列に `PLUGIN_PLUGINNAME.*` を接頭辞として使用します。テーマは、衝突の可能性は低いですが、 `THEME_THEMENAME.*` を接頭辞として追加するのは良い考えです。

<h3 id="translation-overrides">翻訳の上書き</h3>

特定の翻訳を上書きしたい場合、単純に、修正した key/value ペアを `user/languages/` フォルダにある適切な言語ファイルに置くだけです。たとえば、 `user/languages/en.yaml` ファイルは、次のようになります：

```yaml
PLUGIN_ERROR:
  TITLE: My Error Plugin
```

こうすることで、プラグインやテーマ自体に手を加えることなく、すべての翻訳文字列を上書きできます。そしてまた、それらのアップデート持にカスタム翻訳が上書きされることもありません。

<h2 id="advanced">上級編</h2>

<h3 id="environment-based-language-handling">環境ベースの言語制御</h3>

[Grav の環境設定](../../08.advanced/04.environment-config/) を利用して、 URL に基づいて自動的にユーザーを正しい言語バージョンへルーティングすることができます。たとえば、 `http://french.mysite.com` のような URL があったとき、そしてこれが標準的な `http://www.mysite.com` の別名である場合に、環境設定を次のように設定できます：

`/user/french.mysite.com/config/system.yaml`

```yaml
languages:
  supported:
    - fr
    - en
```

ここで、 **言語の優先順位が逆順** になっているので、デフォルト言語は、 `fr` となり、フランス語がデフォルトで表示されます。

<h3 id="language-alias-routes">カスタムのルーティングを使っていたときの言語切り替え</h3>

各ページはカスタムのルーティングを設定できるので、同じページの異なる言語バージョン間を切り替えるのは難しいです。しかしながら、 Page オブジェクトに **Page.rawRoute()** メソッドという新しいメソッドがあり、このメソッドは、ある1つのページの多様な言語翻訳すべてについて、元の同じルーティングを取得します。必要なことは、言語コードを、特定の言語バージョンのページの先頭に付け加えるだけです。

たとえば、英語バージョンで、次のようなカスタムルーティングのページがあるとします：

```txt
/my-custom-english-page
```

フランス語ページのカスタムルーティングは次のようになっています：

```txt
/ma-page-francaise-personnalisee
```

英語ページの元のページルーティングは、次のようになっているかもしれません：

```txt
/blog/custom/my-page
```

その場合、欲しい言語を追記するだけで、新しい URL になります：

```txt
/fr/blog/custom/my-page
```

これは、 `/ma-page-francaise-personnalisee` と同じページが表示されます。

<h2 id="translation-support">翻訳サポート</h2>

Grav はシンプルながらパワフルな翻訳機能を提供し、 Twig や PHP 経由で、テーマやプラグインで利用できます。これはデフォルトで有効化されており、言語が定義されていなければ `en` 言語が使われます。翻訳を手動で有効化・無効化するには、 `system.yaml` ファイルを設定します：

```yaml
languages:
  translations: true
```

翻訳は、 `system.yaml` 内の `languages: supported:` で定義された言語のリストと同じものを利用します。

翻訳システムは、 Grav の config 設定に似たやり方で機能します。翻訳を提供する場所ややり方は、いくつかあります。

Grav が翻訳ファイルを探す最初の場所は、 `system/languages` フォルダです。ファイルは次のように作られていることが期待されます： `en.yaml`, `fr.yaml`, など。各 yaml ファイルは、キーと値がペアになった配列もしくは入れ子の配列を含まなければいけません：

```yaml
SITE_NAME: My Blog Site
HEADER:
    MAIN_TEXT: Welcome to my new blog site
    SUB_TEXT: Check back daily for the latest news
```

識別を簡単にするため、大文字の利用が望ましいです。これは未翻訳の文字列を判断する助けとなり、 Twig テンプレートで使うときに明確になるからです。

Grav には、フォールバック機能があり、サポート言語について、有効な言語のひとつが見つからなかった場合に、翻訳を探すための機能です。これはデフォルトで有効化されていますが、 `translations_fallback` オプションで無効化できます：

```yaml
languages:
  translations_fallback: true
```

> [!Tip]  
> **あなたの言語** で翻訳することにより、 Grav のユーザーコミュニティが広がっていくことへ力を貸してください。わたしたちは、 [Grav コア](https://crowdin.com/project/grav-core) と [Grav 管理パネルプラグイン](https://crowdin.com/project/grav-admin) の翻訳を促進するため、 [Crowdin 翻訳プラットフォーム](https://crowdin.com/) を利用しています。 [登録](https://crowdin.com/join) して、今日から翻訳を始めましょう！

<h3 id="language-switcher">言語の切り替え</h3>

シンプルな **Language Switching** プラグインを、管理パネル経由もしくは GPM からダウンロード可能です：

```bash
bin/gpm install langswitcher
```

[GitHub に、設定と実装のドキュメントがあります](https://github.com/getgrav/grav-plugin-langswitcher) 。


<h3 id="setup-with-language-specific-domains">特定のドメインでの設定</h3>

ドメインにデフォルト言語（第1言語）を割り当てるため、 [環境ベースの言語制御](#environment-based-language-handling) によりサイトを設定してみましょう。

オプションを確認してください：

```yaml
pages.redirect_default_route: true
```

上記が、 `system.yaml` ファイルで `true` になっていることを確認してください。

**.htaccess** ファイルに以下を追記してください。必要に応じて、言語スラッグやドメイン名を合わせてください：

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

rewrite rule の簡素化をご存知の方は、このページの上にある **Edit** リンクをクリックし、 GitHub のこのページを編集してください。

以下は、このルールセットのシンプルバージョンです：

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

このシンプルバージョンは、 "en" と "de" のサブページをリダイレクトする rewrite rule を、グルーピングによりひとつのルールに結合しています。加えて、重複を減らすため、管理パスの RewriteCond を結合しています。

> [!Note]  
> これらのルールは、 Grav CMS に付属するデフォルトルールの前に追加してください。

<h3 id="language-logic-in-twig-templates">twigテンプレート中の言語ロジック</h3>

しばしば、 Twig テンプレートから言語の状態やロジックにアクセスする必要があります。たとえば、特定の画像ファイルにアクセスする必要があるときに、その画像が特定の言語では異なるもので、名前も違う場合（ `myimage.en.jpg` と `myimage.fr.jpg` ）です。

正しいバージョンの画像を表示するには、現在有効になっている言語を知る必要があります。これは、 Grav では、 `Grav` オブジェクト経由で `Language` オブジェクトにアクセスし、適切なメソッドを呼び出すことでできます。この例の場合、以下のような Twig コードで解決できます：

```twig
{{ page.media.images['myimage.'~grav.language.getActive~'.jpg'].html()|raw }}
```

`getActive` を Twig 内で呼び出すと、効率的に `Language->getActive()` メソッドを呼び出し、現在有効になっている言語の言語コードを返します。いくつかの便利な Language メソッドがあります：

* `getLanguages()` - サポートしている言語すべての配列を返します
* `getLanguage()` - 現在のアクティブになっている言語、あるいはデフォルト言語を返します
* `getActive()` - 現在アクティブになっている言語を返します
* `getDefault()` - デフォルトの（最初の）言語を返します

利用可能なメソッドのすべてについては、 `<grav root>/system/src/Grav/Common/Language/Language.php` ファイルをご覧ください。

