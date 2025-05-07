---
title: "管理パネルのイベントフック"
layout: ../../../../layouts/Default.astro
---

管理パネルプラグインには、複数のイベントフックがあり、 [Grav のライフサイクル](../../04.plugins/05.grav-lifecycle/) 中に利用できます。プラグインでイベントフックを使う方法は、[Plugins](../../04.plugins/) にある一般的なプラグインのドキュメントをご覧ください

## Available Admin Event Hooks

* [onAdminTaskExecute](#onadmintaskexecute)
* [onAdminCreatePageFrontmatter](#onadmincreatepagefrontmatter)
* [onAdminSave](#onadminsave)
* [onAdminAfterSave](#onadminaftersave)
* [onAdminAfterSaveAs](#onadminaftersaveas)
* [onAdminAfterDelete](#onadminafterdelete)
* [onAdminAfterAddMedia](#onadminafteraddmedia)
* [onAdminAfterDelMedia](#onadminafterdelmedia)


## Enabling an Admin Event Hook

管理パネルのイベントフックは、コアのイベントフックの呼び出し方と [同じ方法](../../04.plugins/03.plugin-tutorial/#step-6-determine-if-the-plugin-should-run) で呼び出せます。


* * *

### onAdminTaskExecute

The Admin plugin fires various tasks, depending on user interaction.  Tasks might include logout, login, save, 2faverify, etc.  After the task completes, this event hook fires.

### onAdminCreatePageFrontmatter

While creating a new page, this event is fired after the header data is initially set to allow plugins to programmatically manipulate the frontmatter.

### onAdminSave

Use admin event `onAdminSave()` to manipulate the page object data `$object` before it is saved to the filesystem.

### onAdminAfterSave

After saving the page in the administration panel, this event is fired.

### onAdminAfterSaveAs

When creating a folder via the panel, this event fires immediately after creating the new folder and performing a standard cache clear.

### onAdminAfterDelete

Fires after a page or folder is deleted.  It is immediately followed by a standard cache clear.

### onAdminAfterAddMedia

Fires after an add media task completes, but before the confirmation message is displayed.

### onAdminAfterDelMedia

Fires after a delete media task completes, but before the confirmation message is displayed.

