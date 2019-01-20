<?php

namespace Rougin\Slytherin\Middleware;

/**
 * Dispatcher Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class DispatcherTest extends DispatcherTestCases
{
    /**
     * Sets up the middleware dispatcher instance.
     *
     * @return void
     */
    public function setUp()
    {
        if (! interface_exists('Rougin\Slytherin\Middleware\MiddlewareInterface'))
        {
            $this->markTestSkipped('MiddlewareInterface is not yet defined');
        }

        $this->dispatcher = new Dispatcher;
    }
}
