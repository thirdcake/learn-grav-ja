---
title: Flex
layout: ../../../../../layouts/Default.astro
lastmod: '2025-09-06'
description: 'twig や、プラグイン中の PHP で、 flex を取得する方法を解説します。'
---

> [!Tip]  
> メソッドの完全な一覧は、 **Customizing Flex Objects** セクションで解説します。

> [!訳注]  
> Customizing Flex Objects セクションは、まだ書かれていないようです。

## count()

`count(): int`   
Flex に登録されたディレクトリの数を数えます。

返り値：
- `int` [**ディレクトリ**](../02.directory/) の数

```twig
{% set flex = grav.get('flex') %}

Flex has {{ flex.count() }} enabled directories.
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexInterface;

/** @var FlexInterface $flex */
$flex = Grav::instance()->get('flex');

/** @var int $count */
$count = $flex->count();
```

## getDirectories()

`getDirectories( [names] ): array` ディレクトリのリストを取得

パラメータ：

- **names** Optional: ディレクトリ名のリスト (`array`)

返り値：

- `array` **[ディレクトリ](../02.directory/)** のリスト

> [!Tip]  
> 名前のリストが渡されなかった場合、メソッドは Flex に登録されたすべてのディレクトリを返します。

```twig
{% set flex = grav.get('flex') %}

{# Get all directories #}
{% set directories = flex.directories() %}

{# Get listed directories #}
{% set listed_directories = flex.directories(['contacts', 'phonebook']) %}

{# Do something with the directories #}
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexInterface;
use Grav\Framework\Flex\Interfaces\FlexDirectoryInterface;

/** @var FlexInterface $flex */
$flex = Grav::instance()->get('flex');

/** @var FlexDirectoryInterface[] $directories */
$directories = $flex->getDirectories();
// = ['contacts' => FlexDirectory, ...]

/** @var FlexDirectoryInterface[] $directories */
$listedDirectories = $flex->getDirectories(['contacts', 'phonebook']);
// = ['contacts' => FlexDirectory]

/** @var array<FlexDirectoryInterface|null> $directories */
$listedDirectoriesWithMissing = $flex->getDirectories(['contacts', 'phonebook'], true);
// = ['contacts' => FlexDirectory, 'phonebook' => null]
```

> [!Tip]  
> 必要なディレクトリのみ返すようにしてください。

## hasDirectory()

`hasDirectory( name ): bool`: ディレクトリが存在するかどうかチェック

パラメータ：

- **name** ディレクトリ名 (`string`)

返り値：

- `bool` 見つかった場合は True、そうでなければ false

```twig
{% set flex = grav.get('flex') %}

Flex has {{ not flex.hasDirectory('contacts') ? 'not' }} contacts directory.
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexInterface;

/** @var FlexInterface $flex */
$flex = Grav::instance()->get('flex');

/** @var bool $exists */
$exists = $flex->hasDirectory('contacts');
```

## getDirectory()

`getDirectory( name ): Directory | null` ディレクトリを取得。見つからなければ null を返します。

パラメータ：

- **name** ディレクトリ名 (`string`)

返り値：

- [**Directory**](../02.directory/) (`object`)
- `null` ディレクトリが無かった場合

```twig
{% set flex = grav.get('flex') %}

{# Get contacts directory (null if not found) #}
{% set directory = flex.directory('contacts') %}

{# Do something with the contacts directory #}
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexInterface;
use Grav\Framework\Flex\Interfaces\FlexDirectoryInterface;

/** @var FlexInterface $flex */
$flex = Grav::instance()->get('flex');

/** @var FlexDirectoryInterface|null $directory */
$directory = $flex->getDirectory('contacts');
if ($directory) {
    // Directory exists, do something with it...
}
```

> [!Tip]  
> [**Flex ディレクトリ**](../02.directory/) でできることを、見てみてください。

## getObject()

`getObject( id, directory ): Object | null` オブジェクトを取得。  
見つからない場合は null を返します。

パラメータ：

- **id** オブジェクトの ID (`string`)
- **directory** ディレクトリ名 (`string`)

返り値：

- **[Object](../04.object/)** (`object`)
- `null` 見つからなかった場合

```twig
{% set flex = grav.get('flex') %}

{% set contact = flex.object('ki2ts4cbivggmtlj', 'contacts') %}

{# Do something #}
{% if contact %}
  {# Got Bruce Day #}
  {{ contact.first_name|e }} {{ contact.last_name|e }} has a website: {{ contact.website|e }}
{% else %}
  Oops, contact has been removed!
{% endif %}
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexInterface;
use Grav\Framework\Flex\Interfaces\FlexObjectInterface;

/** @var FlexInterface $flex */
$flex = Grav::instance()->get('flex');

/** @var FlexObjectInterface|null $object */
$object = $flex->getObject('ki2ts4cbivggmtlj', 'contacts');
if ($object) {
    // Object exists, do something with it...
}
```

> [!Tip]  
> [**Flex オブジェクト**](../04.object/) でできることを、見てみてください。

## getCollection()

`getCollection( directory ): Collection | null` コレクションを取得。  
見つからない場合は null を返します。

パラメータ：

- `directory` ディレクトリ名 (`string`)

返り値：

- **[Collection](../03.collection/)** (`object`)
- `null` 見つからない場合

```twig
{% set flex = grav.get('flex') %}

{% set contacts = flex.collection('contacts') %}

{# Do something #}
<h2>Ten random contacts:</h2>
<ul>
  {% for contact in contacts.filterBy({published: true}).shuffle().limit(0, 10) %}
    <li>{{ contact.first_name|e }} {{ contact.last_name|e }}</li>
  {% endfor %}
</ul>
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;
use Grav\Framework\Flex\Interfaces\FlexInterface;

/** @var FlexInterface $flex */
$flex = Grav::instance()->get('flex');

/** @var FlexCollectionInterface|null $collection */
$collection = $flex->getCollection('contacts');
if ($collection) {
    // Collection exists, do something with it...
}
```

> [!Tip]  
> [**Flex コレクション**](../03.collection/) でできることを、見てみてください。

