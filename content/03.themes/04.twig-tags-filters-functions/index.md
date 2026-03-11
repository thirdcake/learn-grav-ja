---
title: Twigのタグ・フィルタ・関数
layout: ../../../layouts/Default.astro
lastmod: '2025-08-03'
description: 'Twig で使えるタグ・フィルタ・関数についての概要を解説します。Twig オリジナルのものとは別に、 Grav で追加しているものもあります。'
---

Twig でもすでに、 [フィルタ、関数、タグ](https://twig.symfony.com/doc/1.x/#reference) の幅広いリストを提供していますが、 Grav でもテーマ処理を容易にする便利な追加機能を提供します。

> [!Info]  
> 独自のカスタム Twig フィルタを開発する方法については、 **レシピ集** チャプターにある **Twig レシピ** の [カスタム Twig フィルタ/関数](../../10.cookbook/02.twig-recipes/#custom-twig-filter-function) の具体例を参照してください。

## Tags

タグは、 Twig の高度な機能を提供します。  
組み込みのタグには、 `include`、 `block`、 `for`、 `if` などたくさんあります。  
タグは、 Twig 上では、 `{% tagname %}` という構文で識別されます。  
また、多くのタグは `{% endtagname %}` により閉じます。

Grav では、いくつかの便利なカスタムタグを提供しています。  
`cache`、 `markdown`、 `script`、 `style`、 `switch` などです。

[カスタムタグの説明](01.tags/)

## Filters

Twig のフィルタは、パイプ記号（`|`）左にある変数について、機能を適用させることができます。  
これは、テキストや変数を操作したいときに、特に便利です。  
フィルタへの最初の引数は常に左側の項目ですが、それ以降の引数はカッコで囲んで渡せます。  
フィルタには、コンテキストや環境を認識する機能など、いくつかの特別な機能があります。

Twig に組み込みのフィルタには、`date`、 `escape`、 `join`、 `lower`、 `slice` など、たくさんあります。  
たとえば：

```twig
{% set foo = "one,two,three,four,five"|split(',', 3) %}
```

Grav では、いくつかのカスタムフィルタを提供します。  
`hyphenize`, `nicetime`, `starts_with`, `contains`, `base64_decode`, などたくさんあります。

[カスタムフィルタの説明](02.filters/)

<h2 id="functions">関数</h2>

Twig の関数は、 Twig に機能を実装するもうひとつの方法です。  
フィルタに似ていますが、 `|` を通じて変数に働きかけるのではなく、関数を直接呼び出して、関数名の後のカッコに、対象の属性を渡すことではたらきます。  
しばしば、 Grav は同じロジックをフィルタと関数で提供し、ユーザが好みの方法を選べるようにしています。

Twig 組み込みの関数には、 `block`, `dump`, `parent`, `random`, `range`, などがあります。  
たとえば：

```twig
{{ random(['apple', 'orange', 'citrus']) }}
```

Grav が提供する、カスタム関数には、`authorize`, `debug`, `evaluate`, `regex_filter`, `media` などたくさんあります。

[カスタム関数の説明](03.functions/)

