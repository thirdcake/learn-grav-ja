---
title: WordPress から Grav への引っ越し
layout: ../../../layouts/Default.astro
lastmod: '2025-12-04'
---

<h2 id="requirements">要件</h2>

* PHP v7.1 以上 composer の依存関係のため
* その WordPress がホストされている環境に [WP-CLI](https://wp-cli.org/) がインストールされている
* エクスポートされるコンテンツのある WordPress が機能している
* WordPress サイトの `wp-content/uploads` に読み/書きのアクセスができる
* Grav がホストされる環境に [Composer](https://getcomposer.org/) がインストールされている

<h2 id="installation">インストール</h2>

1. 最新リリースの [wp2grav_exporter](https://github.com/jgonyea/wp2grav_exporter/releases) プラグインをダウンロードし、 WordPress の `wp-content/plugins` ディレクトリにアップロードしてください。
2. `wp2grav_exporter` ディレクトリ内で、依存関係のインストールのため、 `composer install --no-dev` を実行してください。
3. 新しいプラグインを有効にしてください：
   - wp-cli を使う： `wp plugin activate wp2grav_exporter` か、 GUI の管理パネルから有効化
4. すべてのアイテムをエクスポートするため、 `wp wp2grav-all` を実行してください。その他のオプションは以下を参照してください。
5. エクスポートされたファイルは、 `WP_ROOT/wp-content/uploads/wp2grav-exports/DATE` に置かれます。
6. Grav v1.6 サイトに対しては、ユーザーを表示し、管理するために [admin-addon-user-manager](https://github.com/david-szabo97/grav-plugin-admin-addon-user-manager) を推奨します。 Grav 1.7 以上のサイトには不要です。

<h2 id="notes">注意点</h2>

> `wp wp2grav-all` を実行すると、以下のエクスポートの各手順を、一度に実行することになります。それから、インストールされている Grav に、データをインポートする方法を解説した、以下の各セクションに従ってください。

<h2 id="exporting-users-from-wordpress">WordPress からユーザーのエクスポート</h2>

![WordPress users exported to Grav](users.webp)

左にある WordPress ユーザー一覧を、右の Grav にエクスポートしました。

<h3 id="command">コマンド</h3>

`wp wp2grav-users` により、 Grav のユーザーアカウントファイルが生成されます。

<h3 id="results">結果</h3>

* ユーザーアカウントは、 `EXPORT/accounts/` 下のエクスポートディレクトリにあります。
  * ユーザーネームは、 3文字以上16文字以下になります。
  * もしユーザーネームが短縮されたり調整された場合、衝突を避けるため、ユーザーネームに、 WordPress の uid も付与されます。
  * 各アカウントのパスワードはランダムに生成され、対応する WordPress アカウントとは関係ありません。アカウントが初回に認証を行うと、平文のパスワードは自動でハッシュ化されたパスワードに変換されます。

<h3 id="importing-users-to-grav">Grav へのユーザーのインポート</h3>

Copy the `EXPORT/accounts` directory to your `user` directory (e.g. username.yaml files should be placed at `user/accounts`).

<h2 id="exporting-user-roles-from-wordpress">WordPressからユーザーロールのエクスポート</h2>

![WordPress roles exported to Grav groups](roles.webp)

WordPress users with roles on left exported to Grav groups on the right.

<h3 id="command-1">コマンド</h3>

`wp wp2grav-roles` will generate a Grav groups.yaml file.

<h3 id="results-1">結果</h3>

WordPress user roles export as Grav groups in a `groups.yaml` file at `config/groups.yaml`. Some notes about the role exporting:

* Each WordPress role is converted to the Grav group `wp_<ROLE_WITH_UNDERSCORES>` (e.g. `subscriber` becomes `wp_subscriber`).
* WordPress users with administrator roles receive the `wp_administrator` group.
* The `wp_administrator` group receives `admin.super` access along with `admin.login` access.  Accounts with these permissions are full admins on the site!
* A new Grav group called `wp_authenticated_user` group receives `admin.login` access.
* All accounts receive the "wp_authenticated_user" group.

<h3 id="importing-user-roles">ユーザーロールのインポート</h3>

Copy the `EXPORT/config` directory to `users/config`.

<h2 id="exporting-post-types-from-wordpress">WordPressから投稿タイプのエクスポート</h2>

![Exported post types](post-types.png)

WordPress post types are converte to Grav page types, with a pre-pended "WP" in front of each type (highlighted in yellow here).

<h3 id="command-2">コマンド</h3>

* `wp wp2grav-post-types` will generate a basic Grav plugin, along with page types that match the WordPress post types.

<h3 id="results-2">結果</h3>

* A Grav plugin will be generated that will present basic field functionality within the Admin tool.

<h3 id="importing-post-types-to-grav">Gravへの投稿タイプのインポート</h3>

* Copy the `EXPORT/plugins` directory to your `user` directory
* Navigate to the Grav plugin directory `user/plugins/wordpress-exporter-helper` and run `composer install`.

<h2 id="exporting-posts-from-wordpress">WordPressから投稿のエクスポート</h2>

![Sample page, admin view](sample-page-admin.webp)

Admin view of WordPress "Sample Page" on left exported to Grav markdown on the right.


![Sample page, page view](sample-page-render.webp)

User view of WordPress "Sample Page" on left exported and rendered via Grav on the right.


<h3 id="command-3">コマンド</h3>

* `wp wp2grav-posts` will export all posts.

<h3 id="results-3">結果</h3>

* Each post/page will be exported to directories matching metadata from the post, typically the post/ page title.
* Library media will be copied to the `data/wp-content` and in-line content will (eventually) be included within the page's directory.

<h3 id="importing-posts-to-grav">Gravへの投稿のインポート</h3>

* Copy the `EXPORT/pages` directory to your `user` directory
* Copy the `EXPORT/data` directory to your `user` directory

<h2 id="exporting-site-metadata-from-wordpress">WordPressからサイトのメタデータのエクスポート</h2>

![Sample page, admin view](site-metadata.webp)

Admin view of WordPress General Settings on left exported to Grav Site Config on the right.

<h3 id="command-4">コマンド</h3>

* `wp wp2grav-site` will export site metadata.

<h3 id="results-4">結果</h3>

* Grav site metadata is stored in `EXPORT/config/site.yaml`.

<h3 id="importing-site-metadata-to-grav">Gravへのサイトのメタデータのインポート</h3>

* Copy the `EXPORT/config/site.yaml` directory to Grav at `user/config/site.yaml`.

