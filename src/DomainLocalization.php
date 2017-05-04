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

        $this->validateLocale($this->defaultLocale);
    }

    /**
     * Get the current locale.
     *
     * @return string
     */
    public function getCurrentLocale()
    {
        return $this->app->getLocale();
    }

    /**
     * Set the current locale.
     *
     * @param  string  $locale
     */
    public function setCurrentLocale($locale)
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
     * @return array|null
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
        $host = $this->request->getHttpHost();

        // Try to match the locale using the supported locales.
        // We do it this way to support non standard tld combinations like '.es.dev'.
        foreach ($this->supportedLocales as $locale) {
            if (isset($locale['tld']) && strpos($host, $locale['tld']) !== false) {
                return $locale['tld'];
            }
        }

        // When we don't match anything the locale might not be configured.
        // We fallback to returning the last item after the last period.
        return substr(strrchr($host, '.'), 0);
    }

    /**
     * Returns the current URL adapted to $locale.
     *
     * @param  string  $locale
     * @throws \Kevindierkx\LaravelDomainLocalization\UnsupportedLocaleException
     * @return string
     */
    public function getLocalizedUrl($locale)
    {
        // We validate the supplied locale before we mutate the current URL
        // to make sure the locale exists and we don't return an invalid URL.
        $this->validateLocale($locale);

        return str_replace(
            $this->getTld(),
            $this->getTldForLocale($locale),
            $this->request->getUri()
        );
    }

    /**
     * Get tld for current locale.
     *
     * @return string|null
     */
    public function getTldForCurrentLocale()
    {
        return $this->getTldForLocale($this->getCurrentLocale());
    }

    /**
     * Get name for current locale.
     *
     * @return string|null
     */
    public function getNameForCurrentLocale()
    {
        return $this->getNameForLocale($this->getCurrentLocale());
    }

    /**
     * Get direction for current locale.
     *
     * @return string|null
     */
    public function getDirectionForCurrentLocale()
    {
        return $this->getDirectionForLocale($this->getCurrentLocale());
    }

    /**
     * Get script for current locale.
     *
     * @return string|null
     */
    public function getScriptForCurrentLocale()
    {
        return $this->getScriptForLocale($this->getCurrentLocale());
    }

    /**
     * Get native for current locale.
     *
     * @return string|null
     */
    public function getNativeForCurrentLocale()
    {
        return $this->getNativeForLocale($this->getCurrentLocale());
    }

    /**
     * Get tld for locale.
     *
     * @param  string  $locale
     * @return string|null
     */
    public function getTldForLocale($locale)
    {
        return $this->getSupportedLocale($locale)['tld'];
    }

    /**
     * Get name for locale.
     *
     * @param  string  $locale
     * @return string|null
     */
    public function getNameForLocale($locale)
    {
        return $this->getSupportedLocale($locale)['name'];
    }

    /**
     * Get direction for locale.
     *
     * @param  string  $locale
     * @return string|null
     */
    public function getDirectionForLocale($locale)
    {
        return $this->getSupportedLocale($locale)['direction'];
    }

    /**
     * Get script for locale.
     *
     * @param  string  $locale
     * @return string|null
     */
    public function getScriptForLocale($locale)
    {
        return $this->getSupportedLocale($locale)['script'];
    }

    /**
     * Get native for locale.
     *
     * @param  string  $locale
     * @return string|null
     */
    public function getNativeForLocale($locale)
    {
        return $this->getSupportedLocale($locale)['native'];
    }

    /**
     * Validate the locale exists in the supported locales array.
     *
     * @param  string  $locale
     * @throws \Kevindierkx\LaravelDomainLocalization\UnsupportedLocaleException
     */
    protected function validateLocale($locale)
    {
        if (! $this->hasSupportedLocale($locale)) {
            throw new UnsupportedLocaleException("The locale [$locale] is not in the supported locales array.");
        }
    }
}
