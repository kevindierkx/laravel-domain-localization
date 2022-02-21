# Getting Started

This Laravel package offers easy i18n domain based localization in Laravel applications.

For more advanced locale management take a look at [mcamara/laravel-localization](https://github.com/mcamara/laravel-localization).

## Installation

The package can be installed via composer:

```bash
composer require kevindierkx/laravel-domain-localization
```

*This package implements Laravel's Package Discovery, no further changes are needed to your application configurations. For more information [please refer to the Laravel documentation](https://laravel.com/docs/packages#package-discovery).*

## Version Compatibility

| Laravel | PHP            | Package |
| ------- | -------------- | ------- |
| 5.x     | ^7.1           | >= 2.0  |
| 6.x     | ^7.2           | >= 3.0  |
| 7.x     | ^7.2           | >= 4.0  |
| 8.x     | ^7.2           | >= 4.1  |
| 8.x     | ^7.4 \|\| ^8.0 | >= 4.2  |
| 9.x     | ^8.0           | >= 5.0  |

## Configuration

Supported locales are defined in the package configuration file. Desired locales can be added or removed after publishing the package configuration file.

In order to edit the default configuration you need to publish the package configuration to your application config directory:

```bash
php artisan vendor:publish --provider="Kevindierkx\LaravelDomainLocalization\ServiceProvider"
```

The config file will be published to `config/domain-localization.php` in your application directory. Please refer to the [config file](https://github.com/kevindierkx/laravel-domain-localization/blob/master/config/domain-localization.php) for an overview of the available options.

**Please note:** When a desired locale isn't present in the supported locales config an exception will be thrown.

## Setup Middleware

The provided middleware enables dynamically setting the current application locale. To enable this behavior the middleware needs to be registered in the application's middleware array, found in the `app\Http\Kernel.php`:

```php
protected $middleware = [
    ...
    \Kevindierkx\LaravelDomainLocalization\Middleware\SetupLocaleMiddleware::class,
];
```

For example, when you add the dutch locale `nl` the user could access two different locales, using the following addresses:

```
https://example.com
https://example.nl
```

**Please note:** It is not required to use the middleware on all routes. The `Localization` service provides a variety of helper methods to resolve a matching locale from an URL.
