---
title: API イベント
lastmod: '2026-04-12T19:13:17+09:00'
description: 'API操作の前後に発火する各種イベントの仕様を解説します。ページ、メディア、ユーザー、構成の変更時に発生するAPI固有のイベントや、Adminプラグインとの互換イベント、発火順序、自作プラグインでの実装例について紹介します。'
weight: 40
params:
    srcPath: '/api/events'
---

API は、すべての書き込み操作の前後にイベントを発火するので、プラグインが反応したり、バリデーション処理をしたり、データを修正したり、処理をキャンセルしたりできます。

## API イベント{#api-events-onapi}

API プラグイン自身のイベントがあり、変更のたびに発火します：

### ページイベント{#page-events}

| イベント | 発火時 | データ |
|-------|------|------|
| `onApiBeforePageCreate` | ページを作成前 | `route`, `header`, `content`, `template`, `lang` |
| `onApiPageCreated` | ページを作成後 | `page`, `route`, `lang` |
| `onApiBeforePageUpdate` | ページを更新前 | `page`, `data` (request body) |
| `onApiPageUpdated` | ページを更新後 | `page` |
| `onApiBeforePageDelete` | ページを削除前 | `page`, `lang` |
| `onApiPageDeleted` | ページを削除後 | `route`, `lang` |
| `onApiPageMoved` | ページを移動後 | `page`, `old_route`, `new_route` |
| `onApiBeforePageTranslate` | 翻訳作成前 | `page`, `lang`, `header`, `content` |
| `onApiPageTranslated` | 翻訳作成後 | `page`, `route`, `lang` |

### メディアイベント{#media-events}

| イベント | 発火時 | データ |
|-------|------|------|
| `onApiBeforeMediaUpload` | 各ファイルのアップロード前 | `page`, `filename`, `type`, `size` |
| `onApiMediaUploaded` | アップロード完了後 | `page`, `filenames` |
| `onApiBeforeMediaDelete` | メディア削除前 | `page`, `filename` |
| `onApiMediaDeleted` | メディア削除後 | `page`, `filename` |

### 構成、ユーザー、GPM イベント{#config-user-and-gpm-events}

| イベント | 発火時 | データ |
|-------|------|------|
| `onApiConfigUpdated` | config の保存後 | `scope`, `data` |
| `onApiUserCreated` | ユーザー作成後 | `user` |
| `onApiUserUpdated` | ユーザー更新後 | `user` |
| `onApiBeforeUserDelete` | ユーザー削除前 | `user` |
| `onApiUserDeleted` | ユーザー削除後 | `username` |
| `onApiBeforePackageInstall` | パッケージインストール前 | `package`, `type` |
| `onApiPackageInstalled` | インストール完了後 | `package`, `type` |

## Admin 互換イベント（`onAdmin*`）{#admin-compatible-events-onadmin}

API は、 Grav Admin プラグインが発火するイベントと同時に発火します。  
これにより、 Admin イベント（SEO Magic, Auto Date, Mega Frontmatter, などの）Admin プラグインのイベントに登録した、サードパーティ製プラグインが、Admin の UI からの変更か API からの変更かに関わらず、正しく動くことを保証します。

両方のイベントは、 -- Admin イベントがまず発火し、それから API イベントが発火するという形で、すべての操作で発火します。

| イベント | 発火時 | データ |
|-------|------|------|
| `onAdminCreatePageFrontmatter` | ページ作成（保存前） | `header`, `data` |
| `onAdminSave` | 保存前（ページ、ユーザー、config） | `object` (by reference), `page` |
| `onAdminAfterSave` | 保存後 | `object`, `page` |
| `onAdminAfterDelete` | ページ削除後 | `object`, `page` |
| `onAdminAfterSaveAs` | ページ移動後・名前変更後 | `path` |
| `onAdminAfterAddMedia` | メディアアップロード後 | `object`, `page` |
| `onAdminAfterDelMedia` | メディア削除後 | `object`, `page`, `media`, `filename` |

### イベントの順序例{#event-ordering-example}

ページの作成処理において、イベントは次の順序で発火します：

1. `onApiBeforePageCreate` — API のイベント前
2. `onAdminCreatePageFrontmatter` — Admin フロントマターインジェクション
3. `onAdminSave` — Admin 保存前 (プラグインがページを修正できます)
4. `onAdminAfterSave` — Admin 保存後 (インデックス操作、通知)
5. `onApiPageCreated` — API のイベント後 (webhook のトリガー)

### 自作プラグインでのイベントの使い方{#using-events-in-your-plugin}

```php
public static function getSubscribedEvents(): array
{
    return [
        'onApiPageCreated' => ['onPageChanged', 0],
        'onApiPageUpdated' => ['onPageChanged', 0],
        'onApiPageDeleted' => ['onPageChanged', 0],
    ];
}

public function onPageChanged(Event $event): void
{
    // Rebuild search index, clear CDN cache, etc.
    $page = $event['page'] ?? null;
    $route = $event['route'] ?? $page?->route();

    $this->rebuildIndex($route);
}
```

## ルーティング登録{#route-registration}

プラグインから、カスタムのエンドポイントを持つ API を拡張できます：

| イベント | 発火時 | データ |
|-------|------|------|
| `onApiRegisterRoutes` | ルーティング初期化中 | `routes` (ApiRouteCollector) |

詳細は、 [プラグイン API 統合](../../04.plugins/07.plugin-api-integration/) ガイドを参照してください。

