<?php declare(strict_types = 1);

namespace Rougin\Slytherin\Dispatching\Phroute;

use Phroute\Phroute\RouteCollector;
use Rougin\Slytherin\Routing\Route;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class RouterTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Routing\PhrouteRouter
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
        if (! class_exists('Phroute\Phroute\RouteCollector'))
        {
            $this->markTestSkipped('Phroute is not installed.');
        }
        // @codeCoverageIgnoreEnd

        // Generate a sample route for testing --------------
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $route = new Route('GET', '/', $class . '@index');

        array_push($this->routes, $route);
        // --------------------------------------------------

        $this->router = new Router;

        $this->router->setCollector(new RouteCollector);
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
        $expected = 'Phroute\Phroute\RouteDataArray';

        $this->router = new Router;

        $actual = $this->router->parsed();

        $this->assertInstanceOf($expected, $actual);
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
