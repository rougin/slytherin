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
    public function test_making_with_middleware_component()
    {
        $items = array('Rougin\Slytherin\Fixture\Components\MiddlewareComponent');

        $collector = new Collector($items);

        $container = new Container;

        $collection = $collector->make($container);

        $this->assertTrue($collection->has(System::MIDDLEWARE));
    }
}
