---
title: 具体例：ブループリントの設定
layout: ../../../../layouts/Default.astro
lastmod: '2025-04-27'
---
サイトコンテンツ内に表示する設定を、site.yaml ファイルに設定で追加するのは、一般的なことです。

管理パネルプラグインから、そのようなオプションを設定できるようにするには、`user/blueprints/config/site.yaml` にいくつかのフィールドを追記します。たとえば：


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

まったく新しいセクションを追加することもできます。たとえば：

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

