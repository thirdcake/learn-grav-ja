---
title: カスタム関数
layout: ../../../../layouts/Default.astro
lastmod: '2026-01-03'
description: 'Grav で独自に追加した Twig のカスタム関数について解説します。'
---

Twig 関数は、カッコ内にパラメータを渡すことで、直接呼び出せます。

### array

値を配列型にします。

Twig:

```twig
{% set value = array(value) %}
```

出力：

```txt
（配列型にキャストされた値）
```

<h3 id="array-diff">array_diff</h3>

配列の差分を計算します。

Twig:

```twig
{% set diff = array_diff(array1, array2...) %}
```

出力：

```txt
（array1 に含まれ、それ以降の array には含まれないものを含む配列）
```

<h3 id="array-key-value">array_key_value</h3>

キー/バリューのペアを配列に追加します。

Twig:

```twig
{% set my_array = {fruit: 'apple'} %}
{% set my_array = array_key_value('meat','steak', my_array) %}
{{ print_r(my_array)}}
```

出力：

```txt
[
    "fruit" => "apple"
    "meat" => "steak"
]
```

<h3 id="array-key-exists">array_key_exists</h3>

PHP の `array_key_exists` 関数と同じです。  
配列にキーが存在するかどうかを判断します

Twig:

```twig
{% set my_array = {fruit: 'apple', meat: 'steak'} %}
{{ array_key_exists('meat', my_array) }}
```

出力：

```txt
true
```

<h3 id="array-intersect">array_intersect</h3>

2つの配列もしくは Grav collections の間の共通部分を調べます

Twig:

```twig
{% set array_1 = {fruit: 'apple', meat: 'steak'} %}
{% set array_2 = {fish: 'tuna', meat: 'steak'} %}
{{ print_r(array_intersect(array_1, array_2)) }}
```

出力：

```txt
[
    "meat" => "steak"
]
```

<h3 id="array-unique">array_unique</h3>

PHP の `array_unique` 関数と同じです。配列から重複を除きます。

Twig:

```twig
{{ array_unique(['foo', 'bar', 'foo', 'baz']) }}
```

出力：

```txt
['foo', 'bar', 'baz']
```

### authorize

そのリソースを見る権限があるかどうか認証します。  
引数には、1つの string 型のパーミッション文字列か、複数の string 型のパーミッション文字列のみを要素に持つ配列を渡してください。

Twig:

```twig
{{ authorize(['admin.statistics', 'admin.super']) }}
```

出力：

```txt
true/false （ユーザーのパーミッション次第）
```

<h3 id="body-class">body_class</h3>

クラス名の配列を渡してください。  
`body_classes` に設定されていなければ、現在のテーマ設定にセットされているか調べます。

Twig:

```twig
set body_classes = body_class(['header-fixed', 'header-animated', 'header-dark', 'header-transparent', 'sticky-footer'])
```

出力：

```txt
header-fixed header-animated header-dark
```

### cron

cron の構文から、 "Cron" オブジェクトを作ります

Twig:

```twig
{{ cron("3 * * * *").getNextRunDate()|date(config.date_format.default) }}
```

出力：

```txt
2024-01-15 14:03:00
```

### dump

Twig 変数を渡すと、 [Grav デバッグパネル](../../../08.advanced/03.debugging/) に出力します。  
デバッガは、**enabled** になっていなければいけません。

Twig:

```twig
{% do dump(page.header) %}
```

出力：

```txt
（デバッガパネルに変数を出力）
```

### debug

`dump()` と同じです。

### `evaluate`

文字列を Twig として評価します。

Twig:

```twig
{{ evaluate('grav.language.getLanguage') }}
```

出力：

```txt
en
```

<h3 id="evaluate-twig">evaluate_twig</h3>

evaluate に似ていますが、 Twig として評価した後に処理します。

Twig:

```twig
{{ evaluate_twig('This is a twig variable: {% verbatim %}{{ foo }}{% endverbatim %}', {foo: 'bar'}) }}
```

出力：

```txt
This is a twig variable: bar
```

### exif

filepath で渡した画像から、 EXIF データを出力します。  
これを実行するには、 `system.yaml` で `media: auto_metadata_exif: true` が設定されている必要があります。  
たとえば、 Twig テンプレート上で以下のようにします：

Twig:

```twig
{% set image = page.media['sample-image.jpg'] %}
{% set exif = exif(image.filepath, true) %}
{{ exif.MaxApertureValue }}
```

出力：

```txt
40/10
```

これは、 `MaxApertureValue` が出力されます。  
これはカメラに設定されている値で、たとえば "40/10" です。  
いつでも `{{ dump(exif) }}` を使うことで、デバッガ利用できるデータはすべて見られます。

<h3 id="get-cookie">get_cookie</h3>

cookie を取得します：

Twig:

```twig
{{ get_cookie('your_cookie_key') }}
```

出力：

```txt
（cookie の値）
```

<h3 id="get-type">get_type</h3>

変数の型を取得します：

Twig:

```twig
{{ get_type(page) }}
```

出力：

```txt
Grav\Common\Page\Page
```

### gist

GitHub Gist ID を使って、適切な Gist 埋め込みコードを作成します

Twig:

```twig
{{ gist('bc448ff158df4bc56217') }}
```

出力：

```txt
<script src="https://gist.github.com/bc448ff158df4bc56217.js"></script>
```

<h3 id="header-var">header_var</h3>

`header_var($variable, $pages = null)`

`page.header.<variable>` を返します。

> [!Note]  
> **NOTE:** Grav 1.7 から非推奨になりました。 `theme_var` を使ってください。

> [!WARNING]  
> 変数を探すロジックが変更され、期待しない結果になるかもしれません：
> - 探すページの配列が第2引数に渡されたら、最初のページだけが使われます
> - `<variable>` がページのフロントマターに定義されていなければ、 Grav はページの親のツリーで変数を探します
> - それでも見つからなければ、 Grav はテーマの config ファイルの変数を探します

次のようなフロントマターが与えられたとき：

```
---
title: Home
---
```

Twig:

```twig
{{ header_var('title') }}
```

出力：

```txt
Home
```

<h3 id="http-response-code">http_response_code</h3>

レスポンスコードを渡すと、そのステータスコードを返します。  
渡さない場合、現在のステータスコードが返ります。  
web サーバ環境では、両方とも、デフォルトは 200 です。

Twig:

```twig
{% do http_response_code(404) %}
```

出力：

```txt
（HTTP レスポンスコードに 404 を設定）
```

### isajaxrequest

`HTTP_X_REQUESTED_WITH` ヘッダが設定されているかチェックします。 

Twig:

```twig
{{ isajaxrequest() }}
```

出力：

```txt
true/false
```

<h3 id="json-decode">json_decode</h3>

JSON がシンプルにデコードできます：

Twig:

```twig
{{ json_decode('{"first_name": "Guido", "last_name":"Rossum"}') }}
```

出力：

```txt
[
    "first_name" => "Guido"
    "last_name" => "Rossum"
]
```

<h3 id="media-directory">media_directory</h3>

任意のディレクトリから、メディアオブジェクトを返します。  
一度取得すれば、ページと似た方法で画像を操作できます。

Twig:

```twig
{{ media_directory('theme://images')['some-image.jpg'].cropResize(200,200).html }}
```

出力：

```txt
<img src="/user/themes/mytheme/images/some-image.jpg" width="200" height="200">
```

### nicefilesize

人間に読みやすいファイルサイズを出力します

Twig:

```twig
{{ nicefilesize(612394) }}
```

出力：

```txt
598.04 KB
```

### nicenumber

人間に読みやすい数字を出力します

Twig:

```twig
{{ nicenumber(12430) }}
```

出力：

```txt
12K
```

### nicetime

人間に読みやすいフォーマットの日付を返します

Twig:

```twig
{{ nicetime(page.date) }}
```

出力：

```txt
1 month ago
```

<h3 id="nonce-field">nonce_field</h3>

`action` を渡すことで、フォームのセキュリティのための nonce フィールドを生成します：

Twig:

```twig
{{ nonce_field('action') }}
```

出力：

```txt
<input type="hidden" name="nonce" value="abc123def456">
```

<h3 id="of-type">of_type</h3>

変数の型をチェックします：

Twig:

```twig
{{ of_type(page, 'string') }}
```

出力：

```txt
false
```

### pathinfo

パスを配列にパースします。

Twig:

```twig
{% set parts = pathinfo('/www/htdocs/inc/lib.inc.php') %}
{{ print_r(parts) }}
```

出力：

```txt
[
    "dirname" => "/www/htdocs/inc"
    "basename" => "lib.inc.php"
    "extension" => "php"
    "filename" => "lib.inc"
]
```

<h3 id="print-r">print_r</h3>

読みやすい書式で、変数を表示します

Twig:

```twig
{{ print_r(page.header) }}
```

出力：

```txt
[
    "title" => "My Page"
    "published" => true
]
```

<h3 id="random-string">random_string</h3>

渡された文字数分の、ランダムな文字列を生成します。  
ユニークな ID を作りたいときに便利です。

Twig:

```twig
{{ random_string(10) }}
```

出力：

```txt
aBc123XyZ9
```

<h3 id="unique-id">unique_id</h3>

接頭辞や接尾辞付きで、文字数分のランダムな文字列を作ります。  
PHP 組み込みの `uniqid` 関数や、`random_string` カスタム関数と違い、この文字列は真にユニークで、コンフリクトしません。

Twig:

```twig
{{ unique_id(9) }}
{{ unique_id(11, { prefix: 'user_' }) }}
{{ unique_id(13, { suffix: '.json' }) }}
```

出力：

```txt
a1b2c3d4e
user_a1b2c3d4e5f
a1b2c3d4e5f6g.json
```

### range

範囲内の要素を持つ配列を生成します。  
要素間の差も決められます

Twig:

```twig
{{ range(25, 300, 50) }}
```

出力：

```txt
[25, 75, 125, 175, 225, 275]
```

<h3 id="read-file">read_file</h3>

ファイルのパスをもとに、ファイルを読み込み、それを出力します。

Twig:

```twig
{{ read_file('plugins://admin/README.md')|markdown }}
```

出力：

```txt
<h1>Grav Standard Administration Panel Plugin</h1>
<p>This <strong>admin plugin</strong> for Grav...</p>
```

<h3 id="redirect-me">redirect_me</h3>

選んだURLにリダイレクトします

Twig:

```twig
{% do redirect_me('http://google.com', 304) %}
```

出力：

```txt
（304 ステータスで http://google.com にリダイレクトします）
```

<h3 id="regex-filter">regex_filter</h3>

PHP の `preg_grep` 関数のように、正規表現パターンに合う配列を返します

Twig:

```twig
{{ regex_filter(['pasta', 'fish', 'steak', 'potatoes'], "/p.*/") }}
```

出力：

```txt
['pasta', 'potatoes']
```

<h3 id="regex-replace">regex_replace</h3>

PHP の [`preg_replace`](https://www.php.net/manual/ja/function.preg-replace.php) 関数のように、複雑な正規表現でテキストを書き換えます：

Twig:

```twig
{{ regex_replace('The quick brown fox jumps over the lazy dog.', ['/quick/','/brown/','/fox/','/dog/'], ['slow','black','bear','turtle']) }}
```

出力：

```txt
The slow black bear jumps over the lazy turtle.
```

<h3 id="regex-match">regex_match</h3>

PHP の [`preg_match`](https://www.php.net/manual/ja/function.preg-match.php) 関数のように、複雑な正規表現にマッチするか調べられます。

Twig:

```twig
{{ regex_match('http://www.php.net/index.html', '@^(?:http://)?([^/]+)@i') }}
```

出力：

```txt
[
    0 => "http://www.php.net"
    1 => "www.php.net"
]
```

<h3 id="regex-split">regex_split</h3>

PHP の [`preg_split`](https://www.php.net/manual/ja/function.preg-split.php) 関数のように、正規表現で文字列を分割できます。

`regex_split('hypertext language, programming', '/\\s*,\\s*/u')`

Twig:

```twig
{{ regex_split('hypertext language, programming', '/\\s*,\\s*/u') }}
```

出力：

```txt
['hypertext language', 'programming']
```

### repeat

引数に渡されたものを、その回数分繰り返します。

Twig:

```twig
{{ repeat('blah ', 10) }}
```

出力：

```txt
blah blah blah blah blah blah blah blah blah blah
```
 
### string

値から文字列型を返します。  
配列が渡されると、 JSON にエンコードしたものが返ります

Twig:

```twig
{{ string(23) }}
{{ string(['test' => 'x']) }}
```

出力：

```txt
"23"
{"test":"x"}
```

<h3 id="svg-image">svg_image</h3>

必要なクラスを付けて、 SVG 画像を返します。  
ページに直接コードを書くことなく、インラインの SVG の利益が得られます。  
ソーシャルメディアアイコンのような、再利用可能な画像のとき便利です。

```txt
{{ svg_image(path, classes, strip_style) }}
```

`strip_style` = svg インラインのスタイルを取り除きます - CSS class でスタイリングしたいときに便利です

Twig:

```twig
{{ svg_image('theme://images/something.svg', 'my-class-here mb-10', true) }}
```

出力：

```txt
<svg class="my-class-here mb-10" viewBox="0 0 24 24">...</svg>
```

<h3 id="theme-var">theme_var</h3>

`theme_var($variable, $default = null, $page = null)`

ページのフロントマターからテーマ変数を取得します。  
もし見つからない場合は、その親（先祖）や、テーマの設定ファイルや、デフォルト値が決められていればそれを取得します。

Twig:

```twig
{{ theme_var('grid-size') }}
```

出力：

```txt
1200
```

上記の場合、まず `page.header.grid-size` を探します。  
無ければ、親フォルダのそれを探します。  
まだ見つからなければ、テーマの設定ファイルから、`theme.grid-size` を探します。

見つからなかった時のための、デフォルト値を渡すこともできます：

Twig:

```twig
{{ theme_var('grid-size', 1024) }}
```

出力：

```txt
1200 （もし他に見つからなければ）
```

### t

[`|t` フィルタ](../02.filters/#t) のように、文字列を翻訳します。

Twig:

```twig
{{ t('SITE_NAME') }}
```

出力：

```txt
（サイト名）
```

### ta

[`|ta` フィルタ](../02.filters/#ta) と同じことをする関数です。

### tl

文字列を特定の言語に翻訳します。  
詳しくは、 [多言語サイトのドキュメント](../../02.content/11.multi-language/#complex-translations) を参照してください。

Twig:

```twig
{{ tl('SIMPLE_TEXT', ['fr']) }}
```

出力：

```txt
Texte simple
```

### url

URL を作り、 PHP URL ストリームを適切な HTML に変換します。  
URL として解決できなかったときのため、デフォルト値を渡すこともできます。

Twig:

```twig
{{ url('theme://images/logo.png')|default('http://www.placehold.it/150x100/f4f4f4') }}
```

出力：

```txt
/user/themes/mytheme/images/logo.png
```

### vardump

現在の変数を画面に表示します（ `dump` のように、デバッガでなくても動きます）

Twig:

```twig
{% set my_array = {foo: 'bar', baz: 'qux'} %}
{{ vardump(my_array) }}
```

出力：

```txt
[
  "foo" => "bar"
  "baz" => "qux"
]
```

### xss

文字列の XSS 脆弱性を手動でチェックできます

Twig:

```twig
{{ xss('this string contains a <script>alert("hello");</script> XSS vulnerability') }}
```

出力：

```txt
this string contains a  XSS vulnerability
```

