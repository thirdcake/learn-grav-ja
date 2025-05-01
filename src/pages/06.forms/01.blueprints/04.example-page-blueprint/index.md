---
title: "具体例：ページのブループリント"
layout: ../../../../layouts/Default.astro
---

**ページのブループリント** は、デフォルトのページを拡張し、オプションを追加できる機能を提供します。基本的に、カスタムページは、ページのブループリントを使うことで成立します。ページのブループリントによって、管理パネル上のページの編集画面を 100% 設定できます。

> [!訳注]  
> このドキュメント中に明示的に書かれていませんが、ページのフォームタイプは、基本的にページのファイル名と一致する YAML ファイルのようです。（よって、おのずとテーマのテンプレート名にも一致するはずです）。たとえば、 `/user/pages/01.home/default.md` のページに対するページのブループリントは、 `/user/blueprints/pages/deafult.yaml` もしくは、 `/user/themes/あなたのテーマ/blueprints/default.yaml` であることが多いはずです。

<h3 id="a-first-example">最初の例</h3>

デフォルトのページフォームをベースに、たとえばいくつかの select ボックスを追加するだけの場合、デフォルトのページから拡張できます。

以下のようにすることで、デフォルトのページフォームが使用された上で、 **Overrides** セクションの中に、 **Advanced** タブのテキストフィールドを追加します。

```yaml
title: Gallery
extends@:
    type: default
    context: blueprints://pages

form:
  fields:
    tabs:
      type: tabs
      active: 1

      fields:
        advanced:
          fields:
            overrides:
              fields:
                header.an_example_text_field:
                  type: text
                  label: Add a number
                  default: 5
                  validate:
                    required: true
                    type: int
```

以下の例では、いくつかのフィールドを含む、 **Grallery** と呼ばれる新しいタブを追加します。

```yaml
title: Gallery
'@extends':
    type: default
    context: blueprints://pages

form:
  fields:
    tabs:
      type: tabs
      active: 1

      fields:
        gallery:
          type: tab
          title: Gallery

          fields:
            header.an_example_text_field:
              type: text
              label: Add a number
              default: 5
              validate:
                required: true
                type: int

            header.an_example_select_box:
              type: select
              label: Select one of the following
              default: one
              options:
                one: One
                two: Two
                three: Three
```

追加できるフィールドタイプは、[管理パネルで塩生可能なフォームフィールド](../01.fields-available/) に一覧表示されています。

<h3 id="how-to-name-fields">フィールドの命名法</h3>

フィールドが `header.*` という構造を使うことは重要です。これにより、ページの保存時に、フィールドのコンテンツは **ページヘッダー** に保存されます。

<h3 id="create-a-completely-custom-page-form">完全にカスタムされたページのフォームを作成する</h3>

デフォルトのフォームを拡張せずに、完全に独自のページフォームを作成することができます。

具体例：

```yaml
title: Gallery

form:
  fields:
    tabs:
      type: tabs
      active: 1

      fields:
        gallery:
          type: tab
          title: Gallery

          fields:
            header.an_example_text_field:
              type: text
              label: Add a number
              default: 5
              validate:
                required: true
                type: int

            header.an_example_select_box:
              type: select
              label: Select one of the following
              default: one
              options:
                one: One
                two: Two
                three: Three

            route:
              type: parents
              label: PLUGIN_ADMIN.PARENT
              classes: fancy

```

> [!Info]  
> **WARNING:** `route` フィールドは、Grav 1.7 で変更されました。既存のブループリントを、新しい `type: parents` に更新してください。

<h3 id="a-note-for-expert-mode">エキスパートモードに関する注意</h3>

ページを **エキスパート** モードで編集する場合、 **ブループリント** は読み込まれず、ページの編集フォームは全てのページで同じになります。このようになっている理由は、エキスパートモードでは、 **フロントマター** フィールドを直接編集するため、編集画面をカスタマイズする必要が無いからです。

<h3 id="where-to-put-the-page-blueprints">ページのブループリントを保存する場所</h3>

管理パネルプラグインが、ブループリントを取得して、新しいページタイプを表示するために、ブループリントを正しい場所に配置する必要があります。

<h4 id="in-the-user-blueprints-folder">ユーザーのブループリントフォルダ</h4>

`user/blueprints/pages/` 内に保存します。ブループリントをサイトにシンプルに反映させたいときは、ここに置くのが良いです。

<h4 id="in-the-theme">テーマ</h4>

`user/themes/YOURTHEME/blueprints/` 内に保存します。テーマを配布する予定がある場合、ここが最適です。テーマがページのブループリントを提供し、使いやすくなります。

<h4 id="in-the-data-folder">データフォルダ</h4>

Gantry5 ベースのテーマを利用している場合、`user/data/gantry5/themes/YOURTHEME/blueprints/` 内が、最適な場所です。そうでない場合、テーマのアップデート中に、ファイルが消えてしまうかもしれません。

<h4 id="in-a-plugin">プラグイン</h4>

`user/plugins/YOURPLUGIN/blueprints/` 内に保存します。これは、プラグインでカスタムページを定義して追加するようなときに保存する場所です。

次に、 `onGetPageBlueprints` イベントを登録し、Grav に追加します。以下の例は、 `blueprints/` フォルダからブループリントを追加します。
Then subscribe to the `onGetPageBlueprints` event and add them to Grav. The following example adds the blueprints from the `blueprints/` folder.

```php
public static function getSubscribedEvents()
{
  return [
    'onGetPageBlueprints' => ['onGetPageBlueprints', 0]

  ];
}

public function onGetPageBlueprints($event)
{
  $types = $event->types;
  $types->scanBlueprints('plugins://' . $this->name . '/blueprints');
}
```

