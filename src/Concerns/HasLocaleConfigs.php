<?php

namespace Kevindierkx\LaravelDomainLocalization\Concerns;

use Illuminate\Support\Arr;
use Kevindierkx\LaravelDomainLocalization\Exceptions\UnsupportedLocaleException;

trait HasLocaleConfigs
{
    /**
     * All configured locales.
     *
     * @var array
     */
    protected $supportedLocales = [];

    /**
     * Get the active app locale.
     *
     * @return string
     */
    abstract public function getCurrentLocale(): string;

    /**
     * Add a locale config.
     *
     * @param string $name
     * @param array  $config
     *
     * @return void
     */
    public function addLocale(string $name, array $config): void
    {
        $this->supportedLocales[$name] = $config;
    }

    /**
     * Get an array of all supported locales.
     *
     * @return array
     */
    public function getSupportedLocales(): array
    {
        return $this->supportedLocales;
    }

    /**
     * Get a supported locale.
     *
     * @param string $key
     *
     * @throws \Kevindierkx\LaravelDomainLocalization\Exceptions\UnsupportedLocaleException
     *
     * @return array
     */
    public function getSupportedLocale(string $key): array
    {
        if (! $this->hasSupportedLocale($key)) {
            throw new UnsupportedLocaleException(sprintf(
                'The locale \'%s\' is not in the `supported_locales` array.',
                $key
            ));
        }

        return $this->supportedLocales[$key];
    }

    /**
     * Determine a supported locale exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasSupportedLocale(string $key): bool
    {
        return isset($this->supportedLocales[$key]);
    }

    /**
     * Get tld for current locale.
     *
     * @return string
     */
    public function getTldForCurrentLocale(): string
    {
        return $this->getTldForLocale($this->getCurrentLocale());
    }

    /**
     * Get name for current locale.
     *
     * @return string
     */
    public function getNameForCurrentLocale(): string
    {
        return $this->getNameForLocale($this->getCurrentLocale());
    }

    /**
     * Get direction for current locale.
     *
     * @return string
     */
    public function getDirectionForCurrentLocale(): string
    {
        return $this->getDirectionForLocale($this->getCurrentLocale());
    }

    /**
     * Get script for current locale.
     *
     * @return string
     */
    public function getScriptForCurrentLocale(): string
    {
        return $this->getScriptForLocale($this->getCurrentLocale());
    }

    /**
     * Get native for current locale.
     *
     * @return string
     */
    public function getNativeForCurrentLocale(): string
    {
        return $this->getNativeForLocale($this->getCurrentLocale());
    }

    /**
     * Get tld for locale.
     *
     * @param string $locale
     *
     * @return string
     */
    public function getTldForLocale(string $locale): string
    {
        return $this->getSupportedLocale($locale)['tld'] ?? 'unknown';
    }

    /**
     * Get name for locale.
     *
     * @param string $locale
     *
     * @return string
     */
    public function getNameForLocale(string $locale): string
    {
        return $this->getSupportedLocale($locale)['name'] ?? 'unknown';
    }

    /**
     * Get direction for locale.
     *
     * @param string $locale
     *
     * @return string
     */
    public function getDirectionForLocale(string $locale): string
    {
        return $this->getSupportedLocale($locale)['dir'] ?? 'unknown';
    }

    /**
     * Get script for locale.
     *
     * @param string $locale
     *
     * @return string
     */
    public function getScriptForLocale(string $locale): string
    {
        return $this->getSupportedLocale($locale)['script'] ?? 'unknown';
    }

    /**
     * Get native for locale.
     *
     * @param string $locale
     *
     * @return string
     */
    public function getNativeForLocale(string $locale): string
    {
        return $this->getSupportedLocale($locale)['native'] ?? 'unknown';
    }

    /**
     * Get a supported locale name by tld.
     *
     * @param string $tld
     *
     * @throws \Kevindierkx\LaravelDomainLocalization\Exceptions\UnsupportedLocaleException
     *
     * @return string
     */
    public function getSupportedLocaleNameByTld(string $tld): string
    {
        $key = Arr::first(array_keys($this->supportedLocales), function ($key) use ($tld) {
            return $this->getTldForLocale($key) === $tld;
        });

        return $key ?: throw new UnsupportedLocaleException(sprintf(
            'The TLD \'%s\' is not in the `supported_locales` array.',
            $tld
        ));
    }

    /**
     * Get a supported locale by tld.
     *
     * @param string $tld
     *
     * @throws \Kevindierkx\LaravelDomainLocalization\Exceptions\UnsupportedLocaleException
     *
     * @return array
     */
    public function getSupportedLocaleByTld(string $tld): array
    {
        return $this->getSupportedLocale(
            $this->getSupportedLocaleNameByTld($tld)
        );
    }

    /**
     * Determine a supported locale exists for the tld.
     *
     * @param string $tld
     *
     * @return bool
     */
    public function hasSupportedLocaleByTld(string $tld): bool
    {
        try {
            $this->getSupportedLocaleNameByTld($tld);
        } catch (UnsupportedLocaleException $e) {
            return false;
        }

        return true;
    }
}
