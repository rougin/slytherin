<?php

namespace Rougin\Slytherin\Dispatching\FastRoute;

use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use Rougin\Slytherin\Routing\Route;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RouterTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Dispatching\FastRoute\Router
     */
    protected $router;

    /**
     * @var \Rougin\Slytherin\Routing\RouteInterface[]
     */
    protected $routes = array();

    /**
     * @return void
     */
    protected function doSetUp()
    {
        // @codeCoverageIgnoreStart
        if (! class_exists('FastRoute\RouteCollector'))
        {
            $this->markTestSkipped('FastRoute is not installed.');
        }
        // @codeCoverageIgnoreEnd

        // Generate a sample route for testing --------------
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $route = new Route('GET', '/', $class . '@index');

        array_push($this->routes, $route);
        // --------------------------------------------------

        $collector = new RouteCollector(new Std, new GroupCountBased);

        $this->router = new Router;

        $this->router->setCollector($collector);
    }

    /**
     * @return void
     */
    public function test_adding_a_route()
    {
        // Returns details from the sample route ---
        $expected = $this->routes[0];

        $method = $expected->getMethod();

        $uri = $expected->getUri();

        $handler = $expected->getHandler();
        // -----------------------------------------

        $this->router->addRoute($method, $uri, $handler);

        $actual = $this->router->getRoute($method, $uri);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_an_empty_route()
    {
        $this->assertNull($this->router->getRoute('GET', '/test'));
    }

    /**
     * @return void
     */
    public function test_getting_parsed_routes()
    {
        $this->router = new Router;

        $this->assertInstanceOf('Closure', $this->router->parsed());
    }

    /**
     * @return void
     */
    public function test_checking_the_router_interface()
    {
        $interface = 'Rougin\Slytherin\Routing\RouterInterface';

        $this->assertInstanceOf($interface, $this->router);
    }
}
