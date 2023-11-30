<?php

namespace Rougin\Slytherin\Routing;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Route implements RouteInterface
{
    /**
     * @var callable|string[]|string
     */
    protected $handler;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[]
     */
    protected $middlewares;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @param string                                                        $method
     * @param string                                                        $uri
     * @param callable|string[]|string                                      $handler
     * @param \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[] $middlewares
     */
    public function __construct($method, $uri, $handler, $middlewares = array())
    {
        $this->handler = $handler;

        $this->method = $method;

        $this->middlewares = $middlewares;

        $this->uri = (string) $uri;
    }

    /**
     * @return callable|string[]|string
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[]
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }

    /**
     * Returns a regular expression pattern from the given URI.
     *
     * @link https://stackoverflow.com/q/30130913
     *
     * @return string
     */
    public function getRegex()
    {
        // Turn "(/)" into "/?"
        $uri = preg_replace('#\(/\)#', '/?', $this->uri);

        // Create capture group for ":parameter", replaces ":parameter"
        $uri = $this->capture($uri, '/:(' . self::ALLOWED_REGEX . ')/');

        // Create capture group for '{parameter}', replaces "{parameter}"
        $uri = $this->capture($uri, '/{(' . self::ALLOWED_REGEX . ')}/');

        // Add start and end matching
        return (string) '@^' . $uri . '$@D';
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
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
        $replace = '(?<$1>' . self::ALLOWED_REGEX . ')';

        return preg_replace($search, $replace, $pattern);
    }
}
