---
title: 'Invalid Security Token'
layout: ../../../layouts/Default.astro
lastmod: '2025-05-17'
---
**問題：** 管理パネルにログインして、処理を実行中に、この Invalid Security Token エラーが表示されました。

問題の原因はいくつか考えられますが、すべて session に関係します：

- ブラウザを **リロード** してみて、トークンを新しくしてみます

- ブラウザの session cookies をクリアしてみます。また、一度ログアウトして戻ってみます。

- Grav の `system.yaml` で `session.secure: true` となっている場合は、SSL 下で、 HTTPS URL にアクセスしているか確認します。

- PHP が、正しい tmp path に設定されているかチェックします。これは、 PHP で直接設定できるほか、 Grav の `system.yaml` ファイルの `session.path` 設定でもできます（これは、管理パネルの System 設定からも設定できます）。 [Reported issue](https://github.com/getgrav/grav-plugin-admin/issues/958)

- web サーバーの設定が正しくクエリ文字列を含んでいるか確認します。 [Reported issue](https://github.com/getgrav/grav-plugin-admin/issues/893)

- ホスト名にアンダースコアが含まれていないことを確認します。これは、ホスト名のデフォルトが `unknown` になり、セッションが無効になる原因となります。

