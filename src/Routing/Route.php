<?php

namespace Rougin\Slytherin\Routing;

/**
 * Route
 *
 * A simple class for handling routes.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Route implements RouteInterface
{
    /**
     * @var callable|string[]
     */
    protected $handler;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var mixed[]
     */
    protected $middlewares;

    /**
     * @var array<string, string>
     */
    protected $params = array();

    /**
     * @var string
     */
    protected $uri;

    /**
     * @param string                   $method
     * @param string                   $uri
     * @param callable|string[]|string $handler
     * @param mixed[]|string           $middlewares
     */
    public function __construct($method, $uri, $handler, $middlewares = array())
    {
        if (is_string($handler))
        {
            /** @var string[] */
            $handler = explode('@', $handler);
        }

        $this->handler = $handler;

        $this->method = $method;

        if (! is_array($middlewares))
        {
            $middlewares = array($middlewares);
        }

        $this->middlewares = $middlewares;

        $this->uri = (string) $uri;
    }

    /**
     * Returns the handler.
     *
     * @return callable|string[]
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * Returns the HTTP method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Returns the defined middlewares.
     *
     * @return mixed[]
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }

    /**
     * Returns the defined parameters.
     *
     * @return array<string, string>
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Returns a regular expression pattern from the given URI.
     *
     * @return string
     *
     * @link https://stackoverflow.com/q/30130913
     */
    public function getRegex()
    {
        // Turn "(/)" into "/?" ------------------------------
        /** @var string */
        $uri = preg_replace('#\(/\)#', '/?', $this->getUri());
        // ---------------------------------------------------

        // Create capture group for ":parameter", replaces ":parameter" ---
        $uri = $this->capture($uri, '/:(' . self::ALLOWED_REGEX . ')/');
        // ----------------------------------------------------------------

        // Create capture group for '{parameter}', replaces "{parameter}" ---
        $uri = $this->capture($uri, '/{(' . self::ALLOWED_REGEX . ')}/');
        // ------------------------------------------------------------------

        // Add start and end matching ------
        return (string) '@^' . $uri . '$@D';
        // ---------------------------------
    }

    /**
     * Return the URI of the route.
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Sets the parameters to the route.
     *
     * @param  array<string, string> $params
     * @return self
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Capture the specified regular expressions.
     *
     * @param  string $pattern
     * @param  string $search
     * @return string
     */
    protected function capture($pattern, $search)
    {
        /** @var string */
        return preg_replace($search, '(?<$1>' . self::ALLOWED_REGEX . ')', $pattern);
    }
}
