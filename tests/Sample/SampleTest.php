<?php

namespace Rougin\Slytherin\Forward;

use Rougin\Slytherin\Sample\Builder;
use Rougin\Slytherin\Sample\Handlers\Parsed;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class SampleTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Sample\Builder
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
    public function test_with_sample_text()
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
    public function test_with_arguments_in_uri()
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
    public function test_with_sest_depot_as_the_constructor()
    {
        $this->builder->setUrl('GET', '/');

        $this->expectOutputString('Welcome home!');

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_with_sest_depot_as_the_argument()
    {
        $this->builder->setUrl('GET', '/param');

        $this->expectOutputString('Welcome param!');

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_with_only_string_as_the_output()
    {
        $this->builder->setUrl('GET', '/string');

        $this->expectOutputString('This is a simple string.');

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_with_no_slash_in_the_route()
    {
        $this->builder->setUrl('GET', '/without-slash');

        $this->expectOutputString('This is a simple string.');

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_with_callable_as_the_route_and_only_as_the_output()
    {
        $this->builder->setUrl('GET', '/callable');

        $this->expectOutputString('Welcome call!');

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_with_middleware_changing_the_request_constructor()
    {
        $this->builder->addHandler(new Parsed);

        $this->builder->setUrl('GET', '/handler/conts');

        $this->expectOutputString('Hello, Slytherin!');

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_with_middleware_changing_the_request_parameter()
    {
        $this->builder->addHandler(new Parsed);

        $this->builder->setUrl('GET', '/handler/param');

        $this->expectOutputString('Hello, Slytherin!');

        $this->builder->make()->run();
    }
}