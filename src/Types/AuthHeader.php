<?php

declare(strict_types=1);

namespace Routepress\Types;

use JsonSerializable;

final readonly class AuthHeader implements JsonSerializable
{
    public function __construct(
        public JWT $jwt,
        public RefreshToken $refreshToken,
    ) {
    }

    /**
     * @return array{
     *     jwt: array{iat: int, iss: string, exp: int, uid: string},
     *     refresh_token: array{refresh_token: string, exp: int}
     * }
     */
    #[\Override]
    public function jsonSerialize(): array
    {
        return [
            'jwt' => $this->jwt->jsonSerialize(),
            'refresh_token' => $this->refreshToken->jsonSerialize(),
        ];
    }
}
