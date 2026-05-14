<?php

namespace Rougin\Slytherin\Container;

use Auryn\Injector;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class AurynContainerTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Container\ContainerInterface
     */
    protected $self;

    /**
     * @return void
     */
    public function test_failed_if_container_exception_thrown()
    {
        $expect = 'Psr\Container\ContainerExceptionInterface';

        $this->doExpectException($expect);

        // Attempt to get an unregistered class ---
        $this->self->get('Test');
        // ----------------------------------------
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
    public function test_passed_if_instance_set()
    {
        $expect = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        // Set a class instance in the container ---
        $this->self->set($expect, new $expect);
        // -----------------------------------------

        // Verify the instance is retrievable ----
        $actual = $this->self->get($expect);

        $this->assertInstanceOf($expect, $actual);
        // ---------------------------------------
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
        $this->checkIfAurynExists();

        $this->self = new AurynContainer(new Injector);
    }
}
