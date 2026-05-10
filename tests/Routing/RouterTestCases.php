<?php

namespace Rougin\Slytherin\Routing;

use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RouterTestCases extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Routing\Router
     */
    protected $router;

    /**
     * @var array<integer, array<integer, \Rougin\Slytherin\Middleware\MiddlewareInterface[]|string|string[]>>
     */
    protected $routes = array(array('GET', '/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index'));

    /**
     * @return void
     */
    public function test_passed_if_empty_route_retrieved()
    {
        $this->exists(get_class($this->router));

        // Retrieve a non-existent route ---
        $route = $this->router->retrieve('GET', '/test');
        // ----------------------------------------------

        // Verify the route is null ---
        $this->assertNull($route);
        // ---------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_multiple_prefixes_used()
    {
        $this->exists(get_class($this->router));

        // Set multiple prefixes for different route groups ---
        $this->router->prefix('', 'Acme\Http\Controllers');

        $this->router->get('/home', 'HomeController@index');

        $this->router->prefix('/v1/auth');

        $this->router->post('/login', 'AuthController@login');

        $this->router->post('/logout', 'AuthController@logout');

        $this->router->prefix('/v1/test');

        $this->router->get('/hello', 'TestController@hello');

        $this->router->get('/test', 'TestController@test');
        // ---------------------------------------------------

        // Verify routes resolve with correct class prefixes ---
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
        // ---------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_restful_routes_added()
    {
        $this->exists(get_class($this->router));

        // Define the expected RESTful routes -----------------
        $expect = array();

        $expect[] = new Route('GET', '/', array('Rougin\Slytherin\Fixture\Classes\NewClass', 'index'));
        $expect[] = new Route('GET', '/posts', array('PostsController', 'index'));
        $expect[] = new Route('POST', '/posts', array('PostsController', 'store'));
        $expect[] = new Route('DELETE', '/posts/:id', array('PostsController', 'delete'));
        $expect[] = new Route('GET', '/posts/:id', array('PostsController', 'show'));
        $expect[] = new Route('PATCH', '/posts/:id', array('PostsController', 'update'));
        $expect[] = new Route('PUT', '/posts/:id', array('PostsController', 'update'));
        // ----------------------------------------------------

        // Add the RESTful routes ---------------
        $this->router->restful('posts', 'PostsController');
        // --------------------------------------

        // Verify all expected routes were created ---
        $actual = $this->router->routes();

        $this->assertEquals($expect, $actual);
        // -------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_restful_with_middleware_added()
    {
        $this->exists(get_class($this->router));

        // Define the expected routes with middleware -----------------
        $expect = array();

        $middleware = 'Rougin\Slytherin\Sample\Handlers\Cors';

        $expect[] = new Route('GET', '/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index');
        $expect[] = new Route('GET', '/news', 'NewsController@index', $middleware);
        $expect[] = new Route('POST', '/news', 'NewsController@store', $middleware);
        $expect[] = new Route('DELETE', '/news/:id', 'NewsController@delete', $middleware);
        $expect[] = new Route('GET', '/news/:id', 'NewsController@show', $middleware);
        $expect[] = new Route('PATCH', '/news/:id', 'NewsController@update', $middleware);
        $expect[] = new Route('PUT', '/news/:id', 'NewsController@update', $middleware);
        // -------------------------------------------------------------

        // Add the RESTful routes with middleware -----------
        $this->router->restful('news', 'NewsController', $middleware);
        // --------------------------------------------------

        // Verify the routes include the middleware ---
        $actual = $this->router->routes();

        $this->assertEquals($expect, $actual);
        // ---------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_route_added()
    {
        $this->exists(get_class($this->router));

        // Set the class prefix and add a route ----------
        $this->router->prefix('', 'Rougin\Slytherin\Fixture\Classes');

        $this->router->add('POST', '/store', 'NewClass@store');
        // ------------------------------------------------

        // Verify the route exists ---
        $this->assertTrue($this->router->has('POST', '/store'));
        // --------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_route_added_via_magic()
    {
        $this->exists(get_class($this->router));

        // Add a route using the magic __call method ---
        $this->router->post('/posts', 'PostsController@store');
        // -----------------------------------------------

        // Verify the route exists ---
        $this->assertTrue($this->router->has('POST', '/posts'));
        // --------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_route_added_with_prefix()
    {
        $this->exists(get_class($this->router));

        // Set a URI prefix for the route group ---
        $this->router->prefix('/v1/slytherin');

        $this->router->get('/hello', 'HelloController@hello');
        // ----------------------------------------------

        // Verify the route includes the prefix ---
        $exists = $this->router->has('GET', '/v1/slytherin/hello');

        $this->assertTrue($exists);
        // -----------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_route_exists()
    {
        $this->exists(get_class($this->router));

        // Verify the default route exists ---
        $actual = $this->router->has('GET', '/');

        $this->assertTrue($actual);
        // -----------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_route_retrieved()
    {
        $this->exists(get_class($this->router));

        $expect = array('Rougin\Slytherin\Fixture\Classes\NewClass', 'index');

        // Retrieve the route from the router -------------
        /** @var \Rougin\Slytherin\Routing\RouteInterface */
        $route = $this->router->retrieve('GET', '/');
        // ------------------------------------------------

        // Verify the handler is returned correctly ---
        $actual = $route->getHandler();

        $this->assertEquals($expect, $actual);
        // ---------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_routes_added_via_constructor()
    {
        // Define routes to pass as constructor argument ----
        $expect = array();

        $expect[] = new Route('GET', '/news', 'NewsController@index');
        $expect[] = new Route('POST', '/news', 'NewsController@store');
        // ---------------------------------------------------

        // Create the router with pre-defined routes ---
        $router = new Router($expect);
        // ---------------------------------------------

        // Verify the routes match ---
        $actual = $router->routes();

        $this->assertEquals($expect, $actual);
        // ---------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_routes_merged()
    {
        $this->exists(get_class($this->router));

        // Create a separate router with its own routes ---
        $router = new Router;

        $router->get('/test', 'TestController@test');
        // -----------------------------------------------

        // Merge the routes into the main router ----------
        $this->router->merge($router->routes());
        // ------------------------------------------------

        // Verify the merged route exists ---
        $exists = $this->router->has('GET', '/test');

        $this->assertTrue($exists);
        // ----------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_routes_retrieved()
    {
        $this->exists(get_class($this->router));

        // Define the expected route list ---
        $route = new Route('GET', '/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index');

        $expect = array($route);
        // ---------------------------------

        // Verify the routes are returned correctly ---
        $actual = $this->router->routes();

        $this->assertEquals($expect, $actual);
        // ---------------------------------------------
    }

    /**
     * Verifies the specified router if it exists.
     *
     * @param string $router
     *
     * @return void
     */
    protected function exists($router)
    {
        if ($router === 'Rougin\Slytherin\Routing\FastRouteRouter')
        {
            // @codeCoverageIgnoreStart
            $this->checkIfFastRouteExists();
            // @codeCoverageIgnoreEnd
        }

        if ($router === 'Rougin\Slytherin\Routing\PhrouteRouter')
        {
            // @codeCoverageIgnoreStart
            $this->checkIfPhrouteExists();
            // @codeCoverageIgnoreEnd
        }
    }
}
