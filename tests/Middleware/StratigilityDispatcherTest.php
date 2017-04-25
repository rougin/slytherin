<?php

namespace Rougin\Slytherin\Middleware;

/**
 * Stratigility Dispatcher Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class StratigilityDispatcherTest extends MiddlewareTestCases
{
    /**
     * Sets up the middleware dispatcher.
     *
     * @return void
     */
    public function setUp()
    {
        $pipe = new \Zend\Stratigility\MiddlewarePipe;

        $this->dispatcher = new StratigilityDispatcher($pipe);
    }
}
