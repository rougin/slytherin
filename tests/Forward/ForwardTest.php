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
    public function test_get_method()
    {
        $this->builder->setUrl('GET', '/');

        $this->expectOutputString('Hello');

        $this->builder->make()->run();
    }
}
