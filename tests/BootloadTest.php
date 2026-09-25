<?php

declare(strict_types=1);

namespace Routepress\Tests;

use Brain\Monkey;
use Brain\Monkey\Functions;
use InvalidArgumentException;
use LogicException;
use PHPUnit\Framework\TestCase;
use Routepress\AuthInterface;
use Routepress\Bootload;
use Routepress\JwtAuthenticator;
use Routepress\Tests\Concerns\StubsRouteRegistration;
use Routepress\Types\AuthHeader;
use Routepress\Types\HttpVerb;
use Routepress\Types\JWT;
use Routepress\Types\RefreshToken;
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

    public function testAuthHeaderSerializesItsParts(): void
    {
        $header = new AuthHeader(
            new JWT(1_700_000_000, 'routepress', 1_700_003_600, '7'),
            new RefreshToken('refresh-token', 1_700_003_600)
        );

        self::assertSame([
            'jwt' => [
                'iat' => 1_700_000_000,
                'iss' => 'routepress',
                'exp' => 1_700_003_600,
                'uid' => '7',
            ],
            'refresh_token' => [
                'refresh_token' => 'refresh-token',
                'exp' => 1_700_003_600,
            ],
        ], $header->jsonSerialize());
    }

    private function bootload(?JwtAuthenticator $authenticator = null): Bootload
    {
        return new Bootload(
            $authenticator ?? $this->createMock(JwtAuthenticator::class),
            self::NAMESPACE
        );
    }
}
