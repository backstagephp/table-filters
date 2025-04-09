## Backstage Table Filters

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

## Usage

In youre ``ListRecords`` resource page, add te following trait:
```php
use Backstage\TableFilters\Concerns\HasFileBasedTableFilters;

class ListUsers extends ListRecords
{
    use HasFileBasedTableFilters;
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
