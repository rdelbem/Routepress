<?php 

namespace Rdelbem\Types\Routepress;

use Rdelbem\Routepress\Types\JWT;
use Rdelbem\Routepress\Types\RefreshToken;

final class AuthHeader{
    public JWT $jwt;
    public RefreshToken $refreshToken;

    public function __construct(JWT $jwt, RefreshToken $refreshToken) {
        $this->jwt = $jwt;
        $this->refreshToken = $refreshToken;
    }
}