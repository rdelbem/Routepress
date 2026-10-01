<?php

declare(strict_types=1);

namespace Routepress\Tests\Cli;

use PHPUnit\Framework\TestCase;
use Routepress\Cli\RouteReporter;
use Routepress\Types\RouteDefinition;

final class RouteReporterTest extends TestCase
{
    public function testBuildsOneRowPerVerb(): void
    {
        $rows = RouteReporter::rows([
            new RouteDefinition('ns', ['GET', 'POST'], '/items', true, false, []),
        ]);

        self::assertSame(['GET', 'POST'], array_column($rows, 'verb'));
        self::assertSame(['/ns/items', '/ns/items'], array_column($rows, 'route'));
    }

    public function testFormatsAuthMiddlewareAndArgs(): void
    {
        $rows = RouteReporter::rows([
            new RouteDefinition(
                'myplugin/v1',
                ['GET'],
                '/items/(?P<id>\d+)',
                true,
                true,
                ['q' => ['type' => 'string']]
            ),
        ]);

        self::assertSame([
            [
                'verb' => 'GET',
                'route' => '/myplugin/v1/items/{id}',
                'auth' => 'jwt',
                'middleware' => 'yes',
                'args' => 'q',
            ],
        ], $rows);
    }

    public function testFiltersByNamespace(): void
    {
        $definitions = [
            new RouteDefinition('a/v1', ['GET'], '/x', false, false, []),
            new RouteDefinition('b/v1', ['GET'], '/y', false, false, []),
        ];

        $rows = RouteReporter::rows($definitions, 'b/v1');

        self::assertSame(['/b/v1/y'], array_column($rows, 'route'));
    }

    public function testSortsByRouteThenVerb(): void
    {
        $rows = RouteReporter::rows([
            new RouteDefinition('ns', ['POST', 'GET'], '/b', false, false, []),
            new RouteDefinition('ns', ['GET'], '/a', false, false, []),
        ]);

        self::assertSame(
            [['/ns/a', 'GET'], ['/ns/b', 'GET'], ['/ns/b', 'POST']],
            array_map(static fn (array $row): array => [$row['route'], $row['verb']], $rows)
        );
    }

    public function testReturnsNoRowsForNoDefinitions(): void
    {
        self::assertSame([], RouteReporter::rows([], null));
    }

    public function testWithoutAFilterIncludesEveryNamespace(): void
    {
        $rows = RouteReporter::rows([
            new RouteDefinition('a/v1', ['GET'], '/x', false, false, []),
            new RouteDefinition('b/v1', ['GET'], '/y', false, false, []),
        ]);

        self::assertSame(['/a/v1/x', '/b/v1/y'], array_column($rows, 'route'));
    }

    public function testJoinsMultipleArgumentNames(): void
    {
        $rows = RouteReporter::rows([
            new RouteDefinition('ns', ['GET'], '/x', false, false, ['q' => [], 'page' => []]),
        ]);

        self::assertSame('q, page', $rows[0]['args']);
    }
}
