---
title: カスタム関数
layout: ../../../../layouts/Default.astro
lastmod: '2025-04-19'
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

任意のディエク鳥から、メディアオブジェクトを返します。一度取得すれば、ページと似た方法で画像を操作できます。

`media_directory('theme://images')['some-image.jpg'].cropResize(200,200).html`

### `nicefilesize`

人間に読みやすいファイルサイズを出力します

`nicefilesize(612394)` 

### `nicenumber`

人間に読みやすい数字を出力します

`nicenumber(12430)` 

### `nicetime`

人間に読みやすいフォーマットの日付を返します

`nicetime(page.date)` 

### `nonce_field`

`action` を渡すことで、フォームのセキュリティのための nonce フィールドを生成します：

`nonce_field('action')` 

### `of_type`

変数の型をチェックします：

`of_type(page, 'string')` 

### `pathinfo`

パスを配列にパースします。

```twig
{% set parts = pathinfo('/www/htdocs/inc/lib.inc.php') %}
{{ print_r(parts) }}
```

### `print_r`

読みやすい書式で、変数を表示します

`print_r(page.header)`

### `random_string`

渡された文字数分の、ランダムな文字列を生成します。ユニークなIDを作りたいときに便利です。

`random_string(10)` 

### `unique_id`

接頭辞や接尾辞付きで、文字数分のランダムな文字列を作ります。PHP組み込みの `uniqid` 関数や、`random_string` カスタム関数と違い、この文字列は真にユニークで、コンフリクトしません。


`unique_id(9)`  
`unique_id(11, { prefix: 'user_' })`  
`unique_id(13, { suffix: '.json' })` 

### `range`

範囲内の要素を持つ配列を生成します。要素間の差も決められます

`range(25, 300, 50)` 

### `read_file`

ファイルのパスをもとに、ファイルを読み込み、それを出力します。

`read_file('plugins://admin/README.md')|markdown`

```markdown
# Grav Standard Administration Panel Plugin

This **admin plugin** for [Grav](https://github.com/getgrav/grav) is an HTML user interface that provides a convenient way to configure Grav and easily create and modify pages...
```


### `redirect_me`

選んだURLにリダイレクトします

`redirect_me('http://google.com', 304)`

### `regex_filter`

PHPの `preg_grep` 関数のように、正規表現パターンに合う配列を返します

`regex_filter(['pasta', 'fish', 'steak', 'potatoes'], "/p.*/")`


### `regex_replace`

PHPの [`preg_replace`](https://www.php.net/manual/ja/function.preg-replace.php) 関数のように、複雑な正規表現でテキストを書き換えます：

`regex_replace('The quick brown fox jumps over the lazy dog.', ['/quick/','/brown/','/fox/','/dog/'], ['slow','black','bear','turtle'])`

### `regex_match`

PHPの [`preg_match`](https://www.php.net/manual/ja/function.preg-match.php) 関数のように、複雑な正規表現にマッチするか調べられます。

`regex_match('http://www.php.net/index.html', '@^(?:http://)?([^/]+)@i')`

### `regex_split`

PHPの [`preg_split`](https://www.php.net/manual/ja/function.preg-split.php) 関数のように、正規表現で文字列を分割できます。

`regex_split('hypertext language, programming', '/\\s*,\\s*/u')`

### `repeat`

引数に渡されたものを、その回数分繰り返します。

`repeat('blah ', 10)` 

### `string`

値から文字列を返します。配列が渡されると、JSONにエンコードしたものが返ります

`string(23)` => **"23"**

`string(['test' => 'x'])` => **{"test":"x"}**

### `svg_image`

必要なクラスを付けて、SVG画像を返します。ページに直接コードを書くことなく、インラインのSVGの利益が得られます。ソーシャルメディアアイコンのような、再利用可能な画像のとき便利です。

`{{ svg_image(path, classes, strip_style) }}`


たとえば：

`{{ svg_image('theme://images/something.svg', 'my-class-here mb-10', true) }}`



### `theme_var`

`theme_var($variable, $default = null, $page = null)`

ページのフロントマターからテーマ変数を取得します。もし見つからない場合は、その親（先祖）や、テーマの設定ファイルや、デフォルト値が決められていればそれを取得します。

`theme_var('grid-size')`

上記の場合、まず`page.header.grid-size` を探します。無ければ、親フォルダのそれを探します。まだ見つからなければ、テーマの設定ファイルから、`theme.grid-size` を探します。

見つからなかった時のための、デフォルト値を渡すこともできます：

`theme_var('grid-size', 1024)`

### `t`


[`|t`](../02.filters/#t) フィルタのように、文字列を翻訳します。

`t('SITE_NAME')` 

### `ta`

[`|ta`](../02.filters/#ta) フィルタと同じことをする関数です。

### `tl`

文字列を特定の言語に翻訳します。詳しくは、[multi-language documentation](../../02.content/11.multi-language/#complex-translations) を参照してください。

`tl('SIMPLE_TEXT', ['fr'])`

### `url`

URLを作り、PHP URLストリームを適切なHTMLに変換します。URLとして解決できなかったときのため、デフォルト値を渡すこともできます。

`url('theme://images/logo.png')|default('http://www.placehold.it/150x100/f4f4f4')` 

### `vardump`

現在の変数を画面に表示します（`dump` のように、デバッガでなくても動きます）

```twig
{% set my_array = {foo: 'bar', baz: 'qux'} %}
{{ vardump(my_array) }}
```

### `xss`

文字列のXSS脆弱性を手動でチェックできます

`xss('this string contains a <script>alert("hello");</script> XSS vulnerability')` 

