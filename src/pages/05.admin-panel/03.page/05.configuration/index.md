---
title: "config 設定"
layout: ../../../../layouts/Default.astro
---

> [!訳注]  
> このページの内容は、 [`アカウント>config 設定`](../../03.accounts/03.configuration/) と同じで、画像もリンク切れしており、 管理パネルの Pages に configuration タブも無いので、コピペミスか何かだと思います。詳しい方がおられたら、教えてください。

```
![Compatibility Tab](page-configuration.png)
```

| Option                        | Description |
| :-----                        | :----- |
| **Admin event compatibility** | Enables `onAdminSave` and `onAdminSaveAfter` events for plugins. Enabled by default. |

```
![Caching Tab](page-configuration.png)
```

For more information, see Flex Objects.

| Option                        | Description |
| :-----                        | :----- |
| **Enable Index Caching** | Index caching speeds up searches by creating temporary lookup indexes for the queries. |
| **Index Cache Lifetime (seconds)** | Lifetime for index caching in seconds. |
| **Enable Object Caching** | Object caching speeds up loading the object data and images. |
| **Object Cache Lifetime (seconds)** | Lifetime for object caching in seconds. |
| **Enable Render Caching** | Render caching speeds up rendering the content by caching the resulting HTML. |
| **Render Cache Lifetime (seconds)** | Lifetime for render caching in seconds. |

If the rendered HTML has dynamic content, render cache can be disabled from the Twig template by `{% do block.disableCache() %}`.

