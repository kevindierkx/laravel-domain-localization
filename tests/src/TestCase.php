<?php

namespace Kevindierkx\LaravelDomainLocalization\Tests;

use Kevindierkx\LaravelDomainLocalization\DomainLocalization;

abstract class TestCase extends \Orchestra\Testbench\BrowserKit\TestCase
{
    const TEST_URL_EN = 'https://test.com';

    const TEST_URL_NL = 'https://test.nl';

    const TEST_TLD_CUSTOM = '.com.dev';

    const TEST_HTTP_HOST = 'test.local.dev';

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
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.locale', 'en');
        $app['config']->set('domain-localization.supported_locales', ['en' => self::TEST_EN_CONFIG]);

        $app['translator']->getLoader()->addNamespace('DomainLocalizationTest', realpath(dirname(__FILE__)).'/../lang');
        $app['translator']->load('DomainLocalizationTest', 'data', 'en');
        $app['translator']->load('DomainLocalizationTest', 'data', 'nl');

        $app['router']->group([
            'middleware' => [
                \Kevindierkx\LaravelDomainLocalization\Middleware\SetupLocaleMiddleware::class,
            ],
        ], function () use ($app) {
            $app['router']->get('/test', ['as'=> 'test', function () use ($app) {
                return $app['translator']->get('DomainLocalizationTest::data.native');
            }, ]);
        });
    }

    protected function refreshApplication()
    {
        parent::refreshApplication();
    }

    /**
     * Create fake request.
     *
     * @param  string $uri
     * @param  string $method
     * @param  array  $parameters
     * @param  array  $cookies
     * @param  array  $files
     * @param  array  $server
     * @param  mixed  $content
     *
     * @return \Illuminate\Http\Request
     */
    protected function createRequest(
        $uri = '/test',
        $method = 'GET',
        $parameters = [],
        $cookies = [],
        $files = [],
        $server = ['CONTENT_TYPE' => 'application/json'],
        $content = null
    )
    {
        $request = new \Illuminate\Http\Request;
        return $request->createFromBase(
            \Symfony\Component\HttpFoundation\Request::create(
                $uri,
                'GET',
                [],
                [],
                [],
                $server,
                $content
            )
        );
    }
}
