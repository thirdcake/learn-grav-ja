---
title: スケジューラ
layout: ../../../layouts/Default.astro
lastmod: '2025-05-05'
---
Grav のスケジューラは、 Grav 1.6 で追加された新しい機能で、ジョブを定期的に実行します。基本的な処理は、サーバーの **cron** スケジューラに依存しますが、 cron サービスにエントリーを1つ追加すると、すべてのジョブと特定のスケジュールを Grav から設定できるようになります。

スケジューラを使ってタスクを処理する主な利点のひとつは、ユーザーによる操作の必要なく、フロントエンドから独立してタスクを実行できることです。定期的なキャッシュクリア、バックアップ、同期、検索インデックス作成などのタスクは、いずれもスケジュールジョブの最適な候補です。

## Installation

The first step in getting the scheduler setup and ready for tasks, is to add the `bin/grav scheduler` command to the cron service.  The simplest approach is to utilize the CLI command itself to output the appropriate command to run for installation:

```bash
$ bin/grav scheduler -i

Install Scheduler
=================

 [ERROR] You still need to set up Grav's Scheduler in your crontab

 ! [NOTE] To install, run the following command from your terminal:

 (crontab -l; echo "* * * * * cd /Users/andym/grav;/usr/local/bin/php bin/grav scheduler 1>> /dev/null 2>&1") | crontab -
```

On my mac system, the full command required is displayed, so all you need to do is to copy and paste the entire then into your terminal and hit return.

> [!Info]  
> You need to be logged in to the shell with the same user as your webserver.  This is to ensure that the user that runs the schdeduler commands matches the webserver user that needs to interact with those files.  If you install the crontab entry with another user (e.g. `root`) any files created will be created as that `root` user and not the `webserver` user which can lead to problems.

```bash
(crontab -l; echo "* * * * * cd /Users/andym/grav;/usr/local/bin/php bin/grav scheduler 1>> /dev/null 2>&1") | crontab -
```

 You won't get a response, but you should not get any errors either.  After that you can confirm things look good by re-running the `bin/grav scheduler -i` command:

```bash
bin/grav scheduler -i

Install Scheduler
=================

 [OK] All Ready! You have already set up Grav's Scheduler in your crontab
```

You can also get the needed command from the admin plugin by simply navigating to **Tools** → **Scheduler**.

## Scheduling Basics

In order to schedule a job the frequency is controlled by a flexible format.

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

Some examples:

`0 * * * *`	run once an hour (every hour at minute zero)
`0 0 * * *`	run once a day (every day at midnight and minute zero)
`0 0 1 * *`	run once a month (on the first day of every month at midnight and minute zero)
`0 0 1 1 *`	run once a year (on the first day of the first month every year at midnight and minute zero)

Advanced options:

`*/5 * * * *` run every 5 minutes



## Configuration File

You can see which jobs are currently available to the Scheduler  by running the `bin/grav scheduler -j` command:

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

The Grav scheduler is controlled by a primary configuration file.  This is located in `user/config/scheduler.yaml` and is required to have any job `enabled` in order to run.

Below the configruation shows the jobs that are available and if they are enabled to run or not.  Simply set an entry to `disabled` to stop it from running.

```yaml
status:
  ls-job: enabled
  cache-purge: enabled
  cache-clear: enabled
  default-site-backup: enabled
  pages-backup: enabled
```

To see more details about any potential **errors** or to see the next time the job will run you can use the `/bin/grav scheduler -d` command:

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

## Manually Running Jobs

The CLI command provides an simple way to manually run any jobs.  In fact this is what the scheduler is doing when it runs periodically.

```bash
bin/grav scheduler
```

This will silently run the jobs, but you can also see details of what run using:

```bash
bin/grav scheduler -v
```

## Grav System Jobs

The Grav core provides a few jobs out-of-the-box.  These include some useful maintenance type tasks:

* `cache-purge` - This task is useful if you use Grav's `file` caching because it clears out old files that have expired.  This is a great task to schedule as otherwise it would require a user to manually clear the old caches.  If you don't keep up on this, and your file space is limited, you could run out of space and crash the server.

* `cache-clear` - The cache clear is the job that works the same way as the `bin/grav clear` command that you would manually run.  You can configure if you want to use a `standard` cache clearing, or the `all` variant that deletes all the files and folders in the `cache/` folder for a more thorough cache clearing.

* `default-site-backup` - The default backup job available via the new Grav Backup configuration.  You can create custom backup configurations, and these will also be available to run as a scheduled job.

## Custom Jobs

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


