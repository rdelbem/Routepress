# Routepress Refactor Backlog

> **Status: all items resolved.** The notes below are kept as a record. Key
> semantic changes: `JwtAuthenticator::validateJwt()` now returns
> `WP_User|WP_Error|bool` and `securityCheck()` sets the current user;
> `create()` returns `void`; groups carry an auth default (`requiresAuth()` /
> `withoutAuth()`); routes accept an `args` schema; registration happens in a
> single `rest_api_init` callback.

Code review findings for the library. Items are grouped by severity and each
includes the relevant location and a suggested direction.

Status reference: 41 PHPUnit tests passing, PHPStan level 8 clean.

---

## Bugs

- [x] **Root prefix is normalized incorrectly** — `src/RouteGroup.php`
  - `group('/')` produced the prefix `'/'`, so routes became `//stats` (and
    nested groups compounded it).
  - Fixed: `$trimmed = trim($prefix, '/'); return $trimmed === '' ? '' : '/' . $trimmed;`

- [x] **`create()` docblock example contradicted real semantics** — `src/Bootload.php`
  - The inline example passed only `permissionCallback:` with no
    `requiresAuth: false`, but a custom permission runs *in addition to* JWT.
  - Fixed: example now passes `false` and is covered by a test.

- [x] **Route normalization was inconsistent** — `Bootload::normalizeRoute()` vs `RouteGroup::normalizeRoute()`
  - `Bootload` did not add a leading slash; `RouteGroup` did.
  - Fixed: both normalize to a leading slash.

---

## Security / Correctness

- [x] **JWT auth did not establish the WordPress current user** — `src/JwtAuthenticator.php`, `src/Bootload.php`, `src/Middleware.php`
  - `validateJwt()` returned `bool`, so `Middleware::loggedIn()` /
    `capability()` read WP's cookie-authenticated current user — a valid JWT
    could fail capability checks, or be evaluated against the wrong (cookie)
    user.
  - Fixed: `validateJwt()` now returns `WP_User|WP_Error|bool`;
    `securityCheck()` calls `wp_set_current_user()` when a `WP_User` is returned.

- [x] **Authentication could not return a specific error** — `src/JwtAuthenticator.php`
  - `bool` collapsed "expired token", "bad signature" and "missing token" into a
    generic 401/403.
  - Fixed: authenticators may return a `WP_Error`, which is propagated as-is.

---

## Design / API

- [x] **Group + capability middleware forced `requiresAuth: false` per route** — `src/Bootload.php`, `src/RouteGroup.php`
  - Capability-only groups without an authenticator threw at boot.
  - Fixed: group-level default via `group(..., requiresAuth: false)` or
    `$group->withoutAuth()` / `$group->requiresAuth(bool)`; routes can still
    override in `create()`.

- [x] **`Bootload::addRoute()` was public `@internal`** — `src/Bootload.php`
  - Implementation detail leaking into the public surface.
  - Fixed: `registerRoute()` is private; `group()` passes a bound first-class
    callable to `RouteGroup`.

- [x] **No `args`/schema support** — `src/Bootload.php`, `src/RouteGroup.php`
  - No way to declare per-route request validation/sanitization.
  - Fixed: trailing `array $args = []` on `create()`; forwarded to
    `register_rest_route()`.

- [x] **`create()` returned a `bool` that was always `true`** — `src/Bootload.php`
  - `add_action` always returns `true`, so the value could not signal failure.
  - Fixed: `create()` returns `void`.

- [x] **Per-route `add_action('rest_api_init', ...)`** — `src/Bootload.php`
  - N routes created N callbacks and depended on WP internals.
  - Fixed: routes are collected and registered in a single `rest_api_init`
    callback.

---

## Minor / Nits

- [x] `Middleware::chain()` truthy-pass behavior — documented for WP parity.
- [x] `RefreshToken::$refresh_token` renamed to `$token` (JSON key unchanged).
- [x] Redundant `toArray()` removed from the three DTOs.
- [x] `HttpVerb` gained `HEAD`; `OPTIONS` documented.
- [x] Test gaps closed: `allCapabilities()` success, `WP_Error` short-circuit
  through the router, root prefix, args forwarding, current-user setting, HEAD.
- [x] `composer.json` keywords added.
- [x] Static analysis migrated from Psalm to PHPStan (`phpstan/phpstan` +
  `szepeviktor/phpstan-wordpress`), level 8, zero errors; Psalm-specific
  `@psalm-api` annotations and suppressions removed.
- [x] WordPress minimum verified: 5.6 already merges same-route registrations, so
  `README`'s 5.6+ claim is accurate.

---

## Remaining

None. PHPStan level 8 reports zero errors.
