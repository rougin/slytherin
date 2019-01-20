<?php

namespace Rougin\Slytherin\Application;

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
    public function setUp()
    {
        $container = new \Rougin\Slytherin\Container\Container;
        $dispatcher = new \Rougin\Slytherin\Routing\Dispatcher($this->router());
        $response = new \Rougin\Slytherin\Http\Response;

        $container->set('Psr\Http\Message\ServerRequestInterface', $this->request('GET', '/'));
        $container->set('Psr\Http\Message\ResponseInterface', $response);
        $container->set('Rougin\Slytherin\Routing\DispatcherInterface', $dispatcher);

        if (interface_exists('Rougin\Slytherin\Middleware\MiddlewareInterface'))
        {
            $middleware = new \Rougin\Slytherin\Middleware\Dispatcher;

            $container->set('Rougin\Slytherin\Middleware\MiddlewareInterface', $middleware);
        }

        $this->application = new \Rougin\Slytherin\Application($container);
    }
}
