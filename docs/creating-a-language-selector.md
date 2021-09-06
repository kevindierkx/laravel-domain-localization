# Creating a language selector

Using the helper methods we can create a simple but effective language switcher. The example below uses a [Bootstrap dropdown](https://getbootstrap.com/docs/3.4/components/#dropdowns).

```php
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
```
