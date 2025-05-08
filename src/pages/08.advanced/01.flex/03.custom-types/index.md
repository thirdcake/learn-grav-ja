---
title: "カスタムディレクトリタイプ"
layout: ../../../../layouts/Default.astro
---

多くのサイトで、 **Flex ディレクトリ** を使う主な理由は、独自のカスタムデータタイプを定義できるためです。そのようなカスタムデータタイプは、config 設定で解決できるほどシンプルでも小さくも無く、1ページに収めて表示するには適切ではないようなものです。

Flex ディレクトリは、このような課題を解決するものです。 **Flex タイプ** は、
Flex Directories solve this issue for you. Since **Flex Types** use the common design principles to both configuration and pages, it is really easy to convert your existing configuration and pages to use Flex. You may also have existing forms in the site, which you'd want to manage from the Administration Panel, or maybe you want to display selected comments in your site. These forms can also be converted to use Flex.

The base of a **Flex Type** is its **Blueprint**. The blueprint defines both the form and the fields of the object. It also has some additional configuration, which can be used to customize the directory and its objects, where it shows up in Admin Panel and ACL.
