---
title: 'Config 設定画面'
lastmod: '2025-08-06T00:00:00+09:00'
description: '管理パネルの Flex Objects の設定画面から、設定できることの概要を解説します。'
weight: 30
params:
    srcPath: /advanced/flex/administration/configuration
---
**Config 設定** には、 **Flex ディレクトリ** の共通事項を設定します。

これらの設定は、通常、 flex ディレクトリの設定を変更するのに使われたり、オブジェクトのデフォルトを設定するのに使われたり、レイアウトのレンダリングを変更したりするのに使われます。

> [!Info]  
> 設定項目は、すべてのディレクトリで違います。このドキュメントでは、すべてのディレクトリに共通する設定のみを記載しています。

#### コントロール{#controls}

ページの上部に、管理コントロール部分があります。

- **Back**: [**コンテンツリスト**](../01.views-list/) に戻る
- **Save**: config 設定を保存し、 [**コンテンツリスト**](../01.views-list/) に戻る

### キャッシュタブ{#caching-tab}

| オプション | 説明 |
| :-----  | :----- |
| **Enable Index Caching** | インデックスキャッシュは、クエリの一時的な検索インデックスを作成することで、検索をスピードアップさせます。 |
| **Index Cache Lifetime (seconds)** | インデックスキャッシュの有効秒数。 |
| **Enable Object Caching** | オブジェクトキャッシュは、オブジェクトデータや画像の読み込みをスピードアップさせます。 |
| **Object Cache Lifetime (seconds)** | オブジェクトキャッシュの有効秒数。 |
| **Enable Render Caching** | レンダリングキャッシュは、結果の HTML をキャッシュすることで、コンテンツのレンダリングをスピードアップします。 |
| **Render Cache Lifetime (seconds)** | レンダリングキャッシュの有効秒数。 |

レンダリングされる HTML に、動的コンテンツが含まれるために、キャッシュさせたくない場合は、以下のような Twig テンプレート内のタグで、レンダリングキャッシュを無効化できます。

```
{% do block.disableCache() %}
```

