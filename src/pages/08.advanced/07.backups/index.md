---
title: バックアップ
layout: ../../../layouts/Default.astro
lastmod: '2025-05-05'
---
Grav のバックアップシステムは、 Grav 1.6 で完全に書きかえられ、より多くの特長や機能が提供されました。改善点は、次のとおりです：

* Grav の新しい [スケジューラ](../06.scheduler/) と統合されたことで、好きな時にオフラインバックアップが取れるようになりました
Ability to create multiple backup **profiles** each with their own set of files, exclude path and file rules, and schedule configuration
* New **auto-purge** options based on `number`, `space`, or `time`.
* New dedicated backups page in the **Tools** section of the admin plugin.

<h2 id="configuration">config 設定</h2>

For backwards compatibility, the default configuration mimics the system prior to Grav 1.6, however, it does now have a 5GB limit by default for backup space.  You should copy the default configuration file (`system/config/backups.yaml`) to your `user/config/`

!! If you use the **admin plugin**, and save the configuration, the `user/config/backups.yaml` file will be created automatically.

The default configuration is as follows:

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

* `space` - will purge older backups when you hit the space limit. Controlled by ``max_backups_space`` measured in `GB`
* `time` - will purge older backups beyond a number of day. Controlled by ``max_backups_time``  measured in `days`
* `number` - will purge older backups beyond a certain number of backups. Controlled by ``max_backups_count``.

<h4 id="profiles">プロファイル</h4>

An array of profiles can be configured.  The `Default Site Backup` profile is configured similarly to the default Grav backup in previous versions.  By default, the backup is not automatically processed by the scheduler, but you can set `schedule: true` and configure the ``schedule_at:`` option with a preferred [cron expression](https://crontab.guru/).

An example of a more complex set of profiles could be:

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

This is covered in more details in the [Cli Console -> Grav Command](../../07.cli-console/02.grav-cli/) section, but here's an example of running the backup manually:

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

