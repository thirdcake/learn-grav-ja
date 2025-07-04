---
title: チュートリアル
layout: ../../../layouts/Default.astro
lastmod: '2025-06-25'
description: 'Grav の基本操作をチュートリアル形式で学びましょう。ページ構造や Markdown によるコンテンツ作成を解説します。'
---

前の章の手順どおりに [Grav のインストール](../03.installation/) が完了していることを前提に、ここでは引き続き Grav を操作してみて、使い方に慣れていきましょう。

Grav はデータベースを必要としないため、非常に簡単に操作できますし、 Grav のインストール作業と他の重要なデータソース間で問題を引き起こす心配がありません。  
もし何かうまくいかない場合も、たいていの場合、とても簡単に解決できます。

<h2 id="content-basics">コンテンツの基本</h2>

まず、 Grav がコンテンツを保存する場所について理解しましょう。  
[今後の章](../06.folder-structure/) でより詳しく説明しますが、今のところ覚えておいてほしいのは、すべてのユーザーコンテンツは、 Grav の `user/pages/` フォルダに保存されるということです。

現在（通常のインストール直後）、その pages フォルダには、2つのフォルダがあり、最初のものが `01.home` で、2つ目が `02.typography` となっているはずです。  
フォルダ名の `01.` という部分は、無くても良いのですが、あることでいくつかの便利な機能が提供されます。

まず、フォルダ名の数字部分があることにより、ページの順序が明確に定義されます。  
たとえば、`01` は `02` の前に来ますが、`00` は `01` より前に来ます。

そして同時に、フォルダ名の数字部分があることで、 Grav はそのページをメニューに表示します。  
ここで大事な事は、 `.` までの数字部分は、 URL から削除されます。

<h2 id="home-page-configuration">ホームページの設定</h2>

> [!訳注]  
> ここでの "ホームページ" とは、webサイトのページ一般を指すのではなく、ドメインの一番上にあるトップページことで、たとえば `https://yoursite.com` にアクセスしたときに表示されるページのことです。

`user/config/system.yaml` ファイルには、その **ホームページ** の場所を設定するオプションがあります。  
言い換えると、あなたのサイト `http://yoursite.com` のルートフォルダから、 Grav が指し示す場所を設定できます。

インストールした環境でこの設定ファイルを確認すると、すでに `/home` の別名として指し示されていることが分かると思います。  
このドキュメントでは、このままにしておきます。

<h2 id="page-editing">ページ編集</h2>

**Grav** のページは、 **マークダウン** 構文で構成されています。  
マークダウンは、コンピュータが容易に解析し、 HTML に変換できるプレーンなテキストフォーマット構文です。  
基本的な記号（例： **太字**、_斜体_、 見出し、リストなど）を使用することで、 HTML の複雑さを知らなくても、簡単に書けます。  
マークダウンの利点は、エラー率の低さと、読みやすさ、覚えやすさ、使いやすさなどにあります。

[利用可能な構文の幅広い書き方](../../02.content/04.markdown/) については、その例とともにドキュメントを読んでいただくとして、今は先に進みましょう。

トップページをテキストエディタで開いてみます。  
トップページを制御しているファイルは、 `user/pages/01.home/` というフォルダにある `default.md` というファイルです。  
コンテンツはすべて、 `user/pages/` フォルダに作成されます。

そのページをテキストエディタで編集すると、コンテンツは次のようになっているはずです：

```markdown
---
title: Home
body_classes: title-center title-h1h2
---
# Say Hello to Grav!
## installation successful...

Congratulations! You have installed the **Base Grav Package** that provides a **simple page** and the default **Quark** theme to get you started.

!! If you see a **404 Error** when you click `Typography` in the menu, please refer to the [troubleshooting guide](https://learn.getgrav.org/troubleshooting/page-not-found).
```

マークダウンで書くことがいかに簡単かを理解するために、少し掘り下げてみましょう。  
`---` という目印の間にあるものは、 [ページヘッダ](../../02.content/02.headers/) であり、 [YAML](../../08.advanced/11.yaml/) という、わかりやすいフォーマットで書かれています。  
`.md` ファイルに置かれた、このような設定ブロックは、 **YAML フロントマター** として知られています。

```yaml
title: Home
body_classes: title-center title-h1h2
```

このブロックは、そのページの HTML タイトルタグ（ブラウザのタブに表示される文章）を設定します。  
テーマ編集では、 `page.title` 属性から使うこともできます。  
このページのさまざまなオプションを設定できる [標準的なヘッダーがいくつかあります](../../02.content/02.headers/) 。  
他の例として、 `menu: 何かのテキスト` もあります。メニューの表示名を上書きする設定です。  
デフォルトでは、 Grav はタイトルをメニューに表示します。

```markdown
# Say Hello to Grav!
## installation successful...
```

マークダウンのこの `#` や `ハッシュ記号` 構文は、タイトルを意味します。  
ひとつの `#` と半角スペースとテキストは、 HTML の `<h1>` 見出しに変換されます。  
`##` または2つのハッシュ記号は、 `<h2>` タグに変換されます。  
もちろん、 HTML で使える `<h6>` タグまで、6つのハッシュ記号： `###### わたしの6段階目の見出し` が使えます。

```markdown
Congratulations! You have installed the **Base Grav Package** that provides a **simple page** and the default **Quark** theme to get you started.
```

これは、 HTML に変換されるとき、普通の `<p>` タグで包まれる、シンプルな段落です。  
`**` というマークは、太字つまり `<strong>` タグの（かつては `<b>` タグの）目印です。  
斜体は、 `_` マークでテキストを包むことで示せます。

```markdown
!! If you see a **404 Error** when you click `Typography` in the menu, please refer to the [troubleshooting guide](https://learn.getgrav.org/troubleshooting/page-not-found).
```

このセクションでは、 `markdown-notices` プラグインによってカスタムされたマークダウンを使っています。  
段落の先頭に、 `!` （イクスクラメーションマーク）を、1つ `!` から、4つ `!!!!` まで置くことにより、簡単に注意書きが作れます。

この概要では、マークダウンを書くための、いくつかの重要なポイントを説明しましたが、 [より詳細な説明](../../02.content/04.markdown/) をぜひチェックし、十分に理解してください。

> [!Info]  
> `.md` ファイルを保存するときは、`UTF8` で保存してください。このことで、言語ごとの特殊文字が使えるようになります。

<h2 id="adding-a-new-page">新しいページの追加</h2>

**Grav** で新しいページを作るのは、簡単なことです。次のような単純なステップを踏むだけです：

1. pages フォルダ（ `user/pages/` ）に移動して、新しいフォルダを作ります。今回はたとえば、 [明示的なデフォルトの順序](../../02.content/01.content-pages/) を利用して、 `03.mypage` というフォルダにしましょう。
2. テキストエディタを起動し、新しいファイルを作成します。そして次のようなサンプルコードを貼り付けます。
   ```
   ---
   title: My New Page
   ---
   # My New Page!
   
   This is the body of **my new page** and I can easily use _Markdown_ syntax here.
   ```
3. このファイルを `user/pages/03.mypage/` に `default.md` として保存します。このことで、 **Grav** は、現在のテーマにある **default** テンプレート（ `user/themes/quark/templates/default.html.twig` ）を使って、ページを表示します。
4. これで終わりです！　ブラウザをリロードして、ページ上部のメニューに新しいページがあることを確認してください。

メニューの **"Typography"** の後に、自動的にページが表示されたことでしょう。  
メニューの表示を変えたい場合は、 `menu: 私のページ` という設定をそのページのフロントマターのダッシュマーク（ `---` ）の間に追記してください。

**おめでとうございます！**  
Grav で、新しいページができました。  
Grav では、もっといろんなことができます。  
引き続き読み進めて、より高度な可能性と、より深い特長を理解してください。

> [!Info]  
> この新しいページにアクセスするときに問題が発生した場合、（ Apache サーバの場合のみ） `.htaccess` ファイルが無いか、もしくは `.htaccess` ファイルの `RewriteBase` コマンドを編集する必要があるかもしれません。より詳しくは、 [トラブルシューティング](../../11.troubleshooting/) の章をご覧ください。

