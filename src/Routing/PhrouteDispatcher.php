<?php

namespace Rougin\Slytherin\Routing;

use Phroute\Phroute\Dispatcher as Phroute;
use Phroute\Phroute\Exception\HttpRouteNotFoundException;
use Phroute\Phroute\HandlerResolverInterface;
use Phroute\Phroute\RouteDataArray;

/**
 * Phroute Dispatcher
 *
 * A simple implementation of dispatcher that is built on top of Phroute.
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
     * @var \Phroute\Phroute\HandlerResolverInterface|null
     */
    protected $resolver;

    /**
     * @param \Rougin\Slytherin\Routing\RouterInterface|null $router
     * @param \Phroute\Phroute\HandlerResolverInterface|null $resolver
     */
    public function __construct(RouterInterface $router = null, HandlerResolverInterface $resolver = null)
    {
        parent::__construct($router);

        if (! $resolver)
        {
            $resolver = new PhrouteResolver;
        }

        $this->resolver = $resolver;
    }

    /**
     * Dispatches against the provided HTTP method verb and URI.
     *
     * @param string $method
     * @param string $uri
     *
     * @return \Rougin\Slytherin\Routing\RouteInterface
     *
     * @throws \BadMethodCallException
     */
    public function dispatch($method, $uri)
    {
        $this->validMethod($method);

        $phroute = new Phroute($this->items, $this->resolver);

        try
        {
            /** @var \Rougin\Slytherin\Routing\RouteInterface */
            $route = $phroute->dispatch($method, $uri);

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
     * Sets the router and parse its available routes if needed.
     *
     * @param \Rougin\Slytherin\Routing\RouterInterface $router
     *
     * @return self
     *
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
