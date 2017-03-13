<?php

namespace Rougin\Slytherin\Routing\Phroute;

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
class Dispatcher implements \Rougin\Slytherin\Routing\DispatcherInterface
{
    /**
     * @var \Phroute\Phroute\Dispatcher
     */
    protected $dispatcher;

    /**
     * @var \Rougin\Slytherin\Routing\RouterInterface
     */
    protected $router;

    /**
     * @param \Rougin\Slytherin\Routing\RouterInterface $router
     */
    public function __construct(\Rougin\Slytherin\Routing\RouterInterface $router)
    {
        $this->router = $router;

        if (is_a($router, 'Rougin\Slytherin\Routing\Phroute\Router')) {
            $routes = $router->getRoutes(true);
        } else {
            $collector = new \Phroute\Phroute\RouteCollector;

            foreach ($router->getRoutes() as $route) {
                $collector->addRoute($route[0], $route[1], $route[2]);
            }

            $routes = $collector->getData();
        }

        $this->dispatcher = new \Phroute\Phroute\Dispatcher($routes);
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
        $middlewares = ($routeResult && isset($routeInfo[3])) ? $routeInfo[3] : array();

        return array($routeResult, null, $middlewares);
    }
}
