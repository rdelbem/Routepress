<?php

declare(strict_types=1);

namespace Routepress\Tests;

use Brain\Monkey;
use LogicException;
use PHPUnit\Framework\TestCase;
use Routepress\Bootload;
use Routepress\JwtAuthenticator;
use Routepress\RouteGroup;
use Routepress\Tests\Concerns\StubsRouteRegistration;
use WP_Error;
use WP_REST_Request;

final class RouteGroupTest extends TestCase
{
    use StubsRouteRegistration;

    private const NAMESPACE = 'api_namespace';

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

    public function testGroupPrefixIsNormalizedAndApplied(): void
    {
        $group = $this->bootload()->group('admin/');

        $registration = $this->captureRegistration(
            static fn () => $group->create('GET', 'stats', static fn (): bool => true, false)
        );

        self::assertSame('/admin/stats', $registration['route']);
    }

    public function testRootGroupPrefixDoesNotProduceADoubleSlash(): void
    {
        $group = $this->bootload()->group('/');

        $registration = $this->captureRegistration(
            static fn () => $group->create('GET', '/stats', static fn (): bool => true, false)
        );

        self::assertSame('/stats', $registration['route']);
    }

    public function testGroupMiddlewareIsAppliedToRoutes(): void
    {
        $called = false;
        $middleware = static function () use (&$called): bool {
            $called = true;

            return true;
        };

        $group = $this->bootload()->group('/admin')->middleware($middleware);

        $registration = $this->captureRegistration(
            static fn () => $group->create('GET', '/stats', static fn (): bool => true, false)
        );

        $permission = $registration['args']['permission_callback'];
        self::assertIsCallable($permission);
        self::assertTrue($permission(new WP_REST_Request('GET', '/admin/stats')));
        self::assertTrue($called);
    }

    public function testAuthenticationRunsBeforeGroupMiddleware(): void
    {
        $order = [];

        $authenticator = $this->createMock(JwtAuthenticator::class);
        $authenticator->expects(self::once())
            ->method('validateJwt')
            ->willReturnCallback(static function () use (&$order): bool {
                $order[] = 'auth';

                return true;
            });

        $group = (new Bootload($authenticator, self::NAMESPACE))
            ->group('/admin')
            ->middleware(static function () use (&$order): bool {
                $order[] = 'middleware';

                return true;
            });

        $registration = $this->captureRegistration(
            static fn () => $group->create('GET', '/stats', static fn (): bool => true)
        );

        ($registration['args']['permission_callback'])(new WP_REST_Request('GET', '/admin/stats'));

        self::assertSame(['auth', 'middleware'], $order);
    }

    public function testRoutePermissionRunsAfterGroupMiddleware(): void
    {
        $order = [];

        $group = $this->bootload()
            ->group('/admin')
            ->middleware(static function () use (&$order): bool {
                $order[] = 'group';

                return true;
            });

        $routePermission = static function () use (&$order): bool {
            $order[] = 'route';

            return true;
        };

        $registration = $this->captureRegistration(
            static fn () => $group->create(
                'GET',
                '/stats',
                static fn (): bool => true,
                false,
                $routePermission
            )
        );

        ($registration['args']['permission_callback'])(new WP_REST_Request('GET', '/admin/stats'));

        self::assertSame(['group', 'route'], $order);
    }

    public function testGroupMiddlewareWpErrorShortCircuitsTheRoutePermission(): void
    {
        $error = new WP_Error('forbidden', 'Nope.');
        $routeCalled = false;

        $group = $this->bootload()
            ->group('/admin')
            ->withoutAuth()
            ->middleware(static fn (): WP_Error => $error);

        $routePermission = static function () use (&$routeCalled): bool {
            $routeCalled = true;

            return true;
        };

        $registration = $this->captureRegistration(
            static fn () => $group->create(
                'GET',
                '/stats',
                static fn (): bool => true,
                null,
                $routePermission
            )
        );

        $permission = $registration['args']['permission_callback'];
        self::assertSame($error, $permission(new WP_REST_Request('GET', '/admin/stats')));
        self::assertFalse($routeCalled);
    }

    public function testNestedGroupsInheritPrefixAndMiddleware(): void
    {
        $order = [];

        $group = $this->bootload()
            ->group('/admin')
            ->middleware(static function () use (&$order): bool {
                $order[] = 'admin';

                return true;
            });

        $nested = $group
            ->group('/v2')
            ->middleware(static function () use (&$order): bool {
                $order[] = 'v2';

                return true;
            });

        $registration = $this->captureRegistration(
            static fn () => $nested->create('GET', '/stats', static fn (): bool => true, false)
        );

        self::assertSame('/admin/v2/stats', $registration['route']);
        self::assertTrue(($registration['args']['permission_callback'])(new WP_REST_Request()));
        self::assertSame(['admin', 'v2'], $order);
    }

    public function testGroupAcceptsACallback(): void
    {
        $bootload = $this->bootload();

        $registration = $this->captureRegistration(
            static function () use ($bootload): void {
                $bootload->group('/api', static function (RouteGroup $group): void {
                    $group->create('GET', '/ping', static fn (): bool => true, false);
                });
            }
        );

        self::assertSame('/api/ping', $registration['route']);
    }

    public function testGroupCanDisableAuthenticationForAllRoutes(): void
    {
        $bootload = new Bootload(null, self::NAMESPACE);

        $group = $bootload->group('/admin', requiresAuth: false)->middleware(
            static fn (): bool => false
        );

        $registration = $this->captureRegistration(
            static fn () => $group->create('GET', '/stats', static fn (): bool => true)
        );

        self::assertFalse(($registration['args']['permission_callback'])(new WP_REST_Request()));
    }

    public function testAuthenticatedGroupRouteFailsFastWithoutAnAuthenticator(): void
    {
        $group = (new Bootload(null, self::NAMESPACE))->group('/admin');

        $this->expectException(LogicException::class);

        $group->create('GET', '/stats', static fn (): bool => true);
    }

    public function testMultipleMiddlewareCanBeAddedAtOnceInOrder(): void
    {
        $order = [];

        $group = $this->bootload()
            ->group('/x')
            ->withoutAuth()
            ->middleware(
                static function () use (&$order): bool {
                    $order[] = 'first';

                    return true;
                },
                static function () use (&$order): bool {
                    $order[] = 'second';

                    return true;
                },
            );

        $registration = $this->captureRegistration(
            static fn () => $group->create('GET', '/y', static fn (): bool => true)
        );

        ($registration['args']['permission_callback'])(new WP_REST_Request());

        self::assertSame(['first', 'second'], $order);
    }

    public function testNestedGroupInheritsTheAuthDefault(): void
    {
        $group = (new Bootload(null, self::NAMESPACE))->group('/admin')->withoutAuth();
        $nested = $group->group('/v2');

        // No authenticator was configured, yet this must not throw: the nested
        // group inherited the `withoutAuth()` default.
        $registration = $this->captureRegistration(
            static fn () => $nested->create('GET', '/ping', static fn (): bool => true)
        );

        self::assertTrue(($registration['args']['permission_callback'])());
    }

    public function testARouteCanOverrideTheGroupAuthDefault(): void
    {
        $order = [];

        $authenticator = $this->createMock(JwtAuthenticator::class);
        $authenticator->expects(self::once())
            ->method('validateJwt')
            ->willReturnCallback(static function () use (&$order): bool {
                $order[] = 'auth';

                return true;
            });

        $group = (new Bootload($authenticator, self::NAMESPACE))->group('/admin')->withoutAuth();

        $registration = $this->captureRegistration(
            static fn () => $group->create('GET', '/stats', static fn (): bool => true, true)
        );

        self::assertTrue(($registration['args']['permission_callback'])(new WP_REST_Request()));
        self::assertSame(['auth'], $order);
    }

    public function testNestedGroupAcceptsACallback(): void
    {
        $bootload = $this->bootload();

        $registration = $this->captureRegistration(
            static function () use ($bootload): void {
                $group = $bootload->group('/admin')->withoutAuth();
                $group->group('/v2', static function (RouteGroup $nested): void {
                    $nested->create('GET', '/ping', static fn (): bool => true);
                });
            }
        );

        self::assertSame('/admin/v2/ping', $registration['route']);
    }

    private function bootload(?JwtAuthenticator $authenticator = null): Bootload
    {
        return new Bootload(
            $authenticator ?? $this->createMock(JwtAuthenticator::class),
            self::NAMESPACE
        );
    }
}
