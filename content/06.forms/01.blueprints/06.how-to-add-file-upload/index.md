---
title: ハウツー：ファイルを追加アップロード
layout: ../../../../layouts/Default.astro
lastmod: '2025-08-29'
description: '管理パネルに、ファイルのアップロードフォームを作成したい場合の設定方法やアップロード場所の指定方法を解説します。'
---

<h3 id="file-uploads">ファイルのアップロード</h3>

ページや、config 、プラグイン、テーマのブループリントで、ファイルのアップロード機能を追加できます。  
ファイルのアップロードは、常に Ajax ベースで行われ、デスクトップからのドラッグ & ドロップできたり、通常の file フィールドとして選択できます。  
ファイルがフィールドに追加されるたびに、一時フォルダに自動でアップロードされ、 save アクション（もしくは submit アクション）が実行されたときのみ保存されます。

使用例：

```yaml
custom_file:
  name: myfile
  type: file
  label: A Label
  destination: 'plugins://my-plugin/assets'
  multiple: true
  autofocus: false
  accept:
    - image/*
```

> [!Note]  
> ファイルのアップロードを追加するために、ベースの Twig テンプレートに 下部の JavaScript をレンダリングするコマンドが必要です。 `{{ assets.js('bottom') }}`

<h2 id="options">オプション</h2>

file フィールドは、複数のオプションが利用できます。  
受け入れ可能な MIME タイプや、拡張子、許容されるファイルサイズまで、さまざまなオプションがあります：

<h4 id="defaults">デフォルト</h4>

```yaml
custom_file:
  type: file
  label: A Label
  multiple: false
  destination: 'self@'
  random_name: false
  avoid_overwriting: false
  limit: 10
  accept:
    - image/*
```

#### `multiple`

```yaml
multiple: false # [false | true]
```

通常の HTML5 の file フィールドと同じように、 `multiple` オプションを有効化すると、複数のファイルをアップロードできます。  
この設定は、 [`limit`](#limit) オプションとも関係しており、フィールドに許可されるファイルの数を決定します。

#### `destination`

``` yaml
destination: 'self@' # [<path> | <stream> | self@ | page@:<path>]
```

Destination とは、アップロードしたファイルの保存先となる場所です。  
これは、次のいずれかです: 通常の `path`  (Grav のルートからの相対パス) か、 `stream` （たとえば `theme://images` ）か、 `self@` か、もしくは特別な `page@` 接頭辞のあるパスか。  
現在のページからの相対的なサブフォルダを `self@/path` により参照することも可能です。

> [!Info]  
> `self@` は、ページ外や、Flex Objects では許可されず、エラーを投げます。もし ページ外や Flex Object で file フィールドを使うなら、常に `destination` を設定してください。

<h5 id="examples">具体例</h5>

1. もし、プラグインの `testing` フォルダ（ `user/plugins/testing` ）にファイルをアップロードしたい場合、destination は、以下のようになります：
  ```yaml
  destination: 'plugins://testing'
  ```
  
1. ルーティングで `/blog/ajax-upload` となるページ （実際のパスは `user/pages/02.blog/ajax-upload` ）に、ブログの投稿ページがあったとして、そのページが `page@:` 接頭辞を使う時、 destination は、次のようになります：
  ```yaml
  destination: 'page@:/blog/ajax-upload'
  ```
  
1. 現在のテーマが `antimatter` で、`assets` フォルダ（実際のパスは `user/themes/antimatter/assets/` ）に `theme` ストリームでアップロードしたい場合、 destination は、次のようになります：
   ```yaml
   destination: 'theme://assets'
   ```

#### `random_name`

```yaml
random_name: false # [false | true]
```

`random_name` を有効化すると、アップロードしたファイル名が、 **15** 文字のランダムな文字列になります。  
これは、アップロードしたファイル名をハッシュ化したい場合や、名前の衝突を減らす方法を探しているときに便利です。

<h5 id="example">具体例</h5>

```php
'my_file.jpg' => 'y5bqsGmE1plNTF2.jpg'
```

#### `avoid_overwriting`

```yaml
avoid_overwriting: false # [false | true]
```

`avoid_overwriting` を有効化すると、 `destination` にすでに存在しているファイルと同じファイル名をアップロードしたとき、ファイル名を変更されます。  
新たにアップロードされたファイル名は、現在日時が接頭辞として追加され、ダッシュを介して合体します。

<h5 id="example-1">具体例</h5>

```php
'my_file.jpg' => '20160901130509-my_file.jpg'
```

#### `limit`

```yaml
limit: 10 # [1...X | 0 (unlimited)]
```

[`multiple`](#multiple) 設定が有効化されたとき、 `limit` により、個々のフィールドで許可されるファイル数が制限されます。  
`multiple` が無効化（デフォルトでは無効）されている場合は、 `limit` は自動的に **1** になります。

`limit` に **0** を設定した場合、アップロードできるファイル数に制限は無いということになります。

> [!Info]  
> アップロードできるファイルに、常に limit を設定することは良い方法です。この方法により、サーバーのリソース使用を、より制御できます。

#### `accept`

```yaml
accept:
  - 'image/*' # Array of MIME types and/or extensions. ['*'] for allowing any file.
```

`accept` は、MIME タイプや、拡張子の配列で設定します。  
拡張子はすべて、 `.` （ドット）で始めなければいけません。

加えて、あらゆるファイルを許可するときは、シンプルに  __*__ （スター）記法を使ってください。  
`accept: ['*']` 。

<h5 id="examples-1">具体例</h5>

1. `yaml` ファイルと `json` ファイルのみ許可する場合：
   ```yaml
     accept:
       - .yaml
       - .json
   ```
1. images と videos のみ許可する場合：
   ```yaml
     accept:
       - 'image/*'
       - 'video/*'
   ```
1. あらゆる image と、あらゆる video と、mp3 ファイルだけを許可する場合：
   ```yaml
     accept:
       - 'image/*'
       - 'video/*'
       - .mp3
   ```
1. あらゆるファイルを許可する場合：
   ```yaml
     accept:
       - '*'
   ```

#### `filesize`

ファイルサイズの最大値は、次のように制限されます：

1. フィールドレベルの `filesize:` 。それが無ければ ...
2. Form プラグインレベルの設定値。  `user/plugins/form.yaml` ファイルに設定した `files: filesize:` の値。そこでも制限されていなければ ...
3. PHP レベルの設定値。 `upload_max_filesize` として、アップロードされた個々のファイルが設定され、 `post_max_size` として、POST フォームでのトータルのサイズが設定されます。

<h5 id="examples-2">具体例</h5>

1. 特定のフィールドを `5M` に制限するには：
   ```yaml
   custom_file:
     name: myfile
     type: file
     label: A Label
     destination: 'plugins://my-plugin/assets'
     filesize: 5
     accept:
       - image/*
   ```

1. すべての file フィールドを `5M` に制限するには、 `user/config/form.yaml` ファイルを編集してください：
   ```yaml
   files:
     multiple: false
     limit: 10
     destination: 'self@'
     avoid_overwriting: false
     random_name: false
     filesize: 5
     accept:
       - 'image/*
   ```

<h3 id="legacy-file-upload-processing-and-manual-control">従来のファイルアップロード処理と手動制御</h3>

基本的なファイル制御のためにやることは、フィールドを定義するだけです。  
ファイルは、サーバーの一時的な場所に保存されます。  
その際、 Dropzone ウィジェットを通し、 XHR の呼び出しによります。  
form を送信すると、そのファイルは一時的な場所から最終的な保存先へ、自動的に移動します。  
しかし、 `process:` ブロックで `upload: true` アクションを使うことで、ワークフローの中のどこでファイルを移動させるのか、手動でトリガーすることもできます。

<h5 id="example-2">具体例</h5>

```yaml
process:
    upload: true
    message: 'Thank you for your files'
    reset: true 
```

