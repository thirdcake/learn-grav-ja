---
title: "エディタ（セキュリティ）"
layout: ../../../../layouts/Default.astro
---

```
![Security Tab > Page Access](page-security.png)
```

This section defines frontend access to the page.

| Option                                | Description |
| :-----                                | :----- |
| **Menu Visibility Requires Access**   | Set to Yes if page should be shown in menus only if user can access them. |
| **Page Access**                       | User with following access permissions can access the page. |

```
![Security Tab > Page Permissions](page-security.png)
```

This section defines administration access to the page.

Page specific CRUD ACL works by using user groups only. In addition, it has two special groups named `authors` and `defaults`, which give special access to page owners, and a default fallback to all logged in users.

| Option                        | Description |
| :-----                        | :----- |
| **Inherit Permissions**       | Inherit ACL from parent page. |
| **Page Authors**              | Members of Page Authors have owner level access to this page defined in special 'Authors' page group. |
| **Page Groups**               | Members of Page Groups have special access to this page. |

For more information how the CRUD permissions work, please check [Page Permissions](/admin-panel/page/permissions).

