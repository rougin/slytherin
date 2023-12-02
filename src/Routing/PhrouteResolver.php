<?php

namespace Rougin\Slytherin\Routing;

use Phroute\Phroute\HandlerResolverInterface;

/**
 * Phroute Resolver
 *
 * A handler resolver that uses PSR-11 containers to resolve dependencies.
 *
 * https://github.com/mrjgreen/phroute
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class PhrouteResolver implements HandlerResolverInterface
{
    /**
     * Create an instance of the given handler.
     *
     * @param  \Rougin\Slytherin\Routing\RouteInterface $handler
     * @return array<int, mixed>
     */
    public function resolve($handler)
    {
        /** @var \Rougin\Slytherin\Routing\RouteInterface */
        $route = $handler;

        $handler = $route->getHandler();

        $method = $route->getMethod();

        $uri = $route->getUri();

        $middlewares = $route->getMiddlewares();

        $route = new Route($method, $uri, $handler, $middlewares);

        return array(new PhrouteRoute($route), 'setParams');
    }
}
