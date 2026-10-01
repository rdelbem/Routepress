<?php

declare(strict_types=1);

namespace Routepress;

use WP_Error;
use WP_REST_Request;
use WP_User;

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
     * Allow the request when a valid API key is present.
     *
     * The key is read from `$header` first, then from `$queryParam` when one is
     * configured. Comparison is constant-time against one or more secrets.
     *
     * ```php
     * $group->withoutAuth()->middleware(Middleware::apiKey('s3cr3t'));
     * $group->withoutAuth()->middleware(
     *     Middleware::apiKey('s3cr3t', header: 'Authorization', prefix: 'Bearer ')
     * );
     * ```
     *
     * @param string|array<int, string> $secrets One or more accepted keys.
     * @param string $header The request header carrying the key.
     * @param string|null $queryParam Optional query parameter used as a fallback.
     * @param string $prefix A scheme prefix (e.g. `Bearer `) stripped from the value.
     */
    public static function apiKey(
        string|array $secrets,
        string $header = 'X-Api-Key',
        ?string $queryParam = null,
        string $prefix = '',
    ): callable {
        $accepted = is_array($secrets) ? array_values($secrets) : [$secrets];

        return static function (WP_REST_Request $request) use (
            $accepted,
            $header,
            $queryParam,
            $prefix
        ): bool|WP_Error {
            $key = self::readApiKey($request, $header, $queryParam, $prefix);

            if ($key === null) {
                return self::missingApiKey();
            }

            foreach ($accepted as $secret) {
                if (hash_equals($secret, $key)) {
                    return true;
                }
            }

            return new WP_Error(
                'routepress_invalid_api_key',
                'The provided API key is invalid.',
                ['status' => 403]
            );
        };
    }

    /**
     * Allow the request when a custom validator accepts the API key.
     *
     * The validator receives the extracted key and may return `true`, `false`,
     * a `WP_Error`, or a `WP_User` (which becomes the current user so that
     * capability middleware works).
     *
     * @param callable(string): (bool|WP_Error|WP_User) $validator
     * @param string $header The request header carrying the key.
     * @param string|null $queryParam Optional query parameter used as a fallback.
     * @param string $prefix A scheme prefix (e.g. `Bearer `) stripped from the value.
     */
    public static function apiKeyUsing(
        callable $validator,
        string $header = 'X-Api-Key',
        ?string $queryParam = null,
        string $prefix = '',
    ): callable {
        return static function (WP_REST_Request $request) use (
            $validator,
            $header,
            $queryParam,
            $prefix
        ): bool|WP_Error {
            $key = self::readApiKey($request, $header, $queryParam, $prefix);

            if ($key === null) {
                return self::missingApiKey();
            }

            $result = $validator($key);

            if ($result instanceof WP_User) {
                wp_set_current_user($result->ID);

                return true;
            }

            return $result;
        };
    }

    private static function missingApiKey(): WP_Error
    {
        return new WP_Error(
            'routepress_missing_api_key',
            'An API key is required.',
            ['status' => 401]
        );
    }

    private static function readApiKey(
        WP_REST_Request $request,
        string $header,
        ?string $queryParam,
        string $prefix,
    ): ?string {
        $value = $request->get_header($header);

        if (($value === null || $value === '') && $queryParam !== null) {
            $parameter = $request->get_param($queryParam);
            $value = is_string($parameter) ? $parameter : null;
        }

        if (!is_string($value) || $value === '') {
            return null;
        }

        if ($prefix !== '' && str_starts_with($value, $prefix)) {
            $value = substr($value, strlen($prefix));
        }

        return $value === '' ? null : $value;
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
