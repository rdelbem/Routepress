<?php

declare(strict_types=1);

namespace Routepress\Tests\Types;

use PHPUnit\Framework\TestCase;
use Routepress\Types\AuthHeader;
use Routepress\Types\JWT;
use Routepress\Types\RefreshToken;

final class AuthHeaderTest extends TestCase
{
    public function testSerializesItsParts(): void
    {
        $header = new AuthHeader(
            new JWT(1_700_000_000, 'routepress', 1_700_003_600, '7'),
            new RefreshToken('refresh-token', 1_700_003_600)
        );

        self::assertSame([
            'jwt' => [
                'iat' => 1_700_000_000,
                'iss' => 'routepress',
                'exp' => 1_700_003_600,
                'uid' => '7',
            ],
            'refresh_token' => [
                'refresh_token' => 'refresh-token',
                'exp' => 1_700_003_600,
            ],
        ], $header->jsonSerialize());
    }
}
