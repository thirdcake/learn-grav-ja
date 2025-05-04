---
title: "プロフィール"
layout: ../../../../layouts/Default.astro
---

![Admin Profile](grav-profile.png)

管理パネルのプロフィールページから、あなた個人のプロフィール設定を閲覧したり、更新したりできます。アバターや、メールアドレス、名前、言語、その他たくさんの設定ができる場所です。管理者にとっては、個々のユーザーにグループやパーミッションのレベルを設定する場所でもあります。

プロフィールページへのアクセスは簡単です。管理パネルにログインしたら、サイドバーのアバター画像と名前が書いてあるエリアを選択してください。あなた自身のプロフィールページへ直接リンクしています。

加えて、管理者にとっては、サイト URL に、 `admin/user/ユーザー名` と付け加えることで、 他のユーザーのプロフィールページへ簡単に飛べます。 `ユーザー名` のところは、プロフィール情報やパーミッションを編集したいと思っているユーザーのユーザー名に書き換えてください。

<h3 id="profile-photo">プロフィール写真</h3>

![Admin Profile](grav-profile2.png)

管理パネルの **プロフィール** エリアでは、すばやく、整理された見た目で、あなたのアバターや、名前、タイトルが表示されます。アバターは、 [Gravatar](http://en.gravatar.com/) というグローバルなアバターサービスで自動的に生成されます。そこに、ひとつのプロフィール画像をアップロードすれば、それが有効化され、これは、複数のサイトや複数のサービスをまたいで利用可能です。

![Admin Profile](grav-profile2b.png)

Gravatar に画像をアップロードしていなければ、もしくは、あなたが選んだ画像を使いたい場合は、ページの **Drop Your Files Here or Click This Area** と書いてあるセクションに、画像をドラッグ・アンド・ドロップすることで、ここの画像をアップロードできます。そのエリアをクリックすることでも、ファイル選択が立ち上がり、そこで選び、手元のシステムから画像ファイルをアップロードできます。

新しい画像をアップロードしてから、ページ上部の右端にある **Save** ボタンを選択してください。

<h3 id="account">アカウント</h3>

![Admin Profile](grav-profile3.png)

プロフィールページの **アカウント** セクションでは、コンタクト情報や、名前、言語その他を更新できます。ここでは、 **ユーザー名** は編集できません。ユーザー名は、あなたのユーザー情報が保存されている場所と直接結びついているためです。しかし、ユーザー名以外については、編集可能です。

<h3 id="2-factor-authentication">2要素認証</h3>

![Admin Profile](grav-profile5.png)

**2要素認証** は、別レイヤーの web サイトセキュリティを提供します。この機能については、このガイドの [**セキュリティ**](../../06.security/01.2fa/) エリアで詳しく解説しています。

<h3 id="access-levels">アクセスレベル</h3>

![Admin Profile](grav-profile4.png)

管理者には、特に便利なパーミッションのエリアが表示されます。このエリアでは、ユーザーが、管理画面内でどこにアクセスでき、なにができるのかを正確に設定できます。

以下に、ざっくりとパーミッションのオプションと、その人がなにができるのかを掘り下げます。

| オプション | 説明 |
| :-----     | :-----  |
| **admin.super**                | Designates the user as a super admin, giving them the ability to see and configure all areas of the site.        |
| **admin.login**                | Enables the user to log in to the admin. This must be set to **Yes** to enable the user to log in.               |
| **admin.cache**                | Gives the user access to the cache reset buttons.                                                                |
| **admin.configuration**        | Gives the user access to the **Configuration** area of the admin. This does not include any tabs or subsections. |
| **admin.configuration_system** | Gives the user access to the **System** tab in the **Configuration** area of the admin.                          |
| **admin.configuration_site**   | Gives the user access to the **Site** tab in the **Configuration** area of the admin.                            |
| **admin.configuration_media**  | Gives the user access to the **Media** tab in the **Configuration** area of the admin.                           |
| **admin.configuration_info**   | Gives the user access to the **Info** tab in the **Configuration** area of the admin.                            |
| **admin.pages**                | Gives the user access to the **Pages** area of the admin.                                                        |
| **admin.maintenance**          | Gives the user the ability to access the **Maintenance** area of the **Dashboard**.                              |
| **admin.statistics**           | Gives the user the ability to access the **Statistics** area of the **Dashboard**.                               |
| **admin.plugins**              | Gives the user access to the **Plugins** area of the admin.                                                      |
| **admin.themes**               | Gives the user access to the **Themes** area of the admin.                                                       |
| **admin.users**                | Enables the user to access and edit other users' profile information. This does not include permissions.         |
| **site.login**                 | Enables the user to log in to the front end.                                                                     |

