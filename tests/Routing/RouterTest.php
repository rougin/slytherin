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
    public function setUp()
    {
        $this->router = new Router($this->routes);
    }
}
