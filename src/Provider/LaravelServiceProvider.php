<?php namespace Kevindierkx\LaravelDomainLocalization\Provider;

use Illuminate\Support\ServiceProvider;
use Kevindierkx\LaravelDomainLocalization\DomainLocalization;

class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $name = 'domain-localization';
        $path = realpath(__DIR__."/../config/{$name}.php");

        $this->publishes([$path => config_path("{$name}.php")], 'config');
        $this->mergeConfigFrom($path, $name);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton('domain.localization', function ($app) {
            return new DomainLocalization(
                $app['config'],
                $app['request'],
                $app
            );
        });
        $this->app->alias(DomainLocalization::class, 'domain.localization');
    }
}
