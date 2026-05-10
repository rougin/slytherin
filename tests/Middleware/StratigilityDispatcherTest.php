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
        // @codeCoverageIgnoreStart
        $this->checkIfStratigilityExists();
        // @codeCoverageIgnoreEnd

        $pipe = new MiddlewarePipe;

        $this->dispatcher = new StratigilityDispatcher($pipe);
    }
}
