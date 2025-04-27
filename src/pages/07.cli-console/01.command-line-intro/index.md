---
title: "コマンドラインの導入"
layout: ../../../layouts/Default.astro
---

Gravが、コマンドラインを念頭に作られたことは、よく知られています。管理パネルプラグインは、たしかに、ターミナル（MacOS や Linux）やコマンドプロンプト（Windows）を開かなくても、いろいろなことを、かんたんにできるようにしてくれますが、コマンドラインでできることのスピードやレベルについては、書くべきことが、たくさんあります。

このことは、自社サーバーや、コマンドラインでアクセス可能なリモートサーバーで運用している方に、特に当てはまります。コマンドラインから使えるツールの量は、信じられないほどあります。わずかなキーストロークで、サイトのホスティングから、Gravシステム、そのプラグインやテーマまで、実質的にすべての面をコントロール可能です。

結局は、すべては個人の好みに帰着します。このページでは、いくつかの素晴らしいリソースを取り上げます。それらは、コマンドラインに親しむのに役立ちます。

> [!Info]  
> すべてのOSで、コマンドの互換性があるわけではありません。MacOS と 多くのLinux のディストリビューションの間には、細かい違いがありますし、Windows のコマンドプロンプトと比べれば、大きな違いがあります。

## MacOS

MacOS is based on Unix and is POSIX standards compliant. This means that most of the commands you may be familiar with on other Unix or Linux-based operating systems will work exactly as expected in MacOS. There are some exceptions to the rule, and it's for that reason that we recommend researching Terminal commands for the specific operating system you're working with.

Here are some great resources to help you become accustomed to using the Terminal in MacOS:

* [Michael Hogg's MacOS Terminal Commands Guide](http://michael-hogg.co.uk/os_x_terminal.php) - A practical resource for MacOS-friendly Terminal commands, what they do, and how to use them.
* [MacRumors Guide to Terminal](http://guides.macrumors.com/Terminal) - A useful resource for navigating and using the Terminal, including tips for using it with the GUI.
* [Envato Tuts+ Terminal Tips and Tricks](http://computers.tutsplus.com/tutorials/40-terminal-tips-and-tricks-you-never-thought-you-needed--mac-51192) - 40 clever tips and tricks for mastering the Terminal. Includes commands you won't find in many basic introductions.
* [Envato Tuts+ Taming the Terminal](http://computers.tutsplus.com/articles/new-mactuts-session-taming-the-terminal--mac-45471) - A multi-part, detailed course in using the Terminal. Includes videos, screenshots, and more.


## Linux

世の中の Linux（および Unix ）ディストリビューションの多くには、共通点があります：Bash コマンドラインインターフェース（ターミナル）です。Gnomeや、Unity、KDE、その他いずれの GUI で動かしていたとしても、デスクトップであれ、ノートPCであれ、コマンドラインを訪れたチャンスはあるでしょう。

結局、CLIは、パワフルです。GUIでできることは、ほとんど何でも、直接コマンドラインで実現できます。Linux のターミナルに親しむための素晴らしいリソースは、次のようなものがあります：

* [TechSpot's Beginner's Guide to the Linux Command Line](http://www.techspot.com/guides/835-linux-command-line-basics/) - An excellent beginner's guide to the command line.
* [MakeUseOf's Quick Guide to Getting Started with the Linux Command Line](http://www.makeuseof.com/tag/a-quick-guide-to-get-started-with-the-linux-command-line/) - Another great resource for learning about the Terminal.
* [O'Reilly Linux DevCenter Directory of Linux Commands](http://www.linuxdevcenter.com/cmd/) - An index of commands available in the Terminal.
* [Ryan's Tutorials Linux Tutorial](http://ryanstutorials.net/linuxtutorial/) - An excellent all-in-one guide to Linux and the Bash command line interface (Terminal).

## Windows

Windows sits apart from the pack for a number of reasons. Many of the commands in the command line for Windows are reminiscent of its DOS roots. Common commands such as `ls` for a directory listing doesn't work here. Instead, you would type `dir`. Here are a handful of resources to help you get the hang of the Windows Command Prompt:

* [MakeUseOf's Beginner's Guide to the Windows Command Line](http://www.makeuseof.com/tag/a-beginners-guide-to-the-windows-command-line/) - A well-written introduction to the command line for Windows.
* [DOSPrompt.info](http://dosprompt.info/) - An entire site devoted to familiarizing users with the Command Prompt.

> [!Info]  
> All of Grav's CLI commands rely on PHP, but this is not immediately available in Windows. You can find out whether it is installed by opening a console and typing `php -v` to check. If `'php' is not recognized as an internal or external command ...` returns, it is not.

If you want to add PHP to your Windows system, you need to find your "Environment Variables", either by searching for it in the Start-Menu or going to Control Panel -> Advanced System Settings -> Click the "Environment Variables"-button.

Under "System Variables", find "Path" and click edit. Copy the "variable value" into notepad, and add a semicolon at the end - to separate variables. Then find the path to your installation of PHP ([from scratch](http://windows.php.net/) or using a current installation that came with your development environment), and add it to the end of this long list of variables. You want the folder-path, not including `php.exe`.

When that is done, open a new console (or restart your current one) so the new path is applied. Then try `php -v` again, you should get an output like: `PHP 7.0.7 (cli) ...`. When you run Grav's commands, you will need to prepend `php` to them, for instance `php grav/gpm index`.

<h2 id="grav-specific-commands">Grav 特有のコマンド</h2>

Grav のクールなところの1つは、何でもできるパワフルなコマンドを持っていることです。追加のプラグインやテーマをインストールしたり、ユーザーを管理パネルに追加したりできます。このセクションでは、最も一般的なコマンドを挙げます。

以下のコマンドはすべて、**どのOSにも** 対応しています。


| コマンド | 説明  |
| :----------------   | :--------------------------------------  |
| `bin/grav list`                   | Grav で使える（GPM 以外の）コマンドをリスト表示します|
| `bin/grav help <command>`         | `<command>` のヘルプを表示します  |
| `bin/grav new-project <location>` | Used to create a new, clean Grav instance in a different folder. Can be run from an existing Grav install.                         |
| `bin/grav install`                | 現在の Grav に必要な依存関係をインストールします                                               |
| `bin/grav cache`                  | キャッシュをクリアします。次のオプションが付けられます： `--all`, `--assets-only`, `--images-only`, and `--cache-only` |
| `bin/grav backup`                 | 現在の Grav サイトのバックアップを zip で作ります                                                                                    |
| `bin/grav composer`               | 手作業でインストールした、コンポーザーベースの vendor パッケージをアップデートします                       |
| `bin/grav security`               | 設定されたXSS セキュリティチェックを、すべてのページに対して実行します                |
| `bin/gpm list`                    | GPM（Gravパッケージマネージャー）経由で利用できるすべてのコマンドをリスト表示します                                             |
| `bin/gpm help <command>`          | `<command>` のヘルプを表示します               |
| `bin/gpm index`                   | テーマとプラグインで整理して、Grav リポジトリの利用可能なリソースの一覧を表示します |
| `bin/gpm info`                    | 目的のパッケージの詳細を表示します。たとえば、説明、作者、ホームページなど。 |
| `bin/gpm install`                 | リポジトリから、利用中の Grav へ、シンプルなコマンドでリソースをインストールします |
| `bin/gpm update`                  | インストール済みのプラグインやテーマでアップデート可能かをチェックし、一覧表示します |
| `bin/gpm uninstall`               | インストール済みのプラグインやテーマを削除し、キャッシュもクリアします |
| `bin/gpm self-upgrade`            | Grav を最新版にアップデートします |
| `bin/gpm logviewer`               | Easily view Grav logs with configuration options to pick log file, number of lines, and verbosity                                  |
| `bin/gpm scheduler`               | スケジュールされたジョブを管理し、必要に応じてスケジュールされた処理を手動で実行します |


> [!Info]  
> これらのコマンドのより詳しい説明は、 [Grav CLI](../02.grav-cli/) と [Grav GPM](../04.grav-cli-gpm/) のドキュメントで解説します。

以下のコマンドは、**mac もしくは unix 系システム** で使えます。

| コマンド                 | 説明                                                                                                           |
| :----------------                        | :--------------------------------------                                                                                   |
|  `bin/gpm index \| grep '\| installed'`  | Lists all plugins and themes you currently have installed. |

<h2 id="symbolic-links">シンボリック・リンク</h2>

シンボリックリンク（Symbolic Links、シムリンク symlinks とも言います）は、大変便利で、コマンドライン上でかんたんに実行できます。やることは、与えられたフォルダやコンテンツの仮想的なコピー（クローン）を作り、好きなところに置けます。真のコピーと違い、オリジナルとシンプルなトンネルでつながっています。これにより、内容の変更が一度に、オリジナルとシムリンクすべてに反映されます。

もうひとつの素晴らしいメリットは、同じファイルの複数のコピーを持つわけではないので、ディスク容量を圧迫しないことです。

Grav では、シムリンクは複数のインスタンスに、プラグインやテーマ、コンテンツを追加するのに最適な方法であり、更新や修正がかんたんになります。一度変更を加えれば、シムリンクされたすべてのファイルに反映されます。

シムリンクの実行プロセスは、OS間によって多少の違いはあるものの、非常にかんたんです。

<h3 id="symbolic-links-in-macox-and-linux">MacOS と Linux でのシンボリックリンク</h3>

![](osx_symlink.png)

コマンドのパターンは、 `ln -s <オリジナルのファイルまたは、ディレクトリ、コンテンツ> <仮想的なコピーをここに置きます>` のようになっています。

シムリンクを開始するコマンドは、OSにより異なります。MaxOS や、大半の Unix 及び Linux のディストロでは、`ln -s` がコマンドです。`ln` 部分がシステムにリンクを作るよう指示します。`-s` により、リンクをシンボリックにします。

<h3 id="symbolic-links-in-windows">Windows でのシンボリックリンク</h3>

The basic structure of the command in Windows is `mklink <type> <put virtual copies here> <original file, directory, or its contents>`. Unlike MacOS or Linux, you will need to set the argument for the type of file you're symbolically linking. The source and destination are also flipped in this case, where the new symbolic link comes before the file you're linking to. There are three arguments you can use here:

* `/j` - This is the most commonly used argument. It creates a symlink of a directory.
* `/h` - This creates a symbolic link for a specific file.
* `/d` - This creates a soft link, or a shortcut. It's not likely to be used for the purposes outlined here.


<h3 id="example-commands">コマンドの例</h3>

基本的に、次のものを指定します。シムリンクを始めるコマンド、何をシンボリックリンクするか、どこに仮想的なコピーを置くか。以下に、これらの例を詳しく示します：

<h5 id="link-contents-of-one-folder-to-another">あるフォルダから別のフォルダへコンテンツをリンクする</h5>

| MacOS and Linux             | Windows                           |
| :-----                      | :-----                            |
| `ln -s ~/folder1 ~/folder2` | `mklink /J C:\folder2 C:\folder1` |

このコマンドは、シムリンクを作り、 **folder1** にあるオリジナルコンテンツを使って、**folder2** の中にシンボリックリンクされたそれらのコピーを置きます。もし **folder2** が存在しなければ、このコマンドにより作られます。

<h5 id="link-entire-folders-from-one-place-to-another">ある場所から別の場所へフォルダ全体をリンクする</h5>

| MacOS and Linux              | Windows                            |
| :-----                       | :-----                             |
| `ln -s ~/folder1 ~/folder2/` | `mklink /J C:\folder2\ C:\folder1` |

このコマンドは、**folder1** 全体をコピーし、ターゲットの場所（この場合、**folder2** ）に配置します。この場合、**folder2** はすでに存在している必要があります。このコマンドによりフォルダは作成されません。**folder2** の末尾のスラッシュもしくはバックスラッシュに注目してください。

<h5 id="link-individual-file-s-from-one-place-to-another">ある場所から別の場所へ単独のファイルをリンクする</h5>

| MacOS and Linux                      | Windows                                     |
| :-----                               | :-----                                      |
| `ln -s ~/folder1/file.jpg ~/folder2` | `mklink /H C:\folder2\ C:\folder1\file.jpg` |

これは、単独のファイルをシンボリックリンクするときに便利です。ファイルを不k数雨のディレクトリ間のシェアしたいときに、特に便利で、あらゆる場所のそれらリンクのアップデートを同時にすることができます。オリジナルファイルが、唯一のシンボリックでないコピーなので、すべてのシンボリックリンクが機能するためには、オリジナルはその場所にいなければいけないことに留意してください。

