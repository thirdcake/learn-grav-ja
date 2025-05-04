---
title: "パフォーマンスとキャッシュ"
layout: ../../../layouts/Default.astro
---

Grav を魅力的なものとしている中心機能のひとつは、その速さです。速さは、 Grav の本質的な設計において、常に重要な考慮事項です。主にキャッシュによるものですが、他のコンポーネントによる部分もあります。

<h2 id="performance">パフォーマンス</h2>

1. **PHP のキャッシュは重要です** 。Grav のベストパフォーマンスを出すには、 PHP **opcache** と **usercache** （たとえば **APCu**）を実行してください。

2. **SSD ドライブ** により、大きく変化します。ほとんどの場合、PHP のユーザーキャッシュからキャッシュを取得できますが、ファイルから取得するものもあります。そのため、 SSD ドライブは、パフォーマンスに大きく影響します。 NFS のようなネットワークファイルシステムを Grav で使用するのは避けてください。

3. **ネイティブ・ホスティング** は、仮想マシン（VM）と比べると、常に速いです。たしかに VM は、ホスティングプロバイダが、柔軟に "クラウド" タイプの環境を提供できるすばらしい方法です。これらは、パフォーマンスに影響を与える処理レイヤーを追加します。 VM 上のGrav は、（wordpress や joomla などよりは） 十分速いですが、パフォーマンスを最適化するなら、ネイティブ・ホスティングを選択しないわけにはいきません。

4. **より速いメモリー** は、より良いです。 Grav はとても速く、多くのキャッシュ機能は、メモリーを高頻度で利用するので、サーバーのメモリーのスピードは、パフォーマンスに大きく影響します。 Grav はいくつかのプラットフォームと比べて、大量のメモリーを使うわけではないので、メモリーの量は、メモリーのタイプやスピードほどにはパフォーマンスに影響せず、重要ではありません。

5. **速いマルチコア・プロセッサ** も、より良いです。より速く、より高度なプロセッサは、常に助けになります。ただし、他のポイントほどではありません。

6. **レンタルサーバー** は、安くて準備された環境が手に入ります。が、共用されたリソースは、常に少し遅いです。もう一度いいますが、Grav は、（他の CMS よりは）レンタルサーバーでもとても良く動作します。しかし、究極のスピードを求めるなら、専用サーバーがおすすめです。

7. **PECL Yaml パーサー** ネイティブの PHP PECL Yaml パーサーをインストールすると、YAML をパースするスピードが早くなります。最大 400% です！ さらなるスピードを求めるなら、一見の価値があります。

> [!Info]  
> getgrav.org サイトは、1つの専用サーバーで、クアッドコアのプロセッサーと、16GB のメモリーと、6G のSSD ドライブとで、運用されています。また、PHP 7.4 の実行に、 Zend opcache と、APCu ユーザーキャッシュを利用しています。この web サーバーは、いくつか他の web サイトも運用していますが、レンタルサーバー環境ほどたくさんではありません。

<h2 id="caching-options">キャッシュ・オプション</h2>

Caching is an integral feature of Grav that has been baked in from the start.  The caching mechanism that Grav employs is the primary reason Grav is as fast as it is.  That said, there are some factors to take into account.

Grav uses the established and well-respected [Doctrine Cache](https://www.doctrine-project.org/projects/doctrine-cache/en/latest/index.html) library. This means that Grav supports any caching mechanism that Doctrine Cache supports.  This means that Grav supports:

* **Auto** _（デフォルト）_ - 自動で最適な選択をします
* **File** - `cache/` フォルダにキャッシュファイルを保存します
* **APCu** - [https://php.net/manual/en/book.apcu.php](https://php.net/manual/en/book.apcu.php)
* **Memcache** - [https://php.net/manual/en/book.memcache.php](https://php.net/manual/en/book.memcache.php)
* **Redis** - [https://redis.io](https://redis.io)
* **WinCache** - [https://www.iis.net/downloads/microsoft/wincache-extension](https://www.iis.net/downloads/microsoft/wincache-extension)

By default, Grav comes preconfigured to use the `auto` setting.  This will try **APC**, then **WinCache**, and lastly **File**.  You can, of course, explicitly configure the cache in your `user/config/system.yaml` file, which could make things ever so slightly faster.

<h2 id="caching-types">キャッシュ・タイプ</h2>

There are actually **5 types** of caching happening in Grav.  They are:

1. YAML configuration caching into PHP.
2. Core Grav caching for page objects.
3. Twig caching of template files as PHP classes.
4. Image caching for media resources.
5. Asset caching of CSS and JQuery with pipelining.

The YAML configuration caching is not configurable, and will always compile and cache the configuration into the `/cache` folder. Image caching is also always on, and stores its processed images in the `/images` folder.

<h3 id="grav-core-caching">Grav のコア・キャッシュ</h3>

Core Grav caching has the following configuration options as configured in your `user/config/system.yaml` file:

```yaml
cache:
  enabled: true                        # Set to true to enable caching
  check:
    method: file                       # Method to check for updates in pages: file|folder|hash|none
  driver: auto                         # One of: auto|file|apc|xcache|memcache|wincache|redis
  prefix: 'g'                          # Cache prefix string (prevents cache conflicts)
```

As you can see, the options are documented in the configuration file itself.  During development sometimes it is useful to disable caching to ensure you always have the latest page edits.

By default, Grav uses the `file` check method for its caching.  What this means is that every time you request a Grav URL, Grav uses a highly optimized routing to run through all the **files** in the `user/pages`  folder to determine if anything has changed.

`folder` cache check is going to be slightly faster than `file` but will not work reliably in all environments.  You will need to check if Grav picks up modifications to pages on your server when using the `folder` option.

`hash` checking uses a fast hash algorithm on all of the files in each page folder.  This may be faster than file checking in some situations and does take into account every file in the folder.

If automatic re-caching of changed pages is not critical to you (or if your site is rather large), then setting this value to `none` will speed up a production environment even more. You will just need to manually [clear the cache](../../07.cli-console/02.grav-cli/#clear-cache) after changes are made. This is intended as a **Production-only** setting.

> [!Warning]  
> Deleting a page does not clear the cache as cache clears are based on folder-modified timestamps.

<!-- -->

> [!Tip]  
> You can easily force the cache to clear by just touching/saving a configuration file.

<h4 id="memcache-specific-options">Memcache に特有のオプション</h4>

There are some extra configuration options that are required if you are connecting to a **memcache** server via the `memcache` driver option.  These options should go under the `cache:` group in your `user/config/system.yaml`:

```yaml
cache:
  ...
  memcache:
    server: localhost
    port: 11211
```

<h4 id="memcached-specific-options">Memcached に特有のオプション</h4>

Similar to memcache, memcached has some extra configuration options that are required if you are connecting to a **memcached** server via the `memcached` driver option.  These options should go under the `cache:` group in your `user/config/system.yaml`:

```yaml
cache:
  ...
  memcached:
    server: localhost
    port: 11211
```

<h4 id="redis-specific-options">Redis に特有のオプション</h4>

There are some extra configuration options that are required if you are connecting to a **redis** server via the `redis` driver option.  These options should go under the `cache:` group in your `user/config/system.yaml`:

```yaml
cache:
  ...
  redis:
    server: localhost
    port: 6379
```

Alternatively, you can use a socket connection:

```yaml
cache:
  ...
  redis:
    socket: '/tmp/redis.sock'
```

If your redis server has a password or secret set you can also set that in this configuration:

```yaml
cache:
  ...
  redis:
    password: your-secret
```

_you will also need the php-redis installed in your system_

<h4 id="twig-specific-options">Twig に特有のオプション</h4>

Twig テンプレートエンジンは、独自のファイルベースのキャッシュシステムを使います。そして、それに伴い、いくつかのオプションがあります。

```yaml
twig:
  cache: false                          # Set to true to enable twig caching
  debug: true                           # Enable Twig debug
  auto_reload: true                     # Refresh cache on changes
  autoescape: false                     # Autoescape Twig vars
```

パフォーマンスを少し良くするため、`debug` 拡張を無効にできます。また、`cache: check: method: none` と似た機能を持つ `auto_reload` も無効にできます。それにより、キャッシュがリフレッシュされるまで、 `.html.twig` ファイルの変更を探しません。

<h2 id="caching-and-events">キャッシュとイベント</h2>

ほとんどの場合、キャッシュが有効化されていたとしても、 [イベントは発火します](../../04.plugins/04.event-hooks/) 。イベントが、 `onPageContentRaw`, `onPageProcessed`, `onPageContentProcessed`, `onTwigPageVariables`, もしくは `onFolderProcessed` 以外であるとき、これは正しいです。例外となるイベントは、すべてのページとフォルダが再起されるときに実行され、ページやフォルダが見つかるごとに発火します。イベント名から示唆されるとおり、 **処理中** にのみ実行され、キャッシュされたあとには実行されません。

