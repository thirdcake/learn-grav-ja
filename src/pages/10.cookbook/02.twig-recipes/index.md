---
title: Twigレシピ
layout: ../../../layouts/Default.astro
lastmod: '2025-05-15'
---
このページでは、 Twig テンプレートに関するさまざまな問題とその解決策を記載しています。

<h2 id="list-the-last-5-recent-blog-posts">ブログ投稿で最近の5つのリスト</h2>

<h5 id="problem">問題：</h5>

サイドバーに直近の5投稿のブログ記事を表示したい。
これにより、読者が最近のブログ状況を知ることができるようにしたい。

<h5 id="solution">解決策：</h5>

`/blog` ページを見つけて、その子ページを取得し、日付降順で並び替え、最初の5つを取得し、リストに表示します：

```twig
<ul>
{% for post in page.find('/blog').children.published.order('date', 'desc').slice(0, 5) %}
    <li class="recent-posts">
        <strong><a href="{{ post.url }}">{{ post.title }}</a></strong>
    </li>
{% endfor %}
</ul>
```

ページ内で使うときは、フロントマターに以下の設定を付け加えることを忘れないでください：

```yaml
twig_first: true
process:
    twig: true
```


<h2 id="add-non-modular-navigation-links">モジュラーページのモジュール以外のナビゲーションリンク</h2>

<h5 id="problem-1">問題：</h5>

モジュラーページ以外のナビゲーションリンクを表示したい。

<h5 id="solution-1">解決策：</h5>

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

<h5 id="problem-2">問題：</h5>

今年のカレンダー上の1年に投稿したすべてのブログ記事を表示したい。

<h5 id="solution-2">解決策：</h5>

`/blog` ページを探し、その子ページを取得し、適切な `dateRange()` メソッドでフィルタリングし、日付降順で並び替えます：

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

<h2 id="displaying-a-translated-month">翻訳された月を表示する</h2>

<h5 id="problem-3">問題：</h5>

いくつかのページテンプレートでは、 Twig `date` フィルターが使われており、 それはロケールや多言語に対応していません。そのため、たとえ英語以外の言語ページであっても、テンプレートが月名を表示する選択になっていれば、英語で月が表示されてしまいます。

<h5 id="solution-3">解決策：</h5>

この問題については、2つの解決策があります。

<h6 id="first-approach">最初のアプローチ</h6>

最初の方法は、Twig intl 拡張機能を使用します。

https://github.com/Perlkonig/grav-plugin-twig-extensions をインストールしてください。

twig テンプレートで、たとえば（ Antimatter テーマのように） `{{ page.date|date("M") }}` のかわりに `{{ page.date|localizeddate('long', 'none', 'it', 'Europe/Rome', 'MMM') }}` にします（ここにタイムゾーンを追加してください）。

<h6 id="second-approach">2つ目のアプローチ</h6>

たとえば、 `user/languages/` フォルダにある `en.yaml` ファイルに、いくつかの言語翻訳設定がされているとします：

```yaml
MONTHS_OF_THE_YEAR: [January, February, March, April, May, June, July, August, September, October, November, December]
```

そして `fr.yaml` では：

```yaml
MONTHS_OF_THE_YEAR: [Janvier, Février, Mars, Avril, Mai, Juin, Juillet, Août, Septembre, Octobre, Novembre, Décembre]
```

そして、 Twig では：

```html
<li>
    <a href='{{ post.url }}'><aside class="dates">{{ 'GRAV.MONTHS_OF_THE_YEAR'|ta(post.date|date('n') - 1) }} {{ post.date|date('d') }}</aside></a>
    <a href='{{ post.url }}'>{{ post.title }}</a>
</li>
```

これは Grav のカスタム Twig フィルターで、 **Translate Array** の略である `|ta` を使っています。英語では、出力は次のようになります：

```txt
An Example Post  July 2015
```

そしてフランス語では：

```txt
Un exemple d’article Juillet 2015
```

<h2 id="displaying-page-content-without-summary">要約無しでページコンテンツを表示する</h2>

<h5 id="problem-4">問題：</h5>

上部に要約を掲載せずに、ページコンテンツを表示したい。

<h5 id="solution-4">解決策：</h5>

`slice` フィルターを使って、ページコンテンツから要約部分を削除してください：

```twig
{% set content = page.content|slice(page.summary|length) %}
{{ content|raw }}
```

<h2 id="hiding-the-email-to-spam-bots">スパムボットから Eメールを隠す</h2>

<h5 id="problem-5">問題：</h5>

スパムボットから Eメールを隠したい。

<h5 id="solution-5">解決策：</h5>

ページのフロントマターで、 Twig 処理を有効化します：

```yaml
process:
    twig: true
```

次に、 `safe_email` Twig フィルターを使います：

```html
<a href="mailto:{{'your.email@server.com'|safe_email}}">
  Email me
</a>
```

<h2 id="picking-a-random-item-from-a-translated-array">翻訳された配列からランダムにアイテムを取り上げる</h2>

<h5 id="problem-6">問題：</h5>

特定の言語で翻訳された配列から、ランダムにアイテムを取り上げたい。この機能のため、 [多言語サイト設定](../../02.content/11.multi-language/) がドキュメントの大枠通り設定されていることを前提とします。

<h5 id="solution-6">解決策：</h5>

また、 `user/languages/` フォルダにいくつかの言語の翻訳設定があることを前提とします。
`en.yaml` ファイルには、次のような内容が書かれています：

```txt
FRUITS: [Banana, Cherry, Lemon, Lime, Strawberry, Raspberry]
```

そして、 `fr.yaml` には：

```txt
FRUITS: [Banane, Cerise, Citron, Citron Vert, Fraise, Framboise]
```

そして、 Twig はこうなります：

```twig
{% set langobj  = grav['language'] %}
{% set curlang  = langobj.getLanguage() %}
{% set fruits   = langobj.getTranslation(curlang,'FRUITS',true) %}
<span data-ticker="{{ fruits|join(',') }}">{{ random(fruits) }}</span>
```

<h2 id="displaying-an-image-uploaded-in-a-file-field">file フィールドでアップロードされた画像を表示する</h2>

<h5 id="problem-7">問題：</h5>

カスタムブループリントに `file` フィールドを追加し、このフィールドで追加された画像を表示したい。

<h5 id="solution-7">解決策：</h5>

`file` フィールドでは、複数の画像がアップロードできるため、フロントマターに2つのネストされたオブジェクトが生成されます。1つ目のオブジェクトは、アップロード画像のリストで、その中にネストされたオブジェクトは、与えられた画像のプロパティ/値の集合です。

_注意点として、ユーザーに1つの画像のみ選択させたい場合は、 `filepicker` フィールドを使った方が簡単です。このフィールドは、選択された画像のプロパティとともに、1つのオブジェクトを保存します。_

画像が1つなら、次のようにしてテンプレートに表示できます：

```twig
{{ page.media[header.yourfilefield|first.name] }}
```

複数画像のアップロードを許可するなら、 twig は次のようになります：

```twig
{% for imagesuploaded in page.header.yourfilefield %}
{{ page.media[imagesuploaded.name] }}
{% endfor %}
```

<h2 id="displaying-an-image-picked-in-a-mediapicker-field">mediapicker フィールドで選択した画像を表示する</h2>

<h5 id="problem-8">問題：</h5>

カスタムブループリントに `mediapicker` フィールドを追加し、選択画像を表示したい。

<h5 id="solution-8">解決策：</h5>

`mediapicker` フィールドをブループリントに追加するのは、次のようにします：

```yaml
header.myimage:
  type: mediapicker
  folder: 'self@'
  label: Select a file
  preview_images: true
```

`mediapicker` フィールドは `/home/background.jpg` のような文字列で画像への path を保存します。
ページのメディア機能でこの画像にアクセスするには、この文字列を分割しなければいけません：

- 画像が保存されたページへの path
- 画像の名前。

以下のスニペットを使って、 twig 経由でこれを実行できます：

```twig
{% set image_parts = pathinfo(header.myimage) %}
{% set image_basename = image_parts.basename %}
{% set image_page = image_parts.dirname == '.' ? page : page.find(image_parts.dirname) %}

{{ image_page.media[image_basename].html()|raw }}
```

<h2 id="custom-twig-filter-function">カスタム Twig フィルター/関数</h2>

<h5 id="problem-9">問題：</h5>

ときには、 PHP でしか実行できないようなロジックが Twig で必要となることもあります。
そのような場合、最適な解決策は、カスタムの Twig フィルターや Twig 関数を作ることです。
フィルターは通常、 `"some string"|custom_filter` というフォーマットで文字列に追加されます。
関数は、文字列もしくは他のあらゆる型の変数を受け取り、 `custom_function("some string")` というフォーマットになります。
しかし、本質ではどちらもとてもよく似ています。

フィルター内で追加のパラメータが使える場合、それを渡すこともできます。このように： `"some string"|custom_filter('foo', 'bar')` 
関数の場合は、こうなります： `custom_function("some string", 'foo', 'bar')` 。

この例では、文字列を区切り文字で区切られたチャンクに分割し、その数を数えるシンプルな Twig フィルターを作成します。これは、クレジットカード番号やライセンスキーなどの情報を取得する際に、特に便利です。

<h5 id="solution-9">解決策：</h5>

この機能を追加する最良の方法は、カスタムプラグインにロジックを追加することです。しかし、テーマの PHP ファイルに追加することもできます。
この例では、簡単のためにプラグインを使います。
まず、ウィザードから簡単な処理でプラグインを作成するために、 devtools プラグインをインストールする必要があります：

```bash
bin/gpm install devtools
```

次に、新しいカスタムプラグインを作成し、プロンプトの表示ごとに詳細を入力してください。

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

デフォルトでは、この新しいプラグイン用のスケルトンフレームワークは、 `onPageContentRaw()` イベントを介して、ページにダミーテストを追加します。
まず、この機能を `onTwigInitialized()` イベントで発火するコードに書きかえる必要があります：

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

まず、 `onTwigInitialized()` メソッドでフィルターを登録する必要があります：

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

メソッドの最初のパラメーターは、フィルター名として `chunker` を登録し、ロジックが実行される PHP メソッドとして `chunkString` を登録します。
そのため、次のように作成する必要があります：

```php
    /**
     * Break a string up into chunks
     */
    public function chunkString($string, $chunksize = 4, $delimiter = '-')
    {
        return (trim(chunk_split($string, $chunksize, $delimiter), $delimiter));
    }
```

次のように、 Twig テンプレートで試すことができます：

```twig
{{ "ER27XV3OCCDPRJK5IVSDME6D6OT6QHK5"|chunker }}
```

結果は、次のようになります：

```txt
ER27-XV3O-CCDP-RJK5-IVSD-ME6D-6OT6-QHK5
```

もしくは、追加のパラーめたーを渡せます：

```twig
{{ "ER27XV3OCCDPRJK5IVSDME6D6OT6QHK5"|chunker(8, '|') }}
```

結果は次のようになります：

```txt
ER27XV3O|CCDPRJK5|IVSDME6D|6OT6QHK5
```

最後に、これをフィルターだけでなく関数経由でも利用できるようにするには、 `onTwigInitialized()` メソッドに同じ名前の Twig 関数を登録するだけです：

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

そして、関数の構文を利用できます：

```twig
{{ chunker("ER27XV3OCCDPRJK5IVSDME6D6OT6QHK5", 8, '|') }}
```

<h2 id="extend-base-template-of-inherited-theme">継承したテーマのベーステンプレートを拡張する</h2>

<h5 id="problem-10">問題：</h5>

ベーステンプレートそれ自体を拡張する必要があることもあります。
これは、テンプレート内にすでに存在する block を拡張するための簡単で分かりやすい方法が無いときに起こります。
Quark を親テーマの例として使い、 `myTheme` テーマで `themes/quark/templates/partials/base.html.twig` を拡張してみましょう。

<h5 id="solution-10">解決策：</h5>

テーマの Quark を Twig の名前空間として追加できます。テーマの `my-theme.php` で、 `onTwigLoader` イベントで発火するようにし、 Quark テーマテンプレートディレクトリを追加することで、追加できます。
class の内容は、次のようになるでしょう：

```php
<?php
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

これで、 `themes/my-theme/templates/partials/base.html.twig` において、 Quark のベーステンプレートを次のように拡張できます：

```twig
    {% extends '@quark/partials/base.html.twig' %}
    
    {% block header %}
    This is a new extended header.
    {% endblock %}
```

