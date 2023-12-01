<?php

namespace Rougin\Slytherin\Routing;

/**
 * Dispatcher
 *
 * A simple implementation of a route dispatcher that is based on DispatcherInterface.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Dispatcher implements DispatcherInterface
{
    /**
     * @var \Rougin\Slytherin\Routing\RouterInterface
     */
    protected $router;

    /**
     * Initializes the route dispatcher instance.
     *
     * @param \Rougin\Slytherin\Routing\RouterInterface|null $router
     */
    public function __construct(RouterInterface $router = null)
    {
        if ($router) $this->router($router);
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
        $uri = $uri[0] !== '/' ? '/' . $uri : $uri;

        $route = $this->match($method, $uri);

        if (! $route)
        {
            $text = (string) 'Route "%s %s" not found';

            $error = sprintf($text, $method, $uri);

            throw new \UnexpectedValueException($error);
        }

        // Parses the matched parameters back to the route --------
        $params = $route->getParams();

        /** @var string[] */
        $filtered = array_filter(array_keys($params), 'is_string');

        /** @var string[] */
        $flipped = (array) array_flip($filtered);

        /** @var string[] */
        $values = array_intersect_key($params, $flipped);
        // --------------------------------------------------------

        return $route->setParams($values);
    }

    /**
     * Sets the router and parse its available routes if needed.
     * NOTE: To be removed in v1.0.0. Use $this->setRouter() instead.
     *
     * @param  \Rougin\Slytherin\Routing\RouterInterface $router
     * @return self
     */
    public function router(RouterInterface $router)
    {
        return $this->setRouter($router);
    }

    /**
     * Sets the router and parse its available routes if needed.
     *
     * @param  \Rougin\Slytherin\Routing\RouterInterface $router
     * @return self
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;

        return $this;
    }

    /**
     * Matches the route from the parsed URI.
     *
     * @param  string $method
     * @param  string $uri
     * @return \Rougin\Slytherin\Routing\RouteInterface
     */
    protected function match($method, $uri)
    {
        $routes = $this->router->routes();

        foreach ($routes as $route)
        {
            $regex = $route->getRegex();

            $matched = preg_match($regex, $uri, $matches);

            $sameMethod = $route->getMethod() === $method;

            if ($matched && $sameMethod)
            {
                return $route->setParams($matches);
            }
        }

        return null;
    }
}
