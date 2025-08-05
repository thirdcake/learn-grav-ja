---
title: デザインのカスタマイズ
layout: ../../../layouts/Default.astro
lastmod: '2025-08-05'
description: '既存のテーマを活かしつつ、オリジナルのデザインや CSS スタイルをカスタマイズする方法を、いくつかの段階に分けて解説します'
---

テーマのカスタマイズ方法は、たくさんあります。  
Grav は、あなたの創造性の邪魔をしません。  
とはいえ、カスタムを簡単にするために、 Grav が提供する特長や機能がいくつかあります。

<h2 id="custom-css">CSSのカスタム</h2>

テーマをカスタムする、最も簡単な方法は、独自の `custom.css` を追加する方法です。  
デフォルトテーマの **Quark** では、 `css/custom.css` ファイルを **アセットマネージャー** から参照します。  
幸運にも、 **アセットマネージャー** がこれを処理するので、ファイルが見つからなければ、それは HTML に追加されません。

しかし、`custom.css` を Quark の `css/` フォルダに追加すれば、アセットマネージャーはこれを参照してくれます。  
注意すべきは、 CSS 要素が十分な [詳細度](http://www.smashingmagazine.com/2007/07/27/css-specificity-things-you-should-know/) を持って上書きしているかどうかだけです。  
たとえば：

**custom.css**

```css
body a {
    color: #CC0000;
}
```

上記は、デフォルトのリンクの色を上書きします。  
もとの色の代わりに、 **赤** を使うでしょう。

<h2 id="custom-scss-less">SCSS/LESSのカスタム</h2>

custom.css の追加の次のステップは、 `_custom.scss` ファイルを使うことです。  
Quark は、 [SCSS](https://sass-lang.com/) を使って書かれています。  
SCSS は、 CSS 互換のプリプロセッサで、 [vriables や、 nested structures、 partials、 imports、 operators そして mix-ins](http://sass-lang.com/guide) を使って、 CSS をより効率的に書くことができます。

このように聞くと、最初は、少しやる気をくじかれるかもしれませんが、 SCSS は、多くても少なくても好きなだけ使えばよく、最初のうちは、トラブルが起きれば伝統的な CSS に戻れば良いのです。  
約束です！

Quark テーマは、 `scss/` フォルダに多様な `.scss` ファイルを持ちます。  
これらは、 `css-compiled/` フォルダにコンパイルされます。

`scss/theme/_custom.scss` というファイルを作ることができます。  
それを `theme.scss` ファイルの最後に `@import 'theme/custom';` としてインポートできます。  
このファイルにコードを入れておくと、いくつかのメリットが得られます：

1. 変更の結果が、他のCSSとともに `css-compiled/theme.min.css` にコンパイルされます。
1. 他の SCSS ファイルがテーマで利用している変数やミックスインにアクセスできます。
1. 標準的な SCSS の特長や機能にアクセスできるので、開発が簡単になります。

たとえば、このファイルを次のようにできます：

**_custom.scss**

```css
body {
    a {
        color: darken($core-accent, 30%);
    }
}
```

この方法のデメリットは、 _テーマ・アップグレード_ のときに、このファイルが上書きされてしまうことです。  
そのため、きちんとバックアップを取っておいてください。  
この問題は、次に書くテーマの継承を使うことで解決されます。

## Wellington SCSS

> [!訳注]  
> wellington は5年以上コミットがないようですし、 libsass も 2025 年現在非推奨になっているので、以下の内容がどこまで現在も通用するものなのかわかりません。

[Wellington](https://github.com/wellington/wellington) は、 [libsass](https://sass-lang.com/libsass/) のネイティブなラッパーです。  
Linux と MacOS で使えます。  
デフォルトの Ruby ベースの scss コンパイラよりも、ずっと速くコンパイルします。  
どれくらい早いかというと、 **20倍くらい早い！** です。  
インストールは簡単です ( brew を使います):

```bash
brew install wellington
```

上の例のように、 `scss` フォルダから `css-compiled` フォルダへコンパイルするため、 [この gist を利用できます](https://gist.github.com/rhukster/bcfe030e419028422d5e7cdc9b8f75a8).

> [!Info]  
> Wellington は、 _Gravチーム_ のすべてのテーマに使われています。とても素晴らしいものです！


<h2 id="theme-inheritance">テーマの継承</h2>

これは、テーマの修正やカスタマイズにより良いアプローチです。  
しかし、多少のセットアップを伴います。

基本的な考え方はこうです：  
継承元となる **ベーステーマ** を決めます。  
**ほんの少し修正するだけ** で、残りはベーステーマに任せます。  
この方法のメリットは、ベーステーマのアップーデートが容易であるということと、継承したテーマに直接影響を及ぼさないということです。

既存のテーマから継承するには、 2 つの方法があります：

1. コマンドラインインターフェース（CLI） を使って、DevToolsプラグインを使います。
2. 手作業でやります。

<h3 id="inheriting-using-the-cli">CLIを使った継承</h3>

[テーマのチュートリアル](../02.theme-tutorial/) で解説したとおり、 DevTools プラグインで、新しいテーマを作れます。  
DevTools では、同時に、既存テーマから継承することもできます。  
処理はシンプルです。

1. もしまだであれば、 [DevTools プラグインをインストールしてください](../02.theme-tutorial/#step-1-install-devtools-plugin) 。
2. 続けて、 [ベーステーマを作ってください](../02.theme-tutorial/#step-2-create-base-theme) 。ただし、 `Please choose a template type` と聞かれたら、 `inheritance` と答えてください。テーマに Quark しか無ければ、選択肢に 0 として表示されます。 `0` と入力してください。 Quark から継承します。継承されたテーマが作られます。
3. 継承元のテーマの YAML ファイルの設定（もしくは、カスタマイズ済みなら `user/config/themes` フォルダの設定）を、新しく作った `/user/themes/mytheme/mytheme.yaml` の YAML 設定へ、すべてコピーしてください。
4. `/user/themes/quark/blueprints.yaml` ファイルの "form" セクションを `/user/themes/mytheme/blueprints.yaml` へコピーしてください。これは、管理パネルでカスタマイズできるようにするためです（もしくは、単に、ファイルを入れ替えて、編集するのでもかまいません）。
5. 新しい **mytheme** にデフォルトテーマを変更してください。`user/config/system.yaml` 設定ファイルの `pages: theme:` を編集します。

   ```yaml
   pages:
     theme: mytheme
   ```

<h3 id="inheriting-manually">手作業の継承</h3>

次のステップを踏んでください：

1. 新しいフォルダを作ります： `user/themes/mytheme` テーマを入れるためのフォルダです。
2. 継承元のテーマのYAMLファイル（もしくは、カスタマイズ済みならば `user/config/themes` フォルダの設定）を、`/user/themes/mytheme/mytheme.yaml` にコピーしてください。そして、以下のコンテンツを追記してください（`user/themes/quark` は、継承元のテーマ名にしてください）：

   ```yaml   
   streams:
     schemes:
       theme:
         type: ReadOnlyStream
         prefixes:
           '':
             - 'user://themes/mytheme'
             - 'user://themes/quark'

   ```
   
   注意： Grav 1.7 では、`mytheme.yaml` は、prefixes にシングルクオート（`'`）で囲んでください。古いドキュメントでは、シングルクオートがありませんでした。`-user/themes/mytheme, - user/themes/quark` のように。`mytheme.yaml` で間違った使い方をすると、致命的な（fatal）エラーになります。
   
4. `/user/themes/quark/blueprints.yaml` ファイルを `/user/themes/mytheme/blueprints.yaml` にコピーしてください。管理パネルでテーマのカスタマイズができるようにするためです。

5. デフォルトテーマを、新しい **mytheme** に変更してください。`user/config/system.yaml` 設定ファイルの `pages: theme:` 設定を以下のように編集します：

   ```yaml
   pages:
     theme: mytheme
   ```

6. テーマ Class ファイルを作ってください。発展的な、イベント駆動の機能を追加するために使われるものです。`user/themes/mytheme/mytheme.php` ファイルを作ってください：

   ```php
   <?php
   namespace Grav\Theme;

   class Mytheme extends Quark
   {
       // Some new methods, properties etc.
   }
   ?>
   ```

これで、 **mytheme** という新しいテーマが作られました。最初に **mytheme** テーマを探し、その後 **quark** テーマを探すろいうストリームが設定されています。  
つまり、 Quark はこの新しいテーマのベーステーマであるということです。

あとは、必要なファイルを追加していくだけです。  
たとえば、**JS** 、 **CSS** などで、さらにお望みなら **Twig テンプレートファイル** を修正しても良いです。

<h3 id="using-scss">SCSSの利用</h3>

特定の **SCSS** ファイルを修正するため、少し設定が必要です。  
新しい `mytheme` を最初に、それから `quark` を2つ目に、探すように知らせなければいけません。  
これには、いくつかの設定をします。

1. まず、さまざまなサブファイルを `@import` で呼び出しているメインの SCSS ファイルをコピーしなければいけません。`quark/scss/` から、 `mytheme/scss/` へ、`theme.scss` ファイルをコピーしてください。
2. この `theme.scss` ファイル中、インポートしている行の始まりを、`@import '../../quark/scss/theme/';` に変更してください。これにより、quarkテーマからインポートファイルを利用することを知らせます。たとえば、最初の行はこうなります：`@import '../../quark/scss/theme/variables';`
3. `theme.scss` ファイルの最後に、`@import 'theme/custom';` を追記してください。
4. 次のステップでは、`mytheme/scss/theme/_custom.scss` ファイルを作ってください。このファイルが、修正可能なものになります。
5. `gulpfile.js` ファイルと、`package.json` ファイルを、テーマのベースフォルダにコピーしてください。

**mytheme** 用の新しいSCSSファイルをコンパイルするために、ターミナルを開いて、テーマフォルダまで移動してください。  
Quark は sass のコンパイルに gulp を使っているので、それらがインストールされ、依存関係が yarn されている必要があります。  
`npm install -g gulp` を実行し、`yarn install` を実行してください。  
それから、`gulp wathc` を実行します。  
そうすると、ファイルにどんな変更を加えても、再コンパイルされるようになります。

