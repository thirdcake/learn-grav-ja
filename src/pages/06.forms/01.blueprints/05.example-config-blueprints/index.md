---
title: 具体例：config 設定のブループリント
layout: ../../../../layouts/Default.astro
lastmod: '2025-08-28'
description: '管理パネルプラグインのサイト設定のフォーム内容をブループリントで設定する方法を解説します。'
---

サイトコンテンツ内に表示する設定は、site.yaml ファイル設定で追加するのが一般的です。

管理パネルプラグインから、そのようなオプションを設定できるようにするには、`user/blueprints/config/site.yaml` にいくつかのフィールドを追記します。  
たとえば：


```yaml
extends@:
    '@parent'

form:
    fields:
        content:

            fields:
                myfield:
                    type: text
                    label: My Field
```

上記は、サイト設定の Content セクションに 'My Field' インプットタイプを追加します。

まったく新しいセクションを追加することもできます。  
たとえば：

```yaml
extends@:
    '@parent'

form:
    fields:
        anothersection:
            type: section
            title: Another Section
            underline: true

            fields:
                myfield:
                    type: text
                    label: A label
                    size: large
```

