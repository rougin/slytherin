<?php

namespace Rougin\Slytherin\Forward;

use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ForwardTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Forward\Builder
     */
    protected $builder;

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->builder = new Builder;
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_default_route()
    {
        $this->builder->setUrl('GET', '/');

        $this->expectOutputString('Hello');

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_route_as_callback()
    {
        $this->builder->setUrl('GET', '/hello');

        $this->expectOutputString('Hello world!');

        $this->builder->make()->run();
    }
}
