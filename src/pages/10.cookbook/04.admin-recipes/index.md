---
title: "管理パネルレシピ"
layout: ../../../layouts/Default.astro
---

このページでは、 Grav 管理パネルの修正に関連するさまざまな問題とその解決策を紹介します。

<h2 id="add-a-custom-yaml-file">カスタム YAML ファイルを追加する</h2>

<h4 id="problem">問題：</h4>

`system.yaml` や `site.yaml` のようなサイト全体でユーザーが編集できる company フィールドグループを、専用のファイルで提供したい。

<h4 id="solution">解決策：</h4>

[Basics / Configuration](../../01.basics/05.grav-configuration/#other-configuration-settings-and-files) セクションで概要を説明したとおり、最初のステップは、新しい YAML データファイルを提供することです。たとえば： `user/config/details.yaml` ：

```yaml
name: 'ABC Company Limited'
address: '8732 North Cumbria Street, Golden, CO, 80401'
email:
  general: 'hello@abc-company.com'
  support: 'support@abc-company.com'
  sales: 'sales@abc-company.com'
phone:
  default: '555-123-1111'
```

次に、フォームを定義する適切なブループリントファイルを提供する必要があります。
ブループリントは、プラグインから提供されますが、最もシンプルなアプローチは、単純に、次の場所にブループリントを書くことです：`user/blueprints/config/details.yaml`

プラグインからブループリントを提供したい場合は、まず、プラグインの class 定義の直後に、このコードを追加を追記する必要があります：

```twig
class MyPlugin extends Plugin
{
    public $features = [
        'blueprints' => 1000,
    ];
    protected $version;
    ...
```

それから、このコードを `onPluginsInitialized()` に追加します：

```twig
if ($this->isAdmin()) {
    // Store this version and prefer newer method
    if (method_exists($this, 'getBlueprint')) {
        $this->version = $this->getBlueprint()->version;
    } else {
        $this->version = $this->grav['plugins']->get('admin')->blueprints()->version;
    }
}
```

それから、 `user/plugins/myplugin/blueprints/config/details.yaml` というファイルを作成します。

実際のブループリントファイルは、config 設定データに適合するフォーム定義が書かれているべきです：

```yaml
title: Company Details
form:
    validation: loose
    fields:

        content:
            type: section
            title: 'Details'
            underline: true
        name:
            type: text
            label: 'Company Name'
            size: medium
            placeholder: 'ACME Corp'

        address:
            type: textarea
            label: 'Address'
            placeholder: '555 Somestreet,\r\nNewville, TX, 77777'
            size: medium

        email:
            type: array
            label: 'Email Addresses'
            placeholder_key: Key
            placeholder_value: Email Address

        phone:
            type: array
            label: 'Phone Numbers'
            placeholder_key: Key
            placeholder_value: Phone Number
```

`array` フィールドタイプを使うことで、必要なぶんだけ任意の email フィールドや phone フィールドが追加できます。

<h2 id="add-a-custom-page-creation-model">カスタムのページ作成モーダルを追加する</h2>

<h4 id="problem-1">問題：</h4>

新しいブログ投稿やギャラリー画像ページを作成する簡単な方法を提供したい。
この例では、ブログ投稿について説明します。
ブログを作成し、ブログ投稿を現在のフォルダで、ボタンクリックだけで簡単に作成したいとします。

<h4 id="solution-1">解決策：</h4>

まずはじめに、モーダルで使うフォームを作成します。新しいファイルを作成してください： `user/blueprints/admin/pages/new_post.yaml`

```twig
form:
  validation: loose
  fields:
    section:
        type: section
        title: Add Post

    title:
      type: text
      label: Post Title
      validate:
        required: true

    folder:
      type: hidden
      default: '@slugify-title'

    route:
      type: hidden
      default: /posts

    name:
      type: hidden
      default: 'post'

    visible:
      type: hidden
      default: ''

    blueprint:
      type: blueprint
```

このフォームは、デフォルトの `Add Page` モーダルのフォームを真似しています。 **folder** フィールドには、見てのとおり特別な値： `@slugify-title` が設定されています。これは、 **folder** には、 **title** フォームに入力された値のスラッグ化されたテキストがデフォルトで入力されることを意味します。
**route** が `/posts` なので、これは `/posts` フォルダ内に置かれます。

**name** は `post` なので、`post` のページブループリントを使います。

次のステップでは、管理パネルプラグインの設定を編集します。
管理パネルプラグインの `admin.yaml` 設定ファイルにカスタムコードを追加するために、 `user/config/plugins/admin.yaml` ファイルを作成し、以下のスニペットを追加してください：

```twig
add_modals:
  -
    label: Add Post
    blueprint: admin/pages/new_post
    show_in: bar
```

`add_modals` には、キー/バリュー形式の設定が可能です：

- `label` - ボタンに表示されるテキスト
- `show_in` (default: bar) (values: bar|dropdown) - ボタンの表示を **bar** にするか **dropdown** にするか
- `blueprint` - テンプレートによって使われるブループリント
- `template` - モーダルによって使われるテンプレート（デフォルト： partials/blueprints-new.html.twig）
- `with` - テンプレートに渡されるデータ
- `link_classes` - link 要素に追加される class
- `modal_classes` - modal 要素に追加される class

<h2 id="add-a-custom-select-field">カスタムの select フィールドを追加する</h2>

<h4 id="problem-2">問題：</h4>

値の多いリストで select フィールドを追加したい。
この例では、国のリストを表示したいものとします。

<h4 id="solution-2">解決策：</h4>

静的な関数を作り、ブループリント内から配列を呼び出すことができます。この関数は、テーマの PHP ファイルにも、カスタムプラグインの PHP ファイルにも書くことができます。

この例では、Antimatter テーマに関数を追加します。よって、 `user/themes/antimatter` フォルダの `antimatter.php` ファイルを編集します。

```php
<?php
namespace Grav\Theme;

use Grav\Common\Theme;

class Antimatter extends Theme
{
    public static function countryCodes()
    {
        return array (
            'AF' => 'Afghanistan',
            'AX' => 'Åland Islands',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua & Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AC' => 'Ascension Island',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
        );
    }
}
```

> [!Note]  
> 上記は、見やすくするために省略されたリストですが、 [umpirsky/count-list](https://github.com/umpirsky/country-list/blob/master/data/en_US/country.php) から、全ての国のリストをコピー/ペーストできます。

次に、この関数をブループリントやフロントエンドのフォーム定義から呼び出します。このように：

```yaml
country:
  type: select
  label: Country
  data-options@: '\Grav\Theme\Antimatter::countryCodes'
```

以下は、管理パネルでの見た目です。

![](countrylist.png)

