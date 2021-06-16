# Laravel domain localization

[![Latest Version](https://img.shields.io/github/tag/kevindierkx/laravel-domain-localization.svg?style=flat-square)](https://github.com/kevindierkx/laravel-domain-localization/tags)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/github/workflow/status/kevindierkx/laravel-domain-localization/CI-CD/master?style=flat-square)](https://github.com/kevindierkx/laravel-domain-localization/actions)
[![Code Coverage](https://img.shields.io/codecov/c/github/kevindierkx/laravel-domain-localization?style=flat-square&token=2L6UCW8F96)](https://codecov.io/gh/kevindierkx/laravel-domain-localization)

A tool for easy i18n domain based localization in Laravel applications.

For more advanced locale management take a look at [mcamara/laravel-localization](https://github.com/mcamara/laravel-localization).

## Version Compatibility

| Laravel | PHP            | Package |
| ------- | -------------- | ------- |
| 5.x     | ^7.1           | >= 2.0  |
| 6.x     | ^7.2           | >= 3.0  |
| 7.x     | ^7.2           | >= 4.0  |
| 8.x     | ^7.2           | >= 4.1  |
| 8.x     | ^7.4 \|\| ^8.0 | >= 4.2  |

## Installation

Install the package via composer: `composer require kevindierkx/laravel-domain-localization`

*This package implements Laravel's Package Discovery, no further changes are needed to your application configs. For more information [please refer to the Laravel documentation](https://laravel.com/docs/packages#package-discovery).*

### Config

In order to edit the default configuration you need to publish the package configuration to your application config directory:

`php artisan vendor:publish --provider="Kevindierkx\LaravelDomainLocalization\LocalizationServiceProvider"`

The config file will be published in `config/domain-localization.php`. Here you can enable or change the supported locales.

*Please note:* When a desired locale isn't present in the supported locales config an exception will be thrown.

### Register Middleware

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

*Please note:* It is not required to use the middleware on all routes. The `Localization` service provides a variety of helper methods to resolve a matching locale from an URL.

## Usage

The package provides some useful helper methods. For a full list of methods and method descriptions please refer to the [DomainLocalization class](https://github.com/kevindierkx/laravel-domain-localization/blob/master/src/DomainLocalization.php).

*Please note:* By default the `Localization` facade will be registered during package discovery. In the following examples we will use this facade directly.

### Get the localized URL

By providing a valid URL and the desired locale you can automatically create a localized URL:

```php
Localization::getLocalizedUrl('https://example.com/page', 'nl');
```

Would return:

```php
https://example.nl/page
```

### Listing supported locale configs

You can either list all configured locales:

```php
Localization::getSupportedLocales();
```

Would return:

```php
['en' => [
    'tld' => '.com',
    'script' => 'Latn',
    'dir' => 'ltr',
    'name' => 'English',
    'native' => 'English'
]]
```

Or list a specific locale by its name:

```php
Localization::getSupportedLocale('en');
```

Would return:

```php
[
    'tld' => '.com',
    'script' => 'Latn',
    'dir' => 'ltr',
    'name' => 'English',
    'native' => 'English'
]
```

Additionally you can simply check if a locale is configured:

```php
Localization::hasSupportedLocale('en');
```

Would return:

```php
true
```

### Resolving locale configs by TLD

Instead of directly using the locale name to resolve a configuration you can also use the TLD:

```php
Localization::getSupportedLocaleByTld('.com');
```

Would return:

```php
[
    'tld' => '.com',
    'script' => 'Latn',
    'dir' => 'ltr',
    'name' => 'English',
    'native' => 'English'
]
```

Or resolve the locale name by TLD:

```php
Localization::getSupportedLocaleNameByTld('.com');
```

Would return:

```php
'en'
```

And similar to the locale name you can check for the existence of a locale config by using its TLD:

```php
Localization::hasSupportedLocaleByTld('en');
```

Would return:

```php
true
```

### Resolving the TLD from an URL

Preferably you wouldn't parse the URL without a lookup table to resolve the TLD. This due to the unusual format of some TLDs. A great package for parsing domains is [jeremykendall/php-domain-parser](https://github.com/jeremykendall/php-domain-parser).

Luckily this package also supports parsing TLDs, matching them on the configured locales:

```php
Localization::getTldFromUrl('https://example.com.local');
```

Would return:

```php
true
```

*Please note:* For this to work `.com.local` needs to be registered as TLD in the supported locales config. This is very effective during development where you can't point multiple domains to your local machine.

### Get attributes from locales

For the current locale or a specific locale you can resolve various configuration attributes using the following helpers:

```php
Localization::getTldForCurrentLocale();
Localization::getNameForCurrentLocale();
Localization::getDirectionForCurrentLocale();
Localization::getScriptForCurrentLocale();
Localization::getNativeForCurrentLocale();
```

```php
Localization::getTldForLocale('en');
Localization::getNameForLocale('en');
Localization::getDirectionForLocale('en');
Localization::getScriptForLocale('en');
Localization::getNativeForLocale('en');
```

Would return:

```php
'.com'
'Latn'
'ltr'
'English'
'English'
```

### Modifying the active locale

When not using the middleware you might want to change the active locale manually:

```php
Localization::setCurrentLocale('en');
```

Or you might just want to check the currently set locale:

```php
Localization::getCurrentLocale();
```

Would return:

```php
'en'
```

During boot we also keep track of the default locale, this is the locale set in the `app.php` config file before any mutations have been made:

```php
Localization::getDefaultLocale();
```

Would return:

```php
'en'
```

## Creating a language selector
Using the helper methods we can create a simple but effective language switcher. The example below uses a [Bootstrap dropdown](https://getbootstrap.com/docs/3.4/components/#dropdowns).

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

## Contributing

Contributions are welcome and will be [fully credited](https://github.com/kevindierkx/laravel-domain-localization/graphs/contributors). Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## License
The MIT License (MIT). Please see [License File](https://github.com/kevindierkx/laravel-domain-localization/blob/master/LICENSE) for more information.
