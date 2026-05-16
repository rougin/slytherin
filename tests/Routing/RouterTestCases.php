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
    protected $self;

    /**
     * @var array<integer, array<integer, \Rougin\Slytherin\Middleware\MiddlewareInterface[]|string|string[]>>
     */
    protected $routes = array(

        array('GET', '/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index'),

    );

    /**
     * @return void
     *
     * @deprecated since ~0.9, calls deprecated "retrieve()"; use "find()" instead.
     */
    public function test_passed_if_empty_route_found()
    {
        $this->exists(get_class($this->self));

        // Retrieve a non-existent route --------------
        $route = $this->self->retrieve('GET', '/test');
        // --------------------------------------------

        // Verify the route is null ---
        $this->assertNull($route);
        // ----------------------------
    }

    /**
     * @return void
     *
     * @deprecated since ~0.9, calls deprecated "retrieve()"; use "find()" instead.
     */
    public function test_passed_if_multiple_prefixes_used()
    {
        $this->exists(get_class($this->self));

        // Set multiple prefixes for different route groups ---
        $this->self->prefix('', 'Acme\Http\Controllers');

        $this->self->get('/home', 'HomeController@index');

        $this->self->prefix('/v1/auth');

        $this->self->post('/login', 'AuthController@login');

        $this->self->post('/logout', 'AuthController@logout');

        $this->self->prefix('/v1/test');

        $this->self->get('/hello', 'TestController@hello');

        $this->self->get('/test', 'TestController@test');
        // ----------------------------------------------------

        // Verify routes resolve with correct class prefixes ---
        $route = $this->self->retrieve('GET', '/home');

        $home = false;

        if ($route && is_array($handler = $route->getHandler()))
        {


            $class = 'Acme\Http\Controllers\HomeController';

            $home = $class === $handler[0];
        }

        $route = $this->self->retrieve('POST', '/v1/auth/login');

        $login = false;

        if ($route && is_array($handler = $route->getHandler()))
        {
            $class = 'Acme\Http\Controllers\AuthController';

            $login = $class === $handler[0];
        }

        $route = $this->self->retrieve('GET', '/v1/test/hello');

        $hello = false;

        if ($route && is_array($handler = $route->getHandler()))
        {
            $class = 'Acme\Http\Controllers\TestController';

            $hello = $class === $handler[0];
        }

        $this->assertTrue($home && $login && $hello);
        // ------------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_restful_routes_added()
    {
        $this->exists(get_class($this->self));

        // Define the expected RESTful routes --------------------------------
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $expect = array(new Route('GET', '/', array($class, 'index')));

        $ctrl = 'PostsController';

        $expect[] = new Route('GET', '/posts', array($ctrl, 'index'));
        $expect[] = new Route('POST', '/posts', array($ctrl, 'store'));
        $expect[] = new Route('DELETE', '/posts/:id', array($ctrl, 'delete'));
        $expect[] = new Route('GET', '/posts/:id', array($ctrl, 'show'));
        $expect[] = new Route('PATCH', '/posts/:id', array($ctrl, 'update'));
        $expect[] = new Route('PUT', '/posts/:id', array($ctrl, 'update'));
        // -------------------------------------------------------------------

        $this->self->restful('posts', $ctrl);

        // Verify all expected routes were created ---
        $actual = $this->self->routes();

        $this->assertEquals($expect, $actual);
        // -------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_restful_with_middleware_added()
    {
        $this->exists(get_class($this->self));

        // Define the expected routes with middleware -------------------------------
        $route = 'Rougin\Slytherin\Fixture\Classes\NewClass@index';

        $expect = array(new Route('GET', '/', $route));

        $middleware = 'Rougin\Slytherin\Sample\Handlers\Cors';

        $ctrl = 'NewsController';

        $expect[] = new Route('GET', '/news', $ctrl . '@index', $middleware);
        $expect[] = new Route('POST', '/news', $ctrl . '@store', $middleware);
        $expect[] = new Route('DELETE', '/news/:id', $ctrl . '@delete', $middleware);
        $expect[] = new Route('GET', '/news/:id', $ctrl . '@show', $middleware);
        $expect[] = new Route('PATCH', '/news/:id', $ctrl . '@update', $middleware);
        $expect[] = new Route('PUT', '/news/:id', $ctrl . '@update', $middleware);
        // --------------------------------------------------------------------------

        $this->self->restful('news', $ctrl, $middleware);

        // Verify the routes include the middleware ---
        $actual = $this->self->routes();

        $this->assertEquals($expect, $actual);
        // --------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_route_added()
    {
        $this->exists(get_class($this->self));

        // Set the class prefix and add a route -------------
        $class = 'Rougin\Slytherin\Fixture\Classes';

        $this->self->prefix('', $class);

        $this->self->add('POST', '/store', 'NewClass@store');
        // --------------------------------------------------

        $this->assertTrue($this->self->has('POST', '/store'));
    }

    /**
     * @return void
     */
    public function test_passed_if_route_added_via_magic()
    {


        $this->exists(get_class($this->self));

        $this->self->post('/posts', 'PostsController@store');

        $this->assertTrue($this->self->has('POST', '/posts'));
    }

    /**
     * @return void
     */
    public function test_passed_if_route_added_with_prefix()
    {
        $this->exists(get_class($this->self));

        // Set a URI prefix for the route group ---
        $this->self->prefix('/v1/slytherin');

        $route = 'HelloController@hello';

        $this->self->get('/hello', $route);
        // ----------------------------------------

        // Verify the route includes the prefix ---
        $uri = '/v1/slytherin/hello';

        $exists = $this->self->has('GET', $uri);

        $this->assertTrue($exists);
        // ----------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_route_exists()
    {
        $this->exists(get_class($this->self));

        // Verify the default route exists ----
        $actual = $this->self->has('GET', '/');

        $this->assertTrue($actual);
        // ------------------------------------
    }

    /**
     * @return void
     *
     * @deprecated since ~0.9, calls deprecated "retrieve()"; use "find()" instead.
     */
    public function test_passed_if_route_found()
    {
        $this->exists(get_class($this->self));

        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $expect = array($class, 'index');

        // Retrieve the route from the router --------------
        /** @var \Rougin\Slytherin\Routing\RouteInterface */
        $route = $this->self->retrieve('GET', '/');
        // -------------------------------------------------

        // Verify the handler is returned correctly ---
        $actual = $route->getHandler();

        $this->assertEquals($expect, $actual);
        // --------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_routes_added_via_constructor()
    {
        // Define routes to pass as constructor argument --------
        $ctrl = 'NewsController';

        $expect = array();

        $expect[] = new Route('GET', '/news', $ctrl . '@index');
        $expect[] = new Route('POST', '/news', $ctrl . '@store');

        $router = new Router($expect);
        // ------------------------------------------------------

        // Verify the routes match -----------
        $actual = $router->routes();

        $this->assertEquals($expect, $actual);
        // -----------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_routes_merged()
    {
        $this->exists(get_class($this->self));

        // Create a separate router with its own routes ---
        $router = new Router;

        $router->get('/test', 'TestController@test');
        // ------------------------------------------------

        // Merge the routes into the main router ---
        $this->self->merge($router->routes());
        // -----------------------------------------

        // Verify the merged route exists ---------
        $exists = $this->self->has('GET', '/test');

        $this->assertTrue($exists);
        // ----------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_routes_found()
    {
        $this->exists(get_class($this->self));

        $route = 'Rougin\Slytherin\Fixture\Classes\NewClass@index';

        // Define the expected route list -----
        $route = new Route('GET', '/', $route);

        $expect = array($route);
        // ------------------------------------

        // Verify the routes are returned correctly ---
        $actual = $this->self->routes();

        $this->assertEquals($expect, $actual);
        // --------------------------------------------
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
            $this->checkIfFastRouteExists();
        }

        if ($router === 'Rougin\Slytherin\Routing\PhrouteRouter')
        {
            $this->checkIfPhrouteExists();
        }
    }
}
