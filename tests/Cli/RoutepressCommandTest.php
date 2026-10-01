<?php

declare(strict_types=1);

namespace Routepress\Tests\Cli;

use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;
use Routepress\Cli\CommandRegistrar;
use Routepress\Cli\RoutepressCommand;
use Routepress\Cli\RouteRegistry;
use Routepress\Types\RouteDefinition;
use WP_CLI;
use WP_CLI\Formatter;

/**
 * Runs in a separate process so the fake WP-CLI classes in the fixture do not
 * leak into the rest of the suite.
 */
#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
final class RoutepressCommandTest extends TestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/fixtures/wp-cli-stubs.php';

        RouteRegistry::reset();
        CommandRegistrar::reset();
        WP_CLI::$warnings = [];
        WP_CLI::$commands = [];
        Formatter::$items = [];
        Formatter::$fields = [];
    }

    protected function tearDown(): void
    {
        RouteRegistry::reset();
        CommandRegistrar::reset();
    }

    public function testAutoRegistersTheCommandWhenWpCliIsAvailable(): void
    {
        CommandRegistrar::register();

        self::assertSame([['routepress', RoutepressCommand::class]], WP_CLI::$commands);
    }

    public function testListsRoutesForTheRequestedNamespace(): void
    {
        RouteRegistry::register(static fn (): array => [
            new RouteDefinition('myplugin/v1', ['GET', 'POST'], '/items', true, false, []),
            new RouteDefinition('other/v2', ['GET'], '/ping', false, false, []),
        ]);

        (new RoutepressCommand())->routes([], ['namespace' => 'myplugin/v1']);

        self::assertSame(['verb', 'route', 'auth', 'middleware', 'args'], Formatter::$fields);
        self::assertSame(['GET', 'POST'], array_column(Formatter::$items, 'verb'));
        self::assertSame(
            ['/myplugin/v1/items', '/myplugin/v1/items'],
            array_column(Formatter::$items, 'route')
        );
    }

    public function testWarnsWhenNoRoutesAreRegistered(): void
    {
        (new RoutepressCommand())->routes([], []);

        self::assertSame(['No routes registered.'], WP_CLI::$warnings);
        self::assertSame([], Formatter::$items);
        // The formatter must not be constructed for an empty result.
        self::assertSame([], Formatter::$fields);
    }
}
