<?php

namespace Rougin\Slytherin\Application;

use Rougin\Slytherin\Http\ServerRequest;
use Rougin\Slytherin\Routing\Router;

/**
 * Application Test Cases
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ApplicationTestCases extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Rougin\Slytherin\Application
     */
    protected $application;

    /**
     * Sets up the application instance.
     *
     * @return void
     */
    public function setUp()
    {
        $this->markTestSkipped('No implementation style defined.');
    }

    /**
     * Tests Application::handle.
     *
     * @return void
     */
    public function testHandleMethod()
    {
        $request = $this->request('GET', '/store');

        $expected = (string) 'Store';

        $response = $this->application->handle($request);

        $result = (string) $response->getBody();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests Application::handle with a callback as result.
     *
     * @return void
     */
    public function testHandleMethodWithCallback()
    {
        $request = $this->request('GET', '/callback');

        $expected = 'Hello, this is a callback';

        $response = $this->application->handle($request);

        $result = (string) $response->getBody();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests Application::handle with a HTTP 401 response as result.
     *
     * @return void
     */
    public function testHandleMethodWithHttp401Response()
    {
        $request = $this->request('GET', '/error');

        $expected = 'Hello with error response';

        $response = $this->application->handle($request);

        $result = (string) $response->getBody();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests Application::handle with a callback middleware as result.
     *
     * @return void
     */
    public function testHandleMethodWithMiddleware()
    {
        $interface = 'Interop\Http\ServerMiddleware\MiddlewareInterface';

        interface_exists($interface) || $this->markTestSkipped('PSR-15 is not installed.');

        $request = $this->request('GET', '/middleware');

        $expected = (string) 'Loaded with middleware';

        $response = $this->application->handle($request);

        $result = (string) $response->getBody();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests Application::handle with an optional parameter as result.
     *
     * @return void
     */
    public function testHandleMethodWithOptionalParameter()
    {
        $request = $this->request('GET', '/optional');

        $expected = (string) 'Hello';

        $response = $this->application->handle($request);

        $result = (string) $response->getBody();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests Application::handle with a parameter as result.
     *
     * @return void
     */
    public function testHandleMethodWithParameter()
    {
        $request = $this->request('GET', '/parameter');

        $expected = 'Hello';

        $response = $this->application->handle($request);

        $result = (string) $response->getBody();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests Application::handle with a PUT HTTP method.
     *
     * @return void
     */
    public function testHandleMethodWithPutHttpMethod()
    {
        $request = $this->request('PUT', '/hello');

        $expected = 'Hello from PUT HTTP method';

        $response = $this->application->handle($request);

        $result = (string) $response->getBody();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests Application::handle with a response as result.
     *
     * @return void
     */
    public function testHandleMethodWithResponse()
    {
        $request = $this->request('GET', '/response');

        $expected = 'Hello with response';

        $response = $this->application->handle($request);

        $result = (string) $response->getBody();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests Application::handle with an updated server request.
     *
     * @return void
     */
    public function testHandleMethodWithServerRequest()
    {
        $data = array('test' => 'Hello with request');

        $request = $this->request('GET', '/request', $data);

        $expected = 'Hello with request';

        $response = $this->application->handle($request);

        $result = (string) $response->getBody();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests the handle() method with a type hinted parameter as result.
     *
     * @return void
     */
    public function testHandleMethodWithTypehintedParameter()
    {
        $interface = 'Rougin\Slytherin\Routing\DispatcherInterface';

        $dispatcher = Application::container()->get($interface);

        // TODO: Implement resolving of type hinted parameters from container to PhrouteResolver
        if (is_a($dispatcher, 'Rougin\Slytherin\Routing\PhrouteDispatcher')) {
            $this->markTestSkipped('Resolving type hinted parameters are not yet implemented in Phroute.');
        }

        $request = $this->request('GET', '/typehint/202');

        $expected = (integer) 202;

        $response = $this->application->handle($request);

        $result = $response->getStatusCode();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests Application::run.
     *
     * @return void
     * @runInSeparateProcess
     */
    public function testRunMethod()
    {
        $this->expectOutputString('Hello');

        $this->application->run();
    }

    /**
     * Prepares the HTTP method and the URI of the request.
     *
     * @param  string $method
     * @param  string $uri
     * @param  array  $data
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    protected function request($method, $uri, $data = array(), $server = array())
    {
        $server['REQUEST_METHOD'] = $method;
        $server['REQUEST_URI'] = $uri;
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $request = new ServerRequest($server);

        switch ($method) {
            case 'GET':
                $request = $request->withQueryParams($data);

                break;
            case 'POST':
            case 'PUT':
            case 'DELETE':
                $request = $request->withParsedBody($data);

                break;
        }

        // TODO: Remove this one. This was added because of Phroute will resolve it automatically. :(
        if (method_exists(Application::container(), 'set')) {
            $container = Application::container()->set(Application::SERVER_REQUEST, $request);

            $this->application = new Application($container);
        }

        return $request;
    }

    /**
     * Returns a listing of routes for testing.
     *
     * @return \Rougin\Slytherin\Routing\RoutingInterface
     */
    protected function router()
    {
        $middleware = 'Rougin\Slytherin\Fixture\Middlewares\LastMiddleware';

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

        $router->get('/callback', function () {
            return 'Hello, this is a callback';
        });

        return $router;
    }
}
