<?php

namespace Rougin\Slytherin\Component;

use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\System;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class CollectorTest extends Testcase
{
    /**
     * @return void
     */
    public function test_passed_if_middleware_collected()
    {
        $item = 'Rougin\Slytherin\Fixture\Components\MiddlewareComponent';

        // Define a middleware component to collect ---
        $collector = new Collector(array($item));

        $container = new Container;
        // --------------------------------------------

        // Make the collection from the container ---
        $collection = $collector->make($container);
        // ------------------------------------------

        // Verify the middleware component was registered ------
        $this->assertTrue($collection->has(System::MIDDLEWARE));
        // -----------------------------------------------------
    }
}
