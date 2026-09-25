<?php

declare(strict_types=1);

namespace Routepress\Types;

/**
 * The HTTP verbs supported by the router.
 *
 * The enum backing values are the tokens WordPress expects for the `methods`
 * argument of `register_rest_route()`. `HEAD` is accepted and handled by
 * WordPress alongside `GET`; `OPTIONS` is allowed but may interact with
 * WordPress's own CORS/preflight handling.
 */
enum HttpVerb: string
{
    case GET = 'GET';
    case HEAD = 'HEAD';
    case POST = 'POST';
    case PUT = 'PUT';
    case PATCH = 'PATCH';
    case DELETE = 'DELETE';
    case OPTIONS = 'OPTIONS';
}
