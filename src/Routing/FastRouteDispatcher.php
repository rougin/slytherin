<?php

namespace Rougin\Slytherin\Routing;

use FastRoute\RouteCollector;

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
class FastRouteDispatcher extends Dispatcher
{
    /**
     * @var \FastRoute\Dispatcher
     */
    protected $fastroute;

    /**
     * @var \Rougin\Slytherin\Routing\RouterInterface
     */
    protected $router;

    /**
     * Dispatches against the provided HTTP method verb and URI.
     *
     * @param  string $method
     * @param  string $uri
     * @return array|mixed
     */
    public function dispatch($method, $uri)
    {
    }
}
