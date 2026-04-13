---
title: 'API 開発者ガイド'
lastmod: '2026-04-13T18:18:03+09:00'
description: 'プラグイン開発者が Grav API を利用するときのガイドを解説します。'
weight: 50
params:
    srcPath: '/api/developer-guide'
---

このガイドでは、プラグイン開発者が、 Grav API 及び次世代 Admin インターフェース（admin-next）と統合する方法を解説します。

## API 拡張{#extending-the-api}

あらゆるプラグインは、カスタム API エンドポイントを追加できます。  
[プラグイン API 統合](../../04.plugins/07.plugin-api-integration/) で、すべてのステップを解説しています。

概要としては：

1. あなたのプラグインで `onApiRegisterRoutes` を購読する
2. コントローラークラスへのルーティングを登録する
3. `AbstractApiController` を extends したコントローラーを作成する

## Web Components を利用したカスタム Admin フィールド{#custom-admin-fields-via-web-components}

次世代 Admin は、クラシック Admin と同様、プラグインの構成フォームを、 blueprint スキーマを利用して表示します。  
標準的なフィールドタイプ（テキスト、トグル、セレクト、配列、リスト、その他）は、自動で機能します。

**カスタムフィールドタイプ** （標準的なタイプでは制御できない特別な UI を持つフィールド）のため、プラグインには、次世代 Admin で読み込める **Web コンポーネント** を積み込めます。

### 使い方{#how-it-works}

1. 次世代 Admin が、 blueprint 内の未知のフィールドタイプを発見する
1. そのプラグインが、 API レスポンスでカスタムフィールドを宣言しているか確認する
1. 見つかったら、 API から JavaScript ファイルを取得する
1. その JavaScript は、カスタム要素（Custom Element）を定義している。
1. 次世代 Admin は、その要素をマウントし、プロパティとイベントを使って通信する

### ファイル規約{#file-convention}

Web コンポーネント JavaScript ファイルは、次の場所に置いてください：

```txt
your-plugin/
  admin-next/
    fields/
      yourfieldtype.js      # カスタムフィールドタイプ1つにつき、 JS 1ファイル
```

次世代 Admin が、プラグインの詳細ページを読み込むとき、 API は自動的に `admin-next/fields/` 内のファイルを探し、レスポンスの中にそれらを含めます：

```json
{
  "slug": "your-plugin",
  "custom_fields": {
    "yourfieldtype": "yourfieldtype"
  }
}
```

### Web コンポーネント契約{#web-component-contract}

各 JavaScript ファイルは、 `window.__GRAV_FIELD_TAG` によるタグ名を使用したカスタム要素を定義しなければいけません：

```javascript
const TAG = window.__GRAV_FIELD_TAG;

class YourFieldType extends HTMLElement {
  // Properties set by admin-next
  set field(f) { this._field = f; this._render(); }
  set value(v) { this._value = v; this._render(); }
  get value() { return this._value; }

  connectedCallback() {
    this.attachShadow({ mode: 'open' });
    this._render();
  }

  _render() {
    // Build your UI in this.shadowRoot
  }

  _emitChange(newValue) {
    this.dispatchEvent(new CustomEvent('change', {
      detail: newValue,
      bubbles: true
    }));
  }
}

customElements.define(TAG, YourFieldType);
```

**プロパティ** （次世代 Admin が設定する）：
- `field` -- ブループリントのフィールド定義オブジェクト（label, help, options, validate, その他）
- `value` -- 現在のフィールド値

**Events** （あなたの作成したコンポーネントから発火する）：
- `change` -- `detail` に新しい値を持つ `CustomEvent`

### API へアクセス{#accessing-the-api}

あなたの web コンポーネントは、 API エンドポイントを呼び出せます。  
認証の詳細は、グローバル変数を使って利用できます。

```javascript
// API connection details (set by admin-next before loading your script)
const serverUrl = window.__GRAV_API_SERVER_URL;  // e.g. "https://mysite.com"
const apiPrefix = window.__GRAV_API_PREFIX;       // e.g. "/api/v1"
const apiToken  = window.__GRAV_API_TOKEN;        // Bearer token (pre-set by admin-next)

// Read auth token from admin-next's localStorage (alternative to __GRAV_API_TOKEN)
function getAuth() {
  try {
    const auth = JSON.parse(localStorage.getItem('grav_admin_auth') || '{}');
    return {
      token: auth.accessToken || '',
      env: auth.environment || ''
    };
  } catch {
    return { token: '', env: '' };
  }
}

// Make authenticated API calls
async function apiGet(path) {
  const { token, env } = getAuth();
  const headers = {};
  if (token) headers['Authorization'] = `Bearer ${token}`;
  if (env) headers['X-Grav-Environment'] = env;

  const resp = await fetch(`${serverUrl}${apiPrefix}${path}`, { headers });
  const json = await resp.json();
  return json.data || json;
}
```

### モーダルとオーバーレイ{#modals-and-overlays}

あなたの作成するフィールドに、モーダルが必要な場合（たとえば、ピックアップダイアログのような場合）、シャドウ DOM 内にレンダリングするのではなく、 `document.body` に追加してください。  
これにより、フォームレイアウトからのオーバーフローを防げます。

```javascript
_openModal() {
  const modal = document.createElement('div');
  modal.id = '__my-plugin-modal';
  modal.innerHTML = `<style>...</style><div class="modal">...</div>`;
  document.body.appendChild(modal);
}

_closeModal() {
  document.getElementById('__my-plugin-modal')?.remove();
}
```

> [!WARNING]  
> `document.body` 内でのレンダリングは、ホストページのスタイル（Tailwind CSS を含む）の影響を受けます。衝突を防ぐため、独自のクラス接頭辞を使い、プロパティ値を明示してください。特に、 Tailwind v4 では `* {min-height: 0}` を設定しており、これにより要素を壊す可能性があります -- あなたのコンテナに `min-height: auto` を追加してください。

### 新旧 Admin 間でのコードの共有{#sharing-code-between-old-and-new-admin}

プラグイン制作者は、Twig/jQuery の旧 Admin と、web コンポーネンツの次世代 Admin 間で、ビジネスロジックを共有できます：

```
your-plugin/
  admin/
    lib/
      data-utils.js         # Shared: API calls, data parsing
      validation.js          # Shared: input validation
    js/
      my-field.js            # Classic admin: jQuery-based UI
  admin-next/
    fields/
      myfieldtype.js         # Admin-next: Web Component UI
                             # Can import from ../../admin/lib/
```

`admin/lib` ディレクトリに、フレームワークに依存しないロジックを置きます。  
jQuery ベースのフィールドも、 web コンポーネンツも、ここからインポートします。

## 実例：コード構文ハイライト{#real-world-example-code-syntax-highlighter}

[Codesh プラグイン](https://github.com/trilbymedia/grav-plugin-codesh) では、リファレンス実装として2つのカスタムフィールドタイプを提供します：

### カスタム API エンドポイント{#custom-api-endpoints}

Codesh は、自身のエンドポイントをテーマと文法管理用に登録します：

```php
// In codesh.php
public function onApiRegisterRoutes(Event $event): void
{
    $routes = $event['routes'];
    $routes->get('/codesh/themes', [ApiController::class, 'themes']);
    $routes->post('/codesh/themes/import', [ApiController::class, 'importTheme']);
    $routes->delete('/codesh/themes/{name}', [ApiController::class, 'deleteTheme']);
    $routes->get('/codesh/grammars', [ApiController::class, 'grammars']);
    $routes->post('/codesh/grammars/import', [ApiController::class, 'importGrammar']);
    $routes->delete('/codesh/grammars/{slug}', [ApiController::class, 'deleteGrammar']);
}
```

### カスタムフィールド：テーマピッカー（`codeshtheme`）{#custom-field-theme-picker-codeshtheme}

code プレビューカードによるヴィジュアルテーマセレクター：

- **ファイル**: `admin-next/fields/codeshtheme.js`
- **Blueprint での使い方**: `type: codeshtheme` with `variant: dark` or `variant: light`
- **機能**: 62以上のテーマを持つモーダルグリッド、構文をハイライトしたコードプレビュー、検索、dark/light/custom フィルタ、テーマカスタマイズのためのimport/delete
- **API 呼び出し**: `GET /codesh/themes`, `POST /codesh/themes/import`, `DELETE /codesh/themes/{name}`

```yaml
# In blueprints.yaml
theme_dark:
  type: codeshtheme
  label: Dark Theme
  help: Syntax highlighting theme for dark mode
  variant: dark
  default: helios-dark
```

### カスタムフィールド：文法リスト（`codeshgrammarlist`）{#custom-field-grammar-list-codeshgrammarlist}

TextMate 文法が利用できる複数カラム表示：

- **ファイル**: `admin-next/fields/codeshgrammarlist.js`
- **Blueprint での使い方**: `type: codeshgrammarlist`
- **機能**: 4カラムのレスポンシブレイアウト、カスタム文法のためのインポートボタン、カスタム入力の削除、エイリアスの表示
- **API 呼び出し**: `GET /codesh/grammars`, `POST /codesh/grammars/import`, `DELETE /codesh/grammars/{slug}`

### Codesh から学ぶ重要パターン{#key-patterns-from-codesh}

1. **独立した API Controller** — `classes/ApiController.php` が、すべての REST エンドポイントを制御する
2. **既存のマネージャーを再利用** — `ThemeManager` 及び `GrammarManager` は、旧 Admin 及び API Controller の両方で使われる
3. **ファイルアップロード制御** — PSR-7 の `getUploadedFiles()` が何も返さない場合、 `$_FILES` にフォールバックする
4. **document.body 内のモーダル** — テーマピッカーは、シャドウ DOM 制約を避けるため、そのモーダルを `document.body` に追加する
5. **シングルパストークン化ハイライト** — 構文ハイライトに、自己一致を避けるため、 `|` グループを持つ単一正規表現を使う

## カスタム Admin ページ

カスタムフィールドタイプを超えて、プラグインは、次世代 Admin のサイドバーに、独自の **フルページ** を登録できます。  
このことにより、プラグインは、次世代 Admin それ自体の修正をすることなく、専用の管理インターフェースを提供できます （License Manager のライセンス編集ページのように）

2つのレンダリングモードがあります：

- **Blueprint モード** — プラグインは Grav ブループリントを提供し、次世代 Admin が自動でフォームをレンダリングする。データ-ドリブンページ（設定、キー・バリューエディタ、config パネル）にピッタリです。
- **Component モード** — プラグインはフルページの web コンポーネントを提供する。標準的なフォームでは表示できない、完全にカスタマイズされた UI に最適です。

### Sidebar Registration

To add an entry to the admin-next sidebar, subscribe to the `onApiSidebarItems` event and append your item:

```php
public static function getSubscribedEvents()
{
    return [
        'onApiSidebarItems' => ['onApiSidebarItems', 0],
    ];
}

public function onApiSidebarItems(Event $event): void
{
    $items = $event['items'] ?? [];
    $items[] = [
        'id'       => 'license-manager',
        'plugin'   => 'license-manager',
        'label'    => 'Licenses',
        'icon'     => 'fa-key',
        'route'    => '/plugin/license-manager',
        'priority' => 10,
    ];
    $event['items'] = $items;
}
```

**Sidebar item properties:**

| Property   | Type    | Required | Description |
|------------|---------|----------|-------------|
| `id`       | string  | yes      | Unique identifier for this sidebar item |
| `plugin`   | string  | yes      | The owning plugin's slug |
| `label`    | string  | yes      | Display name shown in the sidebar |
| `icon`     | string  | yes      | FontAwesome icon class (e.g. `fa-key`) |
| `route`    | string  | yes      | Admin-next route path (e.g. `/plugin/license-manager`) |
| `priority` | integer | no       | Sort order; higher values appear earlier (default: 0) |
| `badge`    | string  | no       | Optional badge text or count shown next to the label |

Admin-next calls `GET /sidebar/items` on load. The API fires `onApiSidebarItems`, collects all items from plugins, and returns them.

### Page Definition

When a user navigates to a plugin page, admin-next calls `GET /gpm/plugins/{slug}/page` to get the page definition. Subscribe to `onApiPluginPageInfo` to provide it:

```php
public static function getSubscribedEvents()
{
    return [
        'onApiPluginPageInfo' => ['onApiPluginPageInfo', 0],
    ];
}

public function onApiPluginPageInfo(Event $event): void
{
    if ($event['plugin'] !== 'license-manager') {
        return;
    }

    $event['definition'] = [
        'id'            => 'license-manager',
        'plugin'        => 'license-manager',
        'title'         => 'License Manager',
        'icon'          => 'fa-key',
        'page_type'     => 'blueprint',
        'blueprint'     => 'licenses',
        'data_endpoint' => '/licenses/form-data',
        'save_endpoint' => '/licenses',
        'actions'       => [
            [
                'id'       => 'import',
                'label'    => 'Import',
                'icon'     => 'fa-upload',
                'upload'   => true,
                'endpoint' => '/licenses/import',
            ],
            [
                'id'       => 'export',
                'label'    => 'Export',
                'icon'     => 'fa-download',
                'download' => true,
                'endpoint' => '/licenses/export',
            ],
            [
                'id'      => 'save',
                'label'   => 'Save',
                'icon'    => 'fa-check',
                'primary' => true,
            ],
        ],
    ];
}
```

> [!NOTE]
> Always check `$event['plugin']` before setting the definition. Every plugin listening to `onApiPluginPageInfo` receives every request — only respond when the slug matches yours.

#### Blueprint Mode

Set `page_type` to `'blueprint'` and provide:

| Property        | Description |
|-----------------|-------------|
| `blueprint`     | Name of the blueprint file (without `.yaml`) in `admin/blueprints/` |
| `data_endpoint` | API path that returns current data in blueprint-compatible format |
| `save_endpoint` | API path that receives a PATCH with the form data |

Admin-next fetches the blueprint via `GET /blueprints/plugins/{plugin}/pages/{pageId}`, loads the current data from `data_endpoint`, renders the form, and sends saves to `save_endpoint`.

The blueprint file lives in the standard Grav location:

```
your-plugin/
  admin/
    blueprints/
      your-page.yaml       # Standard Grav blueprint YAML
```

#### Component Mode

Set `page_type` to `'component'` and place a JavaScript file at:

```
your-plugin/
  admin-next/
    pages/
      your-plugin.js       # Full-page web component
```

Admin-next fetches the script via `GET /gpm/plugins/{slug}/page-script`, sets the tag name via `window.__GRAV_PAGE_TAG`, and mounts the element in the content area. The same globals (`__GRAV_API_SERVER_URL`, `__GRAV_API_PREFIX`, `__GRAV_API_TOKEN`) are available for API calls.

You can also use **both modes together**: set `page_type` to `'blueprint'` and also ship a `pages/{slug}.js` file. The API response will include `has_custom_component: true`, letting admin-next render the blueprint form alongside custom component sections.

### Action Buttons

The `actions` array defines buttons rendered in the page header toolbar. Each action is an object with these properties:

| Property   | Type    | Description |
|------------|---------|-------------|
| `id`       | string  | Unique action identifier |
| `label`    | string  | Button text |
| `icon`     | string  | FontAwesome icon class |
| `primary`  | boolean | If `true`, this is the main save action (uses form data, calls `save_endpoint`) |
| `upload`   | boolean | If `true`, clicking opens a file picker and POSTs the file to `endpoint` |
| `download` | boolean | If `true`, clicking triggers a file download from `endpoint` |
| `endpoint` | string  | API path for upload/download actions |
| `confirm`  | string  | If set, shows a confirmation dialog with this message before executing |

A page typically has one `primary` save button plus optional import/export or custom actions.

### Real-World Example: License Manager

The [license-manager plugin](https://github.com/getgrav/grav-plugin-license-manager) is a complete reference implementation of a custom admin page using blueprint mode.

#### Event Handlers

The plugin subscribes to three events:

```php
public static function getSubscribedEvents()
{
    return [
        'onPluginsInitialized'  => ['onPluginsInitialized', 0],
        'onApiRegisterRoutes'   => ['onApiRegisterRoutes', 0],
        'onApiSidebarItems'     => ['onApiSidebarItems', 0],
        'onApiPluginPageInfo'   => ['onApiPluginPageInfo', 0],
    ];
}
```

- `onApiRegisterRoutes` — Registers REST endpoints for license CRUD, import, export, and product status
- `onApiSidebarItems` — Adds the "Licenses" entry to the sidebar
- `onApiPluginPageInfo` — Returns the page definition with blueprint reference, data/save endpoints, and import/export actions

#### API Endpoints

The `LicenseApiController` provides these endpoints:

| Method | Path | Description |
|--------|------|-------------|
| GET    | `/licenses/form-data` | Returns license data in blueprint-compatible format (used by `data_endpoint`) |
| PATCH  | `/licenses` | Saves all licenses from the form (used by `save_endpoint`) |
| POST   | `/licenses/import` | Imports a `licenses.yaml` file (upload action) |
| GET    | `/licenses/export` | Downloads `licenses.yaml` (download action) |
| GET    | `/licenses/products-status` | Returns installation status of licensed products |

#### Custom Field: Products Status

The blueprint includes a `products-status` custom field type that displays a read-only list of licensed products with their installation state:

```yaml
# admin/blueprints/licenses.yaml
form:
  validation: loose
  fields:
    licenses:
      type: array
      style: vertical
      placeholder_key: PLUGIN_LICENSE_MANAGER.SLUG
      placeholder_value: PLUGIN_LICENSE_MANAGER.LICENSE
    products_status:
      type: products-status
      style: vertical
```

The web component (`admin-next/fields/products-status.js`) calls `GET /licenses/products-status` and renders each product with its status (enabled, disabled, installed, or not installed) using the `window.__GRAV_API_TOKEN` global for authentication.

#### How It All Fits Together

1. Admin-next loads and calls `GET /sidebar/items` — the license-manager adds its "Licenses" entry
2. User clicks the sidebar item, admin-next navigates to `/plugin/license-manager`
3. Admin-next calls `GET /gpm/plugins/license-manager/page` — the plugin returns its page definition
4. Admin-next sees `page_type: 'blueprint'`, fetches the blueprint from `GET /blueprints/plugins/license-manager/pages/licenses`
5. Admin-next loads current data from `GET /licenses/form-data`
6. The form renders with standard fields (array for licenses) and a custom field (products-status web component)
7. The Save button sends a PATCH to `/licenses`; Import/Export trigger their respective endpoints

## Compatibility Declaration

Declare API compatibility in your plugin's `blueprints.yaml`:

```yaml
compatibility:
  grav:
    - 1.8
  api:
    - 1.0
```

This signals to the ecosystem that your plugin:
- Has been tested with the API plugin
- Ships web components for any custom field types (if applicable)
- Works correctly with admin-next

## Webhooks

The API plugin can dispatch webhooks for all mutation events. Plugins don't need to do anything special — the API's `WebhookDispatcher` listens for `onApi*` events and forwards them to configured webhook URLs.

Webhook events map directly to API events:

| API Event | Webhook Event |
|-----------|---------------|
| `onApiPageCreated` | `page.created` |
| `onApiPageUpdated` | `page.updated` |
| `onApiPageDeleted` | `page.deleted` |
| `onApiMediaUploaded` | `media.uploaded` |
| `onApiUserCreated` | `user.created` |
| `onApiConfigUpdated` | `config.updated` |
| `onApiPackageInstalled` | `gpm.installed` |


