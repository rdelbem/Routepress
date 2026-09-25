<?php

declare(strict_types=1);

namespace Routepress\Tests\Concerns;

use Brain\Monkey\Functions;
use Mockery;

trait StubsRouteRegistration
{
    /**
     * Stub the WordPress registration functions, immediately invoking the
     * `rest_api_init` callback and returning what it registered.
     *
     * @param callable(): void $create
     * @return array{namespace: string, route: string, args: array<string, mixed>}
     */
    private function captureRegistration(callable $create): array
    {
        $registration = [];

        Functions\expect('add_action')
            ->once()
            ->with('rest_api_init', Mockery::type('callable'))
            ->andReturnUsing(static function (string $hook, callable $callback): bool {
                $callback();

                return true;
            });

        Functions\expect('register_rest_route')
            ->once()
            ->andReturnUsing(static function (
                string $namespace,
                string $route,
                array $args
            ) use (&$registration): bool {
                $registration = [
                    'namespace' => $namespace,
                    'route' => $route,
                    'args' => $args,
                ];

                return true;
            });

        $create();

        return $registration;
    }
}
