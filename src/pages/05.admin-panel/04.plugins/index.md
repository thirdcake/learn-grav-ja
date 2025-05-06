---
title: "プラグイン"
layout: ../../../layouts/Default.astro
---

![Admin Plugins](plugins.png)

管理パネルの **Plugins** エリアでは、プラグインの管理ができます。新しいプラグインを追加したり、既存のプラグインを削除したり、プラグインのドキュメントやその他の情報にすぐにアクセスできるリンクが表示されたりします。

このページでは、管理パネルのこのエリアを詳しく見ていき、インストールしたプラグインの有効化・無効化の方法や、簡単にプラグインを追加する方法を解説します。

> [!Info]  
> この Plugins 機能にアクセスするには、 `access.admin.super` パーミッションもしくは `access.admin.plugins` パーミッションが必要です。

### Controls

![Admin Plugins](plugins-1.png)


The **Controls** area at the top of the page gives you the ability to add new plugins, as well as check for updates for existing ones.

![Admin Plugins](plugins-2.png)

The <i class="fa fa-plus"></i> Add button takes you to a page listing all of the currently available Grav plugins, enabling you to view their information and install them by selecting the <i class="fa fa-plus"></i> Install button to the right of the plugin.

> [!Note]  
> The <span color="purple"><i class="fa fa-check-circle"></i></span> icon indicates that the plugin is created and supported by the Grav team. Plugins without this icon were created by third-party developers.

### Installed Plugins

![Admin Plugins](plugins-4.png)

This area of the Plugins administrator shows you, at a glance, which plugins are presently installed on your Grav site. Additionally, you can enable and disable these plugins by selecting the <i class="fa fa-fw fa-toggle-on"></i> toggle icon to the right of each plugin.

![Admin Plugins](plugins-3.png)

Selecting the <i class="fa fa-chevron-down"></i> chevron icon will give you more information about the plugin, including its author, project home page and bug tracker, as well as its license and a brief description. You can also quickly access the plugin's readme file for additional information and usage guide.

You can also click the plugin's name to go to a more-detailed page including the plugin's settings area where you can configure the plugin.

