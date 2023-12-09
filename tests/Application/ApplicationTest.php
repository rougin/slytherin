<?php

namespace Rougin\Slytherin\Application;

use Rougin\Slytherin\Component\Collector;
use Rougin\Slytherin\Configuration;
use Rougin\Slytherin\Dispatching\Phroute\Dispatcher as PhrouteDispatcher;
use Rougin\Slytherin\Dispatching\Phroute\Router as PhrouteRouter;
use Rougin\Slytherin\Dispatching\Vanilla\Router;
use Rougin\Slytherin\Http\Uri;
use Rougin\Slytherin\IoC\Vanilla\Container;
use Rougin\Slytherin\Testcase;

/**
 * Application Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ApplicationTest extends Testcase
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
    protected function doSetUp()
    {
        if (! interface_exists('Psr\Container\ContainerInterface'))
        {
            $this->markTestSkipped('Container Interop is not installed.');
        }

        if (! interface_exists('Psr\Http\Message\ResponseInterface'))
        {
            $this->markTestSkipped('PSR-7 HTTP Message is not installed.');
        }

        $items = array();

        $items[] = 'Rougin\Slytherin\Fixture\Components\ContainerComponent';
        $items[] = 'Rougin\Slytherin\Fixture\Components\DebuggerComponent';
        $items[] = 'Rougin\Slytherin\Fixture\Components\DispatcherComponent';
        $items[] = 'Rougin\Slytherin\Fixture\Components\HttpComponent';
        $items[] = 'Rougin\Slytherin\Fixture\Components\TemplateComponent';

        if (class_exists('Zend\Stratigility\MiddlewarePipe'))
        {
            $items[] = 'Rougin\Slytherin\Fixture\Components\MiddlewareComponent';
        }

        $this->components = Collector::get($items);
    }

    /**
     * Tests the run() method.
     *
     * @return void
     * @runInSeparateProcess
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
     * @runInSeparateProcess
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
     * @runInSeparateProcess
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
     * @runInSeparateProcess
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
     * @runInSeparateProcess
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
     * @runInSeparateProcess
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
     * @runInSeparateProcess
     */
    public function testRunMethodWithPhroute()
    {
        if (! class_exists('Phroute\Phroute\RouteCollector'))
        {
            $this->markTestSkipped('Phroute is not installed.');
        }

        $this->expectOutputString('Hello');

        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $routes = array(array('GET', '/', array($class, 'index')));

        $router = new PhrouteRouter((array) $routes);

        $dispatcher = new PhrouteDispatcher($router);

        $this->components->setDispatcher($dispatcher);

        $this->runApplication('GET', '/')->run();
    }

    /**
     * Tests the "integration" functionality.
     *
     * @return void
     * @runInSeparateProcess
     */
    public function testRunMethodWithIntegrateMethod()
    {
        $slash = DIRECTORY_SEPARATOR;

        $root = str_replace($slash . 'tests' . $slash . 'Application', '', __DIR__);

        header('X-SLYTHERIN-HEADER: foobar');

        $router = new Router;

        $router->get('/', array('Rougin\Slytherin\Fixture\Classes\NewClass', 'index'));

        $application = new Application;

        $config = new Configuration(__DIR__ . '/../Fixture/Configurations');

        $config->set('app.environment', 'development');
        $config->set('app.router', $router);
        $config->set('app.views', (string) $root);

        $items = array('Rougin\Slytherin\Http\HttpIntegration');

        $items[] = 'Rougin\Slytherin\Routing\RoutingIntegration';
        $items[] = 'Rougin\Slytherin\Integration\ConfigurationIntegration';
        $items[] = 'Rougin\Slytherin\Template\RendererIntegration';
        $items[] = 'Rougin\Slytherin\Debug\ErrorHandlerIntegration';
        $items[] = 'Rougin\Slytherin\Middleware\MiddlewareIntegration';

        $this->expectOutputString('Hello');

        $application->integrate($items, $config)->run();
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

        $uri = new Uri('http://localhost:8000' . $uriEndpoint);

        $request = $request->withMethod($httpMethod)->withUri($uri);

        switch ($httpMethod)
        {
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

        return new Application($this->components);
    }
}
