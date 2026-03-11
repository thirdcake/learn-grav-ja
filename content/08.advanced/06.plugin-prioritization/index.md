---
title: プラグインの優先度
layout: ../../../layouts/Default.astro
lastmod: '2025-12-14'
description: 'プラグイン内のイベントフックの実行順序を、直接 PHP を触ることなく、設定ファイルだけで変更する方法を解説します。'
---

複数のプラグインが同じイベントフック（ [Plugins > Event Hooks page for details](../../04.plugins/04.event-hooks/) ）を利用する場合、さまざまな処理が "優先度" の順番で実行されます。  
優先度は、シンプルな番号です。  
大きい番号ほど、先に処理されます。

レアケースとして、特定の処理の優先度を入れ替える必要があるかもしれません。  
このような場合も、オリジナルのプラグインコードに触ることなく、入れ替え可能です。

まず、どのハンドラをどのように調整する必要があるのか、正確に決定してください。  
これは高度なタスクで、プラグインの `.php` ファイルを読める必要があります。  
通常は、イベントフックと、ハンドラの関数と、デフォルトの優先度は、プラグインの `onPluginsInitialized()` 関数内にあります。

それから、 `user/config/priorities.yaml` ファイルを作成します。  
データ構造は、次のようにします：

```yaml
pluginName:
    eventName:
        handlerName: [integer]
```

たとえば、 `essential` というプラグインがあり、元のプラグインでは `onPageInitialized` イベントで、 `handlePage` 関数を、優先度 0 で実行しているとします。  
そして、いくつかの他のプラグインの *前に* 実行させたい事情があり、優先度を `100` に変更する必要があるとしましょう。  
その場合、 `user/config/priorities.yaml` ファイルに、以下のように追記してください：

```yaml
essential:
    onPageInitialized:
        handlePage: 100
```

