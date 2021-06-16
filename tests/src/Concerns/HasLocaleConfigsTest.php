<?php

namespace Kevindierkx\LaravelDomainLocalization\Tests\Concerns;

use Kevindierkx\LaravelDomainLocalization\Exceptions\UnsupportedLocaleException;
use Kevindierkx\LaravelDomainLocalization\Tests\TestCase;
use Localization;

class HasLocaleConfigsTest extends TestCase
{
    public function testAddingLocales()
    {
        $this->assertFalse(Localization::hasSupportedLocale('nl'));
        Localization::addLocale('nl', self::TEST_NL_CONFIG);
        $this->assertTrue(Localization::hasSupportedLocale('nl'));
    }

    public function testListingAllLocales()
    {
        $this->assertSame(Localization::getSupportedLocales(), ['en' => self::TEST_EN_CONFIG]);
    }

    public function testListingALocaleByName()
    {
        $this->assertSame(Localization::getSupportedLocale('en'), self::TEST_EN_CONFIG);
    }

    public function testListingAnUnknownLocaleByNameThrowsAnException()
    {
        $this->expectException(UnsupportedLocaleException::class);

        Localization::getSupportedLocale('foo');
    }

    public function testGettingTheTldForTheCurrentLocale()
    {
        $this->assertSame(Localization::getTldForCurrentLocale(), '.com');
    }

    public function testGettingTheNameForTheCurrentLocale()
    {
        $this->assertSame(Localization::getNameForCurrentLocale(), 'English');
    }

    public function testGettingTheDirectionForTheCurrentLocale()
    {
        $this->assertSame(Localization::getDirectionForCurrentLocale(), 'ltr');
    }

    public function testGettingTheScriptForTheCurrentLocale()
    {
        $this->assertSame(Localization::getScriptForCurrentLocale(), 'Latn');
    }

    public function testGettingTheNativeForTheCurrentLocale()
    {
        $this->assertSame(Localization::getNativeForCurrentLocale(), 'English');
    }

    public function testGettingTheTldForASpecificLocale()
    {
        $this->assertSame(Localization::getTldForLocale('en'), '.com');
    }

    public function testGettingUnknownAsTheTldForASpecificLocale()
    {
        Localization::addLocale('unknown', []);
        $this->assertSame(Localization::getTldForLocale('unknown'), 'unknown');
    }

    public function testGettingTheNameForASpecificLocale()
    {
        $this->assertSame(Localization::getNameForLocale('en'), 'English');
    }

    public function testGettingUnknownAsTheNameForASpecificLocale()
    {
        Localization::addLocale('unknown', []);
        $this->assertSame(Localization::getNameForLocale('unknown'), 'unknown');
    }

    public function testGettingTheDirectionForASpecificLocale()
    {
        $this->assertSame(Localization::getDirectionForLocale('en'), 'ltr');
    }

    public function testGettingUnknownAsTheDirectionForASpecificLocale()
    {
        Localization::addLocale('unknown', []);
        $this->assertSame(Localization::getDirectionForLocale('unknown'), 'unknown');
    }

    public function testGettingTheScriptForASpecificLocale()
    {
        $this->assertSame(Localization::getScriptForLocale('en'), 'Latn');
    }

    public function testGettingUnknownAsTheScriptForASpecificLocale()
    {
        Localization::addLocale('unknown', []);
        $this->assertSame(Localization::getScriptForLocale('unknown'), 'unknown');
    }

    public function testGettingTheNativeForASpecificLocale()
    {
        $this->assertSame(Localization::getNativeForLocale('en'), 'English');
    }

    public function testGettingUnknownAsTheNativeForASpecificLocale()
    {
        Localization::addLocale('unknown', []);
        $this->assertSame(Localization::getNativeForLocale('unknown'), 'unknown');
    }

    public function testGettingTheSupportedLocaleNameByTld()
    {
        $this->assertSame(Localization::getSupportedLocaleNameByTld('.com'), 'en');
    }

    public function testGettingAnUnknownLocaleNameByTldThrowsAnException()
    {
        $this->expectException(UnsupportedLocaleException::class);

        Localization::getSupportedLocaleNameByTld('.foo');
    }

    public function testGettingTheSupportedLocaleByTld()
    {
        $this->assertSame(Localization::getSupportedLocaleByTld('.com'), self::TEST_EN_CONFIG);
    }

    public function testHavingASupportedLocaleByTld()
    {
        $this->assertSame(Localization::hasSupportedLocaleByTld('.com'), true);
        $this->assertSame(Localization::hasSupportedLocaleByTld('.dev'), false);
    }
}
