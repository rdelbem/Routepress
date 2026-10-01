Routepress
==========

[![Build Status](https://github.com/rdelbem/routepress/actions/workflows/ci.yml/badge.svg)](https://github.com/rdelbem/routepress/actions)

A PHP library to simplify the creation and management of WordPress REST API routes.

Table of Contents
-----------------

-   [Introduction](#introduction)
-   [Features](#features)
-   [Installation](#installation)
-   [Requirements](#requirements)
-   [Usage](#usage)
    -   [Authentication](#authentication)
    -   [Initializing Routepress](#initializing-routepress)
    -   [Creating Routes](#creating-routes)
-   [Examples](#examples)
    -   [Simple GET Route](#simple-get-route)
    -   [POST Route with Authentication](#post-route-with-authentication)
    -   [Multiple HTTP Verbs](#multiple-http-verbs)
    -   [Route Parameters](#route-parameters)
    -   [Custom Permission Callback](#custom-permission-callback)
    -   [Route Groups and Middleware](#route-groups-and-middleware)
-   [WP-CLI](#wp-cli)
-   [Testing](#testing)
-   [Contributing](#contributing)
-   [License](#license)

Introduction
------------

Routepress is a PHP library designed to streamline the process of registering and managing REST API routes in WordPress plugins. It provides a fluent interface for defining routes, handling callbacks, and integrating custom authentication mechanisms.

Features
--------

-   **Simplified Route Registration**: Easily create REST API routes with minimal code.
-   **HTTP Verb Support**: Handle various HTTP methods like GET, POST, PUT, DELETE, and more.
-   **Custom Authentication**: Integrate your own authentication logic through the `AuthInterface`.
-   **Namespacing**: Organize your routes under a specific namespace.
-   **Callback Handling**: Define callbacks for route responses effortlessly.

Installation
------------

Install Routepress via Composer:

```bash
composer require rdelbem/routepress
```

Requirements
------------

-   PHP 8.2 or higher
-   WordPress 5.6 or higher
-   Composer

Usage
-----

### Authentication

Authentication is optional. The router only depends on `JwtAuthenticator`, a
single-method contract, so implement that when all you need is token validation:

```php
use Routepress\JwtAuthenticator;
use WP_REST_Request;
use WP_User;
use WP_Error;

class MyJwt implements JwtAuthenticator {
    public function validateJwt(WP_REST_Request $request): WP_User|WP_Error|bool {
        // Return a WP_User to authenticate (and set the current user),
        // true for an authenticated request with no user context,
        // a WP_Error for a specific error response, or false to deny.
        return $this->resolveUser($request) ?? false;
    }
}
```

Returning a `WP_User` makes the router call `wp_set_current_user()`, so
capability middleware such as `current_user_can()` evaluates the authenticated
user rather than any cookie session.

If you also want the login/session helpers, implement `AuthInterface`, which
extends `JwtAuthenticator`:

```php
use Routepress\AuthInterface;
use Routepress\Types\AuthHeader;
use WP_User;
use WP_REST_Request;
use WP_Error;

class MyAuth implements AuthInterface {
    public function validateJwt(WP_REST_Request $request): WP_User|WP_Error|bool {
        // Your JWT validation logic. Return the authenticated WP_User,
        // true, a WP_Error, or false.
    }

    public function validateRefreshToken(string $refreshToken): bool {
        // Your validation logic
    }

    public function createSession(WP_User $user): void {
        // Create a user session
    }

    public function generateJwtAtLogin(): void {
        // Generate JWT upon user login
    }

    public function generateAuthHeader(): AuthHeader {
        // Generate and return an AuthHeader object
    }

    public function removeJwt(): void {
        // Remove JWT token
    }
}
```

### Initializing Routepress

Instantiate `Bootload` with an optional authenticator and your API namespace:

```php
// With JWT authentication.
$route = new Routepress\Bootload(new MyJwt(), 'myplugin/v1');

// Public API, or an API where every route defines its own permission callback.
$route = new Routepress\Bootload(null, 'myplugin/v1');
```

Registering a route that requires JWT authentication without an authenticator
throws a `LogicException` at boot.

### Creating Routes

Use the `create` method to define a new route:

```php
$route->create(
    string|array|HttpVerb $httpVerb,
    string $route,
    callable $callback,
    bool $requiresAuth = true,
    ?callable $permissionCallback = null,
    array $args = []
);
```
-   **$httpVerb**: The HTTP method(s) (e.g., `'GET'`, `'POST'`, an `HttpVerb` enum case, or an array of them).
-   **$route**: The endpoint route (e.g., `/my-route`). Use `:name` for dynamic segments (e.g., `/items/:id`) and an optional regex (e.g., `/items/:id(\d+)`).
-   **$callback**: The function to execute when the route is accessed.
-   **$requiresAuth**: Whether JWT authentication is required (default is `true`). Pass `false` for a route that relies solely on its own permission callback, or for a public route.
-   **$permissionCallback**: A WordPress permission callback that runs after JWT authentication (when required), e.g. `static fn (): bool => current_user_can('manage_options')`.
-   **$args**: Per-parameter schema/validation passed straight to `register_rest_route()`, e.g. `['id' => ['type' => 'integer', 'required' => true]]`.

Invalid HTTP verbs throw an `InvalidArgumentException`. A route that requires JWT
authentication while no authenticator was configured throws a `LogicException`.

Examples
--------

### Simple GET Route

```php
$route->create('GET', '/hello', function () {
    return ['message' => 'Hello, World!'];
}, false);
```

### POST Route with Authentication

```php
$route->create('POST', '/submit', function ($request) {
    $data = $request->get_params();
    // Process the data
    return ['status' => 'success'];
}, true);
```

### Multiple HTTP Verbs

```php
$route->create(['GET', 'POST'], '/data', function (\WP_REST_Request $request) {
    if ($request->get_method() === 'GET') {
        return ['data' => 'Some data'];
    }

    // Handle the POST (create) request.
    return ['created' => true];
}, false);
```

### Route Parameters

```php
$route->create('GET', '/items/:id', function ($request) {
    return ['id' => $request['id']];
}, false);
```

### Custom Permission Callback

Routes do not have to use JWT. Pass a WordPress permission callback to rely on
capabilities such as `current_user_can` instead:

```php
use WP_REST_Request;

$route->create(
    'GET',
    '/admin/stats',
    function (WP_REST_Request $request) {
        return ['stats' => []];
    },
    false,
    static fn (): bool => current_user_can('manage_options')
);
```

### Route Groups and Middleware

Group routes under a shared prefix and apply middleware (WordPress permission
callbacks) to all of them:

```php
use Routepress\Middleware;
use Routepress\RouteGroup;

$route->group('/admin', function (RouteGroup $group): void {
    $group->middleware(
        Middleware::loggedIn(),
        Middleware::capability('manage_options'),
    );

    $group->create('GET', '/stats', $statsHandler);
    $group->create('POST', '/users', $createUserHandler);
});
```

Groups can also be chained and nested:

```php
$admin = $route->group('/admin')->middleware(Middleware::capability('manage_options'));
$admin->create('GET', '/stats', $statsHandler);

// Nested groups inherit the prefix and middleware.
$admin->group('/v2')->create('GET', '/stats', $statsHandler); // /admin/v2/stats
```

Groups inherit an authentication default (JWT required). Disable it for a
capability-only or public group:

```php
$route->group('/admin', requiresAuth: false)->middleware(
    Middleware::capability('manage_options')
);

// or, fluently:
$route->group('/admin')->withoutAuth()->middleware(
    Middleware::capability('manage_options')
);
```

Individual routes can override the group default through the `$requiresAuth`
argument of `create()`.

Built-in middleware:

-   `Middleware::loggedIn()` — requires a logged-in user.
-   `Middleware::capability('edit_posts')` — requires one capability.
-   `Middleware::anyCapability('edit_posts', 'publish_posts')` — requires any one of them.
-   `Middleware::allCapabilities('edit_posts', 'publish_posts')` — requires all of them.

Middleware are just permission callbacks, so any callable works:

```php
$route->group('/reports')->middleware(
    static function (WP_REST_Request $request): bool {
        return $request->get_param('token') === 'secret';
    }
);
```

Checks run in this order: JWT authentication (when `$requiresAuth` is `true`),
then group middleware, then the route's own `$permissionCallback`. All must pass;
the first failure wins, and a `WP_Error` short-circuits as-is. Middleware must be
added **before** the routes it should apply to.

WP-CLI
------

When WP-CLI is available, `Bootload` registers a `wp routepress` command
automatically. List every Routepress route, its verbs, authentication and
arguments:

```bash
wp routepress routes
wp routepress routes --format=json
wp routepress routes --namespace=myplugin/v1
```

Output columns: `verb`, `route`, `auth` (`jwt`/`public`), `middleware`
(`yes`/`no`) and `args`. Routes with several verbs produce one row per verb.

Testing
-------

Routepress ships a [PHPUnit](https://phpunit.de/) suite that uses
[Brain Monkey](https://brain-wp.github.io/BrainMonkey/) to stub the WordPress
functions, so no WordPress installation, database or Docker is required.

### Running Tests

```bash
composer install
composer test
```

### Test Coverage

Coverage requires [PCOV](https://github.com/krakjoe/pcov) or Xdebug, and only
measures `src/`:

```bash
composer coverage        # human-readable report
composer coverage:check  # fail when below the minimums (100% lines/methods/classes)
```

### Mutation Testing

[Mutation testing](https://infection.github.io/) checks that the tests actually
assert behaviour, not just execute lines:

```bash
composer infection
```

It also requires PCOV or Xdebug. Note that `Bootload`, `RouteGroup` and
`Middleware` are excluded in `infection.json5`: their tests use Brain Monkey
(`antecedent/patchwork`), whose stream wrapper conflicts with Infection's
include-interceptor, so every mutant in those classes escapes. They will be
re-included once the tests can run without Patchwork. See
[infection/infection#1827](https://github.com/infection/infection/issues/1827).

### Static Analysis with PHPStan

```bash
composer phpstan
```

### Linting and Formatting

Coding style is enforced with
[PHP_CodeSniffer](https://github.com/PHPCSStandards/PHP_CodeSniffer) against the
PSR-12 standard:

```bash
composer lint      # report violations
composer format    # auto-fix what can be fixed
```

### Git Hooks

A pre-commit hook runs `composer lint` and `composer test` and blocks the commit
when either fails. It is installed automatically by `composer install` /
`composer update`; run `composer hooks` to install or refresh it manually.

Contributing
------------

Contributions are welcome! Please follow these steps:

1.  **Fork the Repository**: Click the "Fork" button at the top right of the repository page.

2.  **Clone Your Fork**:

    ```bash
    git clone https://github.com/yourusername/routepress.git
    ```

3.  **Create a New Branch**:

    ```bash
    git checkout -b feature/your-feature-name
    ```

4.  **Make Your Changes**: Implement your feature or bug fix.

5.  **Commit Your Changes**:

    ```bash
    git commit -am 'Add new feature'
    ```

6.  **Push to Your Branch**:

    ```bash
    git push origin feature/your-feature-name
    ```

7.  **Submit a Pull Request**: Go to the original repository and click "New Pull Request".

### Coding Standards

-   Follow PSR-12 (`composer lint`), and run `composer format` before committing.
-   Ensure static analysis (`composer phpstan`) and all tests pass before submitting.
-   Write unit tests for new features.

License
-------

Routepress is open-source software licensed under the [GNU General Public License v3.0 or later](https://spdx.org/licenses/GPL-3.0-or-later.html).

* * * * *

**Disclaimer**: This project is not affiliated with or endorsed by WordPress. "WordPress" is a registered trademark of the WordPress Foundation.
