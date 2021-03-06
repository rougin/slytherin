<?php

namespace Rougin\Slytherin\Dispatching\Phroute;

/**
 * Router Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class RouterTest extends \PHPUnit_Framework_TestCase
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
    public function setUp()
    {
        if (! class_exists('Phroute\Phroute\RouteCollector')) {
            $this->markTestSkipped('Phroute is not installed.');
        }

        $this->router = new \Rougin\Slytherin\Dispatching\Phroute\Router;

        $this->router->setCollector(new \Phroute\Phroute\RouteCollector);
    }

    /**
     * Tests if the newly added route exists in the router.
     *
     * @return void
     */
    public function testAddRouteMethod()
    {
        list($httpMethod, $uri, $handler) = $this->routes[0];

        $this->router->addRoute($httpMethod, $uri, $handler);

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
        $this->router = new \Rougin\Slytherin\Dispatching\Phroute\Router($this->routes);

        $this->assertInstanceOf('Phroute\Phroute\RouteDataArray', $this->router->getRoutes(true));
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
}
