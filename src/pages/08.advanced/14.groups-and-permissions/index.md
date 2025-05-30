---
title: グループとパーミッション
layout: ../../../layouts/Default.astro
lastmod: '2025-05-28'
---

> [!Info]  
> ユーザー管理については、 [Grav Admin FAQ](../../05.admin-panel/09.faq/#adding-and-managing-users) をご覧ください。

<h2 id="defining-groups">グループの定義</h2>

デフォルトでは、Grav はグループを提供しません。これらを定義する必要があります。

グループは、 `user/config/groups.yaml` ファイルで定義されます。まだファイルが存在していなければ、作成してください。

以下は、ユーザーグループの定義例です：

```yaml
registered:
  icon: users
  readableName: 'Registered Users'
  description: 'The group of registered users'
  access:
    site:
      login: true
paid:
  readableName: 'Paid Members'
  description: 'The group of paid members'
  icon: money
  access:
    site:
      login: true
      paid: true
administrators:
  groupname: administrators
  readableName: Administrators
  description: 'The group of administrators'
  icon: child
  access:
    admin:
      login: true
    site:
      login: true
```

ここでは、3つのグループを定義しています。

<h2 id="assigning-a-user-to-a-group">ユーザーをグループに割り当てる</h2>

すべてのユーザーは、グループに割り当てられます。

簡単な追加方法：

```yaml
groups:
  - paid
```

上記を、 `user/accounts` 下のユーザーの yaml ファイルに追加してください。

複数のグループに追加することもできます：

```yaml
groups:
  - administrators
  - another-group
```

管理パネルプラグインから、ユーザーのグループ情報を編集することもできます。

<h2 id="permissions">パーミッション</h2>

グループに割り当てられたユーザーは、グループのパーミッションを継承します。たとえば、 `site.paid` パーミッションを持つグループを定義するには、次のように追加します：

```yaml
access:
  site:
    paid: true
```

上記を、 `user/config/groups.yaml` のグループ定義のところに追加してください。

ユーザーがグループに割り当てられるとき、 `site.paid: true` パーミッションが継承されます。

ユーザーが複数のグループに所属する場合、グループは、あるパーミッションを提供すればそれだけで、ユーザーにそのパーミッションが追加されます。

<h3 id="fine-tuning-permissions-on-a-user-level">ユーザーレベルでの権限の微調整</h3>

通常、パーミッションを、ユーザーレベルで微調整することもできます。グループによって、サイト全体のパーミッションを定義し、ユーザーレベルでそれを取り消せます。次のように：

```yaml
access:
  site:
    paid: false
```

ユーザーの yaml ファイルに追加します。

> [!Info]  
> 利用可能なパーミッションについて、より詳しくは、 [Grav 管理パネルの FAQ](../../05.admin-panel/09.faq/#managing-acl) をお読みください。

