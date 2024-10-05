<?php

use Codeception\TestCase\WPTestCase;
use Rdelbem\Routepress\Routepress;
use Rdelbem\Routepress\AuthInterface;

class RoutepressTest extends WPTestCase
{
    private $routepress;

    public function setUp(): void
    {
        parent::setUp();
        $authInterfaceMock = $this->createMock(AuthInterface::class);
        $this->routepress = new Routepress($authInterfaceMock, 'api_namespace');
    }

    public function testCreateRoute()
    {
        $httpVerb = 'GET';
        $route = '/test-route';
        $callback = function () {
            return 'Hello World, from Routepress!';
        };

        $this->routepress->create($httpVerb, $route, $callback, true);

        $request = new WP_REST_Request($httpVerb, '/api_namespace' . $route);
        $response = rest_get_server()->dispatch($request);

        $this->assertEquals(200, $response->get_status());
        $this->assertEquals('Hello World, from Routepress!', $response->get_data());
    }
}
