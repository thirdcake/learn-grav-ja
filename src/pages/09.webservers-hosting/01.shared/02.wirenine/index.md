---
title: WireNine
layout: ../../../../layouts/Default.astro
lastmod: '2025-07-10'
---

[WireNine](https://my.wirenine.com/aff.php?aff=023) は処理が速く、モダンなホスティングプロバイダです。同社は、パフォーマンスに焦点を当て、 **100% SSD** ストレージと、 **Litespeed** ウェブサーバー、高速な **DDR4 ram** とともに最新の **Intel E5-based** プロセッサを利用しています。
これらの機能は、彼らのレンタルサーバーが Grav サイトの素晴らしい解決策となってくれることを保証しています。

![](wirenine.webp)

このガイドでは、 Grav で最適に動作するために、中間のレンタルサーバーアカウントを設定するために必要な知識を解説します。

<h2 id="picking-your-hosting-plan">ホスティングプランを決める</h2>

[WireNine](https://my.wirenine.com/aff.php?aff=023) には、3つのレンタルサーバープランがあります。最安は月額 $9 のベーシックプランから、月額 $18 のヘビーな通信量オプションのあるプランまであります。これらすべての設定は同じですが、私たちは中間の **Plus** プラン（月額 $14 ）をおすすめしています。なぜなら、 1 CPU 及び 1GB のメモリーが付き、妥当な提供プランだからです。

<h2 id="enabling-ssh">SSH を有効化</h2>

まず、 cPanel の **Security** セクションで、 **Toggle SSH Access** オプションを開かなければいけません。この SSH アクセスページで、 **SSH アクセスを有効化** するボタンをクリックしてください。

![](manage-ssh-keys.png)

この時、2つの選択肢があります。 **新しい鍵を生成** するか **鍵をインポート** するかです。公開・秘密鍵のペアをローカルコンピュータで作成し、 DSA パブリックキーをインポートするだけの方が簡単です。

> [!Info]  
> Windows ユーザーは、多くの便利な GNU と Mac や Linux プラットフォームで使える便利なツールを提供するため、まず [Cygwin](https://www.cygwin.com/) のインストールが必要です。パッケージ選択プロンプトでは、 SSH オプションに確実にチェックを入れてください。インストール後、 `Cygwin Terminal` を立ち上げてください。

ターミナルウインドウを立ち上げ、次のようにタイプしてください：

```bash
ssh-keygen -t dsa
```

この鍵の生成スクリプトは、いくつかの値を入力させるプロンプトを表示します。デフォルト値を許容できる場合は、 `[return]` キーを押すだけでも良いです。このスクリプトは、ホームディレクトリの `.ssh/` というフォルダに、 `id_dsa` （秘密鍵）と、 `id_dsa.pub` （公開鍵）を作成します。プライベートキーを与えたり、どこかにアップロードするようなことは **決してしないでください** 。してよいのは、 **公開鍵だけです** 。

> [!訳注]  
> キー生成に関しては、 [このページ](https://kaityo256.github.io/github/ssh/index.html) が参考になりました。特に、パスフレーズを聞かれるプロンプトでは、何か入力したほうが良い（デフォルトにしない方が良い）ようです。

鍵を生成できたら、 **SSH Access** ページの **Import SSH key** セクションで、 `Public Key` 入力欄に `id_dsa.pub` パブリックキーの中身を貼り付けできます：

![](ssh-public-key.png)

アップロード後、 SSH 鍵管理ページの **Public Keys** セクションで鍵のリストを確認してください。それから、 **Manage** をクリックする必要があります。そのキーが認証されたことが確認されます：

![](authorized-keys.png)

> [!Info]  
> WireNine は、デフォルトでは **Shell Access** を有効化していないようです。サポートチケットを開き、あなたのアカウントに対してシェルアクセスを有効化するようリクエストする必要があるでしょう。

これで、サーバーに SSH テストする準備ができました。

```bash
ssh wirenine_username@wirenine_servername -p2200
```

言うまでもなく、 `wirenine_username` には WireNine から提供されているユーザー名を、 `wirenine_servername` には WireNine から提供されているサーバー名を入力する必要があります。 `-p2200` は、WireNine が SSH を標準とは違うポート番号で実行しているため、必要なものです。

<h2 id="403-forbidden-errors">403 Forbidden エラー</h2>

It seems in some WireNine setups the default permissions on user created files are incorrect and will cause **403 Forbidden** errors due to security flags being triggered.  The issue is that the default **umask is incorrect** and files are created with `775` for folders and `664` for files.  These files need to be `755` and `644` respectively to work correctly.

This should be setup automatically but is not currently.  However, the fix is easy.  Just edit your `.bash_profile` file and add this line to the bottom of it.

```txt
umask 022
```

You will need to re-login to your terminal to get this change picked up.

## Configuring PHP & Caching

WireNine uses PHP **5.4** by default, but you do have the option to use the newer **5.5**, **5.6**, or **7.0** versions. Grav requires at least PHP 5.5.9 to operate.

WireNine provides a very full-featured **cPanel** control panel. This is directly accessible via the **My Accounts** tab.

The first thing to do is to change the default version of PHP your site runs with. So click the **Select PHP Version** link in the **Software** Section.

You will see a page that shows the current version of PHP.  Below is a dropdown that let's you pick alternative versions.  Choose **5.6** and click `Set as current` button.

![](php-settings.png)

You will first need to enable `mbstring` and `zip` extension.

WireNine is a rare bread in the world of hosting providers, in that they provide some sophisticated caching extensions for PHP.  To take advantage of these, enable the `apcu` caching extension, and also the Zend `opcache` extension.  Then, click `Save` at the bottom of these options.

To test that you have the **correct version of PHP**, **Zend OPcache**, and **APCu** running, you can create a temporary file: `public_html/info.php` and put this in the contents:

```php
<?php phpinfo();
```

Save the file and point your browser to this info.php file on your site, and you should be greeted with PHP information reflecting the version you selected earlier:

![](php-info1.webp)

You should also be able to scroll down and see **Zend OPcache** listed in the **zend engine** block, and an **APCu** section below it:

![](php-info2.png)

## Install and Test Grav

Using your new found SSH capabilities, let's SSH to your WireNine server (if you are not already there) and download the latest version of Grav, unzip it and test it out!

We will extract Grav into a `/grav` subfolder, but you could unzip directly into the root of your `~/public_html/` folder to ensure Grav is accessible directly.

```bash
cd ~/public_html
wget https://getgrav.org/download/core/grav/latest
unzip grav-v{{ grav_version }}.zip
```

You should now be able to point your browser to `http://mywirenineserver.com/grav` using the appropriate URL of course.

Because you have followed these instructions diligently, you will also be able to use the [Grav CLI](../../advanced/grav-cli) and [Grav GPM](../../advanced/grav-gpm) commands such as:

```bash
cd ~/public_html/grav
bin/grav clear-cache

Clearing cache

Cleared:  cache/twig/*
Cleared:  cache/doctrine/*
Cleared:  cache/compiled/*
Cleared:  cache/validated-*
Cleared:  images/*
Cleared:  assets/*

Touched: /home/your_user/public_html/grav/user/config/system.yaml
```

## Alternate Install Method: Softaculous

Selecting this category will take you to a page where you can find the Grav CMS.

![](soft_1.png)

Available in Cpanel, Softaculous is a quick-and-easy installation method for Grav. You will find it at the bottom of your Cpanel dashboard in the **Portals/CMS** category. Once you have selected that category, you can scroll down and find the Grav entry.

Selecting the download icon will take you to the product page for Grav.

![](soft_2.png)

At this point, you can select the **Download** icon to progress to the main product page for Grav. This page includes additional information, as well as the link where you can install Grav directly to your server.

Selecting the download icon will take you to the installation page for Grav.

![](soft_3.png)

Once there, you can select the blue **Install** button in the upper-left area of the page to begin the installation process. This will take you to a configuration page enabling you to set up your Grav install, including the directory you wish to install it to, and an Admin account so you can hit the ground running in the Grav Admin.

![](soft_4.png)

Once you have configured this page as you would like, you're good to go!

