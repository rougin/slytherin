<?php

namespace Rougin\Slytherin\Routing;

use Rougin\Slytherin\Testcase;

/**
 * Router Test Cases
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class RouterTestCases extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Routing\Router
     */
    protected $router;

    /**
     * @var array<int, array<int, \Rougin\Slytherin\Middleware\MiddlewareInterface[]|string[]|string>>
     */
    protected $routes = array(array('GET', '/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index'));

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

        $expected = array('Rougin\Slytherin\Fixture\Classes\NewClass', 'index');

        /** @var \Rougin\Slytherin\Routing\RouteInterface */
        $route = $this->router->retrieve('GET', '/');

        $actual = $route->getHandler();

        $this->assertEquals($expected, $actual);
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

        $route = new Route('GET', '/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index');

        $expected = (array) array($route);

        $actual = $this->router->routes();

        $this->assertEquals($expected, $actual);
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

        $expected[] = new Route('GET', '/', array('Rougin\Slytherin\Fixture\Classes\NewClass', 'index'));
        $expected[] = new Route('GET', '/posts', array('PostsController', 'index'));
        $expected[] = new Route('POST', '/posts', array('PostsController', 'store'));
        $expected[] = new Route('DELETE', '/posts/:id', array('PostsController', 'delete'));
        $expected[] = new Route('GET', '/posts/:id', array('PostsController', 'show'));
        $expected[] = new Route('PATCH', '/posts/:id', array('PostsController', 'update'));
        $expected[] = new Route('PUT', '/posts/:id', array('PostsController', 'update'));

        $this->router->restful('posts', 'PostsController');

        $actual = $this->router->routes();

        $this->assertEquals($expected, $actual);
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

        /** @var \Rougin\Slytherin\Routing\RouteInterface */
        $route = $this->router->retrieve('GET', '/home');

        $home = false;

        if (is_array($handler = $route->getHandler()))
        {
            $home = 'Acme\Http\Controllers\HomeController' === $handler[0];
        }

        /** @var \Rougin\Slytherin\Routing\RouteInterface */
        $route = $this->router->retrieve('POST', '/v1/auth/login');

        $login = false;

        if (is_array($handler = $route->getHandler()))
        {
            $login = 'Acme\Http\Controllers\AuthController' === $handler[0];
        }

        /** @var \Rougin\Slytherin\Routing\RouteInterface */
        $route = $this->router->retrieve('GET', '/v1/test/hello');
        
        $hello = false;

        if (is_array($handler = $route->getHandler()))
        {
            $hello = 'Acme\Http\Controllers\TestController' === $handler[0];
        }

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
        if ($router === 'Rougin\Slytherin\Routing\FastRouteRouter')
        {
            $exists = class_exists('FastRoute\RouteCollector');

            $message = 'FastRoute is not installed.';

            if (! $exists) $this->markTestSkipped($message);
        }

        if ($router === 'Rougin\Slytherin\Routing\PhrouteRouter')
        {
            $exists = class_exists('Phroute\Phroute\RouteCollector');

            $message = 'Phroute is not installed.';

            if (! $exists) $this->markTestSkipped($message);
        }
    }
}
