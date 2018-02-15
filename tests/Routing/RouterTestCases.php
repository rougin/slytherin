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

        $this->router->prefix('', 'Rougin\Slytherin\Fixture\Classes');

        $this->router->add('POST', '/store', 'NewClass@store');

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
        $this->setExpectedException('BadMethodCallException');

        $this->exists(get_class($this->router));

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

        $result = $this->router->has('GET', '/');

        $this->assertTrue((boolean) $result);
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

        $result = implode('@', $route[2]);

        $this->assertEquals($expected, $result);
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

        $this->assertNull($route);
    }

    /**
     * Tests RouterInterface::routes.
     *
     * @return void
     */
    public function testRoutesMethod()
    {
        $this->exists(get_class($this->router));

        $expected = $this->routes;

        $expected[0][2] = explode('@', $expected[0][2]);

        $expected[0][] = array();

        $result = $this->router->routes();

        $this->assertEquals($expected, $result);
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

        $expected[] = array('GET', '/', array('Rougin\Slytherin\Fixture\Classes\NewClass', 'index'), array());
        $expected[] = array('GET', '/posts', array('PostsController', 'index'), array());
        $expected[] = array('POST', '/posts', array('PostsController', 'store'), array());
        $expected[] = array('DELETE', '/posts/:id', array('PostsController', 'delete'), array());
        $expected[] = array('GET', '/posts/:id', array('PostsController', 'show'), array());
        $expected[] = array('PATCH', '/posts/:id', array('PostsController', 'update'), array());
        $expected[] = array('PUT', '/posts/:id', array('PostsController', 'update'), array());

        $this->router->restful('posts', 'PostsController');

        $result = $this->router->routes();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests RouterInterface::prefix.
     *
     * @return void
     */
    public function testPrefixMethod()
    {
        $this->exists(get_class($this->router));

        $this->router->prefix((string) '/v1/slytherin');

        $this->router->get('/hello', (string) 'HelloController@hello');

        $exists = $this->router->has('GET', '/v1/slytherin/hello');

        $this->assertTrue($exists);
    }

    /**
     * Tests RouterInterface::prefix with multiple prefixes.
     *
     * @return void
     */
    public function testPrefixMethodWithMultiplePrefixes()
    {
        $this->exists(get_class($this->router));

        $this->router->prefix('', 'Acme\Http\Controllers');

        $this->router->get('/home', 'HomeController@index');

        $this->router->prefix('/v1/auth');

        $this->router->post('/login', 'AuthController@login');

        $this->router->post('/logout', 'AuthController@logout');

        $this->router->prefix('/v1/test');

        $this->router->get('/hello', 'TestController@hello');

        $this->router->get('/test', 'TestController@test');

        $home = $this->router->retrieve('GET', '/home');

        $home = 'Acme\Http\Controllers\HomeController' === $home[2][0];

        $login = $this->router->retrieve('POST', '/v1/auth/login');

        $login = 'Acme\Http\Controllers\AuthController' === $login[2][0];

        $hello = $this->router->retrieve('GET', '/v1/test/hello');

        $hello = 'Acme\Http\Controllers\TestController' === $hello[2][0];

        $this->assertTrue($home && $login && $hello);
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

        $exists = $this->router->has('GET', '/test');

        $this->assertTrue($exists);
    }

    /**
     * Verifies the specified router if it exists.
     *
     * @param  string $router
     * @return void
     */
    protected function exists($router)
    {
        if ($router === 'Rougin\Slytherin\Routing\FastRouteRouter') {
            $exists = class_exists('FastRoute\RouteCollector');

            $message = (string) 'FastRoute is not installed.';

            $exists || $this->markTestSkipped((string) $message);
        }

        if ($router === 'Rougin\Slytherin\Routing\PhrouteRouter') {
            $exists = class_exists('Phroute\Phroute\RouteCollector');

            $message = (string) 'Phroute is not installed.';

            $exists || $this->markTestSkipped((string) $message);
        }
    }
}
