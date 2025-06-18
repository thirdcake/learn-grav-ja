---
title: スケジューラ
layout: ../../../layouts/Default.astro
lastmod: '2025-06-18'
---

Grav のスケジューラは、 Grav 1.6 で追加された新しい機能で、ジョブを定期的に実行します。基本的な処理は、サーバーの **cron** スケジューラに依存しますが、 cron サービスにエントリーを1つ追加すると、すべてのジョブと特定のスケジュールを Grav から設定できるようになります。

スケジューラを使ってタスクを処理する主な利点のひとつは、ユーザーによる操作の必要なく、フロントエンドから独立してタスクを実行できることです。定期的なキャッシュクリア、バックアップ、同期、検索インデックス作成などのタスクは、いずれもスケジュールジョブの最適な候補です。

<h2 id="installation">インストール</h2>

スケジュールセットアップとタスク準備のための最初のステップは、 `bin/grav scheduler` コマンドに cron サービスを追加することです。最も簡単なアプローチは、 CLI コマンド自身を利用して、インストールのために実行する適切なコマンドを出力することです：

```bash
$ bin/grav scheduler -i

Install Scheduler
=================

 [ERROR] You still need to set up Grav's Scheduler in your crontab

 ! [NOTE] To install, run the following command from your terminal:

 (crontab -l; echo "* * * * * cd /Users/andym/grav;/usr/local/bin/php bin/grav scheduler 1>> /dev/null 2>&1") | crontab -
```

わたしの mac システム上では、必要なフルコマンドが表示されました。そこで、必要なことは、これら全体をターミナルへコピーアンドペーストし、リターンキーを押すことです。

> [!Info]  
> ウェブサーバーと同じユーザーでシェルにログインしている必要があります。これは、スケジューラコマンドを実行するユーザーと、ファイル操作する必要のあるウェブサーバーユーザーが、同じであることを確定させるためです。もし別ユーザー（例えば `root` ユーザー）で crontab エントリーをインストールすると、作成されるファイルはすべて `root` ユーザーにより作成され、 `webserver` ユーザーが作成するものではないため、問題が発生する可能性があります。

```bash
(crontab -l; echo "* * * * * cd /Users/andym/grav;/usr/local/bin/php bin/grav scheduler 1>> /dev/null 2>&1") | crontab -
```

レスポンスは得られませんが、エラー表示もされないはずです。その後、 `bin/grav scheduler -i` コマンドを再実行すれば、うまくいっていることが確認できます：

```bash
bin/grav scheduler -i

Install Scheduler
=================

 [OK] All Ready! You have already set up Grav's Scheduler in your crontab
```

必要なコマンドは、管理パネルプラグインからも得られます。単純に、 **Tools** -> **Scheduler** と移動するだけです。

<h2 id="scheduling-basics">スケジュールの基本</h2>

ジョブをスケジュールするため、その頻度を柔軟なフォーマットで制御します。

```txt
* * * * * *
| | | | | |
| | | | | +-- Year              (range: 1900-3000)
| | | | +---- Day of the Week   (range: 1-7, 1 standing for Monday)
| | | +------ Month of the Year (range: 1-12)
| | +-------- Day of the Month  (range: 1-31)
| +---------- Hour              (range: 0-23)
+------------ Minute            (range: 0-59)
```

いくつか具体例を示します：

`0 * * * *`	1時間に1回実行 (毎時間の0分)
`0 0 * * *`	1日に1回実行 (毎日の深夜0時0分)
`0 0 1 * *`	1月に1回実行 (毎月の最初の日の深夜0時0分)
`0 0 1 1 *`	1年に1回実行 (毎年の最初の月の最初の日の深夜0時0分)

上級者向けオプション：

`*/5 * * * *` 5分ごとに実行

<h2 id="configuration-file">設定ファイル</h2>

スケジューラーの現在利用可能なジョブがどれか知るには、 `bin/grav scheduler -j` コマンドを実行します：

```bash
bin/grav scheduler -j

Scheduler Jobs Listing
======================

┌─────────────────────┬────────────────────────────────────┬───────────┬─────────┬──────────────────┬─────────┐
│ Job ID              │ Command                            │ Run At    │ Status  │ Last Run         │ State   │
├─────────────────────┼────────────────────────────────────┼───────────┼─────────┼──────────────────┼─────────┤
│ cache-purge         │ Grav\Common\Cache::purgeJob        │ * * * * * │ Success │ 2019-02-21 11:23 │ Enabled │
│ cache-clear         │ Grav\Common\Cache::clearJob        │ * * * * * │ Success │ 2019-02-21 11:23 │ Enabled │
│ default-site-backup │ Grav\Common\Backup\Backups::backup │ 0 3 * * * │ Ready   │ Never            │ Enabled │
│ pages-backup        │ Grav\Common\Backup\Backups::backup │ * 3 * * * │ Success │ 2018-09-20 09:55 │ Enabled │
│ ls-job              │ ls                                 │ * * * * * │ Success │ 2019-02-21 11:23 │ Enabled │
└─────────────────────┴────────────────────────────────────┴───────────┴─────────┴──────────────────┴─────────┘

 ! [NOTE] For error details run "bin/grav scheduler -d"
```

Grav スケジューラは、主要な config 設定ファイルによって制御されます。これは、 `user/config/scheduler.yaml` にあり、ジョブを実行するためには `enabled` にする必要があります。

以下の設定には、利用可能なジョブが表示され、それらが実行可能かどうかが表示されています。 `disabled` に設定するだけで、実行されなくなります。

```yaml
status:
  ls-job: enabled
  cache-purge: enabled
  cache-clear: enabled
  default-site-backup: enabled
  pages-backup: enabled
```

ありうる **errors** の詳細や、次に実行されるジョブについては、 `/bin/grav scheduler -d` コマンドを使って確認できます：

```bash
bin/grav scheduler -d

Job Details
===========

┌─────────────────────┬──────────────────┬──────────────────┬────────┐
│ Job ID              │ Last Run         │ Next Run         │ Errors │
├─────────────────────┼──────────────────┼──────────────────┼────────┤
│ cache-purge         │ 2019-02-21 11:29 │ 2019-02-21 11:31 │ None   │
│ cache-clear         │ 2019-02-21 11:29 │ 2019-02-21 11:31 │ None   │
│ default-site-backup │ Never            │ 2019-02-22 03:00 │ None   │
│ pages-backup        │ 2018-09-20 09:55 │ 2019-02-22 03:00 │ None   │
│ ls-job              │ 2019-02-21 11:29 │ 2019-02-21 11:31 │ None   │
└─────────────────────┴──────────────────┴──────────────────┴────────┘
```

<h2 id="manually-running-jobs">手動によるジョブ実行</h2>

CLI コマンドにより、すべてのジョブを手動実行する簡単な方法が提供されています。実際、これはスケジューラーが定期実行するときに行っていることです。

```bash
bin/grav scheduler
```

これは、静かに（実行経過が表示されずに）実行されますが、実行内容の詳細を見ることもできます。次のようにしてください：

```bash
bin/grav scheduler -v
```

<h2 id="grav-system-jobs">Grav システムジョブ</h2>

Grav コアは、最初からいくつかのジョブを提供しています。これらには、メンテナンスに便利なタスクを含みます：

* `cache-purge` - このタスクは、 Grav の `file` キャッシュを使っているときに便利です。期限切れの古いファイルをクリアするタスクだからです。このタスクが大事なのは、そうしないと、ユーザーが手動で古いキャッシュをクリアする必要があるからです。この作業を怠り、ファイルスペースが限られてきたとき、容量が足りなくなり、サーバーがクラッシュするかもしれません。

* `cache-clear` - キャッシュクリアは、 `bin/grav clear` コマンドを手動実行するのと同じ方法で実行されるジョブです。`standard` なキャッシュクリアを使いたいか、 `all` バリエーションにより、 `cache/` フォルダ内のすべてのファイルとフォルダを完全に削除したいかを設定できます。

* `default-site-backup` - デフォルトのバックアップジョブは、新しい Grav のバックアップ config 設定から利用できます。カスタムのバックアップ設定を作成でき、これらの設定もスケジュールされたジョブとして実行可能です。

<h2 id="custom-jobs">カスタムジョブ</h2>

The Grav Scheduler can be manually configured with any number of custom jobs.  These can be setup in the same `scheduler.yaml` configuration file referenced above.  For example, the `ls-job` referenced above would be configured:

```yaml
custom_jobs:
  ls-job:
    command: ls
    args: '-lah'
    at: '* * * * *'
    output: logs/cron-ls.out
    output_mode: overwrite
    email: user@email.com
```

The command should be any local script that can be run from the command line/terminal.  Only the `command` and the `at` attributes are required.

## Plugin-provided Jobs

One of the most powerful feature of the Grav Scheduler, is the ability for 3rd party plugins to provide their own jobs.  A great example of this is provided by the `TNTSearch` plugin.  TNTSearch is a full-featured text search engine that requires content to be indexed before it can be searched.  This indexing job can be performed in a variety of ways, but the Grav Scheduler allows you to reindex your content periodically rather than having to do so manually.

The first step is for your plugin to subscribe to the `onSchedulerInitialized()` event.  And then create a method in your plugin file that can add a custom job when called:

```php
public function onSchedulerInitialized(Event $e): void
{
    $config = $this->config();

    if (!empty($config['scheduled_index']['enabled'])) {
        $scheduler = $e['scheduler'];
        $at = $config['scheduled_index']['at'] ?? '* * * * *';
        $logs = $config['scheduled_index']['logs'] ?? '';
        $job = $scheduler->addFunction('Grav\Plugin\TNTSearchPlugin::indexJob', [], 'tntsearch-index');
        $job->at($at);
        $job->output($logs);
        $job->backlink('/plugins/tntsearch');
    }
}
```

Here, you can see how some relevant scheduler configuration is obtained from the TNTSearch plugin's configuration settings, and then a new `Job` is created with a **static** function called `indexJob()`.


