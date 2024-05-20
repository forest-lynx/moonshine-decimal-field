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
...
use ForestLynx\MoonshineDecimal\Fields\Decimal;
...
Decimal::make('Decimal', 'decimal');
```
Доступные методы:

`locale()` - принимает строку с локалью, например: 'ru_RU', по умолчанию значение берется из настроек проекта.
`precision()` - принимает число, количество знаков дробной части, по умолчанию 2.

> При формировании поля используется NumberFormatter php-intl. 
При отображении к полю применятся маска [@money Alpine.js](https://alpinejs.dev/plugins/mask#money-inputs)

## Лицензия
[Лицензия MIT](LICENSE).


[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg
[ico-laravel]: https://img.shields.io/badge/Laravel-10+-FF2D20?style=for-the-badge&logo=laravel
[ico-php]: https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php
