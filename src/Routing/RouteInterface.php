<?php

namespace Rougin\Slytherin\Routing;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
interface RouteInterface
{
    const ALLOWED_REGEX = '[a-zA-Z0-9\_\-]+';

    /**
     * @return callable|string[]|string
     */
    public function getHandler();

    /**
     * @return string
     */
    public function getMethod();

    /**
     * @return \Interop\Http\ServerMiddleware\MiddlewareInterface[]|string[]
     */
    public function getMiddlewares();

    /**
     * Returns a regular expression from URI.
     *
     * @return string
     */
    public function getRegex();

    /**
     * @return string
     */
    public function getUri();
}
