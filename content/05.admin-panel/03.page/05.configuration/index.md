---
title: 'config 設定'
layout: ../../../../layouts/Default.astro
lastmod: '2025-08-20'
description: 'このページは、 acount>configuration と重複しています。'
---

> [!訳注]  
> このページの内容は、 [アカウント &gt; config 設定](../../03.accounts/03.configuration/) と同じで、画像もリンク切れしており、 管理パネルの Pages に configuration タブも無いので、コピペミスか何かだと思います。詳しい方がおられたら、教えてください。

```
![Compatibility Tab](page-configuration.png)
```

| オプション | 説明 |
| :----- | :----- |
| **Admin event compatibility** | プラグインで `onAdminSave` イベントと `onAdminSaveAfter` イベントを有効化します。デフォルトで有効です。 |

```
![Caching Tab](page-configuration.png)
```

より詳しい情報は、 Flex Objects を参照してください。

| オプション | 説明 |
| :----- | :----- |
| **Enable Index Caching** | インデックスキャッシュは、クエリに対する一時的な検索 index を作成することで、検索をスピードアップします |
| **Index Cache Lifetime (seconds)** | インデックスキャッシュの有効秒数 |
| **Enable Object Caching** | オブジェクトキャッシュは、オブジェクトデータや画像の読み込みをスピードアップします |
| **Object Cache Lifetime (seconds)** | オブジェクトキャッシュの有効秒数 |
| **Enable Render Caching** | レンダーキャッシュは、結果となる HTML をキャッシュすることで、コンテンツのレンダリングをスピードアップします。 |
| **Render Cache Lifetime (seconds)** | レンダーキャッシュの有効秒数 |

レンダリングされた HTML に 動的なコンテンツがある場合、 `{% do block.disableCache() %}` により、 Twig テンプレートでキャッシュのレンダリングを無効にできます。

