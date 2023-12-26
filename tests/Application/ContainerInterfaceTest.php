<?php

namespace Rougin\Slytherin\Application;

use Rougin\Slytherin\Application;
use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Middleware\Dispatcher as Middleware;
use Rougin\Slytherin\Routing\Dispatcher;
use Rougin\Slytherin\System;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ContainerInterfaceTest extends ApplicationTestCases
{
    /**
     * @return void
     */
    protected function doSetUp()
    {
        $container = new Container;

        $dispatcher = new Dispatcher($this->router());
        $container->set(System::DISPATCHER, $dispatcher);

        $request = $this->request('GET', '/');
        $container->set(System::REQUEST, $request);

        $response = new Response;
        $container->set(System::RESPONSE, $response);

        $dispatch = new Middleware;
        $container->set(System::MIDDLEWARE, $dispatch);

        $this->system = new Application($container);
    }
}
