<?php

namespace Rougin\Slytherin\Fixture\Routing;

use Rougin\Slytherin\Routing\RouteInterface;

/**
 * @codeCoverageIgnore
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class WeirdRoute implements RouteInterface
{
    /**
     * @return callable|string[]
     */
    public function getHandler()
    {
        return 'test';
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return 'GET';
    }

    /**
     * @return mixed[]
     */
    public function getMiddlewares()
    {
        return array();
    }

    /**
     * @return string[]
     */
    public function getParams()
    {
        return array();
    }

    /**
     * @return string
     */
    public function getRegex()
    {
        return '@^/$@D';
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return '/';
    }

    /**
     * @param string[] $params
     *
     * @return string
     */
    public function setParams($params)
    {
        return 'not-a-route';
    }
}
