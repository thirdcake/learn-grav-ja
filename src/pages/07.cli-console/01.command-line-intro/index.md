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
| `bin/gpm index`                   | Shows a list of all the available resources in the Grav repository, organized by themes and plugins.                               |
| `bin/gpm info`                    | Displays the details of the desired package, such as description, author, homepage, etc.                                           |
| `bin/gpm install`                 | Installs a resource from the repository to your current Grav instance with a simple command.                                       |
| `bin/gpm update`                  | Checks installed plugins and themes for available updates and lists them.                                                          |
| `bin/gpm uninstall`               | Removes an installed theme or plugin and clears the cache.                                                                         |
| `bin/gpm self-upgrade`            | Enables you to update Grav to the latest version.                                                                                  |
| `bin/gpm logviewer`               | Easily view Grav logs with configuration options to pick log file, number of lines, and verbosity                                  |
| `bin/gpm scheduler`               | Manage the scheduled jobs and manually run the scheduler process if required                                                       |


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

The process for performing a symlink is pretty straightforward, with minor differences between operating systems.

<h3 id="symbolic-links-in-macox-and-linux">MacOS と Linux でのシンボリックリンク</h3>

![](osx_symlink.png)

The command follows a common pattern of `ln -s <original file, directory, or its contents> <put virtual copies here>`.

The commands that initiate a symlink differ between operating systems. For MacOS and the majority of Unix and Linux distros, `ln -s` is the command. The `ln` part tells the system you want to create a link. The `-s` switch sets the link as symbolic.

<h3 id="symbolic-links-in-windows">Windows でのシンボリックリンク</h3>

The basic structure of the command in Windows is `mklink <type> <put virtual copies here> <original file, directory, or its contents>`. Unlike MacOS or Linux, you will need to set the argument for the type of file you're symbolically linking. The source and destination are also flipped in this case, where the new symbolic link comes before the file you're linking to. There are three arguments you can use here:

* `/j` - This is the most commonly used argument. It creates a symlink of a directory.
* `/h` - This creates a symbolic link for a specific file.
* `/d` - This creates a soft link, or a shortcut. It's not likely to be used for the purposes outlined here.


<h3 id="example-commands">コマンドの例</h3>

Basically, you state the command that initiates the symlink, what you're symbolically linking, and where you're putting the virtual copies. Below, we've detailed examples of these commands:

##### Link Contents of One Folder to Another

| MacOS and Linux             | Windows                           |
| :-----                      | :-----                            |
| `ln -s ~/folder1 ~/folder2` | `mklink /J C:\folder2 C:\folder1` |

This command creates a symlink that takes contents originally placed in **folder1** and puts a symbolically linked copy of them in **folder2**. If **folder2** does not already exist, it is created with this command.

##### Link Entire Folders from One Place to Another

| MacOS and Linux              | Windows                            |
| :-----                       | :-----                             |
| `ln -s ~/folder1 ~/folder2/` | `mklink /J C:\folder2\ C:\folder1` |

This command copies the entire **folder1** directory and places it in the target location (in this case **folder2**). In this case, **folder2** would need to already exist as it will not be created with this command.
Watch the slash or backslash at the ending when specifying **folder2**.

##### Link Individual File(s) from One Place to Another

| MacOS and Linux                      | Windows                                     |
| :-----                               | :-----                                      |
| `ln -s ~/folder1/file.jpg ~/folder2` | `mklink /H C:\folder2\ C:\folder1\file.jpg` |

This is a useful command for symbolically linking individual files. This is especially useful if you have files that are shared between multiple directories and you want to have them update everywhere at the same time. Keep in mind that the original file is the only actual copy, so it must remain where it is for all of the symbolic links to work.

