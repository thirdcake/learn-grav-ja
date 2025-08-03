---
title: カスタムフィルタ
layout: ../../../../layouts/Default.astro
lastmod: '2025-08-03'
description: 'Grav で独自に追加した Twig のカスタムフィルタについて解説します。'
---

> [!訳注]  
> このページの内容は、 Twig のフィルタを動的に実行している部分があり、静的サイトでは再現できません。実行結果は、 [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters) をご確認ください。

Twig のフィルタは、 `|` という文字と、それに続くフィルタ名を使って、 Twig 変数に適用されます。  
Twig 関数と同じように、カッコを使って引数を渡すことができます。

<h3 id="absolute-url"><code>absolute_url</code></h3>

相対パスを使った `src` 属性や `href` 属性を持つ HTML 部分に使われます。  
相対パスを、ホスト名を含む絶対 URL 表記の文字列に変換します。

`'<img src="/some/path/to/image.jpg" />'|absolute_url` -&gt; （結果は [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters#absolute-url) へ）

<h3 id="array-unique"><code>array_unique</code></h3>

PHP の `array_unique()` 関数と同様のもので、配列から重複するものを取り除きます。

`['foo', 'bar', 'foo', 'baz']|array_unique`  -&gt; （結果は [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters#array-unique) へ）

<h3 id="base32-encode"><code>base32_encode</code></h3>

変数をbase32エンコードします。

`'some variable here'|base32_encode`  -&gt; （結果は [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters#base32-encode) へ）

<h3 id="base32-decode"><code>base32_decode</code></h3>

変数をbase32デコードします。

`'ONXW2ZJAOZQXE2LBMJWGKIDIMVZGK'|base32_decode`  -&gt; （結果は [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters#base32-decode) へ）

<h3 id="base64-encode"><code>base64_encode</code></h3>

変数をbase64エンコードします。

`'some variable here'|base64_encode`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

<h3 id="base64-decode"><code>base64_decode</code></h3>

変数をbase64デコードします。

`'c29tZSB2YXJpYWJsZSBoZXJl'|base64_decode`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

### `basename`

パスのbasenameを返します。

`'/etc/sudoers.d'|basename`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

### `camelize`

文字列をキャメルケース表記に変換します。

`'send_email'|camelize`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

<h3 id="chunk-split"><code>chunk_split</code></h3>

文字列をあるサイズで小さく区切ります。

`'ONXW2ZJAOZQXE2LBMJWGKIDIMVZGKA'|chunk_split(6, '-')`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

### `contains`

ある文字列に別の文字列が含まれるか判断します。

`'some string with things in it'|contains('things')`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

<h4 id="casting-values">値をキャストする</h4>

PHP7から、型チェックが厳しくなりました。これにより、間違った型の値には例外が投げられます。これを避けるため、値を適切にメソッドに渡すために、フィルタを使ってください。

### `string`

文字列型にするには、 `|string` を使ってください。

### `int`

整数型にするには、 `|int` を使ってください。

### `bool`

真偽値型にするには、 `|bool` を使ってください。

### `float`

実数型にするには、 `|float` を使ってください。

### `array`

配列型にするには、 `|array` を使ってください。

### `defined`

ときには、その変数が定義済みかどうかチェックしたいときがあります。未定義の場合、デフォルト値を渡したい場合です。たとえば：

`set header_image_width  = page.header.header_image_width|defined(900)`

この例では、ページのフロントマターで、 `header_image_width` 変数が未定義だった場合に、900を入れます。

### `dirname`

パスのディレクトリ名を返します。

`'/etc/sudoers.d'|dirname`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）


<h3 id="ends-with"><code>ends_with</code></h3>

ニードルとヘイスタックを使って、ヘイスタックがニードルで終わっているか判断します。また、ニードルを配列で渡し、ヘイスタックがいずれかのニードルで終わっていれば `true` を返します。

`'the quick brown fox'|ends_with('fox')`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

### `fieldName`

フィールド名をドット表記から配列表記に変えます。

`'field.name'|fieldName`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）


<h3 id="get-type"><code>get_type</code></h3>

変数の型を返します。

`page|get_type`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

### `humanize`

文字列を「人間に読みやすい」表記に変えます。

`'something_text_to_read'|humanize`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

### `hyphenize`

ハイフン付き文字列に変えます。

`'Something Text to Read'|hyphenize`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

<h3 id="json-decode"><code>json_decode</code></h3>

JSONをデコードします。

`array|json_decode` 

```twig
{% set array = '{"first_name": "Guido", "last_name":"Rossum"}'|json_decode %}
{{ print_r(array) }}
```

（結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

### `ksort`

配列をキーでソートします。

`array|ksort`

```twig
{% set items = {'orange':1, 'apple':2, 'peach':3}|ksort %}
{{ print_r(items) }}
```

（結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

### `ltrim`

`'/strip/leading/slash/'|ltrim('/')`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

Left trimは、文字列の最初の空白などを取り除きます。同時に、他の文字を設定すれば、その文字も取り除けます。（[https://www.php.net/manual/ja/function.ltrim.php](https://www.php.net/manual/ja/function.ltrim.php) もご覧ください）

### `markdown`

マークダウンを含む文字列に対して、Gravのマークダウンパーサーを使ってHTMLに変換します。`boolean` パラメータを付けられます。

* `true` （デフォルト）: ブロックとして処理する（テキストモードで、`<p>` タグで囲まれます）
* `false`: 行として処理する（何も囲まれません）

```
string|markdown($is_block)
```

```twig
<div class="div">
{{ 'A paragraph with **markdown** and [a link](http://www.cnn.com)'|markdown }}
</div>

<p class="paragraph">{{'A line with **markdown** and [a link](http://www.cnn.com)'|markdown(false) }}</p>
```

（結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

### `md5`

文字列に対するmd5ハッシュ値を作成します。

`'anything'|md5`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

### `modulus`

PHPの `%` 記号（割り算の余り）と同じ機能です。ある数字に対して、割る数と、その中から選ばれる配列を渡して使います。

`7|modulus(3, ['red', 'blue', 'green'])`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

### `monthize`

日数を月数に変換します。

`'181'|monthize`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）


### `nicecron`

cronの構文を人間にとって読みやすい出力にします。

`"2 * * * *"|nicecron`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

### `nicefilesize`

人間にとって読みやすいファイルサイズを出力します。

`612394|nicefilesize`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

### `nicenumber`

人間にとって読みやすい形式で数字を出力します。

`12430|nicenumber`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）
[/version]

### `nicetime`

人間にとって読みやすい形式で日付を出力します。

`page.date|nicetime(false)`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

最初の引数は、日付のフルフォーマットかどうかを示します。デフォルトでは `true` です。

第2引数に `false` を渡すと、相対的な時間の記述（'ago' や 'from now' など）が結果から取り除かれます。


<h3 id="of-type"><code>of_type</code></h3>

引数の型かどうかチェックします：

`page|of_type('string')`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

### `ordinalize`

順番のある整数値にします（1st, 2nd, 3rd, 4th など）

`'10'|ordinalize`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

### `pad`

pad は、ある長さにするために他の文字で埋めます。これは、PHPの[`str_pad`](https://www.php.net/manual/ja/function.str-pad.php) 関数と同じです。

`'foobar'|pad(10, '-')`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

### `pluralize`

文字列を英語の複数形に変換します。

`'person'|pluralize`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

`pluralize` also takes an optional numeric parameter which you can pass in when you don't know in advance how many items the noun will refer to. It defaults to 2, so will provide the plural form if omitted. For example:


```twig
<p>We have {{ num_vacancies }} {{ 'vacancy'|pluralize(num_vacancies) }} right now.</p>
```

<h3 id="print-r"><code>print_r</code></h3>

人間に読みやすい形式で変数を表示します。

`page.header|print_r`

（結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

### `randomize`

一覧をランダムに入れ替えます。パラメータが与えられたら、その数まではスキップして、その順番のままとなります。

`array|randomize`

```twig
{% set ritems = ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten']|randomize(2) %}
{{ print_r(ritems) }}
```

（結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

<h3 id="regex-replace"><code>regex_replace</code></h3>

PHPの [`preg_replace`](https://www.php.net/manual/ja/function.preg-replace.php) 関数と同じ機能です。このフィルタを使えば、複雑な正規表現の書き換えができます：

`'The quick brown fox jumps over the lazy dog.'|regex_replace(['/quick/','/brown/','/fox/','/dog/'], ['slow','black','bear','turtle'])`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

> [!Note]  
> Use the `~`-delimiter rather than the `/`-delimiter where possible. Otherwise you'll most likely have to [double-escape certain characters](https://github.com/getgrav/grav/issues/833). Eg. `~\/\#.*~` rather than `'/\\/\\#.*/'`, which conforms more closely to the [PCRE-syntax](https://www.php.net/manual/en/regexp.reference.delimiters.php) used by PHP.

### `rtrim`

`'/strip/trailing/slash/'|rtrim('/')`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

文字列の最後の空白などを取り除きます。同時に、他の文字を設定すれば、その文字も取り除けます。（[https://www.php.net/manual/ja/function.rtrim.php](https://www.php.net/manual/ja/function.rtrim.php) もご覧ください）

### `singularize`

英語の単数形に変えます。

`'shoes'|singularize`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

<h3 id="safe-email"><code>safe_email</code></h3>

eメールアドレスをASCII文字に変換します。Eメールスパムボットに認識されづらくします。

`"someone@domain.com"|safe_email`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

mailto リンクの例です：

```html
<a href="mailto:{{ 'your.email@server.com'|safe_email }}">
  Email me
</a>
```

最初に見たときは、違いが分からないかもしれませんが、ページソース（ブラウザのディベロッパーツールではなく、実際のページソース）を確かめてください。文字列がエンコードされています。

<h3 id="sort-by-key"><code>sort_by_key</code></h3>

配列を特定のキーでソートします。

`array|sort_by_key`

```twig
{% set people = [{'email':'fred@yahoo.com', 'id':34}, {'email':'tim@exchange.com', 'id':21}, {'email':'john@apple.com', 'id':2}]|sort_by_key('id') %}
{% for person in people %}{{ person.email }}:{{ person.id }}, {% endfor %}
```

（結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

<h3 id="starts-with"><code>starts_with</code></h3>

ニードルとヘイスタックを使って、ヘイスタックがニードルで始まるか調べます。ニードルが配列の場合、ヘイスタックがニードルの **いずれか** で始まるとき、`true` を返します。

`'the quick brown fox'|starts_with('the')`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

### `titleize`

文字列を"Title Case" フォーマットに変換します。

`'welcome page'|titleize`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）


### `t`

現在の言語に翻訳します。

`'MY_LANGUAGE_KEY_STRING'|t`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

これは、あなたのサイトでその文字列が翻訳済みで、その言語がサポートされていることが前提です。詳しくは、[多言語サイトのドキュメント](../../../02.content/11.multi-language/) を参照してください。

### `tu`

文字列を、管理者のユーザー設定した言語に翻訳します。

`'MY_LANGUAGE_KEY_STRING'|tu`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

ユーザのyamlに設定された言語を使います。

### `ta`

配列（array）に対して翻訳します。詳しくは、[多言語サイトのドキュメント](../../../02.content/11.multi-language/) を参照してください。

`'MONTHS_OF_THE_YEAR'|ta(post.date|date('n') - 1)`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

### `tl`

文字列を特定の言語に翻訳します。詳しくは、[多言語サイトのドキュメント](../../../02.content/11.multi-language/) を参照してください。

`'SIMPLE_TEXT'|tl(['fr'])`

### `truncate`

かんたんに、文字列を短くし、切り捨てられます。数字を渡しますが、他のオプションもあります：

`'one sentence. two sentences'|truncate(5)|raw`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

単に5文字に切り捨てます。

`'one sentence. two sentences'|truncate(5, true)|raw`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

> [!Info]  
> The `|raw` Twig filter should be used with the default `&hellip;` (elipsis) padding element in order for it to render with Twig auto-escaping

trancateは、5文字の後の単語の終わりで、一番近いところで切り捨てます。

また、HTMLテキストを切り捨てることもできます。ただし、先に `|striptags` フィルタをして、HTMLフォーマットを取り除いてください。最後がタグ中だった場合、壊れてしまうので：

`'<span>one <strong>sentence</strong>. two sentences</span>'
|raw|striptags|truncate(25)`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）


<h4 id="specialized-versions">特別なバージョン：</h4>

<h3 id="safe-truncate"><code>safe_truncate</code></h3>

`|safe_truncate` を使うと、 "word-safe" な方法で、テキストを文字数で切り捨てます。

> [!訳注]  
> ここでの"word-safe" が何を指すのか分からないのですが、たぶんマルチバイト文字列のことかなと思います。

<h3 id="truncate-html"><code>truncate_html</code></h3>

`|truncate_html` を使うと、HTMLを文字数で切り捨てます。"word-safe" ではありません！

<h3 id="safe-truncate-html"><code>safe_truncate_html</code></h3>

`|safe_truncate_html` を使うと、 "word-safe" な方法で、HTMlを文字数で切り捨てます。

### `underscorize`

「アンダースコア」のフォーマットに文字列を変換します。

`'CamelCased'|underscorize`  -&gt; （結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

<h3 id="yaml-encode"><code>yaml_encode</code></h3>

変数をYAML構文に出力します。

```twig
{% set array = {foo: [0, 1, 2, 3], baz: 'qux' } %}
{{ array|yaml_encode }}
```

（結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

<h3 id="yaml-decode"><code>yaml_decode</code></h3>

YAML構文から変数にデコード・パースします。

```twig
{% set yaml = "foo: [0, 1, 2, 3]\nbaz: qux" %}
{{ yaml|yaml_decode|var_dump }}
```

（結果は[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/filters)へ）

