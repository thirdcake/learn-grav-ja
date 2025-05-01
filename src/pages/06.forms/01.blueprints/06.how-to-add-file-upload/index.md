---
title: "ハウツー：ファイルを追加アップロード"
layout: ../../../../layouts/Default.astro
---

<h3 id="file-uploads">ファイルのアップロード</h3>

ページや、config 、プラグイン、テーマのブループリントで、ファイルのアップロード機能を追加できます。ファイルのアップロードは、常に Ajax ベースで行われ、デスクトップからのドラッグ & ドロップできたり、通常の file フィールドとして選択できます。ファイルがフィールドに追加されるたびに、一時フォルダに自動でアップロードされ、 save アクション（もしくは submit アクション）が実行されたときのみ保存されます。

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

file フィールドは、複数のオプションが利用できます。受け入れ可能な MIME タイプや、拡張子、許容されるファイルサイズまで、さまざまなオプションがあります：

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

通常の HTML5 の file フィールドと同じように、 `multiple` オプションを有効化すると、複数のファイルをアップロードできます。この設定は、 [`limit`](#limit) オプションとも関係しており、フィールドに許可されるファイルの数を決定します。

#### `destination`

``` yaml
destination: 'self@' # [<path> | <stream> | self@ | page@:<path>]
```

Destination is the location where uploaded files should be stored. This can be either a regular `path` (relative to the root of Grav), a `stream` (such as `theme://images`), `self@` or the special  `page@:` prefix. You can also reference a subfolder relative to the current page with `self@/path`. 

!! `self@` is not allowed outside the Pages or Flex Objects scope, an error will be thrown. If you use a file field outside a Page or Flex Object, you should always change the `destination` setting.

<h5 id="examples">具体例</h5>

1. If it's desired to upload files to a plugin `testing` folder (`user/plugins/testing`), destination would be:

  ```yaml
  destination: 'plugins://testing'
  ```

2. Assuming we have a blog item at the route `/blog/ajax-upload` (physical location being `user/pages/02.blog/ajax-upload`), with the `page@:` prefix the destination would be:

  ```yaml
  destination: 'page@:/blog/ajax-upload'
  ```

3. Assuming the current theme is `antimatter` and we want to upload to the assets folder (physical location being `user/themes/antimatter/assets`), with the `theme` stream the destination would be:

   ```yaml
   destination: 'theme://assets'
   ```

#### `random_name`

```yaml
random_name: false # [false | true]
```

When the `random_name` is enabled, the uploaded file will get renamed with a random string **15** characters long. This is helpful if you wish to hash your uploaded files or if you are looking for a way to reduce names collision.

<h5 id="example">具体例</h5>

```php
'my_file.jpg' => 'y5bqsGmE1plNTF2.jpg'
```

#### `avoid_overwriting`

```yaml
avoid_overwriting: false # [false | true]
```

When the `avoid_overwriting` is enabled and a file with the same name of the uploaded one already exists in `destination`, it will be renamed. The newly uploaded file will be prefixed with the current date and time, concatenated by a dash.

<h5 id="example-1">具体例</h5>

```php
'my_file.jpg' => '20160901130509-my_file.jpg'
```

#### `limit`

```yaml
limit: 10 # [1...X | 0 (unlimited)]
```

When the [`multiple`](#multiple) setting is enabled, `limit` allows to constrain the number of allowed files for an individual field. If `multiple` is not enabled (not enabled by default), `limit` automatically falls back to **1**.

When `limit` is set to **0**, it means that there are no restrictions on the amount of allowed files that can be uploaded.

!! It is good practice to always ensure you have a set limit of allowed files that can be uploaded. This way you have more control over your server resources utilizations.

#### `accept`

[prism classes="language-yaml line-numbers"]
accept:
  - 'image/*' # Array of MIME types and/or extensions. ['*'] for allowing any file.
[/prism]

The `accept` setting allows an array of MIME type as well as extensions definitions. All of the extensions need to be starting with the `.` (dot) plus the extension itself.

In addition you can also allow any file by simply using the __*__ (star) notation `accept: ['*']`.

<h5 id="examples-1">具体例</h5>

1. To only allow `yaml` and `json` files:
   ```yaml
     accept:
       - .yaml
       - .json
   ```
2. To only allow images and videos:
   ```yaml
     accept:
       - 'image/*'
       - 'video/*'
   ```
3. To allow any image, any video and only mp3 files:
   ```yaml
     accept:
       - 'image/*'
       - 'video/*'
       - .mp3
   ```
4. To allow any file:
   ```yaml
     accept:
       - '*'
   ```

#### `filesize`

The max file size is limited by:

1. field level  `filesize:`, then ...

2. Form plugin level configuration `user/plugins/form.yaml` setting `files: filesize:`, then if neither of those are limiting...

3. PHP level configuration for `upload_max_filesize` for individual files that are uploaded, and `post_max_size` for the max form post total size.

<h5 id="examples-2">具体例</h5>

1. To limit a specific field to `5M`
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

2. To limit all file fields to `5M`, edit your `user/config/form.yaml` file:
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
   
   ### Legacy File Upload Processing and Manual Control

   For basic file handling, all you need is the field defintion. The files get uploaded to a temporary location via the Dropzone widget via an XHR call to the server.  On form submission, the files are moved from their temporary location to their final location automatically.  You can however use the `upload: true` action in the `process:` block to manually trigger where in the workflow you want those files to be moved.

   ##### Example:

```yaml
process:
    upload: true
    message: 'Thank you for your files'
    reset: true 
```
