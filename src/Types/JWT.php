<?php

declare(strict_types=1);

namespace Routepress\Types;

use JsonSerializable;

final readonly class JWT implements JsonSerializable
{
    public function __construct(
        public int $iat,
        public string $iss,
        public int $exp,
        public string $uid,
    ) {
    }

    /**
     * @return array{iat: int, iss: string, exp: int, uid: string}
     */
    #[\Override]
    public function jsonSerialize(): array
    {
        return [
            'iat' => $this->iat,
            'iss' => $this->iss,
            'exp' => $this->exp,
            'uid' => $this->uid,
        ];
    }
}
