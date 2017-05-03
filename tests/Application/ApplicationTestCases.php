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
     * @runInSeparateProcess
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
     * Tests the handle() method with a HTTP 401 response as result.
     *
     * @return void
     */
    public function testHandleMethodWithHttp401Response()
    {
        $request = $this->request('GET', '/error');

        $result = $this->application->handle($request);

        $this->assertEquals(401, $result->getStatusCode());
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
     * @runInSeparateProcess
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
     * Prepares the HTTP method and the URI of the request.
     *
     * @param  string $httpMethod
     * @param  string $uriEndpoint
     * @param  array  $data
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    protected function request($httpMethod, $uriEndpoint, $data = array())
    {
        $server = array();

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

        $router = new \Rougin\Slytherin\Routing\Router;

        $router->get('/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index');
        $router->get('/store', 'Rougin\Slytherin\Fixture\Classes\NewClass@store');
        $router->get('/request', 'Rougin\Slytherin\Fixture\Classes\WithServerRequestInterface@index');
        $router->get('/response', 'Rougin\Slytherin\Fixture\Classes\WithResponseInterface@index');
        $router->get('/error', 'Rougin\Slytherin\Fixture\Classes\WithResponseInterface@error');
        $router->get('/parameter', 'Rougin\Slytherin\Fixture\Classes\WithParameter@index');
        $router->get('/optional', 'Rougin\Slytherin\Fixture\Classes\WithOptionalParameter@index');
        $router->get('/middleware', 'Rougin\Slytherin\Fixture\Classes\NewClass@index', $middleware);
        $router->put('/hello', 'Rougin\Slytherin\Fixture\Classes\WithPutHttpMethod@index');

        $router->get('/callback', function () {
            return 'Hello, this is a callback';
        });

        return $router;
    }
}
