## Backstage: Table Filters in Filament

[![Latest Version on Packagist](https://img.shields.io/packagist/v/backstage/table-filters.svg?style=flat-square)](https://packagist.org/packages/backstage/table-filters)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/backstage/table-filters/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/backstage/table-filters/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/backstage/table-filters/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/backstage/table-filters/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/backstage/table-filters.svg?style=flat-square)](https://packagist.org/packages/backstage/table-filters)

## Nice to meet you, we're [Backstage](https://backstagephp.com)

Hi! We are a web development agency from Nijmegen in the Netherlands and we use Laravel for everything: advanced websites with a lot of bells and whitles and large web applications.

## About the package

This package makes it easy to add filters to your Filament resource tables with minimal setup.

It allows you to define your table filters externally (file-based), helping you keep your code clean, organized, and easy to maintain.


## Installation

You can install the package via composer:

```bash
composer require backstage/table-filters
```

## Usage

In your resource, add te following method at the end of your table:
```php
public static function table(Table $table): Table
{
    return $table
        ->withFileBasedFilters();
}
```

Now if you want to create a file-based Filament Table Filter, please execute the following command:
```php
php artisan make:filament-filter
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Manoj Hortulanus](https://github.com/arduinomaster22)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
