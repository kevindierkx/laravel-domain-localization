<?php

namespace Kevindierkx\LaravelDomainLocalization;

use Closure;
use Illuminate\Http\Request;

class DomainLocalization
{
    use Concerns\HasLocaleConfigs;

    /**
     * The default app locale.
     *
     * @var string
     */
    protected $defaultLocale;

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The callback for resolving the active locale.
     *
     * @var Closure
     */
    protected $localeGetter;

    /**
     * The callback for setting the active locale.
     *
     * @var Closure
     */
    protected $localeSetter;

    /**
     * Creates a new domain localization instance.
     *
     * @param  \Illuminate\Config\Repository       $configRepository
     * @param  \Illuminate\Http\Request            $request
     * @param  \Illuminate\Foundation\Application  $app
     */
    public function __construct(
        string $defaultLocale,
        Closure $localeGetter,
        Closure $localeSetter,
        array $locales,
        Request $request
    ) {
        $this->defaultLocale = $defaultLocale;

        $this->localeGetter = $localeGetter;
        $this->localeSetter = $localeSetter;

        $this->request = $request;

        foreach ($locales as $name => $config) {
            $this->addLocale($name, $config);
        }
    }

    /**
     * Get the default application locale.
     *
     * @return string
     */
    public function getDefaultLocale() : string
    {
        return $this->defaultLocale;
    }

    /**
     * Get the active app locale.
     *
     * @return string
     */
    public function getCurrentLocale() : string
    {
        return call_user_func($this->localeGetter);
    }

    /**
     * Set the active app locale.
     *
     * @param  string  $locale
     * @return self
     */
    public function setCurrentLocale($locale) : self
    {
        call_user_func($this->localeSetter, $locale);

        return $this;
    }

    /**
     * Get top level domain.
     *
     * @return string
     */
    public function getTld() : string
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
    public function getLocalizedUrl(string $locale) : string
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
}
