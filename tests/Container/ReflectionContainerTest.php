<?php

namespace Rougin\Slytherin\Container;

use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ReflectionContainerTest extends Testcase
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $self;

    /**
     * @return void
     */
    public function test_failed_if_not_found_exception_thrown()
    {
        $expect = 'Psr\Container\NotFoundExceptionInterface';

        $this->doExpectException($expect);

        $this->self->get('Rougin\Slytherin\HelloWorld');
    }

    /**
     * @return void
     */
    public function test_passed_if_arguments_resolved_without_container()
    {
        $container = new ReflectionContainer;

        // Define a closure with default parameter values ------
        $fn = function ($name = 'Slytherin', $message = 'Hello')
        {
            /** @var string $name */
            /** @var string $message */
            return "$message $name";
        };
        // -----------------------------------------------------

        // Resolve the closure's arguments ----------
        $reflector = new \ReflectionFunction($fn);

        $args = $container->getArguments($reflector);
        // ------------------------------------------

        // Verify default values were resolved correctly ---
        $expect = array('Slytherin', 'Hello');

        $this->assertEquals($expect, $args);
        // -------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_constructed_without_container()
    {
        $container = new ReflectionContainer;

        $expect = 'Rougin\Slytherin\Container\ReflectionContainer';

        $this->assertInstanceOf($expect, $container);
    }

    /**
     * @return void
     */
    public function test_passed_if_multiple_params_resolved()
    {
        // Resolve a class with multiple constructor parameters ----
        $class = 'Rougin\Slytherin\Fixture\Classes\ParameterClass';

        $expect = 'With multiple parameters';

        /** @var \Rougin\Slytherin\Fixture\Classes\ParameterClass */
        $object = $this->self->get($class);

        $actual = $object->index();
        // ---------------------------------------------------------

        // Verify the parameters were correctly resolved ---
        $this->assertEquals($expect, $actual);
        // -------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_parameter_class_found()
    {
        $expect = 'Rougin\Slytherin\Fixture\Classes\WithParameter';

        $actual = $this->self->get($expect);

        $this->assertInstanceOf($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_simple_class_found()
    {
        $expect = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        // Resolve a simple class via reflection ---
        $actual = $this->self->get($expect);
        // -----------------------------------------

        // Verify the class was resolved correctly ---
        $this->assertInstanceOf($expect, $actual);
        // -------------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $delegate = new Container;

        $this->self = new ReflectionContainer($delegate);
    }
}
