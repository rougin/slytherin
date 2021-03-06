<?php

namespace Rougin\Slytherin\Application;

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
    public function setUp()
    {
        class_exists('Auryn\Injector') || $this->markTestSkipped('Auryn is not installed.');

        $container = new \Rougin\Slytherin\Container\AurynContainer;

        $container->share($this->request('GET', '/'));
        $container->share(new \Rougin\Slytherin\Http\Response);
        $container->share(new \Rougin\Slytherin\Routing\Dispatcher($this->router()));
        $container->share(new \Rougin\Slytherin\Middleware\Dispatcher);

        $container->alias('Psr\Http\Message\ServerRequestInterface', 'Rougin\Slytherin\Http\ServerRequest');
        $container->alias('Psr\Http\Message\ResponseInterface', 'Rougin\Slytherin\Http\Response');
        $container->alias('Rougin\Slytherin\Routing\DispatcherInterface', 'Rougin\Slytherin\Routing\Dispatcher');
        $container->alias('Rougin\Slytherin\Middleware\MiddlewareInterface', 'Rougin\Slytherin\Middleware\Dispatcher');

        $this->application = new \Rougin\Slytherin\Application($container);
    }
}
