<?php

namespace Rougin\Slytherin\Routing;

/**
 * Router Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class RouterTest extends RouterTestCases
{
    /**
     * Sets up the router.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $this->router = new Router($this->routes);
    }
}
