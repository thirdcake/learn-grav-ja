---
title: 'Flex オブジェクト'
layout: ../../../../../layouts/Default.astro
lastmod: '2025-09-11'
---

# Render Object

## render()

`render( [layout], [context] ): Block` オブジェクトをレンダリングする

パラメータ：
- **layout** レイアウト名 (`string`)
- **context** Twig テンプレートファイル内で使うことができる追加の変数 (`array`)

返り値：
- **Block** (`object`) 出力を含んだ Html ブロック class

> [!Note]  
> Twig に、メソッドを直接呼ぶ代わりに使える `{% render %}` タグがあります。これにより、 object から JS/CSS が適切に機能します。 

```twig
{% set contact = grav.get('flex').object('gizwsvkyo5xtms2s', 'contacts') %}

{% render contact layout: 'details' with { my_variable: true } %}
```


```php
use Grav\Common\Grav;
use Grav\Framework\ContentBlock\HtmlBlock;
use Grav\Framework\Flex\Interfaces\FlexObjectInterface;

/** @var FlexObjectInterface|null $collection */
$object = Grav::instance()->get('flex')->getObject('gizwsvkyo5xtms2s', 'contacts');
if ($object) {

    /** @var HtmlBlock $block */
    $block = $object->render('details', ['my_variable' => true]);

}
```



# Other

## getKey()

`getKey(): string` オブジェクトのキーを取得する

返り値：
- `string` オブジェクトのキー

## hasKey()

`hasKey(): bool` オブジェクトのキーがある場合に true を返す

返り値：
- `true` : オブジェクトにキーがあるとき。  `false` : そうでないとき。

## getFlexType()

`getFlexType(): string` オブジェクトの型を取得する

返り値：
- `string` そのオブジェクトが所属する Flex ディレクトリ名

## hasProperty()

`hasProperty( property ): bool` オブジェクトのプロパティが定義され、値を持つ場合(null でない場合) に true を返す

パラメータ：
- **property** プロパティ名 (`string`)

返り値：
- `true` : プロパティが値を持つとき。 `false` : そうでないとき。

## getProperty()

`getProperty( property, default ): mixed` オブジェクトのプロパティの値を返す

パラメータ：
- **property** プロパティ名 (`string`)

返り値：
- `mixed` プロパティ値
- `null` プロパティが定義されていない、もしくは値が無いとき

## setProperty()

`setProperty( property, value ): Object` オブジェクトのプロパティに新しい値を設定する

パラメータ：
- **property** プロパティ名 (`string`)
- **value** 新しい値 (`mixed`)

返り値：
- **Object** (`object`) The object for chaining the method calls

> [!Warning]  
> This method modifies the object instance shared between all the collections. If that is not intended, please `clone` the object before using this method.

## defProperty()

`defProperty( property, default ): Object` Define default value to the object property.

パラメータ：
- **property** Property name (`string`)
- **default** Default value (`mixed`)

返り値：
- **Object** (`object`) The object for chaining the method calls

!! **WARNING:** This method modifies the object instance shared between all the collections. If that is not intended, please `clone` the object before using this method.

## unsetProperty()

`unsetProperty( property ): Object` Remove value of the object property.

パラメータ：
- **property** Property name (`string`)

返り値：
- **Object** (`object`) The object for chaining the method calls

!! **WARNING:** This method modifies the object instance shared between all the collections. If that is not intended, please `clone` the object before using this method.

## isAuthorized()

`isAuthorized( action, [scope], [user] ): bool | null` Check if user is authorized for the action.

パラメータ：
- **action** (`string`)
  - One of: `create`, `read`, `update`, `delete`, `list`
- **scope** Optional (`string`)
  - Usually either `admin` or `site`
- **user** Optional User Object (`object`)

返り値：
- `true` Allow action
- `false` Deny action
- `null` Not set (acts as Deny)

!!! **Note:** There are two deny values: denied (false), not set (null). This allows chaining multiple rules together when the previous rules were not matched.`

## getFlexDirectory()

`getFlexDirectory(): Directory`

返り値：
- **[Directory](/advanced/flex/using/directory)** (`object`)

## getTimestamp()

`getTimestamp(): int` Get last modified timestamp for the object.

返り値：
- `int` Timestamp.

## search()

`search(string, [properties], [options] ): float` Search a string from the object, returns weight between 0 and 1.

パラメータ：
- **string** Search string (`string`)
- **properties** Properties to search, if null (or not provided), use defaults (`array` or `null`)
- **options** Extra options used while searching (`array`)
  - starts_with: `bool`
  - ends_with: `bool`
  - contains: `bool`
  - case_sensitive: `bool`

返り値：
- `float` Search weight between 0 and 1, used for ordering the results
- `0` Object does not match the search

!!! **Note:** If you override this function, make sure you return value in range 0...1!

## getFlexKey()

`getFlexKey(): string` Get a unique key for the object.

返り値：
- `string` **Flex key** of the object

Flex Keys can be used without knowing the Directory the Object belongs into.

## getStorageKey()

`getStorageKey(): string` Get a unique storage key (within the directory) which is used for figuring out the filename or database id.

返り値：
- `string` **Storage key** of the object

## exists()

`exists(): bool` Returns true if the object exists in the storage.

返り値：
- `true` Object exists in the storage
- `false` Object has not been saved

## update()

`update( data, files ): Object` Updates object in the memory.

パラメータ：
- **data** (`array`) Nested arrays of properties with their values
- **files** (`array`) Array of `Psr\Http\Message\UploadedFileInterface` objects

返り値：
- **Object** (`object`) The object for chaining the method calls

! **TIP:** You need to save the object after calling this method.

## create()

`create( [key] ): Object` Create new object into the storage.

パラメータ：
- **key** (`string`) Optional key

返り値：
- **Object** (`object`) Saved object

## createCopy()

`createCopy( [key] ): Object` Create a new object from the current one and save it into the storage.

パラメータ：
- **key** (`string`) Optional key

返り値：
- **Object** (`object`) Saved object

## save()

`save(): Object` Save object into the storage.

返り値：
- **Object** (`object`) Saved object

## delete()

`delete(): Object` Delete object from the storage.

返り値：
- **Object** (`object`) Deleted object

## getBlueprint()

`getBlueprint( [name] ): Blueprint` Returns the blueprint of the object.

パラメータ：
- **name** (`string`) Optional name for the blueprint

返り値：
- **Blueprint** (`object`)

## getForm()

`getForm( [name], [options] ): Form` Returns a form instance for the object.

パラメータ：
- **name** (`string`) Optional name for the form
- **options** (`array`) Optional options to the form

返り値：
- **Form** (`object`)

## getDefaultValue()

`getDefaultValue( name, [separator] ): mixed` Returns default value suitable to be used in a form for the given property.

パラメータ：
- **name** (`string`) Name of the property
- **separator** (`string`) Optional separator character for nested properties, defaults to `.` (dot)

返り値：
- `mixed` Default value of the property

## getDefaultValues()

`getDefaultValues(): array` Returns default values suitable to be used in a form for the given property.

返り値：
- `array` All default values

## getFormValue()

`getFormValue( name, [default], [separator] ): mixed` Returns raw value suitable to be used in a form for the given property.

パラメータ：
- **name** (`string`) Name of the property
- **default** (`mixed`) Optional default value of the field, `null` if not given
- **separator** (`string`) Optional separator character for nested properties, defaults to `.` (dot)

返り値：
- `mixed` Value of the form field

## triggerEvent()

`triggerEvent( name, [Event] ): Object` Trigger an event of your choice.

パラメータ：
- **name** (`string`) Name of the event
- **Event** (`object`) Optional event class

返り値：
- **Object** (`object`) The object for chaining the method calls

