---
title: "導入"
layout: ../../../../../layouts/Default.astro
---


このセクションでは、既存の **Flex ディレクトリ** をすぐに有効化し、 Grav 管理パネルに表示する方法について解説します。具体例としてデモ用に、 **Flex Objects プラグイン** に含まれる **Contacts** flex ディレクトリを使用します。

<h2 id="enabling-a-directory">ディレクトリの有効化</h2>

カスタムの **Flex ディレクトリ** を有効化するには、管理パネルのサイドバーから、 **Plugins** > **Flex Objects** へ移動してください。

ページの下の方に、 **Directories** 設定があります。この設定には、 Grav で検出されたすべての **Flex ディレクトリ** が一覧表示されます。

![Plugin Configuration](flex-objects-options.png)

有効化したいディレクトリを見つけて、 **Enabled** オプションをチェックします。

このデモでは、 **Contacts** （連絡先）ディレクトリを有効化し、ページ上部にある **Save** ボタンをクリックします。

ページのリロード後、 Grav の管理パネルメニューに **Contacts** という新しい項目が表示されているはずです。

<h2 id="install-sample-data-optional">サンプルデータのインストール（オプション）</h2>

今回の具体例のため、 **Contacts** flex ディレクトリ用のサンプルデータセットをコピーしたものとします。

```shell
$ cp user/plugins/flex-objects/data/flex-objects/contacts.json user/data/flex-objects/contacts.json
```

<h2 id="create-a-page">ページの作成</h2>

**[管理パネルのページ](../../../../05.admin-panel/03.page/)** へ移動して、 [新しいページを追加](../../../../05.admin-panel/03.page/#adding-new-pages) してください。以下の値を入力してください。

- **Page Title** : `Directory`
- **Page Template** : `Flex-objects`

その後、 **Continue** ボタンをクリックします。

**[コンテンツエディタの Advanced タブ](../02.views-edit/)** で、次のようにフロントマターに `flex.direcory` が `contacts` となっていることを確認してください：

```twig
---
title: Directory
flex:
  directory: contacts
---

# Directory Example
```

ページがこれで良かったら、 **Save** をクリックします。

> [!Note]  
> **TIP:** `Flex ディレクトリ` を指定しなかったとき、単一のディレクトリではなく、すべてのディレクトリからページが表示されます。

<h2 id="display-the-page">ページを表示</h2>

作成したページに移動してください。 **Contacts** を含む以下のようなページが表示されます。

![](flex-objects-site.png)

ディレクトリを選択しなかった場合、代わりに、次のように表示されます。

![](flex-objects-directory.png)

