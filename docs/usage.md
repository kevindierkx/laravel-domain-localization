# Usage

The package provides some useful helper methods. For a full list of methods and method descriptions please refer to the [`DomainLocalization::class`](https://github.com/kevindierkx/laravel-domain-localization/blob/master/src/DomainLocalization.php).

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
