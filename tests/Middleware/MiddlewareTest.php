<?php

namespace Rougin\Slytherin\Middleware;

/**
 * Middleware Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class MiddlewareTest extends MiddlewareTestCases
{
    /**
     * Sets up the middleware dispatcher.
     *
     * @return void
     */
    public function setUp()
    {
        $this->dispatcher = new Dispatcher;
    }
}
