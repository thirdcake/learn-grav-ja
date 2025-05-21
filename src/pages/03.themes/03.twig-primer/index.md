---
title: Twig入門
layout: ../../../layouts/Default.astro
lastmod: '2025-04-17'
---
Twigは、高速で、最適化された、PHP用のテンプレートエンジンです。開発者にも、デザイナーにもかんたんにテンプレートが作れるように設計されています。

かんたんに学べる構文と、率直な処理により、Smarty(PHP)や、Django(Python)、Jinja、Liquid(Ruby)、Stencil(Java)などに親しんでいる方なら誰にでも合います。

GravのテンプレートとしてTwigを採用する理由のひとつとして、柔軟性と、持ち前のセキュリティがあります。また、PHPのテンプレートエンジンとしては最速のもののひとつなので、Gravでの採用に悩むことはありませんでした。

Twigは、テンプレートをプレーンなPHPにコンパイルします。これにより、PHPのオーバーヘッドが最小限になり、より高速で合理的な開発体験が実現します。

同時に、_lexer_ （字句解析）と_parser_ （構文解析）のおかげで、柔軟性の高いエンジンにもなっています。開発者は、独自のカスタムタグや、カスタムフィルタを作れます。Twigの lexer と parser は、独自の[ドメイン固有言語（DSL）](http://en.wikipedia.org/wiki/Domain-specific_language) が作れます。

セキュリティに関しては、Twigは手を抜きません。開発者にサンドボックスモードを提供し、信用できないコードを試すことができます。これにより、Twigをアプリケーション用のテンプレート言語として使えるようになり、同時に、ユーザはテンプレートデザインを編集できるようになります。

基本的に、Twigはユーザ・インターフェイスを制御するのに強力なエンジンです。設定用のYAMLと組み合わせれば、あらゆる開発者やあらゆるサイト管理者にとって、強力でシンプルなシステムになります。

<h2 id="how-does-twig-work">Twigはどのように動くか？</h2>

Twigは、テンプレートデザインからすべての面倒を取り去ってくれます。テンプレートは基本的にテキストファイルであり、それが評価されるときに、ある値に変換される _variables_ （変数）や _expressions_ （式）を持ちます。

_Tags_ （タグ）は、テンプレートファイルにおいて重要な部分です。テンプレートそのもののロジックを制御します。

Twigには、2つの主要な言語制約があります。

* `{{ }}` 式を評価して、結果を表示する
* `{% %}` 文を実行する

以下が、Twigを使った標準的なテンプレートです：

```html
<!DOCTYPE html>
<html>
    <head>
        <title>All About Cookies</title>
    </head>
    <body>
        My name is {{ name|e }} and I love cookies.
        My favorite flavors of cookies are:
        <ul>
        {% for cookie in cookies %}
            <li>{{ cookie.flavor|e }}</li>
		{% endfor %}
        </ul>
        <h1>Cookies are the best!</h1>
    </body>
</html>
```

この例では、普通のwebページと同じように、サイトのタイトルを設定しています。違うところは、単純なTwig構文を使って、著者の名前を表示し、動的にアイテムの一覧を作成しています。

A template is first loaded, then passed through the **lexer** where its source code is tokenized and broken up into small pieces. At this point, the **parser** takes the tokens and turns them into the abstract syntax tree.

一度これが行われると、PHPコードにコンパイルされます。そのPHPコードは、評価され、ユーザーに表示できるものとなります。

Twigは拡張可能でもあります。タグや、フィルタ、テスト、オペレータ、グローバル変数、そして関数を追加できます。より詳しい情報はi、[公式ドキュメント](https://twig.symfony.com/doc/1.x/advanced.html) をご覧ください。

<h2 id="twig-syntax">Twig構文</h2>

Twigテンプレートは、いくつかの重要なコンポーネントがあります。それらは、あなたが何をしたいのかを理解するのに役立ちます。それらは、タグ、フィルタ、関数、そして変数です。

これらの重要な道具について、すばらしいテンプレートづくりにいかに役立つかを、もっと詳しく見ていきましょう。

<h3 id="tags">タグ</h3>

タグは、Twigに何をやるべきかを伝えます。どのコードを制御すべきで、どのコードを無視するべきかを指示できます。

いくつかの種類のタグがあります。それぞれは、特定の構文を持ちます。

<h4 id="comment-tags">コメントタグ</h4>

コメントタグ（`{# Insert Comment Here #}`）は、Twigテンプレートファイルの中でのコメントになります。このコメントは、エンドユーザーには見えなくなります。PHPの処理中にこれらは取り除かれ、parseされることも、出力されることもありません。

このタグの便利な使い方は、特定のコードやコマンドに関する説明文とすることです。そうすれば、同じチームの他の開発者やデザイナは、すぐにそれを読んで、理解してくれます。

以下が、Twigテンプレートファイルの中で見ることになる、コメントタグの例です。

```twig
{# Chocolate Chip Cookies are great! Don't tell anyone! #}
```

<h4 id="output-tags">出力タグ</h4>

出力タグ (`{{ Insert Output Here }}`) は、評価され、出力を生成するために追加されます。フロントエンドや、その他の生成コンテンツに表示させたいものを、ここに置いてください。

以下が、Twigテンプレート中の出力タグです。

```twig
My name is {{ name|e }} and I love cookies.
```

`name` という変数が、この行に挿入されます。`Jake` がこの変数の値だった場合、エンドユーザーには `My name is Jake and I love cookies` と表示されます。

> [!Info]  
> [システム設定](../../01.basics/05.grav-configuration/#twig) で、`autoescape` をtrueにするのか、それともすべての変数ひとつひとつに対して `|e` フィルタを付けて、[XSS攻撃](https://developer.mozilla.org/en-US/docs/Glossary/Cross-site_scripting) への対策のために忘れずにエスケープするのかは、とても重要な問題です。安全なHTMLコンテンツにおいては、`|raw` フィルタを使ってください。

<h4 id="action-tags">実行タグ</h4>

実行タグは、Twig界のやり手（go-getters）です。このタグは、実際に何かをやります。これまで説明してきたタグが、何かを伝えるだけだったり、デザイナに読んでもらうだけのものだったりするのとは対象的でｓ．

実行タグは、変数を定義したり、配列をループさせたり、条件を分岐したりします。`for` や `if` のような文は、このタグで使われます。

Twigテンプレート中の実行タグは、以下のようなものです：

```twig
{% set hour = now | date("G") %}
{% if hour >= 9 and hour < 17 %}
    <p>Time for cookies!</p>
{% else %}
    <p>Time to bake more cookies!</p>
{% endif %}
```

最初の実行タグは、現在の時間を24時間表記で hourという変数にセットします。その値をもとに、AM9時からPM5時の間かどうか判断します。もしそうなら、`Time for cookies!` が表示されますし、違うなら、`Time to bake more cookies!` が代わりに表示されます。

とても重要なことに、タグは、オーバーラップさせることはできません。実行タグの中に出力タグを入れたり、その逆にしたりすることはできません。

<h3 id="filters">フィルタ</h3>

フィルタは、適切な表示形式になっていないデータを、出力タグで使うときに、とても便利です。

たとえば、`name` 変数に、SGML/XMLタグが混入しているかもしれません。以下のようなコードで、それらをフィルタできます。

```twig
{{ name|striptags|e }}
```

<h3 id="functions">関数</h3>

関数によって、コンテンツを生成できます。通常は引数を伴い、関数が呼び出された直後のカッコ内に引数を表します。引数が無かった場合でも、関数は直後に `()` カッコが必要です。

```twig
{% if date(cookie.created_at) < date('-2days') %}
    {# Eat it! #}
{% endif %}
```

<h2 id="resources">役立つ資料</h2>

* [Official Twig Documentation](https://twig.symfony.com/doc/1.x/)
* [Twig for Template Designers](https://twig.symfony.com/doc/1.x/templates.html)
* [Twig for Developers](https://twig.symfony.com/doc/1.x/api.html)
* [6 Minute Video Introduction to Twig](http://www.dev-metal.com/6min-video-introduction-twig-php-templating-engine/)
* [Introduction to Twig](http://www.slideshare.net/markstory/introduction-to-twig)
* [Twig: The Basics (free intro to paid course)](https://knpuniversity.com/screencast/twig/basics)

