---
title: メディア
layout: ../../../layouts/Default.astro
lastmod: '2025-07-31'
---

> [!訳注]  
> このページでは、ファイルの動的な処理をするため、静的サイトでは再現できない部分があります。また、動画などの容量が大きく、このGitHub Pages上では削除しています。メディア編集による Grav の機能については、 [翻訳元](https://learn.getgrav.org/content/media) にてご確認ください。

コンテンツを作るとき、**画像** や、 **動画** や、他の種類の **ファイル** を表示することもあります。  
Grav は、これらのファイルも自動で探し出し、処理して、どんなページからでも使えるようにします。  
ページにあらかじめ搭載された機能として、サムネイル画像を使ったり、メタデータにアクセスしたり、メディアを動的に修正したり（例：画像のリサイズ、動画の表示サイズの設定など）と、必要なことを必要なとおりにできるので、とても便利です。

Grav では、 **スマートキャッシュ** システムが使われており、動的に生成されたメディアは、必要なときに自動でキャッシュされます。  
これにより、以降のリクエストでは、再度動的に生成することなく、キャッシュされたバージョンを利用できます。

<h2 id="supported-media-files">サポート対象のメディアファイル</h2>

以下のメディアファイルのタイプが、 Grav が始めからサポートする対象です。  
追加のサポートは、プラグインを通じて行われます。

| メディアタイプ | ファイルタイプ |
| :-----  | :-----   |
| 画像  | jpg, jpeg, png  |
| 音声  | mp3, wav, wma, ogg, m4a, aiff, aif  |
| アニメーション画像   | gif  |
| ベクトル画像   | svg  |
| ビデオ     | mp4, mov, m4v, swf, flv, webm, ogv      |
| データ / 情報 | txt, doc, docx, html, htm, pdf, zip, gz, 7z, tar, css, js, json, xml, xls, xlt, xlm, xlsm, xld, xla, xlc, xlw, xll, ppt, pps, rtf, bmp, tiff, mpeg, mpg, mpe, avi, wmv |

サポート対象の mime タイプの全リストは、 `system/config/media.yaml` にあります。  
もしまだサポートされていない mime タイプがあれば、あなた自身で `user/config/media.yaml` を作り、そこに書き加えてください。  
オリジナルの media.yaml と同じ書式となるように注意してください。  
最もシンプルな方法は、元のファイルをすべてコピーして、それに書き加える方法です。

<h2 id="where-to-put-your-media-files">メディアファイルを置く場所</h2>

通常は、メディアファイルはページ内で使うでしょうから、ページのフォルダに置き、そしてページのマークダウンから参照すれば良いです。  
以下のように：

```markdown
![my image](image.jpg)
```

ひとつのフォルダで画像を管理したい場合は、`user/pages/images` フォルダを作り、そこに置くこともできます。  
この方法では、 twig は、次のようにして画像にアクセスできます

```twig
{% set my_image = page.find('/images').media['my-image.jpg'] %}
```

そして、マークダウンからも、簡単にメディアを見つけることができ、処理できます：

```markdown
![my image](/images/my-image.jpg?cropResize=300,300)
```

他方で、テーマファイルに置くこともできます。  
そうすると、 CSS で簡単にアクセスできますし、マークダウンファイルからは、`theme://` ストリームから利用できます：

```markdown
![my image](theme://images/theme-image.jpg)
```

別の選択肢としては、 `user/image` フォルダというのもあります。  
`image://` ストリームからアクセスできます：

```markdown
![my image](image://my-image.jpg)
```

実際は、 `user/` フォルダ内にあるあらゆるストリームデータは、`user://` ストリームを通して利用可能です：

```markdown
![my image](user://themes/mytheme/images/my-image.jpg)
```

Twig の `Media` オブジェクトにより、同じことができます：

```twig
{{ media['user://themes/mytheme/images/my-image.jpg'].html()|raw }}
```

> [!Warning]  
> Grav には、ルート（一番上のフォルダ）に、`/images` フォルダがあります。しかしこのフォルダには画像を入れないでください。ここは、 Grav が自動で生成したキャッシュ画像を入れる場所だからです。

また、すべてのメディアファイルをそれぞれ自身のフォルダに入れたいと思うかもしれません。  
その場合、ファイルは一度にアクセスすることができます。  
たとえば、すべての MP3 ファイルを、（ visible でない） `user/pages/mp3s` というひとつのフォルダで管理するとします。  
そして、あるページで、そのページに関係する MP3 ファイルの名前を、そのページのフロントマターに `thistrack` というフィールドで定義したとします。  
すると、 HTML5 のオーディオ要素でアクセスするには、次のようなコードを書いてください：

```twig
<audio controls>
  <source src="{{ page.find('/mp3s').media[page.header.thistrack~'.mp3']|e }}">
</audio>
```

<h2 id="display-modes">ディスプレイ・モード</h2>

Grav には、すべてのメディアオブジェクトに対して、いくつかの異なるディスプレイモードがあります。

| モード    | 説明  |
| :-----    | :----- |
| source    | メディア自身のビジュアルで表現されます。 例： 画像、video ファイル |
| text      | メディアのテキスト表現  |
| thumbnail | そのメディアオブジェクトのサムネイル画像 |

> [!Warning]  
> **Data / Information** タイプのメディアは、`source` モードに対応していません。他の設定がなければ、基本的に、`text` モードです。

<h2 id="thumbnail-location">サムネイルの場所</h2>

Grav では、3つのサムネイル画像の保存場所があります。

1. メディアファイルと同じフォルダ： `[media-name].[media-extension].thumb.[thumb-extension]` ここでの `media-name` と `media-extension` は、それぞれオリジナルのメディアファイルの名前と拡張子であり、 `thumb-extension` は、`image` メディアタイプがサポートする拡張子です。たとえば、 `my_video.mp4.thumb.jpg` や、 `my-image.jpg.thumb.png` などです。 **画像のみです！** 画像それ自体が、サムネイルとして使われます。
2. ユーザーフォルダ： `user/images/media/thumb-[media-extension].png` ここでの `media-extension` は、オリジナルのメディアファイルの拡張子です。 たとえば、 `thumb-mp4.png` や、 `thumb-jpg.jpg` です。
3. システムフォルダ： `system/images/media/thumb-[media-extension].png` ここでの `media-extension` は、オリジナルのメディアファイルの拡張子です。 **system フォルダ内のサムネイルは、Grav が準備するものです。**

> [!Info]  
> 以下に説明するような方法で、手動でサムネイルを選ぶこともできます。

<h2 id="links-and-lightboxes">リンクと lightbox</h2>

上記のディスプレイ・モードは、リンクと lightbox でも使えます。  
詳細は後ほど説明しますが、さしあたり重要な点は：

> [!Warning]  
> Grav は、初期状態では lightbox 機能を提供しません。プラグインが必要です。 [FeatherLight Grav plugin](https://github.com/getgrav/grav-plugin-featherlight) を使ってください。

> [!訳注]  
> 上記の FeatherLight プラグインは、もう何年も開発が止まっているようなので、使わない方が良いかもしれません。代替プラグインなどについては、 GitHub issue などを読んでください。

lightbox をレンダリングするために Grav のメディア機能を使う場合、 Grav がやるのは、**アンカー** タグの出力です。  
そのタグは、 lightbox プラグインが読めるようにするためのいくつかの属性を持ちます。  
わたしたちのプラグインリポジトリのものではない lightbox ライブラリの使用に興味があり、自身のプラグインを作成したい場合は、以下の表を参照して使ってください。

| 属性   | 説明  |
| :----- | :----- |
| rel    | 通常のリンクではなく、lightbox のリンクであることを示すシンプルな指標です。その値は、常に `lightbox` です。 |
| href   | メディアオブジェクトそのものへの URL |
| data-width  | この lightbox に望まれる幅 |
| data-height | この lightbox に望まれる高さ |
| data-srcset | 画像メディアの場合に、 `srcset` の文字列をここに入力できます（ [more info](#responsive-images) ） |

<h2 id="actions">アクション</h2>

Grav は、メディアを制御するとき、 **builder-pattern** を使います。  
このため、特定のメディアに、**複数のアクション** を実行できます。  
いくつかのアクションは、すべての種類のメディアで利用可能であり、その他は特定のメディアでのみ使えます。

<h3 id="general">一般</h3>

すべてのメディアで使えるアクションです。

<h4 id="url">url</h4>

> [!Info]  
> このメソッドは、**Twig** テンプレートでのみ使われることを想定しています。そのため、マークダウンでは使えません。

そのメディアへの **生のURL パス** 返します。

```twig
{{ page.media['sample-image.jpg'].url|e }}
```

実行結果は <a href="https://learn.getgrav.org/content/media#url">翻訳元</a> を見てください

#### html

> [!Info]  
> マークダウンでは、このメソッドは `![]` 構文を使って、暗黙的に呼び出されます。

`html` アクションは、現在の表示モードをもとに、メディアの妥当な HTML タグを出力します。

```markdown
![Some ALT text](sample-image.jpg?classes=myclass "My title")
```
```twig
{{ page.media['sample-image.jpg'].html('My title', 'Some ALT text', 'myclass')|raw }}
```

実行結果は <a href="https://learn.getgrav.org/content/media#html">翻訳元</a> を見てください

#### display

このアクションを使うと、 Grav の提供するさまざまなディスプレイモードを切り替えられます。  
ディスプレイモードを切り替えると、すべてのこれまでのアクションがリセットされます。  
このルールの例外は、 `lightbox` と `link` アクションと、これら2つよりも前に使用されたアクションです。

たとえば、 `page.media['sample-image.jpg'].sepia().display('thumbnail').html()` から呼び出されたサムネイルは、 `sepia()` アクションが適用されませんが、 `page.media['sample-image.jpg'].display('thumbnail').sepia().html()` とすれば適用されます。

> [!Note]  
> サムネイルモードに切り替えると、画像処理されます。これはつまり、ビデオコンテンツであっても、サムネイルに対して画像タイプのアクションが利用できるということです。

#### link

メディアオブジェクトをリンクにします。  
`link()` よりも前に呼び出したあらゆるアクションは、リンクのターゲットに適用される一方で、後に呼び出したアクションは、ページで表示されるものに適用されます。

> [!Info]  
> `link()` を呼び出した後、 Grav は自動的に **サムネイル** ディスプレイモードに切り替えます。

以下の具体例では、 `sample-image.jpg` ファイルのセピアバージョンへのテキストリンク (`display('text')`) を表示します：

```markdown
![Image link](sample-image.jpg?sepia&link&display=text)
```

```twig
{{ page.media['sample-image.jpg'].sepia().link().display('text').html('Image link')|raw }}
```

実行結果は <a href="https://learn.getgrav.org/content/media#link">翻訳元</a> を見てください

> [!訳注]  
> 説明文では、テキストリンクになるとのことですが、2025年7月31日確認時点での翻訳元の実行結果は画像リンクになっているようなので、説明が間違っているのか、この機能が正しく実装されていないのかは不明です。

#### Cache only

Grav は、すべての画像ファイルをキャッシュする設定になっており、これによりファイルの提供スピードが高速化されます。  
しかし、画像が Grav 画像処理システムで処理されると、このシステムは以前最適化済みの画像サイズをかなり大きくして読み込むかもしれません。  
画像処理はショートカットできます。

`system/config/system.yaml` の `cache_all` を有効化してください

> [!訳注]  
> ここの説明文は、無効化してください (Disable ...) の間違いかもしれません。

```yaml
images:
  default_image_quality: 85
  cache_all: false
```

`cache` オプションについて画像処理が無効化されます。

```markdown
![](sample-image.jpg?cache)
```

```twig
{{ page.media['sample-image.jpg'].cache.html()|raw }}
```

実行結果は <a href="https://learn.getgrav.org/content/media#cache-only">翻訳元</a> を見てください

#### lightbox

lightbox アクションは、本質的に link アクションと同じですが、いくつか追加事項があります。  
[上記の解説](#links-and-lightboxes) にもあるように、 lightbox アクションは、 link 作成に追加の属性があるというだけです。  
link アクションとの違いは、 `rel="lightbox"` 属性が追加されることと、 `width` `hight` 属性を受け入れるということです。

もし可能なら（現在は画像の場合のみですが）、 Grav はリクエストされた幅と高さにメディアをリサイズします。  
そうでなければ、単に `data-width` と `data-height` 属性をリンクに追加します。

```markdown
![Sample Image](sample-image.jpg?lightbox=600,400&resize=200,200)
```

```twig
{{ page.media['sample-image.jpg'].lightbox(600,400).resize(200,200).html('Sample Image')|raw }}
```

実行結果は <a href="https://learn.getgrav.org/content/media#lightbox">翻訳元</a> を見てください

<h5 id="result">結果</h5>

> [!訳注]  
> 結果を削除しているため、動作確認は [翻訳元](https://learn.getgrav.org/content/media#lightbox) でしてください

#### thumbnail

Grav で利用されるサムネイルを手動で選べます。  
すべてのタイプのメディアについて、 `page` と `default` から選べます。画像メディアについては `media` も選べ、その画像自体をサムネイルとして使えます。

```markdown
![Sample Image](sample-image.jpg?thumbnail=default&display=thumbnail)
```

```twig
{{ page.media['sample-image.jpg'].thumbnail('default').display('thumbnail').html('Sample Image')|raw }}
```

```html
{{ page.media['sample-image.jpg'].thumbnail('default').display('thumbnail').html('Sample Image')|e }}
```

##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#thumbnail) で確認してください

#### attribute

これは、出力に HTML 属性を追加できます。

```markdown
![Sample Image](sample-image.jpg?attribute=myattribute,myvalue)
```

```twig
{{ page.media['sample-image.jpg'].attribute('myattribute', 'myvalue').html('Sample Image')|raw }}
```

```html
{{ page.media['sample-image.jpg'].attribute('myattribute', 'myvalue').html('Sample Image')|e }}
```


<h2 id="image-actions">画像のアクション</h2>

#### resize

Resizing does exactly what you would expect it to do.  `resize` lets you create a new image based on the `width` and the `height`.  The aspect ratio is maintained and the new image will contain blank areas in the color of the **optional** background color provided as a `hex value`, e.g. `0xffffff`. The background parameter is optional, and if not provided will default to **transparent** if the image is a PNG, or **white** if it is a JPEG.

```markdown
![Sample Image](sample-image.jpg?resize=400,200)
```

```twig
{{ page.media['sample-image.jpg'].resize(400, 200).html()|raw }}
```

##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#resize) で確認してください

#### forceResize

Resizes the image to the `width` and `height` as provided.  `forceResize` will not respect original aspect-ratio and will stretch the image as needed to fit the new image size.

```markdown
![Sample Image](sample-image.jpg?forceResize=200,300)
```

```twig
{{ page.media['sample-image.jpg'].forceResize(200, 300).html()|raw }}
```

##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#forceresize) で確認してください

#### cropResize

`cropResize` resizes an image to a smaller or larger size based on the `width` and the `height`.  The aspect ratio is maintained and the new image will be resized to fit in the bounding-box as described by the `width` and `height` provided. In other words, any background area you would see in a regular `resize` is cropped.

For example, if you have an image that is `640` x `480` and you perform a `cropResize(100, 100)` action upon it, you will end up with an image that is `100` x `75`.



```markdown
![Sample Image](sample-image.jpg?cropResize=300,300)
```


```twig
{{ page.media['sample-image.jpg'].cropResize(300, 300).html()|raw }}
```



##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#cropresize) で確認してください

#### crop

`crop` will not resize the image at all, it will merely crop the original image so that only the portion of the bounding box as described by the `width` and the `height` originating from the `x` and `y` location is used to create the new image.

For example, an image that is `640` x `480` with `crop(0, 0, 400, 100)` will produce an image with a width of `400` and a height of `100` originating from the top-left corner as described by `0, 0`.



```markdown
![Sample Image](sample-image.jpg?crop=100,100,300,200)
```


```twig
{{ page.media['sample-image.jpg'].crop(100,100,300,200).html()|raw }}
```



##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#crop) で確認してください

#### cropZoom

Similar to regular `cropResize`, `cropZoom` also takes a `width` and a `height` but will **resize and crop** the image to ensure the resulting image is the exact size you requested.  The aspect ratio is maintained but parts of the image may be cropped, however the resulting image is centered.

> [!Info]  
> The primary difference between **cropResize** and **cropZoom** is that in cropResize, the image is resized maintaining aspect ratio so that the entire image is shown, and any extra space is considered background.

With **cropZoom**, the image is resized so that there is no background visible, and the extra image area of the image outside of the new image size is cropped.

For example if you have an image that is `640` x `480` and you perform a `cropZoom(400, 100)` action, the resulting image will be resized to `400` x `300` and then the height is cropped resulting in a `400` x `100` image.



```markdown
![Sample Image](sample-image.jpg?cropZoom=600,200)
```


```twig
{{ page.media['sample-image.jpg'].cropZoom(600,200).html()|raw }}
```



> [!Info]  
> Folks familiar with using `zoomCrop` for this purpose will find that it also works in Grav.

##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#cropzoom) で確認してください

#### quality

Dynamically allows the setting of a **compression percentage** `value` for the image between `0` and `100`. A lower number means less quality, where `100` means maximum quality.



```markdown
![Sample Image](sample-image.jpg?cropZoom=300,200&quality=25)
```


```twig
{{ page.media['sample-image.jpg'].cropZoom(300,200).quality(25).html()|raw }}
```



##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#quality) で確認してください

#### negate

Applies a **negative filter** to the image where colors are inverted.



```markdown
![Sample Image](sample-image.jpg?cropZoom=300,200&negate)
```


```twig
{{ page.media['sample-image.jpg'].cropZoom(300,200).negate.html()|raw }}
```



##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#negate) で確認してください

#### brightness

Applies a **brightness filter** to the image with a `value` from `-255` to `+255`. Larger negative numbers will make the image darker, while larger positive numbers will make the image brighter.



```markdown
![Sample Image](sample-image.jpg?cropZoom=300,200&brightness=-100)
```


```twig
{{ page.media['sample-image.jpg'].cropZoom(300,200).brightness(-100).html()|raw }}
```



##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#brightness) で確認してください

#### contrast

This applies a **contrast filter** to the image with a `value` from `-100` to `+100`. Larger negative numbers will increase the contrast, while larger positive numbers will reduce the contrast.



```markdown
![Sample Image](sample-image.jpg?cropZoom=300,200&contrast=-50)
```


```twig
{{ page.media['sample-image.jpg'].cropZoom(300,200).contrast(-50).html()|raw }}
```



> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#contrast) で確認してください

#### grayscale

This processes the image with a **grayscale filter**.



```markdown
![Sample Image](sample-image.jpg?cropZoom=300,200&grayscale)
```


```twig
{{ page.media['sample-image.jpg'].cropZoom(300,200).grayscale.html()|raw }}
```



##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#grayscale) で確認してください

#### emboss

This processes the image with an **embossing filter**.



```markdown
![Sample Image](sample-image.jpg?cropZoom=300,200&emboss)
```


```twig
{{ page.media['sample-image.jpg'].cropZoom(300,200).emboss.html()|raw }}
```



##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#emboss) で確認してください

#### smooth

This applies a **smoothing filter** to the image based on smooth `value` setting from `-10` to `10`.



```markdown
![Sample Image](sample-image.jpg?cropZoom=300,200&smooth=5)
```


```twig
{{ page.media['sample-image.jpg'].cropZoom(300,200).smooth(5).html()|raw }}
```



##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#smooth) で確認してください

#### sharp

This applies a **sharpening filter** on the image.



```markdown
![Sample Image](sample-image.jpg?cropZoom=300,200&sharp)
```


```twig
{{ page.media['sample-image.jpg'].cropZoom(300,200).sharp.html()|raw }}
```



##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#sharp) で確認してください

#### edge

This applies an **edge finding filter** on the image.



```markdown
![Sample Image](sample-image.jpg?cropZoom=300,200&edge)
```


```twig
{{ page.media['sample-image.jpg'].cropZoom(300,200).edge.html()|raw }}
```



##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#edge) で確認してください

#### colorize

You can colorize the image based on adjusting the `red`, `green`, and `blue` values for the image from `-255` to `+255` for each color.



```markdown
![Sample Image](sample-image.jpg?cropZoom=300,200&colorize=100,-100,40)
```


```twig
{{ page.media['sample-image.jpg'].cropZoom(300,200).colorize(100,-100,40).html()|raw }}
```
##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#colorize) で確認してください

#### sepia

This applies a **sepia filter** on the image to produce a vintage look.

```markdown
![Sample Image](sample-image.jpg?cropZoom=300,200&sepia)
```


```twig
{{ page.media['sample-image.jpg'].cropZoom(300,200).sepia.html()|raw }}
```

##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#sepia) で確認してください

#### gaussianBlur

**blurs** the image by an Factor, that defines how often the blur filter is applied to the image. Default is 1 time.



```markdown
![Sample Image](sample-image.jpg?gaussianBlur=3)
```


```twig
{{ page.media['sample-image.jpg'].gaussianBlur(3).html()|raw }}
```



##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#gaussianblur) で確認してください

#### rotate

**rotates** the image by `angle` degrees counterclockwise, negative values rotate clockwise.



```markdown
![Sample Image](sample-image.jpg?cropZoom=300,200&rotate=-90)
```


```twig
{{ page.media['sample-image.jpg'].cropZoom(300,200).rotate(-90).html()|raw }}
```



##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#rotate) で確認してください

#### flip

**flips** the image in the given directions. Both params can be `0|1`.  Both `0` is equivalent to no flipping in either direction.



```markdown
![Sample Image](sample-image.jpg?cropZoom=300,200&flip=0,1)
```


```twig
{{ page.media['sample-image.jpg'].cropZoom(300,200).flip(0,1).html()|raw }}
```



##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#flip) で確認してください

#### fixOrientation

Fixes the orientation of the image when rotation is made via EXIF data (applies to jpeg images taken with phones and cameras).



```markdown
![Sample Image](sample-image.jpg?fixOrientation)
```


```twig
{{ page.media['sample-image.jpg'].fixOrientation().html()|raw }}
```



#### watermark

The **watermark action** merges two images, a watermark image and a source image, into a final watermarked image. This is a very specific action that needs a more detailed description than other actions or filters. In particular, the specific behavior when [combining filters](#combinations) must be taken into account. For those interested, there is a very detailed [blog post about the watermark action](https://www.grav.cz/blog/vodoznak-aneb-nepokrades-kelisova), written by [Vít Petira](https://github.com/petira), but only in Czech. However, the instructions are easy to understand.

> [!Note]  
> If you are using a page-level [stream](/content/image-linking#php-streams), then page prefixes must also be specified.



```markdown
![Sample Image](sample-image.jpg?watermark=user://pages/02.content/07.media/sample-watermark.png,top-left,50)
```


```twig
{{ page.media['sample-image.jpg'].watermark('user://pages/02.content/07.media/sample-watermark.png','top-left',50).html()|raw }}
```



##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#watermark) で確認してください

#### loading

The loading attributing on images gives authors control over when the browser should start loading the resource. The value for the loading attribute can be one of `auto` (default), `lazy`, `eager`.
Value can be set in `system.images.defaults.loading` as default value, or per md image with `?loading=lazy`
When value `auto` is chosen, no `loading` attribute is added and browser will determine which strategy to use.



```markdown
![Sample Image](sample-image.jpg?loading=lazy)
```


```twig
{# Using default value as defined in 'config.system.images.defaults.loading' #}
{{ page.media['sample-image.jpg'].loading.html('Sample Image')|raw }}

{# Using explicit value #}
{{ page.media['sample-image.jpg'].loading('lazy').html('Sample Image')|raw }}
```


```html
<img loading="lazy" title="Sample Image"  src="/images/e/f/1/0/5/ef10554cd3a99f2e65136e79dce170d4f8a7a1b9-sample-image.jpg" />
```



#### decoding

The decoding attributing on images gives authors control over when the browser should start decoding the resource. The value for the decoding attribute can be one of `auto` (default), `sync`, `async`.
Value can be set in `system.images.defaults.decoding` as default value, or per md image with `?decoding=async`
When value `auto` is chosen, no `decoding` attribute is added and browser will determine which strategy to use.



```markdown
![Sample Image](sample-image.jpg?decoding=async)
```


```twig
{# Using default value as defined in `config.system.images.defaults.decoding` #}
{{ page.media['sample-image.jpg'].decoding.html('Sample Image')|raw }}

{# Using explicit value #}
{{ page.media['sample-image.jpg'].decoding('async').html('Sample Image')|raw }}
```


```html
<img decoding="async" title="Sample Image"  src="/images/e/f/1/0/5/ef10554cd3a99f2e65136e79dce170d4f8a7a1b9-sample-image.jpg" />
```



#### fetchpriority

The fetchpriority attributing gives authors control over when the browser should prioritize the fetch of the image relative to other images. The value for the fetchpriority attribute can be one of `auto` (default), `high`, `low`.
Value can be set in `system.images.defaults.fetchpriority` as default value, or per md image with `?fetchpriority=high`
When value `auto` is chosen, no `fetchpriority` attribute is added and browser will determine which strategy to use.



```markdown
![Sample Image](sample-image.jpg?fetchpriority=high)
```


```twig
{# Using default value as defined in `config.system.images.defaults.fetchpriority` #}
{{ page.media['sample-image.jpg'].fetchpriority.html('Sample Image')|raw }}

{# Using explicit value #}
{{ page.media['sample-image.jpg'].fetchpriority('high').html('Sample Image')|raw }}
```


```html
<img fetchpriority="high" title="Sample Image"  src="/images/e/f/1/0/5/ef10554cd3a99f2e65136e79dce170d4f8a7a1b9-sample-image.jpg" />
```



## Animated / Vectorized Actions

#### resize

Because PHP cannot handle dynamically resizing these types of media, the resize action will only make sure that a `width` and `height` or `data-width` and `data-height` attribute are set on your `<img>`/`<video>` or `<a>` tag respectively. This means your image or video will be displayed in the requested size, but the actual image or video file will not be converted in any way.



```markdown
![Sample Trailer](sample-trailer.mov?resize=400,200)
```


```twig
{{ page.media['sample-trailer.mov'].resize(400, 200).html('Sample Trailer')|raw }}
```


```html
{{ page.media['sample-trailer.mov'].resize(400, 200).html('Sample Trailer')|e }}
```




#### examples

Some examples of this:


```markdown
![Sample Vector](sample-vector.svg?resize=300,300)
```

```markdown
![Animated Gif](sample-animated.gif?resize=300,300)
```

```markdown
![Sample Trailer](sample-trailer.mov?resize=400,200)
```
> [!訳注]  
> 事例は、[翻訳元](https://learn.getgrav.org/content/media#animated-vectorized-actions) で確認してください


<h2 id="audio-actions">オーディオのアクション</h2>

Audio media will display an HTML5 audio link:

```markdown
![Hal 9000: I'm Sorry Dave](hal9000.mp3)
```

```twig
{{ page.media['hal9000.mp3'].html()|raw }}
```



##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#audio-actions) で確認してください

#### controls

Allows explicitly setting or removing the HTML5 default controls. Passing `0` hides browser's controls for playback, volume, etc..



```markdown
![Hal 9000: I'm Sorry Dave](hal9000.mp3?controls=0)
```


```twig
{{ page.media['hal9000.mp3'].controls(0)|raw }}
```


```html
{{ page.media['hal9000.mp3'].controls(0)|e }}
```



#### preload

Allows setting of `preload` property, which defaults to `auto`. Permitted params are `auto`, `metadata`, and `none`.

> [!Info]  
> <q cite="https://developer.mozilla.org/en-US/docs/Web/HTML/Element/audio#attr-preload">If not set, its default value is browser-defined (i.e. each browser may have its own default value). The spec advises it to be set to <code>metadata</code>.</q>

> [!Info]  
> The `preload` attribute is ignored if `autoplay` is present.



```markdown
![Hal 9000: I'm Sorry Dave](hal9000.mp3?preload=metadata)
```


```twig
{{ page.media['hal9000.mp3'].preload('metadata')|raw }}
```



#### autoplay

Allows setting whether audio will `autoplay` upon page load. Defaults to `false` by omission if not set.

> [!Info]  
> If `autoplay` and `preload` are both present on a given `audio` element, `preload` will be ignored.



```markdown
![Hal 9000: I'm Sorry Dave](hal9000.mp3?autoplay=1)
```


```twig
{{ page.media['hal9000.mp3'].autoplay(1)|raw }}
```




#### controlsList

Allows setting of `controlsList` property, which takes one or more of three possible values: `nodownload`, `nofullscreen`, and `noremoteplayback`.

> [!Info]  
> If setting more than one parameter in markdown, separate each with a dash (`-`). These will be replaced by spaces in the output HTML.



```markdown
![Hal 9000: I'm Sorry Dave](hal9000.mp3?controlsList=nodownload-nofullscreen-noremoteplayback)
```


```twig
{{ page.media['hal9000.mp3'].controlsList('nodownload nofullscreen noremoteplayback')|raw }}
```



#### muted

Allows setting whether audio is `muted` on load. Defaults to `false` by omission if not set.



```markdown
![Hal 9000: I'm Sorry Dave](hal9000.mp3?muted=1)
```


```twig
{{ page.media['hal9000.mp3'].muted(1)|raw }}
```



#### loop

Allows setting whether audio will `loop` upon playing through completion. Defaults to `false` by omission if not set.



```markdown
![Hal 9000: I'm Sorry Dave](hal9000.mp3?loop=1)
```


```twig
{{ page.media['hal9000.mp3'].loop(1)|raw }}
```



## File Actions

Grav does not provide any custom actions on files at this point in time and there are no plans to add any. Should you think of something, please contact us.



```markdown
[View Text File](acronyms.txt)
```


```twig
<a href="{{ page.media['acronyms.txt'].url()|raw }}">View Text File</a>
```



##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#file-actions) で確認してください

### Combinations

As you can see: Grav provides some powerful image manipulation functionality that makes it really easy to work with images!  The real power comes when you combine multiple effects and produce some very sophisticated dynamic image manipulations.  For example, this is totally valid:



```markdown
![Sample Image](sample-image.jpg?negate&lightbox&cropZoom=200,200)
```


```twig
{{ page.media['sample-image.jpg'].negate.lightbox.cropZoom(200,200)|raw }}
```



##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#combinations) で確認してください

#### Resetting multiple calls to the same image

When you access the same image multiple times in a single page, actions you have provided to the image are not reset by default.  So if you resize an image, and output the HTML, then later in the same page, simply output the image URL, you will also get the URL to the resized image. You were probably expecting the URL to the original image.

To combat this, you can reset the actions on the images by passing `false` to the `url()` method:

```twig
{% for item in page.header.gallery %}
    {% set image = page.media[item.src].cropZoom(800, 600).quality(70) %}
    <a href="{{ image.url(false)|e }}">
      <img src="{{ image.url|e }}" alt="{{ item.alt|e }}" title="{{ item.title|e }}" />
    </a>
{% endfor %}
```

### Responsive images

#### Higher density displays

Grav has built-in support for responsive images for higher density displays (e.g. **Retina** screens). Grav accomplishes this by implementing `srcset` from the [Picture element HTML proposal](https://html.spec.whatwg.org/multipage/embedded-content.html#the-picture-element). A good article to read if you want to understand this better is [this blog post by Eric Portis](http://ericportis.com/posts/2014/srcset-sizes/).

> [!Info]  
> Grav sets the `sizes` argument mentioned in the posts above to full viewport width by default. Use the `sizes` action showcased below to choose yourself.

To start using responsive images, all you need to do is add higher density images to your pages by adding a suffix to the file name. If you only provide higher density images, Grav will automatically generate lower quality versions for you. Naming works as follows: `[image-name]@[density-ratio]x.[image-extension]`, so for example adding `sample-image@3x.jpg` to your page will result in Grav creating a `2x` and a `1x` (regular size) version by default.

> [!Note]  
> These files generated by Grav will be stored in the `images/` cache folder, not your page folder.

Let's assume you have a file called `retina@2x.jpg`, you would actually reference this in your links as `retina.jpg`, and then Grav will not find this image, and start looking for retina image sizes.  It will find `retina@2x.jpg` and then realize it needs to make a `@1x` variant and display the appropriate `srcset` output:



```markdown
![Retina Image](retina.jpg?sizes=80vw)
```


```twig
{{ page.media['retina.jpg'].sizes('80vw').html()|raw }}
```


```html
{{ code_sample|e }}
```



##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#higher-density-displays) で確認してください

> [!Warning]   Depending on your display and your browser's implementation and support for `srcset`, you might never see a difference. We included the HTML markup in the third tab so you can see what's happening behind the screens.

##### Sizes with media queries

Grav also has support for media queries inside the `sizes` attribute, allowing you to use different widths depending on the device's screen size. In contrast to the first method, you don't have to create multiple images; they will get created automatically. The fallback image is the current image, so a browser without support for `srcset`, will display the original image.



```markdown
![Retina Image](retina.jpg?sizes=%28max-width%3A26em%29+100vw%2C+50vw)
```


```twig
{{ page.media['retina.jpg'].sizes('(max-width:26em) 100vw, 50vw').html('Retina Image')|raw }}
```

```html
{{ code_sample|e }}
```



##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#sizes-with-media-queries) で確認してください

> [!Warning]  
> Depending on your display and your browser's implementation and support for `srcset`, you might never see a difference. We included the HTML markup in the fourth tab so you can see what's happening behind the screens.

##### Sizes with media queries using derivatives

If you want to customize the sizes of the automatically created files, you can use the `derivatives()` method (as shown below). The first parameter is the width of the smallest of the generated images. The second is the maximum width (exclusive) of the generated images. The third, and only optional parameter, dictates the intervals with which to generate the photos (default is 200). For example, if you set the first parameter to be `320` and the third to be `100`, Grav will generate an image for 320, 420, 520, 620, and so on until it reaches its set maximum.

In our example, we set the maximum to `1600`. This will result in increments of 300 being met from `320` to `1520` as `1620` would be above the threshold.

> [!Info]  
> For the moment it does not work inside markdown, only in your `twig` files.



```markdown
![Retina Image](retina.jpg?derivatives=320,1600,300&sizes=%28max-width%3A26em%29+100vw%2C+50vw)
```

```twig
{{ page.media['retina.jpg'].derivatives(320,1600,300).sizes('(max-width:26em) 100vw, 50vw').html()|raw }}
```

```html
{{ code_sample|e }}
```



##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#sizes-with-media-queries-using-derivatives) で確認してください

> [!Warning]   Depending on your display and your browser's implementation and support for `srcset`, you might never see a difference. We included the HTML markup in the fourth tab so you can see what's happening behind the screens.


#### Manual size definition

Instead of letting Grav generate the sizes in even steps between given boundaries, you may manually define which sizes Grav should generate:

```markdown
![Retina Image](retina.jpg?derivatives=[360,720,1200])
```

This will generate downsized versions of the `retina.jpg` image in three widths: 360, 720 and 1200px.

<h2 id="metafiles">メタファイル</h2>

Grav で参照するすべてのメディアは（例： `image1.jpg`, `sample-trailer.mov`, もしくは `archive.zip` さえ）、 **メタファイル** を介して、変数を設定できたり、上書きしたりできます。これらのファイルは、 `<filename>.meta.yaml` という形式を取ります。たとえば、 `image1.jpg` というファイル名の画像に対しては、 `image1.jpg.meta.yaml` と呼ばれるメタファイルを作ることができます。

この方法を使えば、あらゆる設定やメタデータを追加することができます。

このファイルのコンテンツは、YAML 構文であるべきです。例としては：

```yaml
image:
    filters:
        default:
            - [cropResize, 300, 300]
            - sharp
alt_text: My Alt Text
```

もしこの方法を使って、ひとつのファイルにファイル特有のにスタイルやメタタグを付け加えているなら、YAML ファイルは、参照先のファイルと同じフォルダに入れたいと思うでしょう。ファイルは、YAML データとともに取得されます。ページ自体からはできないので、ファイル特有のメタデータを設定するのは便利な方法です。

たとえば、 `sample-image.jpg` という画像ファイルに列挙されている ``alt_text`` の値のみを取得したいとします。そのときは、 `sample-image.jpg.meta.yaml` ファイルを作り、参照する画像ファイルと同じフォルダに置きます。それから、上記の例のようなデータを入力し、YAML ファイルを保存してください。ページのマークダウンでは、次のような行を使うことで、このデータを表示できます：

```yaml
{{ page.media['sample-image.jpg'].meta.alt_text|e }}
```

これにより、上記の例でいえば `My Alt Text` というフレーズが、画像の代わりに取得されます。これは基本的な例です。このメソッドは、様々なことに使えます。たとえば、複数のおのおのの画像に対して独自データを持たせたいギャラリーを作ることができます。つまり、画像それぞれに、独自のデータセットを与え、簡単に参照し、必要な時に取り出せるようになります。

## Video Options

In-line video control options are another capability baked into Grav. These options, added in-line with the file name, give you the ability to determine an embedded video's `autoplay`, `controls`, and `loop` settings.

Here is an example:

```markdown
![video.mov](video.mov?loop=1&controls=0&autoplay=1&muted)
```

The options are as follows:

| 属性   | 説明      |
| :-----      | :-----   |
| autoplay    | Enables (`1`) or Disables (`0`) autoplay for the video on pageload.  |
| controls    | Enables (`1`) or Disables (`0`) media controls for the embedded video. |
| loop        | Enables (`1`) or Disables (`0`) automatic looping for the video, replaying it as it ends. |
| muted       | Mute video and generally allow it to autoplay.  |

