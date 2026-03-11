---
title: 'WSL(Windows Subsystem for Linux)'
layout: ../../../layouts/Default.astro
lastmod: '2025-05-13'
---
Windows Subsystem for Linux を使うと、開発者は、仮想マシンのオーバーヘッド無しで、直接 Windows から GNU/Linux 環境（ほとんどのコマンドラインツール、ユーティリティ、アプリケーションを含む）を実行できます。

できること：
- Windows Store から、お気に入りの GNU/Linux ディストリビューションを選びます。　
- grep, sed, awk, その他 ELF-64 バイナリなどの一般的なコマンドラインのフリーソフトウェアを実行します。
- 以下の Bash シェルスクリプトと GNU/Linux コマンドラインアプリケーションを実行します：
  - ツール： vim, emacs, tmux
  - 言語： Javascript/node.js, Ruby, Python, C/C++, C# & F#, Rust, Go, など。
  - サービス： sshd, MySQL, Apache, lighttpd
- 自身の GNU/Linux ディストリビューションのパッケージマネージャーを使って、追加のソフトウェアをインストールします。
- Unix ライクなコマンドラインシェルで、Windows アプリケーションを呼び出せます。
- Windows 上で、GNU/Linux アプリケーションを呼び出せます。

より詳しい情報は： [Windows Subsustem for Linux ドキュメント](https://learn.microsoft.com/ja-jp/windows/wsl/about) をご覧ください。

<h2 id="installing-windows-subsystem-for-linux">WSL のインストール</h2>

*Windows Subsystem for Linux* のインストールについては、 Microsoft 自身のドキュメント [WSL を使用して Windows に Linux をインストールする方法](https://learn.microsoft.com/ja-jp/windows/wsl/install) に詳しく書かれています。
インストールガイドに書かれている標準の Ubuntu ディストロのかわりに、最新の Ubuntu 18.04 LTS を検索して選択してください。

Ubuntu のインストールを初期化してアップデートするには、"Initializing a newly installed distro"（リンク切れ）に従ってください。
前の手順で、 Ubuntu ディストロをすでに初期化していれば、この手順は、スキップできるかもしれません。

> [!Note]  
> WSL の重要な特徴のひとつは、 **Windows tools** が Ubuntu 内に保存されているファイルにアクセス **できない** ことです。しかし Ubuntu は（ほぼ） Windows のファイルシステムに読み書きできます。そのため、Windows tools からアクセスする必要のあるファイル（たとえば： IDE やバックアップなど）は、 Windows のファイルシステムに保存する必要があります。  
> bash シェル内から Windows ファイルシステムにアクセスするには、path に `/mnt/c/` を付ける必要があります。必須ではありませんが、シムリンクを作成する際は、大文字小文字まで正確に同じファイルパスにすることをおすすめします。

> [!訳注]  
> Ubuntu のバージョンは、18でなくても、20 でも 24 でも大丈夫そうです。  
> また、上記のリンク切れ部分は、とりあえず Linux のユーザー名とパスワードを設定できれば問題なさそうです。マイクロソフトのドキュメントで説明されているので、初期化に困ることはないと思います。  
> Ubuntu から Windows のファイルシステムにリンクを張ると、ホストと仮想マシン間のI/Oが発生し、処理が遅くなることがあるようです。Ubuntu 上で完結させられるなら、その方が良いかもしれません。

<h2 id="installing-apache">Apache のインストール</h2>

Apache をインストールするには、 bash シェルで次のコマンドを使用します：

```bash
sudo apt install apache2
```

> [!Tip]  
> WSL で使うターミナルでは、通常のテキスト貼り付けはサポートされていません。 **右クリック** を使って貼り付けできます。

web サイト用のプロジェクトフォルダを作成してください。上述のとおり、このフォルダは WSL ファイルシステムの外に作る必要があります。たとえば： `C:/Users/<Username>/Documents/Development/Web/webroot` もしくはシンプルに： `C:/webroot`

Ubuntu では、 `webroot` フォルダにシンボリックリンクを作成します。

```bash
sudo ln -s /mnt/c/your/path/to/webroot /var/www/webroot
```

Apache のデフォルトの仮想ホスト config ファイルを開きます：

```bash
sudo nano /etc/apache2/sites-available/000-default.conf
```

> [!Tip]  
> `Shift` キーを押したまま `↓` キーを使ってスクロールダウンして、既存のコンテンツを削除します。 `Ctrl` キー + `k` キーを押して、選択範囲を切り取ります。

次の VirtualHost config を挿入します：

```txt
<VirtualHost *:80>

    ServerName localhost

    ServerAdmin webmaster@localhost
    DocumentRoot  /var/www/webroot

    <Directory /var/www/>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined

</VirtualHost>
```

> [!Tip]  
> `Ctrl` + `o` を同時に押して、ファイルを保存し、 `Enter` キーを押して確定します。`Ctrl` + `x` で終了します。  
> （コマンドバー： `^` は、 `Ctrl` を、 `M` は、 `Alt` を意味します）

> [!訳注]  
> 上記の Tip はおそらく、nano エディタの話だと思います。

Windows のお好みの エディタ/IDE を開き、`index.html` ファイルを webroot フォルダに作成し、次のようなコンテンツを書いてください：

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>It works!</title>
</head>
<body>
  <h1>It works!</h1>
</body>
</html>
```

Apache サービスをスタートさせます：

```bash
sudo service apache2 start
```

> [!Info]  
> おそらく、次の既知のエラーメッセージが表示されますが、 [無視できます](https://github.com/Microsoft/WSL/issues/1953) ：  
> *(92)Protocol not available: AH00076: Failed to enable APR_TCP_DEFER_ACCEPT*

[http://localhost](http://localhost) を開くと、 'It works!' というテキストが表示されます。

将来の Grav サイトが適切に機能するには、 Apache モジュールの `rewrite` を有効にする必要があります。

```bash
sudo a2enmod rewrite
```

<h2 id="installing-php">PHP のインストール</h2>

最新の PHP バージョンをインストールするには、次のコマンドを使用します：

```bash
sudo apt install php
```

インストールされた PHP と、そのバージョンを確認するには、次のコマンドを実行します：

```bash
php -v
```

次のようなレスポンスが返ります：

```bash
PHP 7.2.7-0ubuntu0.18.04.2 (cli) (built: Jul  4 2018 16:55:24) ( NTS )
Copyright (c) 1997-2018 The PHP Group
Zend Engine v3.2.0, Copyright (c) 1998-2018 Zend Technologies
```

Grav の PHP 要件を満たすには、いくつか追加で PHP 拡張機能をインストールする必要があります：

```bash
sudo apt install php-mbstring php-gd php-curl php-xml php-zip
```

変更を有効にするために、 Apache を再起動します：

```bash
sudo service apache2 restart
```

<h2 id="installing-grav">Grav をインストール</h2>

Grav は Windows 内からでも Ubuntu 内からもインストールできます。

<h4 id="option-1-windows">オプション1： Windows</h4>

ZIP パッケージをダウンロードし、展開することで、 Grav をインストールします：
1. 最新の  [**Grav**](https://getgrav.org/download/core/grav/latest) または [**Grav + Admin**](https://getgrav.org/download/core/grav-admin/latest) パッケージをダウンロードしてください。
1. ZIP ファイルを、上記で作成した webroot に展開します。
1. 展開したフォルダ名を `mysite` に変更します。
1. ブラウザで [http://localhost/mysite](http://localhost/mysite) を開くと、Grav のインストールが機能しているはずです。

<h4 id="option-2-ubuntu">Option 2: Ubuntu</h4>
次のコマンドを実行して、 Apache のデフォルトの webroot 内に Grav をインストールします：

```bash
wget -O grav.zip https://getgrav.org/download/core/grav/latest
sudo apt install unzip  # unzip コマンドは WSL/Ubuntu ではデフォルトではインストールされていないことがある
unzip grav.zip -d /var/www/webroot
mv /var/www/webroot/grav /var/www/webroot/mysite
```

ブラウザで [http://localhost/mysite](http://localhost/mysite) を開くと、Grav のインストールが機能しているはずです。

他のインストールオプションについては、 Grav の [インストール](../../01.basics/03.installation/) ドキュメントを確認してください。

<h2 id="installing-xdebug-optional">XDebug のインストール（オプション）</h2>

もしあなたが開発者で、独自のプラグインやテーマを開発したい場合、 ~~おそらく~~ 必然的に、いくつかの地点でコードをデバッグする必要があります ...

次のコマンドで、 XDebug をインストールします：

```bash
sudo apt install php-xdebug
```

XDebug は、 `php.ini` で有効化する必要があります。  
エディタを開いてください：

```bash
sudo nano /etc/php/7.2/apache2/php.ini
```

そして、ファイルの最後に、次の行を追加します：

```txt
[XDebug]
xdebug.remote_enable = 1
```

> [!Tip]  
> Nano エディタでは、 `Alt` + `/` を使ってファイルの最後にジャンプできます。

再度、Apache を再起動します：

```bash
sudo service apache2 restart
```

<h4 id="activating-debugger">デバッガの有効化</h4>

デバッグを始めるには、まずサーバーでデバッガーを有効化する必要があります。
このため、特別な GET/POST または COOKIE パラメーターを設定する必要があります。
これは、 [手動](https://xdebug.org/docs/remote#starting) でもできますが、ブラウザ拡張機能を使う方がはるかに便利です。
ボタンをクリックするだけで、デバッガーが有効化されます。拡張機能が有効化されると、 XDEBUG_SESSION_START を経由せずに、 XDEBUG_SESSION クッキーが直接送信されます。以下に、お使いのブラウザに対応する拡張機能へのリンクの表を示します。


| ブラウザ       | ヘルパー 拡張機能  |
| ------------- |------------------|
|Chrome         |[Xdebug Helper](https://chrome.google.com/extensions/detail/eadndfjplgieldjbigjakmdgkmoaaaoc)|
|Firefox|[Xdebug Helper](https://addons.mozilla.org/en-US/firefox/addon/xdebug-helper-for-firefox/) または [The easiest Xdebug](https://addons.mozilla.org/en-US/firefox/addon/the-easiest-xdebug/)|
|Opera  |[Xdebug launcher](https://addons.opera.com/addons/extensions/details/xdebug-launcher/)|

web サイトのデバッグを オン/オフを切り替えるには、ブラウザ拡張機能で 'Debug' を切り替えるだけです。

<h4 id="launching-debugger-in-visual-studio-code-optional">Visual Studio Code でデバッガを使う（オプション）</h4>

Vistual Studio Code を使用する場合、ファイルマッピングのため、Apache/PHP が WSL で実行されていると、デフォルトの PHP デバッグランチャーは機能しません。

次のような config を、 `.vscode/launch.json` に作成されている PHP の [launch configuration](https://code.visualstudio.com/docs/editor/debugging#_launch-configurations) に書き込みます。

```json
{
    "name": "LSW Listen for XDebug",
    "type": "php",
    "request": "launch",
    "port": 9000,
    "pathMappings": {
        "/mnt/c": "c:/",
    }
}
```

<h2 id="adding-extra-virtual-hosts-optional">仮想ホストの追加（オプション）</h2>

サイトのライフサイクルの各段階（開発、テスト、本番）では、 Grav の異なる設定が必要になることがあります。たとえば、キャッシュやアセットパイプラインなどです。開発環境では、これらをオフにし、パフォーマンステストではオンにしたいでしょう。詳しくは、 [自動の環境設定](../../08.advanced/04.environment-config/#automatic-environment-configuration) のドキュメントをご覧ください。
- 管理者としてエディタを起動し、ファイルを開きます。  
  たとえば、以下のホストを追加できます：

  ```bash
  127.0.0.1 mysite-dev
  127.0.0.1 mysite-prod
  ```
  
  Windows ホストファイルで定義されたホストは、自動で WSL/Ubuntu の `/etc/hosts` でも利用できます。
- `/etc/apache2/sites-available` フォルダに新しい仮想ホストの設定ファイルを作成します。

  ```bash
  sudo nano /etc/apache2/sites-available/mysite-dev.conf
  ```
　
　エディタに次の内容を貼り付けます。

  ```txt
  <VirtualHost *:80>

      ServerName mysite-dev

      ServerAdmin webmaster@localhost
      DocumentRoot  /var/www/webroot/mysite

      <Directory /var/www/>
          Options Indexes FollowSymLinks
          AllowOverride All
          Require all granted
      </Directory>

      ErrorLog ${APACHE_LOG_DIR}/error.log
      CustomLog ${APACHE_LOG_DIR}/access.log combined

  </VirtualHost>
  ```

Repeat the above commands for `mysite-prod.conf` and use `ServerName mysite-prod` as server.

Apache の設定で、新しい仮想ホストを有効にします：

```bash
sudo a2ensite mysite-*
sudo service apache2 reload
sudo service apache2 restart
```

これにより、ブラウザで [http://mysite-dev](http://mysite-dev) を指し示すと、 `/user/mysite-dev/config/` フォルダ内の設定ファイルを使って、 `C:/your/path/to/webroot/mysite` にインストールした Grav が開きます。

<h2 id="automatically-start-apache-optional">Apache を自動で起動する（オプション）</h2>

Apache の起動と停止には、上位の権限が必要です。また、上位の権限を付与するには、パスワードが求められます。Ubuntu がパスワードを要求しないようにするには、特定のサービスに対して永続的に上位の権限を付与することができます。

[visudo](http://manpages.ubuntu.com/manpages/trusty/man8/visudo.8.html) エディタを起動して、 sudoer ファイルを編集します：

```bash
sudo visudo -f /etc/sudoers.d/services
```

次の行をエディタにコピーします：

```bash
%sudo ALL=(root) NOPASSWD: /usr/sbin/service *
%wheel ALL=(root) NOPASSWD: /usr/sbin/service *
```

パスワードを入力せずに、上位権限で Apache を起動できるようになりました。

Ubuntu のシェルを起動するとき、いつでも Apache が起動しているようにするには、`sudo service apache2 start` コマンドを `.bashrc` 起動スクリプトに追加する必要があります。 WSL ターミナルを開始するたびに、このスクリプトが実行されます。

```bash
nano .bashrc
```

ファイルの末尾に次のスクリプトを追加します：

```txt
## Start apache2 if not running
status=`service apache2 status`
if [[ $status == *"apache2 is not running" ]]
then
  sudo service apache2 start
fi
```

また、次のコードを `.bash_logout` に追加し、bash シェルが閉じるときに Apache を停止します。

```txt
## Stop apache2 if running
status=`service apache2 status`
if [[ $status == *"apache2 is running" ]]
then
  sudo service apache2 stop
fi
```

<h2 id="tips-and-tricks">Tips と Tricks</h2>

<h3 id="gui-linux-terminal-emulator">GUI Linux ターミナルエミュレータ</h3>

デフォルトのターミナル体験が良くなく、 "ネイティブの" Linux GUI ターミナルをインストールしたい場合は、 [Configuring a pretty and usable terminal emulator for WSL](https://blog.ropnop.com/configuring-a-pretty-and-usable-terminal-emulator-for-wsl/) という記事が参考になるかもしれません。


<h3 id="multiple-websites-one-grav-codebase">1つの Grav コードベースで複数のweb サイト</h3>

私のように、別々のプロジェクトに、複数の Grav web サイトをデプロイしている場合、1つの Grav コアのシムリンクのコピーを作成するため、 [シンボリックリンク](../../07.cli-console/01.command-line-intro/#symbolic-links) と [プロジェクトのコピー](../../07.cli-console/02.grav-cli/#copying-a-project) のドキュメントが役に立つかもしれません。

