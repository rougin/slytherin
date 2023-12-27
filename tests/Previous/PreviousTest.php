<?php

namespace Rougin\Slytherin\Previous;

use Rougin\Slytherin\Previous\Builder;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class PreviousTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Previous\Builder
     */
    protected $builder;

    /**
     * @return void
     */
    protected function doSetUp()
    {
        // @codeCoverageIgnoreStart
        if (! class_exists('Auryn\Injector'))
        {
            $this->markTestSkipped('Auryn is not installed.');
        }

        if (! class_exists('Zend\Stratigility\MiddlewarePipe'))
        {
            $this->markTestSkipped('Zend Stratigility is not installed.');
        }

        if (! class_exists('Zend\Diactoros\Response'))
        {
            $this->markTestSkipped('Zend Diactoros is not installed.');
        }

        if (! class_exists('Twig_Loader_Filesystem'))
        {
            $this->markTestSkipped('Twig v1.0~v2.0 is not installed.');
        }
        // @codeCoverageIgnoreEnd

        $this->builder = new Builder;
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_sample_text()
    {
        $this->expectOutputString('Hello world!');

        $this->builder->make('GET', '/')->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_arguments_in_uri()
    {
        $this->expectOutputString('Hello Rougin!');

        $this->builder->make('GET', '/hi/Rougin')->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_middleware_changing_the_response_parameter()
    {
        $this->expectOutputString('Hello from middleware');

        $this->builder->make('GET', '/hello')->run();
    }
}
