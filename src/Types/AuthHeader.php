<?php 

namespace rdelbem\Types\Routepress;

use rdelbem\Routepress\Types\JWT;
use rdelbem\Routepress\Types\RefreshToken;

final class AuthHeader{
    public JWT $jwt;
    public RefreshToken $refreshToken;

    public function __construct(JWT $jwt, RefreshToken $refreshToken) {
        $this->jwt = $jwt;
        $this->refreshToken = $refreshToken;
    }
}