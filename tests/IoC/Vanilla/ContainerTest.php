<?php

namespace Rougin\Slytherin\IoC\Vanilla;

use Rougin\Slytherin\Container\ReflectionContainer;
use Rougin\Slytherin\Fixture\Classes\AnotherClass;
use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Fixture\Classes\WithInterface;
use Rougin\Slytherin\Fixture\Classes\WithParameter;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ContainerTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\IoC\Vanilla\Container
     */
    protected $container;

    /**
     * @var string
     */
    protected $class = 'Rougin\Slytherin\Fixture\Classes\WithParameter';

    /**
     * @var \Rougin\Slytherin\Fixture\Classes\WithParameter
     */
    protected $instance;

    /**
     * @return void
     */
    public function test_failed_if_class_does_not_exist()
    {
        $expect = 'Rougin\Slytherin\Container\Exception\NotFoundException';

        $this->doSetExpectedException($expect);

        // Attempt to get a non-existent class ---
        $this->container->get('Rougin\Slytherin\Fixture\Classes\NonexistentClass');
        // ----------------------------------------
    }

    /**
     * @return void
     */
    public function test_failed_if_container_exception_raised()
    {
        $expect = 'Rougin\Slytherin\Container\Exception\ContainerException';

        $this->doSetExpectedException($expect);

        // Set a non-class value and attempt to retrieve it ---
        $this->container->set('Foo', array());

        $this->container->get('Foo');
        // ----------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_failed_if_reflection_container_error()
    {
        $container = new ReflectionContainer($this->container);

        $expect = 'Rougin\Slytherin\Container\Exception\NotFoundException';

        $this->doSetExpectedException($expect);

        // Attempt to get an unknown class via reflection ---
        $container->get('Test');
        // --------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_alias_set()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\WithInterface';

        $interface = 'Rougin\Slytherin\Fixture\Classes\NewInterface';

        // Register a class and create an alias ---
        $this->container->add($class, new $class);

        $this->container->alias($interface, $class);
        // ----------------------------------------

        // Verify the alias resolves correctly ---
        $this->assertTrue($this->container->has($interface));
        // ------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_class_added()
    {
        // Add a class with parameters ---
        $this->container->add($this->class, $this->instance);
        // -------------------------------

        // Verify the class was added ---
        $this->assertTrue($this->container->has($this->class));
        // -------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_class_exists()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        // Add a simple class ------------
        $this->container->add($class, new $class);
        // -------------------------------

        // Verify the class exists ---
        $this->assertTrue($this->container->has($class));
        // --------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_empty_constructor_added()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\WithEmptyConstructor';

        // Add a class with an empty constructor ---
        $this->container->add($class, new $class);
        // -----------------------------------------

        // Verify the class was added ---
        $this->assertTrue($this->container->has($class));
        // ---------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_interface_resolved()
    {
        $withParam = 'Rougin\Slytherin\Fixture\Classes\WithInterfaceParameter';

        $interface = 'Rougin\Slytherin\Fixture\Classes\NewInterface';

        // Resolve an interface and use it as a dependency ---
        $this->container->add($interface, new WithInterface);

        $this->container->add($withParam, $this->container->get($interface));
        // ---------------------------------------------------

        // Verify the dependent class was resolved ---
        $this->assertTrue($this->container->has($withParam));
        // ---------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_instance_retrieved()
    {
        // Add a class with parameters ------
        $this->container->add($this->class, $this->instance);
        // ----------------------------------

        // Verify the retrieved instance matches ---
        $expect = $this->instance;

        $actual = $this->container->get($this->class);

        $this->assertEquals($expect, $actual);
        // -------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_optional_parameter_added()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\WithOptionalParameter';

        // Add a class with an optional constructor parameter ---
        $this->container->add($class, new $class);
        // ------------------------------------------------------

        // Verify the class was added ---
        $this->assertTrue($this->container->has($class));
        // ---------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_simple_class_added()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        // Add a simple class without parameters ---
        $this->container->add($class, new $class);
        // -----------------------------------------

        // Verify the class was added ---
        $this->assertTrue($this->container->has($class));
        // ---------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_simple_class_resolved()
    {
        // Add a class with parameters ------
        $this->container->add($this->class, $this->instance);
        // ----------------------------------

        // Verify the class is resolvable ---
        $this->assertTrue($this->container->has($this->class));
        // -------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->container = new Container;

        $this->instance = new WithParameter(new NewClass, new AnotherClass);
    }
}
