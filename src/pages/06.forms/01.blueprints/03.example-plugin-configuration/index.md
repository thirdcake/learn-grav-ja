---
title: "具体例：プラグイン設定"
layout: ../../../../layouts/Default.astro
---

[前回の例](../02.example-plugin-blueprint/) では、プラグインやテーマでのブループリントの定義方法を説明しました。

今回は、プラグインやテーマに、管理パネルで表示される設定オプションを提供する方法を見ていきましょう。

プラグイン（またはテーマ）に、管理パネルのインターフェースから直接設定できるオプションを持たせたい場合、 blueprints.yaml ファイルに、フォームを入力する必要があります。

たとえば、 **Archives** プラグインの **archives.yaml** ファイルの場合、次のようになります：

```yaml
enabled: true
built_in_css: true
date_display_format: 'F Y'
show_count: true
limit: 12
order:
    by: date
    dir: desc
filter_combinator: and
filters:
    category: blog
```

上記は、プラグインのデフォルト設定です。管理プラグイン無しでこれらの設定を変更するには、 `/user/config/plugins/` フォルダにこのファイルをコピーし、そこで変更する必要があります。

正しくフォーマットされた **blueprints.yaml** ファイルを提供することで、ユーザーは、管理パネルのインターフェースで設定を変更できるようになります。設定が保存されたとき、自動で `/user/config/plugins/archives.yaml` に保存されます（テーマの場合は、 `/user/config/themes/` フォルダ以下に保存されます）。構造は、以下のように始まります：

```yaml
name: Archives
version: 1.3.0
description: The **Archives** plugin creates links for pages grouped by month/year
icon: university
author:
  name: Team Grav
  email: devs@getgrav.org
  url: https://getgrav.org
homepage: https://github.com/getgrav/grav-plugin-archives
demo: http://demo.getgrav.org/blog-skeleton
keywords: archives, plugin, blog, month, year, date, navigation, history
bugs: https://github.com/getgrav/grav-plugin-archives/issues
license: MIT

form:
  validation: strict
  fields:
```

ここからが、必要な部分です。 **archives.yaml** ファイルのすべてのフィールドは、対応するフォーム要素が必要です。たとえば：

**Toggle**

```yaml
enabled:
  type: toggle
  label: Plugin status
  highlight: 1
  default: 1
  options:
      1: Enabled
      0: Disabled
  validate:
       type: bool
```

**Select**

```yaml
date_display_format:
  type: select
  size: medium
  classes: fancy
  label: Date Format
  default: 'jS M Y'
  options:
    'F jS Y': "January 1st 2014"
    'l jS of F': "Monday 1st of January"
    'D, m M Y': "Mon, 01 Jan 2014"
    'd-m-y': "01-01-14"
    'jS M Y': "10th Feb 2014"
```

**Text**

```yaml
limit:
  type: text
  size: x-small
  label: Count Limit
  validate:
    type: number
    min: 1
```

最上位のルート（root）要素（この例では、 `enabled`, `date_display_format`, `limit` ）は、オプション名です。各フィールドの追加コンポーネントにより、そのフィールドの表示方法が決まります。たとえば、そのタイプは、`type` で、そのサイズは、 `size` で、ラベル表示は、 `label` で、そしてオプションのホバーした時に表示される便利なツールチップは、 `help` で決まります。`default` により、デフォルト値を作成し、 `placeholder` により、ユーザーに、改善されたフィールドの見た目を提供します。

残りのフィールドは、フィールドタイプに応じて変更可能です。たとえば、 `select` フィールドタイプは、 `options` リストが必要です。

ネストされたオプションは、ドット表記で指定できます（例： `order.dir` ）

```yaml
order.dir:
  type: toggle
  label: Order Direction
  highlight: asc
  default: desc
  options:
    asc: Ascending
    desc: Descending
```

管理パネルプラグインでは、他にも多くのフィールドが定義されています。それらは、`plugins/admin/themes/grav/templates/forms/fields` にあります。

重要な点として、 **Archives** プラグインの例のように、 `form.validation` が `strict` だったとき、ブループリントのフォームを _すべて_ 追加しなければいけません。そうでなければ、保存時にエラーが表示されます。
管理パネルのインターフェースに、すべてではなく、いくつかのフィールドのみをカスタマイズできるようにしたい場合は、  `form.validation` を `loose` に設定してください。

