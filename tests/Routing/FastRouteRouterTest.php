<?php

namespace Rougin\Slytherin\Routing;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class FastRouteRouterTest extends RouterTestCases
{
    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->exists('Rougin\Slytherin\Routing\FastRouteRouter');

        $this->router = new FastRouteRouter($this->routes);
    }
}
