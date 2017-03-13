<?php

namespace Rougin\Slytherin\Routing\FastRoute;

/**
 * FastRoute Dispatcher
 *
 * A simple implementation of dispatcher that is built on top of FastRoute.
 *
 * https://github.com/nikic/FastRoute
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Dispatcher implements \Rougin\Slytherin\Routing\DispatcherInterface
{
    /**
     * @var \FastRoute\Dispatcher
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

        if (! is_a($router, 'Rougin\Slytherin\Routing\FastRoute\Router')) {
            $routes = function (\FastRoute\RouteCollector $collector) use ($router) {
                $routes = array_filter($router->getRoutes());

                foreach ($routes as $route) {
                    $collector->addRoute($route[0], $route[1], $route[2]);
                }
            };

            return $this->dispatcher = \FastRoute\simpleDispatcher($routes);
        }

        $routes = $router->getRoutes(true);

        $this->dispatcher = \FastRoute\simpleDispatcher($routes);
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
        $result = $this->dispatcher->dispatch($httpMethod, $uri);
        $route  = $this->router->getRoute($httpMethod, $uri);

        $this->throwException($result[0], $uri);

        $middlewares = ($route[2] == $route[1] && isset($route[3])) ? $route[3] : array();

        return array($result[1], $result[2], $middlewares);
    }

    /**
     * Throws an exception if it matches to the following result.
     *
     * @param  integer $result
     * @param  string  $uri
     * @return void
     */
    protected function throwException($result, $uri)
    {
        if ($result == \FastRoute\Dispatcher::NOT_FOUND) {
            throw new \UnexpectedValueException('Route "' . $uri . '" not found');
        }

        if ($result == \FastRoute\Dispatcher::METHOD_NOT_ALLOWED) {
            throw new \UnexpectedValueException('Used method is not allowed');
        }
    }
}
