---
title: "Blog メタデータ"
layout: ../../../../layouts/Default.astro
---

Grav をブログのプラットフォームとして使用する場合、Facebook や Twitter などのソーシャルメディアで投稿をシェアするときの説明や画像を入力するのに役立つメタデータを含めたいと思うでしょう。

以下の情報を、Grav の[フロントマター](../../../02.content/02.headers/) に追記してください。

[Meta Page Headers](../../../02.content/02.headers/#meta-page-headers) 以下のドキュメント内に、フロントマターに書く必要のあるメタデータのリファレンスがあります。しかし、WordPressのようなプラグインを使用してメタデータを追加するプラットフォームから移行した場合、メタデータの重要性に気づいていないかもしれません。

ブログ投稿それぞれを、以下のように始めてください：

```yaml
---
title: Blog Post Title
publish_date: Date the blog post will go live
date: Date the blog post was written
metadata:
    'og:title': Blog Post Title
    'og:type': article
    'og:description': Description of what your blog post is covering.  This will be visible when people share your post on social media.
    'og:url': The URL of the blog post
    'og:site_name': The name of the overall site the blog post belongs to. 
    'og:locale': The language your blog post is written in
    'og:image': The image you reference here will be visible when shared on social media. 
    'twitter:card' : The type of Twitter card that should be used. 
    'twitter:site' : Your Twitter handle
    'twitter:title' : Blog Post Title
    'twitter:description' : Description of what your blog post is covering.  This will be visible when people share your post on social media.
    'twitter:image' : The image you reference here will be visible when shared on social media. 
    'twitter:creator': The twitter handle of the blog post author. 
taxonomy:
    category: [Blog post category]
    tag: [Tag 1, Tag 2, Tag 3, Tag 4]
    author: Author's name
---
```

[Twitter カード](https://developer.twitter.com/en/docs/tweets/optimize-with-cards/guides/getting-started.html) について詳しい内容は、こちらを読んでください。

[Open Graph プロトコル](https://ogp.me/) について詳しい内容は、こちらを読んでください。
