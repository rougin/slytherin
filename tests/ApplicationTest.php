<?php

namespace Rougin\Slytherin;

/**
 * Application Test Class
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Application
     */
    protected $application;

    /**
     * Prepares the application instance.
     *
     * @return void
     */
    public function setUp()
    {
        $integrations = array();

        array_push($integrations, 'Rougin\Slytherin\Debug\ErrorHandlerIntegration');
        array_push($integrations, 'Rougin\Slytherin\Http\HttpIntegration');
        array_push($integrations, 'Rougin\Slytherin\Integration\ConfigurationIntegration');
        array_push($integrations, 'Rougin\Slytherin\Middleware\MiddlewareIntegration');
        array_push($integrations, 'Rougin\Slytherin\Routing\RoutingIntegration');
        array_push($integrations, 'Rougin\Slytherin\Template\RendererIntegration');

        $router = new Routing\Router;

        $router->get('/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index');
        $router->get('/store', 'Rougin\Slytherin\Fixture\Classes\NewClass@store');
        $router->get('/response', 'Rougin\Slytherin\Fixture\Classes\WithResponseInterface@index');
        $router->get('/parameter', 'Rougin\Slytherin\Fixture\Classes\WithParameter@index');
        $router->get('/optional', 'Rougin\Slytherin\Fixture\Classes\WithOptionalParameter@index');
        $router->get('/middleware', 'Rougin\Slytherin\Fixture\Classes\NewClass@index', 'Rougin\Slytherin\Fixture\Middlewares\LastMiddleware');

        $router->put('/hello', 'Rougin\Slytherin\Fixture\Classes\WithPutHttpMethod@index');

        $router->get('/callback', function () {
            return 'Hello, this is a callback';
        });

        $config = new Integration\Configuration;

        $config->set('app.router', $router);

        $app = new Application;

        $this->application = $app->integrate($integrations, $config);
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
        $request = $this->setServerRequest('GET', '/store');

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
        $request = $this->setServerRequest('GET', '/response');

        $result = $this->application->handle($request);

        $this->assertEquals('Hello with response', (string) $result->getBody());
    }

    /**
     * Tests the handle() method with a parameter as result.
     *
     * @return void
     */
    public function testHandleMethodWithParameter()
    {
        $request = $this->setServerRequest('GET', '/parameter');

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
        $request = $this->setServerRequest('GET', '/optional');

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
        $request = $this->setServerRequest('GET', '/callback');

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
        $request = $this->setServerRequest('GET', '/middleware');

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
        $request = $this->setServerRequest('PUT', '/hello');

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
    protected function setServerRequest($httpMethod, $uriEndpoint, $data = array())
    {
        $server = array();

        $server['REQUEST_METHOD'] = $httpMethod;
        $server['REQUEST_URI'] = $uriEndpoint;
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $request = new Http\ServerRequest($server);

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
}
