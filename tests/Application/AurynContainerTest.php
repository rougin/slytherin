<?php

namespace Rougin\Slytherin\Application;

use Rougin\Slytherin\Container\AurynContainer;
use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Middleware\Dispatcher as Middleware;
use Rougin\Slytherin\Routing\Dispatcher;
use Rougin\Slytherin\System;

/**
 * Auryn Container Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class AurynContainerTest extends ApplicationTestCases
{
    /**
     * Prepares the application instance.
     *
     * @return void
     */
    protected function doSetUp()
    {
        if (! class_exists('Auryn\Injector'))
        {
            $this->markTestSkipped('Auryn is not installed.');
        }

        $container = new AurynContainer;

        $router = $this->router();

        $container->share($this->request('GET', '/'));
        $container->alias(System::SERVER_REQUEST, 'Rougin\Slytherin\Http\ServerRequest');

        $container->share(new Response);
        $container->alias(System::RESPONSE, 'Rougin\Slytherin\Http\Response');

        $container->share(new Dispatcher($router));
        $container->alias(System::DISPATCHER, 'Rougin\Slytherin\Routing\Dispatcher');

        $container->share(new Middleware);
        $container->alias(System::MIDDLEWARE, 'Rougin\Slytherin\Middleware\Dispatcher');

        $this->application = new Application($container);
    }
}
