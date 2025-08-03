---
title: Twig入門
layout: ../../../layouts/Default.astro
lastmod: '2025-08-03'
description: 'Grav で利用される Twig の基本を学びましょう。基本的な構文や、タグ、フィルタ、関数などについて概要を解説します。'
---

Twig とは、高速で、最適化された、 PHP 用のテンプレートエンジンです。  
開発者にも、デザイナーにも、簡単にテンプレートが作れるように設計されています。

学びやすい構文と、素直な処理により、 Smarty (PHP) や、Django (Python) 、Jinja、Liquid (Ruby) 、Stencil (Java) などに親しんでいる方なら誰にでも合います。

Grav のテンプレートとして Twig を採用する理由のひとつとして、柔軟性と、持ち前のセキュリティがあります。  
また、 PHP のテンプレートエンジンとしては最速のもののひとつなので、 Grav での採用に悩むことはありませんでした。

Twig は、テンプレートをプレーンな PHP にコンパイルします。  
これにより、 PHP のオーバーヘッドが最小限になり、より高速で合理的な開発体験が実現します。

同時に、_lexer_ （字句解析）と_parser_ （構文解析）のおかげで、柔軟性の高いエンジンにもなっています。  
開発者は、独自のカスタムタグや、カスタムフィルタを作れます。  
Twig の lexer と parser は、独自の [ドメイン固有言語（DSL）](https://ja.wikipedia.org/wiki/%E3%83%89%E3%83%A1%E3%82%A4%E3%83%B3%E5%9B%BA%E6%9C%89%E8%A8%80%E8%AA%9E) が作れます。

セキュリティに関しても、 Twig は手を抜きません。  
開発者向けにサンドボックスモードを提供し、信用できないコードを試すことができます。  
これにより、 Twig をアプリケーション用のテンプレート言語として使えるようになり、同時に、ユーザはテンプレートデザインを編集できるようになります。

基本的に、 Twig はユーザ・インターフェイスを制御するのに強力なエンジンです。  
設定用の YAML と組み合わせれば、あらゆる開発者やあらゆるサイト管理者にとって、強力でシンプルなシステムになります。

<h2 id="how-does-twig-work">Twig はどのように動くか？</h2>

Twig は、テンプレートデザインからすべての面倒を取り去ってくれます。  
テンプレートは基本的にテキストファイルであり、それが評価されるときに、ある値に変換される _variables_ （変数）や _expressions_ （式）を持ちます。

_Tags_ （タグ）は、テンプレートファイルにおいて重要な部分です。  
テンプレートそのもののロジックを制御します。

Twig には、2つの主要な言語制約があります。

* `{{ }}` 式を評価して、結果を表示する
* `{% %}` 文を実行する

以下が、 Twig を使った標準的なテンプレートです：

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

この例では、普通の web ページと同じように、サイトのタイトルを設定しています。  
違うところは、単純な Twig 構文を使って、著者の名前を表示し、動的にアイテムの一覧を作成しています。

テンプレートがまず読み込まれ、ソースコードでトークン化されているところを **lexer** が読み通し、小さいピースに分けます。  
この時点で、 **parser** はトークンを抽象構文木に変換します。

一度これが行われると、 PHP コードにコンパイルされます。  
その PHP コードは、評価され、ユーザーに表示できるものとなります。

Twig は拡張可能でもあります。  
タグや、フィルタ、テスト、オペレータ、グローバル変数、そして関数を追加できます。  
より詳しい情報は、 [公式ドキュメント](https://twig.symfony.com/doc/1.x/advanced.html) をご覧ください。

<h2 id="twig-syntax">Twig構文</h2>

Twig テンプレートには、いくつかの重要なコンポーネントがあり、それらによって、あなたがいったい何がしたいのかを Twig に理解させるのに役立ちます。  
それらは、タグ、フィルタ、関数、そして変数です。

これらの重要な道具について、すばらしいテンプレートづくりにいかに役立つかを、もっと詳しく見ていきましょう。

<h3 id="tags">タグ</h3>

タグは、 Twig に何をやるべきかを伝えます。  
どのコードを制御すべきで、どのコードを無視するべきかを指示できます。

いくつかの種類のタグがあります。  
それぞれは、特定の構文を持ちます。

<h4 id="comment-tags">コメントタグ</h4>

コメントタグ（`{# Insert Comment Here #}`）は、 Twig テンプレートファイルの中でのコメントになります。  
このコメントは、エンドユーザーには見えなくなります。  
PHP の処理中にこれらは取り除かれ、 parse されることも、出力されることもありません。

このタグの便利な使い方は、特定のコードやコマンドに関する説明文とすることです。  
そうすれば、同じチームの他の開発者やデザイナは、すぐにそれを読んで、理解してくれます。

以下が、 Twig テンプレートファイルの中で見ることになる、コメントタグの例です。

```twig
{# Chocolate Chip Cookies are great! Don't tell anyone! #}
```

<h4 id="output-tags">出力タグ</h4>

出力タグ (`{{ Insert Output Here }}`) は、評価され、出力を生成するために追加されます。  
フロントエンドや、その他の生成コンテンツに表示させたいものを、ここに置いてください。

以下が、 Twig テンプレート中の出力タグです。

```twig
My name is {{ name|e }} and I love cookies.
```

`name` という変数が、この行に挿入されます。  
`Jake` がこの変数の値だった場合、エンドユーザーには `My name is Jake and I love cookies` と表示されます。

> [!Info]  
> [システム設定](../../01.basics/05.grav-configuration/#twig) で、 `autoescape` を true にするのか、それともすべての変数ひとつひとつに対して `|e` フィルタを付けて、 [XSS攻撃](https://developer.mozilla.org/en-US/docs/Glossary/Cross-site_scripting) への対策のために忘れずにエスケープするのかは、とても重要な問題です。安全な HTML コンテンツにおいては、 `|raw` フィルタを使ってください。

<h4 id="action-tags">実行タグ</h4>

実行タグは、 Twig 界のやり手（go-getters）です。  
このタグは、実際に何かをやります。  
これまで説明してきたタグが、何かを伝えるだけだったり、デザイナに読んでもらうだけのものだったりするのとは対象的です。

実行タグは、変数を定義したり、配列をループさせたり、条件を分岐したりします。  
`for` や `if` のような文は、このタグで使われます。

Twig テンプレート中の実行タグは、以下のようなものです：

```twig
{% set hour = now | date("G") %}
{% if hour >= 9 and hour < 17 %}
    <p>Time for cookies!</p>
{% else %}
    <p>Time to bake more cookies!</p>
{% endif %}
```

最初の実行タグは、現在の時間を24時間表記で hour という変数にセットします。  
その値をもとに、 AM 9 時から PM 5 時の間かどうか判断します。  
もしそうなら、 `Time for cookies!` が表示されますし、違うなら、 `Time to bake more cookies!` が代わりに表示されます。

とても重要なことに、タグは、オーバーラップさせることはできません。  
実行タグの中に出力タグを入れたり、その逆にしたりすることはできません。

<h3 id="filters">フィルタ</h3>

フィルタは、適切な表示形式になっていないデータを、出力タグで使うときに、とても便利です。

たとえば、 `name` 変数に、 SGML/XML タグが混入しているかもしれません。  
以下のようなコードで、それらをフィルタできます。

```twig
{{ name|striptags|e }}
```

<h3 id="functions">関数</h3>

関数によって、コンテンツを生成できます。  
通常は引数を伴い、関数が呼び出された直後のカッコ内に引数を表します。  
引数が無かった場合でも、関数は直後に `()` カッコが必要です。

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

