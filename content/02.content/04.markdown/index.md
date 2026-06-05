---
title: マークダウン構文
lastmod: '2026-06-05T19:10:44+09:00'
description: 'Grav で使用されるマークダウン構文について、その書き方と、その見え方を、合わせて解説します。'
weight: 40
params:
    srcPath: /content/markdown
---

率直にいって、 web コンテンツを書くのは面倒です。  
WYSIWYG エディタは助けになりますが、それらはたびたび、ひどいコードとなり、ひどいだけでなく、見にくい web ページになってしまいます。

**マークダウン** は、 **HTML** を書くための良い方法です。  
複雑さや見にくさを避けながら書けます。

重要なメリットは、以下のとおりです：

1. マークダウンは、最小限の文字が追加されるだけで、簡単に学べるため、コンテンツ制作も早くできるようになります。
2. マークダウンを書くとき、エラーになる危険性は減らせます。
3. 妥当な XHTML 出力が作成されます。
4. コンテンツとヴィジュアル表示を分けたままにできます。サイトの見た目を乱しようがありません。
5. いかなるテキストエディタでも、マークダウンアプリケーションでも、好きなもので書けます。
6. マークダウンは、使っていて楽しい！

マークダウンを生み出したJohn Gruber は、次のように言います：

> The overriding design goal for Markdown’s formatting syntax is to make it as readable as possible. The idea is that a Markdown-formatted document should be publishable as-is, as plain text, without looking like it’s been marked up with tags or formatting instructions. While Markdown’s syntax has been influenced by several existing text-to-HTML filters, the single biggest source of inspiration for Markdown’s syntax is the format of plain text email.  
> -- <cite>John Gruber</cite>

Grav は、最初から [Markdown](https://daringfireball.net/projects/markdown/) と [Markdown Extra](https://michelf.ca/projects/php-markdown/extra/) をサポートしています。**Markdown Extra** を使用したい場合は、`system.yaml` ファイルで設定を有効化してください。

> [!NOTE]
> **Grav 2.0 の新機能** — Markdown のレンダリングに完全な [GitHub Flavored Markdown](https://github.github.com/gfm/) 機能が追加され、 **デフォルトで有効化** されています： [task lists](#task-lists), [highlight](#highlight)/[subscript](#subscript)/[superscript](#superscript) マーク, 自動の [素の `www.` やメールアドレスへのリンク](#automatic-links), そして許可されない生の HTML タグのエスケープ。 オプトインの [便利な表の拡張機能](#enhanced-tables) — colspan, header-less, captions, attributes, そして multi-line cells — も利用できます。 これらは全て設定可能です； [Configuration](#configuration) をご覧ください。

さっそく、マークダウンの主要な要素と、それが HTML でどう見えるかを見ていきましょう：

> [!Info]  
> これからいつでも参照できるように、このページをブックマークしてください！

## 見出し{#headings}

`h1` から `h6` までの見出しは、それぞれのレベルの `#` により作れます：

```txt
# h1 Heading
## h2 Heading
### h3 Heading
#### h4 Heading
##### h5 Heading
###### h6 Heading
```

以下のようにレンダリングされます：

# h1 Heading
## h2 Heading
### h3 Heading
#### h4 Heading
##### h5 Heading
###### h6 Heading

HTML：

```html
<h1>h1 Heading</h1>
<h2>h2 Heading</h2>
<h3>h3 Heading</h3>
<h4>h4 Heading</h4>
<h5>h5 Heading</h5>
<h6>h6 Heading</h6>
```

## コメント{#comments}

コメントは、 HTML と同じように書いてください

```html
<!--
This is a comment
-->
```

次のコメントは、 **見えない** はずです：

<!--
This is a comment
-->

## 水平区切り{#horizontal-rules}

HTMLの `<hr>` 要素は、段落レベルの要素間で、「内容の区切り」を作ります。  
マークダウンでは、次のように書きます：

* `___`: 3つ連続のアンダースコア
* `---`: 3つ連続のダッシュ
* `***`: 3つ連続のアスタリスク

次のようにレンダリングされます：

___

---

***

## 本文{#body-copy}

通常のプレーンなテキストで書かれた本文は、 `<p></p>` タグで包まれて、 HTML にレンダリングされます。

よって、この本文は：

```txt
Lorem ipsum dolor sit amet, graecis denique ei vel, at duo primis mandamus. Et legere ocurreret pri, animal tacimates complectitur ad cum. Cu eum inermis inimicus efficiendi. Labore officiis his ex, soluta officiis concludaturque ei qui, vide sensibus vim ad.
```

次のようにレンダリングされます：

```html
<p>Lorem ipsum dolor sit amet, graecis denique ei vel, at duo primis mandamus. Et legere ocurreret pri, animal tacimates complectitur ad cum. Cu eum inermis inimicus efficiendi. Labore officiis his ex, soluta officiis concludaturque ei qui, vide sensibus vim ad.</p>
```

**改行** は、2つのスペースの後にリターンキーをすることによってできます。

## インラインの HTML{#inline-html}

特定の HTML タグが作りたい場合（class を付けるようなとき）は、単に HTML を使ってください：

```html
Paragraph in Markdown.

<div class="class">
    This is <b>HTML</b>
</div>

Paragraph in Markdown.
```


> [!Note]
> Grav 2.0 では、コンテンツ内の「生の HTML を許可しない」タグの小さな拒否リストをエスケープします — `title`, `textarea`, `style`, `xmp`, `iframe`, `noembed`, `noframes`, `script`, そして `plaintext` です — そのため、これらは表示テキストとしてレンダリングされ、有効なマークアップンとしてはレンダリングされません。 GitHub フレーバーのマークダウンのタグフィルタに準拠します。 これは、意図せず発生する不具合を防ぐのに便利ですが、セキュリティの境界ではありません； 幅広い HTML サニタイズは、サイトのセキュリティレイヤーの仕事のままです。 [Configuration](#configuration) で無効化できます。


## 強調{#emphasis}

### 太字{#bold}

太字で強調したいとき、次の文章の部分は、 **太字のテキストでレンダリングされます** 。

```txt
**rendered as bold text**
```

次のようにレンダリングされます：

**rendered as bold text**

HTML は、このようになります：

```html
<strong>rendered as bold text</strong>
```

### 斜体{#italics}

斜体で強調したいとき、次の文章の部分は、 _斜体のテキストでレンダリングされます_ 。

```txt
_rendered as italicized text_
```

次のようにレンダリングされます：

_rendered as italicized text_

HTML は、このようになります：

```html
<em>rendered as italicized text</em>
```

### 見え消し{#strikethrough}

GFM（GitHubフレーバーのマークダウン）では、見え消しができます。

```txt
~~Strike through this text.~~
```

次のようにレンダリングされます：

~~Strike through this text.~~

HTMLです：

```html
<del>Strike through this text.</del>
```

### Highlight

> [!NOTE]
> Highlight, subscript, そして superscript マークは、 **Grav 2.0 での新機能** であり、デフォルトで有効化されています。

2つのイコール記号でテキストを包むと、ハイライトされ、 HTML の `<mark>` 要素としてレンダリングされます。

```md
Markdown makes ==certain words stand out==.
```

レンダリング：

Markdown makes ==certain words stand out==.

HTML:

```html
<p>Markdown makes <mark>certain words stand out</mark>.</p>
```

### Subscript

1つのチルダ記号でテキストを包むと subscript として、 `<sub>` 要素でレンダリングされます。 1つの `~text~` は subscript であり、2つの `~~text~~` は [取り消し線](#strikethrough) なので、2つは衝突しません。


```md
The chemical formula for water is H~2~O.
```

レンダリング：

The chemical formula for water is H~2~O.

HTML:

```html
<p>The chemical formula for water is H<sub>2</sub>O.</p>
```

### Superscript

superscript として 1つのカレットでテキストを包むと、 `<sup>` 要素でレンダリングされます。

```md
Einstein's famous equation is E = mc^2^.
```

レンダリング：

Einstein's famous equation is E = mc^2^.

HTML:

```html
<p>Einstein's famous equation is E = mc<sup>2</sup>.</p>
```

## 引用ブロック{#blockquotes}

他の文章のコンテンツを、自分の文章中に引用するためのものです。

すべての引用文の頭に、`> ` を付け加えてください。

```txt
> **Fusion Drive** combines a hard drive with a flash storage (solid-state drive) and presents it as a single logical volume with the space of both drives combined.
```

次のようにレンダリングされます：

> **Fusion Drive** combines a hard drive with a flash storage (solid-state drive) and presents it as a single logical volume with the space of both drives combined.

HTML です：

```html
<blockquote>
  <p><strong>Fusion Drive</strong> combines a hard drive with a flash storage (solid-state drive) and presents it as a single logical volume with the space of both drives combined.</p>
</blockquote>
```

引用ブロックは、入れ子にできます：

```txt
> Donec massa lacus, ultricies a ullamcorper in, fermentum sed augue.
Nunc augue augue, aliquam non hendrerit ac, commodo vel nisi.
>> Sed adipiscing elit vitae augue consectetur a gravida nunc vehicula. Donec auctor
odio non est accumsan facilisis. Aliquam id turpis in dolor tincidunt mollis ac eu diam.
```

次のようにレンダリングされます：

> Donec massa lacus, ultricies a ullamcorper in, fermentum sed augue.
Nunc augue augue, aliquam non hendrerit ac, commodo vel nisi.
>> Sed adipiscing elit vitae augue consectetur a gravida nunc vehicula. Donec auctor
odio non est accumsan facilisis. Aliquam id turpis in dolor tincidunt mollis ac eu diam.

## 注意書き{#notices}

> [!Note]  
> 引用ブロックを上書きした注意書き（`>>>`）はもう古く、非推奨です。[Markdown Notices](https://github.com/getgrav/grav-plugin-markdown-notices) という専用のプラグインを使ってください。

> [!訳注]
> Grav 2.0 のベータ版では、プリインストールのプラグインとして Markdown Notices から、Github Markdown Alerts に変わっているようです。 いずれにせよ、注意の書き方は、それぞれのプラグインに合わせる必要があります。

## リスト{#lists}

### 順番無しリスト{#unordered}

アイテムのリストで、順番が大事でないときに使います。

箇条書きを示すために、次のような記号が使えます：

```txt
* 適切な記号
- 適切な記号
+ 適切な記号
```

たとえば

```txt
+ Lorem ipsum dolor sit amet
+ Consectetur adipiscing elit
+ Integer molestie lorem at massa
+ Facilisis in pretium nisl aliquet
+ Nulla volutpat aliquam velit
  - Phasellus iaculis neque
  - Purus sodales ultricies
  - Vestibulum laoreet porttitor sem
  - Ac tristique libero volutpat at
+ Faucibus porta lacus fringilla vel
+ Aenean sit amet erat nunc
+ Eget porttitor lorem
```

次のようにレンダリングされます：

+ Lorem ipsum dolor sit amet
+ Consectetur adipiscing elit
+ Integer molestie lorem at massa
+ Facilisis in pretium nisl aliquet
+ Nulla volutpat aliquam velit
  - Phasellus iaculis neque
  - Purus sodales ultricies
  - Vestibulum laoreet porttitor sem
  - Ac tristique libero volutpat at
+ Faucibus porta lacus fringilla vel
+ Aenean sit amet erat nunc
+ Eget porttitor lorem

そしてHTMLはこうなります

```html
<ul>
  <li>Lorem ipsum dolor sit amet</li>
  <li>Consectetur adipiscing elit</li>
  <li>Integer molestie lorem at massa</li>
  <li>Facilisis in pretium nisl aliquet</li>
  <li>Nulla volutpat aliquam velit
    <ul>
      <li>Phasellus iaculis neque</li>
      <li>Purus sodales ultricies</li>
      <li>Vestibulum laoreet porttitor sem</li>
      <li>Ac tristique libero volutpat at</li>
    </ul>
  </li>
  <li>Faucibus porta lacus fringilla vel</li>
  <li>Aenean sit amet erat nunc</li>
  <li>Eget porttitor lorem</li>
</ul>
```

### 順番ありリスト{#ordered}

アイテムのリストで、順番が大事であるときに使います。

```txt
1. Lorem ipsum dolor sit amet
2. Consectetur adipiscing elit
3. Integer molestie lorem at massa
4. Facilisis in pretium nisl aliquet
5. Nulla volutpat aliquam velit
6. Faucibus porta lacus fringilla vel
7. Aenean sit amet erat nunc
8. Eget porttitor lorem
```

次のようにレンダリングされます：

1. Lorem ipsum dolor sit amet
2. Consectetur adipiscing elit
3. Integer molestie lorem at massa
4. Facilisis in pretium nisl aliquet
5. Nulla volutpat aliquam velit
6. Faucibus porta lacus fringilla vel
7. Aenean sit amet erat nunc
8. Eget porttitor lorem

そしてHTMLはこうなります

```html
<ol>
  <li>Lorem ipsum dolor sit amet</li>
  <li>Consectetur adipiscing elit</li>
  <li>Integer molestie lorem at massa</li>
  <li>Facilisis in pretium nisl aliquet</li>
  <li>Nulla volutpat aliquam velit</li>
  <li>Faucibus porta lacus fringilla vel</li>
  <li>Aenean sit amet erat nunc</li>
  <li>Eget porttitor lorem</li>
</ol>
```

**TIP**: それぞれの番号を `1.` とした場合、マークダウンは自動的に番号を振ってくれます。たとえば：

```txt
1. Lorem ipsum dolor sit amet
1. Consectetur adipiscing elit
1. Integer molestie lorem at massa
1. Facilisis in pretium nisl aliquet
1. Nulla volutpat aliquam velit
1. Faucibus porta lacus fringilla vel
1. Aenean sit amet erat nunc
1. Eget porttitor lorem
```

次のようにレンダリングされます：

1. Lorem ipsum dolor sit amet
2. Consectetur adipiscing elit
3. Integer molestie lorem at massa
4. Facilisis in pretium nisl aliquet
5. Nulla volutpat aliquam velit
6. Faucibus porta lacus fringilla vel
7. Aenean sit amet erat nunc
8. Eget porttitor lorem


### Task Lists

> [!NOTE]
> タスクリストは、 **Grav 2.0 の新機能** であり、デフォルトで有効化されています。

リストマーカーの直後に `[ ]` （未完了）や `[x]` 完了済みを付けると、GitHub フレーバーマークダウンに従い、チェックボックスのアイテムがレンダリングされます。

```md
- [x] Write the documentation
- [x] Review the examples
- [ ] Ship it
```

レンダリング：

- [x] Write the documentation
- [x] Review the examples
- [ ] Ship it

HTML:

```html
<ul>
  <li class="task-list-item"><input type="checkbox" disabled="" checked="" /> Write the documentation</li>
  <li class="task-list-item"><input type="checkbox" disabled="" checked="" /> Review the examples</li>
  <li class="task-list-item"><input type="checkbox" disabled="" /> Ship it</li>
</ul>
```

このチェックボックスは、読み取り専用（`disabled`）としてレンダリングされ、各 `<li>` は `task-list-item` クラスを持ちます。よって、テーマでタスクリストの style が書けます。

## コード{#code}

### 1行コード{#inline-code}

`` ` `` （バッククオート）でコード部分を囲んでください。

```txt
In this example, `<section></section>` should be wrapped as **code**.
```

次のようにレンダリングされます：

In this example, `<section></section>` should be wrapped with **code**.

HTML です：

```html
<p>In this example, <code>&lt;section&gt;&lt;/section&gt;</code> should be wrapped with <strong>code</strong>.</p>
```

### インデント・コード{#indented-code}

4文字以上のスペースで複数行のコードををインデントしてください。次のように：

<pre>
  // Some comments
  line 1 of code
  line 2 of code
  line 3 of code
</pre>

次のようにレンダリングされます：

```txt
// Some comments
line 1 of code
line 2 of code
line 3 of code
```

HTML です：

```html
<pre>
  <code>
    // Some comments
    line 1 of code
    line 2 of code
    line 3 of code
  </code>
</pre>
```

### ブロック・コード{#block-code-fences}

言語属性付きの複数行のコードブロックを作るには、"fences" `` 3つの` `` を使ってください。

<pre>
```
Sample text here...
```
</pre>

HTML です：

```html
<pre language-html>
  <code>Sample text here...</code>
</pre>
```

### 構文のハイライト{#syntax-highlighting}

GFM（GitHubフレーバーのマークダウン）は、構文のハイライトに対応しています。  
有効化すると、最初の "fence" に、言語の拡張子をつけるだけで、自動的に構文のハイライトが適用されます。  
たとえば、 JavaScript コードを適用できます：

<pre>
```js
grunt.initConfig({
  assemble: {
    options: {
      assets: 'docs/assets',
      data: 'src/data/*.{json,yml}',
      helpers: 'src/custom-helpers.js',
      partials: ['src/partials/**/*.{hbs,md}']
    },
    pages: {
      options: {
        layout: 'default.hbs'
      },
      files: {
        './': ['src/templates/pages/index.hbs']
      }
    }
  }
};
```
</pre>

次のようにレンダリングされます：

```javascript
grunt.initConfig({
  assemble: {
    options: {
      assets: 'docs/assets',
      data: 'src/data/*.{json,yml}',
      helpers: 'src/custom-helpers.js',
      partials: ['src/partials/**/*.{hbs,md}']
    },
    pages: {
      options: {
        layout: 'default.hbs'
      },
      files: {
        './': ['src/templates/pages/index.hbs']
      }
    }
  }
};
```

> [!Tip]  
> ハイライトさせるには、 [Highlight plugin](https://github.com/getgrav/grav-plugin-highlight) をインストールして、有効化する必要があります。これは jQuery を使っており、テーマでも読み込む必要があります。

## 表{#tables}

表は、それぞれのセルをパイプ（ `|` ）で区切って作ります。  
そして、表のヘッダの下に、（バーで区切られた）ダッシュ（ `-` ）の行を）追加してください。  
なお、パイプは垂直に揃える必要はありません。

```txt
| Option | Description |
| ------ | ----------- |
| data   | path to data files to supply the data that will be passed into templates. |
| engine | engine to be used for processing templates. Handlebars is the default. |
| ext    | extension to be used for dest files. |
```

次のようにレンダリングされます：

| Option | Description |
| ------ | ----------- |
| data   | path to data files to supply the data that will be passed into templates. |
| engine | engine to be used for processing templates. Handlebars is the default. |
| ext    | extension to be used for dest files. |

そして HTML です：

```html
<table>
  <thead>
    <tr>
      <th>Option</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>data</td>
      <td>path to data files to supply the data that will be passed into templates.</td>
    </tr>
    <tr>
      <td>engine</td>
      <td>engine to be used for processing templates. Handlebars is the default.</td>
    </tr>
    <tr>
      <td>ext</td>
      <td>extension to be used for dest files.</td>
    </tr>
  </tbody>
</table>
```

### 表のテキストを右に揃える{#right-aligned-text}

ダッシュの行の右側にコロンを追加すると、その列は右揃えになります。

```txt
| Option | Description |
| ------:| -----------:|
| data   | path to data files to supply the data that will be passed into templates. |
| engine | engine to be used for processing templates. Handlebars is the default. |
| ext    | extension to be used for dest files. |
```

| Option | Description |
| ------:| -----------:|
| data   | path to data files to supply the data that will be passed into templates. |
| engine | engine to be used for processing templates. Handlebars is the default. |
| ext    | extension to be used for dest files. |


### Enhanced Tables

Grav 2.0 では、5つの **オプトイン** テーブル拡張を追加しました。  
これらは、すべて **デフォルトでは無効** であり、標準的なテーブルは、変更されません — [Configuration](#configuration) でグローバルに拡張機能を有効化したり、各ページのフロントまたーで有効化しない限りは：

```yaml
markdown:
  tables:
    colspan: true
    headerless: true
    captions: true
    attributes: true
    multiline: true
```

#### Colspan

セルを空にして、左のセルにマージさせると、セルの `colspan` が拡大します（MultiMarkdown の規約です）。

```
| Item    | Jan | Feb | Mar |
| :--     | --: | --: | --: |
| Widgets | 10  | 12  | 9   |
| Total for the quarter |   |   | 57 |
```

最後の行のラベルスパンは、3列です：

```html
<tr>
  <td style="text-align: left;" colspan="3">Total for the quarter</td>
  <td style="text-align: right;">57</td>
</tr>
```

#### Header-less

区切り線の行から直接テーブルを始めると、 `<tbody>` のみのヘッダー無しテーブルを作成します。

```md
| --- | --- |
| Mercury | Closest to the Sun |
| Neptune | Furthest from the Sun |
```

```html
<table>
  <tbody>
    <tr><td>Mercury</td><td>Closest to the Sun</td></tr>
    <tr><td>Neptune</td><td>Furthest from the Sun</td></tr>
  </tbody>
</table>
```

#### Captions

テーブルの直後に `[Caption]` 行があると、（テーブルの最初の子要素としてレンダリングされる） `<caption>` タグになります。そのキャプション内のインラインマークダウンはパースされます。  


```md
| Symbol | Element |
| --- | --- |
| H | Hydrogen |
| He | Helium |
[The first two elements of the periodic table]
```

```html
<table>
  <caption>The first two elements of the periodic table</caption>
  <thead>...</thead>
  <tbody>...</tbody>
</table>
```

#### Attributes

テーブルの直後に `{.class #id}` 行があると、 `<table>` 要素に `class` や `id` を設定します — `.striped` や `.responsive` のようなスタイルクラスのある CSS フレームワークで便利です。  
よく使われる `{:.class}` （コロンに続ける）やり方も使えます。  
`class` と `id` のみが認識されます； 他のトークンは触れられないまま残します。

```md
| Name | Role |
| --- | --- |
| Ada | Engineer |
| Grace | Architect |
{.striped .responsive #team-table}
```

```html
<table class="striped responsive" id="team-table">
  ...
</table>
```

#### Multi-line cells

バックスラッシュ（`\`）で行を終えると、次の行に続きます：  
続く行のセルは、列ごとに上の行にマージされ、 `<br>` で繋げられます。  
空のセルは、何も追加しないので、1列だけ次の行に持ち越すことができます。

```md
| Feature | Description |
| --- | --- |
| Tables | Alignment, escaped pipes, \
| | and inline cell content |
| Lists | Ordered, unordered and task lists |
```

```html
<tr>
  <td>Tables</td>
  <td>Alignment, escaped pipes,<br>and inline cell content</td>
</tr>
```

## リンク{#links}

### 普通のリンク{#basic-link}

```txt
[Assemble](https://assemble.io)
```

次のようにレンダリングされます。
リンクの上でホバーしてみると、ツールチップがありません：

[Assemble](https://assemble.io)

HTML:

```html
<a href="https://assemble.io">Assemble</a>
```

### タイトルをつけたリンク{#add-a-title}

```txt
[Upstage](https://github.com/upstage/ "Visit Upstage!")
```

次のようにレンダリングされます。
リンクの上でホバーしてみると、ツールチップが出ます：

[Upstage](https://github.com/upstage/ "Visit Upstage!")

HTML:

```html
<a href="https://github.com/upstage/" title="Visit Upstage!">Upstage</a>
```

### アンカー・リンク{#named-anchors}

アンカーリンクは、同じページのアンカーポイントへジャンプする機能です。たとえば、次のようなそれぞれのチャプターは：

```txt
# Table of Contents
  * [Chapter 1](#chapter-1)
  * [Chapter 2](#chapter-2)
  * [Chapter 3](#chapter-3)
```

以下のようなセクションに飛びます：

```txt
## Chapter 1 <a id="chapter-1"></a>
Content for chapter one.

## Chapter 2 <a id="chapter-2"></a>
Content for chapter one.

## Chapter 3 <a id="chapter-3"></a>
Content for chapter one.
```

**注意** アンカータグの配置は、任意です。ここでは目立たないようにインラインにしており、機能します。


### Automatic Links

> [!NOTE]
> 素の `www.` URL やメールアドレスの自動リンクは、 **Grav 2.0 の新機能** （GitHub フレーバーマークダウンの autolinks） であり、デフォルトで有効です。  
> 素の `http(s)://` URL は、 **Auto URL Links** オプションで分けて制御され、デフォルトでオフのままです。

素の `www.` URL やメールアドレスは、マークアップの必要無く、自動でリンク化します：

```md
Visit www.getgrav.org or email hello@getgrav.org.
```

レンダリング：

Visit www.getgrav.org or email hello@getgrav.org.

HTML:

```html
<p>Visit <a href="http://www.getgrav.org">www.getgrav.org</a> or email <a href="mailto:hello@getgrav.org">hello@getgrav.org</a>.</p>
```

## 画像{#images}

画像は、リンクの構文に似ていますが、イクスクラメーションマーク（ `!` ）が最初に付きます。

```txt
![Minion](https://octodex.github.com/images/minion.png)
```

![Minion](https://octodex.github.com/images/minion.png)

もしくは：

```txt
![Alt text](https://octodex.github.com/images/stormtroopocat.jpg "The Stormtroopocat")
```

![Alt text](https://octodex.github.com/images/stormtroopocat.jpg "The Stormtroopocat")

リンクと同様、画像にも脚注のような構文があります：

```txt
![Alt text][id]
```

![Alt text][id]

あとで、URLを指定することができます：

[id]: https://octodex.github.com/images/dojocat.jpg  "The Dojocat"

```txt
[id]: https://octodex.github.com/images/dojocat.jpg  "The Dojocat"
```

## Configuration

マークダウンのレンダリングは、管理パネルの **Configuration → System → Markdown** で設定するか、 `user/config/system.yaml` 内の `pages.markdown` キーで設定します。  
オプションは全て、各ページのフロントまたー内の `markdown:` キーで上書き可能です。  
デフォルトは：

```yaml
pages:
  markdown:
    extra: false              # Enable Markdown Extra
    auto_line_breaks: false   # Treat a single newline as a <br>
    auto_url_links: false     # Autolink bare http(s):// URLs
    escape_markup: false      # Escape inline HTML into entities
    gfm:                      # GitHub Flavored Markdown - on by default
      task_lists: true        # - [ ] / - [x] checkboxes
      marks: true             # ==highlight==, ~subscript~, ^superscript^
      tagfilter: true         # Escape disallowed raw HTML tags
      autolinks: true         # Autolink bare www. URLs and emails
    tables:                   # Enhanced table extensions - off by default
      colspan: false          # Empty cell merges into the cell on its left
      headerless: false       # Table may start at the divider row
      captions: false         # [Caption] line after a table becomes <caption>
      attributes: false       # {.class #id} line after a table sets attributes
      multiline: false        # Row ending in \ continues onto the next line
```

`gfm` オプションは、既存コンテンツのレンダリング（checkboxes, `<mark>`/`<sub>`/`<sup>`, autolinked URLs, escaped raw HTML） を変更するので、過去のやり方が必要な場合は有効にできます。  
`tables` 拡張は、デフォルトですべて無効であり、オプトインしない限り、標準的なテーブルに影響はありません。

> [!TIP]
> プラグイン開発者は、独自のマークダウン構文 — カスタムブロックおよびインラインマーク — を追加できます。 [Markdown Extensions API](../../04.plugins/10.markdown-extensions/) をご覧ください。


