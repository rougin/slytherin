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
 * @deprecated since ~0.9, use "System\SystemTest" instead.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ApplicationTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Component\Collection
     */
    protected $components;

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_callback_route_responded()
    {
        $expect = 'Hello';

        $this->expectOutputString($expect);

        $this->setUrl('GET', '/callback')->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_default_route_responded()
    {
        $expect = 'Hello';

        $this->expectOutputString($expect);

        $this->setUrl('GET', '/')->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_integrations_loaded()
    {
        $slash = DIRECTORY_SEPARATOR;

        $root = str_replace($slash . 'tests' . $slash . 'Application', '', __DIR__);

        // Set up integrations with configuration -----------
        header('X-SLYTHERIN-HEADER: foobar');

        $router = new Router;

        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $router->get('/', array($class, 'index'));

        $application = new Application;

        $config = __DIR__ . '/../Fixture/Configurations';

        $config = new Configuration($config);

        $config->set('app.environment', 'development');
        $config->set('app.router', $router);
        $config->set('app.views', $root);
        // --------------------------------------------------

        $items = array('Rougin\Slytherin\Http\HttpIntegration');

        $items[] = 'Rougin\Slytherin\Routing\RoutingIntegration';
        $items[] = 'Rougin\Slytherin\Integration\ConfigurationIntegration';
        $items[] = 'Rougin\Slytherin\Template\RendererIntegration';
        $items[] = 'Rougin\Slytherin\Debug\ErrorHandlerIntegration';
        $items[] = 'Rougin\Slytherin\Middleware\MiddlewareIntegration';

        $expect = 'Hello';

        $this->expectOutputString($expect);

        $application->integrate($items, $config)->run();

        // [NOTE] Adding these as this was being ---
        // marked as risky starting in PHP 8.2 -----
        restore_error_handler();

        restore_exception_handler();
        // -----------------------------------------
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_optional_route_responded()
    {
        $expect = 'Hello';

        $this->expectOutputString($expect);

        $this->setUrl('GET', '/optional')->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_parameter_route_responded()
    {
        $expect = 'Hello';

        $this->expectOutputString($expect);

        $this->setUrl('GET', '/parameter')->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_phroute_dispatcher_used()
    {
        // @codeCoverageIgnoreStart
        $this->checkIfPhrouteExists();
        // @codeCoverageIgnoreEnd

        $expect = 'Hello';

        $this->expectOutputString($expect);

        // Set up the Phroute dispatcher --------------------------
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $routes = array(array('GET', '/', array($class, 'index')));

        $router = new PhrouteRouter($routes);

        $dispatcher = new PhrouteDispatcher($router);

        $this->components->setDispatcher($dispatcher);
        // --------------------------------------------------------

        // Execute the route via Phroute ---
        $this->setUrl('GET', '/')->run();
        // ---------------------------------
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_put_method_route_responded()
    {
        $expect = 'Hello from PUT HTTP method';

        $this->expectOutputString($expect);

        $data = array('_method' => 'PUT');

        $this->setUrl('PUT', '/hello', $data)->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_response_route_responded()
    {
        $expect = 'Hello with response';

        $this->expectOutputString($expect);

        $this->setUrl('GET', '/hello')->run();
    }

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
     * Changes the HTTP method and the uri of the request.
     *
     * @param string                $method
     * @param string                $uri
     * @param array<string, string> $data
     *
     * @return \Rougin\Slytherin\Application
     */
    protected function setUrl($method, $uri, $data = array())
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
