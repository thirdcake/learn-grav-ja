---
title: Dokku
layout: ../../../../layouts/Default.astro
lastmod: '2025-07-17'
---

> [!訳注]  
> このページの内容は、最後のノートにもある通り、 Heroku のガイドをもとにして作成されており、一部の文章は Heroku の記述が混在していると思われます。しかし私の能力ではその部分の補完ができませんので、とりあえず元の文章通りに訳しています。 `Heroku` を `Dokku` に読み替えるだけで良いのか、それとも何か追加の手続きが必要なのかは、 [Dokku のドキュメント](https://github.com/dokku/dokku/) などを参考にしてください。

Dokku は、 Docker ベースで、セルフホスティングの "ミニ Heroku" です。そこでは、仮想マシン (VM) をローカルやリモートで実行できます。これを利用する主なメリットは：

* Heroku のビルドパックや [Procfiles](https://devcenter.heroku.com/articles/procfile) や、その他のアーキテクチャエレメントが利用可能
* Docker compose-file が使える
* セルフホスティングである。よって VM のコストを抑えたり、ローカルならコストなしで実行できる
* デプロイ機構として Git を使える
* Let's Encrypt を利用した無料の SSL 認証が使える
* Dokku と 1つの VM で、複数の Grav サイトを実行できる

最初のステップは、新規の VM 内に、以下のオペレーティング・システムのディストリビューションのうちのひとつを動かすことです：

* Ubuntu x64 - Any currently supported release
* Debian 8.2+ x64
* CentOS 7 x64 (experimental)
* Arch Linux x64 (experimental)

最新の安定版リリースをインストールするため、セキュアシェル (SSH) で VM に接続し、以下のコマンドをルート権限 (sudo) で実行してください：

```bash
wget https://raw.githubusercontent.com/dokku/dokku/v0.17.9/bootstrap.sh
sudo DOKKU_TAG=v0.17.9 bash bootstrap.sh
```

インストールスクリプトが完了したら、ブラウザで VM の IP アドレスもしくはドメイン名に移動し、インストールを完了させます。スクリーンには以下のプロンプトが表示されます：

* 公開 SSH 鍵 - デプロイ用の認証トークンとして使われます（ GitHub や Gitlab と同じです）  
    _プロのテクニック： もし **Vultr** か **Digital Ocean** を利用していれば、ダッシュボードから VM に SSH 鍵を追加できます。あとは Dokku が自動でやってくれます_
* ホスト名 - VM のホスト名と同じでなければいけません
* アプリに仮想ホスト名を使うか、サブフォルダを使うかを選択する  
    _仮想ホスト名がおすすめですが、どちらでも使えます_

仮想ホスト名のパスにする場合、 **Cloudflare** のようなドメイン名サービス (DNS) プロバイダを経由して VM にドメインやサブドメインを追加し、それから、そのドメインやサブドメインにワイルドカードサブドメインを追加する必要があります。

シンプルにするなら、サブフォルダ構造を使用し、 VM の IP アドレスをホスト名として使用可能です。

インストールが完了したら、 VM のターミナルで、 Grav サイト用の新しいアプリを作成してください：

```bash
dokku apps:create my-grav-site
```

ここで、ローカルコンピュータに戻ります。

Heroku が提供している PHP "Getting Started" の例を、 Git を使ってローカルマシンの web ルートでクローンしてください。デプロイ前にローカルでサイトをテストできるようになります。

```bash
git clone https://github.com/heroku/php-getting-started.git your-folder
```

```bash
cd your-folder
```

以下のようにして、 Git remote を Dokku サーバに追加します:

```bash
git remote add dokku dokku@your-vm-hostname-or-ip:my-grav-site
```

そして、

```bash
git push dokku master
```

デプロイ後、以下のような出力が表示されるはずです（これは Rails アプリのものなので、実際は少し違うでしょう。しかし例としては機能します）：

```bash
Counting objects: 231, done.
Delta compression using up to 8 threads.
Compressing objects: 100% (162/162), done.
Writing objects: 100% (231/231), 36.96 KiB | 0 bytes/s, done.
Total 231 (delta 93), reused 147 (delta 53)
-----> Cleaning up...
-----> Building ruby-getting-started from herokuish...
-----> Adding BUILD_ENV to build environment...
-----> Ruby app detected
-----> Compiling Ruby/Rails
-----> Using Ruby version: ruby-2.2.1
-----> Installing dependencies using 1.9.7
       Running: bundle install --without development:test --path vendor/bundle --binstubs vendor/bundle/bin -j4 --deployment
       Fetching gem metadata from https://rubygems.org/...........
       Fetching version metadata from https://rubygems.org/...
       Fetching dependency metadata from https://rubygems.org/..
       Using rake 10.4.2

...

=====> Application deployed:
       http://ruby-getting-started.dokku.me
```

デプロイの出力の最後に、新しいアプリの URL が表示されます。そこを見てください。

ここで、サンプル PHP プロジェクトが見られます。すべてが設定され、準備が整いました。サンプルサイトの代わりに Grav を実行できます。

まず、現在のサイトフォルダから `web/` フォルダを削除してください。

そこに Grav サイトのファイルをコピーしてください。 `.htaccess` のような隠しファイルのコピーも忘れずにしてください。すでにあるファイルすべてを上書きします。

`Procfile` を開いてください。これは Heroku 特有のファイルです。次の行を変更します

```txt
web: vendor/bin/heroku-php-apache2 ./
```

ローカルでサイトが機能するか確認してください。 Heroku にアップロードする前に、エラーが何も出ないことを確認してください。

リポジトリにコミットします： `git add . ; git commit -am 'Added Grav'`

それから、 `composer.json` を編集します。 `scripts` セクションにデプロイ後のコマンドを追記します：

```json
"scripts": {
  "compile": [
    "bin/grav install",
    "bin/gpm install quark -y"
  ]
}
```

それから、リポジトリにコミットします：

```bash
git add . ; git commit -am 'Add post deploy bin/grav install'
```

そして、実行します

```bash
git push dokku master
```

これで、サイトが正常に動いているでしょう！

Dokku のファイルシステムは一時的なものなので、必要なプラグインやテーマはすべて、上記のように `composer.json` に追記し、サイトが Heroku に push されるたびにそれらがインストールされるようにし続ける必要があります。 Heroku のインスタンスを恒久的にすることもできますが、将来的なアプリのスケールアップの点で、良いアイディアとは言えません。たとえば、管理パネルプラグインとテーマが必要な場合、composer に次のように追記します：

```json
"scripts": {
  "compile": [
    "bin/grav install",
    "bin/gpm install admin -y",
    "bin/gpm install awesome-theme-name-here -y"
  ]
}
```

> [!Note]  
> Heroku ガイドの著者に感謝します。Dokku と Heroku の類似性により、このガイドをベースとして使うことができました。

