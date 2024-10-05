<?php

namespace Rdelbem\Routepress;
use Rdelbem\Routepress\Types\AuthHeader;

use \WP_REST_Request;
use \WP_User;

interface AuthInterface {
    public function validateRefreshToken(string $refreshToken): bool;
    public function createSession(WP_User $user): void;
    public function generateJwtAtLogin(): void;
    public function generateAuthHeader(): AuthHeader;
    public function validateJwt(WP_REST_Request $request): bool;
    public function removeJwt(): void;
}