<?php

namespace Kevindierkx\LaravelDomainLocalization\Middleware;

use Closure;
use Illuminate\Http\Request;
use Kevindierkx\LaravelDomainLocalization\Facades\Localization;

class SetupLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure                  $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $tld = Localization::getTldFromUrl($request->getUri());

        if ($locale = Localization::getSupportedLocaleNameByTld($tld)) {
            Localization::setCurrentLocale($locale);
        }

        return $next($request);
    }
}
