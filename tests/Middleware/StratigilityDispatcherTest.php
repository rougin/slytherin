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

    /**
     * Tests StratigilityDispatcher::raiseThrowables.
     *
     * @return void
     */
    public function testCallMagicMethod()
    {
        $middleware = new \Rougin\Slytherin\Fixture\Middlewares\InteropMiddleware;

        $this->dispatcher->__call('pipe', array($middleware));

        $this->assertTrue(true);
    }
}
