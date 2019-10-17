<?php

namespace Kevindierkx\LaravelDomainLocalization\Middleware;

use Closure;
use Kevindierkx\LaravelDomainLocalization\DomainLocalization;

class SetupLocaleMiddleware
{
    /**
     * @var \Kevindierkx\LaravelDomainLocalization\DomainLocalization
     */
    protected $localization;

    /**
     * Create a new middleware instance.
     *
     * @param  \Kevindierkx\LaravelDomainLocalization\DomainLocalization  $localization
     */
    public function __construct(DomainLocalization $localization)
    {
        $this->localization = $localization;
    }

    /**
     * Handle an incoming request.
     *
     * @param  mixed    $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $tld = $this->localization->getTld();

        if ($locale = $this->localization->getSupportedLocaleNameByTld($tld)) {
            $this->localization->setCurrentLocale($locale);
        }

        return $next($request);
    }
}
