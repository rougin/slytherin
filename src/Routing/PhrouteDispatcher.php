<?php

namespace Rougin\Slytherin\Routing;

use Phroute\Phroute\Dispatcher as Phroute;
use Phroute\Phroute\Exception\HttpRouteNotFoundException;
use Phroute\Phroute\RouteDataArray;
use Rougin\Slytherin\System\Errors\RouteNotFound;

/**
 * A route dispatcher built on top of Phroute.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 *
 * @link https://github.com/mrjgreen/phroute
 */
class PhrouteDispatcher extends Dispatcher
{
    /**
     * @var \Phroute\Phroute\RouteCollector
     */
    protected $collector;

    /**
     * @var \Phroute\Phroute\Dispatcher
     */
    protected $dispatcher;

    /**
     * @var \Phroute\Phroute\RouteDataArray
     */
    protected $items;

    /**
     * Dispatches against the provided HTTP method
     * verb and URI.
     *
     * @param string $method
     * @param string $uri
     *
     * @return \Rougin\Slytherin\Routing\RouteInterface
     * @throws \BadMethodCallException
     */
    public function dispatch($method, $uri)
    {
        $this->validMethod($method);

        $resolver = new PhrouteResolver;

        $phroute = new Phroute($this->items, $resolver);

        try
        {
            $route = $phroute->dispatch($method, $uri);

            if (! $route instanceof RouteInterface)
            {
                throw new RouteNotFound($route);
            }

            // Combine values from resolver and the current parameters -------------
            $regex = '/\{([a-zA-Z0-9\_\-]+)\}/i';

            $matched = preg_match_all($regex, $route->getUri(), $matches);

            // If "{name}" pattern is not found, try the ":name" pattern instead ---
            if (! $matched)
            {
                $regex = '/\:([a-zA-Z0-9\_\-]+)/i';

                $matched = preg_match_all($regex, $route->getUri(), $matches);
            }
            // ---------------------------------------------------------------------

            /** @var array<string, string> */
            $params = array_combine($matches[1], $route->getParams());

            return $route->setParams($params);
            // ---------------------------------------------------------------------
        }
        catch (HttpRouteNotFoundException $e)
        {
            throw new \BadMethodCallException($e->getMessage());
        }
    }

    /**
     * Sets the router and parse its available
     * routes if needed.
     *
     * @param \Rougin\Slytherin\Routing\RouterInterface $router
     *
     * @return self
     * @throws \UnexpectedValueException
     */
    public function setRouter(RouterInterface $router)
    {
        $routes = $router->routes();

        $parsed = $router->parsed($routes);

        // Force to use third-party router if not being used ---
        if (! $parsed instanceof RouteDataArray)
        {
            $phroute = new PhrouteRouter;

            $parsed = $phroute->asParsed($routes);
        }
        // -----------------------------------------------------

        $this->router = $router;

        $this->items = $parsed;

        return $this;
    }
}
