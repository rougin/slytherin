<?php

namespace Rougin\Slytherin\Middleware;

/**
 * Stratigility Dispatcher Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class StratigilityDispatcherTest extends DispatcherTestCases
{
    /**
     * Sets up the middleware dispatcher.
     *
     * @return void
     */
    public function setUp()
    {
        if (! class_exists('Zend\Stratigility\MiddlewarePipe')) {
            $this->markTestSkipped('Zend Stratigility is not installed.');
        }

        $pipe = new \Zend\Stratigility\MiddlewarePipe;

        $this->dispatcher = new StratigilityDispatcher($pipe);
    }
}
