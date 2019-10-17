<?php

namespace Kevindierkx\LaravelDomainLocalization\Tests\Concerns;

use Kevindierkx\LaravelDomainLocalization\Exceptions\UnsupportedLocaleException;
use Kevindierkx\LaravelDomainLocalization\Tests\TestCase;
use Localization;

class HasLocaleConfigsTest extends TestCase
{
    public function test_listing_all_locales()
    {
        $this->assertSame(Localization::getSupportedLocales(), ['en' => self::TEST_EN_CONFIG]);
    }

    public function test_listing_a_locale_by_name()
    {
        $this->assertSame(Localization::getSupportedLocale('en'), self::TEST_EN_CONFIG);
    }

    public function test_listing_an_unknown_locale_by_name_throws_an_exception()
    {
        $this->expectException(UnsupportedLocaleException::class);

        Localization::getSupportedLocale('foo');
    }

    public function test_getting_the_tld_for_a_specific_locale()
    {
        $this->assertSame(Localization::getTldForLocale('en'), '.com');
    }

    public function test_getting_unknown_as_the_tld_for_a_specific_locale()
    {
        Localization::addLocale('unknown', []);
        $this->assertSame(Localization::getTldForLocale('unknown'), 'unknown');
    }

    public function test_getting_the_name_for_a_specific_locale()
    {
        $this->assertSame(Localization::getNameForLocale('en'), 'English');
    }

    public function test_getting_unknown_as_the_name_for_a_specific_locale()
    {
        Localization::addLocale('unknown', []);
        $this->assertSame(Localization::getNameForLocale('unknown'), 'unknown');
    }

    public function test_getting_the_direction_for_a_specific_locale()
    {
        $this->assertSame(Localization::getDirectionForLocale('en'), 'ltr');
    }

    public function test_getting_unknown_as_the_direction_for_a_specific_locale()
    {
        Localization::addLocale('unknown', []);
        $this->assertSame(Localization::getDirectionForLocale('unknown'), 'unknown');
    }

    public function test_getting_the_script_for_a_specific_locale()
    {
        $this->assertSame(Localization::getScriptForLocale('en'), 'Latn');
    }

    public function test_getting_unknown_as_the_script_for_a_specific_locale()
    {
        Localization::addLocale('unknown', []);
        $this->assertSame(Localization::getScriptForLocale('unknown'), 'unknown');
    }

    public function test_getting_the_native_for_a_specific_locale()
    {
        $this->assertSame(Localization::getNativeForLocale('en'), 'English');
    }

    public function test_getting_unknonw_as_the_native_for_a_specific_locale()
    {
        Localization::addLocale('unknown', []);
        $this->assertSame(Localization::getNativeForLocale('unknown'), 'unknown');
    }

    public function test_adding_locales()
    {
        $this->assertFalse(Localization::hasSupportedLocale('nl'));
        Localization::addLocale('nl', self::TEST_NL_CONFIG);
        $this->assertTrue(Localization::hasSupportedLocale('nl'));
    }
}
