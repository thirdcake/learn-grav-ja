---
title: 'ショートコードの作成'
lastmod: '2026-08-01T20:40:05+09:00'
description: '「Twig を先に書き、ショートコードを後に書く」手法の実践的な解説です。ページコンテンツから何度も呼ばれるロジックを切り出し、小さく安全なショートコードに移行させます。'
weight: 47
params:
    srcPath: /content/creating-a-shortcode
---

Grav 2.0 から、[ページコンテンツ内の Twig](../05.twig-in-content/) はサンドボックス化されたので、記事作成者が行う繰り返し処理や、動的なスニペットは、生の Twig ではなく、 **ショートコード** にすることを推奨します。
ショートコードとは、たとえば、 `[team folder="/team"]` のような短く読みやすいタグで、記事作成者があらゆるページに書くことができるものです。
ロジックは、すべてプラグインの PHP と、信頼できるテンプレートにあり、そのため、コンテンツのサンドボックス内では実行されません。

===

このページでは、コンテンツ内の Twig 部分を、 [Shortcode Core](https://github.com/getgrav/grav-plugin-shortcode-core) プラグインを使って、同等のショートコードに置き換える方法を見ていきます。
まずは、 **Shortcode Core** プラグインをインストールしてください：

```bash
bin/gpm install shortcode-core
```

## 問題： コンテンツ内の Twig{#the-problem-twig-in-content}

たとえば、 `/team` フォルダ以下の全員をページでリスト化したいとしましょう。
1.7 以前なら、コンテンツ内の Twig を有効化して、ページ本文に以下のように直接書いていたかもしれません：

```twig
{% set staff = page.collection({'items': {'@page.children': '/team'}}) %}
<div class="team">
{% for person in staff %}
  <div class="member">
    <h3>{{ person.title }}</h3>
    {{ person.summary|raw }}
  </div>
{% endfor %}
</div>
```

これは、コンテンツ内の Twig が有効化されていたときだけ機能し、そのページを編集できるすべてのひとにテンプレートロジックが公開され、リストを必要とするすべての記事作成者は、このスニペットを正しくコピーしなければなりません。
これはまさに、サンドボックスが防ぐことを意図しているような事態です。

## 解決策： `[team]` ショートコード{#the-solution-a-team-shortcode}

ショートコードでは、記事作成者は一行だけ、次のように書きます：

```txt
[team folder="/team" /]
```

ロジックは、小さなプラグインへ移されます。
以下が、その全体です。

### 1. プラグインブループリント{#1-plugin-blueprint}

`user/plugins/team-shortcode/blueprints.yaml` では、そのプラグインと、 Shortcode Core に依存することが宣言されます：

```yaml
name: Team Shortcode
slug: team-shortcode
type: plugin
version: 1.0.0
description: Adds a [team] shortcode that lists pages under a folder.
icon: users
author:
  name: Your Name
dependencies:
  - { name: grav, version: '>=2.0.0' }
  - { name: shortcode-core }
```

### 2. プラグインの class{#2-plugin-class}

`user/plugins/team-shortcode/team-shortcode.php` では、 `onShortcodeHandlers` イベントに登録し、プラグインの `shortcodes/` フォルダ内のすべてのショートコードを登録します。
同時に、プラグインの `templates/` フォルダーを Twig に追加するので、ショートコードは信頼できるテンプレートでレンダリングできます：

```php
<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;

class TeamShortcodePlugin extends Plugin
{
    public static function getSubscribedEvents(): array
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
        ];
    }

    public function onPluginsInitialized(): void
    {
        if ($this->isAdmin()) {
            return;
        }

        $this->enable([
            'onShortcodeHandlers' => ['onShortcodeHandlers', 0],
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
        ]);
    }

    public function onShortcodeHandlers(): void
    {
        $this->grav['shortcode']->registerAllShortcodes(__DIR__ . '/shortcodes');
    }

    public function onTwigTemplatePaths(): void
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }
}
```

### 3. ショートコード{#3-the-shortcode}

`user/plugins/team-shortcode/shortcodes/TeamShortcode.php` が機能します。
`folder` パラメターを読み取り、 PHP で collection を作成し、そして信頼できる Twig テンプレートにデータを渡します：

```php
<?php
namespace Grav\Plugin\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class TeamShortcode extends Shortcode
{
    public function init()
    {
        $this->shortcode->getHandlers()->add('team', function (ShortcodeInterface $sc) {
            $folder = $sc->getParameter('folder', '/');

            $root = $this->grav['pages']->find($folder);
            $members = $root ? $root->children()->published() : [];

            return $this->twig->processTemplate('shortcodes/team.html.twig', [
                'members' => $members,
            ]);
        });
    }
}
```

> [!Note]  
> この class 名（ `TeamShortcode` ） は、ファイル名と同じでなければいけません。ショートコードは、 Shortcode Core から提供される `Shortcode` class を extends し、 `$this->grav` 及び `$this->twig` 、 `$this->shortcode` 管理を使えるようにしてくれます。

### 4. テンプレート{#4-the-template}

`user/plugins/team-shortcode/templates/shortcodes/team.html.twig` には、マークアップを書きます。
これは、 **ディスク上の信頼できるテンプレートファイル** なので、サンドボックスされませんし、表示ロジックのための正しい置き場所です：

```twig
<div class="team">
{% for person in members %}
  <div class="member">
    <h3>{{ person.title }}</h3>
    {{ person.summary|raw }}
  </div>
{% endfor %}
</div>
```

以上が、マイグレーションの全体像です。
ページ本文は、読みやすい一行に戻り、 Twig はあなたのコントロール可能なファイルに生き続け、コンテンツ内の Twig は無効のままにできます。

## ショートコードの構造{#shortcode-anatomy}

ショートコードハンドラは、必要なすべての情報とともにショートコードオブジェクトを受け取ります：

* `$sc->getParameter('name', $default)` 属性を読み取る。たとえば、上記の `folder="/team"` のように
* `$sc->getContent()` paired （ペア）ショートコードに囲まれたテキストを返す。たとえば、 `[red]wrapped text[/red]` 。
* `$sc->getName()` ショートコードの名前を返す。

ショートコードには、2つの形があります：

* **Self-closing**, 次のような `[team folder="/team" /]`, それら自身が出力を生成するようなタグ。
* **Paired**, 次のような `[red]...[/red]`, コンテンツを囲む。ミニマルなペアの例：

```php
$this->shortcode->getHandlers()->add('red', function (ShortcodeInterface $sc) {
    return '<span style="color:red;">' . $sc->getContent() . '</span>';
});
```

デフォルトでは、ハンドラはマークダウン処理の **後に** 実行されます。
そのため、ショートコードには、マークダウンコンテンツを含められます。
もし、マークダウン処理の **前に** 実行が必要な場合（たとえば、コードブロックのパースを止めたい場合）には、 `getHandlers()` ではなく `getRawHandlers()` を登録してください。

## 次のステップ{#next-steps}

* [Shortcode Core の README](https://github.com/getgrav/grav-plugin-shortcode-core) ドキュメントには `bin/plugin shortcode-core display` CLI コマンドや、高度な生のハンドラについて書かれています。
* [Shortcode UI](https://github.com/getgrav/grav-plugin-shortcode-ui) プラグインは、リッチなマークアップを伴う Twig 駆動のショートコードについての良い参考になります。
* それでもなおページ内に直接 Twig が必要な場合は、 [コンテンツ内の Twig](../twig-in-content) ページの有効化方法や、サンドボックスのカスタマイズ方法をご覧ください。

