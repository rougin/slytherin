<?php

namespace Rougin\Slytherin\Routing;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class PhrouteRouterTest extends RouterTestCases
{
    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->checkIfPhrouteExists();

        $this->self = new PhrouteRouter($this->routes);
    }
}
