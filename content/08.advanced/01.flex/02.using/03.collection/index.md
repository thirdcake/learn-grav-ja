---
title: 'Flex コレクション'
layout: ../../../../../layouts/Default.astro
lastmod: '2025-09-11'
description: 'twig や、プラグイン中の PHP で使える、 flex collection のメソッドを解説します。'
---

**Flex コレクション** とは、**flex オブジェクトの順序付きマップ** であり、リストのように使うこともできます。

flex コレクションにより、いくつかの便利なメソッドが使えるようになります。  
出力をレンダリングするときに使われるメソッドや、オブジェクトを fetch するメソッド、並び替えるメソッド、などです。

> [!Tip]  
> Flex コレクションは、 [**Doctrine Collections**](https://www.doctrine-project.org/projects/doctrine-collections/en/1.6/index.html) を拡張しています。

<h2 id="render-collection">コレクションのレンダリング</h2>

### render()

`render( [layout], [context] ): Block` コレクションをレンダリングする

パラメータ：
- **layout** レイアウト名 (`string`)
- **context** Twig テンプレートファイル内で使うことができるその他の変数 ('array')

返り値：
- **Block** (`object`) 出力を含むHtmlBlock class

> [!Note]  
> このメソッドを直接呼び出すのではなく、twig の `{% render %}` タグを使ってください。これにより、 flex コレクションの JS/CSS アセットが適切に機能します。

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

<h2 id="collection-manipulation">コレクションの操作</h2>

これらのメソッドはすべて、 flex コレクションの **修正されたコピー** を返します。  
オリジナルの  flex コレクションは、変更されないままです。

### sort()

`sort( orderings ): Collection` コレクションを `property: direction` のペアでソートする。

パラメータ：
- **orderings** `property: direction` のペア。 direction のところは、 'ASC' もしくは 'DESC' です。 (`array`)

返り値：
- **[Collection](/advanced/flex/using/collection)** (`object`) 新しくソートされたコレクションのインスタンス。

> [!Tip]  
> デフォルトのソート順は、 **Flex Type** ブループリント内でフロントエンド向けに設定できます。

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

### limit()

`limit( start, limit ): Collection` `start` から始まり、 `limit` 個までのオブジェクトを持つコレクションの一部分を返す。

パラメータ：
- **start** 0始まりの最初のインデックス (`int`)
- **limit** オブジェクトの最大数 (`int`)

返り値：
- **Collection** (`object`) フィルタリングされたコレクションの新しいインスタンス

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

### filterBy()

`filterBy( filters ): Collection` `property: value` のペアによりフィルタリングされたコレクション

パラメータ：
- **filters** `property: value` のペア。コレクションのフィルタリングに使われます。 (`array`)

返り値：
- **Collection** (`object`) フィルタリングされたコレクションの新しいインスタンス。

> [!Tip]  
> デフォルトのフィルタリングは、 **Flex Type** ブループリント内で、フロントエンド向けに設定できます。

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

### reverse()

`reverse(): Collection` コレクションのオブジェクトを逆順にする。

返り値：
- **Collection** (`object`) 逆順にしたコレクションの新しいインスタンス。

> [!Tip]  
> `sort()` を使っている場合は、そこで逆順に設定することをおすすめします。余分なステップが省けます。

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

### shuffle()

`shuffle(): Collection` ランダムな順番でオブジェクトをシャッフルする。

返り値：
- **Collection** (`object`) コレクションの、ランダムな順番になった新しいインスタンス

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

### select()

`select( keys ): Collection` コレクションから(keys によって)オブジェクトを選択する

パラメータ：
- **keys** オブジェクトの選択に使われるキーのリスト (`array`)

返り値：
- **Collection** (`object`) コレクションの新しいインスタンス

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

### unselect()

`unselect( keys ): Collection` コレクションから、(keys によって)オブジェクトを取り除く

パラメータ：
- **keys** オブジェクトを取り除くために使われるキーのリスト (`array`)

返り値：
- **Collection** (`object`) コレクションの新しいインスタンス

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

### search()

`search( string, [properties], [options] ): Collection` コレクションで文字列を検索する。

パラメータ：
- **string** 検索する文字列 (`string`)
- **properties** 検索するプロパティ。もし null （もしくは何も渡されなかった）の場合は、デフォルトを使います。 (`array` or `null`)
- **options** 検索時に使う追加オプション  (`array`)
  - starts_with: `bool`
  - ends_with: `bool`
  - contains: `bool`
  - case_sensitive: `bool`

返り値：
- **Collection** (`object`) コレクションのフィルタリングされた新しいインスタンス

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

### copy()

`copy(): Collection` コレクション内のオブジェクトをすべて clone することで、コピーを作成する

返り値：
- **Collection** (`object`) clone されたオブジェクトを持つコレクションの新しいインスタンス

> [!Warning]  
> コレクション内でオブジェクトを修正する場合は、常にコピーを使ってください！

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

<h2 id="iterate-through-collection">コレクションの繰り返し</h2>

**Collections** は、繰り返し処理ができます。

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

### first()

`first(): Object | false` コレクションの最初のオブジェクトにイテレータを設定し、このオブジェクトを返します。

返り値：
- [**Object**](../04.object/) (`object`) 最初のオブジェクト
- `false` コレクションにオブジェクトが無かった場合

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

### last()

`last(): Object | false` コレクションの最後のオブジェクトにイテレータを設定し、このオブジェクトを返します。

返り値：
- [**Object**](../04.object/) (`object`) 最後のオブジェクト
- `false` コレクションにオブジェクトが無かった場合

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

### next()

`next(): object | false` イテレータのポジションを次のオブジェクトに移し、この要素を返す。

返り値：
- [**Object**](../04.object/) (`object`) 次のオブジェクト
- `false` コレクションに、これ以上のオブジェクトが無かった場合

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

### current()

`current(): object | false` 現在のイテレータのポジションにあるオブジェクトを取得する。

返り値：
- [**Object**](../04.object/)** (`object`) 現在のオブジェクト
- `false` これ以上コレクションにオブジェクトが無い場合

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

### key()

`key(): key | null` 現在のイテレータのポジションがあるオブジェクトのキーを取得

返り値：
- **key** (`string`) オブジェクトのキー
- `null` コレクションにこれ以上オブジェクトが無い場合

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

<h2 id="get-object-key">オブジェクト / キーの取得</h2>

<h3 id="array-access">配列のようにアクセス</h3>

**Collections** は、連想配列型や map 型のようにアクセス可能です。

> [!Note]  
> 与えられたキーのオブジェクトがコレクションに無かった場合、 `null` が返ります。

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

### get()

`get( key ): Object | null` 特定のキーでオブジェクトを取得する。

パラメータ：
- **key**  オブジェクトのキー (`string`)

返り値：
- **Object** (`object`)
- `null` 与えられたキーのオブジェクトがコレクションに無かった場合

かわりに、 [配列のようにアクセス](#array-access) する方法もあります。

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

<h2 id="collection-as-array">配列のようなコレクション</h2>

### getKeys()

`getKeys(): array` コレクションのすべてのキーを取得

返り値：
- `array` キーのリスト

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

### GetObjectKeys()

`GetObjectKeys(): array` : `getKeys()` メソッドのエイリアス（別名）

返り値：
- `array` キーのリスト

### getValues()

`getValues(): array` コレクションのすべてのオブジェクトを取得する

コレクションを array 型に変換します。キーは保存されません。

返り値：
- **Objects** のリスト (`array`)

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

### toArray()

`toArray(): array` コレクションの、PHP ネイティブの array を取得します。

`getValues()` に似ていますが、キーが保存されます。

返り値：
- `array` : `key: Object` ペアのリスト

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

### slice()

`slice( offset, length ): array` コレクションから `offset` されたポジションから初めて、 `length` 個の要素をスライスしたものを抽出する。

パラメータ：
- **offset** 0始まりのオフセットの最初 (`int`)
- **length** オブジェクトの最大数 (`int`)

返り値：
- `array` : `key: Object` ペアのリスト

> [!Tip]  
> このメソッドは、ページネーションに使えます。

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

### chunk()

`chunk( size ): array` コレクションを、 `size` 個のオブジェクトに分ける

パラメータ：
- **size** チャンクするサイズ (`int`)

返り値：
- `array` : `key: Object` ペアの2次元リスト

> [!Tip]  
> このメソッドは、コンテンツを行と列に分けるときに利用できます。

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

`group( property ): array` プロパティでコレクション内のオブジェクトをグループ分けし、連想配列として返す。

パラメータ：
- **property** オブジェクトのグループ分けに使われるプロパティ名。 (`string`)

返り値：
- `array` 2次元の `key: Object` ペアのリスト。プロパティ値は最初のレベルのキーになります。

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

<h2 id="adding-and-removing-objects">オブジェクトを追加・削除</h2>

### add()

`add( Object )` コレクションの最後にオブジェクトを追加する。

パラメータ：
- [**Object**](../04.object/) 追加するオブジェクト (`object`)

### remove()

`remove( key ): Object | null` コレクションから、特定のインデックスを持つ要素を削除する。

パラメータ：
- **key** 削除するオブジェクトのキー。 (`object`)

返り値：
- [**Object**](../04.object/) 削除されたオブジェクト (`object`) もしくは、見つからなければ `null`

### removeElement()

`removeElement( Object ): bool` 特定のオブジェクトについて、それがコレクション内に見つかった場合に削除する

パラメータ：
- [**Object**](../04.object/) 削除するオブジェクト (`object`)

返り値：
- `true` そのオブジェクトがコレクション内似合った場合。 `false` そうでない場合。

### clear()

`clear()` コレクションをクリアし、すべての要素を削除する。

<h2 id="tests">テスト</h2>

### containsKey()

`containsKey( key ): bool` 特定のキーを持つオブジェクトがコレクション内にあるかどうかチェックする。

パラメータ：
- **key** テストするキー (`string`)

返り値：
- `true` コレクション内にオブジェクトがあった場合。  `false` そうでない場合。

### contains()

`contains( object ): bool` コレクション内に要素があるかどうかチェックする。

パラメータ：
- [**Object**](../04.object/) テストするオブジェクト (`object`)

返り値：
- `true` コレクション内にオブジェクトがあった場合。  `false` そうでない場合。

### indexOf()

`indexOf( object ): string | false` 与えられたオブジェクトの index/key を取得する

パラメータ：
- [**Object**](../04.object/) テストするオブジェクト (`object`)

返り値：
- `string` オブジェクトの index/key 。 `false` オブジェクトが見つからなかった場合。

### isEmpty()

`isEmpty(): bool` Checks whether the collection is empty (contains no objects).

返り値：
- `true` if collection is empty, `false` otherwise

### count()

`count(): int`

返り値：
- `int` コレクションにあるオブジェクトの数

<h2 id="bulk-actions-for-objects">オブジェクトへの一斉アクション</h2>

### hasProperty()

`hasProperty( property ): array` オブジェクトのキーにプロパティが定義されているかどうかについて、 `key: boolean` ペアのリストを返す。

パラメータ：
- **property** プロパティ名 (`string`)

返り値：
- `key: bool` ペアの配列。 `key` はオブジェクトのキー、 `bool` は true もしくは false。

### getProperty()

`getProperty( property, default ): array` 各オブジェクトについて、 `key: value` のリストを返す。

パラメータ：
- **property** プロパティ名 (`string`)

返り値：
- `key: value` ペアの配列。 `key` はオブジェクトのキー、 `value` はプロパティの値。

### setProperty()

`setProperty( property, value ): Collection` コレクション内のすべてのオブジェクトで、プロパティに新しい値を設定する。

パラメータ：
- **property** プロパティ名 (`string`)
- **value** 新しい値 (`mixed`)

返り値：
- **Collection** (`object`) メソッド呼び出しを連鎖させるためのコレクション

> [!Warning]  
> このメソッドは、すべてのコレクションで共有されているオブジェクトインスタンスを修正します。それを意図しない場合、このメソッドを使う前に、コレクションを [copy()](#copy) してください。

### defProperty()

`defProperty( property, default ): Collection` コレクション内のすべてのオブジェクトに、プロパティのデフォルト値を定義する。

パラメータ：
- **property** プロパティ名 (`string`)
- **default** デフォルト値 (`mixed`)

返り値：
- **Collection** (`object`) メソッド呼び出しを連鎖させるためのコレクション

> [!Warning]  
> このメソッドは、すべてのコレクションで共有されているオブジェクトインスタンスを修正します。それを意図しない場合、このメソッドを使う前に、コレクションを [copy()](#copy) してください。

### unsetProperty()

`unsetProperty( property ): Collection` コレクション内のすべてのオブジェクトで、そのプロパティの値を削除する。

パラメータ：
- **property** プロパティ名 (`string`)

返り値：
- **Collection** (`object`) メソッド呼び出しを連鎖させるためのコレクション

> [!Warning]  
> このメソッドは、すべてのコレクションで共有されているオブジェクトインスタンスを修正します。それを意図しない場合、このメソッドを使う前に、コレクションを [copy()](#copy) してください。

### call()

`call( method, arguments): array` コレクション内のすべてのオブジェクトで、メソッドを呼び出します。各呼び出しの結果を返します。

パラメータ：
- **method** メソッド名 (`string`)
- **arguments** 引数のリスト (`array`)

返り値：
- `key: result` ペアのリスト (`array`)

> [!Warning]  
> このメソッドがオブジェクトを修正する場合、このメソッドを使う前に、コレクションを [copy()](#copy) してください。

### getTimestamps()

`getTimestamps(): array` 各オブジェクトについて `key: timestamp` のリストを返します。

返り値：
- `key: timestamp` ペアのリスト。ここで、timestamp は整数値です。 (`array`)

## getStorageKeys()

`getStorageKeys(): array` 各オブジェクトについて、 `key: storage_key` のリストを返す。

返り値：
- `key: storage_key` ペアのリスト (`array`)

### getFlexKeys()

`getFlexKeys(): array` 各オブジェクトについて、`key: flex_key` のリストを返します。

返り値：
- `key: flex_key` ペアのリスト (`array`)

### withKeyField()

`withKeyField( field ): Collection` 異なるキーの新しいコレクションを返します。

パラメータ：
- **field** キーのフィールド (`string`)
  - 'key': ディレクトリによって使われるデフォルトキー
  - 'storage_key': ストレージ層のキー
  - 'flex_key': ディレクトリを知らなくても使えるユニークなキー

返り値：
- **Collection** (`object`) そのコレクション。ただし、新しいキーでインデックスされています。

<h2 id="closure-tests-php-only">クロージャーによるテスト( PHP のみ)</h2>

### exists()

`exists( Closure ): bool` 与えられたクロージャーを満足するオブジェクトが存在するかテストします。

パラメータ：
- **Closure** 各オブジェクトのテストに使われるメソッド。

返り値：
- `bool` コールバック関数が true を返すオブジェクトが1つでもある場合に true。

## forAll()

`forAll( Closure ): bool` コレクション内のすべてのオブジェクトでクロージャーがtrueを返すかテストします。

パラメータ：
- **Closure** 各オブジェクトのテストに使われるメソッド。

返り値：
- `bool` コールバック関数がすべてのオブジェクトで true を返す場合に true。

<h2 id="closure-filtering-php-only">クロージャーによるフィルタリング（PHPのみ）</h2>

### filter()

`filter( Closure ): Collection` コレクション内で、クロージャーが true を返すオブジェクトをすべて返します。

要素の順序は保たれます。

パラメータ：
- **Closure** ひとつのオブジェクトをテストするのに使われるメソッド。

返り値：
- **Collection** (`object`) コールバック関数が `true` を返すすべてのオブジェクトを持つ新しいコレクション。

### map()

`map( Closure ): Collection` 与えられた関数をコレクション内の各オブジェクトに適用し、その関数が返したオブジェクトからなる新しいコレクションを返します。

パラメータ：
- **Closure** ひとつのオブジェクトをテストするのに使われるメソッド。

返り値：
- **Collection** (`object`) コールバック関数の返り値であるオブジェクトからなる新しいコレクション。

### collectionGroup()

`collectionGroup( property ): Collection[]` フィールドによってコレクション内のオブジェクトをグルーピングし、コレクションの連想配列として、それらを返します。

パラメータ：
- **property** (`string`) オブジェクトをグルーピングするために使われるプロパティ。

返り値：
- `array` 複数のコレクションの配列。キーはプロパティの値。

### matching()

`matching( Criteria ): Collection` Criteria 表現にマッチするすべてのオブジェクトを選択し、それらのオブジェクトからなる新しいコレクションを返します。

パラメータ：
- [**Criteria**](https://www.doctrine-project.org/projects/doctrine-collections/en/1.6/expression-builder.html#expression-builder) 表現

返り値：
- **Collection** (`object`) criteria にマッチするオブジェクトからなる新しいコレクション。

> [!Tip]  
> Doctrine ドキュメントの [**Expression Builder**](https://www.doctrine-project.org/projects/doctrine-collections/en/1.6/expression-builder.html#expression-builder) と、  [**Expressions**](https://www.doctrine-project.org/projects/doctrine-collections/en/1.6/expressions.html#expressions) をチェックしてください。

### orderBy()

`orderBy( array ): Collection` property/value ペアのリストによりコレクションを並べ直します。

パラメータ：
- `array`

返り値：
- **Collection** (`object`) 新しい順番による新しいコレクション。

### partition()

`partition( Closure ): array` Closure に従って、コレクションを2つのコレクションに分割します。

結果のコレクションでは、キーが保持されます。

パラメータ：
- **Closure** ひとつのオブジェクトを分割するために使われるメソッド。true もしくは false を返します。

返り値：
- `array` パーティションに分けられたオブジェクト `[[a, b], [c, d, e]]`

