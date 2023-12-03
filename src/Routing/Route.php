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
     * @var mixed[]
     */
    protected $middlewares;

    /**
     * @var string[]
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

        if (is_string($middlewares))
        {
            $middlewares = array($middlewares);
        }

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
     * @return mixed[]
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }

    /**
     * @return string[]
     */
    public function getParams()
    {
        return $this->params;
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

        /** @var string */
        return preg_replace($search, $replace, $pattern);
    }

    /**
     * @param  string[] $params
     * @return self
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }
}
