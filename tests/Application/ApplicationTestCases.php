<?php

namespace Rougin\Slytherin\Application;

/**
 * Application Test Cases
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ApplicationTestCases extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Rougin\Slytherin\Application
     */
    protected $application;

    /**
     * Prepares the application instance.
     *
     * @return void
     */
    public function setUp()
    {
        $this->markTestSkipped('No implementation style defined.');
    }

    /**
     * Tests Application::run.
     *
     * @return void
     */
    public function testRunMethod()
    {
        $this->expectOutputString('Hello');

        $this->application->run();
    }

    /**
     * Tests Application::handle.
     *
     * @return void
     */
    public function testHandleMethod()
    {
        $request = $this->request('GET', '/store');

        $result = $this->application->handle($request);

        $this->assertEquals('Store', (string) $result->getBody());
    }

    /**
     * Tests the handle() method with a response as result.
     *
     * @return void
     */
    public function testHandleMethodWithResponse()
    {
        $request = $this->request('GET', '/response');

        $result = $this->application->handle($request);

        $this->assertEquals('Hello with response', (string) $result->getBody());
    }

    /**
     * Tests the handle() method with a HTTP 401 response as result.
     *
     * @return void
     */
    public function testHandleMethodWithHttp401Response()
    {
        $request = $this->request('GET', '/error');

        $result = $this->application->handle($request);

        $this->assertEquals('Hello with error response', (string) $result->getBody());
    }

    /**
     * Tests the handle() method with an updated server request.
     *
     * @return void
     */
    public function testHandleMethodWithServerRequest()
    {
        $request = $this->request('GET', '/request', array('test' => 'Hello with request'));

        $result = $this->application->handle($request);

        $this->assertEquals('Hello with request', (string) $result->getBody());
    }

    /**
     * Tests the handle() method with a parameter as result.
     *
     * @return void
     */
    public function testHandleMethodWithParameter()
    {
        $request = $this->request('GET', '/parameter');

        $result = $this->application->handle($request);

        $this->assertEquals('Hello', (string) $result->getBody());
    }

    /**
     * Tests the handle() method with an optional parameter as result.
     *
     * @return void
     */
    public function testHandleMethodWithOptionalParameter()
    {
        $request = $this->request('GET', '/optional');

        $result = $this->application->handle($request);

        $this->assertEquals('Hello', (string) $result->getBody());
    }

    /**
     * Tests the handle() method with a type hinted parameter as result.
     *
     * @return void
     */
    public function testHandleMethodWithTypehintedParameter()
    {
        $interface = 'Rougin\Slytherin\Routing\DispatcherInterface';

        $dispatcher = \Rougin\Slytherin\Application::container()->get($interface);

        // TODO: Implement resolving of type hinted parameters from container to PhrouteResolver
        if (is_a($dispatcher, 'Rougin\Slytherin\Routing\PhrouteDispatcher')) {
            $this->markTestSkipped('Resolving type hinted parameters are not yet implemented in Phroute.');
        }

        $request = $this->request('GET', '/typehint/202');

        $result = $this->application->handle($request);

        $this->assertEquals(202, $result->getStatusCode());
    }

    /**
     * Tests the handle() method with a callback as result.
     *
     * @return void
     */
    public function testHandleMethodWithCallback()
    {
        $request = $this->request('GET', '/callback');

        $result = $this->application->handle($request);

        $this->assertEquals('Hello, this is a callback', (string) $result->getBody());
    }

    /**
     * Tests the handle() method with a callback as result.
     *
     * @return void
     */
    public function testHandleMethodWithMiddleware()
    {
        $request = $this->request('GET', '/middleware');

        $result = $this->application->handle($request);

        $this->assertEquals('Loaded with middleware', (string) $result->getBody());
    }

    /**
     * Tests the handle() method with a PUT HTTP method.
     *
     * @return void
     */
    public function testHandleMethodWithPutHttpMethod()
    {
        $request = $this->request('PUT', '/hello');

        $result = $this->application->handle($request);

        $this->assertEquals('Hello from PUT HTTP method', (string) $result->getBody());
    }

    /**
     * Tests the handle() method with a OPTIONS HTTP method.
     *
     * @return void
     */
    public function testHandleMethodWithOptionsHttpMethod()
    {
        $container = \Rougin\Slytherin\Application::container();

        $dispatcher = $container->get('Rougin\Slytherin\Routing\DispatcherInterface');

        // TODO: Implement conversion of OPTIONS HTTP method to PUT/PATCH/DELETE
        if (is_a($dispatcher, 'Rougin\Slytherin\Routing\PhrouteDispatcher')) {
            $this->markTestSkipped('OPTIONS HTTP method to PUT/DELETE are not yet implemented in Phroute.');
        }

        $server = array('HTTP_ACCESS_CONTROL_REQUEST_METHOD' => 'PUT');

        $request = $this->request('OPTIONS', '/cors', array(), $server);

        $result = $this->application->handle($request);

        $this->assertEquals('Hello from PUT HTTP method', (string) $result->getBody());
    }

    /**
     * Prepares the HTTP method and the URI of the request.
     *
     * @param  string $httpMethod
     * @param  string $uriEndpoint
     * @param  array  $data
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    protected function request($httpMethod, $uriEndpoint, $data = array(), $server = array())
    {
        $server['REQUEST_METHOD'] = $httpMethod;
        $server['REQUEST_URI'] = $uriEndpoint;
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $request = new \Rougin\Slytherin\Http\ServerRequest($server);

        switch ($httpMethod) {
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
        $cors = array();

        array($cors, 'Rougin\Slytherin\Fixture\Middlewares\CorsMiddleware');
        array($cors, 'Rougin\Slytherin\Fixture\Middlewares\BodyParametersMiddleware');

        $last = 'Rougin\Slytherin\Fixture\Middlewares\LastMiddleware';

        $router = new \Rougin\Slytherin\Routing\Router;

        $router->get('/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index');
        $router->get('/store', 'Rougin\Slytherin\Fixture\Classes\NewClass@store');
        $router->get('/request', 'Rougin\Slytherin\Fixture\Classes\WithServerRequestInterface@index');
        $router->get('/response', 'Rougin\Slytherin\Fixture\Classes\WithResponseInterface@index');
        $router->get('/error', 'Rougin\Slytherin\Fixture\Classes\WithResponseInterface@error');
        $router->get('/parameter', 'Rougin\Slytherin\Fixture\Classes\WithParameter@index');
        $router->get('/optional', 'Rougin\Slytherin\Fixture\Classes\WithOptionalParameter@index');
        $router->get('/middleware', 'Rougin\Slytherin\Fixture\Classes\NewClass@index', $last);
        $router->put('/hello', 'Rougin\Slytherin\Fixture\Classes\WithPutHttpMethod@index');
        $router->get('/typehint/:code', 'Rougin\Slytherin\Fixture\Classes\WithResponseInterface@typehint');
        $router->put('/cors', 'Rougin\Slytherin\Fixture\Classes\WithPutHttpMethod@index', $cors);

        $router->get('/callback', function () {
            return 'Hello, this is a callback';
        });

        return $router;
    }
}
