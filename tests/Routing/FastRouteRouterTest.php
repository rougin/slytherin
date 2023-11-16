<?php

namespace Rougin\Slytherin\Routing;

/**
 * FastRoute Router Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class FastRouteRouterTest extends RouterTestCases
{
    /**
     * Sets up the router.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $this->exists('Rougin\Slytherin\Routing\FastRouteRouter');

        $this->router = new FastRouteRouter($this->routes);
    }
}
