---
title: '認証'
lastmod: '2026-04-04T10:07:47+09:00'
description: 'Grav API を使った認証方法を解説します'
weight: 20
params:
    srcPath: '/api/authentication'
---

API は、3つの認証方式に対応しています、順番にやってみましょう。  
公開エンドポイント（トークン生成、翻訳）へのリクエストは、認証をスキップします。

## API Keys

サーバー間の統合や、 CLI ツールでの利用に推奨される方式です。

**キーの生成：**

```bash
bin/plugin api keys:generate --user=admin --name="CI Pipeline" --expiry=90
```

**リクエスト時の使い方：**

```bash
# Via header (preferred)
curl -H "X-API-Key: grav_abc123..." https://yoursite.com/api/v1/pages

# Via query parameter (useful for quick testing)
curl "https://yoursite.com/api/v1/pages?api_key=grav_abc123..."
```

API キーは、ユーザーアカウントに bcrypt ハッシュとして保存されます。  
各キーは、オプションの有効期限と、最後に利用したタイムスタンプを持つことができます。

### キーの管理{#managing-keys}

- **キーの一覧**: `bin/plugin api keys:list --user=admin`
- **キーの取り消し**: `bin/plugin api keys:revoke --user=admin --id=key_id`
- また、管理パネル UI の各ユーザーのプロフィールページで、キーを管理することもできます

## JWT トークン{#jwt-tokens}

admin-next のようなブラウザベースのアプリケーションに最適です。

**トークンの取得：**

```bash
curl -X POST https://yoursite.com/api/v1/auth/token \
  -H "Content-Type: application/json" \
  -d '{"username": "admin", "password": "password"}'
```

レスポンス：

```json
{
  "data": {
    "access_token": "eyJ...",
    "refresh_token": "eyJ...",
    "token_type": "Bearer",
    "expires_in": 3600
  }
}
```

**リクエストでの使い方：**

```bash
curl -H "Authorization: Bearer eyJ..." https://yoursite.com/api/v1/pages
```

**有効期限切れのトークンを更新：**

```bash
curl -X POST https://yoursite.com/api/v1/auth/refresh \
  -H "Content-Type: application/json" \
  -d '{"refresh_token": "eyJ..."}'
```

## セッション通過{#session-passthrough}

ユーザーがすでに Grav の有効なセッションを持っている場合（たとえば、管理パネルにログイン中の場合）、 API は自動的にそのセッションを受け付けます。  
これは、シームレスな統合のために、主に管理パネルインターフェースで利用されます。

## パーミッション{#permissions}

API は、自身のパーミッション名前空間を使い、管理パネルのパーミッションとは区別されます：

| パーミッション | 目的 |
|-----------|---------|
| `api.access` | API への基本的なアクセス |
| `api.pages.read` | pages の読み込み |
| `api.pages.write` | pages の作成、更新、削除 |
| `api.media.read` | メディアファイルの読み込み |
| `api.media.write` | メディアファイルの更新、削除 |
| `api.config.read` | config 設定の読み込み |
| `api.config.write` | config 設定の修正 |
| `api.users.read` | ユーザーアカウントの読み込み |
| `api.users.write` | ユーザーの作成、修正、削除 |
| `api.gpm.read` | パッケージの一覧、更新チェック |
| `api.gpm.write` | パッケージのインストール、削除、更新 |
| `api.system.read` | System info, scheduler status |
| `api.system.write` | Cache management, scheduler |
| `api.webhooks.read` | webhook の一覧 |
| `api.webhooks.write` | webhooks の管理 |

ユーザーアカウントの `access` 設定により、ユーザーのパーミッションを許可します：

```yaml
access:
  api:
    access: true
    pages:
      read: true
      write: true
    media:
      read: true
      write: true
```

