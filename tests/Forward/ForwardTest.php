<?php

namespace Rougin\Slytherin\Forward;

use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ForwardTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Forward\Builder
     */
    protected $builder;

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_callback_route_responded()
    {
        // Set up a callback-based route ---
        $this->builder->setUrl('GET', '/hello');

        $expect = 'Hello world!';
        $this->expectOutputString($expect);
        // --------------------------------

        // Run the built application ---
        $this->builder->make()->run();
        // -----------------------------
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_default_route_responded()
    {
        // Set up the default route ---
        $this->builder->setUrl('GET', '/');

        $expect = 'Hello';
        $this->expectOutputString($expect);
        // ----------------------------

        // Run the built application ---
        $this->builder->make()->run();
        // -----------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->builder = new Builder;
    }
}
