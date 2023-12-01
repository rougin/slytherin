<?php

namespace Rougin\Slytherin\Routing;

use Phroute\Phroute\Dispatcher as Phroute;
use Phroute\Phroute\HandlerResolverInterface;
use Phroute\Phroute\RouteCollector;

/**
 * Phroute Dispatcher
 *
 * A simple implementation of dispatcher that is built on top of Phroute.
 *
 * https://github.com/mrjgreen/phroute
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
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

        if ($resolver) $this->resolver = $resolver;
    }

    /**
     * Dispatches against the provided HTTP method verb and URI.
     *
     * @param  string $method
     * @param  string $uri
     * @return \Rougin\Slytherin\Routing\RouteInterface
     *
     * @throws \UnexpectedValueException
     */
    public function dispatch($method, $uri)
    {
        $routes = $this->collector->getData();

        $phroute = new Phroute($routes, $this->resolver);

        $result = $phroute->dispatch($method, $uri);

        if (! $result)
        {
            $text = (string) 'Route "%s %s" not found';

            $error = sprintf($text, $method, $uri);

            throw new \UnexpectedValueException($error);
        }

        // Need only to find the Route instance ----
        $route = $this->router->find($method, $uri);
        // -----------------------------------------

        return $route->setResult($result);
    }

    /**
     * Sets the router and parse its available routes if needed.
     *
     * @param  \Rougin\Slytherin\Routing\RouterInterface $router
     * @return self
     */
    public function setRouter(RouterInterface $router)
    {
        $collector = new RouteCollector;

        $routes = $router->routes();

        foreach ($routes as $route)
        {
            $collector->addRoute($route->getMethod(), $route->getUri(), $route->getHandler());
        }

        $this->router = $router;

        $this->collector = $collector;

        return $this;
    }
}
