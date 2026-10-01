<?php

declare(strict_types=1);

namespace Routepress\Tests\Cli;

use PHPUnit\Framework\TestCase;
use Routepress\Cli\CommandRegistrar;
use Routepress\Cli\RoutepressCommand;

final class CommandRegistrarTest extends TestCase
{
    protected function setUp(): void
    {
        CommandRegistrar::reset();
    }

    protected function tearDown(): void
    {
        CommandRegistrar::reset();
    }

    public function testRegistersTheCommandOnlyOnce(): void
    {
        $calls = [];
        $registrar = static function (string $name, string $class) use (&$calls): void {
            $calls[] = [$name, $class];
        };

        CommandRegistrar::register($registrar);
        CommandRegistrar::register($registrar);

        self::assertSame([['routepress', RoutepressCommand::class]], $calls);
    }

    public function testIsANoOpWithoutWpCli(): void
    {
        self::assertFalse(class_exists('WP_CLI', false));

        CommandRegistrar::register();

        self::assertFalse(class_exists('WP_CLI', false));
    }
}
