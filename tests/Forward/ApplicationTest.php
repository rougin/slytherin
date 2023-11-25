<?php

namespace Rougin\Slytherin\Forward;

use Rougin\Slytherin\Forward\Fixture\Builder;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ApplicationTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Forward\Fixture\Builder
     */
    protected $builder;

    protected function doSetUp()
    {
        $this->builder = new Builder;
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_run_with_sample_text()
    {
        $this->builder->setUrl('GET', '/hello');

        $this->expectOutputString('Hello world!');

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_run_with_arguments_in_uri()
    {
        $this->builder->setUrl('GET', '/hi/Rougin');

        $this->expectOutputString('Hello, Rougin!');

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_run_with_sest_depot_as_the_constructor()
    {
        $this->builder->setUrl('GET', '/');

        $this->expectOutputString('Welcome home!');

        $this->builder->make()->run();
    }
}