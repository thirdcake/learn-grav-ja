---
title: 'config 設定'
layout: ../../../../layouts/Default.astro
lastmod: '2025-05-06'
---
![Compatibility Tab](accounts-configuration1.png)

| オプション | 説明 |
| :----- | :----- |
| **Admin event compatibility** | プラグインで `onAdminSave` イベントと `onAdminSaveAfter` イベントを有効化します。デフォルトで有効です。 |

![Caching Tab](accounts-configuration2.png)

詳しい情報は、 Flex Objects をご覧ください。

| オプション | 説明 |
| :----- | :----- |
| **Enable Index Caching** | インデックスキャッシュは、クエリに対する一時的な検索 index を作成することで、検索をスピードアップします |
| **Index Cache Lifetime (seconds)** | インデックスキャッシュの有効秒数 |
| **Enable Object Caching** | オブジェクトキャッシュは、オブジェクトデータや画像の読み込みをスピードアップします |
| **Object Cache Lifetime (seconds)** | オブジェクトキャッシュの有効秒数 |
| **Enable Render Caching** | レンダーキャッシュは、結果となる HTML をキャッシュすることで、コンテンツのレンダリングをスピードアップします。 |
| **Render Cache Lifetime (seconds)** | レンダーキャッシュの有効秒数 |

レンダリングされた HTML に 動的なコンテンツがある場合、 `{% do block.disableCache() %}` により、 Twig テンプレートでキャッシュのレンダリングを無効にできます。

