<?php declare(strict_types = 1);

namespace Rougin\Slytherin\Middleware;

use Zend\Stratigility\MiddlewarePipe;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class StratigilityDispatcherTest extends DispatcherTestCases
{
    /**
     * @return void
     */
    protected function doSetUp()
    {
        // @codeCoverageIgnoreStart
        if (! class_exists('Zend\Stratigility\MiddlewarePipe'))
        {
            $this->markTestSkipped('Zend Stratigility is not installed.');
        }
        // @codeCoverageIgnoreEnd

        $pipe = new MiddlewarePipe;

        $this->dispatcher = new StratigilityDispatcher($pipe);
    }
}
