<?php

namespace Rougin\Slytherin\Test;

use Whoops\Run;
use Zend\Diactoros\Uri;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

use Rougin\Slytherin\Components;
use Rougin\Slytherin\Application;
use Rougin\Slytherin\IoC\Container;
use Rougin\Slytherin\Dispatching\Router;
use Rougin\Slytherin\Debug\WhoopsDebugger;
use Rougin\Slytherin\Dispatching\Dispatcher;

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
    ];

    /**
     * Sets up the application.
     *
     * @return void
     */
    public function setUp()
    {
        $this->routes[] = [
            'GET',
            '/callback',
            function () {
                return 'Hello';
            }
        ];

        $container = new Container;
        $components = new Components;

        $container->add(
            'Rougin\Slytherin\Dispatching\DispatcherInterface',
            new Dispatcher(new Router($this->routes))
        );

        $components->setDispatcher(
            $container->get('Rougin\Slytherin\Dispatching\DispatcherInterface')
        );

        $container->add(
            'Psr\Http\Message\RequestInterface',
            ServerRequestFactory::fromGlobals()
        );

        $container->add('Psr\Http\Message\ResponseInterface', new Response);

        $components->setHttp(
            $container->get('Psr\Http\Message\RequestInterface'),
            $container->get('Psr\Http\Message\ResponseInterface')
        );

        $container->add(
            'Rougin\Slytherin\Debug\DebuggerInterface',
            new WhoopsDebugger(new Run)
        );

        $components->setDebugger(
            $container->get('Rougin\Slytherin\Debug\DebuggerInterface')
        );

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
