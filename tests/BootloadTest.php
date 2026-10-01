<?php

declare(strict_types=1);

namespace Routepress\Tests;

use Brain\Monkey;
use Brain\Monkey\Functions;
use InvalidArgumentException;
use LogicException;
use Mockery;
use PHPUnit\Framework\TestCase;
use Routepress\AuthInterface;
use Routepress\Bootload;
use Routepress\Cli\RouteRegistry;
use Routepress\JwtAuthenticator;
use Routepress\Tests\Concerns\StubsRouteRegistration;
use Routepress\Types\HttpVerb;
use WP_Error;
use WP_REST_Request;
use WP_User;

final class BootloadTest extends TestCase
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

    public function testRegisteredRoutesAreExposedToTheCliRegistry(): void
    {
        RouteRegistry::reset();
        $bootload = $this->bootload();

        $this->captureRegistration(
            static fn () => $bootload->create('GET', '/items/:id', static fn (): bool => true, false)
        );

        $definitions = RouteRegistry::all();

        self::assertCount(1, $definitions);
        self::assertSame('/items/{id}', $definitions[0]->humanPath());
        self::assertFalse($definitions[0]->requiresAuth);
    }

    public function testCreateRegistersAnOpenRouteOnRestApiInit(): void
    {
        $bootload = $this->bootload();

        $registration = $this->captureRegistration(
            static fn () => $bootload->create(
                'GET',
                '/test-route',
                static fn (): string => 'Hello World, from Routepress!',
                false
            )
        );

        self::assertSame(self::NAMESPACE, $registration['namespace']);
        self::assertSame('/test-route', $registration['route']);
        self::assertSame(['GET'], $registration['args']['methods']);
        self::assertSame([], $registration['args']['args']);
        self::assertIsCallable($registration['args']['permission_callback']);
        self::assertTrue(($registration['args']['permission_callback'])());
        self::assertIsCallable($registration['args']['callback']);
    }

    public function testCreateRequiresAuthenticationByDefault(): void
    {
        $bootload = $this->bootload();

        $registration = $this->captureRegistration(
            static fn () => $bootload->create('POST', '/protected', static fn (): string => 'secret')
        );

        self::assertSame([$bootload, 'securityCheck'], $registration['args']['permission_callback']);
    }

    public function testCreateForwardsRouteArguments(): void
    {
        $bootload = $this->bootload();
        $args = ['id' => ['type' => 'integer', 'required' => true]];

        $registration = $this->captureRegistration(
            static fn () => $bootload->create(
                'GET',
                '/items/:id',
                static fn (): bool => true,
                false,
                null,
                $args
            )
        );

        self::assertSame($args, $registration['args']['args']);
    }

    public function testAuthenticationErrorIsPropagated(): void
    {
        $error = new WP_Error('invalid_token', 'The token expired.');

        $authenticator = $this->createMock(JwtAuthenticator::class);
        $authenticator->expects(self::once())->method('validateJwt')->willReturn($error);

        $bootload = new Bootload($authenticator, self::NAMESPACE);

        $registration = $this->captureRegistration(
            static fn () => $bootload->create('GET', '/protected', static fn (): bool => true)
        );

        $permission = $registration['args']['permission_callback'];
        self::assertSame($error, $permission(new WP_REST_Request('GET', '/protected')));
    }

    public function testCustomPermissionIsComposedWithAuthentication(): void
    {
        $authCalled = false;
        $permissionCalled = false;

        $authenticator = $this->createMock(JwtAuthenticator::class);
        $authenticator->expects(self::once())
            ->method('validateJwt')
            ->willReturnCallback(static function () use (&$authCalled): bool {
                $authCalled = true;

                return false;
            });

        $permission = static function () use (&$permissionCalled): bool {
            $permissionCalled = true;

            return true;
        };

        $bootload = new Bootload($authenticator, self::NAMESPACE);

        $registration = $this->captureRegistration(
            static fn () => $bootload->create(
                'GET',
                '/admin',
                static fn (): bool => true,
                true,
                $permission
            )
        );

        $chain = $registration['args']['permission_callback'];
        self::assertIsCallable($chain);
        self::assertFalse($chain(new WP_REST_Request('GET', '/admin')));
        self::assertTrue($authCalled);
        self::assertFalse($permissionCalled);
    }

    public function testPublicRouteWorksWithoutAnAuthenticator(): void
    {
        $bootload = new Bootload(null, self::NAMESPACE);

        $registration = $this->captureRegistration(
            static fn () => $bootload->create(
                'GET',
                '/public',
                static fn (): bool => true,
                false
            )
        );

        self::assertTrue(($registration['args']['permission_callback'])());
    }

    public function testCustomPermissionRouteWorksWithoutAnAuthenticator(): void
    {
        $bootload = new Bootload(null, self::NAMESPACE);
        $permission = static fn (): bool => current_user_can('manage_options');

        $registration = $this->captureRegistration(
            static fn () => $bootload->create(
                'GET',
                '/admin',
                static fn (): bool => true,
                false,
                $permission
            )
        );

        self::assertSame($permission, $registration['args']['permission_callback']);
    }

    public function testAuthenticatedRouteWithoutAnAuthenticatorThrows(): void
    {
        $bootload = new Bootload(null, self::NAMESPACE);

        $this->expectException(LogicException::class);

        $bootload->create('GET', '/protected', static fn (): bool => true);
    }

    public function testSecurityCheckSetsTheCurrentUser(): void
    {
        $request = new WP_REST_Request('GET', '/whatever');
        $user = new WP_User(7);

        $authenticator = $this->createMock(JwtAuthenticator::class);
        $authenticator->expects(self::once())
            ->method('validateJwt')
            ->with($request)
            ->willReturn($user);

        Functions\expect('wp_set_current_user')->once()->with(7);

        $bootload = new Bootload($authenticator, self::NAMESPACE);

        self::assertTrue($bootload->securityCheck($request));
    }

    public function testSecurityCheckReturnsFalseWhenAuthenticationFails(): void
    {
        $authenticator = $this->createMock(JwtAuthenticator::class);
        $authenticator->method('validateJwt')->willReturn(false);

        $bootload = new Bootload($authenticator, self::NAMESPACE);

        self::assertFalse($bootload->securityCheck(new WP_REST_Request('GET', '/whatever')));
    }

    public function testSecurityCheckWithoutAnAuthenticatorThrows(): void
    {
        $bootload = new Bootload(null, self::NAMESPACE);

        $this->expectException(LogicException::class);

        $bootload->securityCheck(new WP_REST_Request('GET', '/whatever'));
    }

    public function testCreateAcceptsMultipleAndMixedCasedVerbs(): void
    {
        $bootload = $this->bootload();

        $registration = $this->captureRegistration(
            static fn () => $bootload->create(
                ['get', HttpVerb::POST, 'post'],
                '/multi',
                static fn (): bool => true,
                false
            )
        );

        self::assertSame(['GET', 'POST'], $registration['args']['methods']);
    }

    public function testHeadVerbIsSupported(): void
    {
        $bootload = $this->bootload();

        $registration = $this->captureRegistration(
            static fn () => $bootload->create(
                'HEAD',
                '/ping',
                static fn (): bool => true,
                false
            )
        );

        self::assertSame(['HEAD'], $registration['args']['methods']);
    }

    public function testDynamicRouteSegmentsAreConvertedToCaptureGroups(): void
    {
        $bootload = $this->bootload();

        $registration = $this->captureRegistration(
            static fn () => $bootload->create(
                'GET',
                '/items/:id',
                static fn (): bool => true,
                false
            )
        );

        self::assertSame('/items/(?P<id>[\w-]+)', $registration['route']);
    }

    public function testDynamicRouteSegmentsAcceptACustomRegex(): void
    {
        $bootload = $this->bootload();

        $registration = $this->captureRegistration(
            static fn () => $bootload->create(
                'GET',
                '/items/:id(\d+)',
                static fn (): bool => true,
                false
            )
        );

        self::assertSame('/items/(?P<id>\d+)', $registration['route']);
    }

    public function testRouteWithoutLeadingSlashIsNormalized(): void
    {
        $bootload = $this->bootload();

        $registration = $this->captureRegistration(
            static fn () => $bootload->create(
                'GET',
                'items',
                static fn (): bool => true,
                false
            )
        );

        self::assertSame('/items', $registration['route']);
    }

    public function testAnUnsupportedVerbThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported HTTP verb "TRACE".');

        $this->bootload()->create('TRACE', '/invalid', static fn (): bool => true, false);
    }

    public function testAnEmptyVerbListThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->bootload()->create([], '/invalid', static fn (): bool => true, false);
    }

    public function testAuthInterfaceIsAJwtAuthenticator(): void
    {
        self::assertTrue(is_subclass_of(AuthInterface::class, JwtAuthenticator::class));
    }

    public function testMultipleRoutesRegisterOnASingleRestApiInitCallback(): void
    {
        $bootload = $this->bootload();
        $registered = [];
        $hooks = [];

        Functions\expect('add_action')
            ->once()
            ->with('rest_api_init', Mockery::type('callable'))
            ->andReturnUsing(static function (string $hook, callable $callback) use (&$hooks): bool {
                $hooks[] = $callback;

                return true;
            });

        Functions\expect('register_rest_route')
            ->twice()
            ->andReturnUsing(static function (string $namespace, string $route, array $args) use (&$registered): bool {
                $registered[$route] = $args;

                return true;
            });

        $bootload->create('GET', '/a', static fn (): bool => true, false);
        $bootload->create('POST', '/b', static fn (): bool => true, false);

        // Simulate WordPress firing `rest_api_init` once, after all routes exist.
        foreach ($hooks as $hook) {
            $hook();
        }

        self::assertCount(1, $hooks);
        self::assertSame(['/a', '/b'], array_keys($registered));
    }

    public function testNonStringVerbEntriesAreRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('HTTP verbs must be strings or HttpVerb cases.');

        /** @var array<int, mixed> $verbs */
        $verbs = ['GET', 123];

        $this->bootload()->create($verbs, '/invalid', static fn (): bool => true, false);
    }

    public function testAuthenticationMiddlewareAndRoutePermissionRunInOrder(): void
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

        $routePermission = static function () use (&$order): bool {
            $order[] = 'route';

            return true;
        };

        $registration = $this->captureRegistration(
            static fn () => $group->create('GET', '/stats', static fn (): bool => true, true, $routePermission)
        );

        ($registration['args']['permission_callback'])(new WP_REST_Request('GET', '/admin/stats'));

        self::assertSame(['auth', 'middleware', 'route'], $order);
    }

    public function testSecurityCheckReturnsTrueWithoutSettingAUser(): void
    {
        $request = new WP_REST_Request('GET', '/whatever');

        $authenticator = $this->createMock(JwtAuthenticator::class);
        $authenticator->expects(self::once())
            ->method('validateJwt')
            ->with($request)
            ->willReturn(true);

        Functions\expect('wp_set_current_user')->never();

        $bootload = new Bootload($authenticator, self::NAMESPACE);

        self::assertTrue($bootload->securityCheck($request));
    }

    private function bootload(?JwtAuthenticator $authenticator = null): Bootload
    {
        return new Bootload(
            $authenticator ?? $this->createMock(JwtAuthenticator::class),
            self::NAMESPACE
        );
    }
}
