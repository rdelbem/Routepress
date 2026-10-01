<?php

declare(strict_types=1);

namespace Routepress\Cli;

use WP_CLI;

/**
 * Registers the WP-CLI command once, when running under WP-CLI.
 *
 * @internal
 */
final class CommandRegistrar
{
    private static bool $registered = false;

    /**
     * @param callable(string, class-string): void|null $addCommand
     */
    public static function register(?callable $addCommand = null): void
    {
        if (self::$registered) {
            return;
        }

        if ($addCommand === null) {
            if (!class_exists(WP_CLI::class)) {
                return;
            }

            $addCommand = [WP_CLI::class, 'add_command'];
        }

        $addCommand('routepress', RoutepressCommand::class);

        self::$registered = true;
    }

    /**
     * Reset the registration guard. Intended for tests.
     */
    public static function reset(): void
    {
        self::$registered = false;
    }
}
