---
title: プラグインの優先度
layout: ../../../layouts/Default.astro
lastmod: '2025-06-24'
---

複数のプラグインが同じイベントフック（ [Plugins > Event Hooks page for details](../../04.plugins/04.event-hooks/) ）を利用する場合、 "優先度" の順番に、でさまざまな処理が実行されます。優先度は、シンプルな番号です。大きい番号ほど、先に処理されます。

レアケースとして、特定の処理の優先度を入れ替える必要があるかもしれません。このような場合も、オリジナルのプラグインコードに触ること無く可能です。

まず、どのハンドラをどのように調整する必要があるのか、正確に決定してください。これは高度なタスクで、プラグインの `.php` ファイルを読める必要があります。通常は、イベントフックと、ハンドラの関数と、デフォルトの優先度は、プラグインの `onPluginsInitialized()` 関数内にあります。

それから、 `user/config/priorities.yaml` ファイルを作成します。データ構造は、次のようにします：

```yaml
pluginName:
    eventName:
        handlerName: [integer]
```

たとえば、 `essential` というプラグインがあり、 `onPageInitialized` イベントで、 `handlePage` 関数を、優先度 0 で実行するとします。そして、いくつかの他のプラグインの *前に* 実行させる必要があるため、優先度を `100` にする必要があるとしましょう。その場合、 `user/config/priorities.yaml` ファイルに、以下のように追記してください：

```yaml
essential:
    onPageInitialized:
        handlePage: 100
```

