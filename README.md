# Поле Decimal для Moonshine
[![Software License][ico-license]](LICENSE)

[![Laravel][ico-laravel]](Laravel) [![PHP][ico-php]](PHP) 

Поле для работы с десятичными числами в административной панели [MoonShine](https://moonshine-laravel.com/). Наследуется от поля Text.

## Содержание
* [Установка](#установка)
* [Использование](#использование)
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
use ForestLynx\MoonshineDecimal\Fields\Decimal;
//...
Decimal::make('Price', 'price');
```
> [!NOTE] 
> При формировании поля используется NumberFormatter php-intl.
> По умолчанию данные о локали берутся из настроек проекта, для ее переопределения используйте метод `locale()`

#### Методы:
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
use ForestLynx\MoonshineDecimal\Fields\Decimal;
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
>use ForestLynx\MoonshineDecimal\Fields\Decimal;
>//...
>Decimal::make('Sum', 'sum')
>   ->precision(3)
>   ->naturalNumber(4);
>//...
>```
>Данный код переопределит значение количества знаков после запятой на 4.

> [!NOTE]
> При работе с натуральными числами, со значением поля полученным из request перед сохранением происходит обратная трансформация.

При редактировании к полю применяется маска [@money Alpine.js](https://alpinejs.dev/plugins/mask#money-inputs)

## Лицензия
[Лицензия MIT](LICENSE).


[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg
[ico-laravel]: https://img.shields.io/badge/Laravel-10+-FF2D20?style=for-the-badge&logo=laravel
[ico-php]: https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php
