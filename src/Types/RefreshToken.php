<?php

declare(strict_types=1);

namespace Routepress\Types;

use JsonSerializable;

final readonly class RefreshToken implements JsonSerializable
{
    public function __construct(
        public string $token,
        public int $exp,
    ) {
    }

    /**
     * @return array{refresh_token: string, exp: int}
     */
    #[\Override]
    public function jsonSerialize(): array
    {
        return [
            'refresh_token' => $this->token,
            'exp' => $this->exp,
        ];
    }
}
