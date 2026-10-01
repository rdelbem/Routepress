<?php

declare(strict_types=1);

namespace Routepress;

/**
 * Helpers for sending an API key with outgoing WordPress HTTP requests.
 *
 * The incoming counterpart lives in {@see Middleware::apiKey()}.
 */
final class ApiKey
{
    /**
     * Build the headers array carrying the API key.
     *
     * ```php
     * ApiKey::headers('s3cr3t');                          // ['X-Api-Key' => 's3cr3t']
     * ApiKey::headers('s3cr3t', 'Authorization', 'Bearer '); // ['Authorization' => 'Bearer s3cr3t']
     * ```
     *
     * @return array<string, string>
     */
    public static function headers(string $key, string $header = 'X-Api-Key', string $prefix = ''): array
    {
        return [$header => $prefix . $key];
    }

    /**
     * Merge the API key header into existing request arguments.
     *
     * ```php
     * $response = wp_remote_get($url, ApiKey::withHeaders(['timeout' => 5], $key));
     * ```
     *
     * @param array<string, mixed> $args
     * @return array<string, mixed>
     */
    public static function withHeaders(
        array $args,
        string $key,
        string $header = 'X-Api-Key',
        string $prefix = '',
    ): array {
        $existing = isset($args['headers']) && is_array($args['headers']) ? $args['headers'] : [];
        $args['headers'] = array_merge($existing, self::headers($key, $header, $prefix));

        return $args;
    }
}
