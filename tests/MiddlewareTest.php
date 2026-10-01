<?php

declare(strict_types=1);

namespace Routepress\Tests;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use Routepress\Middleware;
use WP_Error;
use WP_REST_Request;
use WP_User;

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

    public function testLoggedInReturnsFalseWhenNotLoggedIn(): void
    {
        Functions\expect('is_user_logged_in')->once()->andReturn(false);

        self::assertFalse((Middleware::loggedIn())());
    }

    public function testCapabilityReturnsFalseWhenTheCapabilityIsMissing(): void
    {
        Functions\expect('current_user_can')->once()->with('edit_posts')->andReturn(false);

        self::assertFalse((Middleware::capability('edit_posts'))());
    }

    public function testAnyCapabilityRejectsAnEmptyList(): void
    {
        self::assertFalse((Middleware::anyCapability())());
    }

    public function testChainReturnsTrueWhenEveryMiddlewarePasses(): void
    {
        $called = 0;

        $chain = Middleware::chain([
            static function () use (&$called): bool {
                $called++;

                return true;
            },
            static function () use (&$called): bool {
                $called++;

                return true;
            },
        ]);

        self::assertNotNull($chain);
        self::assertTrue($chain(new WP_REST_Request()));
        self::assertSame(2, $called);
    }

    public function testAllCapabilitiesStopsAtTheFirstMissingCapability(): void
    {
        $checked = [];

        Functions\when('current_user_can')->alias(static function (string $capability) use (&$checked): bool {
            $checked[] = $capability;

            return $capability === 'a';
        });

        self::assertFalse((Middleware::allCapabilities('a', 'b', 'c'))());
        self::assertSame(['a', 'b'], $checked);
    }

    public function testApiKeyAllowsAValidSecret(): void
    {
        $middleware = Middleware::apiKey('secret-123');
        $request = new WP_REST_Request('POST', '/webhook', ['X-Api-Key' => 'secret-123']);

        self::assertTrue($middleware($request));
    }

    public function testApiKeyAcceptsAnyOfSeveralSecrets(): void
    {
        $middleware = Middleware::apiKey(['first', 'second']);
        $request = new WP_REST_Request('POST', '/webhook', ['X-Api-Key' => 'second']);

        self::assertTrue($middleware($request));
    }

    public function testApiKeyIsCaseInsensitiveAboutTheHeaderName(): void
    {
        $middleware = Middleware::apiKey('secret');
        $request = new WP_REST_Request('POST', '/webhook', ['x-api-key' => 'secret']);

        self::assertTrue($middleware($request));
    }

    public function testApiKeyRejectsAnInvalidSecret(): void
    {
        $middleware = Middleware::apiKey('secret');
        $result = $middleware(new WP_REST_Request('POST', '/webhook', ['X-Api-Key' => 'nope']));

        self::assertInstanceOf(WP_Error::class, $result);
        self::assertSame('routepress_invalid_api_key', $result->get_error_code());
        self::assertSame(['status' => 403], $result->get_error_data());
    }

    public function testApiKeyRequiresAKey(): void
    {
        $middleware = Middleware::apiKey('secret');
        $result = $middleware(new WP_REST_Request('POST', '/webhook'));

        self::assertInstanceOf(WP_Error::class, $result);
        self::assertSame('routepress_missing_api_key', $result->get_error_code());
        self::assertSame(['status' => 401], $result->get_error_data());
    }

    public function testApiKeyFallsBackToTheQueryParameter(): void
    {
        $middleware = Middleware::apiKey('secret', 'X-Api-Key', 'api_key');
        $request = new WP_REST_Request('GET', '/webhook', [], ['api_key' => 'secret']);

        self::assertTrue($middleware($request));
    }

    public function testApiKeyStripsTheConfiguredPrefix(): void
    {
        $middleware = Middleware::apiKey('secret', 'Authorization', null, 'Bearer ');
        $request = new WP_REST_Request('GET', '/webhook', ['Authorization' => 'Bearer secret']);

        self::assertTrue($middleware($request));
    }

    public function testApiKeyUsingDelegatesToTheValidator(): void
    {
        $seen = null;
        $middleware = Middleware::apiKeyUsing(static function (string $key) use (&$seen): bool {
            $seen = $key;

            return $key === 'ok';
        });

        self::assertTrue($middleware(new WP_REST_Request('GET', '/webhook', ['X-Api-Key' => 'ok'])));
        self::assertSame('ok', $seen);
    }

    public function testApiKeyUsingRequiresAKey(): void
    {
        $called = false;
        $middleware = Middleware::apiKeyUsing(static function () use (&$called): bool {
            $called = true;

            return true;
        });

        $result = $middleware(new WP_REST_Request('GET', '/webhook'));

        self::assertInstanceOf(WP_Error::class, $result);
        self::assertFalse($called);
    }

    public function testApiKeyUsingPropagatesAWpError(): void
    {
        $error = new WP_Error('expired', 'Expired');
        $middleware = Middleware::apiKeyUsing(static fn (string $key): WP_Error => $error);

        self::assertSame(
            $error,
            $middleware(new WP_REST_Request('GET', '/webhook', ['X-Api-Key' => 'ok']))
        );
    }

    public function testApiKeyUsingSetsTheCurrentUserForAWpUser(): void
    {
        $user = new WP_User(9);
        $middleware = Middleware::apiKeyUsing(static fn (string $key): WP_User => $user);

        Functions\expect('wp_set_current_user')->once()->with(9);

        self::assertTrue($middleware(new WP_REST_Request('GET', '/webhook', ['X-Api-Key' => 'ok'])));
    }
}
