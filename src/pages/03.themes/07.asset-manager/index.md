---
title: アセットマネージャー
layout: ../../../layouts/Default.astro
lastmod: '2025-06-13'
---

> [!訳注]  
> このページの内容は、今のわたしには難しい内容のため、後回しになっています。とりあえず、 Twig カスタムタグの [`script`](../04.twig-tags-filters-functions/01.tags/#script) と [`style`](../04.twig-tags-filters-functions/01.tags/#style) の使い方がわかっていれば、実務上は、ほとんど問題は無いと思います。Grav 1.7.28 以上では、`アセットパイプライン` を使って、ミニファイや1つのファイルに結合したい場合にのみ、このアセットマネージャーが必要になり、それ以外の場合は、上記のカスタムタグで代替可能という認識です。

Grav 1.6 で、**アセットマネージャー** が完全に書き直されました。テーマで、**CSS** や **JavaScript** のアセットをより柔軟なメカニズムで管理できるようになりました。アセットマネージャーの主な目的は、テーマやプラグインでアセットを追加する処理をシンプルにし、優先順位などの強化された機能を提供することです。また、アセットを **ミニファイ** し、**圧縮** し、**インライン化** する **アセットパイプライン** を提供し、ブラウザのリクエストを減らし、アセットの全体サイズも小さくします。

以前よりも、より柔軟に、より信頼できるようになりました。さらに、コードもより 'クリーン' になり、読みやすくなりました。アセットマネージャーは、 Grav の処理中に利用可能で、プラグインのイベントフックでも利用でき、さらに Twig の呼び出しでテーマから直接利用できます。

> [!Note]  
> **技術的詳細** ：主要なアセットの class は大幅にシンプルになり、小さくなりました。ロジックの多くは3つの trait に分割されました。 _testing trait_ は、主に test suite で使われる関数を含みます。 _utils trait_ は、通常のアセットタイプ（JS、インラインJS、CSS、インラインCSS）と、ミニファイや圧縮を行うアセットパイプラインで共有されるメソッドを含みます。最後に、 _legacy trait_ は、ショートカットや回避策のメソッドを含みますが、一般的には、今後は使わない方が良いです。

> [!Tip]  
> アセットマネージャーは、Grav 1.6 以前のバージョンの構文と完全に下位互換がありますが、このドキュメントでは、これ以降、新しい `優先構文` を説明します。

<h2 id="configuration">設定</h2>

アセットマネージャーには、シンプルな設定オプションがあります。デフォルト値は、 system フォルダの `system.yaml` ファイルにあります。 `user/config/system.yaml` ファイルで、上書きしてください。

```yaml
assets:                                        # Configuration for Assets Manager (JS, CSS)
  css_pipeline: false                          # The CSS pipeline is the unification of multiple CSS resources into one file
  css_pipeline_include_externals: true         # Include external URLs in the pipeline by default
  css_pipeline_before_excludes: true           # Render the pipeline before any excluded files
  css_minify: true                             # Minify the CSS during pipelining
  css_minify_windows: false                    # Minify Override for Windows platforms, also applies to js. False by default due to ThreadStackSize
  css_rewrite: true                            # Rewrite any CSS relative URLs during pipelining
  js_pipeline: false                           # The JS pipeline is the unification of multiple JS resources into one file
  js_pipeline_include_externals: true          # Include external URLs in the pipeline by default
  js_pipeline_before_excludes: true            # Render the pipeline before any excluded files
  js_module_pipeline: false                    # The JS Module pipeline is the unification of multiple JS Module resources into one file
  js_module_pipeline_include_externals: true   # Include external URLs in the pipeline by default
  js_module_pipeline_before_excludes: true     # Render the pipeline before any excluded files
  js_minify: true                              # Minify the JS during pipelining
  enable_asset_timestamp: false                # Enable asset timestamps
  collections:
    jquery: system://assets/jquery/jquery-2.x.min.js
```

<h2 id="structure">構造</h2>

下のダイアグラムに示すように、ポジションを制御する多数の層に分かれています。スコープの順に並べると、次のようになります：

* **Group** - アセットを次のようにグループ分けします。 `head` （デフォルト）と、 `bottom`
* **Position** - `before`, `pipeline` （デフォルト）そして `after` に分かれています。基本的に、これによって、アセットがどこで読み込まれるべきかが判別できるようになります。
* **Priority** - ここで、 **順序** を制御します。デフォルトでは、大きな整数値（例 `100`） は、小さな整数値（`10`）よりも前になります。

```txt
 CSS
┌───────────────────────┐
│ Group (head)          │
│┌─────────────────────┐│        ┌──────────────────┐
││ Position            ││        │   priority 100   │─────┐     ┌──────────────────┐
││┌───────────────────┐││        ├──────────────────┤     ├────▶│       CSS        │
│││                   │││        │   priority 99    │─────┤     └──────────────────┘
│││      before       │├┼──┬────▶├──────────────────┤     │
│││                   │││  │     │    priority 1    │─────┤     ┌──────────────────┐
││├───────────────────┤││  │     ├──────────────────┤     ├────▶│    inline CSS    │
│││                   │││  │     │    priority 0    │─────┘     └──────────────────┘
│││     pipeline      │├┼──┤     └──────────────────┘
│││                   │││  │
││├───────────────────┤││  │
│││                   │││  │
│││       after       │├┼──┘
│││                   │││
││└───────────────────┘││
│└─────────────────────┘│
└───────────────────────┘


JS
┌───────────────────────┐
│ Group (head)          │
│┌─────────────────────┐│        ┌──────────────────┐
││ Position            ││        │   priority 100   │─────┐     ┌──────────────────┐
││┌───────────────────┐││        ├──────────────────┤     ├────▶│        JS        │
│││                   │││        │   priority 99    │─────┤     └──────────────────┘
│││      before       │├┼──┬────▶├──────────────────┤     │
│││                   │││  │     │    priority 1    │─────┤     ┌──────────────────┐
││├───────────────────┤││  │     ├──────────────────┤     ├────▶│    inline JS     │
│││                   │││  │     │    priority 0    │─────┘     └──────────────────┘
│││     pipeline      │├┼──┤     └──────────────────┘
│││                   │││  │
││├───────────────────┤││  │
│││                   │││  │
│││       after       │├┼──┘
│││                   │││
││└───────────────────┘││
│└─────────────────────┘│
└───────────────────────┘



JS Module
┌───────────────────────┐
│ Group (head)          │
│┌─────────────────────┐│        ┌──────────────────┐
││ Position            ││        │   priority 100   │─────┐     ┌─────────────────────────┐
││┌───────────────────┐││        ├──────────────────┤     ├────▶│        JS Module        │
│││                   │││        │   priority 99    │─────┤     └─────────────────────────┘
│││      before       │├┼──┬────▶├──────────────────┤     │
│││                   │││  │     │    priority 1    │─────┤     ┌─────────────────────────┐
││├───────────────────┤││  │     ├──────────────────┤     ├────▶│    inline JS Module     │
│││                   │││  │     │    priority 0    │─────┘     └─────────────────────────┘
│││     pipeline      │├┼──┤     └──────────────────┘
│││                   │││  │
││├───────────────────┤││  │
│││                   │││  │
│││       after       │├┼──┘
│││                   │││
││└───────────────────┘││
│└─────────────────────┘│
└───────────────────────┘
```

デフォルトでは、`CSS` と、 `JS` 、 `JS Module` は、`pipeline` ポジションに置かれます。一方、 `InlineCSS` と、 `InlineJS` 、 `Inline JS Module` は、`after` ポジションになります。しかしこの設定は変更可能です。どんなアセットを、どんなポジションに設定することもできます。

<h2 id="assets-in-themes">テーマ中のアセット</h2>

<h3 id="overview">概要</h3>

CSS アセットを追加したい時、普通は、`assets.addCss()` や、`assets.addInlineCss()` を呼び出して、 `assets.css()` によりレンダリングすると思います。優先度や、パイプライン化、インライン化をしたい場合、アセットの追加時にアセットごとに指定することもできますし、アセットグループに対してレンダリング時にすることもできます。

JS アセットも似ていて、`assets.addJs()` や、 `assets/addInlineJs()` を呼び出します。一般的な `assets.add()` メソッドもあり、アセットのタイプを推測しますが、特定のメソッドを呼び出すことをおすすめします。

バージョン 1.7.27 から、アセットマネージャーは、JS Modulesにも対応します。これらのアセットは、JSアセットと同じように機能しますが、`type="module"` となり、`assets.addJsModule()` や、 `assets.addInlineJsModule()` で呼び出します。`assets.add()` メソッドは、拡張子が `.mjs` のときのみ、JS Module と認識します。しかし、`.js` ファイルはすべて、普通のJSファイルとして扱います。

> [!Note]  
> JS Modulesについてもっと学びたいとき：
> * [https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Modules](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Modules)
> * [https://v8.dev/features/modules](https://v8.dev/features/modules)
> * [https://javascript.info/modules-intro](https://javascript.info/modules-intro)

アセットマネージャーは、次のようなものにも対応します：

* 名前付きグループを異なる場所や異なるオプション設定でレンダリングするために、そのグループにアセットを追加すること
* `assets.add*()` 呼び出しで追加できる名前付きアセットのコレクションを設定すること

<h3 id="example">具体例</h3>

テーマ内で CSS ファイルを追加できる方法の具体例は、 Grav に最初からバンドルされているデフォルトの **quark** テーマにあります。もし [`templates/partials/base.html.twig`](https://github.com/getgrav/grav-theme-quark/blob/develop/templates/partials/base.html.twig) 部分を見れば、次のようなことが書いてあるでしょう：

```twig
<!DOCTYPE html>
<html>
    <head>
    ...

    {% block stylesheets %}
        {% do assets.addCss('theme://css-compiled/spectre.css') %}
        {% do assets.addCss('theme://css-compiled/theme.css') %}
        {% do assets.addCss('theme://css/custom.css') %}
        {% do assets.addCss('theme://css/line-awesome.min.css') %}
    {% endblock %}

    {% block javascripts %}
        {% do assets.addJs('jquery', 101) %}
        {% do assets.addJs('theme://js/jquery.treemenu.js', { group: 'bottom' }) %}
        {% do assets.addJs('theme://js/site.js', { group: 'bottom' }) %}
        {% do assets.addJsModule('plugin://my_plugin/app/main.js', { group: 'bottom' }) %}
    {% endblock %}

    {% block assets deferred %}
        {{ assets.css()|raw }}
        {{ assets.js()|raw }}
    {% endblock %}
    </head>

    <body>
    ...

    {% block bottom %}
        {{ assets.js('bottom')|raw }}
    {% endblock %}
    </body>
</html>
```

`block stylesheets` という twig タグは、これを extend するテンプレートで、入れ替えられたり、追加されたりする場所を定義しているだけです。ブロック内では、たくさんの `do assets.addCss()` が呼び出されていることがわかります。

この `{% do %}` タグは、それ自体が [Twig に組み込まれたタグ](https://twig.symfony.com/doc/1.x/tags/do.html) であり、これにより、出力を生成することなく、変数を操作できます。

`addCss()` メソッドは、 CSS アセットを アセットマネージャーに追加するメソッドです。優先度を指定しない場合、アセットが追加される優先度は、それらがレンダリングされた順番になります。 Grav が現在のテーマの相対パスを決定しやすくするために **PHP ストリームラッパー** である `theme://` が使われていることにお気づきでしょう。

> [!Info]  
> `assets.addJs('jquery', 101)` は、グローバルのアセット設定で定義された `jquery` のコレクションを含みます。ここでの `101` というオプションのパラメータは、確実に最初にレンダリングされるように、極めて高い優先度を設定されています。パラメータが無かった場合のデフォルトの優先度は `10` です。より柔軟な書き方は、 `assets.addJs('jquery', {priority: 101})` です。これにより、優先順位とともに他のパラメータも追加できます。

`assets.css()|raw` を呼び出すと、CSS アセットを HTML タグとしてレンダリングします。このメソッドにはパラメータが適用されていないので、このグループはデフォルトで `head` に設定されます。これがどのように `assets deferred` ブロックに囲まれているか、注意して見てください。これは Grav 1.6 の新しい機能で、ページの後から読み込まれた（もしくは、実際は後でなくても任意の場所で読み込まれた）他のテンプレートからアセットを追加できるようになります。そして、必要であればこの `head` にレンダリングされることが保証されます。

テーマ出力の最後にある `bottom` ブロックは、 `bottom` グループに配置された JavaScript をレンダリングします。

<h2 id="adding-assets">アセットを追加</h2>

#### add(asset, [options])

add メソッドは、ファイル拡張子をもとにアセットにマッチするように最善を尽くします。これは便利なメソッドではありますが、CSS や、Link、JS や JS モジュールの直接的なメソッドを呼び出す方が良いでしょう。詳細は直接的なメソッドの説明をお読みください。

> [!Info]  
> 複数のオプションを渡す際には、オプションの配列を渡すのが望ましいです。しかし、先ほどの `jquery` の例のように、**優先度** だけ設定したい場合は、ショートカットを使い、 **第2引数** として整数を渡すことができます。

#### addCss(asset, [options])

このメソッドは、アセットを CSS アセットのリストに追加します。指定しなかった場合のデフォルトの優先度は、 10 です。優先度が大きい数字のアセットは、小さい数字のアセットよりも先に表示されます。 `pipeline` オプションは、このアセットが combination/minify パイプラインに含まれるべきかどうかを制御します。パイプラインに含まれないなら、 `loading` オプションは、そのアセットが外部のスタイルシートへのリンクと同様にレンダリングされるべきか、それともインラインのスタイルタグ内に直接書き込まれるべきコンテンツかをコントロールします。

#### addLink($asset, [options])

このメソッドは、アセットを `<link>` タグ形式の Link アセットのリストに追加します。これは、CSS ファイルではない link タグをサイト内の任意の場所から head タグに追加するときに便利です。優先度は、指定されなければデフォルトで 10 です。優先度の大きい数字のアセットは、小さい数字のアセットよりも先に表示されます。

他のアセット追加メソッドとは異なり、 `link()` はパイプラインも `inline` もサポートしません。

#### addInlineCss(css, [options])

インラインの style タグ内に CSS 文字列を追加します。初期化や、動的処理の際に便利です。標準的なアセットファイルコンテンツをインライン化するには、 `addCss()` と `css()` メソッドの `{'loading': 'inline'}` オプションを参照してください。

#### addJs(asset, [options])

このメソッドは、 JavaScript アセットのリストにアセットを追加します。優先度はデフォルトで 10 です。大きい数字のアセットが、小さい数字のアセットよりも先に読み込まれます。 `pipeline` オプションは、このアセットが 結合/ミニファイ するパイプラインに含まれるべきかどうかを制御します。パイプラインされない場合、 `loading` オプションで、アセットが外部のスクリプトファイルへのリンクとしてレンダリングされるべきか、インラインの script タグ内に書き込まれるべきコンテンツかを制御します。

#### addInlineJs(javascript, [options])

インラインの script タグ内に、JavaScript 文字列を追加します。初期化や、あらゆる動的処理に便利です。標準的なアセットファイルのコンテンツに書き込むため、 `addJs()` メソッドや、 `js()` メソッドの `{ 'loading': 'inline' }` オプションを参照してください。

#### addJsModule(asset, [options])

このメソッドは、 JavaScript モジュールアセットのリストに、アセットを追加します。優先度はデフォルトで 10 です。大きい数字のアセットが、小さい数字のアセットよりも先に読み込まれます。 `pipeline` オプションは、このアセットが 結合/ミニファイ するパイプラインに含まれるべきかどうかを制御します。パイプラインされない場合、 `loading` オプションで、アセットが外部のスクリプトファイルへのリンクとしてレンダリングされるべきか、インラインの script タグ内に書き込まれるべきコンテンツかを制御します。

#### addInlineJsModule(javascript, [options])

インラインのモジュール script タグ内に、 JavaScript 文字列を追加します。標準的なアセットファイルのコンテンツをインライン化するために、 `addJsModule()` メソッドや、 `Js()` メソッドの `{ 'loading': 'inline' }` オプションを参照してください。

#### registerCollection(name, array)

CSS と JavaScript アセットの配列を名前付きで登録でき、あとで `add()` メソッドにより使うことができます。 jQuery や Bootstrap のように、複数のテーマやプラグインで使われるコレクションを登録したい場合に、特に便利です。

<h2 id="options">オプション</h2>

必要に応じて、アセットオプションの配列を渡すことができます。コアのオプションは、次のとおりです：

<h4 id="for-css">CSS 向け</h4>

* **priority**: 整数値（デフォルト値は `10` ）

* **position**: `pipeline` がデフォルトですが、アセットの `before` または `after` にすることが可能です。

* **loading**: （デフォルトのスタイルシートへのリンクを通した参照ではなく）インラインで出力したい場合は、`inline` としてください。（デフォルトの） `position: pipeline` と一緒に使うと何も影響が無くなるので、 `position: before` や `position: after` といっしょに使ってください。

* **group**: 識別可能なアセットのグループ名を指定する文字列（デフォルトは `head` ）

<h4 id="for-js-and-js-module">JS および JS モジュール向け</h4>

* **priority**: 整数値（デフォルト値は `10` ）

* **position**: `pipeline` がデフォルトですが、アセットの `before` または `after` にすることが可能です。

* **loading**: あらゆる loading type （ `async`, `defer`, `async defer` or `inline` ）をサポートします。（デフォルトの） `position: pipeline` と一緒に使うと何も影響が無くなるので、 `position: before` や `position: after` といっしょに使ってください。

* **group**: 識別可能なアセットのグループ名を指定する文字列（デフォルトは `head` ）

<h4 id="other-attributes">その他の属性</h4>

オプションの配列には、他に好きなものを何でも渡すことができます。もしそれらが標準的な型でない場合は、たとえば `{id: 'custom-id'}` が HTML タグ中の `id="custom-id"` としてレンダリングされるように、属性としてレンダリングされます。このことは、 `{type: 'application/ld+json'}` を使って、 `addInlineJs()` を経由した json-ld のような構造化データを含んでも使われます。

<h4 id="examples">具体例</h4>

具体例：

```twig
{% do assets.addCss('page://01.blog/assets-test/example.css?foo=bar', { priority: 20, loading: 'inline', position: 'before'}) %}
```

上記は、下記のようにレンダリングされます：

```html
<style>
h1.blinking {
    text-decoration: underline;
}
</style>
<link.....
```

具体例-2：

```twig
{% do assets.addJs('page://01.blog/assets-test/example.js', {loading: 'async', id: 'custom-css'}) %}
```

上記は、下記のようにレンダリングされます：

```html
<script src="/grav/user/pages/01.blog/assets-test/example.js" async id="custom-css"></script>
```

Link の具体例：

```twig
{% do assets.addLink('theme://images/favicon.png', { rel: 'icon', type: 'image/png' }) %}
{% do assets.addLink('plugin://grav-plugin/build/js/vendor.js', { rel: 'modulepreload' }) %}
```

上記は、下記のようにレンダリングされます：

```html
<link rel="icon" type="image/png" href="/user/themes/quark/images/favicon.png">
<link href="/user/plugins/grav-plugin/build/js/vendor.js" rel="modulepreload">
```

<h2 id="rendering-assets">アセットをレンダリングする</h2>

以下のようにすることで、 CSS や JavaScript アセットの現在の状態をレンダリングできます。

#### css(group, [options], include_link = true)

アセットマネージャーのグループ（デフォルトでは `head` ）に追加されている CSS アセットをレンダリングします。オプションは：

* **loading**: `inline` if **all** assets in this group should be inlined (default: render each asset according to its `position` option)

* **_link attributes_**, see below (default: `{'type': 'text/css', 'rel': 'stylesheet'}`). Effective only if `inline` is **not** used as this group's rendering option

When `include_link` is enabled, which it is by default, calling `css()` will also propagate to calling `link()`.

If pipelining is turned **off** in the configuration, the group's assets are rendered individually, ordered by asset priority (high to low), followed by the order in which assets were added.

If pipelining is turned **on** in the configuration, assets in the pipeline position are combined in the order in which assets were added, then processed according to the pipeline configuration.

Each asset is rendered either as a stylesheet link or inline, depending on the asset's `loading` option and whether `{'loading': 'inline'}` is used for this group's rendering. CSS added by `addInlineCss()` will be rendered in the `after` position by default, but you can configure it to render before the pipelined output with `position: before`

#### link(group, [options])

Renders Link assets that have been added to an Asset Manager's group (default is `head`). It is not recommended using a group different from `head`, this is where the browser expect the tag to be found and processed.

Differently than the other methods for adding assets, `link()` does not support pipelining, nor does support `inline`.

#### js(group, [options], include_js_module = true)

Renders JavaScript assets that have been added to an Asset Manager's group (default is `head`). Options are

* **loading**: `inline` if **all** assets in this group should be inlined (default: render each asset according to its `position` option)

* **_script attributes_**, see below (default: `{'type': 'text/javascript'}`). Effective only if `inline` is **not** used as this group's rendering option

When `include_js_module` is enabled, which it is by default, calling `js()` will also propagate to calling `jsModule()`.

If pipelining is turned **off** in the configuration, the group's assets are rendered individually, ordered by asset priority (high to low), followed by the order in which assets were added.

If pipelining is turned **on** in the configuration, assets in the pipeline position are combined in the order in which assets were added, then processed according to the pipeline configuration. The combined pipeline result is then rendered before or after non-pipelined assets depending on the setting of `js_pipeline_before_excludes`.

Each asset is rendered either as a script link or inline, depending on the asset's `loading` option and whether `{'loading': 'inline'}` is used for this group's rendering. Note that the only way to inline a JS pipeline is to use inline loading as an option of the `js()` method. JS added by `addInlineJs()` will be rendered in the `after` position by default, but you can configure it to render before the pipelined output with `position: before`


#### jsModule(group, [options])

Works exactly like the `js()` renderer, but for JavaScript modules. The default script type attribute is `type="module"`, even when rendering `inline`.

#### all(group, [options])

Renders every asset above in the order: `css()`, `link()`, `js()`, `jsModule()`

This is the recommended way of including deferred assets into your main twig file (usually `base.html.twig`).

```twig
{% block assets deferred %}
  {{ assets.all()|raw }}
{% endblock %}
```

## Named Assets and Collections

Grav now has a powerful feature called **named assets** that allows you to register a collection of CSS and JavaScript assets with a name.  Then you can simply **add** those assets to the Asset Manager via the name you registered the collection with.  Grav comes preconfigured with **jQuery** but has the ability to define custom collections in the `system.yaml` to be used by any theme or plugin:

```yaml
assets:
  collections:
    jquery: system://assets/jquery/jquery-2.1.3.min.js
    bootstrap:
        - https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css
        - https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css
        - https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js
```

You can also use the `registerCollection()` method programmatically.

```yaml
$assets = $this->grav['assets'];
$bootstrapper_bits = [https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css,
                      https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css,
                      https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js];
$assets->registerCollection('bootstrap', $bootstrap_bits);
$assets->add('bootstrap', 100);
```

An example of this action can be found in the [**bootstrapper** plugin](https://github.com/getgrav/grav-plugin-bootstrapper/blob/develop/bootstrapper.php#L51-L71).

##### Collections with attributes
Sometimes you might want to specify custom and/or different attributes to specific items in a collection, for example if you are loading assets from a remote CDN, and you wish to include the integrity check (SRI). This is possible by treating the value of the named asset as an array where the key is the asset location, and the value is the list of additional attributes. For example:

```yaml
assets:
  collections:
    jquery_and_ui:
        https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js:
            integrity: 'sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=='
            group: 'bottom'
        https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js:
            integrity: 'sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=='
            group: 'bottom'
```

Then, after you add the JS in your twig via `{% do assets.addJs('jquery_and_ui', { defer: true }) %}`, the assets will load as:

```html
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" defer="1" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" defer="1" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="></script>
```

Note that `defer` was defined at the twig level and it was applied to all the assets in the collection. This is because Grav will merge together the attributes from both the twig and the yaml definition, giving priority to the ones in the yaml definition.

If the `jquery-ui.min.js` asset included also an attribute `defer: null` then it would have taken precedence over the twig `defer: 1` and it would have not been rendered.

## Grouped Assets

The Asset manager lets you pass an optional `group` as part of an options array when adding assets.  While this is of marginal use for CSS, it is especially useful for JavaScript where you may need to have some JS files or Inline JS referenced in the header, and some at the bottom of the page.

To take advantage of this capability you must specify the group when adding the asset, and should use the options syntax:

```twig
{% do assets.addJs('theme://js/example.js', {'priority':102, 'group':'bottom'}) %}
```

Then for these assets in the bottom group to render, you must add the following to your theme:

```twig
{{ assets.js('bottom')|raw }}
```

If no group is defined for an asset, then `head` is the default group.  If no group is set for rendering, the `head` group will be rendered. This ensures the new functionality is 100% backwards compatible with existing themes.

The same goes for CSS files:

```twig
{% do assets.addCss('theme://css/ie8.css', {'group':'ie'}) %}
```

and to render:


```twig
{{ assets.css('ie')|raw }}
```

## Change attribute of the rendered CSS/JS assets

CSS is by default added using the `rel="stylesheet"` attribute, and `type="text/css"` , while JS has `type="text/javascript"`.

To change the defaults, or to add new attributes, you need to create a new group of assets, and tell Grav to render it with that attribute.

Example of editing the `rel` attribute on a group of assets:

```twig
{% do assets.addCSS('theme://whatever.css', {'group':'my-alternate-group'}) %}
...
{{ assets.css('my-alternate-group', {'rel': 'alternate'})|raw }}
```

## Inlining Assets

Inlining allows the placing critical CSS (and JS) code directly into the HTML document enables the browser to render a page immediately without waiting for external stylesheet or script downloads. This can improve site performance noticeably for users, particularly over mobile networks. Details can be found in [this article on optimizing CSS delivery](https://developers.google.com/speed/docs/insights/OptimizeCSSDelivery).

However, directly inserting CSS or JavaScript code into a page template is not always feasible, for example, where Sass-complied CSS is used. Keeping CSS and JS assets in separate files also simplifies maintenance. Using the Asset Manager's inline capability enables you to optimize for speed without changing the way your assets are stored. Even entire pipelines can be inlined.

To inline an asset file's content, use the option `{'loading': 'inline'}` with `addCss()` or `addJs()`. You can also inline all assets when rendering a group with `js()` or `css()`, which provide the same option.

Example of using `system.yaml` to define asset collections named according to asset loading requirements, with `app.css` being a [Sass](http://sass-lang.com/)-generated CSS file:

```yaml
assets:
  collections:
    css-inline:
      - 'http://fonts.googleapis.com/css?family=Ubuntu:400|Open+Sans:400,400i,700'
      - 'theme://css-compiled/app.css'
    js-inline:
      - 'https://use.fontawesome.com/<embedcode>.js'
    js-async:
      - 'theme://foundation/dist/assets/js/app.js'
      - 'theme://js/header-display.js'
```

The template inserts each collection into its corresponding group, namely `head` and `head-link` for CSS, `head` and `head-async` for JS. The default group `head` is used for inline loading in each case:

```twig
{% block stylesheets %}
    {% do assets.addCss('css-inline') %}
    {% do assets.addCss('css-link', {'group': 'head-link'}) %}
{% endblock %}
{{ assets.css('head-link')|raw }}
{{ assets.css('head', {'loading': 'inline'})|raw }}
{% block javascripts %}
    {% do assets.addJs('js-inline') %}
    {% do assets.addJs('js-async', {'group': 'head-async'}) %}
{% endblock %}
{{ assets.js('head-async', {'loading': 'async'})|raw }}
{{ assets.js('head', {'loading': 'inline'})|raw }}
```


## Static Assets

Sometimes there is a need to reference assets without using the Asset Manager.  There is a `url()` helper method available to achieve this.  An example of this could be if you wanted to reference an image from the theme. The syntax for this is:

```twig
<img src="{{ url("theme://" ~ widget.image)|e }}" alt="{{ widget.text|e }}" />
```

The `url()` method takes an optional second parameter of `true` or `false` to enable the URL to include the schema and domain. By default this value is assumed `false` resulting in just the relative URL.  For example:

```twig
<script src="{{ url('theme://some/extra.css', true)|e }}"></script>
```

