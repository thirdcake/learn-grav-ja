---
title: 画像リンク
layout: ../../../layouts/Default.astro
lastmod: '2025-07-30'
description: 'Grav のマークダウンでは、様々な方法で画像を表示できます。Grav オリジナルの拡張も含めて解説します。'
---

Grav には、たくさんの柔軟なリンク方法があり、各ページからサイト内の画像、もしくは別のサイトの画像を表示することができます。  
これまで HTML を使ってファイルをリンクしたり、あるいはファイルシステムをコマンドラインで使ったことがあれば、とても簡単に理解できるはずです。

これから、簡単な利用例を実演します。  
次のような Grav サイトの **Pages** ディレクトリをモデルにします。

![Pages Directory](pages.png)

上記のディレクトリ構造を例に、コンテンツ中に画像を貼るいくつかの異なる方法を見ていきましょう。  
上記の例では、すべてのフォルダに画像が入っています。  
各ブログ投稿に1枚ずつ、そしてページではなくメディアファイルのみを持つ特別な `pages/images` ディレクトリに3枚あります。

複数のページで頻繁に利用されるファイルを、シンプルに一元化して保存することで、メンテナンスしやすくする場合の例として、`/images` フォルダを使います。  
これにより、リンク処理がシンプルになります。

> [!Warning]  
> 一元化した画像ディレクトリを作る場合、画像はフロントエンドで使われるものなので、そのディレクトリは `/pages` フォルダに入れるようにしてください。

はじめに、 Grav の画像タグの一般的なコンポーネントを簡単に見ていきます。

```markdown
![Alt Text](../path/image.ext)
```

| 文字列 | 説明   |
| :----- | :-----  |
| `!`    | ページリンクのマークダウンタグを、`!` で始めると、画像リンクであることを意味します |
| `[]`   | 角カッコは、**オプショナルな** 画像の altテキストを囲みます |
| `()`   | 丸カッコは、画像の参照先を囲みます。角カッコのすぐ後に書く必要があります |
| `../`  | リンクの中で使われ、親ディレクトリに移動することを意味します |

> [!Tip]  
> 画像リンクを、ページリンクで囲むことができます： `[![Alt text](/path/to/img.jpg)](http://example.net/)`

<h3 id="slug-relative">スラッグによる相対画像リンク</h3>

**相対** 画像リンクは、現在ページからの相対的な行き先を使用します。  
現在ページに関連付けられている画像のように、現在ディレクトリにある別ファイルにリンクを張るだけの簡単なものもあれば、ディレクトリをいくつか上り、さらに画像の存在する特定のフォルダ・ファイルまで下っていくような複雑なものもありえます。

相対リンクでは、リンク元ファイルの場所は、リンク先と全く同じくらい重要です。  
どちらかのファイルが移動し、その間のパスが変更されてしまったら、リンクは壊れてしまうでしょう。

このタイプのリンク構造の利点は、ローカルの開発サーバーから、異なるドメイン名のライブサーバーに移動させるのが簡単であり、ファイル構造が一貫している限り、リンクは問題を起こさないということです。

ファイルリンクは、特定のファイルをディレクトリやスラッグではなく、名前で指定します。  
`pages/01.blog/test-post-1/item.md` ファイルで、 `/pages/01.blog/test-post-3/test-image-3.jpg` ファイルへの画像リンクを作成した場合、次のようなコマンドを使うことになります。

```markdown
![Test Image 3](../test-post-3/test-image-3.jpg)
```

このリンクは、 `../` でフォルダを1つ上がり、フォルダを1つ下がり、直接リンク先である `test-image-3.jpg` ファイルを指し示します。

`01.blog` ディレクトリから `blog-header.jpg` を読み込みたいなら、次のようにします：

```markdown
![Blog Header](../../blog/blog-header.jpg)
```

> [!Note]  
> スラッグの相対リンクでは、順序を示す番号 (`01.`) を含める必要はありません。

Grav は、ページの主なマークダウンファイルのヘッダーでスラッグをサポートしています。  
このスラッグは、ページのフォルダ名に優先し、その中にメディアファイルを含められます。

たとえば、次のような **Test Post 2** ページには、スラッグが設定されています  (`/pages/01.blog/test-post-2/item.md`) 。  
このファイルのヘッダーは、次のようになっています：

```yaml
---
title: Test Post 2
slug: test-slug
taxonomy:
    category: blog
---
```

スラッグに `test-slug` が設定されていることにお気づきでしょう。  
このように設定されたスラッグは、完全にオプションであり、無くてもかまいません。  
前のチャプターで解説したように、リンクを簡単にしてくれるものです。  
スラッグが設定されると、そのフォルダ内のメディアファイルへのリンクは、 **スラッグによる相対リンク** か、 **絶対リンク** のどちらかでなければならず、リンクには完全な URL が設定されます。

**Test Post 2** から `test-image-2.jpg` にリンクしたいときは、次のようにします：

```markdown
![Test Image 2](../test-slug/test-image-2.jpg)
```

お気づきのように、 (`../`) を使って1つディレクトリを上がり、 `/pages/01.blog/test-post-2/item.md` ファイルに設定されたスラッグを使って `test-slug` ページフォルダに下ります。

<h3 id="directory-relative">ディレクトリによる相対画像リンク</h3>

**ディレクトリによる相対** 画像リンクは、現在ページから相対的なリンク先を使います。  
スラッグによる相対リンクとの主な違いは、 URL スラッグを使うのではなく、フォルダ名のフルパスを使って画像を参照するということです。

この具体例として、たとえば次のようになります：

```markdown
![Test Image 3](../../01.blog/02.my_folder/test-image-3.jpg)
```

> [!Info]  
> この方法の主な利点は、 たとえば GitHub のような Grav 以外のシステムでもリンクを利用できるということです。

<h3 id="absolute">絶対画像リンク</h3>

絶対リンクは、総体リンクに似ていますが、サイトのルートディレクトリからの相対です。  
**Grav** では特に、 **/user/pages/** ディレクトリを基準にします。  
このタイプのリンクは、2つの異なる方法で行われます。

**スラッグ相対** リンクのやり方に似た方法で、スラッグもしくはパスのディレクトリ名を使う方法です。  
この方法は、後で順番を変更するような（フォルダ名の最初の数字が変更してしまうような）ことによりリンク切れを起こすという潜在的な問題を避けることができます。  
絶対リンクによる方法で使われる、最も一般的なやりかたです。

絶対リンクでは、リンクは `/` で始まります。  
次の例は、 `pages/01.blog/blog.md` ファイルから、 **スラッグ** スタイルで `pages/01.blog/test-post-2/test-image-2.jpg` へ絶対リンクを張るものです。

```markdown
![Test Image 2](/blog/test-slug/test-image-2.jpg)
```

> [!Tip]  
> `user/pages/images/` フォルダを Grav サイトに作成し、そこに画像を置くのは、強力なテクニックです。そうすれば、 Grav のページから絶対 URL で画像を簡単に参照できます： `/images/test-image-4.jpg` また、それらに [メディアアクション](../07.media/) を処理することもできます。

<h3 id="php-streams">PHPストリーム</h3>

Grav では、 PHP ストリームを使って、画像を参照したりリンクしたりすることもできます。  
いくつかの PHP ストリームを用意しています：

* `user://` - userフォルダ. 例： `user/`
* `page://` - pagesフォルダ 例： `user/pages/`
* `image://` - imagesフォルダ 例： `user/images/`
* `plugins://` - pluginsフォルダ  例： `user/plugins/`
* `theme://` - 現在テーマ  例： `user/themes/antimatter/`

これらにより、以前は pages の階層（`user/pages/`）の外にあった画像へのアクセスが容易になります。

```markdown
![Stream Image](user://media/images/my-image.jpg)
```

もしくは：

```markdown
![Stream Image](theme://images/my-image.jpg)
```

デフォルトで使えるストリームの全体像は、[複数サイト設定 - ストリーム](../../08.advanced/05.multisite-setup/#streams) を参照してください。

<h3 id="remote">サイト外リンク</h3>

外部サイトの画像リンクにより、 URL を使ってあらゆるメディアファイルを直接サイトに表示することができます。  
これは、自身のサイトコンテンツにメディアファイルを含める必要が無くできます。  
以下は、外部サイトの画像ファイルを表示する方法の例です。

```markdown
![Remote Image 1](https://getgrav.org/images/testimage.png)
```

あらゆるダイレクト URL にリンクができます。  
安全な HTTPS リンクも含まれます。

<h3 id="media-actions-on-images">画像でのメディアアクション</h3>

ページに関連付けられた画像を使う主な利点のひとつは、 [Grav の強力なメディアアクション](../07/media/) が使えるということです。  
たとえば、別ページから読み込んだ画像を使う例です：

```markdown
![Styling Example](../test-post-3/test-image-3.jpg?cropResize=400,200)
```

もしくは、現在テーマにある画像にストリームでアクセスすることもできます：

```markdown
![Stream Image](theme://images/default-avatar.jpg?cropZoom=200,200&brightness=-75)
```

You will find more information about actions and other [media file functionality in the next chapter](../07.media).

<h3 id="image-attributes">画像属性</h3>

新しく、素晴らしい機能が使えるようになりました。マークダウン構文を使って、画像属性を直接提供できる機能です。  
これにより、 HTML 属性に、 [Markdown Extra](https://michelf.ca/projects/php-markdown/extra/) を使うこと無く、簡単に **class** や **id** を追加できます。

いくつか具体例を紹介します：

<h5 id="single-class-attribute">ひとつのクラス属性</h5>

```markdown
![My Image](my-image.jpg?classes=float-left)
```

これは、次のような HTML になります：

```html
<img src="/your/pages/some-page/my-image.jpg" class="float-left" />
```

<h5 id="multiple-classes-attributes">複数のクラス属性</h5>

```markdown
![My Image](my-image.jpg?classes=float-left,shadow)
```

これは、次のような HTML になります：

```html
<img src="/your/pages/some-page/my-image.jpg" class="float-left shadow" />
```

<h5 id="id-attribute">ID属性</h5>

```markdown
![My Image](my-image.jpg?id=special-id)
```

これは、次のような HTML になります：

```html
<img src="/your/pages/some-page/my-image.jpg" id="special-id" />
```

