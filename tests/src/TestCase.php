<?php

namespace Kevindierkx\LaravelDomainLocalization\Tests;

abstract class TestCase extends \Orchestra\Testbench\BrowserKit\TestCase
{
    public const TEST_URL_EN = 'https://test.com';

    public const TEST_URL_NL = 'https://test.nl';

    public const TEST_TLD_CUSTOM = '.com.dev';

    public const TEST_HTTP_HOST = 'test.local.dev';

    public const TEST_EN_CONFIG = [
        'tld' => '.com',
        'script' => 'Latn',
        'dir' => 'ltr',
        'name' => 'English',
        'native' => 'English',
    ];

    public const TEST_NL_CONFIG = [
        'tld' => '.nl',
        'script' => 'Latn',
        'dir' => 'ltr',
        'name' => 'Dutch',
        'native' => 'Nederlands',
    ];

    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app)
    {
        return [
            \Kevindierkx\LaravelDomainLocalization\ServiceProvider::class,
        ];
    }

    /**
     * Get package aliases.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array<string, class-string>
     */
    protected function getPackageAliases($app): array
    {
        return [
            'Localization' => \Kevindierkx\LaravelDomainLocalization\Facades\Localization::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
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
        ], function () use ($app): void {
            $app['router']->get('/test', ['as' => 'test', function () use ($app) {
                return $app['translator']->get('DomainLocalizationTest::data.native');
            }]);
        });
    }

    /**
     * Create fake request.
     *
     * @param string               $uri
     * @param string               $method
     * @param array                $parameters
     * @param array                $cookies
     * @param array                $files
     * @param array                $server
     * @param resource|string|null $content
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
    ) {
        return \Illuminate\Http\Request::createFromBase(
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
