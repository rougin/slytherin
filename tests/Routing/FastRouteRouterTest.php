<?php

namespace Rougin\Slytherin\Routing;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class FastRouteRouterTest extends RouterTestCases
{
    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->checkIfFastRouteExists();

        $this->self = new FastRouteRouter($this->routes);
    }
}
