<?php

namespace Kevindierkx\LaravelDomainLocalization;

use Closure;
use Illuminate\Http\Request;
use Kevindierkx\LaravelDomainLocalization\Exceptions\InvalidUrlException;
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
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function getTldFromUrl(string $url) : string
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException(sprintf(
                'The url \'%s\' could not be parsed, make sure you provide a full URL.',
                $url
            ));
        }

        $host = parse_url($url, PHP_URL_HOST);

        $matches = [];

        // Try to match the locale using the supported locales.
        // This way we can support non standard tld combinations like '.com.dev'.
        foreach ($this->getSupportedLocales() as $config) {
            // We ensure the match is at the end of the string to prevent '.com'
            // being matched on '.com.dev'.
            if (
                isset($config['tld'])
                && strpos($host, $config['tld']) !== false
                && strlen($host) - strlen($config['tld']) === strrpos($host, $config['tld'])
            ) {
                $matches[] = $config['tld'];
            }
        }

        // Multiple matches will be sorted on length, the best matching combination
        // will be used as the correct match, ie: '.com.dev' vs '.dev'.
        if (!empty($matches)) {
            usort($matches, [$this, 'compareStrLength']);

            return reset($matches);
        }

        // When we don't match anything the locale might not be configured.
        // We will default to the last item after the last period.
        return substr(strrchr($host, '.'), 0);
    }

    /**
     * Resolve the length difference of two strings, used in the getTld method
     * for comparing the best matching TLD. Negative results would push the
     * item to the start since the TLD would be longer.
     *
     * @param  string  $a
     * @param  string  $b
     * @return int
     */
    protected function compareStrLength(string $a, string $b) : int
    {
        return strlen($b) - strlen($a);
    }

    /**
     * Localize the URL to the provided locale key or to the default locale when
     * no locale is provided.
     *
     * @param  string  $url
     * @param  string|null  $key
     * @throws \Kevindierkx\LaravelDomainLocalization\UnsupportedLocaleException
     * @return string
     */
    public function getLocalizedUrl(string $url, string $key = null) : string
    {
        $key = $key ?: $this->getDefaultLocale();

        // We validate the supplied locale before we mutate the current URL
        // to make sure the locale exists and we don't return an invalid URL.
        if (!$this->hasSupportedLocale($key)) {
            throw new UnsupportedLocaleException(sprintf(
                'The locale \'%s\' is not in the `supported_locales` array.',
                $key
            ));
        }

        return str_replace(
            $this->getTldFromUrl($url),
            $this->getTldForLocale($key),
            $url
        );
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
}
