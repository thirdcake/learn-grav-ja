---
title: 'Gravとプラグインのアップデート'
lastmod: 
description: 'Grav では、コアのアップグレードも、各種テーマ・プラグインのアップデートも、 CLI や管理画面から、簡単にアップデート可能です。専用のパッケージマネージャーを使って本体やプラグインを更新できます。'
weight: 80
params:
    srcPath: '/basics/updates'
---

Grav と、プラグイン、テーマを最新に保つより良い方法は、 **Grav パッケージ・マネージャー(GPM)** を使うことです。  
すべての情報は、 [Grav GPM ドキュメント](../../07.cli-console/04.grav-cli-gpm/)にあります。

また、**GPM** は、[管理パネル](../../05.admin-panel/) プラグインに統合されており、あらゆるアップデートをチェックし、プロンプトを表示し、自動的にインストールします。

### バージョンの確認方法{#which-version-do-i-have}

サイトが使う Grav とプラグインのバージョンを知る方法は、いくつもあります：

* **管理パネル** ： Grav のバージョンは、ページのフッターに表示されています。プラグインとテーマのバージョンは、それぞれのセクションに表示されています。
* **CLI** ：`bin/gpm version grav` コマンドを実行してください。テーマとプラグインのバージョンリストがそれらの名前とともに表示されます。
* **ファイルシステム** ：ファイルシステムでバージョンを確認する最も簡単な方法は、Gravをインストールしたルートディレクトリの `CHANGELOG.md` ファイルを見ることです。プラグインとテーマについても同様に、通常 `user/plugins` と、 `user/themes` フォルダ内に、それぞれ見つかります。

### Grav 1系から2.0 へのマイグレーション {migrating-from-grav-1-x-to-2-0}

Grav 2.0 は、 1.x 系と同じ場所からアップグレードしません。  
PHP の最低バージョン及び、 vendor スタック、管理パネル、 API に至るまで、すべてが新しくなったので、アップグレードの対応方法は、新しくインストールし、コンテンツをインポートすることです。

完全なプロセスは、以下を含む [マイグレーション](../../02.migration/) をご覧ください：

- Grav 2.0 へのマイグレートプラグインを利用する [アシスト付きマイグレーション](../../02.migration/02.assisted-migration)
- 複雑な設定があるサイトの場合の [マニュアルマイグレーション](../../02.migration/03.manual-migration/)
- プラグイン及びテーマ作成者のための [開発者向けアップグレードガイド](../../02.migration/05.developper-upgrade-guide/)

もし Grav 1.5 以前を利用している場合は、 GPM で 1.7 の最終リリース（`1.7.51`） にまずアップグレードし、それから 2.0 へマイグレートしてください。

### Plugin Compatibility Checks

Grav 2.0 introduces a `compatibility:` property in plugin and theme `blueprints.yaml`, declaring which major Grav versions a package has been tested against. The flag is read in two places:

- The **Migrate to Grav 2.0** wizard, when bringing a 1.x site across into a staged 2.0 install. Incompatible packages are routed through the user's skip-or-disable policy, with **strict** and **permissive** mode options for handling unflagged packages.
- **GPM**, when installing or updating plugins on an already-migrated 2.0 site. Packages without a 2.0-compatible flag won't install cleanly.

Plugin authors declare compatibility in their `blueprints.yaml` using the `compatibility:` property. See [Plugin Compatibility](/20/plugins/plugin-compatibility) for full details.

### Grav CMS のアップデート{#grav-cms-updates}

Grav をアップデートするより良い方法は、**Grav パッケージ・マネージャ(GPM)** を使うことです。  
やるべきことは、Grav サイトのルートフォルダに移動し、次のように入力することだけです：

```bash
bin/gpm selfupgrade -f
```

> [!Note]  
> コマンドの詳しい情報は、 [GPM コマンド &gt; self-upgrade](../../07.cli-console/04.grav-cli-gpm/#self-upgrade) にあります。


### Plugin and Theme Updates

Plugins and Themes can be kept up to date by running following command from the root of your Grav site:

```bash
bin/gpm update
```

> [!NOTE]
> **TIP:** More information about the command can be found from [GPM Command > Update](/20/cli-console/grav-cli-gpm#update).


### プラグインとテーマのアップデート{#plugin-and-theme-updates}

プラグインとテーマは、 Grav サイトのルートフォルダで、次のようにコマンドを実行することでアップデートできます：

```bash
bin/gpm update
```
> [!Note]  
> コマンドの詳しい情報は、[GPM コマンド &gt; アップデート](../../07.cli-console/04.grav-cli-gpm/#update) にあります。

