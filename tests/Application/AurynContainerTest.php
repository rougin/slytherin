<?php

namespace Rougin\Slytherin\Application;

use Auryn\Injector;
use Rougin\Slytherin\Container\AurynContainer;
use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Middleware\Dispatcher as Middleware;
use Rougin\Slytherin\Routing\Dispatcher;
use Rougin\Slytherin\System;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class AurynContainerTest extends ApplicationTestCases
{
    /**
     * @var string
     */
    protected $type = 'auryn';

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->checkIfAurynExists();

        $container = new AurynContainer(new Injector);

        $router = $this->router();

        // Add the dispatcher to the container --------
        $class = 'Rougin\Slytherin\Routing\Dispatcher';

        $container->share(new Dispatcher($router));

        $container->alias(System::DISPATCHER, $class);
        // --------------------------------------------

        // Add the server request to the container ----
        $container->share($this->request('GET', '/'));

        $class = 'Rougin\Slytherin\Http\ServerRequest';

        $container->alias(System::REQUEST, $class);
        // --------------------------------------------

        // Add the HTTP response to the container ---
        $class = 'Rougin\Slytherin\Http\Response';

        $container->share(new Response);

        $container->alias(System::RESPONSE, $class);
        // ------------------------------------------

        // Add the middleware to the container -----------
        $class = 'Rougin\Slytherin\Middleware\Dispatcher';

        $container->share(new Middleware);

        $container->alias(System::MIDDLEWARE, $class);
        // -----------------------------------------------

        $this->system = new Application($container);
    }
}
