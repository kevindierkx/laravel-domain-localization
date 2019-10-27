# Laravel domain localization

[![Latest Version](https://img.shields.io/github/tag/kevindierkx/laravel-domain-localization.svg?style=flat-square)](https://github.com/kevindierkx/laravel-domain-localization/tags)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/kevindierkx/laravel-domain-localization/blob/master/LICENSE)
[![Build Status](https://img.shields.io/travis/kevindierkx/laravel-domain-localization.svg?style=flat-square)](https://travis-ci.org/kevindierkx/laravel-domain-localization)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/kevindierkx/laravel-domain-localization.svg?style=flat-square)](https://scrutinizer-ci.com/g/kevindierkx/laravel-domain-localization/)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/kevindierkx/laravel-domain-localization.svg?style=flat-square)](https://scrutinizer-ci.com/g/kevindierkx/laravel-domain-localization/?branch=master)

A tool for easy i18n domain based localization in Laravel applications.

## Installation
_This package is build for [Laravel framework](http://laravel.com) based applications._

For Laravel 4.2+ please refer to [version 1.0](https://github.com/kevindierkx/laravel-domain-localization/tree/1.0).

### Laravel 5.x
Require this package with composer:

```
composer require kevindierkx/laravel-domain-localization
```

### Service provider
Open `config/app.php` and register the required service provider.

```php
'providers' => [
    ...
    Kevindierkx\LaravelDomainLocalization\Provider\LaravelServiceProvider::class,
]
```

If you'd like to make configuration changes, you can publish it with the following Artisan command:

```
php artisan vendor:publish --tag=config
```

## Middleware
The Laravel Domain Localization uses the URL given for the request. In order to achieve this purpose it uses a middleware, to utilize the middleware add the following to you middleware array in `app\Http\Kernel.php`:

```
protected $middleware = [
    ...
    \Kevindierkx\LaravelDomainLocalization\Middleware\SetupLocaleMiddleware::class,
];
```

Once this middleware is enabled, the user can access all the locales defined in the `supported_locales` array ('en' by default, look at the config section to change the supported locales).

For example, when you add the dutch locale `nl` the user could access two different locales, using the following addresses:

```
http://example.com
http://example.nl
```

If the locale is not defined in `supported_locales` array, the system will use the applications default locale.

Incase you only want to use domain localization on specific routes you could use the middleware groups or route middlewares instead.

## Facade
The facade is used for easy access to the domain localization helper. If you would like to use the facade you need to open `config/app.php` and register the facade in the aliases array.

```php
'aliases' => [
    ...
    'Localization' => Kevindierkx\LaravelDomainLocalization\Facade\DomainLocalization::class,
]
```

## Helpers
The package provides some useful helper functions. For a full list of methods and method descriptions please refer to the [DomainLocalization class](https://github.com/kevindierkx/laravel-domain-localization/blob/master/src/DomainLocalization.php).

### Get current URL for a specified locale
Uses the current URL to create a localized URL using the tld value from the config.

```php
{{ Localization::getLocalizedUrl('en') }}
```

### Get supported locales
Returns all the supported currently configured locales.

```php
{{ Localization::getSupportedLocales() }}
```

### Get supported locale by 'name'
Returns a supported currently configured locale by 'name'.

```php
{{ Localization::getSupportedLocale('en') }}
```

### Determine if a locale exists
Determines if the locale is configured by 'name'.

```php
{{ Localization::hasSupportedLocale('en') }}
```

### Resolve a locale config by TLD
Returns a locale config by its TLD, when no config matches the TLD `null` will be returned.

```php
{{ Localization::getSupportedLocaleByTld('.com') }}
```

### Resolve a locale name by TLD
Returns the name for a configured locale based on its TLD, when no config matches the TLD `null` will be returned.

```php
{{ Localization::getSupportedLocaleNameByTld('.com') }}
```

### Get attributes from the current locale
Returns the attribute value of the current locale.

```php
{{ Localization::getTldForCurrentLocale() }}
{{ Localization::getNameForCurrentLocale() }}
{{ Localization::getDirectionForCurrentLocale() }}
{{ Localization::getScriptForCurrentLocale() }}
{{ Localization::getNativeForCurrentLocale() }}
```

### Get attributes from a specified supported locale
Returns the attribute value of a supported locale.

```php
{{ Localization::getTldForLocale('en') }}
{{ Localization::getNameForLocale('en') }}
{{ Localization::getDirectionForLocale('en') }}
{{ Localization::getScriptForLocale('en') }}
{{ Localization::getNativeForLocale('en') }}
```

### Get current TLD
Returns the current top level domain.

```php
{{ Localization::getTld() }}
```

### Get the active locale
Returns the current locale.

```php
{{ Localization::getCurrentLocale() }}
```

### Set the active locale
Sets the current locale.

```php
{{ Localization::setCurrentLocale('en') }}
```

### Get default locale
Returns the applications default locale.

```php
{{ Localization::getDefaultLocale() }}
```

## Creating a language selector
Using the helper methods we can create a simple but effective language switcher. The example below uses a [Bootstrap dropdown](http://getbootstrap.com/components/#dropdowns).

```html
...
<ul class="nav navbar-nav">
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            {{ Localization::getNameForCurrentLocale() }}
        </a>
        <ul class="dropdown-menu">
            @foreach(Localization::getSupportedLocales() as $locale => $properties)
                <li>
                    <a rel="alternate" hreflang="{{ $locale }}" href="{{ Localization::getLocalizedUrl($locale) }}">
                        {{ Localization::getNameForLocale($locale) }} - {{ Localization::getNativeForLocale($locale) }}
                    </a>
                </li>
            @endforeach
        </ul>
    </li>
</ul>
...
```

## License
The MIT License (MIT). Please see [License File](https://github.com/kevindierkx/laravel-domain-localization/blob/master/LICENSE) for more information.
