<?php

namespace Rougin\Slytherin\Routing;

/**
 * Router Test Cases
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class RouterTestCases extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Rougin\Slytherin\Routing\RouterInterface
     */
    protected $router;

    /**
     * @var array
     */
    protected $routes = array(array('GET', '/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index'));

    /**
     * Sets up the router.
     *
     * @return void
     */
    public function setUp()
    {
        $this->markTestSkipped('No implementation style defined.');
    }

    /**
     * Tests RouterInterface::add.
     *
     * @return void
     */
    public function testAddMethod()
    {
        $this->exists(get_class($this->router));

        $this->router->add('POST', '/store', 'Rougin\Slytherin\Fixture\Classes\NewClass@store');

        $this->assertTrue($this->router->has('POST', '/store'));
    }

    /**
     * Tests RouterInterface::__call.
     *
     * @return void
     */
    public function testCallMagicMethod()
    {
        $this->exists(get_class($this->router));

        $this->router->post('/posts', 'PostsController@store');

        $this->assertTrue($this->router->has('POST', '/posts'));
    }

    /**
     * Tests RouterInterface::__call with an exception.
     *
     * @return void
     */
    public function testCallMagicMethodWithException()
    {
        $this->exists(get_class($this->router));

        $this->setExpectedException('BadMethodCallException', '"TEST" is not a valid HTTP method');

        $this->router->test('/test', 'PostsController@test');
    }

    /**
     * Tests RouterInterface::has.
     *
     * @return void
     */
    public function testHasMethod()
    {
        $this->exists(get_class($this->router));

        $this->assertTrue($this->router->has('GET', '/'));
    }

    /**
     * Tests RouterInterface::retrieve.
     *
     * @return void
     */
    public function testRetrieveMethod()
    {
        $this->exists(get_class($this->router));

        $expected = 'Rougin\Slytherin\Fixture\Classes\NewClass@index';

        $route = $this->router->retrieve('GET', '/');

        $this->assertEquals($expected, implode('@', $route[2]));
    }

    /**
     * Tests RouterInterface::retrieve with null as the result.
     *
     * @return void
     */
    public function testRetrieveMethodWithNull()
    {
        $this->exists(get_class($this->router));

        $route = $this->router->retrieve('GET', '/test');

        $this->assertEquals(null, $route);
    }

    /**
     * Tests RouterInterface::routes.
     *
     * @return void
     */
    public function testRoutesMethod()
    {
        $this->exists(get_class($this->router));

        $routes = $this->routes;

        $routes[0][2] = explode('@', $routes[0][2]);

        array_push($routes[0], array());

        $this->assertEquals($routes, $this->router->routes());
    }

    /**
     * Tests RouterInterface::restful.
     *
     * @return void
     */
    public function testRestfulMethod()
    {
        $this->exists(get_class($this->router));

        $expected = array();

        array_push($expected, array('GET', '/', array('Rougin\Slytherin\Fixture\Classes\NewClass', 'index'), array()));
        array_push($expected, array('GET', '/posts', array('PostsController', 'index'), array()));
        array_push($expected, array('POST', '/posts', array('PostsController', 'store'), array()));
        array_push($expected, array('DELETE', '/posts/:id', array('PostsController', 'delete'), array()));
        array_push($expected, array('GET', '/posts/:id', array('PostsController', 'show'), array()));
        array_push($expected, array('PATCH', '/posts/:id', array('PostsController', 'update'), array()));
        array_push($expected, array('PUT', '/posts/:id', array('PostsController', 'update'), array()));

        $this->router->restful('posts', 'PostsController');

        $this->assertEquals($expected, $this->router->routes());
    }

    /**
     * Tests RouterInterface::prefix.
     *
     * @return void
     */
    public function testPrefixMethod()
    {
        $this->exists(get_class($this->router));

        $this->router->prefix('/v1/slytherin');

        $this->router->get('/hello', 'HelloController@hello');

        $this->assertTrue($this->router->has('GET', '/v1/slytherin/hello'));
    }

    /**
     * Tests RouterInterface::merge.
     *
     * @return void
     */
    public function testMergeMethod()
    {
        $this->exists(get_class($this->router));

        $router = new Router;

        $router->get('/test', 'TestController@test');

        $this->router->merge($router->routes());

        $this->assertTrue($this->router->has('GET', '/test'));
    }

    /**
     * Verifies the specified router if it exists.
     *
     * @param  string $router
     * @return void
     */
    protected function exists($router)
    {
        switch ($router) {
            case 'Rougin\Slytherin\Routing\FastRouteRouter':
                if (! class_exists('FastRoute\RouteCollector')) {
                    $this->markTestSkipped('FastRoute is not installed.');
                }

                break;
            case 'Rougin\Slytherin\Routing\PhrouteRouter':
                if (! class_exists('Phroute\Phroute\RouteCollector')) {
                    $this->markTestSkipped('Phroute is not installed.');
                }

                break;
        }
    }
}
