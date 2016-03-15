<?php

namespace Rougin\Slytherin\Test;

use Whoops\Run;
use Zend\Diactoros\Uri;
use Relay\RelayBuilder;
use Zend\Diactoros\Response;
use Zend\Stratigility\MiddlewarePipe;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

use Rougin\Slytherin\Components;
use Rougin\Slytherin\Application;
use Rougin\Slytherin\IoC\Container;
use Rougin\Slytherin\Dispatching\Router;
use Rougin\Slytherin\Debug\WhoopsDebugger;
use Rougin\Slytherin\Dispatching\Dispatcher;
use Rougin\Slytherin\Middleware\RelayMiddleware;
use Rougin\Slytherin\Middleware\StratigilityMiddleware;

use PHPUnit_Framework_TestCase;

/**
 * Application Test
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ApplicationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Rougin\Slytherin\Components
     */
    protected $components;

    /**
     * @var array
     */
    protected $routes = [
        [
            'GET',
            '/',
            [
                'Rougin\Slytherin\Test\Fixture\TestClass',
                'index'
            ],
        ],
        [
            'GET',
            '/hello',
            [
                'Rougin\Slytherin\Test\Fixture\TestClassWithResponseInterface',
                'index'
            ]
        ],
        [
            'GET',
            '/middleware',
            [
                'Rougin\Slytherin\Test\Fixture\TestClass',
                'index'
            ],
            'Rougin\Slytherin\Test\Fixture\TestMiddleware',
        ],
    ];

    /**
     * Sets up the application.
     *
     * @return void
     */
    public function setUp()
    {
        $callback = ['GET', '/callback', function () { return 'Hello'; }];

        array_push($this->routes, $callback);

        $container = new Container;
        $components = new Components;

        $dispatcher = 'Rougin\Slytherin\Dispatching\DispatcherInterface';

        $container->add($dispatcher, new Dispatcher(new Router($this->routes)));
        $components->setDispatcher($container->get($dispatcher));

        $request = 'Psr\Http\Message\RequestInterface';
        $response = 'Psr\Http\Message\ResponseInterface';

        $container->add($request, ServerRequestFactory::fromGlobals());
        $container->add($response, new Response);

        $components->setHttp(
            $container->get($request),
            $container->get($response)
        );

        $debugger = 'Rougin\Slytherin\Debug\DebuggerInterface';

        $container->add($debugger, new WhoopsDebugger(new Run));
        $components->setDebugger($container->get($debugger));

        $components->setContainer($container);

        $this->components = $components;
    }

    /**
     * Tests the run() method.
     * 
     * @return void
     */
    public function testRunMethod()
    {
        $this->expectOutputString('Hello');

        $this->setRequest('GET', '/');

        $application = new Application($this->components);

        $application->run();
    }

    /**
     * Tests the run() method with a response as result.
     * 
     * @runInSeparateProcess
     * @return void
     */
    public function testRunMethodWithResponse()
    {
        $this->expectOutputString('Hello');

        $this->setRequest('GET', '/hello');

        $application = new Application($this->components);

        $application->run();
    }

    /**
     * Tests the run() method with a callback as result.
     * 
     * @return void
     */
    public function testRunMethodWithCallback()
    {
        $this->expectOutputString('Hello');

        $this->setRequest('GET', '/callback');

        $application = new Application($this->components);

        $application->run();
    }

    /**
     * Checks if the application runs in the StratigilityMiddleware.
     * 
     * @return void
     */
    public function testRunMethodWithStratigilityMiddleware()
    {
        $container = $this->components->getContainer();
        $middleware = 'Rougin\Slytherin\Middleware\MiddlewareInterface';
        $pipe = new MiddlewarePipe;

        $container->add($middleware, new StratigilityMiddleware($pipe));
        $this->components->setMiddleware($container->get($middleware));

        $this->expectOutputString('Loaded with middleware');

        $this->setRequest('GET', '/middleware');

        $application = new Application($this->components);

        $application->run();
    }

    /**
     * Checks if the application runs in the RelayMiddleware.
     * 
     * @return void
     */
    public function testRunMethodWithRelayMiddleware()
    {
        $container = $this->components->getContainer();
        $middleware = 'Rougin\Slytherin\Middleware\MiddlewareInterface';
        $builder = new RelayBuilder;

        $container->add($middleware, new RelayMiddleware($builder));
        $this->components->setMiddleware($container->get($middleware));

        $this->expectOutputString('Loaded with middleware');

        $this->setRequest('GET', '/middleware');

        $application = new Application($this->components);

        $application->run();
    }

    /**
     * Changes the HTTP method and the uri of the request.
     * 
     * @param string $httpMethod
     * @param string $uri
     * @return void
     */
    private function setRequest($httpMethod, $uri)
    {
        list($request, $response) = $this->components->getHttp();

        $request = $request->withMethod($httpMethod)->withUri(new Uri($uri));

        $this->components->setHttp($request, $response);
    }
}
