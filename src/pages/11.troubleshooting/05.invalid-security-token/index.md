---
title: "Invalid Security Token"
layout: ../../../layouts/Default.astro
---

**問題：** 管理パネルにログインして、処理を実行中に、この Invalid Security Token エラーが表示されました。

問題の原因はいくつか考えられますが、すべて session に関係します：

- ブラウザを **リロード** してみて、トークンを新しくしてみます

- ブラウザの session cookies をクリアしてみます。また、一度ログアウトして戻ってみます。

- Ensure you are running under SSL and a HTTPS URL if you have `session.secure: true` set in Grav's `system.yaml`

- Check that PHP has the correct tmp path set up. This can be set in PHP directly, or by setting Grav's `system.yaml` `session.path` setting (it can also be set via Admin, in the System Configuration) [Reported issue](https://github.com/getgrav/grav-plugin-admin/issues/958)

- Make sure your web server config is right and includes the query string [Reported issue](https://github.com/getgrav/grav-plugin-admin/issues/893)

- Make sure your hostname doesn't have underscores in it. It will cause the hostname to default to `unknown`, making the session invalid.

