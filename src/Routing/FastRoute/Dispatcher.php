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
     * @var \Rougin\Slytherin\Routing\FastRoute\Router
     */
    protected $router;

    /**
     * @param \Rougin\Slytherin\Routing\FastRoute\Router $router
     */
    public function __construct(Router $router)
    {
        $this->dispatcher = \FastRoute\simpleDispatcher($router->getRoutes());
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
        switch ($result) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                throw new \UnexpectedValueException("Route \"$uri\" not found");
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                throw new \UnexpectedValueException("Used method's not allowed");
        }
    }
}
