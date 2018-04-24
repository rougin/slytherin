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
     * Sets up the middleware dispatcher instance.
     *
     * @return void
     */
    public function setUp()
    {
        $this->dispatcher = new Dispatcher;
    }
}
