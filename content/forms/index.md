---
title: フォーム
lastmod: '2026-10-31T16:02:31+09:00'
description: この章では、管理パネルのフォームを拡張する方法や、ページコンテンツにカスタムフィールドを作成する方法について説明します。
weight: 60
layout: 'chapter'
params:
    icon: '#forms'
    srcPath: /forms
    chapter_number: 6
---

[公式ドキュメント](https://learn.getgrav.org/forms) （[md](https://learn.getgrav.org/forms.md)）

公式の [Form](https://getgrav.org/downloads/plugins#form) プラグインでできることをまとめているチャプターです。

フォームは、外部からの攻撃面になるため、十分に注意して作る必要があります。  
システムによって、その自由度とセキュリティのバランスの取り方が違います。

- Laravel は、form タグを直接書いて、中に `@csrf` などのディレクティブを埋め込みます。最も自由度が高く、その分開発者の考慮すべき事項も多いです
- Symfony の form コンポーネントは、オブジェクト操作でフォームを作成します。自由度は少し下がりますが、規約に沿った実装が可能です
- Grav の Form プラグインは、 yaml 設定でフォームを作成します。さらに自由度は低いですが、開発者は設定のみで実装が完結します

Grav はフレームワークではないので、CMS に必要なフォーム機能を設定だけで運用できるようにしているのだと思います。

また、 Form プラグインでは、2種類のフォーム入力欄を作成します。

1. Blurprints という管理パネル用設定ファイルに書いて、管理パネルでの入力欄に使うもの
1. ページフロントマターなどに書いて、フロントエンドの問い合わせフォームなどの入力欄に使うもの

このチャプターも、前半が Blueprints の説明、後半がフロントエンドのフォームの説明になっています。

## Blueprints

[公式ドキュメント](https://learn.getgrav.org/forms/blueprints) （[md](https://learn.getgrav.org/forms/blueprints.md)）

Grav における Blueprints について解説されています。

もともと、建築業界でブループリント（青焼き図面、青写真）といえば、多くの人に共有される設計図を指しました。  
転じて、プログラムを生成する設定のことをブループリントと呼ぶようです。

Grav の Blueprints は、主に管理パネルで使われる設定ファイルを指すことが多いです。

### リファレンス：Blueprint で使えるフォーム入力欄{#fields-available-1}

[公式ドキュメント](https://learn.getgrav.org/forms/blueprints/fields-available) （[md](https://learn.getgrav.org/forms/blueprints/fields-available.md)）

主に管理パネルで使えるフォーム入力欄の設定方法が解説されています。  
Flex Objects のフォーム設定でも使われます。

### 具体例：プラグインの Blueprint{#example-plugin-blueprint}

[公式ドキュメント](https://learn.getgrav.org/forms/blueprints/example-plugin-blueprint) （[md](https://learn.getgrav.org/forms/blueprints/example-plugin-blueprint.md)）

プラグインの Blueprint （ `user/plugins/plugin_name/blueprints.yaml` ）の書き方を解説しています。

### 具体例：プラグイン設定{#example-plugin-configuration}

[公式ドキュメント](https://learn.getgrav.org/forms/blueprints/example-plugin-configuration) （[md](https://learn.getgrav.org/forms/blueprints/example-plugin-configuration.md)）

プラグインの設定の上書きは、CLI でやるなら `user/plugins/plugin_name/plugin_name.yaml` ファイルを `user/config/plugins/plugin_name.yaml` にコピーして使います。  
Blueprint を適切に設定することで、同じことを CLI を使わず、管理パネルを使ってできます。

### 具体例：ページの Blueprints{#example-page-blueprint}

[公式ドキュメント](https://learn.getgrav.org/forms/blueprints/example-page-blueprint) （[md](https://learn.getgrav.org/forms/blueprints/example-page-blueprint.md)）

管理パネルのページ編集画面をカスタマイズしたい場合に、ページの Blueprint を使います。

たとえば、あるテンプレートで何かの変数を使っているとき、管理パネルの所定の場所をクリックするだけで、その変数を書き換えられたら便利です。

### 具体例：サイト設定の Blueprints{#example-config-blueprints}

[公式ドキュメント](https://learn.getgrav.org/forms/blueprints/example-config-blueprints) （[md](https://learn.getgrav.org/forms/blueprints/example-config-blueprints.md)）

サイト設定（ `user/config/site.yaml` ファイル）の変更を管理パネルからできるようにする Blueprint の解説です。

### ハウツー：ファイルアップロードの追加{#how-to-add-file-upload}

[公式ドキュメント](https://learn.getgrav.org/forms/blueprints/how-to-add-file-upload) （[md](https://learn.getgrav.org/forms/blueprints/how-to-add-file-upload.md)）

ファイルのアップロード欄の設定方法を解説しています。

### 高度な Blueprint の機能{#advanced-features}

[公式ドキュメント](https://learn.getgrav.org/forms/blueprints/advanced-features) （[md](https://learn.getgrav.org/forms/blueprints/advanced-features.md)）

Blueprint の高度なカスタマイズ方法が解説されています。  
しかし、 Admin2 以降は、 [JavaScript のカスタム要素による拡張もできるようになった](https://learn.getgrav.org/2/api/developer-guide) ので、昔ほど使われないのかもしれません。

## フロントエンドのフォーム{#forms}

[公式ドキュメント](https://learn.getgrav.org/forms/forms) （[md](https://learn.getgrav.org/forms/forms.md)）

ここから後半です。  
管理パネルとは関係なく、サイトのお問い合わせフォームなどを作るときに必要な知識が解説されます。

管理パネルの場合は、フォームを送信した結果、内部の設定ファイルなどが書き換わりますが、お問い合わせフォームの場合は、メールを送ったり、ログを残したりなど、別の処理が必要になります。

入力欄の作成と同時に、それらのプロセス（アクション）の設定方法も解説されます。

### リファレンス：フォームのオプション{#form-options}

[公式ドキュメント](https://learn.getgrav.org/forms/forms/form-options) （[md](https://learn.getgrav.org/forms/forms/form-options.md)）

フォームに書き込むべきオプション（設定）を解説しています。

### リファレンス：フォーム入力欄{#fields-available-2}

[公式ドキュメント](https://learn.getgrav.org/forms/forms/fields-available) （[md](https://learn.getgrav.org/forms/forms/fields-available.md)）

フロントエンドで利用できるフィールド（入力欄）のリファレンスです。  
上記の管理パネルで利用できるリファレンスと混同しないようにしてください。

なお、フロントエンドのフォームは、外部に露出するフォームなので、攻撃者への対策が必須です。  
[キャプチャ機能に関するブログ記事](https://getgrav.org/blog/grav-form-captcha-options) も合わせて読んでおくと良いと思います。

### 具体例：コンタクトフォーム{#example-form}

[公式ドキュメント](https://learn.getgrav.org/forms/forms/example-form) （[md](https://learn.getgrav.org/forms/forms/example-form.md)）

お問合せフォームの具体例が解説されています。

### リファレンス：フォームアクション{#reference-form-actions}

[公式ドキュメント](https://learn.getgrav.org/forms/forms/reference-form-actions) （[md](https://learn.getgrav.org/forms/forms/reference-form-actions.md)）

フォームが送信された場合に、どのような処理をするのかを設定できます。

### ハウツー：モジュラーページ内のフォーム{#how-to-forms-in-modular-pages}

[公式ドキュメント](https://learn.getgrav.org/forms/forms/how-to-forms-in-modular-pages) （[md](https://learn.getgrav.org/forms/forms/how-to-forms-in-modular-pages.md)）

モジュラーページにフォーム機能を載せて、複数のページでモジュラーを呼び出せば、効率的なフォーム運用ができるようになります。

### ハウツー：Ajax 送信{#how-to-ajax-submission}

[公式ドキュメント](https://learn.getgrav.org/forms/forms/how-to-ajax-submission) （[md](https://learn.getgrav.org/forms/forms/how-to-ajax-submission.md)）

ページ全体を更新せず、非同期で送信する際の注意点などがまとまっています。

