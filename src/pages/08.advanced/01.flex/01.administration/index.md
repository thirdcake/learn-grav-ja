---
title: "管理パネル"
layout: ../../../../layouts/Default.astro
---

Grav 1.7 で、 **管理パネル** を使ったことがあれば、すでに **Flex オブジェクト** を使っています。最良の具体例は、 **Accounts** と **Pages** です。これらは、**Flex** でできることの素晴らしい具体例です。

デフォルトでは、 **Flex Directory の管理画面** は、ユーザーに表示されません。 **Flex ディレクトリ** を表示するためには、それを有効化しなければいけません。有効化された flex ディレクトリは、管理パネルのサイドバーの **ナビゲーションメニュー** か、**Flex Objects** のプラグイン管理メニューか、もしくは他のプラグイン中の、いずれかに表示されます。

> [!Info]  
> カスタムディレクトリを利用するには、**Flex Objects** プラグインを有効化しなければいけません。

> [!訳注]  
> ここはわかりにくいですが、Flex プラグインを有効化したうえで、各Flex ディレクトリを有効化しないといけない、ということが言いたいのだろうと思います。  
> Flex プラグインの有効化というのは、`user/config/plugins/flex-objects.yaml` ファイルの `enable: true # or false` のことを指し、 Flex ディレクトリの有効化というのは、同じ yaml ファイルの `directories: - 'blueprints: ...'` となっているリストに載っていることを指しています。  
> 管理パネルでの方法は、以下説明されます。

<h2 id="enabling-a-directory">Flex ディレクトリを有効化</h2>

カスタムの **Flex ディレクトリ** を有効化するには、管理パネルで、サイドバーの **Plugins** > **Flex Objects** とページ遷移してください。

プラグイン内の **Directories** オプションには、探索されたすべての **Flex ディレクトリ** がリストになっています。有効化したいディレクトリを選択し、 `Enabled` になっているか確認してください。

**Save** ボタンをクリックすると、ページをロード後、ディレクトリが表示されます。

> [!Note]  
> **TIP:** ディレクトリを表示するページの作り方は、 **[はじめに](./01.introduction/)** を、ひととおりチェックしてください。

<h2 id="directory-listing">Flex ディレクトリを一覧にする</h2>

![Directories View](./01.views-list/flex-objects-list.png)

デフォルトでは、 **Flex オブジェクト** のナビゲーションメニューアイテムは、サイトで有効化されている **Flex ディレクトリ** すべてです。

> [!Info]  
> Some Flex Directories choose to hide from this list and show up elsewhere. **Accounts** and **Pages** are good examples of this.

#### Controls

Along the top of the page, you will find the administrative controls.

- **Back**: Go back to **[Dashboard](/admin-panel/dashboard)**
- **Configure**: Redirects to **Plugins** > **Flex Objects**, see [Enabling a Directory](#enabling-a-directory)

#### Directories

When you select a Directory, you will end up at the **Content Listing** view.

In **[Content Listing](/advanced/flex/administration/views-list)** you can browse through the objects, use **Search**, and change **Ordering**. Additionally each object has **Actions**, notably **Edit** and **Delete**. You can also add new objects by using the **Add** button at top of any page. Next to it is also the **Configuration** button to change directory-wide settings.

In **[Content Editor](/advanced/flex/administration/views-edit)** you can edit the object and **Save** it.

In **[Configuration](/advanced/flex/administration/configuration)** you can change configuration, which changes the behavior of the whole directory. Usually caching is among these options.

