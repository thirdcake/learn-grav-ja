---
title: 'Config 設定画面'
layout: ../../../../../layouts/Default.astro
lastmod: '2025-05-10'
---
**Config 設定** には、 **Flex ディレクトリ** の共通事項を設定します。

これらの設定は、通常、 flex ディレクトリのふるまいを変更するのに使われたり、オブジェクトのデフォルトを設定するのに使われたり、レイアウトのレンダリングを変更したりするのに使われます。

> [!Info]  
> 設定項目は、すべてのディレクトリで違います。このドキュメントでは、すべてのディレクトリに共通する設定のみを記載しています。

<h4 id="controls">コントロール</h4>

ページの上部に、管理コントロール部分があります。

- **Back**: **[コンテンツリスト](../01.views-list/)** に戻る
- **Save**: config 設定を保存し、 **[コンテンツリスト](../01.views-list/)** に戻る

<h3 id="caching-tab">キャッシュタブ</h3>

| オプション | 説明 |
| :-----  | :----- |
| **Enable Index Caching** | インデックスキャッシュは、クエリの一時的な検索インデックスを作成することで、検索をスピードアップさせます。 |
| **Index Cache Lifetime (seconds)** | インデックスキャッシュの有効秒数。 |
| **Enable Object Caching** | オブジェクトキャッシュは、オブジェクトデータや画像の読み込みをスピードアップさせます。 |
| **Object Cache Lifetime (seconds)** | オブジェクトキャッシュの有効秒数。 |
| **Enable Render Caching** | レンダリングキャッシュは、結果の HTML をキャッシュすることで、コンテンツのレンダリングをスピードアップします。 |
| **Render Cache Lifetime (seconds)** | レンダリングキャッシュの有効秒数。 |

レンダリングされた HTML に動的コンテンツが含まれる場合、 Twig テンプレートからレンダリングキャッシュを無効化できます。

```
{% do block.disableCache() %}
```
