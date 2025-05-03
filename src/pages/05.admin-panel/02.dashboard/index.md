---
title: "ダッシュボード"
layout: ../../../layouts/Default.astro
---

![Admin Dashboard](grav-dashboard.png)

**ダッシュボード** は、**管理パネル** プラグインで情報のハブ（hub）となるものです。この1ページから、アクセス統計の確認や、情報のメンテナンスの確認、Grav のアップデートの確認、新しいバックアップ作成、最新のページ更新状況確認、Grav のキャッシュをすばやくクリアすることもできます。

ここは、管理する上でのスタートポイントとなる場所です。

> [!Info]  
> ダッシュボードに表示されるコンテンツは、ユーザーのパーミッションにより変わります。たとえば、 `access.admin.super` というパーミッションを与えると、すべてが表示されます。もしそのアクセスレベルが許可されていなければ、 `access.admin.maintenance` でキャッシュのクリアとアップデートが許可されます。 `access.admin.pages` で、ページにアクセスできます。 `access.admin.statistics` で、サイト閲覧の統計情報が表示されます。


<h3 id="cache-and-updates-checking">キャッシュとアップデートをチェック</h3>

![Admin Dashboard](grav-dashboard-cache.png)

ダッシュボードの上部に、2つのボタンがあります。最初のものは、 Grav のキャッシュをクリアします。メインの **Clear Cache** ボタンをクリックすると、すべてのキャッシュをクリアします。アセットと画像のキャッシュも含んでクリアされます。右にある **ドロップダウン** 機能を使うと、特定のタイプのキャッシュのみ消す処理を選べます。

たとえば、他のキャッシュを残したまま、 **画像キャッシュ** のみクリアしたいとき、ここのドロップダウンで、それができます。

2つ目のボタンは、サイトのアップデート状況をチェックできます。
The second button initiates an update check for your site. This includes any supported plugins, themes, and Grav itself. If new updates are discovered, you receive a notification on the Dashboard. This isn't the only method Grav has for checking for new updates.

> [!Info]  
> Update checks are also triggered whenever a new page in the admin is loaded, and cached for one day. If you clear all of Grav's cache and load a new page in the admin, an update check will automatically take place.

### Maintenance and Page View Statistics

![Admin Dashboard](grav-dashboard-maintenance.png)

The **Maintenance** and **Page View Statistics** sections give you quick access to important information about your site.

On the **Maintenance** side, you can see a percentage graph letting you know how many of Grav's bits and pieces are completely up-to-date.

![Admin Dashboard](grav-dashboard-maintenance-2.png)

If new updates are available, an <i class="fa fa-cloud-download"></i> **Update** button will appear that enables you to perform a one-click update for all plugins and themes. This button will not update Grav itself, which notifies you about a required update just above the Maintenance and Page View Statistics sections.

You can update Grav's core by selecting the **Update Grav Now** button in its notification bar.

There is also a graph indicating how long the site has gone without being backed up. Selecting the <i class="fa fa-database"></i> **Backup** button will generate a zip file you can download and store as a backup for your site's data.

!! Backups are also stored in the `backup/` folder of your Grav install.  You can grab them via FTP or web manager tools provided by your hosting company.

The **Page View Statistics** section displays simple, at-a-glance traffic data breaking down the number of page views the front end of the site has received in the past day, week, and month (30 days). Page View Statistics for the past week are displayed in a bar graph separated by days of the week.

### Latest Page Updates

![Admin Dashboard](grav-dashboard-latest.png)

The **Latest Page Updates** area of the admin gives you an at-a-glance view of the latest content changes made to pages in your Grav site. This list is sorted by most recently updated, and is generated each time you refresh the page. Selecting the title of a page in this list will take you directly to the page's editor in the admin.

The **Manage Pages** button takes you to the **Pages** administrative panel.

