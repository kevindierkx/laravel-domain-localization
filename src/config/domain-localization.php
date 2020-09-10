<?php

return [
     // Uncomment the languages that your site supports - or add new ones.
     // These are sorted by the native name, which is the order you might show them in a language selector.
     // Regional languages are sorted by their base language, so "British English" sorts as "English, British"
     //
     // The tld config determines when a locale is used. For example 'example.com' would trigger the 'en'
     // locale because the tld value corresponds to '.com'.
     //
     // Based upon mcamara/laravel-localization:
     // https:// github.com/mcamara/laravel-localization/blob/master/src/config/config.php
    'supported_locales' => [
        'en' => ['tld' => '.com', 'script' => 'Latn', 'dir' => 'ltr', 'name' => 'English',            'native' => 'English'],
        // 'en-AU' => ['tld' => '.au',  'script' => 'Latn', 'dir' => 'ltr', 'name' => 'Australian English', 'native' => 'Australian English'],
        // 'en-GB' => ['tld' => '.uk',  'script' => 'Latn', 'dir' => 'ltr', 'name' => 'British English',    'native' => 'British English'],
        // 'en-US' => ['tld' => '.us',  'script' => 'Latn', 'dir' => 'ltr', 'name' => 'U.S. English',       'native' => 'U.S. English'],
        // 'es'    => ['tld' => '.es',  'script' => 'Latn', 'dir' => 'ltr', 'name' => 'Spanish',            'native' => 'español'],
        // 'nl'    => ['tld' => '.nl',  'script' => 'Latn', 'dir' => 'ltr', 'name' => 'Dutch',              'native' => 'Nederlands'],
    ],
];
