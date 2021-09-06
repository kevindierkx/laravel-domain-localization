<?php

namespace Kevindierkx\LaravelDomainLocalization\Tests\Middleware;

use Kevindierkx\LaravelDomainLocalization\Facades\Localization;
use Kevindierkx\LaravelDomainLocalization\Tests\TestCase;

class SetupLocaleMiddlewareTest extends TestCase
{
    public function testMiddlewareSwitchesBetweenLocalesDuringRequest(): void
    {
        Localization::addLocale('nl', self::TEST_NL_CONFIG);

        $crawler = $this->call('GET', self::TEST_URL_EN.'/test');

        self::assertResponseOk();
        $crawler->assertSee('English');

        $crawler = $this->call('GET', self::TEST_URL_NL.'/test');

        self::assertResponseOk();
        $crawler->assertSee('Nederlands');
    }
}
