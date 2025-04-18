---
title: カスタム関数
layout: ../../../../layouts/Default.astro
---

> [!訳注]  
> このページの内容は、Twigの関数を動的に実行している部分があり、静的サイトでは再現できません。実行結果は、[翻訳元](https://learn.getgrav.org/themes/twig-tags-filters-functions/functions)をご確認ください。

Twig関数は、カッコ内にパラメータを渡して、ダイレクトに呼び出せます。

### `array`

値を配列型にします。

```twig
{% set value = array(value) %}
```

### `array_diff`

配列の差分を計算します。

```twig
{% set diff = array_diff(array1, array2...) %}
```

### `array_key_value`

キー/バリューのペアを配列に追加します。

```twig
{% set my_array = {fruit: 'apple'} %}
{% set my_array = array_key_value('meat','steak', my_array) %}
{{ print_r(my_array)}}
```

### `array_key_exists`

PHPの `array_key_exists` 関数と同じです。配列にキーが存在するかどうかを判断します

```twig
{% set my_array = {fruit: 'apple', meat: 'steak'} %}
{{ array_key_exists('meat', my_array) }}
```

### `array_intersect`

2つの配列もしくはGrav collectionsの間の共通部分を調べます

```twig
{% set array_1 = {fruit: 'apple', meat: 'steak'} %}
{% set array_2 = {fish: 'tuna', meat: 'steak'} %}
{{ print_r(array_intersect(array_1, array_2)) }}
```

### `array_unique`

PHPの `array_unique` 関数と同じです。配列から重複を除きます。

`array_unique(['foo', 'bar', 'foo', 'baz'])` 

### `authorize`

そのリソースを見られるかどうか認証します。引数には、1つのstring型のパーミッション文字列か、複数のstring型のパーミッション文字列のみを要素に持つ配列を渡してください。

`authorize(['admin.statistics', 'admin.super'])`

### `body_class`

クラス名の配列を渡してください。`body_classes` に設定されていなければ、現在のテーマ設定にセットされているか調べます。

`set body_classes = body_class(['header-fixed', 'header-animated', 'header-dark', 'header-transparent', 'sticky-footer'])`

### `cron`

cronの構文から、"Cron" オブジェクトを作ります

`cron("3 * * * *").getNextRunDate()|date(config.date_format.default)`



### `dump`

Twig変数を渡すと、[Gravデバッグパネル](../../../08.advanced/03.debugging/) に出力します。デバッガは、**enabled** になっていなければいけません。

`dump(page.header)`

### `debug`

`dump()` と同じです。

### `evaluate`

文字列をTwigとして評価します。

`evaluate('grav.language.getLanguage')`

### `evaluate_twig`

evaluateに似ていますが、Twigとして評価した後に処理します。

`evaluate_twig('This is a twig variable: {{ foo }}', {foo: 'bar'})`  

### `exif`

filepathで渡した画像から、EXIFデータを出力します。これを実行するには、`system.yaml` で `media: auto_metadata_exif: true` が設定されている必要があります。たとえば、Twigテンプレート上で以下のようにします：

```twig
{% set image = page.media['sample-image.jpg'] %}
{% set exif = exif(image.filepath, true) %}
{{ exif.MaxApertureValue }}
```

これは、`MaxApertureValue` が出力されます。これはカメラに設定されている値で、たとえば"40/10"です。いつでも`{{ dump(exif) }}` を使うことで、デバッガ利用できるデータはすべて見られます。

### `get_cookie`

cookieを取得します：

`get_cookie('your_cookie_key')`

### `get_type`

変数の型を取得します：

`get_type(page)` 

### `gist`

Takes a Github Gist ID and creates appropriate Gist embed code

`gist('bc448ff158df4bc56217')` 

### `header_var`

`header_var($variable, $pages = null)`

`page.header.<variable>` を返します。

> [!Note]  
> **NOTE:** Grav 1.7 から非推奨になりました。 `theme_var` を使ってください。

> [!Note]  
> The logic of finding the variable has changed, which might lead to unexptected results:  
> - If an array of lookup pages is provided as second parameter, only the first page will be used.
> - If `<variable>` is not defined in het header of the page, Grav will search for the variable in the tree of parents of the page.
> - If still not found, Grav will search for the variable in the config file of the theme

Given frontmatter of

```
---
title: Home
---
```

`header_var('title')` 

### `http_response_code`

レスポンスコードを渡すと、そのステータスコードを返します。渡さない場合、現在のステータスコードが返ります。webサーバ環境では、両方とも、デフォルトは200です。

`http_response_code(404)`

### `isajaxrequest`

`HTTP_X_REQUESTED_WITH` ヘッダが設定されているかチェックします。 


### `json_decode`

JSONがシンプルにデコードできます：

`json_decode({"first_name": "Guido", "last_name":"Rossum"})`

### `media_directory`


Returns a media object for an arbitrary directory.  Once obtained you can manipulate images in a similar fashion to pages.

`media_directory('theme://images')['some-image.jpg'].cropResize(200,200).html`

### `nicefilesize`

Output a file size in a human readable nice size format

`nicefilesize(612394)` 

### `nicenumber`

Output a number in a human readable nice number format

`nicenumber(12430)` 

### `nicetime`

Output a date in a human readable nice time format

`nicetime(page.date)` 

### `nonce_field`

Generate a Grav security nonce field for a form with a required `action`:

`nonce_field('action')` 

### `of_type`

Checks the type of a variable to the param:

`of_type(page, 'string')` 

### `pathinfo`

Parses a path into an array.

```twig
{% set parts = pathinfo('/www/htdocs/inc/lib.inc.php') %}
{{ print_r(parts) }}
```

### `print_r`

Prints a variable in a readable format

`print_r(page.header)`

### `random_string`

Will generate a random string of the required number of characters.  Particularly useful in creating a unique id or key.

`random_string(10)` 

### `unique_id`

Generates a random string with configurable length, prefix and suffix. Unlike the built-in PHP `uniqid()` function and the `random_string` utils, this string will be generated truly unique and non-conflicting.


`unique_id(9)`  
`unique_id(11, { prefix: 'user_' })`  
`unique_id(13, { suffix: '.json' })` 

### `range`

Generates an array containing a range of elements, optionally stepped

`range(25, 300, 50)` 

### `read_file`

Simple function to read a file based on a filepath and output it.

`read_file('plugins://admin/README.md')|markdown`

```markdown
# Grav Standard Administration Panel Plugin

This **admin plugin** for [Grav](https://github.com/getgrav/grav) is an HTML user interface that provides a convenient way to configure Grav and easily create and modify pages...
```


### `redirect_me`

Redirects to a URL of your choosing

`redirect_me('http://google.com', 304)`

### `regex_filter`

Performs a `preg_grep` on an array with a regex pattern

`regex_filter(['pasta', 'fish', 'steak', 'potatoes'], "/p.*/")`


### `regex_replace`

A helpful wrapper for the PHP [preg_replace()](https://php.net/manual/en/function.preg-replace.php) method, you can perform complex Regex replacements on text via this filter:

`regex_replace('The quick brown fox jumps over the lazy dog.', ['/quick/','/brown/','/fox/','/dog/'], ['slow','black','bear','turtle'])`

### `regex_match`

A helpful wrapper for the PHP [preg_match()](https://php.net/manual/en/function.preg-match.php) method, you can perform complex regular expression match on text via this filter:

`regex_match('http://www.php.net/index.html', '@^(?:http://)?([^/]+)@i')`

### `regex_split`

A helpful wrapper for the PHP [preg_split()](https://php.net/manual/en/function.preg-split.php) method. Split string by a regular expression on text via this filter:

`regex_split('hypertext language, programming', '/\\s*,\\s*/u')`

### `repeat`

Will repeat whatever is passed in a certain amount of times.

`repeat('blah ', 10)` 

### `string`

Returns a string from a value. If the value is array, return it json encoded

`string(23)` => **"23"**

`string(['test' => 'x'])` => **{"test":"x"}**

### `svg_image`

Returns the content of an SVG image and adds extra classes as needed. Provides the benefits of inline svg without having to paste the code directly on the page. Useful for reusable images such as social media icons.

`{{ svg_image(path, classes, strip_style) }}`


example:

`{{ svg_image('theme://images/something.svg', 'my-class-here mb-10', true) }}`



### `theme_var`
`theme_var($variable, $default = null, $page = null)`

Get a theme variable from the page's header, or, if not found, from its parent(s), the theme's config file, or the default value if provided:

`theme_var('grid-size')`

This will first try `page.header.grid-size`, if not set, it will traverse the tree of parents. If still not found, it will try `theme.grid-size` from the theme's configuration file.

It can optionally take a default value as fallback:

`theme_var('grid-size', 1024)`

### `t`

Translate a string, as the [`|t`](../02.filters/#t) filter.

`t('SITE_NAME')` 

### `ta`

Functions the same way the [`|ta`](../02.filters/#ta) filter does.

### `tl`

Translates a string in a specific language. For more details check out the [multi-language documentation](../../02.content/11.multi-language/#complex-translations).

`tl('SIMPLE_TEXT', ['fr'])`

### `url`

Will create a URL and convert any PHP URL streams into a valid HTML resources. A default value can be passed in in case the URL cannot be resolved.

`url('theme://images/logo.png')|default('http://www.placehold.it/150x100/f4f4f4')` 

### `vardump`

The `vardump()` function outputs the current variable to the screen (rather than in the debugger as with `dump()`)

```twig
{% set my_array = {foo: 'bar', baz: 'qux'} %}
{{ vardump(my_array) }}
```

### `xss`

Allow a manual check of a string for XSS vulnerabilities

`xss('this string contains a <script>alert("hello");</script> XSS vulnerability')` 

