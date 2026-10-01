<?php

declare(strict_types=1);

namespace Routepress\Cli;

use Routepress\Types\RouteDefinition;

/**
 * Collects the route definitions of every {@see \Routepress\Bootload} instance.
 *
 * Each bootloader registers a provider closure so the routes can be read at
 * command-execution time, after all providers have been registered.
 *
 * @internal
 */
final class RouteRegistry
{
    /** @var list<callable(): list<RouteDefinition>> */
    private static array $providers = [];

    /**
     * @param callable(): list<RouteDefinition> $provider
     */
    public static function register(callable $provider): void
    {
        self::$providers[] = $provider;
    }

    /**
     * @return list<RouteDefinition>
     */
    public static function all(): array
    {
        $definitions = [];

        foreach (self::$providers as $provider) {
            foreach ($provider() as $definition) {
                $definitions[] = $definition;
            }
        }

        return $definitions;
    }

    /**
     * Clear all providers. Intended for tests.
     */
    public static function reset(): void
    {
        self::$providers = [];
    }
}
