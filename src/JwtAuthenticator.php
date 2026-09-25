<?php

declare(strict_types=1);

namespace Routepress;

use WP_Error;
use WP_REST_Request;
use WP_User;

/**
 * The only authentication contract the router needs.
 *
 * Implement this when your routes only require JWT validation. The richer
 * {@see AuthInterface} extends it with the rest of a typical auth lifecycle.
 *
 * `validateJwt()` should return:
 * - a `WP_User` when the request is authenticated; the router makes it the
 *   current user so capability checks (`current_user_can()`) work;
 * - `true` when the request is authenticated but has no WordPress user context;
 * - a `WP_Error` to fail with a specific REST error response;
 * - `false` to fail with the generic 401/403 response.
 */
interface JwtAuthenticator
{
    public function validateJwt(WP_REST_Request $request): WP_User|WP_Error|bool;
}
