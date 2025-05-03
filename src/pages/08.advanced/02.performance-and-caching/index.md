---
title: "パフォーマンスとキャッシュ"
layout: ../../../layouts/Default.astro
---

Grav を魅力的なものとしている中心機能のひとつは、その速さです。速さは、 Grav の本質的な設計において、常に重要な考慮事項です。主にキャッシュによるものですが、他のコンポーネントによる部分もあります。

<h2 id="performance">パフォーマンス</h2>

1. **PHP のキャッシュは重要です** 。Grav のベストパフォーマンスを出すには、 PHP **opcache** と **usercache** （たとえば **APCu**）を実行してください。

2. **SSD ドライブ** can make a big difference. Most things can get cached in PHP user cache, but some are stored as files, so SSD drives can make a big impact on performance. Avoid using network filesystems such as NFS with Grav.

3. **Native hosting** will always be faster than a Virtual Machine.  VMs are a great way hosting providers can offer flexible “cloud” type environments. These add a layer of processing that will always affect performance. Grav can still be fast on a VM (much faster than wordpress, joomla, etc), but still, for optimal performance, you can't beat a native hosting option.

4. **Faster memory** is better. Because Grav is so fast, and because many of its caching solutions use memory heavily, the speed of the memory on your server can have a big impact on performance. Grav does not use extensive amounts of memory compared to some platforms so the amount of memory is not as important, nor does it impact performance as much, as memory type and speed.

5. **Fast Multi-core processors** are better. Faster and more advanced processors will always help, but not as much as the other points.

6. **Shared hosting** is cheap and readily available, but sharing resources will always slow things down a bit. Again, Grav can run very well on a shared server (better than other CMSes), but for ultimate speed, a dedicated server is the way to go.

7. **PECL Yaml Parser**.  Installing the native PHP PECL Yaml parser can increase YAML parsing speed by as much as 400%!  This is well worth looking at if you are looking for some extra speed.

> [!Info]  
> The getgrav.org runs on a single dedicated server with quad core processors, 16GB of memory and 6G SSD drives. We also run PHP 7.4 with Zend opcache and APCu user cache. The web servers do run a few other websites but not as many as you would find in a shared-hosting environment.

<h2 id="caching-options">キャッシュ・オプション</h2>

Caching is an integral feature of Grav that has been baked in from the start.  The caching mechanism that Grav employs is the primary reason Grav is as fast as it is.  That said, there are some factors to take into account.

Grav uses the established and well-respected [Doctrine Cache](https://www.doctrine-project.org/projects/doctrine-cache/en/latest/index.html) library. This means that Grav supports any caching mechanism that Doctrine Cache supports.  This means that Grav supports:

* **Auto** _(Default)_ - Finds the best option automatically
* **File** - Stores in cache files in the `cache/` folder
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

!!! You can easily force the cache to clear by just touching/saving a configuration file.

#### Memcache Specific Options

There are some extra configuration options that are required if you are connecting to a **memcache** server via the `memcache` driver option.  These options should go under the `cache:` group in your `user/config/system.yaml`:

```yaml
cache:
  ...
  memcache:
    server: localhost
    port: 11211
```


#### Memcached Specific Options

Similar to memcache, memcached has some extra configuration options that are required if you are connecting to a **memcached** server via the `memcached` driver option.  These options should go under the `cache:` group in your `user/config/system.yaml`:

```yaml
cache:
  ...
  memcached:
    server: localhost
    port: 11211
```


#### Redis Specific Options

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

#### Twig Specific Options

The Twig templating engine uses its own file based cache system, and there are a few options associated with it.

```yaml
twig:
  cache: false                          # Set to true to enable twig caching
  debug: true                           # Enable Twig debug
  auto_reload: true                     # Refresh cache on changes
  autoescape: false                     # Autoescape Twig vars
```

For slight performance gains, you can disable the `debug` extension, and also disable `auto_reload` which performs a similar function to `cache: check: method: none` as it will not look for changes in `.html.twig` files to trigger cache refreshes.

## Caching and Events

For the most part, [events are still fired](../../04.plugins/04.event-hooks/) even when caching is enabled.  This holds true for all the events except for `onPageContentRaw`, `onPageProcessed`, `onPageContentProcessed`, `onTwigPageVariables`, and `onFolderProcessed`.  These events are run as all pages and folders are recursed and they fire on each page or folder found.  As their name implies they are only run during the **processing**, and not after the page has been cached.

