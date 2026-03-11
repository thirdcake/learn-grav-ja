---
title: 'Flex オブジェクト'
layout: ../../../../../layouts/Default.astro
lastmod: '2025-10-15'
description: 'twig や、プラグイン中の PHP で使える、 flex object のメソッドを解説します。'
---

## オブジェクトをレンダリング

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

## その他

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
> このメソッドは、すべてのコレクションで共有されるオブジェクトインスタンスを修正します。そのつもりがない場合は、このメソッドを使う前に、オブジェクトを `clone` してください。

## defProperty()

`defProperty( property, default ): Object` オブジェクトのプロパティにデフォルト値を定義する。

パラメータ：
- **property** プロパティ名 (`string`)
- **default** デフォルト値 (`mixed`)

返り値：
- **Object** (`object`) メソッドチェーンを呼び出せるオブジェクト

> [!Warning]  
> このメソッドは、すべてのコレクションで共有されるオブジェクトインスタンスを修正します。そのつもりがない場合は、このメソッドを使う前に、オブジェクトを `clone` してください。

## unsetProperty()

`unsetProperty( property ): Object` オブジェクトプロパティの値を取り除く。

パラメータ：
- **property** プロパティ名 (`string`)

返り値：
- **Object** (`object`) メソッドチェーンを呼び出せるオブジェクト

> [!Warning]  
> このメソッドは、すべてのコレクションで共有されるオブジェクトインスタンスを修正します。そのつもりがない場合は、このメソッドを使う前に、オブジェクトを `clone` してください。

## isAuthorized()

`isAuthorized( action, [scope], [user] ): bool | null` ユーザーがそのアクションを認可されているかチェックします。

パラメータ：
- **action** (`string`)
  - 次のいずれか: `create`, `read`, `update`, `delete`, `list`
- **scope** オプション (`string`)
  - 通常は次のいずれか `admin` もしくは `site`
- **user** オプション User オブジェクト (`object`)

返り値：
- `true` 許可されたアクション
- `false` 拒否されたアクション
- `null` 未設定 (拒否として扱う)

> [!Note]  
> 否定を意味する値が2つあります: 拒否(false) と、 未設定(null) です。これにより、マッチしないルールがあったとしても、複数のルールをつなげて判断できます。

## getFlexDirectory()

`getFlexDirectory(): Directory`

返り値：
- **[Directory](/advanced/flex/using/directory)** (`object`)

## getTimestamp()

`getTimestamp(): int` そのオブジェクトの最新の更新時のタイムスタンプを取得

返り値：
- `int` タイムスタンプ

## search()

`search(string, [properties], [options] ): float` オブジェクトから文字列を検索し、0 から 1 の重みを返す

パラメータ：
- **string** 検索文字列 (`string`)
- **properties** 検索するプロパティ。もし null (もしくは空) の場合、デフォルト(`array` もしくは `null`) を利用する
- **options** 検索中に使う追加のオプション (`array`)
  - `starts_with`: `bool`
  - `ends_with`: `bool`
  - `contains`: `bool`
  - `case_sensitive`: `bool`

返り値：
- `float` 結果の順番に利用できる 0 から 1 の間の検索の重み
- `0` オブジェクトは検索にマッチしない場合

> [!Note]  
> この関数を上書きする場合、必ず 0 から 1の間の値を返すようにしてください！

## getFlexKey()

`getFlexKey(): string` オブジェクトのユニークなキーを取得。

返り値：
- `string` オブジェクトの **Flex キー**

Flex キーは、その Flex オブジェクトが、どの Flex ディレクトリに属しているかに関係なく使えます。

## getStorageKey()

`getStorageKey(): string` ユニークな（ディレクトリ内の）ストレージキーを取得し、ファイル名やデータベースの ID を特定するのに使われます。

返り値：
- `string` オブジェクトの **ストレージキー**

## exists()

`exists(): bool` ストレージにオブジェクトが存在していれば true を返す。

返り値：
- `true` ストレージにオブジェクトが存在する
- `false` オブジェクトが保存されていない

## update()

`update( data, files ): Object` メモリ内でオブジェクトを更新する。

パラメータ：
- **data** (`array`) ネストされたプロパティと値の連想配列
- **files** (`array`) `Psr\Http\Message\UploadedFileInterface` オブジェクトの配列

返り値：
- **Object** (`object`) メソッドチェーンを呼び出せるオブジェクト

> [!Tip]  
> このメソッドの呼び出し後、そのオブジェクトを保存する必要があります。

## create()

`create( [key] ): Object` 新しいオブジェクトをストレージに作成する。

パラメータ：
- **key** (`string`) オプションのキー

返り値：
- **Object** (`object`) 保存されたオブジェクト

## createCopy()

`createCopy( [key] ): Object` 現在のオブジェクトから新しいオブジェクトを作成し、ストレージに保存する。

パラメータ：
- **key** (`string`) オプションのキー

返り値：
- **Object** (`object`) 保存されたオブジェクト

## save()

`save(): Object` ストレージにオブジェクトを保存する

返り値：
- **Object** (`object`) 保存されたオブジェクト

## delete()

`delete(): Object` ストレージからオブジェクトを削除する。

返り値：
- **Object** (`object`) 削除されたオブジェクト

## getBlueprint()

`getBlueprint( [name] ): Blueprint` そのオブジェクトのブループリントを返す

パラメータ：
- **name** (`string`) オプション ブループリントの名前

返り値：
- **Blueprint** (`object`)

## getForm()

`getForm( [name], [options] ): Form` オブジェクトの form インスタンスを返す

パラメータ：
- **name** (`string`) オプション フォーム名
- **options** (`array`) フォームに対する追加のオプション

返り値：
- **Form** (`object`)

## getDefaultValue()

`getDefaultValue( name, [separator] ): mixed` 与えられたプロパティに対するフォームで使われる適切なデフォルト値を返す。

パラメータ：
- **name** (`string`) プロパティ名
- **separator** (`string`) オプション ネストされたプロパティでセパレータとして使う文字。デフォルトは `.` (ドット)

返り値：
- `mixed` プロパティのデフォルト値

## getDefaultValues()

`getDefaultValues(): array` 与えられたプロパティに対するフォームで使われる適切なデフォルト値を複数返す。

返り値：
- `array` すべてのデフォルト値

## getFormValue()

`getFormValue( name, [default], [separator] ): mixed` 与えられたプロパティに対するフォームで使われる適切な生の値を返す。

パラメータ：
- **name** (`string`) プロパティ名
- **default** (`mixed`) オプション フィールドのデフォルト値で、与えられなかった場合は `null` となる
- **separator** (`string`) オプション ネストされたプロパティでセパレータとして使う文字。デフォルトは `.` (ドット)

返り値：
- `mixed` フォームフィールドの値

## triggerEvent()

`triggerEvent( name, [Event] ): Object` 選択したイベントのトリガー

パラメータ：
- **name** (`string`) イベント名
- **Event** (`object`) オプション イベントの class

返り値：
- **Object** (`object`) メソッドチェーンを呼び出せるオブジェクト

