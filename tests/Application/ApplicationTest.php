<?php

namespace Rougin\Slytherin\Application;

use Rougin\Slytherin\Component\Collector;
use Rougin\Slytherin\Configuration;
use Rougin\Slytherin\Dispatching\Phroute\Dispatcher as PhrouteDispatcher;
use Rougin\Slytherin\Dispatching\Phroute\Router as PhrouteRouter;
use Rougin\Slytherin\Dispatching\Vanilla\Router;
use Rougin\Slytherin\Http\Uri;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ApplicationTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Component\Collection
     */
    protected $components;

    /**
     * @return void
     */
    protected function doSetUp()
    {
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
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_default_route()
    {
        $this->expectOutputString('Hello');

        $this->setUrl('GET', '/')->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_route_with_http_response()
    {
        $this->expectOutputString('Hello with response');

        $this->setUrl('GET', '/hello')->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_route_with_parameter()
    {
        $this->expectOutputString('Hello');

        $this->setUrl('GET', '/parameter')->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_route_with_optional_parameter()
    {
        $this->expectOutputString('Hello');

        $this->setUrl('GET', '/optional')->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_route_with_callback()
    {
        $this->expectOutputString('Hello');

        $this->setUrl('GET', '/callback')->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_route_with_put_http_method()
    {
        $this->expectOutputString('Hello from PUT HTTP method');

        $this->setUrl('PUT', '/hello', array('_method' => 'PUT'))->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_default_route_with_phroute_dispatcher()
    {
        // @codeCoverageIgnoreStart
        if (! class_exists('Phroute\Phroute\RouteCollector'))
        {
            $this->markTestSkipped('Phroute is not installed.');
        }
        // @codeCoverageIgnoreEnd

        $this->expectOutputString('Hello');

        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $routes = array(array('GET', '/', array($class, 'index')));

        $router = new PhrouteRouter((array) $routes);

        $dispatcher = new PhrouteDispatcher($router);

        $this->components->setDispatcher($dispatcher);

        $this->setUrl('GET', '/')->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_default_route_with_integrations()
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
     * @param  string                $method
     * @param  string                $uri
     * @param  array<string, string> $data
     * @return \Rougin\Slytherin\Application
     */
    private function setUrl($method, $uri, $data = array())
    {
        $result = $this->components->getHttp();

        /** @var \Psr\Http\Message\ServerRequestInterface */
        $request = $result[0];

        /** @var \Psr\Http\Message\ResponseInterface */
        $response = $result[1];

        $uri = new Uri('http://localhost:8000' . $uri);

        $request = $request->withMethod($method)->withUri($uri);

        if ($method === 'GET')
        {
            $request = $request->withQueryParams($data);
        }

        if (in_array($method, array('POST', 'PUT', 'DELETE')))
        {
            $request = $request->withParsedBody($data);
        }

        $this->components->setHttp($request, $response);

        return new Application($this->components);
    }
}
