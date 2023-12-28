<?php declare(strict_types = 1);

namespace Rougin\Slytherin\Application;

use Rougin\Slytherin\Http\ServerRequest;
use Rougin\Slytherin\Routing\Router;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ApplicationTestCases extends Testcase
{
    /**
     * @var \Rougin\Slytherin\System
     */
    protected $system;

    /**
     * @return void
     */
    protected function doSetUp()
    {
        // @codeCoverageIgnoreStart
        $this->markTestSkipped('No implementation style defined.');
        // @codeCoverageIgnoreEnd
    }

    /**
     * @return void
     */
    public function test_response_with_simple_route()
    {
        $request = $this->request('GET', '/store');

        $expected = (string) 'Store';

        $response = $this->system->handle($request);

        $actual = (string) $response->getBody();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_response_with_callback()
    {
        $request = $this->request('GET', '/callback');

        $expected = 'Hello, this is a callback';

        $response = $this->system->handle($request);

        $actual = (string) $response->getBody();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_response_with_http_401_error()
    {
        $request = $this->request('GET', '/error');

        $expected = 'Hello with error response';

        $response = $this->system->handle($request);

        $actual = (string) $response->getBody();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_response_with_middleware()
    {
        $request = $this->request('GET', '/middleware');

        $expected = (string) 'Loaded with middleware';

        $response = $this->system->handle($request);

        $actual = (string) $response->getBody();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_response_with_optional_parameter()
    {
        $request = $this->request('GET', '/optional');

        $expected = (string) 'Hello';

        $response = $this->system->handle($request);

        $actual = (string) $response->getBody();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_response_with_required_parameter()
    {
        $request = $this->request('GET', '/parameter');

        $expected = 'Hello';

        $response = $this->system->handle($request);

        $actual = (string) $response->getBody();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_response_with_put_http_method()
    {
        $request = $this->request('PUT', '/hello');

        $expected = 'Hello from PUT HTTP method';

        $response = $this->system->handle($request);

        $actual = (string) $response->getBody();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_response_using_response_interface()
    {
        $request = $this->request('GET', '/response');

        $expected = 'Hello with response';

        $response = $this->system->handle($request);

        $actual = (string) $response->getBody();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_response_with_mutated_server_request()
    {
        $data = array('test' => 'Hello with request');

        $request = $this->request('GET', '/request', $data);

        $expected = 'Hello with request';

        $response = $this->system->handle($request);

        $actual = (string) $response->getBody();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_response_with_a_typehinted_parameter()
    {
        $request = $this->request('GET', '/typehint/202');

        $expected = (integer) 202;

        $response = $this->system->handle($request);

        $actual = $response->getStatusCode();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_response_in_run_method()
    {
        $this->expectOutputString('Hello');

        $this->system->run();
    }

    /**
     * Prepares the HTTP method and the URI of the request.
     *
     * @param  string                $method
     * @param  string                $uri
     * @param  array<string, string> $data
     * @param  array<string, string> $server
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
