---
title: "Flex オブジェクト"
layout: ../../../layouts/Default.astro
---

**Flex オブジェクト** は、Grav 1.7 で登場した新しい概念です。あなたのサイトに、カスタムのデータタイプを、簡単に追加します。 **Flex オブジェクト** は、[**Flex Objects** プラグイン](https://github.com/trilbymedia/grav-plugin-flex-objects) により提供され、 [管理パネルプラグイン](../../05.admin-panel/) で必要になるもので、 [**Grav コア + 管理パネルプラグイン**](https://getgrav.org/downloads) パッケージに含まれています。

> [!Info]  
> このドキュメントの **Flex ディレクトリ** は、旧来の **Flex Directories プラグイン** とは全く関係ありません。実際、その古いプラグインは、 **Flex Objects プラグイン** により取って代わりました。

<h2 id="introduction">導入</h2>

**Flex** is a set of **Directories** of a given type. Grav has its own built-in types, such as **User Accounts** and **Pages**. Plugins and themes can also define their own types and register those with Grav.


#### Flex

**[Flex](./02.using/01.flex/)** is a container for **Flex Directories**.

This gives a single access point for all the data in the site, given that the data is inside a Flex Directory. This makes all the objects available to every page and plugin in your site.

! **TIP:** Even if Flex *User Accounts* or *Pages* are not enabled, you can still access Flex versions of them in both frontend and Admin Panel.

#### Flex Type

**Flex Type** is the blueprint for your **Flex Directory**.

It defines everything that is needed to display and modify the content: data structure, form fields, permissions, template files, even storage layer.

#### Flex Directory

**[Flex Directory](./02.using/02.directory/)** keeps a collection of **Flex Objects** of a single **Flex Type**.

Each Directory contains a **Collection** of **Objects** with optional support for **Indexes** to speed up queries to **Storage**.

#### Flex Collection

**[Flex Collection](./02.using/03.collection/)** is a structure that contains **Flex Objects**.

The collection usually contains only the objects which are needed to display the page or to perform the given action. It provides useful tools to further filter or manipulate the data as well as methods to render the whole collection.

#### Flex Object

**[Flex Object](./02.using/04.object/)** is a single instance of some **Flex Type**.

The object represents a single entity. The object gives access to its properties, including any associated data, such as **[Media](../../02.content/07.media/)**. Object also knows how to **Render** itself or which **Form** to use to edit its contents. Actions like creating, updating and deleting objects are supported by the object itself.

#### Flex Index

**Flex Index** is used to make fast queries to **Flex Directory**.

It contains meta-data for the **Flex Objects**, but not the objects themselves.

#### Flex Storage

**Flex Storage** is a storage layer for the **Flex Objects**.

It can be a single file, set of files in a single folder or set of folders. Flex also supports custom storages, such as database storages.

#### Flex Form

**Flex Form** integrates to **Form Plugin** and allows **Flex Object** to be created or edited.

Flex supports multiple views, which allow different parts of the object to be modified.

#### Flex Administration 

**[Flex Administration](../01.administration/)** is implemented by **Flex Objects Plugin**.

It adds a new section to **Admin Plugin** allowing site administrators to manage **Flex Objects**. Each **Flex Directory** comes with CRUD-type ACL, which can be used to restrict parts of Admin and actions within them to certain users.

## Current Limitations

There is still a lot of work to do. Here are the current limitations when considering use of Flex Objects:

* Multi-language support has only been implemented for **Pages**, also admin cannot be fully translated yet
* Frontend only has a basic routing; for your custom tasks, such as saving, you need your own implementation
* Bulk update features have not yet been implemented in Admin (in code, they are easy)
* Due to indexing limitations, it is not recommended to use Flex for objects that are constantly being updated
* Customizing your **Flex Type** requires a good coding knowledge and creating your own classes

