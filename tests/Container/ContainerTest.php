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
    protected $container;

    /**
     * @return void
     */
    public function test_failed_if_container_exception_thrown()
    {
        $expect = 'Psr\Container\ContainerExceptionInterface';

        $this->doSetExpectedException($expect);

        // Set a non-class value as an instance ---
        $this->container->set('Test', array());
        // ----------------------------------------

        // Attempt to retrieve the invalid entry ---
        $this->container->get('Test');
        // -----------------------------------------
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
    public function test_passed_if_alias_set()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        // Register a class and create an alias ---
        $this->container->set($class, new $class);

        $this->container->alias('test', $class);
        // ----------------------------------------

        // Verify the alias resolves to the class -------
        $this->assertTrue($this->container->has('test'));
        // ----------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_instance_set()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        // Set a class instance in the container ---
        $this->container->set($class, new $class);
        // -----------------------------------------

        // Verify the instance is available -------------
        $this->assertTrue($this->container->has($class));
        // ----------------------------------------------
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
        $this->container = new Container;
    }
}
