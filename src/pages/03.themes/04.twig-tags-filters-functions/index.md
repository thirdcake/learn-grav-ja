---
title: Twigのタグ・フィルタ・関数
layout: ../../../layouts/Default.astro
lastmod: '2025-04-18'
---
Twigでもすでに、[フィルタ、関数、タグ](https://twig.symfony.com/doc/1.x/#reference) の幅広いリストを提供していますが、Gravでもテーマ処理を容易にする便利な追加機能を提供します。

> [!Info]  
> For information about developing your own custom Twig Filters, check out the [Custom Twig Filter/Function](../../10.cookbook/02.twig-recipes/#custom-twig-filter-function) example in the **Twig Recipes** section of the **Cookbook** chapter.

## Tags

タグは、Twigの高度な機能を提供します。組み込みのタグには、`include`、 `block`、 `for`、 `if` などたくさんあります。タグは、Twig上では、`{% tagname %}` という構文で識別されます。また、多くのタグは `{% endtagname %}` により閉じます。

Gravでは、いくつかの便利なカスタムタグを提供しています。`cache`、 `markdown`、 `script`、 `style`、 `switch` などです。

[カスタムタグの説明](01.tags/)

## Filters

Twigのフィルタは、パイプ記号（`|`）左にある変数について、機能を適用させることができます。これは、テキストや変数を操作したいときに、特に便利です。フィルタへの最初の引数は常に左側の項目ですが、それ以降の引数はカッコで囲んで渡せます。フィルタには、コンテキストや環境を認識する機能など、いくつかの特別な機能があります。

Twigに組み込みのフィルタには、`date`、 `escape`、 `join`、 `lower`、 `slice` など、たくさんあります。たとえば：

```twig
{% set foo = "one,two,three,four,five"|split(',', 3) %}
```

Gravでは、いくつかのカスタムフィルタを提供します。 `hyphenize`, `nicetime`, `starts_with`, `contains`, `base64_decode`, などたくさんあります。

[カスタムフィルタの説明](02.filters/)

<h2 id="functions">関数</h2>

Twigの関数は、Twigに機能を実装するもうひとつの方法です。フィルタに似ていますが、`|` を通じて変数に働きかけるのではなく、関数を直接呼び出して、関数名の後のカッコに、対象の属性を渡すことではたらきます。しばしば、Gravは同じロジックをフィルタと関数で提供し、ユーザが好みの方法を選べるようにしています。

Twig組み込みの関数には、 `block`, `dump`, `parent`, `random`, `range`, などがあります。たとえば：

```twig
{{ random(['apple', 'orange', 'citrus']) }}
```

Gravが提供する、カスタム関数には、`authorize`, `debug`, `evaluate`, `regex_filter`, `media` などたくさんあります。

[カスタム関数の説明](03.functions/)


