<?php

namespace Rougin\Slytherin\Routing;

/**
 * Route Interface
 *
 * An interface for handling the HTTP routes.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
interface RouteInterface
{
    const ALLOWED_REGEX = '[a-zA-Z0-9\_\-]+';

    /**
     * Returns the handler.
     *
     * @return callable|string[]
     */
    public function getHandler();

    /**
     * Returns the HTTP method.
     *
     * @return string
     */
    public function getMethod();

    /**
     * Returns the defined middlewares.
     *
     * @return mixed[]
     */
    public function getMiddlewares();

    /**
     * Returns the defined parameters.
     *
     * @return string[]
     */
    public function getParams();

    /**
     * Returns a regular expression pattern from the given URI.
     *
     * @return string
     */
    public function getRegex();

    /**
     * Return the URI of the route.
     *
     * @return string
     */
    public function getUri();

    /**
     * Sets the parameters to the route.
     *
     * @param  string[] $params
     * @return self
     */
    public function setParams($params);
}
