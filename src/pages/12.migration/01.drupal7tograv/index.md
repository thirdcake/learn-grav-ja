---
title: 'Drupal 7 から Grav への引っ越し'
layout: ../../../layouts/Default.astro
lastmod: '2025-07-24'
description: 'Drupal 7 からデータやユーザーロールなどをエクスポートし、 Grav へインポートする方法を解説します'
---

<h2 id="requirements">要件</h2>

* PHP v7.1 以上 （composer 依存関係のため）
* Drush
* 機能している Drupal 7 サイト（コンテンツが配信されているもの）
* `public://` 及び Drupal サイトのユーザーモジュールフォルダ（通常は `sites/all/modules/contrib` ）への読み・書きアクセス権限

<h2 id="installation">インストール</h2>

<iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/I6UVFUqZMOU" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

1.  [`grav_export`](https://www.drupal.org/project/grav_export/) プラグインをあなたの Drupal の `sites/all/modules/contrib` フォルダにダウンロードし、そこに移動する
2. 依存関係のインストールのため、 `grav_export` フォルダ内で `composer install` を実行
3. `grav_export` モジュールを、 `drush en grav_export -y` もしくは GUI 管理画面から有効化する
4. `drush grav_export_all` を実行するか、もしくはそのエイリアスの `drush gravea` を実行し、すべてのアイテムをエクスポートする。他のオプションは後ほど説明します。
5. エクスポートされたファイルは `[DRUPAL_ROOT]/sites/default/files/grav_export/EXPORT` に配置されている
6. ユーザーの表示や管理には、 Grav プラグインの [admin-addon-user-manager](https://github.com/david-szabo97/grav-plugin-admin-addon-user-manager) を推奨します。
7. Grav へのデータのインポートは、以下の手順を踏んでください

===

<h2 id="exporting-users-from-drupal">Drupal からユーザーをエクスポート</h2>

### Command

`drush grav_export_users` もしくは `drush graveu` により、 Grav のユーザーアカウントファイルを生成します。

### Results

* `EXPORT/accounts/` 下にあるエクスポートフォルダにユーザーアカウントが作成されます
  * ユーザー名は3文字から16文字に整えられます。
  * ユーザー名が省略されたり伸ばされたりした場合は、ユーザー名は衝突を避けるための Drupal の uid も持ちます。
  * 各アカウントのパスワードはランダム生成され、それぞれの Drupal アカウントと関係の無いパスワードになります。アカウントが最初に認証されたとき、パスワードは自動でハッシュ化されます。

### Importing Users to Grav

`EXPORT/accounts` フォルダを `user` ディレクトリにコピーしてください。（例えば、 username.yaml ファイルは  `user/accounts` に置かれるべきです）

<h2 id="exporting-user-roles-from-drupal">Drupal からユーザーロールをエクスポート</h2>

### Command

`drush grav_export_roles` もしくは `drush graver` により、 Grav の groups.yaml ファイルを生成します。

### Results

Drupal のユーザーロールは、 Grav グループの `groups.yaml` ファイルとして、 `config/groups.yaml` にエクスポートされます。ロールのエクスポートには、いくつかの注意点があります：

* 各 Drupal ロールは、 `drupal_<ROLE_WITH_UNDERSCORES>` Grav グループに変換されます（例： `authenticated user` は、次のようになります `drupal_authenticated_user` ）
* `drupal_administrator` グループは、 `admin.super` アクセス及び `admin.login` アクセスの権限を与えられます。
* `drupal_authenticated_user` グループは、 `admin.login` アクセス権限を与えられます。
* すべてのアカウントは、 `"drupal_authenticated_user"` グループを与えられます。
* Drupal の管理者ロールを持つユーザーは、 `drupal_administrator` グループを与えられます。

### Importing User Roles

`EXPORT/config` フォルダを `users/config` にコピーしてください。

<h2 id="exporting-content-types-from-drupal">Drupal からコンテンツタイプをエクスポート</h2>

### Command

`drush grav_export_content_types` もしくは `drush gravect` により、 Grav のブループリントファイル及び html.twig ファイルを生成します。 

### Results

すべての定義されたフィールドタイプに対して、 `drush gravect` コマンドは互換性のあるブループリントファイルと html.twig ファイルを各 Drupal コンテンツタイプについて作成を試みます。ファイルは、それぞれ `EXPORT/themes/drupal_export/blueprints` 及び `EXPORT/themes/drupal_export/templates` にエクスポートされます。

### Known Limitations

1. `number_integer` フィールド
    多くの Grav フィールドでの多重度は、ひとつの値しかサポートしません。Drupal エントリーの最初のひとつだけがエクスポートされます。
2. `addressfield` フィールド
    Grav にはアドレスフィールドの概念がありません。 Drupal フィールドデータは `array` フォームタイプとしてエクスポートされます。

### Importing Content Types to Grav

`EXPORT/themes/drupal_export/blueprints` 及び `EXPORT/themes/drupal_export/templates` フォルダを Grav のアクティブテーマにコピーしてください。管理プラグインは、各コンテンツタイプと関係するフィールドへの追加オプションを提供するようになるでしょう。

注意： フィールドコンテンツが Grav ページヘッダーに追加されている間、これらのフィールドの表示は、 Drupal からエクスポート **されません** 。 html.twig ファイルは、追加フィールドを表示するために、（メインの body コンテンツを除いて）修正される必要があるでしょう。

<h2 id="exporting-nodes-from-drupal">Drupal から node をエクスポート</h2>

### Command

`drush grav_export_nodes` もしくは `drush graven` により、 Grav のユーザーとグループを生成します。

### Results

* 各 node は、 Drupal の url エイリアスとコンテンツタイプをベースに `EXPORT/pages` 下のフォルダ構造にエクスポートされます（例： `pages/content/my_first_page/page.yaml` もしくは `pages/content/cool_article/article.yaml` ）
* Drupal フィールドデータは、ページのヘッダーに保存されます。
* ファイルは、 `EXPORT/data/files/` に保存され、 Drupal 的なストレージモデルに従います。
* drush 出力中、追加のタクソノミータームが出力されるかもしれません。これらを Grav の `user/config/site.yaml` ファイルの taxonomy キー以下にコピーしてください。

### Importing Nodes to Grav Pages

`EXPORT/data` 及び `EXPORT/pages` フォルダを Grav の `user` ディレクトリにコピーしてください。

