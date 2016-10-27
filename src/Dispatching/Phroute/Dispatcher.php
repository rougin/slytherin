<?php

namespace Rougin\Slytherin\Dispatching\Phroute;

/**
 * Phroute Dispatcher
 *
 * A simple implementation of dispatcher that is built on top of Phroute.
 *
 * https://github.com/mrjgreen/phroute
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Dispatcher implements \Rougin\Slytherin\Dispatching\DispatcherInterface
{
    /**
     * @var \Phroute\Phroute\Dispatcher
     */
    protected $dispatcher;

    /**
     * @var \Rougin\Slytherin\Dispatching\Phroute\Router
     */
    protected $router;

    /**
     * @param \Rougin\Slytherin\Dispatching\Phroute\Router $router
     */
    public function __construct(Router $router)
    {
        $this->dispatcher = new \Phroute\Phroute\Dispatcher($router->getRoutes());
        $this->router     = $router;
    }

    /**
     * Dispatches against the provided HTTP method verb and URI.
     *
     * @param  string $httpMethod
     * @param  string $uri
     * @return array
     */
    public function dispatch($httpMethod, $uri)
    {
        $routeInfo   = $this->router->getRoute($httpMethod, $uri);
        $routeResult = $this->dispatcher->dispatch($httpMethod, $uri);
        $middlewares = ($routeResult && isset($routeInfo[3])) ? $routeInfo[3] : [];

        return [ $routeResult, null, $middlewares ];
    }
}
