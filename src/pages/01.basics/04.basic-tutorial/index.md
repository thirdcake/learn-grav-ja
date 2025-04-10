---
title: "基本的なチュートリアル"
layout: ../../../layouts/Default.astro
---

前の章で解説した手順で、[Gravのインストール](../03.installation/)が成功したとして、さらにGravを快適にするために少し遊んでみましょう。

Gravはデータベースを必要としないため、非常にかんたんに操作できますし、Gravのインストール作業と他の重要なデータソース間で問題を引き起こす心配がありません。もし何かうまくいかない場合も、普通にとてもかんたんに解決できます。

## Content Basics

まず、Gravがコンテンツを保存する場所について理解しましょう。[今後の章](../06.folder-structure/)でより詳しく説明しますが、今のところ覚えておいてほしいのは、すべてのユーザーコンテンツは、Gravの`user/pages/` フォルダに保存されるということです。

現在、そのpagesフォルダには、2つのフォルダがあり、最初のものが　`01.home`で、2つ目が `02.typography` です。フォルダ名の `01.` という部分は、無くても良いのですが、いくつかの点で、便利になります。

まず、ページの順序を明確に定義できます。たとえば、`01` は `02` の前に来ますが、`00` は `01` より前に来ます。

フォルダ名の数字部分は、そのページが、メニューに表示されるように、Gravに明確に伝えてくれもします。重要事項として、`.` までの数字部分は、URLから削除されます。

## Home Page Configuration

`user/config/system.yaml` ファイルには、その __ホームページ__ の場所を設定するオプションがあります。言い換えると、あなたのサイト `http://yoursite.com` のルートから、Gravが指し示す場所を設定できます。

インストールした環境でこの設定ファイルを調べてみると、すでに `/home` の別名として指し示されていることが分かると思います。このドキュメントでは、このままにしておきます。

## Page Editing

**Grav** のページは、**マークダウン** 構文で構成されています。マークダウンは、コンピュータが用意に解析し、HTMLに変換できるプレーンなテキストフォーマット構文です。基本的な記号（例： **太字**、_斜体_、 見出し、リストなど）を使用することで、HTMLの複雑さを知らなくても、かんたんに書けます。マークダウンの利点は、エラー率の低さと、読みやすさ、覚えやすさ、使いやすさなどがあります。

[利用可能なシンタックスの幅広い書き方](/02.content/04.markdown/)については、その例とともにドキュメントを読んでいただくとして、今は次に進みましょう。

トップページをテキストエディタで開いてみます。トップページを制御しているファイルは、`user/pages/01.home/` というフォルダにある `default.md` というファイルです。コンテンツはすべて、`user/pages/` フォルダに作成されます。

そのページをテキストエディタで編集すると、コンテンツは次のようになっているはずです：

```
---
title: Home
body_classes: title-center title-h1h2
---
# Say Hello to Grav!
## installation successful...

Congratulations! You have installed the **Base Grav Package** that provides a **simple page** and the default **Quark** theme to get you started.

!! If you see a **404 Error** when you click `Typography` in the menu, please refer to the [troubleshooting guide](https://learn.getgrav.org/troubleshooting/page-not-found).
```

マークダウンで書くことがいかにかんたんかを理解するために、少し掘り下げましょう。`---` という目印の間にあるものは、[ページヘッダ](/02.content/02.headers/)であり、[YAML](/08.advanced/11.yaml/)という、わかりやすいフォーマットで書かれています。`.md` ファイルに置かれた、このような設定ブロックは、**YAMLフロントマター** として知られています。

```bash
title: Home
body_classes: title-center title-h1h2
```

このブロックは、そのページのHTMLタイトルタグ（ブラウザのタブに表示される文章）を設定します。テーマ編集では、`page.title` 属性から使うこともできます。このページのさまざまなオプションを設定できる[標準的なヘッダーがいくつかあります](/02.content/02.headers/)。他の例として、`menu: 何かのテキスト` もあります。メニューの表示名を上書きする設定です。デフォルトでは、Gravはタイトルをメニューにします。

```markdown
# Say Hello to Grav!
## installation successful...
```

マークダウンのこの `#` や、`ハッシュ記号` 構文は、タイトルを意味します。ひとつの `#` と半角スペースとテキストは、HTMLの `<h1>` 見出しに変換されます。`##` または2つのハッシュ記号は、`<h2>`タグに変換されます。もちろん、THMLで使える`<h6>`タグまで、6つのハッシュ記号：`###### わたしの6段階目の見出し` が使えます。

```markdown
Congratulations! You have installed the **Base Grav Package** that provides a **simple page** and the default **Quark** theme to get you started.
```

これは、HTMLに変換されるとき、普通の `<p>` タグで包まれる、シンプルな段落です。`**` というマークは、太字つまり `<strong>` タグの、かつては `<b>` タグの目印です。斜体は、`_` マークでテキストを包むことで示せます。

```markdown
!! If you see a **404 Error** when you click `Typography` in the menu, please refer to the [troubleshooting guide](https://learn.getgrav.org/troubleshooting/page-not-found).
```

このセクションでは、`markdown-notices` プラグインによってカスタムされたマークダウンを使っています。段落の先頭に、`!` （イクスクラメーションマーク）を、1つ `!` から、4つ `!!!!` まで置くことにより、かんたんに注意書きが作れます。

この概要では、マークダウンを書くための、いくつかの重要なポイントを説明しましたが、[より詳細な説明](/02.content/04.markdown/)をぜひチェックし、十分に理解してください。

> [!TIPS]
> `.md` ファイルを保存するときは、`UTF8` で保存してください。このことで、言語ごとの特別な文字が使えるようになります。

## Adding a New Page

**Grav** においては、新しいページを作るのは、かんたんなことです。ただ、次のようなシンプルなステップを踏むだけです：

1. pagesフォルダ（`user/pages/`）に移動して、新しいフォルダを作ります。今回はたとえば、[明示的なデフォルトの順序](/02.content/01.content-pages/) を使って、`03.mypage` というフォルダにしましょう。
2. テキストエディタを起動し、新しいファイルを作成します。そして次のようなサンプルコードを貼り付けます。

```
---
title: My New Page
---
# My New Page!

This is the body of **my new page** and I can easily use _Markdown_ syntax here.
```

3. このファイルを `user/pages/03.mypage/` に `default.md` として保存します。このことで、**Grav** は、現在のテーマにある **default** テンプレート（`user/themes/quark/templates/default.html.twig`）を使って、ページを表示します。
4. これで終わりです！　ブラウザをリロードして、ページ上部のメニューに新しいページがあることを確認してください。

メニューの **"Typography"** の後に、自動的にページが表示されたことでしょう。メニューの表示を変えたい場合は、`menu: 私のページ` という設定をそのページのフロントマターのダッシュマーク（`---`）の間に追記してください。

**おめでとうございます** Gravで、新しいページができました。Gravでは、もっといろんなことができます。引き続き読み進めて、より高度な可能性とより深い特長を理解してください。

> [!TIPS]
> この新しいページにアクセスするときに問題が発生した場合、（Apacheサーバの場合のみ）`.htaccess` ファイルが無いか、もしくは`.htaccess` ファイルの `RewriteBase` コマンドを編集する必要があるかもしれません。より詳しくは、[トラブルシューティング](/11.troubleshooting/)の章をご覧ください。

