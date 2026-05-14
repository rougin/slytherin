<?php

namespace Rougin\Slytherin\System;

use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Http\ServerRequest;
use Rougin\Slytherin\Routing\Dispatcher;
use Rougin\Slytherin\Routing\Router;
use Rougin\Slytherin\System;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class SystemTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Container\Container
     */
    protected $container;

    /**
     * @return void
     */
    public function test_failed_if_debugger_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doExpectException($expect);

        $this->container->set(System::DEBUGGER, new NewClass);

        $system = new System($this->container);

        $system->run();
    }

    /**
     * @return void
     */
    public function test_failed_if_dispatcher_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doExpectException($expect);

        $this->container->set(System::DISPATCHER, new NewClass);
        $system = new System($this->container);

        $server = array();
        $server['REQUEST_METHOD'] = 'GET';
        $server['REQUEST_URI'] = '/';
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $request = new ServerRequest($server);

        $system->handle($request);
    }

    /**
     * @return void
     */
    public function test_failed_if_integration_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';
        $this->doExpectException($expect);

        $system = new System($this->container);

        $sample = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $system->integrate(array($sample));
    }

    /**
     * @return void
     */
    public function test_failed_if_middleware_not_found_in_handle()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doExpectException($expect);

        $router = new Router;

        $router->get('/test', function ()
        {
            return 'test';
        });

        $dispatcher = new Dispatcher($router);

        $this->container->set(System::DISPATCHER, $dispatcher);

        $this->container->set(System::MIDDLEWARE, new NewClass);

        $server = array();
        $server['REQUEST_METHOD'] = 'GET';
        $server['REQUEST_URI'] = '/test';
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $request = new ServerRequest($server);

        $system = new System($this->container);

        $system->handle($request);
    }

    /**
     * @return void
     */
    public function test_failed_if_request_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doExpectException($expect);

        $this->container->set(System::REQUEST, new NewClass);

        $system = new System($this->container);

        $system->run();
    }

    /**
     * @return void
     */
    public function test_failed_if_router_not_found_in_handle()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doExpectException($expect);

        $router = new Router;

        $router->get('/', function ()
        {
            return 'test';
        });

        $dispatcher = new Dispatcher($router);

        $this->container->set(System::DISPATCHER, $dispatcher);

        $this->container->set(System::ROUTER, new NewClass);

        $server = array('REQUEST_URI' => '/');
        $server['REQUEST_METHOD'] = 'GET';
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $request = new ServerRequest($server);

        $system = new System($this->container);

        $system->handle($request);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->container = new Container;
    }
}
