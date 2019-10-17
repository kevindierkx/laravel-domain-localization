<?php

namespace Kevindierkx\LaravelDomainLocalization;

use Closure;
use Illuminate\Http\Request;
use Kevindierkx\LaravelDomainLocalization\Exceptions\UnsupportedLocaleException;

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
     * The callback for resolving the active locale.
     *
     * @var Closure
     */
    protected static $localeGetter;

    /**
     * The callback for setting the active locale.
     *
     * @var Closure
     */
    protected static $localeSetter;

    /**
     * The request instance used for resolving URIs and TLDs.
     *
     * @var \Illuminate\Http\Request
     */
    protected static $requestInstance;

    /**
     * Creates a new domain localization instance.
     *
     * @param  \Illuminate\Config\Repository       $configRepository
     * @param  \Illuminate\Http\Request            $request
     * @param  \Illuminate\Foundation\Application  $app
     */
    public function __construct(string $defaultLocale, array $locales)
    {
        $this->defaultLocale = $defaultLocale;

        foreach ($locales as $name => $config) {
            $this->addLocale($name, $config);
        }

        if (empty($this->supportedLocales[$defaultLocale])) {
            throw new UnsupportedLocaleException(
                'The default locale is not configured in the `supported_locales` array.'
            );
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
        return call_user_func(static::$localeGetter);
    }

    /**
     * Set the active app locale.
     *
     * @param  string  $locale
     * @return self
     */
    public function setCurrentLocale($locale) : self
    {
        call_user_func(static::$localeSetter, $locale);

        return $this;
    }

    /**
     * Get top level domain.
     *
     * @return string
     */
    public function getTld() : string
    {
        $host = static::resolveHttpHost();

        // Try to match the locale using the supported locales.
        // We do it this way to support non standard tld combinations like '.es.dev'.
        foreach ($this->supportedLocales as $locale) {
            if (isset($locale['tld']) && strpos($host, $locale['tld']) !== false) {
                return $locale['tld'];
            }
        }

        // When we don't match anything the locale might not be configured.
        // We will default to the last item after the last period.
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
            static::resolveUri()
        );
    }

    public static function resolveUri() : string
    {
        return static::$requestInstance->getUri();
    }

    public static function resolveHttpHost() : string
    {
        return static::$requestInstance->getHttpHost();
    }

    /**
     * Set the locale getter closure.
     *
     * @param  Closure  $closure
     * @return void
     */
    public static function setLocaleGetter(Closure $closure) : void
    {
        static::$localeGetter = $closure;
    }

    /**
     * Set the locale setter closure.
     *
     * @param  Closure  $closure
     * @return void
     */
    public static function setLocaleSetter(Closure $closure) : void
    {
        static::$localeSetter = $closure;
    }

    /**
     * Set the request resolver closure.
     *
     * @param  \Illuminate\Http\Request  $instance
     * @return void
     */
    public static function setRequestInstance(Request $request) : void
    {
        static::$requestInstance = $request;
    }
}
