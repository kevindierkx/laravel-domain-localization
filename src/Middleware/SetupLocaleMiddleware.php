<?php namespace Kevindierkx\LaravelDomainLocalization\Middleware;

use Closure;
use Kevindierkx\LaravelDomainLocalization\DomainLocalization;

class SetupLocaleMiddleware
{
    /**
     * @var DomainLocalization
     */
    protected $localization;

    /**
     * Create a new middleware instance.
     *
     * @param  DomainLocalization  $localization
     */
    public function __construct(DomainLocalization $localization)
    {
        $this->localization = $localization;
    }

    /**
     * Handle middleware.
     *
     * @param  mixed    $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $tld = $this->localization->getTld();

        if (! is_null($locale = $this->localization->getSupportedLocaleNameByTld($tld))) {
            $this->localization->setCurrentLocale($locale);
        }

        return $next($request);
    }
}
