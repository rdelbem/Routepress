<?php

declare(strict_types=1);

namespace Routepress\Tests\Types;

use PHPUnit\Framework\TestCase;
use Routepress\Types\JWT;

final class JWTTest extends TestCase
{
    public function testSerializesItsParts(): void
    {
        $jwt = new JWT(1_700_000_000, 'routepress', 1_700_003_600, '7');

        self::assertSame([
            'iat' => 1_700_000_000,
            'iss' => 'routepress',
            'exp' => 1_700_003_600,
            'uid' => '7',
        ], $jwt->jsonSerialize());
    }
}
