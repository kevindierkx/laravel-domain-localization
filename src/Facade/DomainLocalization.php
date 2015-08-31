<?php namespace Kevindierkx\LaravelDomainLocalization\Facade;

use Illuminate\Support\Facades\Facade;

class DomainLocalization extends Facade
{
    /**
    * Get the registered name of the component.
    *
    * @return string
    */
    protected static function getFacadeAccessor()
    {
        return 'domain.localization';
    }
}
