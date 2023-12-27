<?php

namespace Rougin\Slytherin\Routing;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class RouterTest extends RouterTestCases
{
    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->router = new Router($this->routes);
    }
}
