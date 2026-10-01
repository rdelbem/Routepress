<?php

declare(strict_types=1);

namespace Routepress\Cli;

use Routepress\Types\RouteDefinition;

/**
 * Turns route definitions into flat rows for the WP-CLI table.
 *
 * @internal
 */
final class RouteReporter
{
    /**
     * Build one row per HTTP verb, sorted by route then verb.
     *
     * @param list<RouteDefinition> $definitions
     * @return list<array{verb: string, route: string, auth: string, middleware: string, args: string}>
     */
    public static function rows(array $definitions, ?string $namespace = null): array
    {
        $rows = [];

        foreach ($definitions as $definition) {
            if ($namespace !== null && $definition->namespace !== $namespace) {
                continue;
            }

            foreach ($definition->methods as $method) {
                $rows[] = [
                    'verb' => $method,
                    'route' => $definition->fullPath(),
                    'auth' => $definition->requiresAuth ? 'jwt' : 'public',
                    'middleware' => $definition->hasMiddleware ? 'yes' : 'no',
                    'args' => implode(', ', $definition->argumentNames()),
                ];
            }
        }

        usort(
            $rows,
            static fn (array $a, array $b): int => [$a['route'], $a['verb']] <=> [$b['route'], $b['verb']]
        );

        return $rows;
    }
}
