---
title: アセットマネージャー
layout: ../../../layouts/Default.astro
lastmod: '2025-07-29'
description: 'Grav では、CSS や JS などを、アセットマネージャーで動的に管理できます。ファイルの指定方法やレンダリング方法、便利な使い方を解説します。'
---

Grav 1.6 で、**アセットマネージャー** が完全に書き直されました。  
テーマで、**CSS** や **JavaScript** のアセットをより柔軟なメカニズムで管理できるようになりました。  
アセットマネージャーの主な目的は、テーマやプラグインでアセットを追加する処理をシンプルにし、優先順位などの強化された機能を提供することです。  
また、アセットを **ミニファイ** し、**圧縮** し、**インライン化** する **アセットパイプライン** を提供し、ブラウザのリクエストを減らし、アセットの全体サイズも小さくします。

以前よりも、より柔軟に、より信頼できるようになりました。  
さらに、コードもより 'クリーン' になり、読みやすくなりました。  
アセットマネージャーは、 Grav の処理中に利用可能で、プラグインのイベントフックでも利用でき、さらに Twig の呼び出しでテーマから直接利用できます。

> [!Note]  
> **技術的詳細** ：主要なアセットの class は大幅にシンプルになり、小さくなりました。ロジックの多くは3つの trait に分割されました。 _testing trait_ は、主に test suite で使われる関数を含みます。 _utils trait_ は、通常のアセットタイプ（JS、インラインJS、CSS、インラインCSS）と、ミニファイや圧縮を行うアセットパイプラインで共有されるメソッドを含みます。最後に、 _legacy trait_ は、ショートカットや回避策のメソッドを含みますが、一般的には、今後は使わない方が良いです。

> [!Tip]  
> アセットマネージャーは、Grav 1.6 以前のバージョンの構文と完全に下位互換がありますが、このドキュメントでは、これ以降、新しい `優先構文` を説明します。

<h2 id="configuration">設定</h2>

アセットマネージャーには、シンプルな設定オプションがあります。  
デフォルト値は、 system フォルダの `system.yaml` ファイルにあります。  
`user/config/system.yaml` ファイルで、上書きしてください。

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

下のダイアグラムに示すように、ポジションを制御する多数の層に分かれています。  
スコープの順に並べると、次のようになります：

* **Group** - アセットを `head` （デフォルト）と、 `bottom` にグループ分けします。
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

デフォルトでは、`CSS` と、 `JS` 、 `JS Module` は、`pipeline` ポジションに置かれます。  
一方、 `InlineCSS` と、 `InlineJS` 、 `Inline JS Module` は、`after` ポジションになります。  
しかしこの設定は変更可能です。  
どんなアセットを、どんなポジションに設定することもできます。

<h2 id="assets-in-themes">テーマ中のアセット</h2>

<h3 id="overview">概要</h3>

CSS アセットを追加したい時、普通は、`assets.addCss()` や、`assets.addInlineCss()` を呼び出して、 `assets.css()` によりレンダリングすると思います。  
優先度や、パイプライン化、インライン化をしたい場合、アセットの追加時にアセットごとに指定することもできますし、アセットグループに対してレンダリング時にすることもできます。

JS アセットも似ていて、`assets.addJs()` や、 `assets/addInlineJs()` を呼び出します。  
一般的な `assets.add()` メソッドもあり、アセットのタイプを推測しますが、特定のメソッドを呼び出すことをおすすめします。

バージョン 1.7.27 から、アセットマネージャーは、JS Modules にも対応します。  
これらのアセットは、JSアセットと同じように機能しますが、`type="module"` となり、`assets.addJsModule()` や、 `assets.addInlineJsModule()` で呼び出します。 
`assets.add()` メソッドは、拡張子が `.mjs` のときのみ、JS Module と認識します。  
しかし、`.js` ファイルはすべて、普通のJSファイルとして扱います。

> [!Note]  
> JS Modulesについてもっと学びたいとき：
> * [https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Modules](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Modules)
> * [https://v8.dev/features/modules](https://v8.dev/features/modules)
> * [https://javascript.info/modules-intro](https://javascript.info/modules-intro)

アセットマネージャーは、次のようなものにも対応します：

* 名前付きグループを異なる場所や異なるオプション設定でレンダリングするために、そのグループにアセットを追加すること
* `assets.add*()` 呼び出しで追加できる名前付きアセットのコレクションを設定すること

<h3 id="example">具体例</h3>

テーマ内で CSS ファイルを追加できる方法の具体例は、 Grav に最初からバンドルされているデフォルトの **quark** テーマにあります。  
もし [`templates/partials/base.html.twig`](https://github.com/getgrav/grav-theme-quark/blob/develop/templates/partials/base.html.twig) 部分を見れば、次のようなことが書いてあるでしょう：

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

`block stylesheets` という twig タグは、これを extend するテンプレートで、入れ替えられたり、追加されたりする場所を定義しているだけです。  
ブロック内では、たくさんの `do assets.addCss()` が呼び出されていることがわかります。

この `{% do %}` タグは、それ自体が [Twig に組み込まれたタグ](https://twig.symfony.com/doc/1.x/tags/do.html) であり、これにより、出力を生成することなく、変数を操作できます。

`addCss()` メソッドは、 CSS アセットを アセットマネージャーに追加するメソッドです。  
優先度を指定しない場合、アセットが追加される優先度は、それらがレンダリングされた順番になります。  
Grav が現在のテーマの相対パスを決定しやすくするために **PHP ストリームラッパー** である `theme://` が使われていることにお気づきでしょう。

> [!Info]  
> `assets.addJs('jquery', 101)` は、グローバルのアセット設定で定義された `jquery` のコレクションを含みます。ここでの `101` というオプションのパラメータは、確実に最初にレンダリングされるように、極めて高い優先度を設定されています。パラメータが無かった場合のデフォルトの優先度は `10` です。より柔軟な書き方は、 `assets.addJs('jquery', {priority: 101})` です。これにより、優先順位とともに他のパラメータも追加できます。

`assets.css()|raw` を呼び出すと、CSS アセットを HTML タグとしてレンダリングします。  
このメソッドにはパラメータが適用されていないので、このグループはデフォルトで `head` に設定されます。  
これがどのように `assets deferred` ブロックに囲まれているか、注意して見てください。  
これは Grav 1.6 の新しい機能で、ページの後から読み込まれた（もしくは、実際は後でなくても任意の場所で読み込まれた）他のテンプレートからアセットを追加できるようになります。  
そして、必要であればこの `head` にレンダリングされることが保証されます。

テーマ出力の最後にある `bottom` ブロックは、 `bottom` グループに配置された JavaScript をレンダリングします。

<h2 id="adding-assets">アセットを追加</h2>

#### add(asset, [options])

add メソッドは、ファイル拡張子をもとにアセットにマッチするように最善を尽くします。  
これは便利なメソッドではあるものの、CSS や、Link、JS や JS モジュールの直接的なメソッドを呼び出す方が良いでしょう。  
各詳細は、直接的なメソッドの説明をお読みください。

> [!Info]  
> 複数のオプションを渡す際には、オプションの配列を渡すのが望ましいです。しかし、先ほどの `jquery` の例のように、**優先度** だけ設定したい場合は、ショートカットを使い、 **第2引数** として整数を渡すことができます。

#### addCss(asset, [options])

このメソッドは、アセットを CSS アセットのリストに追加します。  
優先度を指定しなかった場合のデフォルト値は、 10 です。  
優先度が大きい数字のアセットは、小さい数字のアセットよりも先に表示されます。  
`pipeline` オプションは、このアセットが combination/minify パイプラインに含まれるべきかどうかを制御します。  
パイプラインに含まれないなら、 `loading` オプションは、そのアセットが外部のスタイルシートへのリンクと同様にレンダリングされるべきか、それともインラインのスタイルタグ内に直接書き込まれるべきコンテンツかをコントロールします。

#### addLink($asset, [options])

このメソッドは、アセットを `<link>` タグ形式の Link アセットのリストに追加します。  
これは、CSS ファイルではない link タグをサイト内の任意の場所から head タグに追加するときに便利です。  
優先度は、指定されなければデフォルトで 10 です。  
優先度の大きい数字のアセットは、小さい数字のアセットよりも先に表示されます。

他のアセット追加メソッドとは異なり、 `link()` はパイプラインも `inline` もサポートしません。

#### addInlineCss(css, [options])

インラインの style タグ内に CSS 文字列を追加します。  
初期化や、動的処理の際に便利です。  
標準的なアセットファイルコンテンツをインライン化するには、 `addCss()` と `css()` メソッドの `{'loading': 'inline'}` オプションを参照してください。

#### addJs(asset, [options])

このメソッドは、 JavaScript アセットのリストにアセットを追加します。  
優先度はデフォルトで 10 です。  
大きい数字のアセットが、小さい数字のアセットよりも先に読み込まれます。  
`pipeline` オプションは、このアセットが 結合/ミニファイ するパイプラインに含まれるべきかどうかを制御します。  
パイプラインされない場合、 `loading` オプションで、アセットが外部のスクリプトファイルへのリンクとしてレンダリングされるべきか、インラインの script タグ内に書き込まれるべきコンテンツかを制御します。

#### addInlineJs(javascript, [options])

インラインの script タグ内に、JavaScript 文字列を追加します。  
初期化や、あらゆる動的処理に便利です。  
標準的なアセットファイルのコンテンツに書き込むため、 `addJs()` メソッドや、 `js()` メソッドの `{ 'loading': 'inline' }` オプションを参照してください。

#### addJsModule(asset, [options])

このメソッドは、 JavaScript モジュールアセットのリストに、アセットを追加します。  
優先度はデフォルトで 10 です。  
大きい数字のアセットが、小さい数字のアセットよりも先に読み込まれます。  
`pipeline` オプションは、このアセットが 結合/ミニファイ するパイプラインに含まれるべきかどうかを制御します。  
パイプラインされない場合、 `loading` オプションで、アセットが外部のスクリプトファイルへのリンクとしてレンダリングされるべきか、インラインの script タグ内に書き込まれるべきコンテンツかを制御します。

#### addInlineJsModule(javascript, [options])

インラインのモジュール script タグ内に、 JavaScript 文字列を追加します。  
標準的なアセットファイルのコンテンツをインライン化するために、 `addJsModule()` メソッドや、 `Js()` メソッドの `{ 'loading': 'inline' }` オプションを参照してください。

#### registerCollection(name, array)

CSS と JavaScript アセットの配列を名前付きで登録でき、あとで `add()` メソッドにより使うことができます。  
jQuery や Bootstrap のように、複数のテーマやプラグインで使われるコレクションを登録したい場合に、特に便利です。

<h2 id="options">オプション</h2>

必要に応じて、アセットオプションの配列を渡すことができます。  
コアのオプションは、次のとおりです：

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

オプションの配列には、他に好きなものを何でも渡すことができます。  
もしそれらが標準的な型でない場合は、たとえば `{id: 'custom-id'}` が HTML タグ中の `id="custom-id"` としてレンダリングされるように、属性としてレンダリングされます。  
このことは、 `{type: 'application/ld+json'}` を使って、 `addInlineJs()` を経由した json-ld のような構造化データを含んでも使われます。

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

アセットマネージャーのグループ（デフォルトでは `head` ）に追加されている CSS アセットをレンダリングします。  
[options] に入るのは：

* **loading**: `inline` にすると、このグループ内 **すべての** アセットがインライン出力されます。（デフォルトの挙動は、各アセットごとにaddXxx したときの `position` オプションに従います）

* **_リンク属性_**, 次の例を見てください (デフォルト: `{'type': 'text/css', 'rel': 'stylesheet'}`). `inline` がこのグループのレンダリングオプションとして使われて **いない** ときだけ影響があります

`include_link` が有効になっているとき（デフォルト）は、 `css()` が `link()` も一緒に呼びます。

もし config 設定でパイプラインが **off** になっていた場合、グループのアセットは個別にレンダリングされます。アセットの優先度（高低）の順番で、アセットが追加された順に従います。

もし config 設定でパイプラインが **on** になっていた場合、ポジションにパイプラインを設定したアセットは、アセットが追加された順に結合され、パイプライン config 設定に従って処理されます。

各アセットは、スタイルシートの link タグもしくはインラインのどちらかでレンダリングされます。  
アセットの `loading` オプションと `{'loading': 'inline'}` がこのグループのレンダリングに使われているかどうでによります。  
`addInlineCss()` で追加された CSS  は、デフォルトでは `after` ポジションでレンダリングされますが、パイプライン出力の前に `position: before` と一緒にレンダリングする設定をすることもできます。

> [!訳注]  
> 上記の説明を読んだだけで、分かるかどうか分かりませんが（私は混乱しました）、 `assets.css()` で出力されるものがあまりにもたくさんあるので、混乱するのだと思います。機能を詰め込みすぎです。困った場合は、とりあえず、次の各パターンを、実際に試してください。 `assets.addCss()` に、 `position` をそれぞれ `before`, `pipeline`, `after` にしたもの、 `addLink()` に CSS 以外の link タグを設定したもの、 `assets.css()` に `loading` を `inline` にしたものと、しないもの、 `include_link` を `true` と `false` にしたもの。各パターンを作って、実際に試してみないと、確実に混乱します。

#### link(group, [options])

Link アセットのレンダリングは、アセットマネージャーのグループ（デフォルトでは `head`）に追加できます。  
`head` と異なるグループはおすすめしません。ブラウザ側は、 `head` にそのタグが見つかると期待し、処理するところだからです。

アセットを追加するその他のメソッドと違い、 `link()` はパイプラインをサポートしませんし、 `inline` もサポートしません。

#### js(group, [options], include_js_module = true)

アセットマネージャーグループ（デフォルトは `head` ）に追加してある JavaScript アセットをレンダリングします。  
オプションは、次の通りです。

* **loading**: もし `inline` にすると、このグループのアセット **すべて** がインラインで出力されます。（デフォルトでは、各アセットの `position` オプションによります）

* **_script attributes_**, 次のようにしてください (デフォルトでは： `{'type': 'text/javascript'}`) 。 このグループのレンダリングオプションで `inline` では **ない** ときのみ影響があります。

`include_js_module` が有効であるとき（デフォルトの場合）、 `js()` の呼び出しと同時に `jsModule()` が呼び出されます。

パイプラインが設定で **無効** になっている場合、そのグループのアセットは個別にレンダリングされます。優先度の高低の順で、それからアセットが追加された順です。

パイプラインが設定で **有効** 担っている場合、パイプラインポジションのアセットは、アセットが追加された順に結合され、パイプラインの config 設定の通りに処理されます。  
結合されたパイプラインの結果は、 `js_pipeline_before_excludes` の設定次第で、パイプラインされていないアセットの前か後ろにレンダリングされます。

各アセットは、スクリプトリンクとして、あるいはインラインとして、いずれかの方法でレンダリングされます。アセットの `loading` オプションと `{'loading': 'inline'}` がこのグループのレンダリングに使われているか次第です。  
JS パイプラインをインライン出力する唯一の方法は、 `js()` メソッドで loading オプションとしてインラインを利用することであることに注意してください。  
`addInlineJs()` で追加された JS は、デフォルトでは `after` ポジションにレンダリングされます。しかし、 `position: before` にすれば、パイプライン出力の前にレンダリングする設定は可能です。

#### jsModule(group, [options])

`js()` によるレンダリングと全く同じように、 JavaScript モジュール用として機能します。  
デフォルトのスクリプトタイプ属性は、 `type="module"` です。 `inline` レンダリングのときも同様です。

#### all(group, [options])

次の順に、上記すべてのアセットをレンダリングします： `css()`, `link()`, `js()`, `jsModule()`

この方法は、（通常は `base.html.twig` の） メイン twig ファイルに defer 属性のアセットを含める場合に推奨される方法です。

```twig
{% block assets deferred %}
  {{ assets.all()|raw }}
{% endblock %}
```

<h2 id="named-assets-and-collections">名前付きアセットとコレクション</h2>

Grav には、 **named assets** という強力な機能があります。これは、 CSS と JavaScript のコレクションを名前付きで登録できるというものです。  
これらのアセットを登録した名前によってアセットマネージャーに簡単に **追加** できます。  
Grav では、**jQuery** を事前設定していますが、 `system.yaml` ファイルで、カスタムのコレクションを定義できる機能があり、あらゆるテーマやプラグインで使えます：

```yaml
assets:
  collections:
    jquery: system://assets/jquery/jquery-2.1.3.min.js
    bootstrap:
        - https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css
        - https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css
        - https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js
```

プログラミングによって、 `registerCollection()` メソッドを使うこともできます。

```yaml
$assets = $this->grav['assets'];
$bootstrapper_bits = [https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css,
                      https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css,
                      https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js];
$assets->registerCollection('bootstrap', $bootstrap_bits);
$assets->add('bootstrap', 100);
```

このアクションの具体例として、 [**bootstrapper** プラグイン](https://github.com/getgrav/grav-plugin-bootstrapper/blob/develop/bootstrapper.php#L51-L71) があります。

<h5 id="collections-with-attributes">属性のコレクション</h5>

コレクションの特定のアイテムに、特定のもしくは通常と異なるカスタム属性が欲しいときがあるかもしれません。たとえば、リモートの CDN からアセットを読み込む場合で、インテグリティチェック（SRI）をしたいような場合です。  
キーをアセットのロケーションとし、値を追加属性のリストとする配列として、名前付きアセットの値を扱うことにより、これが可能になります。  
具体例：

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

twig で JS を `{% do assets.addJs('jquery_and_ui', { defer: true }) %}` によって追加した後、 アセットは、次のように読み込まれます：

```html
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" defer="1" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" defer="1" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="></script>
```

`defer` は、 twig レベルで定義されており、コレクションのすべてのアセットに適用されることに注意してください。  
なぜなら、 Grav は、 twig と yaml 定義の両方から属性をマージしますが、 yaml 定義の方を優先するからです。

もし、 `jquery-ui.min.js` アセットが `defer: null` 属性を含んでいる場合、 twig の `defer: 1` よりも優先され、defer はレンダリングされなくなります。

<h2 id="grouped-assets">グルーピングしたアセット</h2>

アセットマネージャーによって、アセットを追加する際に、オプションの一部として `group` オプションを渡せます。  
CSS よりも、特に JavaScript で便利な機能です。というのも、 JS ファイルもしくはインラインの JS レンダリングを、一部はヘッダー内で行い、別の一部をページの下部で行う必要があるからです。

この機能を利用するため、アセット追加時に、オプション構文を使って、グループを指定しなければいけません：

```twig
{% do assets.addJs('theme://js/example.js', {'priority':102, 'group':'bottom'}) %}
```

たとえば bottom グループをレンダリングするためには、以下のようにテーマ内で追加する必要があります：

```twig
{{ assets.js('bottom')|raw }}
```

アセットにグループが定義されなかった場合は、 `head` がデフォルトのグループになります。  
レンダリング時にグループの指定がなければ、 `head` グループがレンダリングされます。  
これにより、新機能が、既存のテーマと 100% 後方互換性を保証してくれます。

CSS ファイルについても同様です：

```twig
{% do assets.addCss('theme://css/ie8.css', {'group':'ie'}) %}
```

そしてレンダリング時は：

```twig
{{ assets.css('ie')|raw }}
```

<h2 id="change-attribute-of-the-rendered-css-js-assets">レンダリングされた CSS/JS アセットの属性変更</h2>

CSS は、デフォルトでは、 `rel="stylesheet"` 属性と `type="text/css"` が追加され、 JS には `type="text/javascript"` が追加されます。

デフォルトの仕様を変更したい場合、もしくは新しい属性を追加したい場合は、新しいアセットのグループを作成する必要があります。そして、その属性とともにレンダリングするよう Grav に伝えます。

あるアセットグループに、 `rel` 属性を編集する具体例です：

```twig
{% do assets.addCSS('theme://whatever.css', {'group':'my-alternate-group'}) %}
...
{{ assets.css('my-alternate-group', {'rel': 'alternate'})|raw }}
```

<h2 id="inlining-assets">インラインアセット</h2>

インライン化すると、 HTML ドキュメントに直接 CSS （と JS ）のコードを書き込めるので、ブラウザが外部のスタイルシートやスクリプトをダウンロードする時間を省いて、すぐにページをレンダリングできるようになります。  
これにより、ユーザーへのサイトパフォーマンスが向上でき、モバイルのネットワーク環境では特に改善されます。  
詳しくは、 [CSS 提供の最適化に関するこの記事](https://developers.google.com/speed/docs/insights/OptimizeCSSDelivery) を参照してください。

しかしながら、 CSS や JavaScirpt コードを直接ページテンプレートに挿入することは、常に可能とは限りません。たとえば、 Sass コンパイルが必要な CSS を使うような場合です。  
分離された CSS と JS アセットファイルは、メンテナンスも簡単にしてくれます。  
アセットマネージャーのインライン化機能を使って、アセットの保存方法を変えることなく、表示スピードの最適化ができるようになります。  
パイプライン全体さえ、インライン化できます。

あるアセットファイルのコンテンツをインライン化するため、 `{'loading': 'inline'}` オプションを `addCss()` もしくは `addJs()` で利用します。  
同じオプションを `js()` や `css()` に適用すれば、グループのレンダリング時にすべてのアセットがインライン化することも可能です。

次の例は、 `system.yaml` を使って、名前付きアセットコレクションを定義し、 [Sass](http://sass-lang.com/) 生成の CSS ファイルである `app.css` とともに読み込むようにしたものです：

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

テンプレートは、各コレクションを対応するグループに挿入します。 `head` と `head-link` を CSS に、 `head` と `head-async` を JS に。  
デフォルトグループの `head` は、各ケースのインライン読み込みに使われます：

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

<h2 id="static-assets">静的アセット</h2>

アセットマネージャーを使わずにアセットを参照する必要がある場合もあります。  
`url()` ヘルパーメソッドを使って、これを解決できます。  
この例では、テーマから画像を参照したい場合のものです。  
このための構文は、次の通りです：

```twig
<img src="{{ url("theme://" ~ widget.image)|e }}" alt="{{ widget.text|e }}" />
```

`url()` メソッドは、第2引数に `true` か `false` を使い、 URL にスキーマとドメインを含めることができます。  
デフォルトでは、この値は `false` となり、相対 URL が返ります。  
具体例：

```twig
<script src="{{ url('theme://some/extra.css', true)|e }}"></script>
```

