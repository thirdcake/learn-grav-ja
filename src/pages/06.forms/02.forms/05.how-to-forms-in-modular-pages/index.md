---
title: ハウツー：モジュラーページ中のフォーム
layout: ../../../../layouts/Default.astro
lastmod: '2025-04-30'
---
<h2 id="using-forms-in-modular-pages">モジュラーページでのフォームの使用</h2>

テーマに `templates/forms/form.html.twig` ファイルが提供されていないときは、フォームが利用できるようにセットアップされていませんが、心配無用です。 - Grav デフォルトテーマの Antimatter からテンプレートをコピーするだけです：

- `templates/form.html.twig`
- `templates/formdata.html.twig`

次に、 `form.md` というページタイプを持つモジュラーフォルダを作ります。

例えば： `01.your-modular-page/_contact/form.md`

この `form.md` ページは、フォームの定義を含みません。これは、フォームを出力する部分であることを示すだけのものです。

重要：次のように設定してください

```yaml
---
cache_enable: false
---
```

モジュラーページの仕組み上、ページのフロントマターヘッダーに、この設定を忘れると、12時間ごとに生成される nonce と一緒に、フォームがキャッシュされます。12時間が経過すると、キャッシュが更新されるまで、フォームは機能を停止します。この設定は、独立したページのフォームでは不要な設定です。

次に、メインのモジュラーページに、フォームヘッダーを追加します、 `modular.md` 

modular.md ページには、 "フルページの" form.md ファイルに書くのと同じように、フォーム定義の全体（fields などを含む）を書いてください。 `form.action` フィールドとして、独自のページ path を使用します。

> [!Tip]  
> Form v2.0 では、他のフォームと同様に、モジュラー サブページ内で直接フォームを定義できるようになりました。ただし、フォームが見つからない場合、フォームプラグインは、 '現在のページ' （例えば、フォームのトップレベルのモジュラーページ）を探します。よって、 1.0 の実装方法と完全に下位互換性があります。

例えば：

```yaml
---
content:
    items: '@self.modular'

form:
    action: /your-modular-page
    name: my-nice-form
    fields:
        -
            name: name
            label: Name
            placeholder: 'Enter your name'
            autofocus: 'on'
            autocomplete: 'on'
            type: text
            default: test

    buttons:
        -
            type: submit
            value: Submit

    process:
        -
            message: 'Thank you for your feedback!'
---

```

フォームヘッダーで、 `action` パラメータに、モジュラーページのルーティングを忘れずに追加してください。

上記例のように。
このステップが必要なのは、明示的に `form.action` を追加しないと、コードは、通常ページのルーティングを検索しますが、フォームは実際のページではなく、モジュラーのサブページにあるため、 path が間違えており、フォーム送信が失敗するためです。

よって、モジュラーページが、たとえば `site.com/my-page` にあるときは、 `modular.md` ファイルに `form: action: /my-page` を追記してください。
たとえモジュラーページがホームページであっても、ページルーティングを使ってください。たとえば： `form: action: /home`

<h4 id="a-live-example">実行できる具体例</h4>

Deliver スケルトンには、子のチュートリアルを読みながら確認できるモジュラーフォームページが用意されています。

[Live page](http://demo.getgrav.org/deliver-skeleton/contact)

[Page markdown file](https://github.com/getgrav/grav-skeleton-deliver-site/blob/develop/pages/07.contact/modular_alt.md)

<h4 id="troubleshooting-forms-in-modular-pages">モジュラーページのフォームのトラブルシューティング</h4>

フォームのトラブルシューティングする最善の方法は、まず根本に戻って、カスタマイズを1つずつ追加し、何が問題になっているのかを確認することです。

- "通常のフォーム" を作り、それが機能することを確認してから、それをモジュラーフォームに組み込んでみることをおすすめします。
- 必要なファイルがすべて提供されている Antimatter ベースのスケルトンでフォームを動作させてみましょう。
- フォームフィールドが表示されない場合、Assets プラグインがインストールされているなら、それを無効化 / アンインストールしてください。Assets プラグインがモジュラーフォームを動作させなくなるという問題が知られていますが、まもなく修正される予定です。

