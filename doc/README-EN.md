# Decimal field for Moonshine

[![Latest Stable Version](https://img.shields.io/packagist/v/forest-lynx/moonshine-decimal-field)](https://github.com/forest-lynx/moonshine-decimal-field)
[![Total Downloads](https://img.shields.io/packagist/dt/forest-lynx/moonshine-decimal-field)](https://github.com/forest-lynx/moonshine-decimal-field) 
[![Software Licence](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)\
[![Laravel](https://img.shields.io/badge/Laravel-11+-FF2D20?style=for-the-badge&logo=laravel)](Laravel) 
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)](PHP) 
[![PHP](https://img.shields.io/badge/Moonshine-2.0+-1B253B?style=for-the-badge)](https://github.com/moonshine-software/moonshine) 

Decimal number field in the [MoonShine](https://moonshine-laravel.com/) admin panel. Inherited from the Text field.
When editing, a mask is applied to the field [@money Alpine.js](https://alpinejs.dev/plugins/mask#money-inputs)

## Contents
* [Installation](#installation)
* [Usage](#usage)
    * [Units of Measurement](#units-of-measurement)
* [License](#license)

## Installation
Command to install:
```bash.
composer require forest-lynx/moonshine-decimal-field
```
## Usage
```php
<?php
//...
use ForestLynx\MoonShine\Fields\Decimal;
//...
Decimal::make('Price', 'price');
```
> [!NOTE] 
> NumberFormatter php-intl is used to generate the field.
> By default, the locale data is taken from the project settings, use the `locale()` method to override it.

##### Methods
`locale(string $locale)`:
- `$locale` - takes a string with a locale, e.g.: 'ru_RU' or 'ru'.

`precision(int $precision, ?bool $isNaturalNumber)`:
 - `$precision` takes a number, the number of digits of the fractional part.
 - `$isNaturalNumber` Not a required parameter, defaults to `false`. Responsible for handling natural numbers, for example if you have values stored as integers in your database.
`naturalNumber(?int $precision = 2)`.
- `$precision` takes a number, the number of digits of the fractional part, the default is 2.

Example with a natural number, the value of the field in the database = 12564. Assume that given your needs it should be transformed to 125.64:
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
> The `$precision` values in the `precision()`,`naturalNumber()` methods overwrites the data about the number of fractional part digits previously defined by the specified methods.
>Example:
>```php.
><?php
>use ForestLynx\MoonShine\Fields\Decimal;
>//...
>Decimal::make('Sum', 'sum')
> ->precision(3)
> ->naturalNumber(4);
>//...
>```
>This code will override the value of the number of decimal places to 4.

> [!NOTE]
> When working with natural numbers, the reverse transformation is performed with the field value obtained from request before saving.

#### Units of measurement
To specify the field where the units of measurement are stored:

##### Methods
`unit(string $unit, string|array $data)`:
- `$unit` - the name of the column in the database.
- `$data` - array with data, or the name of an enumeration class with unit data.

`unitDefault(mixed $default)`:
- `$default` - the default value for the field.

Usage examples:
```php.
<?php
use ForestLynx\MoonShine\Fields\Decimal;
//...
Decimal::make('Price', 'price')
    ->unit('unit', ['kilogram.', 'litre'])
    ->unitDefault(0);
//or
Decimal::make('Price', 'price')
    ->unit('unit', [0 => 'kilogram.', 1 => 'litre'])
    ->unitDefault(1);
//or
Decimal::make('Price', 'price')
    ->unit('unit', UnitEnum::class)
    ->unitDefault(UnitEnum::KILOGRAM);
//...
```
How it looks like in the admin panel:
|View|Edit|.
|:--:|:--:|
|![preview](../screenshots/priview.png)|![edit](../screenshots/edit.png)|

## License
[MIT Licence](LICENSE).
