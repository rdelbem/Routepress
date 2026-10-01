<?php

declare(strict_types=1);

namespace Routepress\Tests\Types;

use PHPUnit\Framework\TestCase;
use Routepress\Types\RouteDefinition;

final class RouteDefinitionTest extends TestCase
{
    public function testHumanPathConvertsCaptureGroups(): void
    {
        $definition = $this->definition('/(?P<id>[\w-]+)/reviews/(?P<reviewId>\d+)');

        self::assertSame('/{id}/reviews/{reviewId}', $definition->humanPath());
    }

    public function testFullPathIncludesTheNamespace(): void
    {
        $definition = $this->definition('/items/(?P<id>\d+)', 'myplugin/v1');

        self::assertSame('/myplugin/v1/items/{id}', $definition->fullPath());
    }

    public function testFullPathTrimsSlashesFromTheNamespace(): void
    {
        $definition = $this->definition('/items', '/myplugin/v1/');

        self::assertSame('/myplugin/v1/items', $definition->fullPath());
    }

    public function testArgumentNamesReturnsTheSchemaKeys(): void
    {
        $definition = new RouteDefinition(
            'ns',
            ['GET'],
            '/items',
            false,
            false,
            ['q' => ['type' => 'string'], 'page' => []]
        );

        self::assertSame(['q', 'page'], $definition->argumentNames());
    }

    public function testHumanPathLeavesRoutesWithoutCaptureGroupsUntouched(): void
    {
        self::assertSame('/items', $this->definition('/items')->humanPath());
    }

    public function testArgumentNamesIsEmptyWithoutASchema(): void
    {
        self::assertSame([], $this->definition('/items')->argumentNames());
    }

    private function definition(string $route, string $namespace = 'ns'): RouteDefinition
    {
        return new RouteDefinition($namespace, ['GET'], $route, false, false, []);
    }
}
