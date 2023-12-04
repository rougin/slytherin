<?php

namespace Rougin\Slytherin\Middleware;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class DispatcherTest extends DispatcherTestCases
{
    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->dispatcher = new Dispatcher;
    }
}
