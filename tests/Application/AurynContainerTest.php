<?php declare(strict_types = 1);

namespace Rougin\Slytherin\Application;

use Auryn\Injector;
use Rougin\Slytherin\Container\AurynContainer;
use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Middleware\Dispatcher as Middleware;
use Rougin\Slytherin\Routing\Dispatcher;
use Rougin\Slytherin\System;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class AurynContainerTest extends ApplicationTestCases
{
    /**
     * @return void
     */
    protected function doSetUp()
    {
        // @codeCoverageIgnoreStart
        if (! class_exists('Auryn\Injector'))
        {
            $this->markTestSkipped('Auryn is not installed.');
        }
        // @codeCoverageIgnoreEnd

        $container = new AurynContainer(new Injector);

        $router = $this->router();

        $container->share($this->request('GET', '/'));
        $container->alias(System::REQUEST, 'Rougin\Slytherin\Http\ServerRequest');

        $container->share(new Response);
        $container->alias(System::RESPONSE, 'Rougin\Slytherin\Http\Response');

        $container->share(new Dispatcher($router));
        $container->alias(System::DISPATCHER, 'Rougin\Slytherin\Routing\Dispatcher');

        $container->share(new Middleware);
        $container->alias(System::MIDDLEWARE, 'Rougin\Slytherin\Middleware\Dispatcher');

        $this->system = new Application($container);
    }
}
