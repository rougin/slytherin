<?php

namespace Rougin\Slytherin\Middleware;

use Zend\Stratigility\MiddlewarePipe;

/**
 * Stratigility Dispatcher Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class StratigilityDispatcherTest extends DispatcherTestCases
{
    /**
     * Sets up the middleware dispatcher instance.
     *
     * @return void
     */
    public function setUp()
    {
        $class = (string) 'Zend\Stratigility\MiddlewarePipe';

        $message = 'Zend Stratigility is not installed';

        class_exists($class) || $this->markTestSkipped($message);

        $this->dispatcher = new StratigilityDispatcher(new MiddlewarePipe);
    }
}
