<?php

namespace Kevindierkx\LaravelDomainLocalization\Tests;

use Kevindierkx\LaravelDomainLocalization\DomainLocalization;
use Kevindierkx\LaravelDomainLocalization\Exceptions\InvalidUrlException;
use Kevindierkx\LaravelDomainLocalization\Exceptions\UnsupportedLocaleException;
use Kevindierkx\LaravelDomainLocalization\Facades\Localization;
use ReflectionClass;

class DomainLocalizationTest extends TestCase
{
    public function testNotHavingTheDefaultLocaleInTheSupportedLocalesThrowsAnException(): void
    {
        self::expectException(UnsupportedLocaleException::class);

        new DomainLocalization('foo', []);
    }

    public function testGettingDefaultLocaleMatchesTheAppLocale(): void
    {
        self::assertSame(Localization::getDefaultLocale(), $this->app['config']->get('app.locale'));
    }

    public function testGettingTheCurrentLocaleMatchesTheAppLocale(): void
    {
        self::assertSame(Localization::getCurrentLocale(), 'en');
    }

    public function testSettingTheCurrentLocaleMatchesTheAppLocale(): void
    {
        Localization::setCurrentLocale('foo');

        self::assertSame($this->app['config']->get('app.locale'), 'foo');
    }

    public function testGettingTheTldMatchesTheCorrectTldFromTheSupportedLocales(): void
    {
        Localization::addLocale('custom', ['tld' => self::TEST_TLD_CUSTOM]);

        self::assertSame(Localization::getTldFromUrl('https://example'.self::TEST_TLD_CUSTOM.'/test'), self::TEST_TLD_CUSTOM);
    }

    public function testGettingTheTldMatchesTheFallbackAndNotAnIncorrectTldWhenTheTldIsNotExactlyInTheSupportedLocales(): void
    {
        self::assertSame(Localization::getTldFromUrl('https://example'.self::TEST_TLD_CUSTOM.'/test'), '.dev');
    }

    public function testGettingTheTldFromAnIncorrectUrlThrowsAnException(): void
    {
        self::expectException(InvalidUrlException::class);

        Localization::getTldFromUrl('test.com');
    }

    /**
     * @param string $a
     * @param string $b
     * @param bool   $isPositive
     *
     * @dataProvider getCompareDataProvider
     */
    public function testCompareToAlwaysFavorTheLongestString(string $a, string $b, bool $isPositive): void
    {
        $class = new ReflectionClass(DomainLocalization::class);
        $method = $class->getMethod('compareStrLength');
        $method->setAccessible(true);

        self::assertSame($method->invokeArgs($this->app['domain.localization'], [$a, $b]) >= 0, $isPositive);
    }

    public function getCompareDataProvider(): array
    {
        return [
            ['.dev',     '.com.dev', true],
            ['.nl.dev',  '.dev',     false],
            ['.test.nl', '.nl',      false],
        ];
    }

    public function testGettingLocalizedUrl(): void
    {
        Localization::addLocale('nl', self::TEST_NL_CONFIG);

        self::assertSame(Localization::getLocalizedUrl(self::TEST_URL_EN.'/test', 'nl'), self::TEST_URL_NL.'/test');
    }

    public function testGettingUnknownLocalizedUrlThrowsAnException(): void
    {
        self::expectException(UnsupportedLocaleException::class);

        Localization::getLocalizedUrl(self::TEST_URL_EN.'/test', 'nl');
    }
}
