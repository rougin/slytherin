<?php

namespace Rougin\Slytherin\Routing;

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
class PhrouteDispatcher implements DispatcherInterface
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
     * @param \Rougin\Slytherin\Routing\RouterInterface|null $router
     */
    public function __construct(RouterInterface $router = null)
    {
        $router == null || $this->router($router);
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
        $routeInfo   = $this->router->retrieve($httpMethod, $uri);
        $routeResult = $this->dispatcher->dispatch($httpMethod, $uri);
        $middlewares = ($routeResult && isset($routeInfo[3])) ? $routeInfo[3] : array();

        return array($routeResult, null, $middlewares);
    }

    /**
     * Sets the router and parse its available routes if needed.
     *
     * @param  \Rougin\Slytherin\Routing\RouterInterface $router
     * @return self
     */
    public function router(RouterInterface $router)
    {
        $this->router = $router;

        if (! is_a($router, 'Rougin\Slytherin\Routing\Phroute\Router')) {
            $collector = new \Phroute\Phroute\RouteCollector;

            foreach ($router->routes() as $route) {
                $collector->addRoute($route[0], $route[1], $route[2]);
            }

            $routes = $collector->getData();
        } else {
            $routes = $router->routes(true);
        }

        $this->dispatcher = new \Phroute\Phroute\Dispatcher($routes);

        return $this;
    }
}
