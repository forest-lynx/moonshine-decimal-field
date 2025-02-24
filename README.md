# Поле Decimal для Moonshine

[![Latest Stable Version](https://img.shields.io/packagist/v/forest-lynx/moonshine-decimal-field)](https://github.com/forest-lynx/moonshine-decimal-field)
[![Total Downloads](https://img.shields.io/packagist/dt/forest-lynx/moonshine-decimal-field)](https://github.com/forest-lynx/moonshine-decimal-field) 
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)\
[![Laravel](https://img.shields.io/badge/Laravel-11+-FF2D20?style=for-the-badge&logo=laravel)](Laravel) 
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)](PHP) 
[![PHP](https://img.shields.io/badge/Moonshine-3.0+-1B253B?style=for-the-badge)](https://github.com/moonshine-software/moonshine) 

Documentation in [English](https://github.com/forest-lynx/moonshine-decimal-field/blob/2.x/doc/README-EN.md)

Поле для работы с десятичными числами в административной панели [MoonShine](https://moonshine-laravel.com/). Наследуется от поля Text.
При редактировании к полю применяется маска [@money Alpine.js](https://alpinejs.dev/plugins/mask#money-inputs)

>[!NOTE]
> Редактирование поля в режиме предварительного просмотра осуществляется через всплывающее окно, как это предусмотрено методом полей [`updateInPopover()`](https://moonshine-laravel.com/ru/docs/3.x/fields/basic-methods#update-in-popover) админ панели MoonShine.

>[!NOTE]
> Валидация для [единиц измерения](#единицы-измерения) не поддерживается.

## Совместимость
|Версия пакета | Версия админ-панели MoonShine |
|:---:|:---:|
| ^1.x | ^2.18.0 |
| ^2.x | ^3.x |
## Содержание
* [Установка](#установка)
* [Использование](#использование)
    * [Единицы измерения](#единицы-измерения)
* [Лицензия](#лицензия)

## Установка
Команда для установки:
```bash
composer require forest-lynx/moonshine-decimal-field
```
## Использование
```php
<?php
//...
use ForestLynx\MoonShine\Fields\Decimal;
//...
Decimal::make('Price', 'price');
```
> [!NOTE] 
> При формировании поля используется NumberFormatter php-intl.
> По умолчанию данные о локали берутся из настроек проекта, для ее переопределения используйте метод `locale()`

##### Методы
`locale(string $locale)`:
- `$locale` - принимает строку с локалью, например: 'ru_RU' или 'ru'.

`precision(int $precision, ?bool $isNaturalNumber)`:
 - `$precision` принимает число, количество знаков дробной части.
 - `$isNaturalNumber` Не обязательный параметр, по умолчанию `false`. Отвечает за обработку натуральных чисел, например если у вас в базе данных значения хранятся в виде целых чисел.
`naturalNumber(?int $precision = 2)`
- `$precision` принимает число, количество знаков дробной части, по умолчанию 2.

Пример с натуральным числом, значение поля в базе данных = 12564. Предположим, что с учетом Ваших потребностей оно должно трансформироваться в 125.64:
```php
<?php
use ForestLynx\MoonShine\Fields\Decimal;
//...
Decimal::make('Sum', 'sum')
   ->precision(2, true);
//or
Decimal::make('Sum', 'sum')
   ->naturalNumber();
//...
```
>[!CAUTION]
> Значения `$precision` в методах `precision()`,`naturalNumber()` перезаписывает данные о количестве знаков дробной части, ранее определенных указанными методами.
>Например:
>```php
><?php
>use ForestLynx\MoonShine\Fields\Decimal;
>//...
>Decimal::make('Sum', 'sum')
>   ->precision(3)
>   ->naturalNumber(4);
>//...
>```
>Данный код переопределит значение количества знаков после запятой на 4.

> [!NOTE]
> При работе с натуральными числами, со значением поля полученным из request перед сохранением происходит обратная трансформация.

#### Единицы измерения
Для указания поля, где хранятся единицы измерения:

##### Методы
`unit(?string $column = null, \Closure|array|Options|string $data, ?Closure $formatted = null)`:
- `$column` - связь столбца в базе и атрибута `name` у поля ввода.
- `$data` - Для создания поля типа [Enum](https://moonshine-laravel.com/ru/docs/3.x/fields/enum) нужно передать название класса (например: `App\Enums\Unit::class`). Для формирования поля типа [Select](https://moonshine-laravel.com/ru/docs/3.x/fields/select) нужно передать опции, как через метод `options()` поля Select.
- `$formatted` - замыкание для форматирования значения поля в режиме preview.

`unitDefault(mixed $default)` идентичен методу [default()](https://moonshine-laravel.com/ru/docs/3.x/fields/select#default),

`unitNullable()` идентичен методу [nullable()](https://moonshine-laravel.com/ru/docs/3.x/fields/select#nullable),

`unitSearchable()` идентичен методу [searchable()](https://moonshine-laravel.com/ru/docs/3.x/fields/select#search),

Примеры использования:
```php
<?php
use ForestLynx\MoonShine\Fields\Decimal;
use App\Enums\Unit;
//...
Decimal::make('Price', 'price')
    ->unit('unit', [0 => 'килограмм.', 1 => 'литр'])
    ->unitDefault(1);
//or
Decimal::make('Price', 'price')
    ->unit('unit', Unit::class)
    ->unitDefault(Unit::KILOGRAM);
//...
```
Как это выглядит в административной панели:
|Просмотр|Редактирование|
|:--:|:--:|
|![preview](https://github.com/forest-lynx/moonshine-decimal-field/blob/2.x/screenshots/priview.png)|![edit](https://github.com/forest-lynx/moonshine-decimal-field/blob/2.x/screenshots/edit.png)|

## Лицензия
[Лицензия MIT](LICENSE).
