<?php

declare(strict_types=1);

namespace Routepress\Tests\Types;

use PHPUnit\Framework\TestCase;
use Routepress\Types\HttpVerb;

final class HttpVerbTest extends TestCase
{
    public function testExposesEverySupportedVerb(): void
    {
        self::assertSame(
            ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
            array_map(static fn (HttpVerb $verb): string => $verb->value, HttpVerb::cases())
        );
    }
}
