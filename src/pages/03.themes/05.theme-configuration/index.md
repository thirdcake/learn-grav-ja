---
title: "テーマ設定"
layout: ../../../layouts/Default.astro
---

Gravでは、TwigやPHPファイルから、テーマ設定やブループリント（設計図）情報へかんたんにアクセスできます。

<h2 id="accessing-theme-blueprint-information">ブループリント情報へのアクセス</h2>

現在有効なテーマの `blueprints.yaml` ファイルの情報は、`theme` オブジェクトにあります。具体例として、次のような `blueprins.yaml` ファイルを使いましょう：

```yaml
name: Antimatter
slug: antimatter
type: theme
version: 1.7.0
description: "Antimatter is the default theme included with **Grav**"
icon: empire
author:
  name: Team Grav
  email: devs@getgrav.org
  url: https://getgrav.org
homepage: https://github.com/getgrav/grav-theme-antimatter
demo: http://demo.getgrav.org/blog-skeleton
keywords: antimatter, theme, core, modern, fast, responsive, html5, css3
bugs: https://github.com/getgrav/grav-theme-antimatter/issues
license: MIT
```

これらの情報には、標準的な **ドット構文** を使い、 `grav.theme` からアクセス可能です。

```twig
Author Email: {{ grav.theme.author.email }}
Theme License: {{ grav.theme.license }}
```

プラグインからは、同じ情報にPHP構文を使ってアクセスできます：

```php
$theme_author_email = $this->grav['theme']['author']['email'];
$theme_license = $this->grav['theme']['license'];
```

<h2 id="accessing-theme-configuration">テーマ設定へのアクセス</h2>

テーマには、設定ファイルもあります。テーマの設定ファイルは、`<テーマ名>.yaml` というファイルです。デフォルトでは、このファイルは、テーマのルートフォルダ（`user/themes/<テーマ名>/`）にあります。

テーマのデフォルトのYAMLファイルを変更するのは、**強く** 非推奨ですので、その代わり、`user/config/themes` フォルダの設定で上書きしてください。これにより、テーマのオリジナルの設定が残り、変更部分に素早く対応できたり、必要な場合はもとに戻すこともできます。

たとえば、Antimatter テーマについて検討してみましょう。デフォルトでは、`antimatter.yaml` ファイルが、テーマのルートフォルダにあります。この設定ファイルは、次のようになっています：

```yaml
enabled: true
color: blue
```

これは、シンプルなファイルですが、同時に、テーマで設定できるものが何なのか知ることができます。これらの設定を、新しいものに上書きしてみましょう。

`user/config/themes/antimatter.yaml` に、ファイルを作成して下さい。そして次のように入力してください。

> `enabled` は、ここでは繰り返さないことに注意してください。。設定ファイルが、単純に置き換えられるのではなく、マージされるとすれば、それは明示されていたほうが良いです。

```yaml
color: red
info: Grav is awesome!
```

そして、テーマテンプレート中、`grav.theme.config` オブジェクトを使って、これらの変数にアクセスできます：

```
<h1 style="color:{{ grav.theme.config.color|e }}">{{ grav.theme.config.info|e }}</h1>
```

これは、次のようにレンダリングされます：

<h1 style="color:red">Grav is awesome!</h1>

PHPでは、現在のテーマの設定は、次のようにアクセス可能です：

```php
$color = $this->grav['theme']->config()['color'];
$info = $this->grav['theme']->config()['info'];
```

シンプルですね！ テーマの設定は無限です。好きなように使ってください！ :)

<h3 id="alternative-notation">その他の注意事項</h3>

次のような別名（エイリアス）も動きます：

```twig
Theme Color Option: {{ config.theme.color_option|e }}
   or
Theme Color Option: {{ theme_var(color_option)|e }}
   or
Theme Color Option: {{ grav.themes.antimatter.color_option|e }} [AVOID!]
```

**`grav.themes.<テーマ名>` がサポートされているとしても、適切に継承できなくなるので、使わないほうが良いです**

