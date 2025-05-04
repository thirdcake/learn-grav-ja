---
title: "config 設定"
layout: ../../../../layouts/Default.astro
---

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

If the rendered HTML has dynamic content, render cache can be disabled from the Twig template by {% verbatim %}```{% do block.disableCache() %}```{% endverbatim %}.

