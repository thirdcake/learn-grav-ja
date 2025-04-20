---
title: "カスタマイズ"
layout: ../../../layouts/Default.astro
---

テーマのカスタマイズ方法は、たくさんあります。Gravは、あなたの創造性の邪魔をしません。とはいえ、カスタムをかんたんにするためにGravが提供する特長や機能がいくつかあります。

<h2 id="custom-css">CSSのカスタム</h2>

テーマをカスタムする、最もかんたんな方法は、独自の `custom.css` を追加する方法です。デフォルトテーマの **Quark** では、`css/custom.css` ファイルを **アセット管理** から参照します。幸運にも、 **アセット管理** がこれを処理するので、ファイルが見つからなければ、それはHTMLに追加されません。

しかし、`custom.css` をQuarkの `css/` フォルダに追加すれば、アセット管理はこれを参照してくれます。注意すべきは、CSS要素が十分な[詳細度](http://www.smashingmagazine.com/2007/07/27/css-specificity-things-you-should-know/) を持って上書きしているかどうかだけです。たとえば：

**custom.css**

```css
body a {
    color: #CC0000;
}
```

上記は、デフォルトのリンクの色を上書きします。もとの色の代わりに、 **赤** を使うでしょう。

<h2 id="custom-scss-less">SCSS/LESSのカスタム</h2>

custom.css の追加の次のステップは、`_custom.scss` ファイルを使うことです。Quark は、[SCSS](https://sass-lang.com/) を使って書かれています。SCSSは、CSS互換のプリプロセッサで、[vriablesや、nested structures、 partials、 imports、 operators そして mix-ins](http://sass-lang.com/guide) を使って、CSSをより効果的に書くことができます。

このように聞くと、最初は、少しやる気をくじかれるかもしれませんが、SCSSは、多くても少なくても好きなだけ使えばよく、最初のうちは、トラブルが起きれば伝統的なCSSに戻れば良いのです。約束です！

Quarkテーマは、`scss/` フォルダに多様な`.scss` ファイルを持ちます。これらは、`css-compiled/` フォルダにコンパイルされます。

`scss/theme/_custom.scss` というファイルを作ることができます。それを `theme.scss` ファイルの最後に `@import 'theme/custom';` としてインポートできます。このファイルにコードを入れておくと、いくつかのメリットが得られます：

1. 変更の結果が、他のCSSとともに `css-compiled/theme.min.css` にコンパイルされます。
1. 他のSCSSファイルがテーマで利用している変数やミックスインにアクセスできます。
1. 標準的なSCSSの特長や機能にアクセスできるので、開発がかんたんになります。

たとえば、このファイルを次のようにできます：

**_custom.scss**

```css
body {
    a {
        color: darken($core-accent, 30%);
    }
}
```

この方法のデメリットは、 _テーマ・アップグレード_ のときに、このファイルが上書きされてしまうことです。そのため、きちんとバックアップを取っておいてください。この問題は、次に書くテーマの継承を使うことで解決されます。

## Wellington SCSS

> [!訳注]  
> wellingtonは5年以上コミットがないようですし、libsassも非推奨になっているので、以下の内容がどこまで現在も通用するものなのかわかりません。

[Wellington](https://github.com/wellington/wellington) は、[libsass](https://sass-lang.com/libsass/) のネイティブなラッパーです。LinuxとMacOSで使えます。デフォルトのRubyベースのscssコンパイラよりも、ずっと速くコンパイルします。どれくらい早いかというと、 **20倍くらい早い！** です。インストールはかんたんです（brewを使います）：

```bash
brew install wellington
```

上の例のように、`scss` フォルダから `css-compiled` フォルダへコンパイルするため、 [このgistを利用できます](https://gist.github.com/rhukster/bcfe030e419028422d5e7cdc9b8f75a8).

> [!Info]  
> Wellington は、 _Gravチーム_ のすべてのテーマに使われています。とても素晴らしいものです！


<h2 id="theme-inheritance">テーマの継承</h2>

これは、テーマの修正やカスタマイズにより良いアプローチです。しかし、多少のセットアップを伴います。

基本的な考え方はこうです： 継承元となる **ベーステーマ** を決めます。 **ほんの少し修正するだけ** で、残りはベーステーマに任せます。この方法のメリットは、ベーステーマのアップーデートが容易であるということと、継承したテーマに直接影響を及ぼさないということです。

既存のテーマから継承するには、2つの方法があります：

1. コマンドラインインターフェース（CLI） を使って、DevToolsプラグインを使います。
2. 手作業でやります。

<h3 id="inheriting-using-the-cli">CLIを使った継承</h3>

[テーマのチュートリアル](../02.theme-tutorial/) で解説したとおり、DevToolsプラグインで、新しいテーマを作れます。DevToolsでは、同時に、既存テーマから継承することもできます。処理はシンプルです。

1. もしまだであれば、[DevToolsプラグインをインストールしてください](../02.theme-tutorial/#step-1-install-devtools-plugin) 。
2. 続けて、[ベーステーマを作ってください](../02.theme-tutorial/#step-2-create-base-theme) 。ただし、`Please choose a template type` と聞かれたら、 `inheritance` と答えてください。テーマにQuarkしか無ければ、選択肢に0として表示されます。 `0` と入力してください。Quarkから継承します。継承されたテーマが作られます。
3. Copy all the options from the theme YAML file you are inheriting from (or from the `user/config/themes` folder if you have customized it) at the top of the newly created YAML configuration file of your theme: `/user/themes/mytheme/mytheme.yaml`.
4. Copy the “form” section from `/user/themes/quark/blueprints.yaml` file into `/user/themes/mytheme/blueprints.yaml` in order to include the customizable elements of the theme in the admin. (Or simply replace the file and edit its content.)
5. Change your default theme to use your new **mytheme** by editing the `pages: theme:` option in your `user/config/system.yaml` configuration file:

   ```yaml
   pages:
     theme: mytheme
   ```

### Inheriting manually

To achieve this you need to follow these steps:

1. Create a new folder: `user/themes/mytheme` to house your new theme.
2. Copy the theme YAML file from the theme you're inheriting (or from the `user/config/themes` folder if you have customized it) to `/user/themes/mytheme/mytheme.yaml` and add the following content (replacing `user/themes/quark` with the name of the theme you are inheriting):

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
   
   NOTE: Your `mytheme.yaml` must single quote the prefixes in 1.7. Older documentation shows no single-quotes sunch as `- user/themes/mytheme
         - user/themes/quark`. The incorrect quoting in the `mytheme.yaml` may result in a fatal error upon activating your new theme `Template "@images/grav-logo.svg" is not defined in "partials/logo.html.twig" at line 7.`
   
4. Copy the `/user/themes/quark/blueprints.yaml` file into `/user/themes/mytheme/blueprints.yaml` in order to include the customizable elements of the theme in the admin.

5. Change your default theme to use your new **mytheme** by editing the `pages: theme:` option in your `user/config/system.yaml` configuration file:

   ```yaml
   pages:
     theme: mytheme
   ```

6. Create a new theme Class file that can be used to add advanced event-driven functionality. Create a `user/themes/mytheme/mytheme.php` file:

   ```php
   <?php
   namespace Grav\Theme;

   class Mytheme extends Quark
   {
       // Some new methods, properties etc.
   }
   ?>
   ```

You have now created a new theme called **mytheme** and set up the streams so that it will first look in the **mytheme** theme first, then try **quark**.  So in essence, Quark is the base-theme for this new theme.

You can then provide just the files you need, including **JS**, **CSS**, or even modifications to **Twig template files** if you wish.

### Using SCSS

In order to modify specific **SCSS** files, we need to use a little configuration so it knows to look in your new `mytheme` location first, then `quark` second. This requires a couple of things.

1. First, you need to copy over the main SCSS file from quark that contains all the `@import` calls for various sub files. So, copy the `theme.scss` file from `quark/scss/` to `mytheme/scss/` folder.
2. While inside the `theme.scss` file, change the beginning of all the import lines to `@import '../../quark/scss/theme/';` so it will know to use the files from the quark theme. So, for example the first line will be `@import '../../quark/scss/theme/variables';`.
3. Add `@import 'theme/custom';` at the very bottom of the `theme.scss` file.
4. The next step is to create a file located at `mytheme/scss/theme/_custom.scss`. This is where your modifications will go.
5. Copy the `gulpfile.js` and `package.json` files into the base folder of the new theme.

In order to compile the new scss for the **mytheme** you will need to open up terminal and navigate to the theme folder. Quark uses gulp to compile the sass so you will need those installed and yarn for the dependencies. Run `npm install -g gulp`, `yarn install`, and then `gulp watch`. Now, any changes made to the files will be recompiled.

