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
     * @var string[]
     */
    protected $allowed = array('DELETE', 'GET', 'OPTIONS', 'PATCH', 'POST', 'PUT');

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
        if ($router) $this->setRouter($router);
    }

    /**
     * Dispatches against the provided HTTP method verb and URI.
     *
     * @param  string $method
     * @param  string $uri
     * @return \Rougin\Slytherin\Routing\RouteInterface
     *
     * @throws \BadMethodCallException
     */
    public function dispatch($method, $uri)
    {
        $this->validMethod($method);

        $uri = $uri[0] !== '/' ? '/' . $uri : $uri;

        $route = $this->match($method, $uri);

        if (! $route)
        {
            $text = (string) 'Route "%s %s" not found';

            $error = sprintf($text, $method, $uri);

            throw new \BadMethodCallException($error);
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
     *
     * @param  \Rougin\Slytherin\Routing\RouterInterface $router
     * @return self
     *
     * @throws \UnexpectedValueException
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;

        return $this;
    }

    /**
     * Checks if the specified method is a valid HTTP method.
     *
     * @param  string $method
     * @return boolean
     *
     * @throws \BadMethodCallException
     */
    protected function validMethod($method)
    {
        if (in_array($method, $this->allowed)) return true;

        $message = 'Used method is not allowed (' . $method . ')';

        throw new \BadMethodCallException($message);
    }

    /**
     * Matches the route from the parsed URI.
     *
     * @param  string $method
     * @param  string $uri
     * @return \Rougin\Slytherin\Routing\RouteInterface|null
     */
    protected function match($method, $uri)
    {
        $routes = $this->router->routes();

        $isOptions = $method === 'OPTIONS';

        foreach ($routes as $route)
        {
            $regex = $route->getRegex();

            $matched = preg_match($regex, $uri, $matches);

            $sameMethod = $route->getMethod() === $method;

            if ($matched && ($sameMethod || $isOptions))
            {
                return $route->setParams($matches);
            }
        }

        return null;
    }
}
