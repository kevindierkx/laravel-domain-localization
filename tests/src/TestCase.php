<?php

namespace Kevindierkx\LaravelDomainLocalization\Tests;

use Kevindierkx\LaravelDomainLocalization\DomainLocalization;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    const TEST_EN_CONFIG = [
        'tld' => '.com',
        'script' => 'Latn',
        'dir' => 'ltr',
        'name' => 'English',
        'native' => 'English',
    ];

    const TEST_NL_CONFIG = [
        'tld' => '.nl',
        'script' => 'Latn',
        'dir' => 'ltr',
        'name' => 'Dutch',
        'native' => 'Nederlands',
    ];

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Kevindierkx\LaravelDomainLocalization\LocalizationServiceProvider::class,
        ];
    }

    /**
     * Get package aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Localization' => \Kevindierkx\LaravelDomainLocalization\Facades\Localization::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application   $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.locale', 'en');
        $app['config']->set('domain-localization.supported_locales', ['en' => self::TEST_EN_CONFIG]);
    }
}
