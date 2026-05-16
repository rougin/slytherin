<?php

namespace Rougin\Slytherin\Previous;

use Rougin\Slytherin\Testcase;

/**
 * @deprecated since ~0.9, use "System\Routing" instead of "Application".
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class PreviousTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Previous\Builder
     */
    protected $builder;

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_middleware_responded()
    {
        $expect = 'Hello from middleware';

        $this->expectOutputString($expect);

        $this->builder->set('GET', '/hello')->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_sample_text_responded()
    {
        $expect = 'Hello world!';

        $this->expectOutputString($expect);

        $this->builder->set('GET', '/')->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_uri_arguments_responded()
    {
        $expect = 'Hello Rougin!';

        $this->expectOutputString($expect);

        $this->builder->set('GET', '/hi/Rougin')->run();
    }

    /**
     * @return void
     *
     * @codeCoverageIgnore
     */
    protected function doSetUp()
    {
        $this->checkIfAurynExists();

        $this->checkIfStratigilityExists();

        $this->checkIfDiactorosExists();

        $this->checkIfTwigExists();

        $this->builder = new Builder;
    }
}
