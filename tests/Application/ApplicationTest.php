<?php

namespace Rougin\Slytherin\Test\Application;

use Zend\Stratigility\MiddlewarePipe;

use Rougin\Slytherin\Application;
use Rougin\Slytherin\Component\Collector;
use Rougin\Slytherin\IoC\Vanilla\Container;
use Rougin\Slytherin\Middleware\Stratigility\Middleware;

use PHPUnit_Framework_TestCase;
use Rougin\Slytherin\Test\Fixture\Http\Uri;

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
     * Sets up the application.
     *
     * @return void
     */
    public function setUp()
    {
        if ( ! interface_exists('Interop\Container\ContainerInterface')) {
            $this->markTestSkipped('Container Interop is not installed.');
        }

        if ( ! interface_exists('Psr\Http\Message\ResponseInterface')) {
            $this->markTestSkipped('PSR HTTP Message is not installed.');
        }

        $components = [
            'Rougin\Slytherin\Test\Fixture\Components\DebuggerComponent',
            'Rougin\Slytherin\Test\Fixture\Components\DispatcherComponent',
            'Rougin\Slytherin\Test\Fixture\Components\HttpComponent',
            'Rougin\Slytherin\Test\Fixture\Components\MiddlewareComponent',
            'Rougin\Slytherin\Test\Fixture\Components\SingleComponent',
            'Rougin\Slytherin\Test\Fixture\Components\CollectionComponent',
        ];

        $this->components = Collector::get(new Container, $components, $GLOBALS);
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
        $this->expectOutputString('Hello with response');

        $this->setRequest('GET', '/hello');

        $application = new Application($this->components);

        $application->run();
    }

    /**
     * Tests the run() method with a parameter.
     * 
     * @runInSeparateProcess
     * @return void
     */
    public function testRunMethodWithParameter()
    {
        $this->expectOutputString('Hello');

        $this->setRequest('GET', '/parameter');

        $application = new Application($this->components);

        $application->run();
    }

    /**
     * Tests the run() method with an optional parameter.
     * 
     * @runInSeparateProcess
     * @return void
     */
    public function testRunMethodWithOptionalParameter()
    {
        $this->expectOutputString('Hello');

        $this->setRequest('GET', '/optional');

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
     * Checks if the application runs in the VanillaMiddleware.
     * 
     * @return void
     */
    public function testRunMethodWithMiddleware()
    {
        if ( ! class_exists('Zend\Stratigility\MiddlewarePipe')) {
            $this->markTestSkipped('Zend Stratigility is not installed.');
        }

        $container  = $this->components->getContainer();
        $middleware = 'Rougin\Slytherin\Middleware\MiddlewareInterface';

        $container->add($middleware, new Middleware(new MiddlewarePipe));

        $this->components->setMiddleware($container->get($middleware));

        $this->expectOutputString('Loaded with middleware');

        $this->setRequest('GET', '/middleware');

        $application = new Application($this->components);

        $application->run();
    }

    /**
     * Tests the run() method with a PUT HTTP method.
     * 
     * @runInSeparateProcess
     * @return void
     */
    public function testRunMethodWithPutHttpMethod()
    {
        $this->expectOutputString('Hello from PUT HTTP method');

        $this->setRequest('PUT', '/hello', [ '_method' => 'PUT' ]);

        $application = new Application($this->components);

        $application->run();
    }

    /**
     * Changes the HTTP method and the uri of the request.
     * 
     * @param string $httpMethod
     * @param string $uri
     * @param array  $data
     * @return void
     */
    private function setRequest($httpMethod, $uri, $data = [])
    {
        list($request, $response) = $this->components->getHttp();

        $request = $request->withMethod($httpMethod)->withUri(new Uri($uri));

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

        $this->components->setHttp($request, $response);
    }
}
