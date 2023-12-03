<?php

namespace Rougin\Slytherin\Application;

use Rougin\Slytherin\Application;
use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Routing\Dispatcher;
use Rougin\Slytherin\Server\Dispatch;
use Rougin\Slytherin\System;

/**
 * Container Interface Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ContainerInterfaceTest extends ApplicationTestCases
{
    /**
     * Prepares the application instance.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $container = new Container;

        $dispatcher = new Dispatcher($this->router());
        $container->set(System::DISPATCHER, $dispatcher);

        $request = $this->request('GET', '/');
        $container->set(System::SERVER_REQUEST, $request);

        $response = new Response;
        $container->set(System::RESPONSE, $response);

        $dispatch = new Dispatch;
        $container->set(System::MIDDLEWARE, $dispatch);

        $this->application = new Application($container);
    }
}
