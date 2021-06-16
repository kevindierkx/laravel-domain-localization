<?php

namespace Kevindierkx\LaravelDomainLocalization\Tests;

use Kevindierkx\LaravelDomainLocalization\DomainLocalization;
use Kevindierkx\LaravelDomainLocalization\Exceptions\InvalidUrlException;
use Kevindierkx\LaravelDomainLocalization\Exceptions\UnsupportedLocaleException;
use Localization;

class DomainLocalizationTest extends TestCase
{
    public function testNotHavingTheDefaultLocaleInTheSupportedLocalesThrowsAnException()
    {
        $this->expectException(UnsupportedLocaleException::class);

        new DomainLocalization('foo', []);
    }

    public function testGettingDefaultLocaleMatchesTheAppLocale()
    {
        $this->assertSame(Localization::getDefaultLocale(), $this->app['config']->get('app.locale'));
    }

    public function testGettingTheCurrentLocaleMatchesTheAppLocale()
    {
        $this->assertSame(Localization::getCurrentLocale(), 'en');
    }

    public function testSettingTheCurrentLocaleMatchesTheAppLocale()
    {
        Localization::setCurrentLocale('foo');

        $this->assertSame($this->app['config']->get('app.locale'), 'foo');
    }

    public function testGettingTheTldMatchesTheCorrectTldFromTheSupportedLocales()
    {
        Localization::addLocale('custom', ['tld' => self::TEST_TLD_CUSTOM]);

        $this->assertSame(Localization::getTldFromUrl('https://example'.self::TEST_TLD_CUSTOM.'/test'), self::TEST_TLD_CUSTOM);
    }

    public function testGettingTheTldMatchesTheFallbackAndNotAnIncorrectTldWhenTheTldIsNotExactlyInTheSupportedLocales()
    {
        $this->assertSame(Localization::getTldFromUrl('https://example'.self::TEST_TLD_CUSTOM.'/test'), '.dev');
    }

    public function testGettingTheTldFromAnIncorrectUrlThrowsAnException()
    {
        $this->expectException(InvalidUrlException::class);

        Localization::getTldFromUrl('test.com');
    }

    /**
     * @param string $a
     * @param string $b
     * @param bool   $isPositive
     *
     * @dataProvider getCompareDataProvider
     */
    public function testCompareToAlwaysFavorTheLongestString(string $a, string $b, bool $isPositive)
    {
        $class = new \ReflectionClass(DomainLocalization::class);
        $method = $class->getMethod('compareStrLength');
        $method->setAccessible(true);

        $this->assertSame($method->invokeArgs($this->app['domain.localization'], [$a, $b]) >= 0, $isPositive);
    }

    public function getCompareDataProvider()
    {
        return [
            ['.dev',     '.com.dev', true],
            ['.nl.dev',  '.dev',     false],
            ['.test.nl', '.nl',      false],
        ];
    }

    public function testGettingLocalizedUrl()
    {
        Localization::addLocale('nl', self::TEST_NL_CONFIG);

        $this->assertSame(Localization::getLocalizedUrl(self::TEST_URL_EN.'/test', 'nl'), self::TEST_URL_NL.'/test');
    }

    public function testGettingUnknownLocalizedUrlThrowsAnException()
    {
        $this->expectException(UnsupportedLocaleException::class);

        Localization::getLocalizedUrl(self::TEST_URL_EN.'/test', 'nl');
    }
}
