---
title: "はじめに"
layout: ../../../layouts/Default.astro
---

[Grav](https://github.com/getgrav/grav) の **管理パネル** プラグインは、web GUI（グラフィカル・ユーザ・インターフェイス）として、Gravの設定を便利にし、ページの作成や更新をかんたんにします。これは完全にオプションの（使っても使わなくても良い）プラグインであり、Gravを効率的に使用するために必要なものでもありません。実際、管理画面は、使いやすく、圧倒されないように、意図的に抑えめな見た目になっています。ヘビーユーザーの方々には、直接ファイルを操作する方法が好まれるでしょう。

![](admin-dashboard.png)

<h3 id="features">機能</h3>

* User login with automatic password hashing
* Forgot password functionality
* Logged-in-user management
* One click Grav core updates
* Dashboard with maintenance status, site activity and latest page updates
* Ajax-powered backup capability
* Ajax-powered clear-cache capability
* System configuration management
* Site configuration management
* Normal and Expert modes which allow editing via forms or YAML
* Page listing with filtering and search
* Page creation, editing, moving, copying, and deleting
* Powerful syntax highlighting code editor with instant Grav-powered preview
* Editor features, hot keys, toolbar, and distraction-free fullscreen mode
* Drag-n-drop upload of page media files including drag-n-drop placement in the editor
* One click theme and plugin updates
* Plugin manager that allows listing and configuration of installed plugins
* Theme manager that allows listing and configuration of installed themes
* GPM-powered installation of new plugins and themes
* ACL for admin users access to features

<h3 id="support">サポート</h3>

The Adminstration Panel is quite an ambitious plugin with lots of functionality that will give you a lot of power and flexibility when building out your Grav sites. So if you have any questions, problems, suggestions or find one of those rare bugs in it, please use one of the following ways to get support from us.

For **live chatting**, please use the [Discord Chat Server](https://chat.getgrav.org) for discussions  related to the admin plugin.

For **bugs, features, improvements**, please ensure you [create issues in the admin plugin GitHub repository](https://github.com/getgrav/grav-plugin-admin).

<h3 id="installation">インストール</h3>

First ensure you are running the latest Grav version, **{{ grav_version }} or later**.  This is required for the admin plugin to run properly.  Check for and upgrade to new Grav versions like this (`-f` forces a refresh of the GPM index):

```bash
bin/gpm version -f
bin/gpm selfupgrade
```

The admin plugin actually requires the help of 3 other plugins, so to get the **admin** plugin to work you first need to install the **login**, **forms**, and **email** plugins.  These are available via GPM, and because the plugin has dependencies you just need to proceed and install the admin plugin, and agree when prompted to install the others:

```bash
bin/gpm install admin
```

You can also [install the plugin manually](../09.faq/#manual-installation-of-admin) if you are unable to use GPM on your system.

<h3 id="creating-a-user">ユーザーを作成</h3>

With the latest version of the Admin, you will be prompted to create an admin user account when you point your browser to your site.  You must complete this step to ensure straight away a valid admin user is under your control.

![](new-user.png)

Simply fill out the form and click the `Create User` button.

The user information is stored in the `user/accounts/` folder of your Grav installation.  You can edit the values manually or via the Admin plugin itself.  You can also create new users manually or via the `bin/plugin login newuser` CLI command.  More information is contained in the [Admin FAQ](../09.faq/#adding-and-managing-users).

<h3 id="username-and-password-complexity">ユーザー名とパスワードの複雑さ</h3>

Regex patterns for usernames and passwords are defined in `system/config/system.yaml`.

The default pattern for users (`system.username_regex`) is only lowercase characters, digits, dashes, and underscores. Usernames must be between 3 - 16 characters in length.

The default pattern for passwords (`system.pwd_regex`) is a minimum of eight (8) characters, with at least one number, one uppercase, and one lowercase letter.

### Usage

By default, you can access the admin by pointing your browser to `http://yoursite.com/admin`. You can simply log in with the `username` and `password` set in the YAML file you configured earlier.

> After logging in, your **plaintext password** will be removed and replaced by an **encrypted** one.

