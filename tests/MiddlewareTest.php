<?php

declare(strict_types=1);

namespace Routepress\Tests;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use Routepress\Middleware;
use WP_Error;
use WP_REST_Request;

final class MiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Monkey\setUp();
    }

    protected function tearDown(): void
    {
        Monkey\tearDown();
        parent::tearDown();
    }

    public function testLoggedInDelegatesToWordPress(): void
    {
        Functions\expect('is_user_logged_in')->once()->andReturn(true);

        self::assertTrue((Middleware::loggedIn())());
    }

    public function testCapabilityDelegatesToCurrentUserCan(): void
    {
        Functions\expect('current_user_can')->once()->with('edit_posts')->andReturn(true);

        self::assertTrue((Middleware::capability('edit_posts'))());
    }

    public function testAnyCapabilityPassesWhenOneMatches(): void
    {
        Functions\when('current_user_can')->alias(
            static fn (string $capability): bool => $capability === 'publish_posts'
        );

        self::assertTrue((Middleware::anyCapability('edit_posts', 'publish_posts'))());
    }

    public function testAnyCapabilityFailsWhenNoneMatch(): void
    {
        Functions\when('current_user_can')->justReturn(false);

        self::assertFalse((Middleware::anyCapability('edit_posts', 'publish_posts'))());
    }

    public function testAllCapabilitiesRequiresEveryCapability(): void
    {
        Functions\when('current_user_can')->alias(
            static fn (string $capability): bool => $capability !== 'publish_posts'
        );

        self::assertFalse((Middleware::allCapabilities('edit_posts', 'publish_posts'))());
    }

    public function testAllCapabilitiesRejectsAnEmptyList(): void
    {
        self::assertFalse((Middleware::allCapabilities())());
    }

    public function testAllCapabilitiesPassesWhenEveryCapabilityMatches(): void
    {
        Functions\when('current_user_can')->justReturn(true);

        self::assertTrue((Middleware::allCapabilities('edit_posts', 'publish_posts'))());
    }

    public function testChainReturnsNullWhenEmpty(): void
    {
        self::assertNull(Middleware::chain([]));
    }

    public function testChainReturnsASingleMiddlewareAsIs(): void
    {
        $middleware = static fn (): bool => true;

        self::assertSame($middleware, Middleware::chain([$middleware]));
    }

    public function testChainStopsAtTheFirstFailure(): void
    {
        $called = false;

        $chain = Middleware::chain([
            static fn (): bool => false,
            static function () use (&$called): bool {
                $called = true;

                return true;
            },
        ]);

        self::assertNotNull($chain);
        self::assertFalse($chain(new WP_REST_Request()));
        self::assertFalse($called);
    }

    public function testChainReturnsAWpErrorAsIs(): void
    {
        $error = new WP_Error('forbidden', 'Nope');
        $called = false;

        $chain = Middleware::chain([
            static fn (): WP_Error => $error,
            static function () use (&$called): bool {
                $called = true;

                return true;
            },
        ]);

        self::assertNotNull($chain);
        self::assertSame($error, $chain(new WP_REST_Request()));
        self::assertFalse($called);
    }
}
