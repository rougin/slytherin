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
        $builder = new Builder;

        $builder->setUrl('GET', '/v1/hello');

        $this->expectOutputString('Hello world!');

        $builder->make()->run();
    }
}