---
title: コンテンツタイプ
layout: ../../../layouts/Default.astro
lastmod: '2025-04-16'
---
<h2 id="default-content-type">デフォルトのコンテンツタイプ</h2>

ほとんどのウェブプラットフォームに特徴的なことですが、Gravのデフォルトのコンテンツタイプは **HTML** です。これはつまり、ユーザーがブラウザでルーティングをリクエストしたとき、たとえば `/blog/new-macbook-pros-soon` にアクセスしようとしたとき、このリクエストには拡張子がないので、HTMLページを要求しているのだとGravは判断します。リクエストされたページが `blog-item.md` によっていた場合、Gravは`blog-item.html.twig` というTwigテンプレートを探して、ページをレンダリングします。

ユーザーが明示的に、`/blog/new-macbook-pros-soon.html` をリクエストしていたとしたら、Gravは同じく `blog-item.html.twig` ファイルを探したでしょう。

<h2 id="other-content-types">異なるコンテンツタイプ</h2>

しかしながら、Gravは柔軟なプラットフォームなので、（`xml`、`rss`、`json`、`pdf`などの）いかなるコンテンツタイプでも提供できます。それらが適切にレンダリングされる方法を提供してさえいれば。

たとえば、`.xml` 拡張子のリクエストを受け取った場合、たとえば：`/blog.xml` 通常の`blog.html.twig` テンプレートでレンダリングする代わりに、Gravは`blog.xml.twig` を探します。そのテンプレートが、適切なXML構造を出力できるようにしておく必要はあります。

<h3 id="example-with-json-files">JSONファイルの例</h3>

ファイルへの一般的なアクセス方法に、`.json` 拡張子によるものがあります。JSONファイルでリクエストされたデータは、JavaScriptでかんたんに処理できます。

特定のページの **フロントマター** と **コンテンツ** がJSONフォーマットで欲しいとします。そのページは、`item.md` により定義されていたとしましょう。やるべきことは、 `item.json.twig` というtwigテンプレートを用意することです。テーマフォルダ内の `templates/` フォルダか、カスタムテンプレートを読み込むプラグインを使っていればそのフォルダに、このテンプレートファイルを保存します。

この`item.json.twig` ファイルの内容は、次のようになります：

```twig
{% set payload = {frontmatter: page.header, content: page.content}  %}
{{ payload|json_encode|raw }}
```

このTwigファイルがやっているのは、ページのヘッダを **frontmatter** に、そしてコンテンツを **content** としている連想配列を作って、その後Twigの `json_encode` フィルタでエンコードしているだけです。

ユーザが`/blog/new-macbook-pros-soon.json` というURLにアクセスしたとき、この新しいTwigファイルが使われ、出力は次のようなフォーマットで送られます：

```json
{
   "frontmatter":{
      "title":"New Macbook Pros Arriving Soon",
      "date": "14:23 08/01/2016",
      "taxonomy":{
         "category":[
            "blog"
         ],
         "tag":[
            "apple",
            "mbpr",
            "laptops"
         ]
      }
   },
   "content":"<p>this has an -&gt; arrow here and <strong>bold</strong> here</p>\n<blockquote>\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ultricies tristique nulla et mattis. Phasellus id massa eget nisl congue blandit sit amet id ligula. Praesent et nulla eu augue tempus sagittis. Mauris faucibus nibh et nibh cursus in vestibulum sapien egestas. Curabitur ut lectus tortor. Sed ipsum eros, egestas ut eleifend non, elementum vitae eros.\n-- <cite> Ronald Wade</cite></p>\n</blockquote>\n<p>Mauris felis diam, pellentesque vel lacinia ac, dictum a nunc. Mauris mattis nunc sed mi sagittis et facilisis tortor volutpat. Etiam tincidunt urna mattis erat placerat placerat ac eu tellus.</p>\n<p>This is a new paragraph</p>\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ultricies tristique nulla et mattis.</p>"
}
```

これは適切なJSONであり、JavaScriptでかんたんにパースでき、処理できます。楽勝です！

<h2 id="custom-content-types">カスタム・コンテンツタイプ</h2>

適切なコンテンツタイプでデータ送信するため、ブラウザが期待するMIMEタイプをGravに知らせる必要があります。標準的なコンテンツタイプであれば、`system/config/media.yaml` ファイルに定義されています。ここで提供されていないコンテンツタイプを使いたい場合、ここに付け加えるだけで良いです。

たとえば、iCal カレンダーイベントをレンダリングしたいとき、`user/config/media.yaml` ファイルに以下のような追記をしてください：

```yaml
  ics:
    type: iCal
    thumb: media/thumb.png
    mime: text/calendar
```

ここでは、`iCal` ファイルに対して、`text/calendar` というmimeタイプとともに `.ics` ファイル拡張子を定義しています。その後必要なことは、このタイプに対して適切な`.ics.twig` テンプレートを提供することです。

