<?php

declare(strict_types=1);

namespace Routepress;

use WP_Error;
use WP_REST_Request;

/**
 * Factories for common authorization checks, plus a composition helper.
 *
 * Every middleware is a WordPress permission callback: it receives the current
 * request and returns `true` to allow, `false` to deny, or a `WP_Error` to
 * short-circuit with a specific error.
 */
final class Middleware
{
    /**
     * Allow only requests from a logged-in user.
     */
    public static function loggedIn(): callable
    {
        return static fn (): bool => is_user_logged_in();
    }

    /**
     * Allow the request only when the current user has the given capability.
     */
    public static function capability(string $capability): callable
    {
        return static fn (): bool => current_user_can($capability);
    }

    /**
     * Allow the request when the current user has at least one capability.
     */
    public static function anyCapability(string ...$capabilities): callable
    {
        return static function () use ($capabilities): bool {
            foreach ($capabilities as $capability) {
                if (current_user_can($capability)) {
                    return true;
                }
            }

            return false;
        };
    }

    /**
     * Allow the request only when the current user has all capabilities.
     */
    public static function allCapabilities(string ...$capabilities): callable
    {
        return static function () use ($capabilities): bool {
            if ($capabilities === []) {
                return false;
            }

            foreach ($capabilities as $capability) {
                if (!current_user_can($capability)) {
                    return false;
                }
            }

            return true;
        };
    }

    /**
     * Compose middleware into a single permission callback.
     *
     * Checks run in order and stop at the first failure, so a `WP_Error` from an
     * earlier check is returned as-is instead of a generic denial.
     *
     * Matching WordPress's own `permission_callback` semantics, any truthy value
     * counts as a pass; `false` and `WP_Error` deny.
     *
     * @param list<callable> $middleware
     *
     * @return callable|null `null` when there is nothing to check.
     */
    public static function chain(array $middleware): ?callable
    {
        if ($middleware === []) {
            return null;
        }

        if (count($middleware) === 1) {
            return $middleware[0];
        }

        return static function (WP_REST_Request $request) use ($middleware) {
            foreach ($middleware as $check) {
                $result = $check($request);

                if ($result instanceof WP_Error) {
                    return $result;
                }

                if (!$result) {
                    return false;
                }
            }

            return true;
        };
    }
}
