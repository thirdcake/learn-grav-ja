---
title: コマンドラインの導入
layout: ../../../layouts/Default.astro
lastmod: '2025-09-03'
description: 'Grav でコマンドラインを使うための基本的な情報を、各 OS ごとに解説します。'
---

Grav が、コマンドラインを念頭に作られたことは、よく知られた事実です。  
管理パネルプラグインは、たしかに、ターミナル (MacOS や Linux) やコマンドプロンプト (Windows) を開かなくても、いろいろなことを、簡単にできるようにしてくれます。  
しかし一方で、コマンドラインでできることのスピードや水準についても、特筆すべき点がたくさんあります。

このことは、自社サーバーや、コマンドラインでアクセス可能なリモートサーバーで運用している方に、特に当てはまります。  
コマンドラインから使えるツールの量は、信じられないほどあります。  
わずかなキーストロークで、サイトのホスティングも、 Grav システム自身も、そのプラグインやテーマも、実質的にすべての面をコントロールできます。

結局は、管理パネルを使うか、コマンドラインを使うかは、個人の好みの問題です。  
このページでは、いくつかの素晴らしい参考資料を紹介します。  
それらは、コマンドラインに慣れるのに役立つものです。

> [!Info]  
> すべての OS で、コマンドの互換性があるわけではありません。 MacOS と 多くの Linux のディストリビューションの間には、細かい違いがありますし、 Windows のコマンドプロンプトとは、大きな違いがあります。

## MacOS

MacOs は、 Unix ベースで、 POSIX 標準に準拠しています。  
これはつまり、他の Unix や Linux ベースのオペレーティング・システムで使い慣れたコマンドのほとんどが、 MacOS でも期待通りに動作するということです。  
ただし、例外もありますので、お使いのオペレーティング・システムの Terminal コマンドを調べることをおすすめします。

以下は、 MacOS の Terminal 利用に慣れるための素晴らしいリソースです：

* [Michael Hogg's MacOS Terminal Commands Guide](http://michael-hogg.co.uk/os_x_terminal.php) - MacOS 向けの Terminal コマンドには何があり、どのように機能するかを解説する実践的なリソース。
* [MacRumors Guide to Terminal](http://guides.macrumors.com/Terminal) - Terminal の使い方や、GUIから Terminal を使う tips を紹介する便利なリソース。
* [Envato Tuts+ Terminal Tips and Tricks](http://computers.tutsplus.com/tutorials/40-terminal-tips-and-tricks-you-never-thought-you-needed--mac-51192) - Terminal をマスターするための 40 個の tips や tricks 。多くの基本的な入門書には書いていないコマンドも含まれます。
* [Envato Tuts+ Taming the Terminal](http://computers.tutsplus.com/articles/new-mactuts-session-taming-the-terminal--mac-45471) - Terminal の使い方に関する複数回の詳細なレッスンコースです。動画やスクリーンショットもあります。

## Linux

世の中の Linux（および Unix ）ディストリビューションの多くには、共通点があります: Bash コマンドラインインターフェース（ターミナル）です。  
Gnomeや、 Unity、 KDE、その他いずれの GUI で動かしていたとしても、デスクトップであれ、ノート PC であれ、コマンドラインを訪れたチャンスはあるでしょう。

何にせよ、 CLI はパワフルです。  
GUI でできることは、ほとんど何でも、直接コマンドラインで実現できます。  
Linux のターミナルを使いこなすための素晴らしいリソースは、次のようなものがあります：

* [TechSpot's Beginner's Guide to the Linux Command Line](http://www.techspot.com/guides/835-linux-command-line-basics/) - コマンドラインの素晴らしい入門書。
* [MakeUseOf's Quick Guide to Getting Started with the Linux Command Line](http://www.makeuseof.com/tag/a-quick-guide-to-get-started-with-the-linux-command-line/) - Terminal を学ぶもうひとつの素晴らしいリソース。
* [O'Reilly Linux DevCenter Directory of Linux Commands](http://www.linuxdevcenter.com/cmd/) - Terminal で利用可能なコマンドの索引。
* [Ryan's Tutorials Linux Tutorial](http://ryanstutorials.net/linuxtutorial/) - Linux と Bash コマンドラインインターフェース（Terminal）に関する素晴らしいオールインワンガイド。

## Windows

Windows は、いくつかの理由で他とは違っています。  
コマンドラインで使われるコマンドの多くは、そのルーツである DOS を彷彿とさせます。  
一般的なコマンド（たとえば、 `ls` でディレクトリの一覧表示）は、 Windows では機能しません。  
かわりに、 `dir` コマンドを使います。  
以下は、 Windows のコマンドプロンプトを使いこなすのに役立つ便利なリソースです：

* [MakeUseOf's Beginner's Guide to the Windows Command Line](http://www.makeuseof.com/tag/a-beginners-guide-to-the-windows-command-line/) - Windows 向けコマンドラインの良い入門書。
* [DOSPrompt.info](http://dosprompt.info/) - コマンドプロンプトを使いこなすためのサイト。

> [!Info]  
> Grav の CLI コマンドはすべて、PHP を使っており、 Windows ではすぐに利用できるものではありません。 PHP がインストールされているかどうかは、コンソールを開いて、 `php -v` と入力することで確かめられます。 `'php' is not recognized as an internal or external command ...` という表示が返ってきたら、PHP がインストールされていません。

Windows システムに PHP を追加したい場合、 "Environment Variables" を探す必要があります。  
スタートメニューで検索するか、もしくは、コントロールパネルから、 Advanced System Settings へ行き、 "Environment Variables" ボタンをクリックしてください。

"System Variables" の下に、 "Path" を見つけ、編集をクリックしてください。  
メモ帳に "variable value" をコピーし、最後にセミコロンを追加します（変数の区切りです）。  
それから、インストールした PHP （ [スクラッチする](http://windows.php.net/) か、開発環境に付いてきたものを使えます）へのパスを探し、変数の長いリストの最後にパスを追記します。  
これは、フォルダのパスです。  
`php.exe` は含めないでください。

ここまで終わったら、新しくコンソールを開いてください（もしくは現在のコンソールを再起動してください）。  
新しいパスが適用されています。  
もう一度、 `php -v` を試してみてください。  
`PHP 7.0.7 (cli) ...` のような出力が得られるはずです。  
Grav のコマンドを実行するときは、 `php` をコマンドの最初に付ける必要があります。  
たとえば、 `php grav/gpm index` のように。

<h2 id="grav-specific-commands">Grav 特有のコマンド</h2>

Grav がクールである理由の1つは、何でもできるパワフルなコマンドを持っていることです。  
追加のプラグインやテーマをインストールしたり、ユーザーを管理パネルに追加したりできます。  
このセクションでは、最も一般的なコマンドを挙げます。

以下のコマンドはすべて、**どのOSにも** 対応しています。


| コマンド | 説明  |
| :----------------   | :--------------------------------------  |
| `bin/grav list`                   | Grav で使える（GPM 以外の）コマンドをリスト表示します|
| `bin/grav help <command>`         | `<command>` のヘルプを表示します  |
| `bin/grav new-project <location>` | 別フォルダに、クリーンで新しい Grav インスタンスを作成するために使います。すでにインストールされている Grav から実行できます |
| `bin/grav install`                | 現在の Grav に必要な依存関係をインストールします |
| `bin/grav cache`                  | キャッシュをクリアします。次のオプションが付けられます： `--all`, `--assets-only`, `--images-only`, and `--cache-only` |
| `bin/grav backup`                 | 現在の Grav サイトのバックアップを zip で作ります  |
| `bin/grav composer`               | 手作業でインストールした、 composer ベースの vendor パッケージをアップデートします                       |
| `bin/grav security`               | 設定されたXSS セキュリティチェックを、すべてのページに対して実行します                |
| `bin/grav logviewer`               | 設定オプション (ログファイルの選択、行番号、詳細度) で Grav のログを簡単に表示します |
| `bin/gpm list`                    | GPM（Gravパッケージマネージャー）経由で利用できるすべてのコマンドをリスト表示します                                             |
| `bin/gpm help <command>`          | `<command>` のヘルプを表示します               |
| `bin/gpm index`                   | テーマとプラグインで整理して、 Grav リポジトリの利用可能なリソースの一覧を表示します |
| `bin/gpm info`                    | 目的のパッケージの詳細を表示します。たとえば、説明、作者、ホームページなど。 |
| `bin/gpm install`                 | リポジトリから、利用中の Grav へ、シンプルなコマンドでリソースをインストールします |
| `bin/gpm update`                  | インストール済みのプラグインやテーマでアップデート可能かをチェックし、一覧表示します |
| `bin/gpm uninstall`               | インストール済みのプラグインやテーマを削除し、キャッシュもクリアします |
| `bin/gpm self-upgrade`            | Grav を最新版にアップデートします |
| `bin/gpm scheduler`               | スケジュールされたジョブを管理し、必要に応じてスケジュールされた処理を手動で実行します |


> [!Info]  
> これらのコマンドのより詳しい説明は、 [Grav CLI](../02.grav-cli/) と [Grav GPM](../04.grav-cli-gpm/) のドキュメントで解説します。

以下のコマンドは、**mac もしくは unix 系システム** で使えます。

| コマンド | 説明 |
| :--------------- | :------------ |
|  `bin/gpm index \| grep '\| installed'`  | インストール済みのテーマとプラグインを一覧表示。 |

<h2 id="symbolic-links">シンボリック・リンク</h2>

シンボリックリンク（Symbolic Links、シムリンク symlinks とも言います）は、大変便利で、コマンドライン上で、簡単に実行できます。  
与えられたフォルダやコンテンツの仮想的なコピー（クローン）を作り、好きなところに置けるという機能です。  
真のコピーと違い、オリジナルとシンプルなトンネルでつながっています。  
これにより、内容の変更が一度に、オリジナルとシムリンクすべてに反映されます。

もうひとつの素晴らしいメリットは、同じファイルの複数のコピーを持つわけではないので、ディスク容量を圧迫しないことです。

Grav では、シムリンクは複数のインスタンスに、プラグインやテーマ、コンテンツを追加するのに最適な方法であり、更新や修正が簡単になります。  
一度変更を加えれば、シムリンクされたすべてのファイルに反映されます。

シムリンクの実行方法は、 OS 間によって多少の違いはあるものの、非常に簡単です。

<h3 id="symbolic-links-in-macox-and-linux">MacOS と Linux でのシンボリックリンク</h3>

![](osx_symlink.png)

コマンドのパターンは、 `ln -s <オリジナルのファイルまたは、ディレクトリ、コンテンツ> <仮想的なコピーをここに置きます>` のようになっています。

シムリンクを開始するコマンドは、 OS により異なります。  
MaxOS や、大半の Unix 及び Linux のディストロでは、 `ln -s` がコマンドです。  
`ln` 部分がシステムにリンクを作るよう指示します。  
`-s` により、リンクをシンボリックにします。

<h3 id="symbolic-links-in-windows">Windows でのシンボリックリンク</h3>

Windows でのコマンドの基本構造は、 `mklink <type> <put virtual copies here> <original file, directory, or its contents>` です。  
MacOS や、 Linux とは違い、シンボリックリンクの type を引数として設定する必要があります。  
リンク元とリンク先も、順序が逆です。  
新しいシンボリックリンクが、リンクされるファイルの前に来ます。  
ここでは、3つの引数が使えます：

* `/j` - これは最も一般的に使われる引数です。ディレクトリのシムリンクを作成します。
* `/h` - 特定のファイルのシンボリックリンクを作成します。
* `/d` - これは、soft link もしくはショートカットを作成します。今回説明しているような目的では使う場面は無いでしょう。

<h3 id="example-commands">コマンドの例</h3>

基本的に、次のものを指定します。  
シムリンクを始めるコマンド、何をシンボリックリンクするか、どこに仮想的なコピーを置くか、です。  
以下に、これらの例を詳しく示します：

<h5 id="link-contents-of-one-folder-to-another">あるフォルダから別のフォルダへコンテンツをリンクする</h5>

| MacOS and Linux             | Windows                           |
| :-----                      | :-----                            |
| `ln -s ~/folder1 ~/folder2` | `mklink /J C:\folder2 C:\folder1` |

このコマンドは、シムリンクを作り、 **folder1** にあるオリジナルコンテンツを使って、**folder2** の中にシンボリックリンクされたそれらのコピーを置きます。  
もし **folder2** が存在しなければ、このコマンドにより作られます。

<h5 id="link-entire-folders-from-one-place-to-another">ある場所から別の場所へフォルダ全体をリンクする</h5>

| MacOS and Linux              | Windows                            |
| :-----                       | :-----                             |
| `ln -s ~/folder1 ~/folder2/` | `mklink /J C:\folder2\ C:\folder1` |

このコマンドは、**folder1** 全体をコピーし、ターゲットの場所（この場合、**folder2** ）に配置します。  
この場合、 **folder2** はすでに存在している必要があります。  
このコマンドによりフォルダは作成されません。  
**folder2** の末尾のスラッシュもしくはバックスラッシュに注目してください。

<h5 id="link-individual-file-s-from-one-place-to-another">ある場所から別の場所へ単独のファイルをリンクする</h5>

| MacOS and Linux                      | Windows                                     |
| :-----                               | :-----                                      |
| `ln -s ~/folder1/file.jpg ~/folder2` | `mklink /H C:\folder2\ C:\folder1\file.jpg` |

これは、単独のファイルをシンボリックリンクするときに便利です。  
ファイルを複数のディレクトリ間でシェアしたいときに、特に便利で、あらゆる場所のそれらリンクのアップデートを同時にすることができます。  
オリジナルファイルが、唯一のシンボリックでないコピーなので、すべてのシンボリックリンクが機能するためには、オリジナルはその場所にいなければいけないことに留意してください。

