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
    protected $container;

    /**
     * @return void
     */
    public function test_failed_if_not_found_exception_thrown()
    {
        $expect = 'Psr\Container\NotFoundExceptionInterface';

        $this->doSetExpectedException($expect);

        // Attempt to get a non-existent class ---
        $class = 'Rougin\Slytherin\HelloWorld';

        $this->container->get($class);
        // ---------------------------------------
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
        $object = $this->container->get($class);

        $actual = $object->index();
        // ---------------------------------------------------------

        // Verify the parameters were correctly resolved ---
        $this->assertEquals($expect, $actual);
        // -------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_parameter_class_retrieved()
    {
        $expect = 'Rougin\Slytherin\Fixture\Classes\WithParameter';

        // Resolve a class with a constructor parameter ---
        $actual = $this->container->get($expect);
        // ------------------------------------------------

        // Verify the class was resolved via reflection ---
        $this->assertInstanceOf($expect, $actual);
        // ------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_simple_class_retrieved()
    {
        $expect = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        // Resolve a simple class via reflection ---
        $actual = $this->container->get($expect);
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

        $this->container = new ReflectionContainer($delegate);
    }
}
