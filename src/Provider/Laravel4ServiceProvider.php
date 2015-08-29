<?php namespace Kevindierkx\LaravelDomainLocalization\Provider;

use Illuminate\Support\ServiceProvider;
use Kevindierkx\LaravelDomainLocalization\DomainLocalization;

class Laravel4ServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->package('kevindierkx/laravel-domain-localization', 'laravel-domain-localization', __DIR__ . '/..');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerDomainLocalization();
        $this->registerDomainLocaleFilter();
    }

    /**
     * Register the domain localization.
     */
    protected function registerDomainLocalization()
    {
        $this->app->bindShared('domain.localization', function ($app) {
            return new DomainLocalization(
                $app['config'],
                $app['request'],
                $app
            );
        });
    }

    /**
     * Register the domain locale filter.
     */
    protected function registerDomainLocaleFilter()
    {
        $app = $this->app;

        $app->router->filter('domain.locale', function () use ($app) {
            $tld = $app['domain.localization']->getTld();

            if (! is_null($locale = $app['domain.localization']->getSupportedLocaleNameByTld($tld))) {
                $app['domain.localization']->setLocale($locale);
            }
        });
    }
}
