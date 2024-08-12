# Поле Decimal для Moonshine

[![Latest Stable Version](https://img.shields.io/packagist/v/forest-lynx/moonshine-decimal-field)](https://github.com/forest-lynx/moonshine-decimal-field)
[![Total Downloads](https://img.shields.io/packagist/dt/forest-lynx/moonshine-decimal-field)](https://github.com/forest-lynx/moonshine-decimal-field) 
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)\
[![Laravel](https://img.shields.io/badge/Laravel-11+-FF2D20?style=for-the-badge&logo=laravel)](Laravel) 
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)](PHP) 
[![PHP](https://img.shields.io/badge/Moonshine-2.0+-1B253B?style=for-the-badge)](https://github.com/moonshine-software/moonshine) 

Documentation in [English](./doc/README-EN.md)

Поле для работы с десятичными числами в административной панели [MoonShine](https://moonshine-laravel.com/). Наследуется от поля Text.
При редактировании к полю применяется маска [@money Alpine.js](https://alpinejs.dev/plugins/mask#money-inputs)

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
`unit(string $unit, string|array $data)`:
- `$unit` - название колонки в базе данных.
- `$data` - массив с данными, или название класса перечисления с данными о единицах измерения.

`unitDefault(mixed $default)`:
- `$default` - значение по умолчанию для поля.

Примеры использования:
```php
<?php
use ForestLynx\MoonShine\Fields\Decimal;
//...
Decimal::make('Price', 'price')
    ->unit('unit', ['килограмм.', 'литр'])
    ->unitDefault(0);
//or
Decimal::make('Price', 'price')
    ->unit('unit', [0 => 'килограмм.', 1 => 'литр'])
    ->unitDefault(1);
//or
Decimal::make('Price', 'price')
    ->unit('unit', UnitEnum::class)
    ->unitDefault(UnitEnum::KILOGRAM);
//...
```
Как это выглядит в административной панели:
|Просмотр|Редактирование|
|:--:|:--:|
|![preview](./screenshots/priview.png)|![edit](./screenshots/edit.png)|

## Лицензия
[Лицензия MIT](LICENSE).
