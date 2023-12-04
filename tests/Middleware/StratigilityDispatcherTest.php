<?php

namespace Rougin\Slytherin\Middleware;

use Zend\Stratigility\MiddlewarePipe;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class StratigilityDispatcherTest extends DispatcherTestCases
{
    /**
     * Sets up the middleware dispatcher instance.
     *
     * @return void
     */
    protected function doSetUp()
    {
        if (! class_exists('Zend\Stratigility\MiddlewarePipe'))
        {
            $this->markTestSkipped('Zend Stratigility is not installed');
        }

        $pipe = new MiddlewarePipe;

        $this->dispatcher = new StratigilityDispatcher($pipe);
    }
}
