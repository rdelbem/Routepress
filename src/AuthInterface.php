<?php

declare(strict_types=1);

namespace Routepress;

use Routepress\Types\AuthHeader;
use WP_User;

/**
 * A full authentication lifecycle for consumers that issue JWTs themselves.
 *
 * The router only depends on {@see JwtAuthenticator}; this interface bundles the
 * additional login/session helpers you may want in your application.
 */
interface AuthInterface extends JwtAuthenticator
{
    public function validateRefreshToken(string $refreshToken): bool;

    public function createSession(WP_User $user): void;

    public function generateJwtAtLogin(): void;

    public function generateAuthHeader(): AuthHeader;

    public function removeJwt(): void;
}
