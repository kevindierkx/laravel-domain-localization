# Laravel domain localization
A tool for easy i18n domain based localization in Laravel applications.

## Installation
_This package is build for [Laravel framework](http://laravel.com) based applications._

### Requirements
- PHP >= 5.4.0
- Laravel 4.2+

### Composer installation
You must modify your `composer.json` file and run `composer update` to include the latest version of the package in your project:

```json
"require": {
    "kevindierkx/laravel-domain-localization": "1.0.*"
}
```

Or you can run the `composer require` command from your terminal:

```
composer require kevindierkx/laravel-domain-localization:1.0.x
```

### Service provider
Open `app/config/app.php` and register the required service provider.

```php
'providers' => [
    'Kevindierkx\LaravelDomainLocalization\Provider\Laravel4ServiceProvider',
]
```

If you'd like to make configuration changes in the configuration file you can publish it with the following Artisan command:

```
php artisan vendor:publish --provider="Kevindierkx\LaravelDomainLocalization\Provider\Laravel4ServiceProvider"
```

### Facade
The facade is used for easy access to the domain localization helper. If you would like to use the facade you need to open `app/config/app.php` and register the facade in the aliases array.

```php
'aliases' => [
    'Localization' => 'Kevindierkx\LaravelDomainLocalization\Facade\DomainLocalization',
]
```

## Usage
The Laravel Domain Localization uses the URL given for the request. In order to achieve this purpose it uses a filter, to utilize the filter a route group should be added into the `app/routes.php` file. It will filter all pages that should be localized.

```php
Route::group([
    'before' => 'domain.locale'
], function() {

    // Localized routes should go here.

});

// Other routes not triggering the localization filter go here.
```

Once this route group is added to the routes file, the user can access all locales added into `supported_locales` ('en' by default, look at the config section to change the supported locales).

For example, when you add the dutch locale `nl` the user could access two different locales, using the following addresses:

```
http://example.com
http://example.nl
```

If the locale is not defined in `supported_locales`, the system will use the application default locale.

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

### Determine a supported locale exists
Determines a supported currently configured locale exists by 'name'.

```php
{{ Localization::hasSupportedLocale('en') }}
```

### Get supported locale by TLD
Returns a supported currently configured locale 'name' by top level domain.

```php
{{ Localization::getSupportedLocaleByTld('.com') }}
```

### Get supported locale 'name' by TLD
Returns a supported currently configured locale by top level domain.

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

### Get current locale
Returns the current locale.

```php
{{ Localization::getCurrentLocale() }}
```

### Set current locale
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
