---
title: 'DDEV によるローカル開発'
layout: ../../../layouts/Default.astro
lastmod: '2025-10-30'
---

[DDEV](https://ddev.readthedocs.io) とは、 Docker 上に構築されたオープンソースの PHP 開発ツールです。簡単にローカルのホスティング環境を作成でき、サーバー設定のバージョンコントロールができます。本来は Drupal DEVelopment を意味した DDEV ですが、Drupal だけでなく、 WordPress や GravCMS サイトもホスティングできます。Docker ベースなので、 DDEV は Windows にも、 Mac にも、 Linux にも互換性があります。

<h2 id="installing-ddev">DDEV のインストール</h2>

最新の DDEV をインストールする解説は、 [DDEV 公式ドキュメント](https://ddev.readthedocs.io/en/stable/) をご覧ください。

<h2 id="configuration">設定</h2>

- Grav ファイルをホストマシンのフォルダに置いてください（/home/USER/projects/grav）。
- ターミナルで、そのフォルダに移動してください `cd /home/USER/projects/grav`
- `ddev config` と入力してください。以下のプロンプトが表示されます：
  - プロジェクト名（デフォルトでは Grav ルートフォルダ名）
  - ドキュメントルートのパス（デフォルトでは Grav のルートフォルダ）
  - プロジェクトタイプ（このオプションでは `php` タイプを使います）
- `ddev start` を Grav のルートフォルダで実行します。
- DDEV は必要なコンテナを構築します。 ローカルホストを変更するために、ルートディレクトリに Sudo 認証が必要になるかもしれません。

<h3 id="symlinking">シムリンク</h3>

Grav では、config 設定、テーマやプラグインのようなフォルダをシムリンクさせるのは便利です。  
残念ながら、シムリンクは DDEV で作成されたコンテナ内ではリンクされません。  
これをするために、 MOUNTS を追加することができます:
  * .ddev フォルダ内で、 docker-compose.mounts.yaml を追加
  * mount を追加（テーマの例）:
    ```yaml
        services:
          web:
            volumes:
              - /absolute/path/to/my/themes_repo:/var/www/html/user/themes
    ```
  * それから、 `ddev restart` を実行


<h2 id="note-about-ddev-and-the-feed-plugin">DDEV と Feed プラグインの注意点</h2>

DDEV はデフォルトでは nginx を使用し、 2020-09-18 時点ではデフォルト設定で、ほとんどのケースに問題なく動きます。ただし、[feed プラグイン](https://github.com/getgrav/grav-plugin-feed) を使いたいと思っている場合は、以下の設定変更が必要になるでしょう：

  * `[GRAV_ROOT].ddev/nginx_full/nginx-site.conf` を編集
  * 変更を継続するために3行目を削除（ `#ddev-generated` ）
  * rss と atom の静的キャッシュを矯正する 58-62 行目を削除（ `# Expire rules for static content ...` ）
  * `ddev restart` を実行し、新しい nginx 設定を読み込む

これらの変更を行わないと、 rss や atom feed を読み込もうとしたときに HTTP Error 404 が発生します。

<h2 id="using-ddev">DDEV の使い方</h2>

ホストマシンの Grav のルートディレクトリから、以下のコマンドを実行します：

* `ddev describe` - 利用可能なサービスをすべて表示
* `ddev ssh` - ドキュメントルートで web サーバーに shell 接続
* `ddev exec 'params'` - ドキュメントルートでパラメータを実行（例： `ddev exec 'bin/grav clear'` により、キャッシュをクリアする）

> 【プラグイン名・テーマ名】をインストールしたいのですが、どうやって `bin/gpm` を実行できますか？

Grav のルートディレクトリで、 `ddev ssh` と入力すると、web サーバーのドキュメントルートに接続します。そこから、 php コマンドを実行できます（ composer や、 bin/gpm, bin/grav, など）。

> どこのファイルを編集するのですか？

ホストマシンのエディタは、 Grav のルートディレクトリにあるファイルを編集できます。変更は自動的に DDEV のコンテナに反映されます。コンテナ内で実行された変更（例： `bin/gpm install admin` ）も、ホストマシンに反映されます。

