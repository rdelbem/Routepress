<?php

declare(strict_types=1);

namespace Routepress\Tests;

use PHPUnit\Framework\TestCase;
use Routepress\ApiKey;

final class ApiKeyTest extends TestCase
{
    public function testHeadersBuildsTheHeaderArray(): void
    {
        self::assertSame(['X-Api-Key' => 'secret'], ApiKey::headers('secret'));
        self::assertSame(
            ['Authorization' => 'Bearer secret'],
            ApiKey::headers('secret', 'Authorization', 'Bearer ')
        );
    }

    public function testWithHeadersMergesIntoExistingArguments(): void
    {
        $args = ApiKey::withHeaders(
            ['timeout' => 5, 'headers' => ['Accept' => 'application/json']],
            'secret'
        );

        self::assertSame(
            [
                'timeout' => 5,
                'headers' => ['Accept' => 'application/json', 'X-Api-Key' => 'secret'],
            ],
            $args
        );
    }

    public function testWithHeadersHandlesMissingHeaders(): void
    {
        self::assertSame(
            ['headers' => ['X-Api-Key' => 'secret']],
            ApiKey::withHeaders([], 'secret')
        );
    }

    public function testWithHeadersReplacesNonArrayHeaders(): void
    {
        self::assertSame(
            ['headers' => ['X-Api-Key' => 'secret']],
            ApiKey::withHeaders(['headers' => 'invalid'], 'secret')
        );
    }

    public function testWithHeadersSupportsAPrefix(): void
    {
        self::assertSame(
            ['headers' => ['Authorization' => 'Bearer secret']],
            ApiKey::withHeaders([], 'secret', 'Authorization', 'Bearer ')
        );
    }
}
