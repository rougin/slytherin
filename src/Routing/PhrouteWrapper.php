<?php

namespace Rougin\Slytherin\Routing;

/**
 * Phroute Wrapper
 *
 * A simple class wrapper for resolving routes in Phroute.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 *
 * @link https://github.com/mrjgreen/phroute
 */
class PhrouteWrapper
{
    /** @var \Rougin\Slytherin\Routing\RouteInterface */
    protected $route;

    /**
     * @param \Rougin\Slytherin\Routing\RouteInterface $route
     */
    public function __construct(RouteInterface $route)
    {
        $this->route = $route;
    }

    /**
     * @param  string  $method
     * @param  mixed[] $params
     * @return mixed
     */
    public function __call($method, $params)
    {
        /** @var string[] $params */
        return $this->route->setParams($params);
    }
}
