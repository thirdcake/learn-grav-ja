---
title: "ユーザー"
layout: ../../../layouts/Default.astro
---

Grav を運用する際、管理パネルプラグインをインストールするかどうかに関わらず、留意してほしいベストプラクティスが、いくつかあります。それは、あなたの web サイトで、 *誰* が、 *何に* アクセス可能かに関することであり、この点について、リスク要因を制限しないことによる潜在的な危険性に関することです。

<h2 id="grav-users-and-the-administration-panel">Grav ユーザーと管理パネル</h2>

[管理パネル](../../05.admin-panel/) にアクセスするユーザーを作成するとき、そのユーザーのアクセス範囲を、まず考えましょう。
 The Admin-plugin offers solid [permissions](https://learn.getgrav.org/admin-panel/dashboard/profile#access-levels) that should be set to restrict what new users can do with the site. If you have many users, and some of them will only write content for the site, these should typically only need the `admin.pages`-permission -- as well as normal permissions like `admin.login`.

Further, it is always a best practice that your users do not have only one password that they use everywhere. If someone stole their password, they could then log in everywhere. A good password is a strong password, but even a long sentence using words in the dictionary is easier to crack than a password made up of random symbols, letters and numbers. Any human will have trouble remembering a long, random password [though](https://xkcd.com/936/) -- much less several -- so the [best practice](https://support.google.com/accounts/answer/32040) is to use a [password manager](https://alternativeto.net/tag/encrypted-passwords/) and **never the same password twice**. Many people also like to have their browser remember the passwords for each site, and only remember one strong password to unlock these. To generate a random password, you need only open Notepad and furiously hit random keys on your keyboard.

Tell your users to use random passwords and create one strong, long password that they'll remember. On occasion this long password should also be changed. And because the Admin-plugin features it, they should always use [2-Factor Authentication](https://learn.getgrav.org/admin-panel/security/2fa). To prevent brute force attacks against the Admin Panel, the administrator should also enable [Flood Protection](https://learn.getgrav.org/admin-panel/security/rate-limiting).

<h2 id="server-users-and-the-webmaster">サーバーのユーザーとwebマスター</h2>

The Webmaster is the person responsible for maintaining the website, and typically has access to it on a server-level. This person should of course [secure the server](https://learn.getgrav.org/security/server-side), but also ensure that he or she -- and anyone else with server-access -- only accesses the server in a secure manner. This means that **under no circumstance should the FTP-protocol be used**, only [SFTP](https://www.ssh.com/ssh/sftp/) with phrase-secured [key-pairs](https://www.ssh.com/ssh/public-key-authentication). The server host typically has information about disabling regular FTP and accessing the server through SFTP, creating key-pairs is [well documented](https://www.linode.com/docs/security/authentication/use-public-key-authentication-with-ssh/#generating-keys).

More broadly, consider whether any other user *needs* server-access. Every additional user with access is a potential risk, not just by their own behavior, but by the danger that if their keys or passwords are stolen others can access the server directly. In the same vein, **no users should have root access** to the server, and the system-user that runs PHP for Grav should be a separate user that only the system has access to.

Given how essential the server is for your website or service, taking care to protect it and its contents should be paramount.

