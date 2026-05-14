<?php

namespace Rougin\Slytherin\Container;

use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ContainerTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Container\Container
     */
    protected $self;

    /**
     * @return void
     */
    public function test_failed_if_container_exception_thrown()
    {
        $expect = 'Psr\Container\ContainerExceptionInterface';

        $this->doExpectException($expect);

        // Set a non-class value as an instance ---
        $this->self->set('Test', array());
        // ----------------------------------------

        // Attempt to retrieve the invalid entry ---
        $this->self->get('Test');
        // -----------------------------------------
    }

    /**
     * @return void
     */
    public function test_failed_if_not_found_exception_thrown()
    {
        $expect = 'Psr\Container\NotFoundExceptionInterface';

        $this->doExpectException($expect);

        // Attempt to get a non-existent class ---
        $class = 'Rougin\Slytherin\HelloWorld';

        $this->self->get($class);
        // ---------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_alias_set()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        // Register a class and create an alias ---
        $this->self->set($class, new $class);

        $this->self->alias('test', $class);
        // ----------------------------------------

        // Verify the alias resolves to the class ---
        $this->assertTrue($this->self->has('test'));
        // ------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_instance_set()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $this->self->set($class, new $class);

        $this->assertTrue($this->self->has($class));
    }

    /**
     * @return void
     */
    public function test_passed_if_simple_class_found()
    {
        $expect = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $this->self->set($expect, new $expect);

        // Verify the object is of the expected class ---
        $actual = $this->self->get($expect);

        $this->assertInstanceOf($expect, $actual);
        // ----------------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->self = new Container;
    }
}
