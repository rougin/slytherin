<?php

namespace Rougin\Slytherin\Middleware;

/**
 * Dispatcher Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class DispatcherTest extends DispatcherTestCases
{
    /**
     * Sets up the middleware dispatcher.
     *
     * @return void
     */
    public function setUp()
    {
        if (! interface_exists('Interop\Http\ServerMiddleware\MiddlewareInterface')) {
            $this->markTestSkipped('Interop Middleware is not installed.');
        }

        $this->dispatcher = new Dispatcher;
    }
}
