<?php

namespace Kevindierkx\LaravelDomainLocalization;

use Illuminate\Support\ServiceProvider;

class LocalizationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $app = $this->app;

        $this->setupConfig();

        DomainLocalization::setLocaleGetter(function () use ($app) {
            return $app->getLocale();
        });
        DomainLocalization::setLocaleSetter(function ($locale) use ($app) {
            return $app->setLocale($locale);
        });
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton(DomainLocalization::class, function ($app) {
            return new DomainLocalization(
                $app->getLocale(),
                config('domain-localization.supported_locales', [])
            );
        });
        $this->app->alias(DomainLocalization::class, 'domain.localization');
    }

    /**
     * Setup the package configuration.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $name = 'domain-localization';
        $path = realpath(__DIR__."/config/{$name}.php");

        $this->publishes([$path => config_path("{$name}.php")], 'config');
        $this->mergeConfigFrom($path, $name);
    }
}
