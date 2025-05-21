---
title: カスタムタグ
layout: ../../../../layouts/Default.astro
lastmod: '2025-04-18'
---
Twigにはすでに、優秀なタグがありますが、Gravではさらに、わたしたちが便利だと気づいたカスタムタグを、たくさん追加しています。

### `markdown`

markdownタグは、Twigテンプレートにマークダウンを組み込むための新しく強力な方法です。変数を使って、`|markdown` フィルタを変数にレンダリングすることは、これまでにも可能でしたが、`{% markdown %}` 構文では、シンプルなマークダウンテキストのブロックを作ることができます。

```twig
{% markdown %}
This is **bold** and this _underlined_

1. This is a bullet list
2. This is another item in that same list
{% endmarkdown %}
```
### `script`

scriptタグは、`{% do assets...%}` による方法に比べて、Twigを読みやすくするために便利なタグです。純粋に、別の書き方になります。
The Script tag is really a convenience tag that keeps your Twig more readable compared to the usual `{% do assets...%}` approach.  It's purely an alternative way of writing things.

<h4 id="script-file">スクリプトファイル</h4>

```twig
{% script 'theme://js/something.js' at 'bottom' priority: 20 with { defer: true, async: true } %}
```

Grav 1.7.28 からは、module にも対応しました：

```twig
{% script module 'theme://js/module.mjs' %}
```


<h4 id="inline-script">インラインのスクリプト</h4>

```twig
{% script at 'bottom' priority: 20 %}
    alert('Warning!');
{% endscript %}
```

### `style`

<h4 id="css-file">CSS ファイル</h4>

```twig
{% style 'theme://css/foo.css' priority: 20 %}
```

<h4 id="inline-css">インラインの CSS</h4>

```twig
{% style priority: 20 with { media: 'screen' } %}
    a { color: red; }
{% endstyle %}
```

### `link`

```twig
{% link icon 'theme://images/favicon.png' priority: 20 with { type: 'image/png' } %}
{% link modulepreload 'plugin://grav-plugin/build/js/vendor.js' %}
```

### `switch`

ほとんどのプログラミング言語において、`switch` 文は、`if else` 文をよりクリーンで読みやすくしてくれる一般的な方法です。また、また、多少速くなるかもしれません。twigの機能では忘れられているこのswitch文を、Gravではシンプルな方法で提供します。

```twig
{% switch type %}
  {% case 'foo' %}
     {{ my_data.foo }}
  {% case 'bar' %}
     {{ my_data.bar }}
  {% default %}
     {{ my_data.default }}
{% endswitch %}
```

### `deferred`

かつての block では、一度そのblockがレンダリングされてしまったら、再度操作することはできませんでした。`{% block scripts %}` を例に取ると、このブロックには、読み込みたい JavaScript を保持できます。もし、子テンプレートから、このブロックを持つベーステンプレートへ拡張するならば、このブロックにカスタムのJavaScriptファイルを追加できます。しかしながら、このページから includeする partial （部分的な）テンプレートの場合、このブロックにアクセスし、操作できません。

deffered属性は、 [Deferred Extension](https://github.com/rybakit/twig-deferred-extension) により提供されているものですが、ブロックにdeferred 属性を付けると、あらゆるテンプレートからこのブロックを定義でき、ほかのものよりも遅れてレンダリングされます。このことにより、`{% do assets.addJs() %}` をどこでも呼び出すことで、参照したいJavaScriptを追加することができます。そしてレンダリングは遅れるので、すべてのアセットを追加できます。追加のタイミングを図る必要は、もうありません。

```twig
{% block myblock deferred %}
    This will be rendered after everything else.
{% endblock %}
```

deffered したブロックに `{{ parent() }}` を使うことで、親ブロックのコンテンツと合体させることもできます。これは、CSSやJavaScript ファイルを追加したいときに、とくに便利です。

```twig
{% block stylesheets %}
    <!-- Additional css library -->
    {% do assets.addCss('theme://libraries/leaflet/dist/leaflet.css') %}
    {{ parent() }}
{% endblock %}
```

### `throw`

手動で例外を投げたい状況がありえます。そのためのタグです。

```twig
{% throw 404 'Not Found' %}
```

<h3 id="try-catch">`try` & `catch`</h3>

Twigテンプレート上で、PHP-スタイルのエラー制御ができると便利です。そのために、`try/catch` タグを提供します。

```twig
{% try %}
   <li>{{ user.get('name') }}</li>
{% catch %}
   User Error: {{ e.message }}
{% endcatch %}
```

### `render`

Flex Objects は、Grav内の要素に、ゆっくりと浸透しています。これらは、Twigテンプレート構造と関連する、自己を認識したオブジェクトなので、Flex Objectには、レンダリングの方法まで含まれています。これらを使うために、新しい `render` タグを実装しました。このタグは、オプションのレイアウトを受け取り、オブジェクトがどのレイアウトでレンダリングされるかを制御します。

```twig
{% render collection layout: 'list' %}
{% render object layout: 'default' with { variable: 'value' } %}
```

> [!訳注]  
> Flex Objects のためのタグのようなので、詳しくはFlex Objectsの章をご覧ください。

### `cache`

ときどき、ページの部分的なキャッシュが必要になることがあります。レンダリングに時間がかかるようなときです。そのようなときは、`cache` タグを使ってください。

```twig
{% cache 600 %}
  {{ some_complex_work() }}
{% endcache %}
```

この例での `600` とは、キャッシュが生きる秒数のことで、設定するかはオプションです。もし秒数を渡さなかった場合、デフォルトの秒数が使われます。

