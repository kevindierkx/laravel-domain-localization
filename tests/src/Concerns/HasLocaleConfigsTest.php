<?php

namespace Kevindierkx\LaravelDomainLocalization\Tests\Concerns;

use Kevindierkx\LaravelDomainLocalization\Exceptions\UnsupportedLocaleException;
use Kevindierkx\LaravelDomainLocalization\Facades\Localization;
use Kevindierkx\LaravelDomainLocalization\Tests\TestCase;

class HasLocaleConfigsTest extends TestCase
{
    public function testAddingLocales(): void
    {
        self::assertFalse(Localization::hasSupportedLocale('nl'));
        Localization::addLocale('nl', self::TEST_NL_CONFIG);
        self::assertTrue(Localization::hasSupportedLocale('nl'));
    }

    public function testListingAllLocales(): void
    {
        self::assertSame(Localization::getSupportedLocales(), ['en' => self::TEST_EN_CONFIG]);
    }

    public function testListingALocaleByName(): void
    {
        self::assertSame(Localization::getSupportedLocale('en'), self::TEST_EN_CONFIG);
    }

    public function testListingAnUnknownLocaleByNameThrowsAnException(): void
    {
        self::expectException(UnsupportedLocaleException::class);

        Localization::getSupportedLocale('foo');
    }

    public function testGettingTheTldForTheCurrentLocale(): void
    {
        self::assertSame(Localization::getTldForCurrentLocale(), '.com');
    }

    public function testGettingTheNameForTheCurrentLocale(): void
    {
        self::assertSame(Localization::getNameForCurrentLocale(), 'English');
    }

    public function testGettingTheDirectionForTheCurrentLocale(): void
    {
        self::assertSame(Localization::getDirectionForCurrentLocale(), 'ltr');
    }

    public function testGettingTheScriptForTheCurrentLocale(): void
    {
        self::assertSame(Localization::getScriptForCurrentLocale(), 'Latn');
    }

    public function testGettingTheNativeForTheCurrentLocale(): void
    {
        self::assertSame(Localization::getNativeForCurrentLocale(), 'English');
    }

    public function testGettingTheTldForASpecificLocale(): void
    {
        self::assertSame(Localization::getTldForLocale('en'), '.com');
    }

    public function testGettingUnknownAsTheTldForASpecificLocale(): void
    {
        Localization::addLocale('unknown', []);
        self::assertSame(Localization::getTldForLocale('unknown'), 'unknown');
    }

    public function testGettingTheNameForASpecificLocale(): void
    {
        self::assertSame(Localization::getNameForLocale('en'), 'English');
    }

    public function testGettingUnknownAsTheNameForASpecificLocale(): void
    {
        Localization::addLocale('unknown', []);
        self::assertSame(Localization::getNameForLocale('unknown'), 'unknown');
    }

    public function testGettingTheDirectionForASpecificLocale(): void
    {
        self::assertSame(Localization::getDirectionForLocale('en'), 'ltr');
    }

    public function testGettingUnknownAsTheDirectionForASpecificLocale(): void
    {
        Localization::addLocale('unknown', []);
        self::assertSame(Localization::getDirectionForLocale('unknown'), 'unknown');
    }

    public function testGettingTheScriptForASpecificLocale(): void
    {
        self::assertSame(Localization::getScriptForLocale('en'), 'Latn');
    }

    public function testGettingUnknownAsTheScriptForASpecificLocale(): void
    {
        Localization::addLocale('unknown', []);
        self::assertSame(Localization::getScriptForLocale('unknown'), 'unknown');
    }

    public function testGettingTheNativeForASpecificLocale(): void
    {
        self::assertSame(Localization::getNativeForLocale('en'), 'English');
    }

    public function testGettingUnknownAsTheNativeForASpecificLocale(): void
    {
        Localization::addLocale('unknown', []);
        self::assertSame(Localization::getNativeForLocale('unknown'), 'unknown');
    }

    public function testGettingTheSupportedLocaleNameByTld(): void
    {
        self::assertSame(Localization::getSupportedLocaleNameByTld('.com'), 'en');
    }

    public function testGettingAnUnknownLocaleNameByTldThrowsAnException(): void
    {
        self::expectException(UnsupportedLocaleException::class);

        Localization::getSupportedLocaleNameByTld('.foo');
    }

    public function testGettingTheSupportedLocaleByTld(): void
    {
        self::assertSame(Localization::getSupportedLocaleByTld('.com'), self::TEST_EN_CONFIG);
    }

    public function testHavingASupportedLocaleByTld(): void
    {
        self::assertSame(Localization::hasSupportedLocaleByTld('.com'), true);
        self::assertSame(Localization::hasSupportedLocaleByTld('.dev'), false);
    }
}
