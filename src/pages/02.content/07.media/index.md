---
title: メディア
layout: ../../../layouts/Default.astro
lastmod: '2025-08-01'
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

リサイズは、あなたの期待どおりに行われます。  
`resize` により、 `width` と `height` の通りの新しい画像が作成されます。  
アスペクト比は元のままで、新しい画像は、`0xffffff` のような `hex value` (16進法の値) で提供される **オプションの** 背景色で空白部分を埋められます。  
背景のパラメータはオプションです。提供されなければ、 PNG ファイルの場合 **透明** がデフォルトになり、 JPEG であれば **白色** になります。

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

画像を提供された `width` と `height` でリサイズします。  
`forceResize` は、元画像のアスペクト比を無視し、新しい画像サイズに合わせるために引き伸ばします。

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

`cropResize` は、 `width` と `height` をもとにより小さい・より大きい画像にリサイズします。  
アスペクト比は元のままで、 `width` と `height` による枠にフィットするようにリサイズされます。  
別の言い方をすると、通常の `resize()` をしたときの背景部分はトリミングされます。

たとえば、 `640` x `480` サイズの画像（アスペクト比4:3）を `cropResize(100,100)` (アスペクト比:1) アクションで処理した場合、出来上がる画像は `100` x `75` (4:3) になります。

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

`crop` は、画像のサイズを変更しません。元画像をただ単に切り取ります。次のような領域部分だけを使って新しい画像を作成します： `x` と `y` から始まり、 `width` と `height` で示される領域部分です。

> [!訳注]  
> 上記は、つまり、 `crop(x, y, width, height)` ということが言いたいのだと思います。

たとえば、ある `640` x `480` サイズの画像を `crop(0, 0, 400, 100)` とする場合、作成される画像は、幅 `400` で、高さ `100` となり、 `0, 0` とあるように左上の角からその幅と高さに切り取られた画像です。

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

通常の `cropResize` に似て、 `cropZoom` も、 `width` と `height` を引数に取りますが、要求したサイズと正確に同じサイズとなることが保証された画像を **リサイズし、切り取ります** 。  
アスペクト比は元のままで、結果画像が中心になるように画像が切り取られます。

> [!Info]  
> **cropResize** と **cropZoom** の主な違いは、 cropResize が、元画像のアスペクト比をそのままとし、画像全体を表示できます。余分なスペースは、背景と認識されます。

**cropZoom** では、画像はリサイズされ、背景として見える部分はありません。画像サイズの外にはみ出した余分な画像エリアは、切り取られます。

たとえば、 `640` x `480` サイズの画像について、 `cropZoom(400, 100)` アクションを実行した場合、結果画像は、まず `400` x `300` にリサイズされたあと、高さが `400` x `100` に合わせて切り取られます。

```markdown
![Sample Image](sample-image.jpg?cropZoom=600,200)
```

```twig
{{ page.media['sample-image.jpg'].cropZoom(600,200).html()|raw }}
```

> [!Info]  
> `zoomCrop` に親しんでいる方は、 Grav でこれも機能することに気づくでしょう。

##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#cropzoom) で確認してください

#### quality

動的に、画像の **圧縮率** の `value` を設定でき、 `0` から `100` までの値を取ります。  
小さい数字は、低クオリティで、 `100` は、最高品質を意味します。

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

**negative フィルター** を画像に適用し、色を反転させます。

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

**brightness フィルター** を画像に適用し、 `value` は `-255` から `+255` までの値を取ります。  
負の数の方向に大きい数字は、画像をより暗くし、正の数の方向に大きい数字は、画像を明るくします。

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

**contrast フィルター** を画像に適用し、 `value` は `-100` から `+100` までの値を取ります。  
負の数の方向に大きい数字は、コントラストを大きくし、正の数の方向に大きい数字は、コントラストを減らします。

```markdown
![Sample Image](sample-image.jpg?cropZoom=300,200&contrast=-50)
```

```twig
{{ page.media['sample-image.jpg'].cropZoom(300,200).contrast(-50).html()|raw }}
```

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#contrast) で確認してください

#### grayscale

画像に **grayscale フィルター** を処理します。

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

画像に **embossing フィルター** を処理します。

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

**smoothing フィルター** を画像に適用し、 `-10` から `10` までに設定されたスムーズ `value` をもとに処理します。

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

**sharpening フィルター** を画像に適用します。

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

**edge finding フィルター** を画像に適用します。

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

画像を色付けできます。 `red`, `green`, そして `blue` の値について、各色 `-255` から `+255` までの値で調整できます。

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

**sepia フィルター** を画像に適用し、ビンテージな見た目にできます。

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

ファクターによって、画像を **ぼかします** 。画像にぼかしフィルターをどれくらいの頻度で適用するかを決定します。
デフォルトは1回です。

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

`angle` 度、半時計回りに画像を **回転させます** 。負の数の場合、時計回りに回転させます。

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

与えられた方向に画像を **反転させます** 。  
両方のパラメータに `0|1` が使えます。  
`0` は、反転しないことを意味します。

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

画像の回転が、 EXIF データ（ケータイやデジカメで撮影した jpeg 画像に適用されることがあるもの）で定義されている場合に、画像の向きを固定します。

```markdown
![Sample Image](sample-image.jpg?fixOrientation)
```

```twig
{{ page.media['sample-image.jpg'].fixOrientation().html()|raw }}
```

#### watermark

**watermark アクション** は、ウォーターマーク画像と、ソース画像の2つの画像を合体させ、最終的なウォーターマークされた画像にします。  
このアクションは、他のアクションやフィルタよりも詳細な指定が必要な、とても特別なアクションです。  
特に、 [フィルターを掛け合わせる](#combinations) 時の特別な挙動は、注意しなければいけません。  
興味のある方向けに、とても詳細な [ウォーターマークアクションに関するブログポスト](https://www.grav.cz/blog/vodoznak-aneb-nepokrades-kelisova) が、 [Vit Petira](https://github.com/petira) によって書かれています。ただし、チェコ語のみです。  
しかし、解説は理解しやすいです。

> [!Note]  
> ページレベルの [ストリーム](../06.image-linking/#php-streams) を利用している場合、ページのプレフィックスも特定されなければいけません。

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

画像へ loading 属性を付加することで、ブラウザがそのリソースをいつ読み込み始めるべきか、著者が制御できるようになります。  
loading 属性の値は、 `auto` (デフォルト), `lazy`, `eager` です。  
この値は、 `system.images.defaults.loading` にデフォルト値として設定でき、各マークダウンでは、画像に対して `?loading=lazy` のようにして設定します。  
`auto` の値を選択した場合、 `loading` 属性は追加されません。ブラウザが、どちらの方法で読み込むかを決定します。

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

画像の decoding 属性によりブラウザがリソースをいつデコードし始めるべきか、著者が制御できるようになります。 
decoding 属性の値は、 `auto` (デフォルト), `sync`, `async` です。
デフォルト値は、 `system.images.defaults.decoding` に設定でき、マークダウンごとには、画像に `?decoding=async` により設定できます。  
`auto` を選んだ場合は、 `decoding` 属性は追加されません。ブラウザが、どちらの読み込み方針を取るか決定します。

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

fetchpriority 属性により、著者は次のようなことが制御できます。ブラウザが、他の画像と比べて、画像の読み込み順序をどう優先付けるかの制御です。  
fetchpriority 属性の値は、 `auto` (デフォルト), `hight`, `low` 。
デフォルト値は、 `system.images.defaults.fetchpriority` に設定するか、マークダウンごとに画像に `?fetchpriority=high` のように設定できます。
`auto` を選んだ場合、 `fetchpriority` 属性は追加されず、どちらの方針で行くかはブラウザが決めます。

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

<h2 id="animated-vectorized-actions">アニメーション/ベクトルファイルのアクション</h2>

<h4 id="resize-1">resize</h4>

PHP は、これらのメディアタイプを動的にリサイズできないため、 resize アクションは、 `<img>`/`<video>` もしくは `<a>` の各タグに、 `width` と `height` もしくは `data-width` と `data-height` 属性を設定するだけです。  
これはつまり、画像や動画は、リクエストされたサイズで表示されますが、実質的には画像や動画が変換されているわけではないということです。

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

この具体例を、いくつか示します：

ベクトル画像:

```markdown
![Sample Vector](sample-vector.svg?resize=300,300)
```

アニメーション Gif:

```markdown
![Animated Gif](sample-animated.gif?resize=300,300)
```

動画:

```markdown
![Sample Trailer](sample-trailer.mov?resize=400,200)
```

> [!訳注]  
> 事例は、[翻訳元](https://learn.getgrav.org/content/media#animated-vectorized-actions) で確認してください


<h2 id="audio-actions">オーディオのアクション</h2>

オーディオメディアは、 HTML5 audio タグのリンクを表示します：

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

明示的に、 HTML5 のデフォルトコントローラーを設定したり取り除いたりできます。  
`0` を渡すと、ブラウザのコントローラー（プレイバック、ボリューム、その他）を隠します。

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

`preload` プロパティを設定できます。これは、デフォルトで `auto` です。  
使用できるパラメータは、 `auto`, `metadata` そして `none` です。

> [!Info]  
> <q cite="https://developer.mozilla.org/ja/docs/Web/HTML/Reference/Elements/audio#preload">既定値はブラウザーによって異なります。仕様書では <code>metadata</code> にするよう助言しています。</q>

> [!Info]  
> `autoplay` が指定されている場合、 `preload` 属性は無視されます。

```markdown
![Hal 9000: I'm Sorry Dave](hal9000.mp3?preload=metadata)
```

```twig
{{ page.media['hal9000.mp3'].preload('metadata')|raw }}
```

#### autoplay

オーディオがページが読み込まれたら `autoplay` するかどうかを設定できます。  
設定されていなければ、デフォルトは `false` です。

> [!Info]  
> ひとつの `audio` 要素に `autoplay` と `preload` が両方指定された場合、 `preload` が無視されます。

```markdown
![Hal 9000: I'm Sorry Dave](hal9000.mp3?autoplay=1)
```

```twig
{{ page.media['hal9000.mp3'].autoplay(1)|raw }}
```

#### controlsList

`controlsList` プロパティを設定できます。3つの値が利用できます： `nodownload`, `nofullscreen`, そして `noremoteplayback` 。

> [!Info]  
> マークダウンで1つ以上のパラメータが設定する場合、各パラメータをダッシュ記号 (`-`) で分けてください。出力 HTML では、スペースに置き換わります。

```markdown
![Hal 9000: I'm Sorry Dave](hal9000.mp3?controlsList=nodownload-nofullscreen-noremoteplayback)
```

```twig
{{ page.media['hal9000.mp3'].controlsList('nodownload nofullscreen noremoteplayback')|raw }}
```

#### muted

オーディオが読み込み時 `muted` になるかどうか設定できます。  
設定されなければ、デフォルトでは `false` です。

```markdown
![Hal 9000: I'm Sorry Dave](hal9000.mp3?muted=1)
```

```twig
{{ page.media['hal9000.mp3'].muted(1)|raw }}
```

#### loop

オーディオの再生が完了した後に、 `loop` するかどうか設定できます。  
設定されなければ、デフォルトでは `false` です。

```markdown
![Hal 9000: I'm Sorry Dave](hal9000.mp3?loop=1)
```

```twig
{{ page.media['hal9000.mp3'].loop(1)|raw }}
```

<h2 id="file-actions">ファイルのアクション</h2>

現時点で、 Grav はファイルにカスタムアクションを提供していませんし、今後も追加予定はありません。  
何か考えがある方は、私たちにコンタクトを取ってください。

```markdown
[View Text File](acronyms.txt)
```

```twig
<a href="{{ page.media['acronyms.txt'].url()|raw }}">View Text File</a>
```

##### Result:

> [!訳注]  
> 削除しているため、[翻訳元](https://learn.getgrav.org/content/media#file-actions) で確認してください

<h3 id="combinations">組み合わせ</h3>

ここまで見てきた通り： Grav は、いくつかの強力な画像処理機能を提供しており、その機能は画像を扱うのを本当に簡単にしてくれます！  
本当の力は、複数のエフェクトを組み合わせ、とても洗練された動的画像処理を作成したときに現れます。  
たとえば、以下は完全に妥当な処理です：

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

<h3 id="responsive-images">レスポンシブ画像</h3>

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
| autoplay    | 有効化 (`1`)  無効化 (`0`) autoplay for the video on pageload.  |
| controls    | 有効化 (`1`)  無効化 (`0`) media controls for the embedded video. |
| loop        | 有効化 (`1`)  無効化 (`0`) automatic looping for the video, replaying it as it ends. |
| muted       | Mute video and generally allow it to autoplay.  |

