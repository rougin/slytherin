<?php

namespace Rougin\Slytherin\Fixture\Routing;

use Rougin\Slytherin\Routing\FastRouteDispatcher;

/**
 * @codeCoverageIgnore
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class TestableFastRoute extends FastRouteDispatcher
{
    /**
     * @param object $fastroute
     *
     * @return void
     */
    public function setFastRoute($fastroute)
    {
        $this->fastroute = $fastroute;
    }
}
