<?php

namespace Rougin\Slytherin\Dispatching;

/**
 * Base Router
 *
 * A simple implementation of a router that is based on
 * Rougin\Slytherin\Dispatching\RouterInterface.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
abstract class BaseRouter implements RouterInterface
{
    /**
     * @var array
     */
    protected $routes = array();

    /**
     * @var array
     */
    protected $validHttpMethods = array('DELETE', 'GET', 'PATCH', 'POST', 'PUT');

    /**
     * Adds a new route.
     *
     * @param  string|string[] $httpMethod
     * @param  string          $route
     * @param  mixed           $handler
     * @param  array           $middlewares
     * @return self
     */
    public function addRoute($httpMethod, $route, $handler, $middlewares = array())
    {
        $class = array($httpMethod, $route, $handler, $middlewares);

        array_push($this->routes, $class);

        return $this;
    }

    /**
     * Returns a route details based on the specified HTTP method and URI.
     *
     * @param  string $httpMethod
     * @param  string $uri
     * @return array|null
     */
    public function getRoute($httpMethod, $uri)
    {
        $result = null;

        foreach ($this->routes as $route) {
            if ($route[0] == $httpMethod && $route[1] == $uri) {
                $result = $route;

                break;
            }
        }

        return $result;
    }

    /**
     * Returns a listing of routes available.
     *
     * @return array|callable
     */
    abstract public function getRoutes();

    /**
     * Calls methods from this class in HTTP method format.
     *
     * @param  string $method
     * @param  mixed  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (in_array(strtoupper($method), $this->validHttpMethods)) {
            array_unshift($parameters, strtoupper($method));

            return call_user_func_array([ $this, 'addRoute' ], $parameters);
        }

        throw new \BadMethodCallException('"' . $method . '" is not a valid HTTP method.');
    }
}
