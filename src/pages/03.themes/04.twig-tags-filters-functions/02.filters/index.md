---
title: カスタムフィルタ
layout: ../../../../layouts/Default.astro
lastmod: '2026-01-03'
description: 'Grav で独自に追加した Twig のカスタムフィルタについて解説します。'
---

Twig のフィルタは、 `|` という文字と、それに続くフィルタ名を使って、 Twig 変数に適用されます。  
Twig 関数と同じように、カッコを使って引数を渡すことができます。

<h3 id="absolute-url">absolute_url</h3>

相対パスを使った `src` 属性や `href` 属性を持つ HTML 部分に使われます。  
相対パスを、ホスト名を含む絶対 URL 表記の文字列に変換します。

Twig:

```twig
{{ '<img src="/some/path/to/image.jpg" />'|absolute_url }}
```

出力:

```txt
<img src="https://learn.getgrav.org/some/path/to/image.jpg">
```

<h3 id="array-unique">array_unique</h3>

PHP の `array_unique()` 関数と同様のもので、配列から重複するものを取り除きます。

Twig:

```twig
{{ ['foo', 'bar', 'foo', 'baz']|array_unique }}
```

出力：

```txt
['foo', 'bar', 'baz']
```

<h3 id="base32-encode">base32_encode</h3>

変数をbase32エンコードします。

Twig:

```twig
{{ 'some variable here'|base32_encode }}
```

出力：

```txt
ONXW2ZJAOZQXE2LBMJWGKIDIMVZGK
```

<h3 id="base32-decode">base32_decode</h3>

変数をbase32デコードします。

Twig:

```twig
{{ 'ONXW2ZJAOZQXE2LBMJWGKIDIMVZGK'|base32_decode }}
```

出力：

```txt
some variable here
```

<h3 id="base64-encode">base64_encode</h3>

変数をbase64エンコードします。

Twig:

```twig
{{ 'some variable here'|base64_encode }}
```

出力：

```txt
c29tZSB2YXJpYWJsZSBoZXJl
```

<h3 id="base64-decode">base64_decode</h3>

変数をbase64デコードします。

Twig:

```twig
{{ 'c29tZSB2YXJpYWJsZSBoZXJl'|base64_decode }}
```

出力：

```txt
some variable here
```

### basename

パスのbasenameを返します。

Twig:

```twig
{{ '/etc/sudoers.d'|basename }}
```

出力：

```txt
sudoers.d
```

### camelize

文字列をキャメルケース表記に変換します。

Twig:

```twig
{{ 'send_email'|camelize }}
```

出力：

```txt
SendEmail
```

<h3 id="chunk-split">chunk_split</h3>

文字列をあるサイズで小さく区切ります。

Twig:

```twig
{{ 'ONXW2ZJAOZQXE2LBMJWGKIDIMVZGKA'|chunk_split(6, '-') }}
```

出力：

```txt
ONXW2Z-JAOZQX-E2LBMJ-WGKIDI-MVZGKA-
```

### contains

ある文字列に別の文字列が含まれるか判断します。

Twig:

```twig
{{ 'some string with things in it'|contains('things') }}
```

出力：

```txt
true
```

<h4 id="casting-values">値をキャストする</h4>

PHP7から、型チェックが厳しくなりました。  
これにより、間違った型の値には例外が投げられます。  
これを避けるため、値を適切にメソッドに渡すために、フィルタを使ってください。

### string

文字列型にするには、 `|string` を使ってください。

### int

整数型にするには、 `|int` を使ってください。

### bool

真偽値型にするには、 `|bool` を使ってください。

### float

実数型にするには、 `|float` を使ってください。

### array

配列型にするには、 `|array` を使ってください。

### defined

ときには、その変数が定義済みかどうかチェックしたいときがあります。  
未定義の場合、デフォルト値を渡したい場合です。たとえば：

```twig
set header_image_width  = page.header.header_image_width|defined(900)
```

この例では、ページのフロントマターで、 `header_image_width` 変数が未定義だった場合に、900を入れます。

### dirname

パスのディレクトリ名を返します。

Twig:

```twig
{{ '/etc/sudoers.d'|dirname }}
```

出力：

```txt
/etc
```

<h3 id="ends-with">ends_with</h3>

> [!訳注]  
> 干し草の山から針を探す作業になぞらえて、文字列などの検索時、ヘイスタック（干し草の山）を探す場所、ニードル（針）を探すものとすることがあります。

ニードルとヘイスタックを使って、ヘイスタックがニードルで終わっているか判断します。  
また、ニードルを配列で渡し、ヘイスタックがいずれかのニードルで終わっていれば `true` を返します。

Twig:

```twig
{{ 'the quick brown fox'|ends_with('fox') }}
```

出力：

```txt
true
```

### fieldName

フィールド名をドット表記から配列表記に変えます。

Twig:

```twig
{{ 'field.name'|fieldName }}
```

出力：

```txt
field[name]
```

<h3 id="get-type">get_type</h3>

変数の型を返します。

Twig:

```twig
{{ page|get_type }}
```

出力：

```txt
Grav\Common\Page\Page
```

### humanize

文字列を「人間に読みやすい」表記に変換します。

Twig:

```twig
{{ 'something_text_to_read'|humanize }}
```

出力：

```txt
Something text to read
```

### hyphenize

ハイフン付き文字列に変換します。

Twig:

```twig
{{ 'Something Text to Read'|hyphenize }}
```

出力：

```txt
something-text-to-read
```

<h3 id="json-decode">json_decode</h3>

JSON をデコードします。

Twig:

```twig
{% set array = '{"first_name": "Guido", "last_name":"Rossum"}'|json_decode %}
{{ print_r(array) }}
```

出力：

```txt
[
    "first_name" => "Guido"
    "last_name" => "Rossum"
]
```

### ksort

配列をキーでソートします。

Twig:

```twig
{% set items = {'orange':1, 'apple':2, 'peach':3}|ksort %}
{{ print_r(items) }}
```

出力：

```txt
[
    "apple" => 2
    "orange" => 1
    "peach" => 3
]
```

### ltrim

Left trim は、文字列の最初の空白などを取り除きます。  
同時に、他の文字を設定すれば、その文字も取り除けます。（[https://www.php.net/manual/ja/function.ltrim.php](https://www.php.net/manual/ja/function.ltrim.php) もご覧ください）

Twig:

```twig
{{ '/strip/leading/slash/'|ltrim('/') }}
```

出力：

```txt
strip/leading/slash/
```

### markdown

マークダウンを含む文字列に対して、 Grav のマークダウンパーサーを使って HTML に変換します。  
`boolean` パラメータを付けられます。

* `true` （デフォルト）: ブロックとして処理する（テキストモードで、`<p>` タグで囲みます）
* `false`: 行として処理する（全体を囲むものはありません）

Text:

```txt
string|markdown($is_block)
```

Twig:

```twig
<div class="div">
{{ 'A paragraph with **markdown** and [a link](http://www.cnn.com)'|markdown }}
</div>

<p class="paragraph">{{'A line with **markdown** and [a link](http://www.cnn.com)'|markdown(false) }}</p>
```

出力：

```txt
<div class="div">
<p>A paragraph with <strong>markdown</strong> and <a href="http://www.cnn.com">a link</a></p>
</div>

<p class="paragraph">A line with <strong>markdown</strong> and <a href="http://www.cnn.com">a link</a></p>
```

### md5

文字列に対するmd5ハッシュ値を作成します。

Twig:

```twig
{{ 'anything'|md5 }}
```

出力：

```txt
f0e166dc34d14d6c228ffac576c9a43c
```

### modulus

PHPの `%` 記号（割り算の余り）と同じ機能です。  
ある数字に対して、割る数と、その中から選ばれる配列を渡して使います。

Twig:

```twig
{{ 7|modulus(3, ['red', 'blue', 'green']) }}
```

出力：

```txt
blue
```

### monthize

日数を月数に変換します。

Twig:

```twig
{{ '181'|monthize }}
```

出力：

```txt
6
```

### nicecron

cron の構文を、人間にとって読みやすい出力にします。

Twig:

```twig
{{ "2 * * * *"|nicecron }}
```

出力：

```txt
At 2 minutes past the hour
```

### nicefilesize

人間にとって読みやすいファイルサイズを出力します。

Twig:

```twig
{{ 612394|nicefilesize }}
```

出力：

```txt
598.04 KB
```

### nicenumber

人間にとって読みやすい形式で数字を出力します。

Twig:

```twig
{{ 12430|nicenumber }}
```

出力：

```txt
12K
```

### nicetime

人間にとって読みやすい形式で日付を出力します。

Twig:

```twig
{{ page.date|nicetime(false) }}
```

出力：

```txt
1 month ago
```

最初の引数は、日付のフルフォーマットかどうかを示します。  
デフォルトでは `true` です。

第2引数に `false` を渡すと、相対的な時間の記述（'ago' や 'from now' など）が結果から取り除かれます。

> [!訳注]  
> 実際に試してみると、上記の説明とは違う挙動をするので、よくわかりません。

<h3 id="of-type">of_type</h3>

引数の型かどうかチェックします：

Twig:

```twig
{{ page|of_type('string') }}
```

出力：

```txt
false
```

### ordinalize

順番のある整数値にします（1st, 2nd, 3rd, 4th など）

Twig:

```twig
{{ '10'|ordinalize }}
```

出力：

```txt
10th
```

### pad

pad は、ある長さにするために他の文字で埋めます。  
これは、 PHP の [`str_pad`](https://www.php.net/manual/ja/function.str-pad.php) 関数と同じです。

Twig:

```twig
{{ 'foobar'|pad(10, '-') }}
```

出力：

```txt
foobar----
```

### pluralize

文字列を英語の複数形に変換します。

Twig:

```twig
{{ 'person'|pluralize }}
```

出力：

```txt
people
```

`pluralize` は、オプションの数字パラメータも受け取り、名詞が参照する個数が事前に分からないときに使えます。  
デフォルトは 2 なので、省略すれば複数形になります。  
例えば：

Twig:

```twig
<p>We have {{ num_vacancies }} {{ 'vacancy'|pluralize(num_vacancies) }} right now.</p>
```

<h3 id="print-r">print_r</h3>

人間に読みやすい形式で変数を表示します。

Twig:

```twig
page.header|print_r
```

### randomize

一覧をランダムに入れ替えます。  
パラメータが与えられたら、その数までは、元の順番のままスキップされます。

Twig:

```twig
{% set ritems = ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten']|randomize(2) %}
{{ print_r(ritems) }}
```

出力：

```txt
['one', 'two', 'eight', 'four', 'ten', 'seven', 'nine', 'three', 'six', 'five']
```

注意：最初の2つ（'one', 'two'）はそのままにして、残りはすべてランダムに入れ替えています。

<h3 id="regex-replace">regex_replace</h3>

PHPの [`preg_replace`](https://www.php.net/manual/ja/function.preg-replace.php) 関数と同じ機能です。  
このフィルタを使えば、複雑な正規表現の書き換えができます：

Twig:

```twig
{{ 'The quick brown fox jumps over the lazy dog.'|regex_replace(['/quick/','/brown/','/fox/','/dog/'], ['slow','black','bear','turtle']) }}
```

出力：

```txt
The slow black bear jumps over the lazy turtle.
```

> [!Warning]  
> デリミタ（区切り文字）は、可能な限り `/` よりも `~` を使ってください。そうしない場合、 [特定の文字を2回エスケープ](https://github.com/getgrav/grav/issues/833) しなければならなくなりそうです。例： `'/\\/\\#.*/'` ではなく `~\/\#.*~` 。これは、 PHP で使われる [PCRE 構文](https://www.php.net/manual/en/regexp.reference.delimiters.php) により準拠した方法です。

### rtrim

文字列の最後の空白などを取り除きます。  
同時に、他の文字を設定すれば、その文字も取り除けます。（[https://www.php.net/manual/ja/function.rtrim.php](https://www.php.net/manual/ja/function.rtrim.php) もご覧ください）

Twig:

```twig
{{ '/strip/trailing/slash/'|rtrim('/') }}
```

出力：

```txt
/strip/trailing/slash
```

### singularize

英語の単数形に変えます。

Twig:

```twig
{{ 'shoes'|singularize }}
```

出力：

```txt
shoe
```

<h3 id="safe-email">safe_email</h3>

Eメールアドレスを ASCII 文字に変換します。  
Eメールスパムボットに認識されづらくします。

Twig:

```twig
{{ "someone@domain.com"|safe_email }}
```

出力：

```txt
someone@domain.com
```

mailto リンクの例です：

```html
<a href="mailto:{{ 'your.email@server.com'|safe_email }}">
  Email me
</a>
```

初見では、違いが分からないかもしれませんが、ページソース（ブラウザのディベロッパーツールではなく、実際のページソース）を確かめてください。  
文字列がエンコードされています。

<h3 id="sort-by-key">sort_by_key</h3>

配列を特定のキーでソートします。

Twig:

```twig
{% set people = [{'email':'fred@yahoo.com', 'id':34}, {'email':'tim@exchange.com', 'id':21}, {'email':'john@apple.com', 'id':2}]|sort_by_key('id') %}
{% for person in people %}{{ person.email }}:{{ person.id }}, {% endfor %}
```

出力：

```txt
john@apple.com:2, tim@exchange.com:21, fred@yahoo.com:34,
```

<h3 id="starts-with">starts_with</h3>

ニードルとヘイスタックを使って、ヘイスタックがニードルで始まるか調べます。  
ニードルが配列の場合、ヘイスタックがニードルの **いずれか** で始まるとき、`true` を返します。

Twig:

```twig
{{ 'the quick brown fox'|starts_with('the') }}
```

出力：

```txt
true
```

### titleize

文字列を "Title Case" フォーマットに変換します。

Twig:

```twig
{{ 'welcome page'|titleize }}
```

出力：

```txt
Welcome Page
```

### t

サイトで表示されている言語に翻訳します。

Twig:

```twig
{{ 'MY_LANGUAGE_KEY_STRING'|t }}
```

出力：

```txt
Some Text in English
```

これは、あなたのサイトでその文字列が翻訳済みで、その言語がサポートされていることが前提です。  
詳しくは、 [多言語サイトのドキュメント](../../../02.content/11.multi-language/) を参照してください。

### tu

文字列を、管理者のユーザー設定した言語に翻訳します。

Twig:

```twig
{{ 'MY_LANGUAGE_KEY_STRING'|tu }}
```

出力：

```txt
Some Text in English
```

ユーザの yaml に設定された言語を使います。

### ta

配列（array）に対して翻訳します。  
詳しくは、 [多言語サイトのドキュメント](../../../02.content/11.multi-language/) を参照してください。

Twig:

```twig
{{ 'MONTHS_OF_THE_YEAR'|ta(post.date|date('n') - 1) }}
```

出力：

```txt
December
```

### tl

文字列を特定の言語に翻訳します。  
詳しくは、 [多言語サイトのドキュメント](../../../02.content/11.multi-language/) を参照してください。

Twig:

```twig
'SIMPLE_TEXT'|tl(['fr'])
```

### truncate

簡単に、文字列を短くし、切り捨てられます。  
数字を渡しますが、他のオプションもあります：

Twig:

```twig
'one sentence. two sentences'|truncate(5)|raw
```

出力：

```txt
one s…
```

単に5文字に切り捨てます。

Twig:

```twig
'one sentence. two sentences'|truncate(5, true)|raw
```

出力：

```txt
one sentence.…
```

5文字目以降で一番近い文末で切り捨てます。

> [!Caution]  
> Twig 自動エスケープが有効になっている場合、 `trancate` フィルタと一緒に `|raw` Twig フィルタを使ってください。 `&hellip;` (省略記号 ... ) 要素がエスケープされてしまうからです。

> [!訳注]  
> XSS の温床になるので、 raw フィルタの取り扱い時は注意してください。

また、 HTML テキストを切り捨てることもできます。  
ただし、先に `|striptags` フィルタをして、 HTML フォーマットを取り除いてください。  
最後がタグ中だった場合、壊れてしまうからです：

Twig:

```twig
'<span>one <strong>sentence</strong>. two sentences</span>'|raw|striptags|truncate(25)
```

出力：

```txt
one sentence. two senten…
```

<h4 id="specialized-versions">特別なバージョン：</h4>

<h3 id="safe-truncate">safe_truncate</h3>

`|safe_truncate` を使うと、 "word-safe" な方法で、テキストを文字数で切り捨てます。

> [!訳注]  
> ここでの "word-safe" が何を指すのか分からないのですが、単語区切りのことなのかもしれません。日本語で試してみた範囲では、うまく短縮できませんでした。

<h3 id="truncate-html">truncate_html</h3>

`|truncate_html` を使うと、HTML を文字数で切り捨てます。  
"word-safe" ではありません！

<h3 id="safe-truncate-html">safe_truncate_html</h3>

`|safe_truncate_html` を使うと、 "word-safe" な方法で、HTMlを文字数で切り捨てます。

### underscorize

「アンダースコア」のフォーマットに文字列を変換します。

Twig:

```twig
{{ 'CamelCased'|underscorize }}
```

出力：

```txt
camel_cased
```

### wordcount

テキスト文字列内の単語数を数えます。  
複数言語をサポートし、 HTML コンテンツの精度が向上しました。

Twig:

```twig
{{ page.content|wordcount }}
```

出力：

```txt
36
```

`wordcount` フィルタは、オプションで、ロケール引数を受け取ることもでき、異なる言語を適切に処理します。  
西欧言語（英語、スペイン語、フランス語など）では、スペース文字で独立している単語を数えます。  
アジア言語（中国語、日本語、韓国語）では、単語ではなく文字を数えます。これは、それぞれの言語の書き方において適切な方法です。

```twig
{# With specific locale for English content #}
{{ page.content|wordcount('en') }}

{# For Chinese content - counts characters instead of words #}
{{ page.content|wordcount('zh') }}

{# Usage in JSON-LD structured data #}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Article",
  "wordCount": page.content|wordcount,
  "headline": page.title
}
</script>
```

> [!Caution]  
> **サポートするロケール:** `en` (英語, デフォルト), `es` (スペイン語), `fr` (フランス語), `de` (ドイツ語), その他西欧言語では、単語ベースに数えます。 `zh`/`zh-cn`/`zh-tw`/`chinese` (中国語), `ja`/`japanese` (日本語), そして `ko`/`korean` (韓国語) は、文字ベースに数えます。

<h3 id="yaml-encode">yaml_encode</h3>

変数を YAML 構文に出力します。

Twig:

```twig
{% set array = {foo: [0, 1, 2, 3], baz: 'qux' } %}
{{ array|yaml_encode }}
```

出力：

```txt
foo:
    - 0
    - 1
    - 2
    - 3
baz: qux
```

<h3 id="yaml-decode">yaml_decode</h3>

YAML 構文から変数にデコード/パースします。

Twig:

```twig
{% set yaml = "foo: [0, 1, 2, 3]\nbaz: qux" %}
{{ yaml|yaml_decode|var_dump }}
```

出力：

```txt
array(2) {
  ["foo"]=> array(4) { [0]=> int(0) [1]=> int(1) [2]=> int(2) [3]=> int(3) }
  ["baz"]=> string(3) "qux"
}
```

