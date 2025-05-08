---
title: "Flex"
layout: ../../../../../layouts/Default.astro
---

> [!Note]  
> **TIP:** メソッドの完全な一覧は、 **Customizing Flex Objects** セクションで解説します。

## count()

`count(): int`   
Flex に登録されたディレクトリの数を数えます。

返り値：
- `int` **[ディレクトリ](../02.directory/)** の数

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

`getDirectories( [names] ): array` Get list of directories.

パラメータ：
- **names** Optional: List of directory names (`array`)

返り値：
- `array` list of **[Directories](/advanced/flex/using/directory)**

> [!Note]  
> **TIP:** 名前のリストが渡されなかった場合、メソッドは Flex に登録されたすべてのディレクトリを返します。



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



> [!Note]  
> **TIP:** You may want to make sure you return only the directories you want to.

## hasDirectory()

`hasDirectory( name ): bool`: Check if directory exists.

パラメータ：
- **name** Name of the directory (`string`)

返り値：
- `bool` True if found, false otherwise



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

`getDirectory( name ): Directory | null` Get a directory, returns null if it was not found.

パラメータ：
- **name** Name of the directory (`string`)

返り値：
- **[Directory](/advanced/flex/using/directory)** (`object`)
- `null` Directory not found



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



!!! Check what you can do with **[Flex Directory](/advanced/flex/using/directory)**

## getObject()

`getObject( id, directory ): Object | null` Get an object, returns null if it was not found.

パラメータ：
- **id** ID of the object (`string`)
- **directory** Name of the directory (`string`)

返り値：
- **[Object](/advanced/flex/using/object)** (`object`)
- `null` Object not found



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



!!! Check what you can do with **[Flex Object](/advanced/flex/using/object)**

## getCollection()

`getCollection( directory ): Collection | null` Get collection, returns null if it was not found.

パラメータ：
- `directory` Name of the directory (`string`)

返り値：
- **[Collection](/advanced/flex/using/collection)** (`object`)
- `null` Directory not found

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

!!! Check what you can do with **[Flex Collection](/advanced/flex/using/collection)**

