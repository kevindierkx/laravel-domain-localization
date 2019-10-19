<?php

namespace Kevindierkx\LaravelDomainLocalization\Tests\Middleware;

use Kevindierkx\LaravelDomainLocalization\Tests\TestCase;
use Localization;

class SetupLocaleMiddlewareTest extends TestCase
{
    public function test_middleware_switches_between_locales_during_request()
    {
        Localization::addLocale('nl', self::TEST_NL_CONFIG);

        $crawler = $this->call('GET', self::TEST_URL_EN.'/test');

        $this->assertResponseOk();
        $this->assertEquals(
            'English',
            $crawler->getContent()
        );

        $crawler = $this->call('GET', self::TEST_URL_NL.'/test');

        $this->assertResponseOk();
        $this->assertEquals(
            'Nederlands',
            $crawler->getContent()
        );
    }
}
