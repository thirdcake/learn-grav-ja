---
title: Grav API 導入
lastmod: '2026-04-04T09:49:34+09:00'
description: 'Grav API をインストールし、その利用方法を解説します。'
weight: 10
params:
    srcPath: '/api/getting-started'
---

[Grav API プラグイン](https://github.com/getgrav/grav-plugin-api) は、 Grav サイトに RESTful API を追加し、コンテンツページ、メディア、 config 設定、ユーザー、システム管理へのヘッドレスなアクセスを提供します。


## システム要件{#requirements}

- Grav CMS 1.8+
- PHP 8.3+
- Login Plugin 3.8+

## インストール{#installation}

GPM 経由でインストール：

```bash
bin/grav install api
```

もしくは、手動で：  
プラグインを `user/plugins/api` にダウンロードし、 `composer install` を実行します。

## クイックスタート{#quick-setup}

### 1. プラグインを有効化{#1-enable-the-plugin}

```yaml
# user/config/plugins/api.yaml
enabled: true
```

### 2. API キーを生成{#2-generate-an-api-key}

**CLI 経由** （初期セットアップ時を推奨）：

```bash
bin/plugin api keys:generate --user=admin --name="My First Key"
```

**管理パネル経由** ： ユーザープロフィールページに移動し、 **API Keys** セクションを利用します。

キーは、一度しか表示されません。 - その場で保存してください。

### 3. 最初のリクエスト{#3-make-your-first-request}

```bash
curl https://yoursite.com/api/v1/pages \
  -H "X-API-Key: grav_abc123..."
```

## config 設定{#configuration}

API は、 `user/config/plugins/api.yaml` で設定します：

```yaml
enabled: true
route: /api                    # Base route for all API endpoints
version_prefix: v1             # Version prefix

auth:
  api_keys_enabled: true       # Enable API key authentication
  jwt_enabled: true            # Enable JWT token authentication
  session_enabled: true        # Enable session passthrough

cors:
  enabled: true                # Enable CORS headers
  origin: '*'                  # Allowed origins
  credentials: false           # Allow credentials

rate_limit:
  enabled: true                # Enable rate limiting
  requests_per_minute: 120     # Requests per minute per user/IP

pagination:
  default_per_page: 20         # Default items per page
  max_per_page: 100            # Maximum items per page
```

## 環境{#environments}

Grav では、複数環境に対応しています。  
`X-Grav-Environment` ヘッダーを通じて API はこれを反映します：

```bash
curl -H "X-Grav-Environment: mysite.com" \
     -H "X-API-Key: ..." \
     https://yoursite.com/api/v1/pages
```

## レスポンス形式{#response-format}

すべての API レスポンスは、一貫した構造に従います：

```json
{
  "data": { ... }
}
```

ページ分割されたレスポンスは、メタデータを含みます：

```json
{
  "data": [ ... ],
  "meta": {
    "total": 42,
    "page": 1,
    "per_page": 20,
    "total_pages": 3
  },
  "links": {
    "self": "/api/v1/pages?page=1",
    "next": "/api/v1/pages?page=2",
    "last": "/api/v1/pages?page=3"
  }
}
```

エラーは、 RFC 7807 フォーマットを使います：

```json
{
  "status": 404,
  "title": "Not Found",
  "detail": "Page '/missing' not found."
}
```

## 並行処理{#concurrency-control}

API は、並行処理の最適化に ETag を使います。  
リソースの更新時、 `If-Match` ヘッダーを GET レスポンスの ETag に含めます：

```bash
# Fetch with ETag
curl -H "X-API-Key: ..." https://yoursite.com/api/v1/pages/blog
# Response includes: ETag: "abc123"

# Update with If-Match
curl -X PATCH \
  -H "X-API-Key: ..." \
  -H "If-Match: \"abc123\"" \
  -H "Content-Type: application/json" \
  -d '{"title": "Updated Title"}' \
  https://yoursite.com/api/v1/pages/blog
```

最後の通信以降、リソースが修正されていたら、 409 コンフリクトレスポンスを受け取ります。

