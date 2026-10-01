<?php

declare(strict_types=1);

namespace Routepress\Tests\Types;

use PHPUnit\Framework\TestCase;
use Routepress\Types\RefreshToken;

final class RefreshTokenTest extends TestCase
{
    public function testSerializesItsParts(): void
    {
        $token = new RefreshToken('refresh-token', 1_700_003_600);

        self::assertSame([
            'refresh_token' => 'refresh-token',
            'exp' => 1_700_003_600,
        ], $token->jsonSerialize());
    }
}
