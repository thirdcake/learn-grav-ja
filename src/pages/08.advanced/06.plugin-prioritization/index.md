---
title: "プラグインの優先度"
layout: ../../../layouts/Default.astro
---

複数のプラグインが同じイベントフック（ [Plugins > Event Hooks page for details](../../04.plugins/04.event-hooks/) ）を利用する場合、 "優先度" の順番に、でさまざまな処理が実行されます。優先度は、シンプルな番号です。大きい番号ほど、先に処理されます。

レアケースとして、特定の処理の優先度を入れ替える必要があるかもしれません。このようなことは、オリジナルのプラグインコードに触ること無く可能です。

まず、
First determine precisely which handlers need to be tweaked and how. This is an advanced task that requires that you be able to read the plugin's `.php` file. Normally the event hooks, handler functions, and default priorities can be found in a plugin's `onPluginsInitialized()` function.

それから、 `user/config/priorities.yaml` ファイルを作成します。データ構造は、次のようにします：

```yaml
pluginName:
    eventName:
        handlerName: [integer]
```

So for example, let's say you have a plugin called `essential` that listens to the `onPageInitialized` event, triggering the function `handlePage` with a priority of 0. Let's then say you discover that you need that priority to be `100` to make sure it executes *before* some other plugin. You would add the following to your `user/config/priorities.yaml` file:

```yaml
essential:
    onPageInitialized:
        handlePage: 100
```

