---
title: バックアップ
layout: ../../../layouts/Default.astro
lastmod: '2025-06-18'
---

Grav のバックアップシステムは、 Grav 1.6 で完全に書きかえられ、より多くの特長や機能が提供されました。改善点は、次のとおりです：

* Grav の新しい [スケジューラ](../06.scheduler/) と統合されたことで、好きな時にオフラインバックアップが取れるようになりました
* 複数のバックアップ **プロファイル** が作成可能となり、各プロファイルごとのファイルセットや、除外パスとファイルルール、そしてスケジュール設定が含まれます
* `number`, `space` もしくは `time` をもとにした新しい **auto-purge** オプション
* 管理パネルプラグインの **Tools** セクション内に新しく専用のバックアップページを追加

<h2 id="configuration">config 設定</h2>

後方互換性のため、デフォルトの config 設定は Grav 1.6 以前のシステムに似せていますが、デフォルトで 5GB のバックアップスペースの制限があります。デフォルトの config ファイル（ `system/config/backups.yaml` ）をあなたの `user/config/` フォルダへコピーしてください。

> [!Info]  
> **管理パネルプラグイン** を使っている場合で、config 設定を保存する場合は、 `user/config/backups.yaml` ファイルが自動で作成されます。

デフォルトの config 設定は、以下のようになっています：

```yaml
purge:
    trigger: space
    max_backups_count: 25
    max_backups_space: 5
    max_backups_time: 365

profiles:
  -
    name: 'Default Site Backup'
    root: '/'
    schedule: false
    schedule_at: '0 3 * * *'
    exclude_paths: "/backup\r\n/cache\r\n/images\r\n/logs\r\n/tmp"
    exclude_files: ".DS_Store\r\n.git\r\n.svn\r\n.hg\r\n.idea\r\n.vscode\r\nnode_modules"
```

<h4 id="purge">パージ（バックアップの消去）</h4>

* `space` - は、容量制限に達したとき、古いバックアップをパージします。 ``max_backups_space`` により、 `GB` 単位で制御します。
* `time` - は、日数を超えたときに古いバックアップをパージします。 ``max_backups_time`` により、 `days` 単位で制御します。
* `number` - は、バックアップ数を超えたときに古いバックアップをパージします。``max_backups_count`` により制御します。

<h4 id="profiles">プロファイル</h4>

プロファイルの配列は、設定可能です。 `Default Site Backup` プロファイルは、以前のバージョンの Grav バックアップに似せて設定されます。デフォルトでは、バックアップはスケジューラで自動処理されませんが、 `schedule: true` を設定でき、``schedule_at:`` オプションをお好みの[cron 表現](https://crontab.guru/) により設定できます。

プロファイルのより複雑な設定例は、次の通りです：

```yaml
profiles:
  -
    name: 'Default Site Backup'
    root: /
    exclude_paths: "/backup\r\n/cache\r\n/images\r\n/logs\r\n/tmp"
    exclude_files: ".DS_Store\r\n.git\r\n.svn\r\n.hg\r\n.idea\r\n.vscode\r\nnode_modules"
    schedule: true
    schedule_at: '0 4 * * *'
  -
    name: 'Pages Backup'
    root: 'page://'
    exclude_files: .git
    schedule: true
    schedule_at: '* 3 * * *'
```

<h2 id="cli-command">CLI コマンド</h2>

詳細は、 [Cli Console -> Grav Command](../../07.cli-console/02.grav-cli/) セクションで解説されていますが、ここでは、手動でバックアップを実行する例を示します：

```bash
cd ~/workspace/portfolio
bin/grav backup

Grav Backup
===========

Choose a backup?
  [0] Default Site Backup
  [1] Pages Backup

Archiving 36 files [===================================================] 100% < 1 sec Done...

 [OK] Backup Successfully Created: /users/joe/workspace/portfolio/backup/pages_backup--20190227120510.zip
```

