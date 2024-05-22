# Поле Decimal для Moonshine
[![Software License][ico-license]](LICENSE)

[![Laravel][ico-laravel]](Laravel) [![PHP][ico-php]](PHP) 

Поле для работы с десятичными числами, для админ-панели [MoonShine](https://moonshine-laravel.com/). Наследуется от поля Text.

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
Decimal::make('Decimal', 'decimal');
```
> [!NOTE] 
> При формировании поля используется NumberFormatter php-intl.

### Доступные методы:

`locale(string $locale)`:
- `$locale` - принимает строку с локалью, например: 'ru_RU' или 'ru', по умолчанию значение берется из настроек проекта.

`precision(int $precision, ?bool $isNaturalNumber)`:
 - `$precision` принимает число, количество знаков дробной части, по умолчанию 2.
 - `$isNaturalNumber` Не обязательный параметр, по умолчанию `false`. Отвечает за обработку натуральных чисел, например если у вас в базе данных значения хранятся в виде целых чисел.

`naturalNumber(?int $precision = 2)`
- `$precision` принимает число, количество знаков дробной части, по умолчанию 2.

Пример с натуральным числом, значение поля в базе данных = 12564. С учетом Ваших потребностей оно должно преобразоваться в 125.64:
```php
<?php
use ForestLynx\MoonshineDecimal\Fields\Decimal;
//...
Decimal::make('Sum', 'sum')
   ->precision(isNaturalNumber: true);
//or
Decimal::make('Sum', 'sum')
   ->naturalNumber();
//...
```
> [!NOTE]
> При работе с натуральными числами, со значением поля полученным из request перед сохранением происходит обратная трансформация.

>[!CAUTION]
> Значения `$precision` в методах `precision()`,`naturalNumber()` перезаписывает данные о количестве знаков дробной части.

При редактировании к полю применятся маска [@money Alpine.js](https://alpinejs.dev/plugins/mask#money-inputs)

## Лицензия
[Лицензия MIT](LICENSE).


[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg
[ico-laravel]: https://img.shields.io/badge/Laravel-10+-FF2D20?style=for-the-badge&logo=laravel
[ico-php]: https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php
