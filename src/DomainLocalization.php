<?php namespace Kevindierkx\LaravelDomainLocalization;

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class DomainLocalization
{
    /**
     * @var string
     */
    protected $defaultLocale;

    /**
     * @var array
     */
    protected $supportedLocales = [];

    /**
     * @var \Illuminate\Config\Repository
     */
    protected $configRepository;

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Creates new instance.
     *
     * @param  \Illuminate\Config\Repository       $configRepository
     * @param  \Illuminate\Http\Request            $request
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @throws \Kevindierkx\LaravelDomainLocalization\UnsupportedLocaleException
     */
    public function __construct(
        Repository $configRepository,
        Request $request,
        Application $app
    ) {
        $this->configRepository = $configRepository;
        $this->request = $request;
        $this->app = $app;

        $this->boot();
    }

    /**
     * Boot the class and make sure the application has a supported default locale.
     */
    protected function boot()
    {
        $this->defaultLocale = $this->configRepository->get('app.locale');
        $this->supportedLocales = $this->configRepository->get('laravel-domain-localization::supported_locales');

        if (! $this->hasSupportedLocale($this->getDefaultLocale())) {
            throw new UnsupportedLocaleException("Laravel's default locale is not in the supported locales array.");
        }
    }

    /**
     * Get the current locale.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->app->getLocale();
    }

    /**
     * Set the current locale.
     *
     * @param  string  $locale
     */
    public function setLocale($locale)
    {
        $this->app->setLocale($locale);
    }

    /**
     * Get the default locale.
     *
     * @return string
     */
    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * Get an array of all supported locales.
     *
     * @return array
     */
    public function getSupportedLocales()
    {
        return $this->supportedLocales;
    }

    /**
     * Get a supported locale.
     *
     * @param  string  $key
     * @return string|null
     */
    public function getSupportedLocale($key)
    {
        if ($this->hasSupportedLocale($key)) {
            return $this->supportedLocales[$key];
        }
    }

    /**
     * Determine a supported locale exists.
     *
     * @return bool
     */
    public function hasSupportedLocale($key)
    {
        return isset($this->supportedLocales[$key]);
    }

    /**
     * Get a supported locale by tld.
     *
     * @param  string  $tld
     * @return array|null
     */
    public function getSupportedLocaleByTld($tld)
    {
        if (! is_null($key = $this->getSupportedLocaleNameByTld($tld))) {
            return $this->supportedLocales[$key];
        }
    }

    /**
     * Get a supported locale name by tld.
     *
     * @param  string  $tld
     * @return string|null
     */
    public function getSupportedLocaleNameByTld($tld)
    {
        foreach ($this->supportedLocales as $key => $value) {
            if ($value['tld'] == $tld) {
                return $key;
            }
        }
    }

    /**
     * Determine a supported locale exists for the tld.
     *
     * @param  string  $tld
     * @return bool
     */
    public function hasSupportedLocaleByTld($tld)
    {
        return ! is_null($this->getSupportedLocaleNameByTld($tld));
    }

    /**
     * Get top level domain.
     *
     * @return string
     */
    public function getTld()
    {
        return substr(strrchr($this->request->getHttpHost(), '.'), 0);
    }
}
