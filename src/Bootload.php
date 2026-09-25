<?php

declare(strict_types=1);

namespace Routepress;

use InvalidArgumentException;
use LogicException;
use Routepress\Types\HttpVerb;
use WP_Error;
use WP_REST_Request;
use WP_User;

/**
 * Registers WordPress REST routes with optional JWT authentication, shared
 * middleware and per-route validation schemas.
 */
final class Bootload
{
    /** @var list<array{methods: list<string>, route: non-falsy-string, callback: callable, permission: callable, args: array<string, array<string, mixed>>}> */
    private array $routes = [];

    private bool $registrationHooked = false;

    /**
     * @param JwtAuthenticator|null $authenticator Required only for routes that
     *        rely on JWT authentication. Pass `null` for public APIs or when
     *        every route supplies its own permission callback.
     * @param non-falsy-string $apiNamespace The REST namespace, e.g. `myplugin/v1`.
     */
    public function __construct(
        private readonly ?JwtAuthenticator $authenticator,
        private readonly string $apiNamespace,
    ) {
    }

    /**
     * Start a group of routes sharing a path prefix, middleware and an
     * authentication default.
     *
     * ```php
     * $bootload->group('/admin', function (RouteGroup $group): void {
     *     $group->requiresAuth(false);
     *     $group->middleware(Middleware::capability('manage_options'));
     *     $group->create('GET', '/stats', $callback);
     * });
     * ```
     *
     * @param bool $requiresAuth Default for every route in the group. Individual
     *        routes can override it by passing `$requiresAuth` to `create()`.
     */
    public function group(string $prefix, ?callable $callback = null, bool $requiresAuth = true): RouteGroup
    {
        $group = new RouteGroup($this->registerRoute(...), $prefix, $requiresAuth);

        if ($callback !== null) {
            $callback($group);
        }

        return $group;
    }

    /**
     * Run the authentication strategy against the incoming request.
     *
     * When the authenticator returns a `WP_User`, it becomes the current user so
     * that capability middleware can rely on `current_user_can()`.
     *
     * A `WP_Error` from the authenticator is returned as-is; `false` lets
     * WordPress answer with the generic 401/403 response.
     *
     * @throws LogicException When no authenticator was configured.
     */
    public function securityCheck(WP_REST_Request $request): bool|WP_Error
    {
        if ($this->authenticator === null) {
            throw new LogicException(
                'Authentication was requested but no JwtAuthenticator was provided.'
            );
        }

        $result = $this->authenticator->validateJwt($request);

        if ($result instanceof WP_User) {
            wp_set_current_user($result->ID);

            return true;
        }

        return $result;
    }

    /**
     * Register a new REST route.
     *
     * ```php
     * $bootload->create('POST', '/items', $callback);            // JWT required
     * $bootload->create(['GET', 'POST'], '/items', $callback);   // multiple verbs
     * $bootload->create('GET', '/public', $callback, false);     // open route
     * $bootload->create(
     *     'GET',
     *     '/admin',
     *     $callback,
     *     false,
     *     static fn (): bool => current_user_can('manage_options'),
     * );                                                         // capability only
     * ```
     *
     * Route parameters can be declared with `:name`, optionally followed by a
     * custom regular expression: `/items/:id(\d+)`.
     *
     * When `$permissionCallback` is given it runs in addition to JWT
     * authentication (when required). WordPress invokes it with the current
     * `WP_REST_Request`.
     *
     * @param HttpVerb|string|array<HttpVerb|string> $httpVerb
     * @param bool $requiresAuth Require JWT authentication.
     * @param callable|null $permissionCallback Custom WordPress permission
     *        callback, e.g. `static fn (): bool => current_user_can('edit_posts')`.
     * @param array<string, array<string, mixed>> $args Per-parameter schema/validation passed to
     *        `register_rest_route()`.
     *
     * @throws InvalidArgumentException When no valid HTTP verb is provided.
     * @throws LogicException When JWT authentication is required but no
     *         authenticator was configured.
     */
    public function create(
        HttpVerb|string|array $httpVerb,
        string $route,
        callable $callback,
        bool $requiresAuth = true,
        ?callable $permissionCallback = null,
        array $args = [],
    ): void {
        $this->registerRoute($httpVerb, $route, $callback, $requiresAuth, $permissionCallback, [], $args);
    }

    /**
     * Register a route with an extra set of middleware.
     *
     * Private on purpose: `RouteGroup` receives a bound first-class callable of
     * this method, so it never needs to be part of the public API.
     *
     * @param HttpVerb|string|array<HttpVerb|string> $httpVerb
     * @param list<callable> $middleware
     * @param array<string, array<string, mixed>> $args
     *
     * @throws InvalidArgumentException When no valid HTTP verb is provided.
     * @throws LogicException When JWT authentication is required but no
     *         authenticator was configured.
     */
    private function registerRoute(
        HttpVerb|string|array $httpVerb,
        string $route,
        callable $callback,
        bool $requiresAuth,
        ?callable $permissionCallback,
        array $middleware,
        array $args = [],
    ): void {
        $methods = $this->normalizeVerbs($httpVerb);
        $route = $this->normalizeRoute($route);
        $permission = $this->resolvePermission($middleware, $requiresAuth, $permissionCallback);

        $this->routes[] = [
            'methods' => $methods,
            'route' => $route,
            'callback' => $callback,
            'permission' => $permission,
            'args' => $args,
        ];

        $this->hookRegistration();
    }

    /**
     * Register every collected route on a single `rest_api_init` callback.
     */
    private function hookRegistration(): void
    {
        if ($this->registrationHooked) {
            return;
        }

        $this->registrationHooked = true;

        add_action('rest_api_init', function (): void {
            foreach ($this->routes as $route) {
                register_rest_route(
                    $this->apiNamespace,
                    $route['route'],
                    [
                        'methods' => $route['methods'],
                        'callback' => $route['callback'],
                        'permission_callback' => $route['permission'],
                        'args' => $route['args'],
                    ]
                );
            }
        });
    }

    /**
     * Resolve the WordPress permission callback for a route.
     *
     * Checks run in this order: JWT authentication, group middleware, then the
     * route's own permission callback. All must pass; the first failure wins.
     *
     * @param list<callable> $middleware
     *
     * @throws LogicException When JWT authentication is required but no
     *         authenticator was configured.
     */
    private function resolvePermission(
        array $middleware,
        bool $requiresAuth,
        ?callable $permissionCallback,
    ): callable {
        $checks = [];

        if ($requiresAuth) {
            if ($this->authenticator === null) {
                throw new LogicException(
                    'Cannot register an authenticated route without a JwtAuthenticator. '
                    . 'Provide one to the constructor, register the route as public, '
                    . 'or pass a custom permission callback.'
                );
            }

            $checks[] = [$this, 'securityCheck'];
        }

        foreach ($middleware as $check) {
            $checks[] = $check;
        }

        if ($permissionCallback !== null) {
            $checks[] = $permissionCallback;
        }

        return Middleware::chain($checks) ?? static fn (): bool => true;
    }

    /**
     * Normalize one or more HTTP verbs into the values WordPress expects.
     *
     * @param HttpVerb|string|array<HttpVerb|string> $httpVerb
     * @return list<string>
     *
     * @throws InvalidArgumentException When the verb list is empty or invalid.
     */
    private function normalizeVerbs(HttpVerb|string|array $httpVerb): array
    {
        /** @var list<mixed> $verbs */
        $verbs = is_array($httpVerb) ? $httpVerb : [$httpVerb];

        if ($verbs === []) {
            throw new InvalidArgumentException('At least one HTTP verb is required.');
        }

        $methods = [];

        foreach ($verbs as $verb) {
            if (!$verb instanceof HttpVerb && !is_string($verb)) {
                throw new InvalidArgumentException('HTTP verbs must be strings or HttpVerb cases.');
            }

            $enum = $verb instanceof HttpVerb
                ? $verb
                : HttpVerb::tryFrom(strtoupper($verb));

            if ($enum === null) {
                throw new InvalidArgumentException(
                    sprintf('Unsupported HTTP verb "%s".', $verb)
                );
            }

            $methods[] = $enum->value;
        }

        return array_values(array_unique($methods));
    }

    /**
     * Normalize the route and convert `:name` placeholders into WordPress named
     * capture groups.
     *
     * @return non-falsy-string
     */
    private function normalizeRoute(string $route): string
    {
        $normalized = preg_replace_callback(
            '/:([A-Za-z_][A-Za-z0-9_]*)(?:\(([^)]+)\))?/',
            static fn (array $matches): string => sprintf(
                '(?P<%s>%s)',
                $matches[1],
                $matches[2] ?? '[\w-]+'
            ),
            '/' . ltrim($route, '/')
        );

        return '/' . ltrim($normalized ?? $route, '/');
    }
}
