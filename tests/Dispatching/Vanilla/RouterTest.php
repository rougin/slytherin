<?php

namespace Rougin\Slytherin\Dispatching\Vanilla;

/**
 * Router Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class RouterTest extends \Rougin\Slytherin\Testcase
{
    /**
     * @var \Rougin\Slytherin\Dispatching\RouterInterface
     */
    protected $router;

    /**
     * @var array
     */
    protected $routes = array(
        array('GET', '/', array('Rougin\Slytherin\Fixture\Classes\NewClass', 'index'), array())
    );

    /**
     * Sets up the router.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $this->router = new \Rougin\Slytherin\Dispatching\Vanilla\Router;
    }

    /**
     * Tests if the newly added route exists in the router.
     *
     * @return void
     */
    public function testAddRouteMethod()
    {
        list($httpMethod, $uri, $handler) = $this->routes[0];

        $this->router->get($uri, $handler);

        $this->assertEquals($this->routes[0], $this->router->getRoute($httpMethod, $uri));
    }

    /**
     * Tests if the getRoute() method returns null.
     *
     * @return void
     */
    public function testEmptyGetRouteMethod()
    {
        $this->assertNull($this->router->getRoute('GET', '/test'));
    }

    /**
     * Tests if the specified routes exists in the listing of routes.
     *
     * @return void
     */
    public function testGetRoutesMethod()
    {
        $this->router = new \Rougin\Slytherin\Dispatching\Vanilla\Router($this->routes);

        $this->assertCount(1, $this->router->getRoutes(true));
    }

    /**
     * Tests if the router is implemented in RouterInterface.
     *
     * @return void
     */
    public function testRouterInterface()
    {
        $interface = 'Rougin\Slytherin\Routing\RouterInterface';

        $this->assertInstanceOf($interface, $this->router);
    }

    /**
     * Tests if $router->test() returns an BadMethodCallException.
     *
     * @return void
     */
    public function testBadMethodCallException()
    {
        $this->setExpectedException('BadMethodCallException');

        list($httpMethod, $uri, $handler) = $this->routes[0];

        $this->router->test($uri, $handler);
    }

    /**
     * Tests $router->setPrefix() to add additional prefix in new routes.
     *
     * @return void
     */
    public function testSetPrefixMethod()
    {
        $this->router->setPrefix('/v1/slytherin');

        list($httpMethod, $uri, $handler) = $this->routes[0];

        $this->router->get($uri, $handler);

        $expected = '/v1/slytherin/';

        $route = $this->router->getRoute($httpMethod, $expected);

        $this->assertEquals($expected, $route[1]);
    }

    /**
     * Tests $router->restful() to add additional routes based on route.
     *
     * @return void
     */
    public function testRestfulMethod()
    {
        $this->router->restful('new', 'Rougin\Slytherin\Fixture\Classes\NewClass');

        $this->assertCount(6, $this->router->getRoutes(true));
    }

    /**
     * Tests $router->addRoutes() to add a listing of routes.
     *
     * @return void
     */
    public function testAddRoutesMethod()
    {
        $this->router->addRoutes($this->routes);

        $this->assertEquals($this->routes, $this->router->getRoutes());
    }

    /**
     * Tests if the newly added route exists in the router.
     *
     * @return void
     */
    public function testHasMethod()
    {
        list($httpMethod, $uri, $handler) = $this->routes[0];

        $this->router->get($uri, $handler);

        $this->assertTrue($this->router->has($httpMethod, $uri));
    }
}
