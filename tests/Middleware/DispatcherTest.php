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
        $interface = 'Interop\Http\ServerMiddleware\MiddlewareInterface';

        $message = 'http-interop/http-middleware (v0.4.0) is not installed.';

        interface_exists($interface) || $this->markTestSkipped($message);

        $this->dispatcher = new Dispatcher;
    }
}
