---
title: "Flex コレクション"
layout: ../../../../../layouts/Default.astro
---

**Flex コレクション** とは、**flex オブジェクトの順序付きマップ** であり、リストのように使うこともできます。

flex コレクションにより、いくつかの便利なメソッドが使えるようになります。出力をレンダリングするときに使われるメソッドや、オブジェクトを fetch するメソッド、並び替えるメソッド、などです。

> [!Note]  
> **TIP:** Flex コレクションは、 **[Doctrine Collections](https://www.doctrine-project.org/projects/doctrine-collections/en/1.6/index.html)** を拡張しています。

# Render Collection

## render()

`render( [layout], [context] ): Block` コレクションをレンダリングする

パラメータ：
- **layout** レイアウト名 (`string`)
- **context** Extra variables that can be used inside the Twig template file (`array`)

返り値：
- **Block** (`object`) HtmlBlock class containing the output

> [!Info]  
> **NOTE:** このメソッドを直接呼び出すのではなく、twig の `{% render %}` タグを使ってください。これにより、 flex コレクションの JS/CSS アセットが適切に機能します。

```twig
{% set contacts = grav.get('flex').collection('contacts') %}
{% set page = 2 %}
{% set limit = 10 %}
{% set start = (page - 1) * limit %}

<h2>Contacts:</h2>

{% render contacts.limit(start, limit) layout: 'cards' with { background: 'gray', color: 'white' } %}
```

```php
use Grav\Common\Grav;
use Grav\Framework\ContentBlock\HtmlBlock;
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;

$page = 2;
$limit = 10;
$start = ($page-1)*$limit;

/** @var FlexCollectionInterface|null $collection */
$collection = Grav::instance()->get('flex')->getCollection('contacts');
if ($collection) {

    $collection = $collection->limit($start, $limit);

    /** @var HtmlBlock $block */
    $block = $collection->render('cards', ['background' =>'gray', 'color' => 'white']);

}
```

# Collection Manipulation

これらのメソッドはすべて、 flex コレクションの **修正されたコピー** を返します。オリジナルの  flex コレクションは、変更されないままです。

## sort()

`sort( orderings ): Collection` Sort the collection by list of `property: direction` pairs.

パラメータ：
- **orderings** Pairs of `property: direction` where direction is either 'ASC' or 'DESC' (`array`)

返り値：
- **[Collection](/advanced/flex/using/collection)** (`object`) New sorted instance of the collection

> [!Note]  
> **TIP:** Default sort order can be set for the frontend in the **Flex Type** blueprints.

```twig
{% set contacts = grav.get('flex').collection('contacts') %}

{% set contacts = contacts.sort({last_name: 'ASC', first_name: 'ASC'}) %}

<div>Displaying all contacts in alphabetical order:</div>
{% render contacts layout: 'cards' %}
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;

/** @var FlexCollectionInterface|null $collection */
$collection = Grav::instance()->get('flex')->getCollection('contacts');
if ($collection) {

    $collection = $collection->sort(['last_name' => 'ASC', 'first_name' => 'ASC']);
    // Collection has now be sorted by last name, first name...

}
```

## limit()

`limit( start, limit ): Collection` Return subset of collection starting from `start` and including only `limit` number of objects.

パラメータ：
- **start** Start index starting from 0 (`int`)
- **limit** Maximum number of objects (`int`)

返り値：
- **[Collection](/advanced/flex/using/collection)** (`object`) New filtered instance of the collection

```twig
{% set contacts = grav.get('flex').collection('contacts') %}
{% set page = 3 %}
{% set limit = 6 %}
{% set start = (page - 1) * limit %}

{% set contacts = contacts.limit(start, limit) %}

<div>Displaying page {{ page|e }}:</div>
{% render contacts layout: 'cards' %}
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;

$start = 0;
$limit = 6;

/** @var FlexCollectionInterface|null $collection */
$collection = Grav::instance()->get('flex')->getCollection('contacts');
if ($collection) {

    $collection = $collection->limit($start, $limit);
    // Collection contains only the objects in the current page...

}
```

## filterBy()

`filterBy( filters ): Collection` Filter collection by list of `property: value` pairs.

パラメータ：
- **filters** Pairs of `property: value` which are used to filter he collection (`array`)

返り値：
- **[Collection](/advanced/flex/using/collection)** (`object`) New filtered instance of the collection

! **TIP:** Default filtering can be set for the frontend in the **Flex Type** blueprints.

```twig
{% set contacts = grav.get('flex').collection('contacts') %}

{% set contacts = contacts.filterBy({'published': true}) %}

<div>Displaying only published contacts:</div>
{% render contacts layout: 'cards' %}
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;

$start = 0;
$limit = 6;

/** @var FlexCollectionInterface|null $collection */
$collection = Grav::instance()->get('flex')->getCollection('contacts');
if ($collection) {

    $collection = $collection->filterBy(['published' => true]);
    // Collection contains only published objects...

}
```

## reverse()

`reverse(): Collection`  Reverse the order of the objects in the Collection.

返り値：
- **[Collection](/advanced/flex/using/collection)** (`object`) New reversed instance of the collection

! **TIP:** If you're using `sort()`, it is recommended to reverse the ordering in there as it saves an extra step.

```twig
{% set contacts = grav.get('flex').collection('contacts') %}

{% set contacts = contacts.reverse() %}

<div>Displaying contacts in reverse ordering:</div>
{% render contacts layout: 'cards' %}
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;

$start = 0;
$limit = 6;

/** @var FlexCollectionInterface|null $collection */
$collection = Grav::instance()->get('flex')->getCollection('contacts');
if ($collection) {

    $collection = $collection->reverse();
    // Collection is now in reverse ordering...

}
```

## shuffle()

`shuffle(): Collection`  Shuffle objects to a random order.

返り値：
- **[Collection](/advanced/flex/using/collection)** (`object`) New randomly ordered instance of the collection

```twig
{% set contacts = grav.get('flex').collection('contacts') %}

{% set contacts = contacts.shuffle().limit(0, 6) %}

<div>Displaying 6 random contacts:</div>
{% render contacts layout: 'cards' %}
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;

/** @var FlexCollectionInterface|null $collection */
$collection = Grav::instance()->get('flex')->getCollection('contacts');
if ($collection) {

    $collection = $collection->shuffle()->limit(0,6);
    // Collection contains 6 random contacts...

}
```

## select()

`select( keys ): Collection` Select objects (by their keys) from the collection.

パラメータ：
- **keys** List of object keys used to select the objects (`array`)

返り値：
- **[Collection](/advanced/flex/using/collection)** (`object`) New instance of the collection

```twig
{% set contacts = grav.get('flex').collection('contacts') %}
{% set selected = ['gizwsvkyo5xtms2s', 'gjmva53uoncdo4sb', 'mfzwwtcugv5hkocd', 'k5nfctkeoftwi4zu'] %}

{% set contacts = contacts.select(selected) %}

<div>Displaying 4 selected contacts:</div>
{% render contacts layout: 'cards' %}
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;

$selected = ['gizwsvkyo5xtms2s', 'gjmva53uoncdo4sb', 'mfzwwtcugv5hkocd', 'k5nfctkeoftwi4zu'];

/** @var FlexCollectionInterface|null $collection */
$collection = Grav::instance()->get('flex')->getCollection('contacts');
if ($collection) {

    $collection = $collection->select($selected);
    // Collection now contains the 4 selected contacts...

}
```

## unselect()

`unselect( keys ): Collection` Remove objects (by their keys) from the collection.

パラメータ：
- **keys** List of object keys used to remove the objects (`array`)

返り値：
- **[Collection](/advanced/flex/using/collection)** (`object`) New instance of the collection

```twig
{% set contacts = grav.get('flex').collection('contacts') %}
{% set ignore = ['gizwsvkyo5xtms2s', 'gjmva53uoncdo4sb', 'mfzwwtcugv5hkocd', 'k5nfctkeoftwi4zu'] %}

{% set contacts = contacts.unselect(ignore) %}

<div>Displaying all but 4 ignored contacts:</div>
{% render contacts layout: 'cards' %}
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;

$ignore = ['gizwsvkyo5xtms2s', 'gjmva53uoncdo4sb', 'mfzwwtcugv5hkocd', 'k5nfctkeoftwi4zu'];

/** @var FlexCollectionInterface|null $collection */
$collection = Grav::instance()->get('flex')->getCollection('contacts');
if ($collection) {

    $collection = $collection->unselect($ignore);
    // Collection now contains all but 4 ignored contacts...

}
```

## search()

`search( string, [properties], [options] ): Collection` Search a string from the collection.

パラメータ：
- **string** Search string (`string`)
- **properties** Properties to search, if null (or not provided), use defaults (`array` or `null`)
- **options** Extra options used while searching (`array`)
  - starts_with: `bool`
  - ends_with: `bool`
  - contains: `bool`
  - case_sensitive: `bool`

返り値：
- **[Collection](/advanced/flex/using/collection)** (`object`) New filtered instance of the collection

```twig
{% set contacts = grav.get('flex').collection('contacts') %}

{% set contacts = contacts.search('Jack', ['first_name', 'last_name', 'email'], {'contains': true}) %}

<div>Displaying all search results for 'Jack':</div>
{% render contacts layout: 'cards' %}
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;

/** @var FlexCollectionInterface|null $collection */
$collection = Grav::instance()->get('flex')->getCollection('contacts');
if ($collection) {

    $collection = $collection->search('Jack', ['first_name', 'last_name', 'email'], ['contains' => true]);
    // Collection now contains all search results...

}
```

## copy()

`copy(): Collection` Create a copy from the collection by cloning all the objects in the collection.

返り値：
- **[Collection](/advanced/flex/using/collection)** (`object`) New instance of the collection, now with cloned objects

!! **WARNING:** If you modify objects in your collection, you should always use copies!

```twig
{% set contacts = grav.get('flex').collection('contacts') %}

{% set contacts = contacts.shuffle().limit(0, 10) %}
{% set fakes = contacts.copy() %}

{% do fakes.setProperty('first_name', 'JACK') %}

<h2>Fake cards</h2>
{% render fakes layout: 'cards' %}

<h2>Original cards</h2>
{% render contacts layout: 'cards' %}
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;

/** @var FlexCollectionInterface|null $collection */
$collection = Grav::instance()->get('flex')->getCollection('contacts');
if ($collection) {

    $collection = $collection->search('Jack', ['first_name', 'last_name', 'email'], ['contains' => true]);
    // Collection now contains all search results...

}
```

# Iterate Through Collection

**Collections** can be iterated over.

```twig
{% set contacts = grav.get('flex').collection('contacts') %}

<h2>All contacts:</h2>
<ul>
  {% for contact in contacts %}
    <li>{{ contact.first_name|e }} {{ contact.last_name|e }}</li>
  {% endfor %}
</ul>
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;
use Grav\Framework\Flex\Interfaces\FlexObjectInterface;

/** @var FlexCollectionInterface|null $collection */
$collection = Grav::instance()->get('flex')->getCollection('contacts');
if ($collection) {

    /** @var FlexObjectInterface $object */
    foreach ($collection as $object) {
        // Do something with the object...
    }

}
```

## first()

`first(): Object | false` Sets the iterator to the first object in the collection and returns this object.

返り値：
- **[Object](/advanced/flex/using/object)** (`object`) First object
- `false` No objects in the Collection

```twig
{% set contacts = grav.get('flex').collection('contacts') %}

{% set contact = contacts.first() %}

{% if contact %}
    <h2>First contact:</h2>
    <div>{{ contact.first_name|e }} {{ contact.last_name|e }}</div>
{% endif %}
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;
use Grav\Framework\Flex\Interfaces\FlexObjectInterface;

/** @var FlexCollectionInterface|null $collection */
$collection = Grav::instance()->get('flex')->getCollection('contacts');
if ($collection) {

    /** @var FlexObjectInterface|false $object */
    $object = $collection->first();
    if ($object) {
        // Do something with the object...
    }

}
```

## last()

`last(): Object | false` Sets the iterator to the last object in the collection and returns this object.

返り値：
- **[Object](/advanced/flex/using/object)** (`object`) Last object
- `false` No objects in the Collection

```twig
{% set contacts = grav.get('flex').collection('contacts') %}

{% set contact = contacts.last() %}

{% if contact %}
    <h2>Last contact:</h2>
    <div>{{ contact.first_name|e }} {{ contact.last_name|e }}</div>
{% endif %}
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;
use Grav\Framework\Flex\Interfaces\FlexObjectInterface;

/** @var FlexCollectionInterface|null $collection */
$collection = Grav::instance()->get('flex')->getCollection('contacts');
if ($collection) {

    /** @var FlexObjectInterface|false $object */
    $object = $collection->last();
    if ($object) {
        // Do something with the object...
    }

}
```

## next()

`next(): object | false` Moves the iterator position to the next object and returns this element.

返り値：
- **[Object](/advanced/flex/using/object)** (`object`) Next object
- `false` No more objects in the Collection

```twig
{% set contacts = grav.get('flex').collection('contacts') %}
{% set first = contacts.first() %}
...

{% set contact = contacts.next() %}

{% if contact %}
    <h2>Next contact is:</h2>
    <div>{{ contact.first_name|e }} {{ contact.last_name|e }}</div>
{% endif %}
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;
use Grav\Framework\Flex\Interfaces\FlexObjectInterface;

/** @var FlexCollectionInterface|null $collection */
$collection = Grav::instance()->get('flex')->getCollection('contacts');
if ($collection) {

    /** @var FlexObjectInterface|false $object */
    while ($object = $collection->next()) {
        // Do something with the object...
    }

}
```

## current()

`current(): object | false` Gets the object of the collection at the current iterator position.

返り値：
- **[Object](/advanced/flex/using/object)** (`object`) Current object
- `false` No more objects in the Collection

```twig
{% set contacts = grav.get('flex').collection('contacts') %}
{% do contacts.next() %}
{% do contacts.next() %}
...

{% set contact = contacts.current() %}

{% if contact %}
    <h2>Current contact is:</h2>
    <div>{{ contact.first_name|e }} {{ contact.last_name|e }}</div>
{% endif %}
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;
use Grav\Framework\Flex\Interfaces\FlexObjectInterface;

/** @var FlexCollectionInterface|null $collection */
$collection = Grav::instance()->get('flex')->getCollection('contacts');
if ($collection) {
    while ($collection->next()) {

        /** @var FlexObjectInterface|false $object */
        $object = $collection->current();
        // Do something with the object...

    }
}
```

## key()

`key(): key | null` Gets the key of the object at the current iterator position.

返り値：
- **key** (`string`) Object key
- `null` No more objects in the Collection

```twig
{% set contacts = grav.get('flex').collection('contacts') %}
{% do contacts.next() %}
{% do contacts.next() %}
...

{% set key = contacts.key() %}

{% if key %}
    Current contact key is: <strong>{{ key|e }}</strong>
{% endif %}
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;

/** @var FlexCollectionInterface|null $collection */
$collection = Grav::instance()->get('flex')->getCollection('contacts');
if ($collection) {
    while ($collection->next()) {

        $key = $collection->key();
        // Do something with the key...

    }
}
```

# Get Object / Key

## Array Access

**Collections** can be accessed just like associative arrays or maps.

!!! **NOTE:** `null` is being returned if object with given key is not in the collection.

```twig
{% set contacts = grav.get('flex').collection('contacts') %}

{% set contact = contacts['ki2ts4cbivggmtlj']

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
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;
use Grav\Framework\Flex\Interfaces\FlexObjectInterface;

/** @var FlexCollectionInterface|null $collection */
$collection = Grav::instance()->get('flex')->getCollection('contacts');
if ($collection) {

    /** @var FlexObjectInterface|null $object */
    $object = $collection['ki2ts4cbivggmtlj'];
    if ($object) {
        // Object exists, do something with it...
    }

}
```

## get()

`get( key ): Object | null` Gets the object with the specified key.

パラメータ：
- **key**  Object key (`string`)

返り値：
- **Object** (`object`)
- `null` Object with given key is not in the collection

Alternative to [Array Access](#array-access)

```twig
{% set contacts = grav.get('flex').collection('contacts') %}

{% set contact = contacts.get('ki2ts4cbivggmtlj')

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
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;
use Grav\Framework\Flex\Interfaces\FlexObjectInterface;

/** @var FlexCollectionInterface|null $collection */
$collection = Grav::instance()->get('flex')->getCollection('contacts');
if ($collection) {

    /** @var FlexObjectInterface|null $object */
    $object = $collection->get('ki2ts4cbivggmtlj');
    if ($object) {
        // Object exists, do something with it...
    }

}
```

# Collection as Array

## getKeys()

`getKeys(): array` Gets all keys of the collection.

返り値：
- `array` List of keys

```twig
{% set contacts = grav.get('flex').collection('contacts') %}

{% set keys = contacts.keys() %}

Keys are: {{ keys|join(', ')|e }}
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;

/** @var FlexCollectionInterface|null $collection */
$collection = Grav::instance()->get('flex')->getCollection('contacts');
if ($collection) {

    /** @var string[] $keys */
    $keys = $collection->getKeys();
    $keysList = implode(', ', $keys);

}
```

## GetObjectKeys()

`GetObjectKeys(): array` Alias to the `getKeys()` method.

返り値：
- `array` List of keys

## getValues()

`getValues(): array` Gets all objects of the collection.

Converts collection into an array. Keys are not preserved.

返り値：
- List of **Objects** (`array`)

```twig
{% set contacts = grav.get('flex').collection('contacts') %}

{% set list = contacts.values() %}
<ol>
{% for i,object in list %}
    <li>#{{ (i+1)|e }}: {{ object.email|e }}</li>
{% endfor %}
</ol>
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;
use Grav\Framework\Flex\Interfaces\FlexObjectInterface;

/** @var FlexCollectionInterface|null $collection */
$collection = Grav::instance()->get('flex')->getCollection('contacts');
if ($collection) {

    /** @var FlexObjectInterface[] $objects */
    $objects = $collection->getValues();
    foreach ($objects as $pos => $object) {
        // Do something with the object and its position...
    }

}
```

## toArray()

`toArray(): array` Gets a native PHP array representation of the collection.

Similar to `getValues()` but preserves the keys.

返り値：
- `array` List of `key: Object` pairs

```twig
{% set contacts = grav.get('flex').collection('contacts') %}

{% set list = contacts.toArray() %}
<ol>
{% for key,object in list %}
    <li>ID: {{ key|e }}: {{ object.email|e }}</li>
{% endfor %}
</ol>
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;
use Grav\Framework\Flex\Interfaces\FlexObjectInterface;

/** @var FlexCollectionInterface|null $collection */
$collection = Grav::instance()->get('flex')->getCollection('contacts');
if ($collection) {

    /** @var array<string, FlexObjectInterface> $objects */
    $objects = $collection->toArray();
    foreach ($objects as $key => $object) {
        // Do something with the object and its key...
    }

}
```

## slice()

`slice( offset, length ): array` Extracts a slice of `length` elements starting at position `offset` from the Collection.

パラメータ：
- **offset** Start offset starting from 0 (`int`)
- **length** Maximum number of objects (`int`)

返り値：
- `array` List of `key: Object` pairs

! **TIP:** This method can be used for pagination.

```twig
{% set contacts = grav.get('flex').collection('contacts') %}

{% set list = contacts.slice(10, 5) %}

<div>Displaying 5 emails starting from offset 10:</div>
<ol>
{% for key,object in list %}
    <li>ID: {{ key|e }}: {{ object.email|e }}</li>
{% endfor %}
</ol>
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;
use Grav\Framework\Flex\Interfaces\FlexObjectInterface;

/** @var FlexCollectionInterface|null $collection */
$collection = Grav::instance()->get('flex')->getCollection('contacts');
if ($collection) {

    /** @var array<string, FlexObjectInterface> $objects */
    $objects = $collection->slice(10, 5);

    // Do something with the object and its key...

}
```

## chunk()

`chunk( size ): array` Split collection into chunks of `size` objects.

パラメータ：
- **size** Size of the chunks (`int`)

返り値：
- `array` Two dimensional list of `key: Object` pairs

! **TIP:** This method can be used to split content into rows and columns.

```twig
{% set contacts = grav.get('flex').collection('contacts') %}

{% set columns = contacts.limit(0, 10).chunk(5) %}

<div>Displaying two columns of 5 emails each:</div>
<div class="columns">
{% for column,list in columns %}
    <div class="column">
    {% for object in list %}
        <div>{{ object.email|e }}</div>
    {% endfor %}
    </div>
{% endfor %}
</div>
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;
use Grav\Framework\Flex\Interfaces\FlexObjectInterface;

/** @var FlexCollectionInterface|null $collection */
$collection = Grav::instance()->get('flex')->getCollection('contacts');
if ($collection) {

    /** @var array $columns */
    $columns = $collection->limit(0, 10)->chunk(5);
    /** @var
        int $column
        array<string, FlexObjectInterface> $objects
    */
    foreach ($columns as $column => $objects) {
        // Do something with the objects...
    }
}

}
```

## group()

`group( property ): array` Group objects in the collection by a property and return them as associated array.

パラメータ：
- **property** Property name which is used to group the objects (`string`)

返り値：
- `array` Two dimensional list of `key: Object` pairs, where value of the property is the key of the first level

```twig
{% set contacts = grav.get('flex').collection('contacts') %}

{% set by_name = contacts.sort({last_name: 'ASC', first_name: 'ASC'}).group('last_name') %}

<div>Displaying contacts grouped by last name:</div>
<div>
{% for last_name,list in by_name %}
    {{ last_name|e }}:
    <ul>
    {% for object in list %}
        <li>{{ object.first_name|e }}</li>
    {% endfor %}
    </ul>
{% endfor %}
</div>
```

```php
use Grav\Common\Grav;
use Grav\Framework\Flex\Interfaces\FlexCollectionInterface;
use Grav\Framework\Flex\Interfaces\FlexObjectInterface;

/** @var FlexCollectionInterface|null $collection */
$collection = Grav::instance()->get('flex')->getCollection('contacts');
if ($collection) {

    /** @var array $byName */
    $byName = $collection->group('last_name');
    /** @var
        string $lastName
        array<string, FlexObjectInterface> $objects
    */
    foreach ($byName as $lastName => $objects) {
        // Do something with the objects...
    }
}

}
```

# Adding and Removing Objects

## add()

`add( Object )` Adds an Object at the end of the collection.

パラメータ：
- **[Object](/advanced/flex/using/object)** Object to be added (`object`)

## remove()

`remove( key ): Object | null` Removes the element at the specified index from the collection.

パラメータ：
- **key** Object key to be removed (`object`)

返り値：
- **[Object](/advanced/flex/using/object)** Removed object (`object`) or `null` if it was not found

## removeElement()

`removeElement( Object ): bool` Removes the specified object from the collection, if it is found.

パラメータ：
- **[Object](/advanced/flex/using/object)** Object to be removed (`object`)

返り値：
- `true` if object was in the collection, `false` if not

## clear()

`clear()` Clears the collection, removing all elements.

# Tests

## containsKey()

`containsKey( key ): bool` Checks whether the collection contains an object with the specified key.

パラメータ：
- **key** Key to be tested (`string`)

返り値：
- `true` if object is in the collection, `false` if not

## contains()

`contains( object ): bool` Checks whether an element is contained in the collection.

パラメータ：
- **[Object](/advanced/flex/using/object)** Object to be tested (`object`)

返り値：
- `true` if object is in the collection, `false` if not

## indexOf()

`indexOf( object ): string | false` Gets the index/key of a given object.

パラメータ：
- **[Object](/advanced/flex/using/object)** Object to be tested (`object`)

返り値：
- `string` index/key of the object, `false` if not object was not found

## isEmpty()

`isEmpty(): bool` Checks whether the collection is empty (contains no objects).

返り値：
- `true` if collection is empty, `false` otherwise

## count()

`count(): int`

返り値：
- `int` Number of objects in the collection

# Bulk Actions for Objects

## hasProperty()

`hasProperty( property ): array` Returns a list of `key: boolean` pairs whether object with key has property defined or not.

パラメータ：
- **property** Property name (`string`)

返り値：
- `array` of `key: bool` pairs, where `key` is the object key and `bool` is either true or false.

## getProperty()

`getProperty( property, default ): array` Returns a list of `key: value` for each object.

パラメータ：
- **property** Property name (`string`)

返り値：
- `array` of `key: value` pairs, where `key` is the object key and `value` is the value of the property.

## setProperty()

`setProperty( property, value ): Collection` Set new value to the property for every object in the collection.

パラメータ：
- **property** Property name (`string`)
- **value** New value (`mixed`)

返り値：
- **Collection** (`object`) The collection for chaining the method calls.

!! **WARNING:** This method modifies the object instances shared between all the collections, if that is not intended, please  [copy()](#copy) collection before using this method.

## defProperty()

`defProperty( property, default ): Collection` Define default value to the property for every object in the collection.

パラメータ：
- **property** Property name (`string`)
- **default** Default value (`mixed`)

返り値：
- **Collection** (`object`) The collection for chaining the method calls.

!! **WARNING:** This method modifies the object instances shared between all the collections, if that is not intended, please  [copy()](#copy) collection before using this method.

## unsetProperty()

`unsetProperty( property ): Collection` Remove value of the property for every object in the collection.

パラメータ：
- **property** Property name (`string`)

返り値：
- **Collection** (`object`) The collection for chaining the method calls.

!! **WARNING:** This method modifies the object instances shared between all the collections, if that is not intended, please  [copy()](#copy) collection before using this method.

## call()

`call( method, arguments): array` Calls a method for every object in the collection. Return results of each call.

パラメータ：
- **method** Method name (`string`)
- **arguments** List of arguments (`array`)

返り値：
- List of `key: result` pairs (`array`)

!! **WARNING:** If the method modifies the object, please  [copy()](#copy) collection before using this method.

## getTimestamps()

`getTimestamps(): array`  Returns a list of `key: timestamp` for each object.

返り値：
- List of `key: timestamp` pairs, where timestamp is integer (`array`)

## getStorageKeys()

`getStorageKeys(): array`  Returns a list of `key: storage_key` for each object.

返り値：
- List of `key: storage_key` pairs (`array`)

## getFlexKeys()

`getFlexKeys(): array`  Returns a list of `key: flex_key` for each object.

返り値：
- List of `key: flex_key` pairs (`array`)

## withKeyField()

`withKeyField( field ): Collection` Return new collection with a different key.

パラメータ：
- **field** Key field (`string`)
  - 'key': Default key used by the directory
  - 'storage_key': Storage layer key
  - 'flex_key': Unique key which can be used without knowing the directory

返り値：
- **Collection** (`object`) The collection, but indexed with the new key.

# Closure Tests (PHP only)

## exists()

`exists( Closure ): bool` Tests for the existence of an object that satisfies the given predicate.

パラメータ：
- **Closure** Method used to test each object.

返り値：
- `bool` True if your callback function returns true for any object.

## forAll()

`forAll( Closure ): bool` Tests whether the given predicate holds for all objects of this collection.

パラメータ：
- **Closure** Method used to test each object.

返り値：
- `bool` True if your callback function returns true for all objects.

# Closure Filtering (PHP only)

## filter()

`filter( Closure ): Collection` Returns all the objects of this collection that satisfy the predicate.

The order of the elements is preserved.

パラメータ：
- **Closure** Method used to test a single object.

返り値：
- **Collection** (`object`) New collection with all the objects for which your callback function returns `true`.

## map()

`map( Closure ): Collection` Applies the given function to each object in the collection and returns a new collection with the objects returned by the function.

パラメータ：
- **Closure** Method used to test a single object.

返り値：
- **Collection** (`object`) New collection with objects returned by the callback function.


## collectionGroup()

`collectionGroup( property ): Collection[]` Group objects in the collection by a field and return them as associated array of collections.

パラメータ：
- **property** (`string`) Property used to group the objects.

返り値：
- `array` Multiple collections in an array, key being the value of the property.

## matching()

`matching( Criteria ): Collection` Selects all objects that match the expression and returns a new collection containing these objects.

パラメータ：
- **[Criteria](https://www.doctrine-project.org/projects/doctrine-collections/en/1.6/expression-builder.html#expression-builder)** Expression

返り値：
- **Collection** (`object`) New collection with objects matching the criteria.

! **TIP:** Check Doctrine documentation for **[Expression Builder](https://www.doctrine-project.org/projects/doctrine-collections/en/1.6/expression-builder.html#expression-builder)** and** [Expressions](https://www.doctrine-project.org/projects/doctrine-collections/en/1.6/expressions.html#expressions)**.

## orderBy()

`orderBy( array ): Collection` Reorder collection by list of property/value pairs.

パラメータ：
- `array`

返り値：
- **Collection** (`object`) New collection with the new ordering.

## partition()

`partition( Closure ): array` Partitions this collection in two collections according to a predicate.

Keys are preserved in the resulting collections.

パラメータ：
- **Closure** Method used to partition a single object. Returns true or false.

返り値：
- `array` Partitioned objects `[[a, b], [c, d, e]]`
