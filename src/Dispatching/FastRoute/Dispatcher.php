<?php

namespace Rougin\Slytherin\Dispatching\FastRoute;

use Closure;
use FastRoute;
use UnexpectedValueException;
use FastRoute\Dispatcher as FastRouteDispatcher;

use Rougin\Slytherin\Dispatching\RouterInterface;
use Rougin\Slytherin\Dispatching\DispatcherInterface;

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
class Dispatcher implements DispatcherInterface
{
    /**
     * @var \FastRoute\Dispatcher
     */
    protected $dispatcher;

    /**
     * @var \Rougin\Slytherin\Dispatching\RouterInterface
     */
    protected $router;

    /**
     * @param \Rougin\Slytherin\Dispatching\RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->dispatcher = FastRoute\simpleDispatcher($router->getRoutes());
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
        $className   = '';
        $middlewares = [];
        $parameters  = [];

        $result = $this->dispatcher->dispatch($httpMethod, $uri);
        $route  = $this->router->getRoute($httpMethod, $uri);

        switch ($result[0]) {
            case FastRouteDispatcher::NOT_FOUND:
                throw new UnexpectedValueException("Route \"$uri\" not found");
            case FastRouteDispatcher::METHOD_NOT_ALLOWED:
                throw new UnexpectedValueException("Used method's not allowed");
            case FastRouteDispatcher::FOUND:
                $className  = $result[1];
                $parameters = $result[2];

                // Checks if the result contains middlewares
                if ($route[2] == $className && isset($route[3])) {
                    $middlewares = $route[3];
                }

                break;
        }

        return [ $className, $parameters, $middlewares ];
    }
}
