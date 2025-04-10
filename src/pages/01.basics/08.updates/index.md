---
title: "Gravとプラグインのアップデート"
layout: ../../../layouts/Default.astro
---

Gravと、プラグイン、テーマを最新に保つより良い方法は、**Gravパッケージ・マネージャー(GPM)** を使うことです。すべての情報は、[Grav GPM ドキュメント](/advanced/grav-gpm)にあります。

また、**GPM** は、[管理パネル](/admin-panel)プラグインに統合されており、あらゆるアップデートをチェックし、プロンプトを表示し、自動的にインストールします。

### Which version do I have?

サイトが使うGravとプラグインのバージョンを知る方法は、いくつもあります：

* **管理パネル** ：Gravのバージョンは、ページのフッターに表示されています。プラグインとテーマのバージョンは、それぞれのセクションに表示されています。
* **CLI** ：`bin/gpm version grav` コマンドを実行してください。テーマとプラグインのバージョンリストがそれらの名前とともに表示されます。
* **ファイルシステム** ：バージョンを確認する最もかんたんな方法は、Gravをインストールしたルートディレクトリの`CHANGELOG.md` ファイルを見ることです。プラグインとテーマについても同じで、通常`user/plugins` と、`user/themes` フォルダ内に、それぞれ見つかります。

### Upgrading from Grav 1.5 or older version

Updating an older version of Grav may need some extra preparations and work because of the increased minimum requirements and potential incompatibilities.

The basic workflow is following:

- Copy the site to a server with **PHP 7.3** and **CLI** support
- Upgrade manually **to Grav 1.6.31**
- Upgrade to the latest version

A detailed guide **[Upgrading from Grav <1.6](/advanced/grav-development/grav-15-upgrade-guide)** should help you in the process.

### Upgrading to the Next Version

次のバージョンにアップデートについては、アップグレード後もすべてが機能することを確認するための特別なガイドがあります。

- **[Grav1.7にアップグレード](/advanced/grav-development/grav-17-upgrade-guide)**
- **[Grav1.6にアップグレード](/advanced/grav-development/grav-16-upgrade-guide)**

> [!NOTE]
> Gravの次のバージョンをインストールする前に、このアップグレードガイドを読むことをおすすめします。

### Grav CMS Updates

Gravをアップデートするより良い方法は、**Gravパッケージ・マネージャ(GPM)** を使うことです。やるべきことは、Gravサイトのルートフォルダに移動し、次のように入力することだけです：

```bash
bin/gpm selfupgrade -f
```

> [!TIPS]
> コマンドの詳しい情報は、[GPM コマンド > 自身をアップグレード](/cli-console/grav-cli-gpm#self-upgrade) にあります。

### Plugin and Theme Updates

プラグインとテーマは、Gravサイトのルートフォルダで、次のようにコマンドを実行することでアップデートできます：

```bash
bin/gpm update
```
> [!TIPS]
> コマンドの詳しい情報は、[GPM コマンド > 自身をアップグレード](/cli-console/grav-cli-gpm#self-upgrade) にあります。

