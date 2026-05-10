<?php

namespace Rougin\Slytherin\Middleware;

use Zend\Stratigility\MiddlewarePipe;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class StratigilityDispatcherTest extends DispatcherTestCases
{
    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->checkIfStratigilityExists();

        $pipe = new MiddlewarePipe;

        $this->self = new StratigilityDispatcher($pipe);
    }
}
