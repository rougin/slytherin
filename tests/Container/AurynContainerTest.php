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
    protected $container;

    /**
     * @return void
     */
    public function test_failed_if_container_exception_thrown()
    {
        $expect = 'Psr\Container\ContainerExceptionInterface';

        $this->doSetExpectedException($expect);

        // Attempt to get an unregistered class ---
        $this->container->get('Test');
        // ----------------------------------------
    }

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
    public function test_passed_if_instance_set()
    {
        $expect = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        // Set a class instance in the container ---
        $this->container->set($expect, new $expect);
        // -----------------------------------------

        // Verify the instance is retrievable ----
        $actual = $this->container->get($expect);

        $this->assertInstanceOf($expect, $actual);
        // ---------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_simple_class_retrieved()
    {
        $expect = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        // Register the class instance -------------
        $this->container->set($expect, new $expect);
        // -----------------------------------------

        // Verify the object is of the expected class ---
        $actual = $this->container->get($expect);

        $this->assertInstanceOf($expect, $actual);
        // ----------------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->checkIfAurynExists();

        $this->container = new AurynContainer(new Injector);
    }
}
