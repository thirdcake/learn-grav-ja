---
title: API 統合
lastmod: '2026-04-12T19:48:34+09:00'
description: 'API インテグレーション'
weight: 70
params:
    srcPath: /plugins/plugin-api-integration
---

[Grav API プラグイン](https://github.com/getgrav/grav-plugin-api) は、 Grav CMS に RESTful API を提供します。  
あらゆるプラグインが、 `onApiRegisterRoutes` イベントを使って、自身のエンドポイントを登録することにより、この API を拡張できます。  
このガイドでは、一歩ずつパターンを解説します。

## 概要{#overview}

API プラグインがインストールされているとき、ルーターの初期化中に `onApiRegisterRoutes` イベントを発火します。  
あなたのプラグインは、このイベントを聞き、 `AbstractApiController` クラスを extends したコントローラークラスを指し示した独自のルーティングを登録します。  
これにより、自由に、認証や、パーミッション、リクエストのパース、ページネーション、エラー処理を制御できます。

## 前提条件{#prerequisites}

- Grav API プラグインがインストールされ、有効化されていること
- あなたのプラグインに、 `composer.json` があり、 PSR-4 設定で `classes/` ディレクトリをオートロードすること
- あなたのプラグインに `autoload()` メソッドがあり、 `vendor/autoload.php` を読み込めること

## 段階的なガイド{#step-by-step-guide}

### 1. イベントリスナーの追加{#1-add-the-event-listener}

あなたのプラグインのメインの PHP ファイルで、 `onApiRegisterRoutes` イベントを登録してください：

```php
public static function getSubscribedEvents()
{
    return [
        // ... your existing events ...
        'onApiRegisterRoutes' => ['onApiRegisterRoutes', 0],
    ];
}
```

### 2. ルーティング登録{#2-register-routes}

イベントハンドラ内で、 `ApiRouteCollector` を使って、エンドポイントを登録してください：

```php
public function onApiRegisterRoutes(Event $event): void
{
    $routes = $event['routes'];
    $controller = \Grav\Plugin\MyPlugin\MyApiController::class;

    $routes->get('/my-resource', [$controller, 'index']);
    $routes->get('/my-resource/{id}', [$controller, 'show']);
    $routes->post('/my-resource', [$controller, 'create']);
    $routes->patch('/my-resource/{id}', [$controller, 'update']);
    $routes->delete('/my-resource/{id}', [$controller, 'delete']);
}
```

ルーティングは、接頭辞グループ内で  `get()`, `post()`, `patch()`, `put()`, `delete()`, 及び `group()` をサポートします：

```php
$routes->group('/my-plugin', function ($group) {
    $group->get('/items', [$controller, 'listItems']);
    $group->post('/items', [$controller, 'createItem']);
    $group->get('/stats', [$controller, 'stats']);
});
```

### 3. API コントローラーの作成{#3-create-the-api-controller}

`AbstractApiController` クラスを extends したコントローラークラスを、あなたのプラグインの `classes/` ディレクトリ内に作成してください：

```php
<?php
// classes/MyApiController.php

declare(strict_types=1);

namespace Grav\Plugin\MyPlugin;

use Grav\Plugin\Api\Controllers\AbstractApiController;
use Grav\Plugin\Api\Response\ApiResponse;
use Grav\Plugin\Api\Exceptions\NotFoundException;
use Grav\Plugin\Api\Exceptions\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MyApiController extends AbstractApiController
{
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $this->requirePermission($request, 'api.system.read');

        $pagination = $this->getPagination($request);

        // Your data retrieval logic here
        $items = $this->getItems();
        $total = count($items);
        $paged = array_slice($items, $pagination['offset'], $pagination['limit']);

        return ApiResponse::paginated(
            $paged, $total,
            $pagination['page'], $pagination['per_page'],
            $this->getApiBaseUrl() . '/my-resource'
        );
    }

    public function show(ServerRequestInterface $request): ResponseInterface
    {
        $this->requirePermission($request, 'api.system.read');

        $id = $this->getRouteParam($request, 'id');
        $item = $this->findItemOrFail($id);

        return ApiResponse::create($item);
    }

    public function create(ServerRequestInterface $request): ResponseInterface
    {
        $this->requirePermission($request, 'api.system.write');

        $body = $this->getRequestBody($request);
        $this->requireFields($body, ['name']);

        // Your creation logic here
        $item = $this->createItem($body);

        return ApiResponse::created(
            $item,
            $this->getApiBaseUrl() . '/my-resource/' . $item['id']
        );
    }

    public function delete(ServerRequestInterface $request): ResponseInterface
    {
        $this->requirePermission($request, 'api.system.write');

        $id = $this->getRouteParam($request, 'id');
        $this->deleteItem($id);

        return ApiResponse::noContent();
    }
}
```

### 4. 利用可能なヘルパー{#4-available-helpers}

`AbstractApiController` は、デフォルトで次のようなメソッドを提供します：

| メソッド | 目的 |
|--------|---------|
| `requirePermission($request, $perm)` | パーミッションをチェックし、無ければ 403 を投げる |
| `getUser($request)` | 認証されたユーザーを取得 |
| `getRequestBody($request)` | リクエストを JSON パースする |
| `getRouteParam($request, $name)` |  Get a FastRoute route parameter |
| `getPagination($request)` | Parse `page`/`per_page` query params |
| `getSorting($request, $fields)` | Parse `sort`/`order` query params |
| `getFilters($request, $fields)` | Parse filter query params |
| `requireFields($body, $fields)` | Validate required fields, throw 422 if missing |
| `validateEtag($request, $hash)` | Optimistic concurrency check |
| `respondWithEtag($data)` | Response with ETag header |
| `fireEvent($name, $data)` | Fire a Grav event |
| `getApiBaseUrl()` | Get the API base URL for link generation |

### 5. レスポンスヘルパー{#5-response-helpers}

`ApiResponse` 及び `ErrorResponse` の静的メソッドを利用できます：

```php
use Grav\Plugin\Api\Response\ApiResponse;

// Standard JSON response
ApiResponse::create($data);                    // 200 with {data: ...}
ApiResponse::created($data, $locationUrl);     // 201 with Location header
ApiResponse::noContent();                      // 204, empty body
ApiResponse::paginated($data, $total, $page, $perPage, $baseUrl);  // With pagination meta
```

### 6. 例外処理{#6-exception-handling}

型付きの例外を投げます -- それらは、自動的にキャッチされ、 RFC 7807 エラーレスポンスに変換されます：

```php
use Grav\Plugin\Api\Exceptions\NotFoundException;
use Grav\Plugin\Api\Exceptions\ValidationException;
use Grav\Plugin\Api\Exceptions\ForbiddenException;
use Grav\Plugin\Api\Exceptions\ConflictException;
use Grav\Plugin\Api\Exceptions\ApiException;

throw new NotFoundException("Item not found.");           // 404
throw new ValidationException("Invalid input.", $errors); // 422
throw new ForbiddenException("Not allowed.");             // 403
throw new ConflictException("Resource modified.");        // 409
throw new ApiException(503, 'Unavailable', 'Detail');     // Custom status
```

### 7. オートロードの確約{#7-ensure-autoloading}

あなたのプラグインは、 class を読み込むための `autoload()` メソッドを **持たなければいけません** ：

```php
public function autoload(): \Composer\Autoload\ClassLoader
{
    return require __DIR__ . '/vendor/autoload.php';
}
```

そして、 `composer.json` で PSR-4 を指定してください：

```json
{
    "autoload": {
        "psr-4": {
            "Grav\\Plugin\\MyPlugin\\": "classes/"
        }
    }
}
```

新しい class を追加した後は、 `composer dump-autoload` を実行してください。

## API ドキュメント{#api-documentation}

### Helios 互換ドキュメント{#helios-compatible-docs}

あなたのプラグインに、 Helios フォーマットのエンドポイントページを持つ `api-docs/` ディレクトリを作成してください：

```txt
my-plugin/
├── api-docs/
│   ├── chapter.md              # 概要ページ
│   ├── 01.list-items/
│   │   └── api-endpoint.md     # エンドポイントドキュメント
│   ├── 02.create-item/
│   │   └── api-endpoint.md
│   └── grav-my-plugin-api.postman_collection.json
```

各エンドポイントページは、 `api-endpoint` テンプレートを利用します：

```yaml
---
title: 'List Items'
template: api-endpoint
api:
  method: GET
  path: /my-resource
  description: 'List all items with pagination.'
  parameters:
    - name: page
      type: integer
      required: false
      description: 'Page number (default: 1)'
  request_example: ''
  response_example: '{"data": [...]}'
  response_codes:
    - code: '200'
      description: 'Success'
    - code: '401'
      description: 'Unauthorized'
---

## Usage Notes

Additional documentation in markdown...
```

### Postman Collection

標準的な Grav API 環境変数を利用する Postman v2.1 コレクション JSON ファイルを含めます：

- `{{base_url}}` — サイト URL
- `{{api_prefix}}` — API プレフィックス (デフォルト： `/api/v1`)
- `{{api_key}}` — API キー
- `{{grav_environment}}` — Grav 環境

すべｔねおリクエストには、明示的なヘッダーを含むべきです：

```json
{
    "header": [
        {"key": "X-API-Key", "value": "{{api_key}}"},
        {"key": "X-Grav-Environment", "value": "{{grav_environment}}"},
        {"key": "Content-Type", "value": "application/json"}
    ]
}
```

## イベント{#events}

API エンドポイントは、他のプラグインがフックできる、それら自身のイベントを、発火できます：

```php
// コントローラー内で
$this->fireEvent('onMyPluginItemCreated', ['item' => $item]);
```

## パーミッション{#permissions}

Grav の既存のパーミッションシステムを使ってください。  
一般的なパターンは次の通りです：

- **Read endpoints** → `api.system.read` もしくは、カスタムパーミッション
- **Write endpoints** → `api.system.write` もしくは、カスタムパーミッション
- **Plugin-specific** → 独自のパーミッションを `permissions.yaml` を使って登録してください

## 統合レポート{#reports-integration}

The API provides a plugin-extensible reports system in the **Tools > Reports** tab. Plugins can contribute diagnostic reports by listening for the `onApiGenerateReports` event and optionally providing a web component for custom rendering.

### How It Works

1. The `GET /reports` endpoint collects built-in reports (Security Check, YAML Linter)
2. It fires the `onApiGenerateReports` event so plugins can add their own
3. Each report includes structured data and an optional `component` reference
4. If `component` is set, the admin-next frontend loads the plugin's web component via `GET /gpm/plugins/{slug}/report-script/{reportId}`

### 1. Subscribe to the Event

```php
public static function getSubscribedEvents(): array
{
    return [
        // ... your existing events ...
        'onApiGenerateReports' => ['onApiGenerateReports', 0],
    ];
}
```

### 2. Add Report Data

Read the reports array from the event, append your report, and set it back. **Do not use references** — the `Event` class uses `ArrayAccess` which does not support `&` references.

```php
public function onApiGenerateReports(Event $e): void
{
    // Run your diagnostic checks
    $checker = new ProblemChecker();
    $problems = $checker->getProblems();

    $items = [];
    $hasCritical = false;

    foreach ($problems as $problem) {
        $item = $problem->toArray();
        unset($item['class'], $item['order']);
        $items[] = $item;

        if (!$problem->getStatus() && $problem->getLevel() === 'critical') {
            $hasCritical = true;
        }
    }

    // Read, modify, set — do NOT use &$e['reports']
    $reports = $e['reports'];
    $reports[] = [
        'id'        => 'problems',
        'title'     => 'Grav Potential Problems',
        'provider'  => 'problems',           // your plugin slug
        'component' => 'problems-report',    // web component ID (or null for default rendering)
        'status'    => $hasCritical ? 'error' : 'success',
        'message'   => $hasCritical
            ? 'Critical problems found that need attention.'
            : 'No critical problems detected.',
        'items'     => $items,
    ];
    $e['reports'] = $reports;
}
```

### Report Data Structure

Each report in the array must include:

| Field | Type | Description |
|-------|------|-------------|
| `id` | string | Unique report identifier |
| `title` | string | Display title shown above the report |
| `provider` | string | Your plugin slug — used to resolve the web component script URL |
| `component` | string\|null | Web component ID, or `null` to use the default renderer |
| `status` | string | Overall status: `success`, `warning`, or `error` |
| `message` | string | Summary message shown in the colored status banner |
| `items` | array | Report-specific detail items (structure is up to your plugin) |

When `component` is `null`, the admin-next frontend renders items using a built-in default renderer. When set, the frontend loads your custom web component.

### 3. Create the Web Component (Optional)

If your report needs custom rendering, place a JavaScript file at:

```
your-plugin/admin-next/reports/{component-id}.js
```

For example, the Problems plugin uses `admin-next/reports/problems-report.js`.

The script must define a custom element using the tag name provided via `window.__GRAV_REPORT_TAG`:

```javascript
const TAG = window.__GRAV_REPORT_TAG || 'grav-problems--problems-report';

class ProblemsReportElement extends HTMLElement {
    #report = null;

    set report(val) {
        this.#report = val;
        this.render();
    }

    get report() {
        return this.#report;
    }

    connectedCallback() {
        if (this.#report) this.render();
    }

    render() {
        const report = this.#report;
        if (!report) return;

        const shadow = this.shadowRoot || this.attachShadow({ mode: 'open' });
        shadow.innerHTML = '';

        // Add styles
        const style = document.createElement('style');
        style.textContent = `
            :host { display: block; font-family: inherit; }
            .status-bar {
                padding: 10px 16px;
                font-size: 13px;
                font-weight: 600;
                color: #fff;
            }
            .status-bar.success { background: #22c55e; }
            .status-bar.error { background: #ef4444; }
            .status-bar.warning { background: #8b5cf6; }
            /* ... additional styles ... */
        `;
        shadow.appendChild(style);

        // Render report items
        for (const item of report.items) {
            // Build your custom DOM for each item
            const section = document.createElement('div');
            // ... populate section ...
            shadow.appendChild(section);
        }
    }
}

customElements.define(TAG, ProblemsReportElement);
```

Key points:
- The element receives the full report object via the `report` property setter
- Use Shadow DOM (`attachShadow`) for style isolation
- The tag name is injected as `window.__GRAV_REPORT_TAG` — always use it
- For API calls from within the component, use the exposed globals: `window.__GRAV_API_SERVER_URL`, `window.__GRAV_API_PREFIX`, `window.__GRAV_API_TOKEN`

### File Structure Convention

```
your-plugin/
├── your-plugin.php               # Event handler with onApiGenerateReports
├── admin-next/
│   └── reports/
│       └── {component-id}.js     # Web component script
```

## 実例{#real-world-examples}

- **Email Plugin** — Registers `/email/send` and `/email/test` endpoints
- **License Manager** — Registers full CRUD for `/licenses` with format validation
- **Problems Plugin** — Adds diagnostic reports via `onApiGenerateReports` with a custom web component for rendering PHP version checks and module status

All follow the patterns described above and can be found in their respective plugin repositories.
