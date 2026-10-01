<?php

declare(strict_types=1);

namespace Routepress\Tests\Cli;

use PHPUnit\Framework\TestCase;
use Routepress\Cli\RouteRegistry;
use Routepress\Types\RouteDefinition;

final class RouteRegistryTest extends TestCase
{
    protected function setUp(): void
    {
        RouteRegistry::reset();
    }

    protected function tearDown(): void
    {
        RouteRegistry::reset();
    }

    public function testAggregatesProviders(): void
    {
        RouteRegistry::register(
            static fn (): array => [new RouteDefinition('ns', ['GET'], '/a', false, false, [])]
        );
        RouteRegistry::register(
            static fn (): array => [new RouteDefinition('ns', ['GET'], '/b', false, false, [])]
        );

        self::assertCount(2, RouteRegistry::all());
    }

    public function testResetClearsProviders(): void
    {
        RouteRegistry::register(
            static fn (): array => [new RouteDefinition('ns', ['GET'], '/a', false, false, [])]
        );

        RouteRegistry::reset();

        self::assertSame([], RouteRegistry::all());
    }
}
