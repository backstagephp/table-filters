# This is my package table-filters

[![Latest Version on Packagist](https://img.shields.io/packagist/v/backstage/table-filters.svg?style=flat-square)](https://packagist.org/packages/backstage/table-filters)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/backstage/table-filters/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/backstage/table-filters/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/backstage/table-filters/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/backstage/table-filters/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/backstage/table-filters.svg?style=flat-square)](https://packagist.org/packages/backstage/table-filters)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require backstage/table-filters
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="table-filters-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="table-filters-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="table-filters-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$tableFilters = new Backstage\TableFilters();
echo $tableFilters->echoPhrase('Hello, Backstage!');
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
