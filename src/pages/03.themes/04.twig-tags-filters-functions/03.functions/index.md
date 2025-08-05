---
title: カスタム関数
layout: ../../../../layouts/Default.astro
lastmod: '2025-08-05'
description: 'Grav で独自に追加した Twig のカスタム関数について解説します。'
---

> [!訳注]  
> このページの内容は、 Twig の関数を動的に実行している部分があり、静的サイトでは再現できません。実行結果は、 [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions) をご確認ください。

Twig 関数は、カッコ内にパラメータを渡して、ダイレクトに呼び出せます。

### `array`

値を配列型にします。

```twig
{% set value = array(value) %}
```

<h3 id="array-diff"><code>array_diff</code></h3>

配列の差分を計算します。

```twig
{% set diff = array_diff(array1, array2...) %}
```

<h3 id="array-key-value"><code>array_key_value</code></h3>

キー/バリューのペアを配列に追加します。

```twig
{% set my_array = {fruit: 'apple'} %}
{% set my_array = array_key_value('meat','steak', my_array) %}
{{ print_r(my_array)}}
```

出力： ( [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#array-key-value) で確認してください。)

<h3 id="array-key-exists"><code>array_key_exists</code></h3>

PHP の `array_key_exists` 関数と同じです。  
配列にキーが存在するかどうかを判断します

```twig
{% set my_array = {fruit: 'apple', meat: 'steak'} %}
{{ array_key_exists('meat', my_array) }}
```

出力： ( [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#array-key-exists) で確認してください。)

<h3 id="array-intersect"><code>array_intersect</code></h3>

2つの配列もしくは Grav collections の間の共通部分を調べます

```twig
{% set array_1 = {fruit: 'apple', meat: 'steak'} %}
{% set array_2 = {fish: 'tuna', meat: 'steak'} %}
{{ print_r(array_intersect(array_1, array_2)) }}
```

出力： ( [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#array-intersect) で確認してください。)

<h3 id="array-unique"><code>array_unique</code></h3>

PHP の `array_unique` 関数と同じです。配列から重複を除きます。

`array_unique(['foo', 'bar', 'foo', 'baz'])` -&gt; (結果は [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#array-unique) で確認してください。)

### `authorize`

そのリソースを見られるかどうか認証します。  
引数には、1つの string 型のパーミッション文字列か、複数の string 型のパーミッション文字列のみを要素に持つ配列を渡してください。

`authorize(['admin.statistics', 'admin.super'])`

<h3 id="body-class"><code>body_class</code></h3>

クラス名の配列を渡してください。  
`body_classes` に設定されていなければ、現在のテーマ設定にセットされているか調べます。

`set body_classes = body_class(['header-fixed', 'header-animated', 'header-dark', 'header-transparent', 'sticky-footer'])`

### `cron`

cron の構文から、 "Cron" オブジェクトを作ります

`cron("3 * * * *").getNextRunDate()|date(config.date_format.default)`

### `dump`

Twig 変数を渡すと、 [Grav デバッグパネル](../../../08.advanced/03.debugging/) に出力します。  
デバッガは、**enabled** になっていなければいけません。

`dump(page.header)`

### `debug`

`dump()` と同じです。

### `evaluate`

文字列を Twig として評価します。

`evaluate('grav.language.getLanguage')`

<h3 id="evaluate-twig"><code>evaluate_twig</code></h3>

evaluate に似ていますが、 Twig として評価した後に処理します。

`evaluate_twig('This is a twig variable: {{ foo }}', {foo: 'bar'})`  -&gt; (結果は [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#evaluate-twig) で確認してください。)

### `exif`

filepath で渡した画像から、 EXIF データを出力します。  
これを実行するには、 `system.yaml` で `media: auto_metadata_exif: true` が設定されている必要があります。  
たとえば、 Twig テンプレート上で以下のようにします：

```twig
{% set image = page.media['sample-image.jpg'] %}
{% set exif = exif(image.filepath, true) %}
{{ exif.MaxApertureValue }}
```

これは、 `MaxApertureValue` が出力されます。  
これはカメラに設定されている値で、たとえば "40/10" です。  
いつでも `{{ dump(exif) }}` を使うことで、デバッガ利用できるデータはすべて見られます。

<h3 id="get-cookie"><code>get_cookie</code></h3>

cookie を取得します：

`get_cookie('your_cookie_key')`

<h3 id="get-type"><code>get_type</code></h3>

変数の型を取得します：

`get_type(page)` -&gt; (結果は [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#get-type) で確認してください。)

### `gist`

GitHub Gist ID を使って、適切な Gist 埋め込みコードを作成します

`gist('bc448ff158df4bc56217')` -&gt; (結果は [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#gist) で確認してください。)

<h3 id="header-var"><code>header_var</code></h3>

`header_var($variable, $pages = null)`

`page.header.<variable>` を返します。

> [!Note]  
> **NOTE:** Grav 1.7 から非推奨になりました。 `theme_var` を使ってください。

> [!Note]  
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

`header_var('title')` -&gt; (結果は [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#header-var) で確認してください。)

<h3 id="http-response-code"><code>http_response_code</code></h3>

レスポンスコードを渡すと、そのステータスコードを返します。  
渡さない場合、現在のステータスコードが返ります。  
web サーバ環境では、両方とも、デフォルトは 200 です。

`http_response_code(404)`

### `isajaxrequest`

`HTTP_X_REQUESTED_WITH` ヘッダが設定されているかチェックします。 


<h3 id="json-decode"><code>json_decode</code></h3>

JSON がシンプルにデコードできます：

`json_decode({"first_name": "Guido", "last_name":"Rossum"})`

<h3 id="media-directory"><code>media_directory</code></h3>

任意のディレクトリから、メディアオブジェクトを返します。  
一度取得すれば、ページと似た方法で画像を操作できます。

`media_directory('theme://images')['some-image.jpg'].cropResize(200,200).html`

### `nicefilesize`

人間に読みやすいファイルサイズを出力します

`nicefilesize(612394)` -&gt; (結果は [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#nicefilesize) で確認してください。)

### `nicenumber`

人間に読みやすい数字を出力します

`nicenumber(12430)` -&gt; (結果は [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#nicenumber) で確認してください。)

### `nicetime`

人間に読みやすいフォーマットの日付を返します

`nicetime(page.date)` -&gt; (結果は [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#nicetime) で確認してください。)

<h3 id="nonce-field"><code>nonce_field</code></h3>

`action` を渡すことで、フォームのセキュリティのための nonce フィールドを生成します：

`nonce_field('action')` -&gt; (結果は [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#nonce-field) で確認してください。)

<h3 id="of-type"><code>of_type</code></h3>

変数の型をチェックします：

`of_type(page, 'string')` -&gt; (結果は [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#of-type) で確認してください。)

### `pathinfo`

パスを配列にパースします。

```twig
{% set parts = pathinfo('/www/htdocs/inc/lib.inc.php') %}
{{ print_r(parts) }}
```

出力： ( [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#pathinfo) で確認してください。)

<h3 id="print-r"><code>print_r</code></h3>

読みやすい書式で、変数を表示します

`print_r(page.header)`

[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#print-r) で確認してください。

<h3 id="random-string"><code>random_string</code></h3>

渡された文字数分の、ランダムな文字列を生成します。  
ユニークな ID を作りたいときに便利です。

`random_string(10)` -&gt; (結果は [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#random-string) で確認してください。)

<h3 id="unique-id"><code>unique_id</code></h3>

接頭辞や接尾辞付きで、文字数分のランダムな文字列を作ります。  
PHP 組み込みの `uniqid` 関数や、`random_string` カスタム関数と違い、この文字列は真にユニークで、コンフリクトしません。

`unique_id(9)`  
`unique_id(11, { prefix: 'user_' })`  
`unique_id(13, { suffix: '.json' })`  
-&gt; (各結果は [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#unique-id) で確認してください。)

### `range`

範囲内の要素を持つ配列を生成します。  
要素間の差も決められます

`range(25, 300, 50)` -&gt; (結果は [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#range) で確認してください。)


<h3 id="read-file"><code>read_file</code></h3>

ファイルのパスをもとに、ファイルを読み込み、それを出力します。

`read_file('plugins://admin/README.md')|markdown`

```markdown
# Grav Standard Administration Panel Plugin

This **admin plugin** for [Grav](https://github.com/getgrav/grav) is an HTML user interface that provides a convenient way to configure Grav and easily create and modify pages...
```

<h3 id="redirect-me"><code>redirect_me</code></h3>

選んだURLにリダイレクトします

`redirect_me('http://google.com', 304)`

<h3 id="regex-filter"><code>regex_filter</code></h3>

PHP の `preg_grep` 関数のように、正規表現パターンに合う配列を返します

`regex_filter(['pasta', 'fish', 'steak', 'potatoes'], "/p.*/")`

結果は [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#regex-filter) で確認してください。

<h3 id="regex-replace"><code>regex_replace</code></h3>

PHP の [`preg_replace`](https://www.php.net/manual/ja/function.preg-replace.php) 関数のように、複雑な正規表現でテキストを書き換えます：

`regex_replace('The quick brown fox jumps over the lazy dog.', ['/quick/','/brown/','/fox/','/dog/'], ['slow','black','bear','turtle'])`

結果は [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#regex-replace) で確認してください。

<h3 id="regex-match"><code>regex_match</code></h3>

PHP の [`preg_match`](https://www.php.net/manual/ja/function.preg-match.php) 関数のように、複雑な正規表現にマッチするか調べられます。

`regex_match('http://www.php.net/index.html', '@^(?:http://)?([^/]+)@i')`

結果は [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#regex-match) で確認してください。

<h3 id="regex-split"><code>regex_split</code></h3>

PHP の [`preg_split`](https://www.php.net/manual/ja/function.preg-split.php) 関数のように、正規表現で文字列を分割できます。

`regex_split('hypertext language, programming', '/\\s*,\\s*/u')`

結果は [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#regex-split) で確認してください。

### `repeat`

引数に渡されたものを、その回数分繰り返します。

`repeat('blah ', 10)` -&gt; (結果は [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#repeat) で確認してください。)
 
### `string`

値から文字列を返します。配列が渡されると、 JSON にエンコードしたものが返ります

`string(23)` => **"23"**

`string(['test' => 'x'])` => **{"test":"x"}**

<h3 id="svg-image"><code>svg_image</code></h3>

必要なクラスを付けて、 SVG 画像を返します。  
ページに直接コードを書くことなく、インラインの SVG の利益が得られます。  
ソーシャルメディアアイコンのような、再利用可能な画像のとき便利です。

`{{ svg_image(path, classes, strip_style) }}`

`strip_style` = svg インラインのスタイルを取り除きます - CSS class でスタイリングしたいときに便利です

たとえば：

`{{ svg_image('theme://images/something.svg', 'my-class-here mb-10', true) }}`

<h3 id="theme-var"><code>theme_var</code></h3>

`theme_var($variable, $default = null, $page = null)`

ページのフロントマターからテーマ変数を取得します。  
もし見つからない場合は、その親（先祖）や、テーマの設定ファイルや、デフォルト値が決められていればそれを取得します。

`theme_var('grid-size')`

上記の場合、まず `page.header.grid-size` を探します。  
無ければ、親フォルダのそれを探します。  
まだ見つからなければ、テーマの設定ファイルから、`theme.grid-size` を探します。

見つからなかった時のための、デフォルト値を渡すこともできます：

`theme_var('grid-size', 1024)`

### `t`

[`|t`](../02.filters/#t) フィルタのように、文字列を翻訳します。

`t('SITE_NAME')` -&gt; (結果は [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#t) で確認してください。)


### `ta`

[`|ta`](../02.filters/#ta) フィルタと同じことをする関数です。

### `tl`

文字列を特定の言語に翻訳します。  
詳しくは、 [多言語サイトのドキュメント](../../02.content/11.multi-language/#complex-translations) を参照してください。

`tl('SIMPLE_TEXT', ['fr'])`

### `url`

URL を作り、 PHP URL ストリームを適切な HTML に変換します。  
URL として解決できなかったときのため、デフォルト値を渡すこともできます。

`url('theme://images/logo.png')|default('http://www.placehold.it/150x100/f4f4f4')` -&gt; (結果は [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#url) で確認してください。)

### `vardump`

現在の変数を画面に表示します（ `dump` のように、デバッガでなくても動きます）

```twig
{% set my_array = {foo: 'bar', baz: 'qux'} %}
{{ vardump(my_array) }}
```

結果は [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#vardump) で確認してください。

### `xss`

文字列の XSS 脆弱性を手動でチェックできます

`xss('this string contains a <script>alert("hello");</script> XSS vulnerability')` -&gt; (結果は [翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions#xss) で確認してください。)


