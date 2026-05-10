<?php

namespace Rougin\Slytherin\Application;

use Rougin\Slytherin\Http\ServerRequest;
use Rougin\Slytherin\Routing\Router;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ApplicationTestCases extends Testcase
{
    /**
     * @var string
     */
    protected $type = '';

    /**
     * @var \Rougin\Slytherin\System
     */
    protected $system;

    /**
     * @return void
     */
    public function test_passed_if_callback_responded()
    {
        // Handle a request to the callback route ----
        $request = $this->request('GET', '/callback');

        $expect = 'Hello, this is a callback';

        $response = $this->system->handle($request);

        $actual = $response->getBody();
        // ------------------------------------------

        // Verify the callback returned the correct text ---
        $this->assertEquals($expect, $actual);
        // -------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_error_response_handled()
    {
        // Handle a request to the error route ---
        $request = $this->request('GET', '/error');

        $expect = 'Hello with error response';

        $response = $this->system->handle($request);

        $actual = $response->getBody();
        // --------------------------------------

        // Verify the error handler returned the correct body ---
        $this->assertEquals($expect, $actual);
        // ------------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_middleware_responded()
    {
        // Handle a request to a middleware-protected route ---
        $request = $this->request('GET', '/middleware');

        $expect = 'Loaded with middleware';

        $response = $this->system->handle($request);

        $actual = $response->getBody();
        // ---------------------------------------------------

        // Verify the middleware modified the response correctly ---
        $this->assertEquals($expect, $actual);
        // ---------------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_mutated_request_handled()
    {
        if ($this->type === 'auryn')
        {
            $this->markTestSkipped('Dynamic request must be shared again if using Auryn.');
        }

        // Handle a request with mutated server request data ---
        $data = array('test' => 'Hello with request');

        $request = $this->request('GET', '/request', $data);

        $expect = 'Hello with request';

        $response = $this->system->handle($request);

        $actual = $response->getBody();
        // -----------------------------------------------------

        // Verify the request data was processed correctly ---
        $this->assertEquals($expect, $actual);
        // ---------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_optional_parameter_handled()
    {
        // Handle a request with an optional route parameter ---
        $request = $this->request('GET', '/optional');

        $expect = 'Hello';

        $response = $this->system->handle($request);

        $actual = $response->getBody();
        // -----------------------------------------------------

        // Verify the optional parameter was handled correctly ---
        $this->assertEquals($expect, $actual);
        // -------------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_put_method_responded()
    {
        // Handle a request using the PUT HTTP method ---
        $request = $this->request('PUT', '/hello');

        $expect = 'Hello from PUT HTTP method';

        $response = $this->system->handle($request);

        $actual = $response->getBody();
        // ----------------------------------------------

        // Verify the PUT route was handled correctly ---
        $this->assertEquals($expect, $actual);
        // ----------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_required_parameter_handled()
    {
        // Handle a request with a required route parameter ---
        $request = $this->request('GET', '/parameter');

        $expect = 'Hello';

        $response = $this->system->handle($request);

        $actual = $response->getBody();
        // ----------------------------------------------------

        // Verify the required parameter was resolved correctly ---
        $this->assertEquals($expect, $actual);
        // --------------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_response_interface_used()
    {
        // Handle a request to the response interface route ---
        $request = $this->request('GET', '/response');

        $expect = 'Hello with response';

        $response = $this->system->handle($request);

        $actual = $response->getBody();
        // ---------------------------------------------------

        // Verify the response interface was handled correctly ---
        $this->assertEquals($expect, $actual);
        // -------------------------------------------------------
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_run_method_responded()
    {
        $expect = 'Hello';
        $this->expectOutputString($expect);

        // Execute the application via the run method ---
        $this->system->run();
        // -----------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_simple_route_responded()
    {
        // Handle a request to the store route ---
        $request = $this->request('GET', '/store');

        $expect = 'Store';

        $response = $this->system->handle($request);

        $actual = $response->getBody();
        // ---------------------------------------

        // Verify the response body matches the expected text ---
        $this->assertEquals($expect, $actual);
        // ------------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_typehinted_parameter_handled()
    {
        // Handle a request with a typehinted route parameter ---
        $request = $this->request('GET', '/typehint/202');

        $expect = 202;

        $response = $this->system->handle($request);

        $actual = $response->getStatusCode();
        // -----------------------------------------------------

        // Verify the typehinted parameter changed the status code ---
        $this->assertEquals($expect, $actual);
        // -----------------------------------------------------------
    }

    /**
     * @return void
     *
     * @codeCoverageIgnore
     */
    protected function doSetUp()
    {
        $this->markTestSkipped('No implementation defined.');
    }

    /**
     * Prepares the HTTP method and the URI of the request.
     *
     * @param string                $method
     * @param string                $uri
     * @param array<string, string> $data
     * @param array<string, string> $server
     *
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    protected function request($method, $uri, $data = array(), $server = array())
    {
        $server['REQUEST_METHOD'] = $method;
        $server['REQUEST_URI'] = $uri;
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $request = new ServerRequest($server);

        if ($method === 'GET')
        {
            $request = $request->withQueryParams($data);
        }

        if (in_array($method, array('POST', 'PUT', 'DELETE')))
        {
            $request = $request->withParsedBody($data);
        }

        return $request;
    }

    /**
     * Returns a listing of routes for testing.
     *
     * @return \Rougin\Slytherin\Routing\RouterInterface
     */
    protected function router()
    {
        $middleware = 'Rougin\Slytherin\Fixture\Middlewares\FinalMiddleware';

        $router = new Router;

        $router->prefix('', 'Rougin\Slytherin\Fixture\Classes');

        $router->get('/', 'NewClass@index');

        $router->get('/store', 'NewClass@store');

        $router->get('/request', 'WithServerRequestInterface@index');

        $router->get('/response', 'WithResponseInterface@index');

        $router->get('/error', 'WithResponseInterface@error');

        $router->get('/parameter', 'WithParameter@index');

        $router->get('/optional', 'WithOptionalParameter@index');

        $router->get('/middleware', 'NewClass@index', $middleware);

        $router->put('/hello', 'WithPutHttpMethod@index');

        $router->get('/typehint/:code', 'WithResponseInterface@typehint');

        $router->get('/callback', function ()
        {
            return 'Hello, this is a callback';
        });

        return $router;
    }
}
