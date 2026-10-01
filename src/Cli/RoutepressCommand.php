<?php

declare(strict_types=1);

namespace Routepress\Cli;

use WP_CLI;
use WP_CLI\Formatter;

/**
 * The `wp routepress` command.
 */
final class RoutepressCommand
{
    /**
     * List every route registered through Routepress and its HTTP verbs.
     *
     * ## OPTIONS
     *
     * [--namespace=<namespace>]
     * : Only list routes registered in this REST namespace.
     *
     * [--format=<format>]
     * : Render output in a particular format.
     * ---
     * default: table
     * options:
     *   - table
     *   - json
     *   - csv
     *   - yaml
     * ---
     *
     * [--fields=<fields>]
     * : Limit the output to specific fields.
     *
     * @param array<int, string> $args
     * @param array<string, string> $assocArgs
     */
    public function routes(array $args, array $assocArgs): void
    {
        $namespace = isset($assocArgs['namespace']) ? (string) $assocArgs['namespace'] : null;
        $rows = RouteReporter::rows(RouteRegistry::all(), $namespace);

        if ($rows === []) {
            WP_CLI::warning('No routes registered.');

            return;
        }

        $formatter = new Formatter($assocArgs, ['verb', 'route', 'auth', 'middleware', 'args']);
        $formatter->display_items($rows);
    }
}
