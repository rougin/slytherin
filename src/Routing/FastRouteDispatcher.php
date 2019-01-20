<?php

namespace Rougin\Slytherin\Routing;

use FastRoute\Dispatcher\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;

/**
 * FastRoute Dispatcher
 *
 * A simple implementation of dispatcher that is built on top of FastRoute.
 *
 * https://github.com/nikic/FastRoute
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class FastRouteDispatcher extends AbstractDispatcher implements DispatcherInterface
{
    /**
     * @var \FastRoute\Dispatcher\GroupCountBased
     */
    protected $dispatcher;

    /**
     * @var \Rougin\Slytherin\Routing\RouterInterface
     */
    protected $router;

    /**
     * Dispatches against the provided HTTP method verb and URI.
     *
     * @param  string $httpMethod
     * @param  string $uri
     * @return array|mixed
     */
    public function dispatch($httpMethod, $uri)
    {
        $this->allowed($httpMethod);

        $result = $this->dispatcher->dispatch($httpMethod, $uri);

        if ($result[0] == \FastRoute\Dispatcher::NOT_FOUND) {
            $message = 'Route "' . $uri . '" not found';

            throw new \UnexpectedValueException($message);
        }

        $route = $this->router->retrieve($httpMethod, $uri);

        $middlewares = ($route[2] == $result[1] && isset($route[3])) ? $route[3] : array();

        return array(array($result[1], $result[2]), $middlewares);
    }

    /**
     * Sets the router and parse its available routes if needed.
     *
     * @param  \Rougin\Slytherin\Routing\RouterInterface $router
     * @return self
     */
    public function router(RouterInterface $router)
    {
        $routes = $router->routes();

        $this->router = $router;

        $generator = new \FastRoute\DataGenerator\GroupCountBased;

        $routes($router = new RouteCollector(new Std, $generator));

        $this->dispatcher = new GroupCountBased($router->getData());

        return $this;
    }
}
