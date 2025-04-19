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

The Quark theme has an `scss/` folder that contains a variety of `.scss` files. These should be compiled into the `css-compiled/` folder.

You can create a file called `scss/theme/_custom.scss` and import it to the `theme.scss` file at the bottom using `@import 'theme/custom';`. There are several great benefits of putting your code in this file:

1. The resulting changes will be compiled into the `css-compiled/theme.min.css` file along with all the other CSS.
2. You have access to all the variables and mix-ins that are available to any of the other SCSS used in the theme.
3. You have access to all the standard SCSS features and functionality to make development easier.

An example of this file would be:

**_custom.scss**

```css
body {
    a {
        color: darken($core-accent, 30%);
    }
}
```

The downside to this approach is that this file is overwritten during any *theme upgrade*, so you should ensure you create a backup of any custom work you do.  This issue is resolved by using theme inheritance as described below.

## Wellington SCSS

[Wellington](https://github.com/wellington/wellington) is a native wrapper for [libsass](http://libsass.org/) available for both Linux and MacOS. It provides a much faster solution for compiling SCSS than the default Ruby-based scss compiler.  By faster we mean about **20X faster!**. It's super easy to install (via brew):

```bash
brew install wellington
```

To take advantage of it to compile an `scss` folder into a `css-compiled` folder as in the example above you can [use this gist](https://gist.github.com/rhukster/bcfe030e419028422d5e7cdc9b8f75a8).

> [!Info]  
> Wellington is what we have been using for all _Team Grav_ themes and it's been working great!


## Theme Inheritance

This is the preferred approach to modifying or customizing a theme, but it does require a little bit more setup.

The basic concept is that you define a theme as the **base-theme** that you are inheriting from, and provide **only the bits you wish to modify** and let the base theme handle the rest. The great benefit to this is that you can more easily keep the base theme updated and current without directly impacting your customized inherited theme.

There are two ways to inherit from an existing theme:

1. Using the Command Line Interface (CLI) with the DevTools plugin.
2. Manually.

### Inheriting using the CLI

As discussed in the [Theme Tutorial](https://learn.getgrav.org/16/themes/theme-tutorial), you can create a new theme using the DevTools plugin. But you can also inherit from an existing theme. The procedure is simple.

1. [Install the DevTools plugin](https://learn.getgrav.org/16/themes/theme-tutorial#step-1-install-devtools-plugin) if it is not already done.
2. Then follow the [Create Base Theme](https://learn.getgrav.org/16/themes/theme-tutorial#step-2-create-base-theme) procedure, but when asked to `Please choose a template type`, type `inheritance`. If Quark is the only theme, it will be displayed as option 0. So type `0` to inherit from Quark. Your new inherited theme will be created.
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

