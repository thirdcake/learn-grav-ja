---
title: Heroku
layout: ../../../../layouts/Default.astro
lastmod: '2025-07-16'
---

Heroku は、 web アプリケーションのための、大変よく知られたホスティングサービスです。
テスト目的の便利なフリープランがあり、ウェブサイトをデプロイする有料オプションがあります。

幅広い種類のアドオンが提供されており、最も柔軟な PaaS のひとつです。

Heroku は、 PHP に親切です。素晴らしい "Heroku で PHP を開始するための" ガイドが [https://devcenter.heroku.com/articles/getting-started-with-php#introduction](https://devcenter.heroku.com/articles/getting-started-with-php#introduction) にあります。これは基本的な解説です。

Heroku に Grav をインストールする方法を見ていきましょう。

まず、 Heroku に登録します。

[Heroku CLI](https://devcenter.heroku.com/articles/heroku-cli) をダウンロードしてください。これは、作成物やサイトをデプロイするのに必要なコマンドラインのユーティリティです。

インストールできたら、次を入力します：

```bash
heroku login
```

クレデンシャルを入力してください。

それでは、 PHP の "入門編" として提供されている具体例をローカルの web ルートディレクトリでチェックしてください。デプロイ前にローカルでテストできます。

```bash
git clone https://github.com/heroku/php-getting-started.git your-folder
```

```bash
cd your-folder
```

デプロイは、次のようにします

```bash
heroku create
```

そして、

```bash
git push heroku master
```

最低でも1つ以上のインスタンスが実行されていることを確認してください：

```bash
heroku ps:scale web=1
```

そしてブラウザでサイトを開いてください：

```bash
heroku open
```

サンプルの PHP プロジェクトが表示されるはずです。すべては設定されているので、サンプルサイトの代わりに Grav を実行する準備ができています。

まず、現在のサイトフォルダで `web/` フォルダを削除してください。

Grav サイトのファイルをここにコピーします。 `.htaccess` 隠しファイルもコピーするのを忘れないでください。存在するすべてのファイルを上書きしてください。

`Profile` ファイルを開きます。これは Heroku 特有のファイルです。次の行を変更します

```txt
web: vendor/bin/heroku-php-apache2 ./
```

ローカルでサイトが機能するか確認してください。 Heroku へアップロード前に、エラーが出てないかだけ確認します。

それから、リポジトリをコミットします

```bash
git add . ; git commit -am 'Added Grav'
```

それから、 `composer.json` を編集し `scripts` セクションを次のようにデプロイコマンドを追記します。、

```json
"scripts": {
  "compile": [
    "bin/grav install",
    "bin/gpm install quark -y"
  ]
}
```

そして、リポジトリをコミットします

```bash
git add . ; git commit -am 'Add post deploy bin/grav install'
```

それから、実行します

```bash
git push heroku master
```

そうすれば、サイトがうまく動きます！

Heroku のファイルシステムはもともと一時的なものなので、必要なプラグインやテーマすべてについて、上記のように `composer.json` に追記し、サイトが Heroku へ push されるたびにそれらがインストールされるようにし続ける必要があります。たとえば、 `admin` プラグインとテーマが必要な場合、 composer に次のように追記します

```json
"scripts": {
  "compile": [
    "php bin/grav install",
    "php bin/gpm install admin -y",
    "php bin/gpm install awesome-theme-name-here -y"
  ]
}
```

