<?php

namespace Rougin\Slytherin\Routing;

/**
 * Phroute Router Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class PhrouteRouterTest extends RouterTestCases
{
    /**
     * Sets up the router.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $this->exists('Rougin\Slytherin\Routing\PhrouteRouter');

        $this->router = new PhrouteRouter($this->routes);
    }
}
