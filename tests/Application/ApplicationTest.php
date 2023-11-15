<?php

namespace Rougin\Slytherin\Application;

/**
 * Application Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Rougin\Slytherin\ComponentCollection
     */
    protected $components;

    /**
     * Sets up the application.
     *
     * @return void
     */
    public function setUp()
    {
        if (! interface_exists('Psr\Container\ContainerInterface')) {
            $this->markTestSkipped('Container Interop is not installed.');
        }

        if (! interface_exists('Psr\Http\Message\ResponseInterface')) {
            $this->markTestSkipped('PSR-7 HTTP Message is not installed.');
        }

        $components = array(
            'Rougin\Slytherin\Fixture\Components\CollectionComponent',
            'Rougin\Slytherin\Fixture\Components\DebuggerComponent',
            'Rougin\Slytherin\Fixture\Components\DispatcherComponent',
            'Rougin\Slytherin\Fixture\Components\HttpComponent',
            'Rougin\Slytherin\Fixture\Components\SingleComponent',
        );

        if (class_exists('Zend\Stratigility\MiddlewarePipe')) {
            $components[] = 'Rougin\Slytherin\Fixture\Components\MiddlewareComponent';
        }

        $container = new \Rougin\Slytherin\IoC\Vanilla\Container;

        $components = \Rougin\Slytherin\Component\Collector::get($container, $components, $GLOBALS);

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

        $this->runApplication('GET', '/')->run();
    }

    /**
     * Tests the run() method with a response as result.
     *
     * @return void
     */
    public function testRunMethodWithResponse()
    {
        $this->expectOutputString('Hello with response');

        $this->runApplication('GET', '/hello')->run();
    }

    /**
     * Tests the run() method with a parameter.
     *
     * @return void
     */
    public function testRunMethodWithParameter()
    {
        $this->expectOutputString('Hello');

        $this->runApplication('GET', '/parameter')->run();
    }

    /**
     * Tests the run() method with an optional parameter.
     *
     * @return void
     */
    public function testRunMethodWithOptionalParameter()
    {
        $this->expectOutputString('Hello');

        $this->runApplication('GET', '/optional')->run();
    }

    /**
     * Tests the run() method with a callback as result.
     *
     * @return void
     */
    public function testRunMethodWithCallback()
    {
        $this->expectOutputString('Hello');

        $this->runApplication('GET', '/callback')->run();
    }

    /**
     * Tests the run() method with a PUT HTTP method.
     *
     * @return void
     */
    public function testRunMethodWithPutHttpMethod()
    {
        $this->expectOutputString('Hello from PUT HTTP method');

        $this->runApplication('PUT', '/hello', array('_method' => 'PUT'))->run();
    }

    /**
     * Tests the run() method with Phroute as dispatcher.
     *
     * @return void
     */
    public function testRunMethodWithPhroute()
    {
        if (! class_exists('Phroute\Phroute\RouteCollector')) {
            $this->markTestSkipped('Phroute is not installed.');
        }

        $this->expectOutputString('Hello');

        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $routes = array(array('GET', '/', array($class, 'index')));

        $router = new \Rougin\Slytherin\Dispatching\Phroute\Router($routes);

        $dispatcher = new \Rougin\Slytherin\Dispatching\Phroute\Dispatcher($router);

        $this->components->setDispatcher($dispatcher);

        $this->runApplication('GET', '/')->run();
    }

    /**
     * Tests the "integration" functionality.
     *
     * @return void
     */
    public function testRunMethodWithIntegrateMethod()
    {
        $slash = DIRECTORY_SEPARATOR;

        $root = str_replace($slash . 'tests' . $slash . 'Application', '', __DIR__);

        header('X-SLYTHERIN-HEADER: foobar');

        $router = new \Rougin\Slytherin\Dispatching\Vanilla\Router;

        $router->get('/', array('Rougin\Slytherin\Fixture\Classes\NewClass', 'index'));

        $application = new \Rougin\Slytherin\Application;

        $config = new \Rougin\Slytherin\Configuration(__DIR__ . '/../Fixture/Configurations');

        $config->set('app.environment', 'development');
        $config->set('app.router', $router);
        $config->set('app.views', $root);

        $integrations = array('Rougin\Slytherin\Http\HttpIntegration');

        $integrations[] = 'Rougin\Slytherin\Routing\RoutingIntegration';
        $integrations[] = 'Rougin\Slytherin\Template\RendererIntegration';
        $integrations[] = 'Rougin\Slytherin\Debug\ErrorHandlerIntegration';

        if (interface_exists('Interop\Http\ServerMiddleware\MiddlewareInterface')) {
            $integrations[] = 'Rougin\Slytherin\Middleware\MiddlewareIntegration';
        }

        $integrations[] = 'Rougin\Slytherin\Integration\ConfigurationIntegration';

        $this->expectOutputString('Hello');

        $application->integrate($integrations, $config)->run();
    }

    /**
     * Changes the HTTP method and the uri of the request.
     *
     * @param  string $httpMethod
     * @param  string $uriEndpoint
     * @param  array  $data
     * @return \Rougin\Slytherin\Application\Application
     */
    private function runApplication($httpMethod, $uriEndpoint, $data = array())
    {
        list($request, $response) = $this->components->getHttp();

        $uri = new \Rougin\Slytherin\Http\Uri('http://localhost:8000' . $uriEndpoint);

        $request = $request->withMethod($httpMethod)->withUri($uri);

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

        return new \Rougin\Slytherin\Application($this->components);
    }
}
