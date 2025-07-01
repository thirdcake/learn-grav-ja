---
title: 'Grav 1.6 未満からのアップデート'
layout: ../../../../layouts/Default.astro
lastmod: '2025-07-01'
---

> [!Tip]  
> このガイドは、 Grav v1.2.0 以上でテストされました。

> [!Warning]  
> Grav を直接最新バージョンにアップグレードすることは、機能しますが、完全にはサポートされず、サイトが壊れる原因になるかもしれません！

<h2 id="preparations">準備</h2>

Grav の古いバージョンをアップグレードする最も簡単な方法は、 **PHP 7.3** がインストールされていて、 CLI コマンドにアクセスする SSH ログインをサポートしている Linux/Unix サーバーにサイトをコピーすることです。
このガイドは、 Windows10 の Linux subsystem でも機能しますが、インストールしたことがなければ、アップデートパッケージを手動でダウンロードし、リネームしなければいけないかもしれません。

**PHP 7.3** が選ばれたのは、それが Grav のすべてのバージョンで利用可能なたった1つの PHP バージョンだからです。
PHP 7.1 や 7.2 では、 Grav v1.6.31 までしかアップグレードできません。その時点で、アップグレードの続きの作業をする前に、 PHP 7.3 や 7.4 にスイッチする必要があります。PHP 8 は、Grav 1.7 以上にアップグレードした後に使えます。

このガイドでは、 **Grav** コアのアップグレードの解説をしますが、一般的に使われているパッケージ（ **Problems**, **Error**, **Form**, **Email**, **Login** そして **Admin** ）についての解説も含んでいます。それ以外のプラグインについては、それらが今もメンテナンスされているか確認し、Grav 1.6 と最新バージョンをサポートしているか確認してください。
最も安全なプラグインは、 Grav 1.7.0 以降にリリースされたもの（2021年1月18日以降）もしくは、Grav 1.7 で機能することが確認されているものです。

**テーマ** と **カスタムプラグイン** や **メンテナンスされていないプラグイン** については、より多くの作業が必要です。すべてのカスタムコードについて、現在の Grav のバージョンと PHP でも機能するかどうかチェックしたほうが良いでしょう。 **マークダウン** や、 **YAML** 、 **Twig** ファイルについても、同じことが言えます。ライブラリの新しいバージョンでは、エラーをキャッチする修正が加えられており、通常、壊れたファイルをパースすると失敗します。Grav の新しいバージョンでは、これらのファイルをチェックするツールが提供されていますが、チェックはすべての問題をキャッチするわけではないので、追加のテストが必要になります。

チェックリスト（コンソールと Grav CLI を使う場合）：

* バックアップを取る
* `bin/gpm version grav` により Grav のバージョンを確認
* `php -v` により、PHP CLI バージョンをチェック。 **PHP 7.3** (>=7.3.6) が必要です
* PHP サーバーのバージョンをチェック。CLI と同じバージョンが必要です
* インストールされたプラグインをすべてリストにし、（サポートされている、カスタムされている、メンテナンスされていない）にカテゴリー分けする
* テーマについても同様に行う

<h2 id="step-to-grav-1-6-31">Grav 1.6.31 への手順</h2>

このパートでは、すでにサイトのコピーを作成済みであり、 CLI コマンドが機能することを前提としています。Windows ユーザーは、Linux subsystem をインストール済みでなければ、ファイルを手動でダウンロードし、リネームしてください。

> [!訳注]  
> WSL については、マイクロソフト社のドキュメントに設定方法などが載っています。WSL2 は、WSL1 の時代よりもセットアップが簡単になっています。

Grav サイトのルートフォルダで、以下のコマンドを実行してください（ `-y` パラメータは、 Grav 1.2 またはそれより古いものを利用している場合は GPM コマンドから省いてください）：

```bash
wget -q https://getgrav.org/download/core/grav-update/1.6.31 -O tmp/grav-update-v1.6.31.zip

bin/gpm direct-install -y tmp/grav-update-v1.6.31.zip
```

Grav も手動でアップデートできます。以下のフォルダを削除してください： `assets bin system vendor  webserver-configs` そして、 **すべての** ファイルを [ここで見つかる](https://getgrav.org/download/core/grav-update/1.6.31) Grav のアップデート zip ファイルからコピーまたは上書きしてください。
zip ファイル内のファイルは、 `grav-update` フォルダ内にあることに注意してください。

次に、ベースプラグインのアップデートが必要です。

```bash
wget -q https://getgrav.org/download/plugins/problems/2.0.3 -O tmp/grav-plugin-problems-v2.0.3.zip
wget -q https://getgrav.org/download/plugins/error/1.7.1 -O tmp/grav-plugin-error-v1.7.1.zip
wget -q https://getgrav.org/download/plugins/form/4.3.0 -O tmp/grav-plugin-form-v4.3.0.zip
wget -q https://getgrav.org/download/plugins/email/3.1.0 -O tmp/grav-plugin-email-v3.1.0.zip
wget -q https://getgrav.org/download/plugins/login/3.3.8 -O tmp/grav-plugin-login-v3.3.8.zip
wget -q https://getgrav.org/download/plugins/admin/1.9.19 -O tmp/grav-plugin-admin-v1.9.19.zip


bin/gpm direct-install -y tmp/grav-plugin-problems-v2.0.3.zip
bin/gpm direct-install -y tmp/grav-plugin-error-v1.7.1.zip
bin/gpm direct-install -y tmp/grav-plugin-form-v4.3.0.zip
bin/gpm direct-install -y tmp/grav-plugin-email-v3.1.0.zip
bin/gpm direct-install -y tmp/grav-plugin-login-v3.3.8.zip
bin/gpm direct-install -y tmp/grav-plugin-admin-v1.9.19.zip
```

あるいは、次のようにしてプラグインをインストールすることもできます： `user/plugins/pluginnname` 内のすべてのファイルを削除し、 zip ファイルからコピーします。zip ファイル内のファイル名には、規則性が無い場合があることに注意してください。

<h2 id="upgrading-to-grav-1-7">Grav 1.7 へのアップグレード</h2>

以下の CLI コマンドを、1行ずつ実行し、それぞれの指示に従ってください：

```bash
bin/gpm self-upgrade
bin/gpm update
```

また、他のプラグインを1つずつ最新バージョンにアップデートしたいかもしれませんが、プラグインの最新バージョンが Grav 1.6 をサポートしている場合にのみ行ってください。そうでないプラグインは、機能するか確認できない場合は無効化してください。後ほど、サイトをテストするときに、再度有効化できます。

> [!Tip]  
> プラグインが壊れていなければ、この時点で、管理パネルとサイトは完全に機能します。

> [!Warning]  
> **[Upgrading to Grav 1.7](/advanced/grav-development/grav-17-upgrade-guide)** を読む前に、Grav や管理パネルプラグインをこれ以上アップグレードすることは避けてください。サイトと管理パネルの両方が壊れる結果になるかもしれません。

