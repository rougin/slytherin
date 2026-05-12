<?php

namespace Rougin\Slytherin\Fixture\Routing;

use Phroute\Phroute\RouteDataArray;
use Rougin\Slytherin\Routing\PhrouteDispatcher;

/**
 * @codeCoverageIgnore
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class TestablePhroute extends PhrouteDispatcher
{
    /**
     * @param \Phroute\Phroute\RouteDataArray $items
     *
     * @return void
     */
    public function setItems(RouteDataArray $items)
    {
        $this->items = $items;
    }
}
