---
title: "プラグインの基本"
layout: ../../../layouts/Default.astro
---

Gravは、ページのみを扱うだけで良いように、**シンプル** に、**集中して** 設計されています。基本的な方針は、Gravそれ自体は **超薄く** 作られ、基本的な機能のみが提供されます。たとえば、ルーティングや、マークダウンのHTMLへのコンパイル、Twigテンプレート、そしてキャッシュなどです。

しかし、Gravを成長させ、強力な機能を必要なときに提供したい場合があります。そのために、システムの処理中に、 **イベントフック（event hooks）** が差し込んであります。これにより、**プラグイン** を使って、どんな拡張でもできるようになっています。

<h2 id="powerful">パワフル！</h2>

All the key objects in Grav are accessible through a powerful [Dependency Injection Container](http://en.wikipedia.org/wiki/Dependency_injection).  With Grav's event hooks throughout the entire life cycle, you can access anything that Grav knows about, and manipulate it as you need.  With this system you have complete control to add as much functionality as you need.

The plugins have proved so easy to write, and so flexible and powerful, that we can not stop creating them! We already have [over 300 freely downloadable plugins](https://getgrav.org/downloads/plugins#extras) that do everything from displaying a **sitemap**, providing **breadcrumbs**, displaying blog **archives**, a simple **search engine**, to providing a fully-functional JavaScript-powered **shopping cart**!

The best way to learn what can be done with plugins is to download some of these and look at what they are doing, and how they are doing it. In the next chapter we will [create a simple plugin from scratch](../03.plugin-tutorial/)!

<h2 id="essentials">必要最小限</h2>

All plugins are located in your `user/plugins` folder.  With the base Grav install, there are only two plugins provided: `error` and `problems`.

The `error` plugin is used to handle HTTP errors, like **404 Page Not Found**.

The `problems` plugin is useful for new Grav installations because it detects any issues with your **hosting setup**, **missing folders**, or **permissions** that could cause problems with Grav.  Only the `error` plugin is really essential for proper operation.

Every plugin in the `user/plugins` folder should have a unique name, and that name should closely define the function of the plugin.  Please do not use spaces, underscores, or capital letters in the plugin name.

<h2 id="accessing-plugin-configuration-values-via-twig">Twigからプラグインの設定値にアクセス</h2>

To access plugin configuration settings via Twig (i.e. within a Theme), the general format is:

```twig
config.plugins.pluginname.pluginproperty
```

If plugin name contains dashes you should refer to its properties using :

```twig
config.plugins['plugin-name'].pluginproperty
```

<h2 id="using-flex-in-plugins">プラグイン中にflexを使う</h2>

The recommended way to start using flex in a plugin is to use the devtools and create a plugin with Flex basic support generated for you:
https://learn.getgrav.org/17/basics/installation#option-1-install-from-zip-package

See [Flex plugin section](../06.plugin-flex/)

