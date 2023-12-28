<?php declare(strict_types = 1);

namespace Rougin\Slytherin\Routing;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class PhrouteRouterTest extends RouterTestCases
{
    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->exists('Rougin\Slytherin\Routing\PhrouteRouter');

        $this->router = new PhrouteRouter($this->routes);
    }
}
