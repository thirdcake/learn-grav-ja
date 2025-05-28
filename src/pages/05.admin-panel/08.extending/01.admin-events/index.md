---
title: 管理パネルのイベントフック
layout: ../../../../layouts/Default.astro
lastmod: '2025-05-28'
---

管理パネルプラグインには、複数のイベントフックがあり、 [Grav のライフサイクル](../../04.plugins/05.grav-lifecycle/) 中に利用できます。プラグインでイベントフックを使う方法は、[Plugins](../../04.plugins/) にある一般的なプラグインのドキュメントをご覧ください

<h2 id="available-admin-event-hooks">利用可能な管理パネルのイベントフック</h2>

* [onAdminTaskExecute](#onadmintaskexecute)
* [onAdminCreatePageFrontmatter](#onadmincreatepagefrontmatter)
* [onAdminSave](#onadminsave)
* [onAdminAfterSave](#onadminaftersave)
* [onAdminAfterSaveAs](#onadminaftersaveas)
* [onAdminAfterDelete](#onadminafterdelete)
* [onAdminAfterAddMedia](#onadminafteraddmedia)
* [onAdminAfterDelMedia](#onadminafterdelmedia)


<h2 id="enabling-an-admin-event-hook">管理パネルのイベントフックの有効化</h2>

管理パネルのイベントフックは、コアのイベントフックの呼び出し方と [同じ方法](../../../04.plugins/03.plugin-tutorial/#step-7-determine-if-the-plugin-should-run) で呼び出せます。

---

### onAdminTaskExecute

管理パネルプラグインは、ユーザーの操作をもとに、複数のタスクを発火させます。タスクの中身は、ログアウトや、ログイン、保存、2FA 検証 などです。タスクが完了したあとに、このイベントフックが発火します。

### onAdminCreatePageFrontmatter

新しいページを作成中、ヘッダーデータが最初に設定された後に、このイベントが発火します。これにより、プラグインがプログラミングでフロントマターを操作できるようになります。

### onAdminSave

ページのオブジェクトデータである `$object` を、ファイルシステムに保存する前に操作するために使われるイベントです。

### onAdminAfterSave

管理パネルでページが保存されたあとに、このイベントが発火します。

### onAdminAfterSaveAs

管理パネルでフォルダを作成するとき、新規フォルダの作成直後にこのイベントが発火します。そして標準的なキャッシュクリアを実行します。

### onAdminAfterDelete

ページやフォルダが削除された後に発火します。標準的なキャッシュクリアが、すぐに続きます。

### onAdminAfterAddMedia

メディア追加タスクが完了し、確認メッセージが表示される前に発火します。

### onAdminAfterDelMedia

メディア削除タスクの完了後で、確認メッセージが表示される前に発火します。

