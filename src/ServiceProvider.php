<?php

namespace Kevindierkx\LaravelDomainLocalization;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $app = $this->app;

        $this->defineConfigPublishing();

        DomainLocalization::setLocaleGetter(function () use ($app) {
            return $app->getLocale();
        });
        DomainLocalization::setLocaleSetter(function ($locale) use ($app) {
            $app->setLocale($locale);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        if (! defined('LARAVEL_DL_PATH')) {
            define('LARAVEL_DL_PATH', realpath(__DIR__.'/../'));
        }

        $this->mergeConfigFrom(LARAVEL_DL_PATH.'/config/domain-localization.php', 'domain-localization');

        $this->app->singleton('domain.localization', function ($app) {
            return new DomainLocalization(
                $app->getLocale(),
                $app->make('config')->get('domain-localization.supported_locales', [])
            );
        });
    }

    /**
     * Define the configuration publishing.
     *
     * @return void
     */
    protected function defineConfigPublishing(): void
    {
        $this->publishes([
            LARAVEL_DL_PATH.'/config/domain-localization.php' => config_path('domain-localization.php'),
        ], 'config');
    }
}
