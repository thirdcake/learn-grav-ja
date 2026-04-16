---
title: 'API 開発者ガイド'
lastmod: '2026-04-16T19:25:32+09:00'
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

### サイドバー登録{#sidebar-registration}

次世代 Admin サイドバーにエントリーを追加するには、 `onApiSidebarItems` イベントに登録し、アイテムを追加してください：

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

**サイドバーアイテムのプロパティ**

| プロパティ | 型      | 必須かどうか | 説明 |
|------------|---------|----------|-------------|
| `id`       | string  | yes      | このサイドバーアイテムで単一の識別子 |
| `plugin`   | string  | yes      | 紐づくプラグインの slug |
| `label`    | string  | yes      | サイドバーに表示される名前 |
| `icon`     | string  | yes      | FontAwesome のアイコンの class (例： `fa-key`) |
| `route`    | string  | yes      | 次世代 Admin のルートパス (例： `/plugin/license-manager`) |
| `priority` | integer | no       | 並び替え順序； 大きい値ほど上に表示される (デフォルト： 0) |
| `badge`    | string  | no       | オプションのバッジテキストまたはラベルの隣に表示されるカウント |

次世代 Admin は読み込み時、 `GET /sidebar/items` を呼び出します。  
API は、 `onApiSidebarItems` で発火し、プラグインからすべてのアイテムを集め、これらを返します。

### ページ定義{#page-definition}

ユーザーがプラグインページに移動したとき、次世代 Admin は、ページの定義を取得するため、 `GET /gpm/plugins/{slug}/page` を呼び出します。  
これを提供するには、 `onApiPluginPageInfo` に登録してください：

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
> 定義を設定する前に、常に `$event['plugin']` をチェックしてください。すべての `onApiPluginPageInfo` をリッスンしているプラグインは、すべてのリクエストを受信するため slug があなたのプラグインと適合したときのみ応答するようにしてください。。

#### ブループリントモード{#blueprint-mode}

`page_type` を `'blueprint'` に設定し、提供してください：

| プロパティ      | 説明 |
|-----------------|-------------|
| `blueprint`     | `admin/blueprints/` 内のブループリントファイルの（`.yaml` を除いた）名前 |
| `data_endpoint` | ブループリント互換のフォーマットで現在のデータを返す API パス |
| `save_endpoint` | フォームデータとともに PATCH を受信する API パス |

次世代 Admin は、 `GET /blueprints/plugins/{plugin}/pages/{pageId}` を使ってブループリントを呼び出し、現在のデータを `data_endpoint` から読み込み、フォームをレンダリングし、そして `save_endpoint` へ保存データを送ります。

ブループリントファイルは、標準的な Grav 配置の中にあります：

```txt
your-plugin/
  admin/
    blueprints/
      your-page.yaml       # Standard Grav blueprint YAML
```

#### コンポーネントモード{#component-mode}


`page_type` を `'component'` に設定し、 JavaScript ファイルを次の場所に置いてください：:

```txt
your-plugin/
  admin-next/
    pages/
      your-plugin.js       # Full-page web component
```

次世代 Admin は、 `GET /gpm/plugins/{slug}/page-script` を利用してスクリプトを取得し、 `window.__GRAV_PAGE_TAG` を利用してタグ名を設定し、コンテンツエリア内の要素をマウントします。  
API 呼び出しにｈ，同じグローバル変数（ `__GRAV_API_SERVER_URL`, `__GRAV_API_PREFIX`, `__GRAV_API_TOKEN` ）が利用できます。

**両方のモードが一緒に** 利用可能です： `page_type` を `'blueprint'` に設定し、同時に `pages/{slug}.js` を載せられます。  
API レスポンスは、 `has_custom_component: true` を含み、次世代 Admin は、ブループリントをカスタムコンポーネントセクションからレンダリングできます。

### アクションボタン{#action-buttons}

`actions` 配列は、ページヘッダーツールバーにレンダリングされるボタンに利用できます。  
各アクションは、以下のプロパティを持つオブジェクトです：

| プロパティ | 型      | 説明 |
|------------|---------|-------------|
| `id`       | string  | 独立したアクションの識別子 |
| `label`    | string  | ボタンテキスト |
| `icon`     | string  | FontAwesome アイコンクラス |
| `primary`  | boolean | `true` のとき、これがメインの保存アクションになる（フォームデータを使い、 `save_endpoint` を呼び出す） |
| `upload`   | boolean | `true` のとき、クリックしてファイルピッカーを開き、 `endpoint` にファイルを POST する |
| `download` | boolean | `true` のとき、クリックして `endpoint` からファイルをダウンロードする |
| `endpoint` | string  | アップロード・ダウンロードアクションへの API パス |
| `confirm`  | string  | 設定すると、実行前にこのメッセージの確認が表示される |

一般的なページでは、1つの `primary` 保存ボタンと、オプションでインポート・エクスポートもしくはカスタムアクションを持ちます。

### 実例： ライセンス管理{#real-world-example-license-manager}

[ライセンス管理プラグイン](https://github.com/getgrav/grav-plugin-license-manager) は、ブループリントモードを使ったカスタム Admin ページの完全なリファレンス実装です。

#### イベント制御{#event-handlers}

プラグインは、以下のイベントを登録します：

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

- `onApiRegisterRoutes` — ライセンスの CRUD, インポート、エクスポート及びプロダクトステータスのための REST エンドポイントを登録する
- `onApiSidebarItems` — "Licenses" の入り口をサイドバーに追加
- `onApiPluginPageInfo` — ブループリントリファレンス、 data/save エンドポイント、そして、インポート・エクスポートアクションについてのページ定義を返す

#### API エンドポイント{#api-endpoints}

`LicenseApiController` は、以下のエンドポイントを提供します：

| メソッド | パス | 説明 |
|--------|------|-------------|
| GET    | `/licenses/form-data` | ブループリント互換フォーマット内のライセンスデータを返す（ `data_endpoint` を利用） |
| PATCH  | `/licenses` | フォームからすべてのライセンスを保存（ `save_endpoint` を利用） |
| POST   | `/licenses/import` | `licenses.yaml` ファイルをインポート（アップロードアクション） |
| GET    | `/licenses/export` | `licenses.yaml` ファイルをダウンロード（ダウンロードアクション） |
| GET    | `/licenses/products-status` | ライセンスされたプロダクトのインストールステータスを返す |

#### カスタムフィールド： プロダクトステータス{#custom-field-products-status}

ブループリントには、 `products-status` カスタムフィールドタイプを含み、読み込み専用のライセンスプロダクトをインストール状態付きで、リスト形式で表示します：

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

web コンポーネント（ `admin-next/fields/products-status.js` ） は、 `GET /licenses/products-status` を呼び出し、各プロダクトをそのステータス（有効、無効、インストール済み、もしくは未インストール）とともに、 `window.__GRAV_API_TOKEN` グローバル定数を認証に利用して、表示します。

#### すべてがどのようにつながっているか{#how-it-all-fits-together}

1. 次世代 Admin が、 `GET /sidebar/items` を呼び出し、読み込み -- ライセンスマネージャーは "Licenses" のエントリーを追加
1. ユーザーはサイドバーアイテムをクリックし、次世代 Admin は `/plugin/license-manager` へ移動
1. 次世代 Admin は `GET /gpm/plugins/license-manager/page` を呼び出す -- プラグインはページ定義を返す
1. 次世代 Admin は `page_type: 'blueprint'` を確認し、 `GET /blueprints/plugins/license-manager/pages/licenses` からブループリントを取得
1. 次世代 Admin は、 `GET /licenses/form-data` から現在のデータを読み込む
1. フォームは、標準的なフィールド（ライセンスの配列）と、カスタムフィールド（web コンポーネントによるプロダクトステータス）で表示される
1. 保存ボタンを押すと、 `/licenses` に PATCH 送信する；インポート・エクスポートはそれぞれのエンドポイントをトリガーする 

## 互換性の宣言{#compatibility-declaration}

プラグインの `blueprints.yaml` に API 互換性を宣言してください：

```yaml
compatibility:
  grav:
    - 1.8
  api:
    - 1.0
```

これにより、あなたのプラグインがエコシステムに以下のことを示します：

- API プラグインでテストされている
- カスタムフィールドタイプ用の web コンポーネントを持つ（該当する場合）
- 次世代 Admin で正しく動く

## web フック{#webhooks}

API プラグインは、すべての mutation イベントへ web フック通信できます。  
プラグインは、特別なことをする必要はありません -- API の `WebhookDispatcher` が、 `onApi*` イベントをリッスンし、設定された web フック URL にそれらを向けます。

Web フックイベントは、直接 API イベントにマップされています：

| API イベント | Web フックイベント |
|-----------|---------------|
| `onApiPageCreated` | `page.created` |
| `onApiPageUpdated` | `page.updated` |
| `onApiPageDeleted` | `page.deleted` |
| `onApiMediaUploaded` | `media.uploaded` |
| `onApiUserCreated` | `user.created` |
| `onApiConfigUpdated` | `config.updated` |
| `onApiPackageInstalled` | `gpm.installed` |


