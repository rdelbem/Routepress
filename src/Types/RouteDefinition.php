<?php

declare(strict_types=1);

namespace Routepress\Types;

/**
 * A read-only description of a route registered through Routepress.
 *
 * Used for introspection (e.g. the `wp routepress routes` command) without
 * exposing the route's callback or permission callables.
 */
final readonly class RouteDefinition
{
    /**
     * @param string $namespace The REST namespace, e.g. `myplugin/v1`.
     * @param list<string> $methods The HTTP verbs, e.g. `['GET', 'POST']`.
     * @param string $route The normalized route including named capture groups.
     * @param bool $requiresAuth Whether JWT authentication is required.
     * @param bool $hasMiddleware Whether the route carries group middleware.
     * @param array<string, array<string, mixed>> $args The request schema.
     */
    public function __construct(
        public string $namespace,
        public array $methods,
        public string $route,
        public bool $requiresAuth,
        public bool $hasMiddleware,
        public array $args,
    ) {
    }

    /**
     * The route with capture groups humanized: `(?P<id>\d+)` becomes `{id}`.
     */
    public function humanPath(): string
    {
        $path = preg_replace(
            '/\(\?P<([A-Za-z_][A-Za-z0-9_]*)>[^)]+\)/',
            '{$1}',
            $this->route
        );

        return $path ?? $this->route;
    }

    /**
     * The full REST path including the namespace, e.g. `/myplugin/v1/items/{id}`.
     */
    public function fullPath(): string
    {
        return '/' . trim($this->namespace, '/') . $this->humanPath();
    }

    /**
     * @return list<string>
     */
    public function argumentNames(): array
    {
        return array_keys($this->args);
    }
}
