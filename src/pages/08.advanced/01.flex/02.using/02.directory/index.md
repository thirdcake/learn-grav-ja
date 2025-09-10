---
title: 'Flex ディレクトリ'
layout: ../../../../../layouts/Default.astro
lastmod: '2025-09-06'
---

## getTitle()

`getTitle(): string` flex ディレクトリの名前を取得

返り値：

- `string` タイトル

```twig
{% set directory = grav.get('flex').directory('contacts') %}

<h2>{{ directory.title|e }}</h2>
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexDirectoryInterface;

/** @var FlexDirectoryInterface|null $directory */
$directory = Grav::instance()->get('flex')->getDirectory('contacts');
if ($directory) {

    /** @var string $title */
    $title = $directory->getTitle();

}
```

## getDescription()

`getDescription(): string` flex ディレクトリの説明を取得

返り値：

- `string` 説明

```twig
{% set directory = grav.get('flex').directory('contacts') %}

<p>{{ directory.description|e }}</p>
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexDirectoryInterface;

/** @var FlexDirectoryInterface|null $directory */
$directory = Grav::instance()->get('flex')->getDirectory('contacts');
if ($directory) {

    /** @var string $title */
    $description = $directory->getDescription();

}
```

## getObject()

`getObject( id ): Object | null` flex オブジェクトを取得。もし見つからなければ null を返す

パラメータ：
- **id** flex オブジェクトの ID (`string`)

返り値：
- **[Object](../04.object/)** (`object`)
- `null` オブジェクトが見つからなかったとき

```twig
{% set directory = grav.get('flex').directory('contacts') %}

{% set contact = directory.object('ki2ts4cbivggmtlj') %}

{# Do something #}
{% if contact %}
  {# Got Bruce Day #}
  Email for {{ contact.first_name|e }} {{ contact.last_name|e }} is {{ contact.email|e }}
{% else %}
  Oops, contact has been removed!
{% endif %}
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexDirectoryInterface;
use Grav\Framework\Flex\Interfaces\FlexObjectInterface;

/** @var FlexDirectoryInterface|null $directory */
$directory = Grav::instance()->get('flex')->getDirectory('contacts');
if ($directory) {

    /** @var FlexObjectInterface|null $object */
    $object = $directory->getObject('ki2ts4cbivggmtlj');
    if ($object) {
        // Object exists, do something with it...
    }

}
```

> [!Info]  
>  **[Flex オブジェクト](../04.object/)** でできることをチェックしてください

## getCollection()

`getCollection(): Collection` flex コレクションを取得。もし見つからなければ null を返す

返り値：
- **[Collection](../03.collection/)** (`object`)

```twig
{% set directory = grav.get('flex').directory('contacts') %}

{% set contacts = directory.collection() %}

{# Do something #}
<h2>Ten first contacts:</h2>
<ul>
  {% for contact in contacts.filterBy({published: true}).limit(0, 10) %}
    <li>{{ contact.first_name|e }} {{ contact.last_name|e }}</li>
  {% endfor %}
</ul>
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexDirectoryInterface;
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;

/** @var FlexDirectoryInterface|null $directory */
$directory = Grav::instance()->get('flex')->getDirectory('contacts');
if ($directory) {

    /** @var FlexCollectionInterface $collection */
    $collection = $directory->getCollection();

    // Do something with the collection...

}
```

> [!Info]  
> [Flex コレクション](../03.collection/) でできることをチェックしてください

