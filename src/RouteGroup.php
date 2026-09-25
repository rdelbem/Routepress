<?php

declare(strict_types=1);

namespace Routepress;

use Closure;
use InvalidArgumentException;
use LogicException;
use Routepress\Types\HttpVerb;

/**
 * A fluent builder that applies a path prefix, middleware and an authentication
 * default to routes.
 *
 * Middleware must be added before the routes it should apply to; it is captured
 * when each route is registered.
 *
 * @internal Created by {@see Bootload::group()}; not intended to be constructed directly.
 */
final class RouteGroup
{
    private readonly string $prefix;

    /** @var list<callable> */
    private array $middleware = [];

    /**
     * @param RouteRegistrar $registrar
     */
    public function __construct(
        private readonly Closure $registrar,
        string $prefix = '',
        private bool $defaultRequiresAuth = true,
    ) {
        $this->prefix = self::normalizePrefix($prefix);
    }

    /**
     * Add one or more permission callbacks applied to every route in the group.
     */
    public function middleware(callable ...$middleware): self
    {
        foreach ($middleware as $check) {
            $this->middleware[] = $check;
        }

        return $this;
    }

    /**
     * Set the JWT authentication default for the group. Individual routes can
     * still override it in `create()`.
     */
    public function requiresAuth(bool $requiresAuth = true): self
    {
        $this->defaultRequiresAuth = $requiresAuth;

        return $this;
    }

    /**
     * Shorthand to disable JWT authentication for the whole group.
     */
    public function withoutAuth(): self
    {
        return $this->requiresAuth(false);
    }

    /**
     * Create a nested group, inheriting the current prefix, middleware and
     * authentication default.
     */
    public function group(string $prefix, ?callable $callback = null): self
    {
        $nested = new self(
            $this->registrar,
            $this->prefix . self::normalizePrefix($prefix),
            $this->defaultRequiresAuth,
        );
        $nested->middleware = $this->middleware;

        if ($callback !== null) {
            $callback($nested);
        }

        return $nested;
    }

    /**
     * Register a route inside the group.
     *
     * @param HttpVerb|string|array<HttpVerb|string> $httpVerb
     * @param bool|null $requiresAuth Override the group default. `null` inherits it.
     * @param array<string, array<string, mixed>> $args Per-parameter schema/validation.
     *
     * @throws InvalidArgumentException When no valid HTTP verb is provided.
     * @throws LogicException When JWT authentication is required but no
     *         authenticator was configured.
     */
    public function create(
        HttpVerb|string|array $httpVerb,
        string $route,
        callable $callback,
        ?bool $requiresAuth = null,
        ?callable $permissionCallback = null,
        array $args = [],
    ): void {
        ($this->registrar)(
            $httpVerb,
            $this->prefix . self::normalizeRoute($route),
            $callback,
            $requiresAuth ?? $this->defaultRequiresAuth,
            $permissionCallback,
            $this->middleware,
            $args,
        );
    }

    private static function normalizePrefix(string $prefix): string
    {
        $trimmed = trim($prefix, '/');

        return $trimmed === '' ? '' : '/' . $trimmed;
    }

    private static function normalizeRoute(string $route): string
    {
        return '/' . ltrim($route, '/');
    }
}
