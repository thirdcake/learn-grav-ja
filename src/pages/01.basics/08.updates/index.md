---
title: "Gravとプラグインのアップデート"
layout: ../../../layouts/Default.astro
---

Gravと、プラグイン、テーマを最新に保つより良い方法は、**Gravパッケージ・マネージャー(GPM)** を使うことです。すべての情報は、[Grav GPM ドキュメント](../../07.cli-console/04.grav-cli-gpm)にあります。

また、**GPM** は、[管理パネル](../../05.admin-panel)プラグインに統合されており、あらゆるアップデートをチェックし、プロンプトを表示し、自動的にインストールします。

<h3 id="which-version-do-i-have">バージョンの確認方法</h3>

サイトが使うGravとプラグインのバージョンを知る方法は、いくつもあります：

* **管理パネル** ：Gravのバージョンは、ページのフッターに表示されています。プラグインとテーマのバージョンは、それぞれのセクションに表示されています。
* **CLI** ：`bin/gpm version grav` コマンドを実行してください。テーマとプラグインのバージョンリストがそれらの名前とともに表示されます。
* **ファイルシステム** ：バージョンを確認する最もかんたんな方法は、Gravをインストールしたルートディレクトリの`CHANGELOG.md` ファイルを見ることです。プラグインとテーマについても同じで、通常`user/plugins` と、`user/themes` フォルダ内に、それぞれ見つかります。

<h3 id="upgrading-from-grav-1-5-or-older-version">Grav1.5以前のバージョンからのアップグレード</h3>

Updating an older version of Grav may need some extra preparations and work because of the increased minimum requirements and potential incompatibilities.

The basic workflow is following:

- Copy the site to a server with **PHP 7.3** and **CLI** support
- Upgrade manually **to Grav 1.6.31**
- Upgrade to the latest version

A detailed guide **[Upgrading from Grav <1.6](../../08.advanced/09.grav-development/01.grav-15-upgrade-guide)** should help you in the process.

### 次のバージョンへのアップグレード

次のバージョンにアップデートについては、アップグレード後もすべてが機能することを確認するための特別なガイドがあります。

- **[Grav1.7にアップグレード](../../08.advanced/09.grav-development/03.grav-17-upgrade-guide)**
- **[Grav1.6にアップグレード](../../08.advanced/09.grav-development/02.grav-16-upgrade-guide)**

> [!Note]  
> Gravの次のバージョンをインストールする前に、このアップグレードガイドを読むことをおすすめします。

<h3 id="grav-cms-updates">Grav CMSのアップデート</h3>

Gravをアップデートするより良い方法は、**Gravパッケージ・マネージャ(GPM)** を使うことです。やるべきことは、Gravサイトのルートフォルダに移動し、次のように入力することだけです：

```bash
bin/gpm selfupgrade -f
```

> [!Tip]  
> コマンドの詳しい情報は、[GPM コマンド > 自身をアップグレード](../../07.cli-console/04.grav-cli-gp/m#self-upgrade) にあります。

### Plugin and Theme Updates

プラグインとテーマは、Gravサイトのルートフォルダで、次のようにコマンドを実行することでアップデートできます：

```bash
bin/gpm update
```
> [!Tip]  
> コマンドの詳しい情報は、[GPM コマンド > 自身をアップグレード](../../07.cli-console/04.grav-cli-gp/m#self-upgrade) にあります。

